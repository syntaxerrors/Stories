// vi: noet

package ersatz

import (
	"sync"
	"log"
)

type subscriptions struct {
	key         string
	queues      []*queue
	countQueues []*queue
	lock        sync.RWMutex
	state       map[string]*event
}

var subs map[string]*subscriptions = make(map[string]*subscriptions)
var subsLock sync.RWMutex

func subscriptionsGet(key string) *subscriptions {
	subsLock.Lock()
	defer subsLock.Unlock()
	return subscriptionsGetAlreadyLocked(key)
}

func subscriptionsGetAlreadyLocked(key string) *subscriptions {
	sub := subs[key]
	if sub == nil {
		sub = &subscriptions{
			key:    key,
			queues: make([]*queue, 0, 10),
			state:  make(map[string]*event)}
		subs[key] = sub
	}
	return sub
}

func subscriptionsCount() int {
	subsLock.RLock()
	defer subsLock.RUnlock()
	return len(subs)
}

func subscriptionsListAll() map[string]subscriptionsStats {
	subsLock.RLock()
	defer subsLock.RUnlock()
	ret := make(map[string]subscriptionsStats)
	for k, s := range subs {
		ret[k] = s.stats()
	}
	return ret
}

func subscriptionsUnsubscribeFromAll(q *queue) {
	subsLock.Lock()
	defer subsLock.Unlock()
	for _, s := range subs {
		s.unsubscribe(q)
		s.unsubscribeCount(q)
	}
}

type subscriptionsStats struct {
	Objects     int      `json:"objects"`
	Queues      []string `json:"queues"`
	CountQueues []string `json:"countQueues"`
}

func (self *subscriptions) stats() subscriptionsStats {
	self.lock.RLock()
	defer self.lock.RUnlock()
	queues := make([]string, len(self.queues))
	for i, q := range self.queues {
		queues[i] = q.queueId
	}
	countQueues := make([]string, len(self.countQueues))
	for i, q := range self.countQueues {
		countQueues[i] = q.queueId
	}
	return subscriptionsStats{
		Objects:     len(self.state),
		Queues:      queues,
		CountQueues: countQueues}
}

func (self *subscriptions) publish(ev *event) {
	self.lock.RLock()
	defer self.lock.RUnlock()
	self.state[ev.Id] = ev
	for _, q := range self.queues {
		q.Publish(ev)
	}
	for _, q := range self.countQueues {
		q.PublishCount(self.key, len(self.state))
	}
}

func (self *subscriptions) unpublish(ev *event) {
	self.lock.RLock()
	defer self.lock.RUnlock()
	log.Println(self.state)
	log.Println(ev)
	delete(self.state, ev.Id)
	for _, q := range self.queues {
		q.Unpublish(ev)
	}
	for _, q := range self.countQueues {
		q.PublishCount(self.key, len(self.state))
	}
}

func (self *subscriptions) subscribe(q *queue) {
	self.lock.Lock()
	defer self.lock.Unlock()
	self.queues = append(self.queues, q)
	if len(self.state) > 0 {
		for _, ev := range self.state {
			q.Publish(ev)
		}
	}
}

func (self *subscriptions) subscribeCount(q *queue) {
	self.lock.Lock()
	defer self.lock.Unlock()
	self.countQueues = append(self.countQueues, q)
	if len(self.state) > 0 {
		q.PublishCount(self.key, len(self.state))
	}
}

func (self *subscriptions) unsubscribe(q *queue) {
	self.lock.Lock()
	defer self.lock.Unlock()
	idx := indexOfQueue(self.queues, q)
	if idx == -1 {
		return
	}
	self.queues[idx] = self.queues[len(self.queues)-1]
	self.queues = self.queues[:len(self.queues)-1]
}

func (self *subscriptions) unsubscribeCount(q *queue) {
	self.lock.Lock()
	defer self.lock.Unlock()
	idx := indexOfQueue(self.countQueues, q)
	if idx == -1 {
		return
	}
	self.countQueues[idx] = self.countQueues[len(self.countQueues)-1]
	self.countQueues = self.countQueues[:len(self.countQueues)-1]
}

func indexOfQueue(queues []*queue, q *queue) int {
	for i, v := range queues {
		if v == q {
			return i
		}
	}
	return -1
}
