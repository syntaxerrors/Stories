// vi: noet

package ersatz

import (
	"fmt"
	"log"
	"sync"
	"time"
)

type queue struct {
	queueId    string
	hasChanges bool
	pending    []*eventWithDelta
	current    map[string]*event
	counts     map[string]int
	// internal request channels
	stats        chan queueStatsRequest
	publish      chan *event
	unpublish    chan *event
	publishCount chan queuePublishCount
	receive      chan queueReceiveRequest
	shutdown     chan queueShutdownRequest
	// internal variables
	waiting        chan queueReceiveResponse
	timeoutWaiting <-chan time.Time
	timeoutIdle    <-chan time.Time
}

type queueStatsRequest struct {
	response chan queueStats
}

type queueStats struct {
	Pending int  `json:"pending"`
	Current int  `json:"current"`
	Waiting bool `json:"waiting"`
}

type queuePublishCount struct {
	key   string
	count int
}

type queueReceiveRequest struct {
	timeout  time.Duration
	response chan queueReceiveResponse
}

type queueReceiveResponse struct {
	events []*eventWithDelta
	counts map[string]int
}

type queueShutdownRequest struct {
	response chan error
}

var queueMap map[string]*queue = make(map[string]*queue)
var queueMapLock sync.RWMutex

func queueGet(queueId string) *queue {
	queueMapLock.RLock()
	defer queueMapLock.RUnlock()
	return queueMap[queueId]
}

func queueCreate(queueId string) (*queue, error) {
	queueMapLock.Lock()
	defer queueMapLock.Unlock()
	if queueMap[queueId] != nil {
		return nil, fmt.Errorf("queue already exists")
	}
	log.Printf("Queue %s: Created", queueId)
	q := &queue{
		queueId:        queueId,
		hasChanges:     false,
		pending:        make([]*eventWithDelta, 0, 10),
		current:        make(map[string]*event),
		counts:         make(map[string]int),
		stats:          make(chan queueStatsRequest),
		publish:        make(chan *event),
		unpublish:      make(chan *event),
		publishCount:   make(chan queuePublishCount),
		receive:        make(chan queueReceiveRequest),
		shutdown:       make(chan queueShutdownRequest),
		waiting:        nil,
		timeoutWaiting: nil,
		timeoutIdle:    nil}
	queueMap[queueId] = q
	go q.run()
	return q, nil
}

func queueDelete(queueId, reason string) (findError, deleteError error) {
	queueMapLock.Lock()
	defer queueMapLock.Unlock()
	q := queueMap[queueId]
	if q == nil {
		return fmt.Errorf("Can't delete queue %s: No such queue.", queueId), nil
	}
	subscriptionsUnsubscribeFromAll(q)
	err := q.Shutdown()
	if err == nil {
		delete(queueMap, queueId)
	}
	log.Printf("Queue %s: Deleted (%s).", queueId, reason)
	return nil, err
}

func queueListAll() map[string]queueStats {
	ret := make(map[string]queueStats)
	queueMapLock.RLock()
	defer queueMapLock.RUnlock()
	for k, q := range queueMap {
		ret[k] = q.Stats()
	}
	return ret
}

func queueCount() int {
	queueMapLock.RLock()
	defer queueMapLock.RUnlock()
	return len(queueMap)
}

func (self *queue) Stats() queueStats {
	response := make(chan queueStats)
	self.stats <- queueStatsRequest{response: response}
	return <-response
}

func (self *queue) Publish(ev *event) {
	self.publish <- ev
}

func (self *queue) Unpublish(ev *event) {
	self.unpublish <- ev
}

func (self *queue) PublishCount(key string, count int) {
	self.publishCount <- queuePublishCount{key: key, count: count}
}

func (self *queue) WaitForEvents(timeout time.Duration) ([]*eventWithDelta, map[string]int) {
	response := make(chan queueReceiveResponse)
	self.receive <- queueReceiveRequest{timeout: timeout, response: response}
	ret := <-response
	return ret.events, ret.counts
}

func (self *queue) Shutdown() error {
	response := make(chan error)
	self.shutdown <- queueShutdownRequest{response: response}
	return <-response
}

func (self *queue) run() {
	self.timeoutIdle = time.After(config.DelTimeout)
	for {
		select {
		case request := <-self.stats:
			request.response <- queueStats{
				Pending: len(self.pending),
				Current: len(self.current),
				Waiting: self.waiting != nil}
		case ev := <-self.publish:
			if self.current[ev.Id] != ev {
				delta := eventDelta(self.current[ev.Id], ev)
				self.current[ev.Id] = ev
				self.pending = append(self.pending, delta)
				self.hasChanges = true
				self.flush()
			}
		case ev := <-self.unpublish:
			if self.current[ev.Id] != ev {
				delta := eventDelta(self.current[ev.Id], ev)
				delete(self.current, ev.Id)
				self.pending = append(self.pending, delta)
				self.hasChanges = true
				self.flush()
			}
		case kc := <-self.publishCount:
			if self.counts[kc.key] != kc.count {
				self.counts[kc.key] = kc.count
				self.hasChanges = true
				self.flush()
			}
		case request := <-self.receive:
			if self.waiting != nil {
				self.flush()
			}
			self.waiting = request.response
			self.timeoutWaiting = time.After(request.timeout)
			self.timeoutIdle = nil
			if self.hasChanges {
				self.flush()
			}
		case request := <-self.shutdown:
			if self.waiting != nil {
				self.flush()
			}
			request.response <- nil
			return
		case <-self.timeoutWaiting:
			self.flush()
		case <-self.timeoutIdle:
			log.Printf("Queue %s: Timeout; deleting...", self.queueId)
			self.timeoutIdle = time.After(config.DelTimeout)
			go queueDelete(self.queueId, "idle timeout")
		}
	}
}

func (self *queue) flush() {
	if self.waiting == nil {
		return
	}
	events := self.pending
	self.pending = make([]*eventWithDelta, 0, 10)
	counts := self.dumpCounts()
	self.waiting <- queueReceiveResponse{events: events, counts: counts}
	self.waiting = nil
	self.timeoutWaiting = nil
	self.timeoutIdle = time.After(config.DelTimeout)
	self.hasChanges = false
}

func (self *queue) dumpCounts() map[string]int {
	var counts map[string]int = make(map[string]int)
	for key, count := range self.counts {
		counts[key] = count
	}
	return counts
}
