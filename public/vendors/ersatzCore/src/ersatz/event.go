// vi: noet

package ersatz

import (
	"reflect"
	"sort"
	"sync"
)

type event struct {
	Id   string                 `json:"id"`
	Type string                 `json:"type"`
	Keys []string               `json:"keys"`
	Data map[string]interface{} `json:"data"`
}

type eventWithDelta struct {
	Id   string                 `json:"id"`
	Type string                 `json:"type"`
	Keys []string               `json:"keys"`
	Data map[string]interface{} `json:"data"`
	Prev map[string]interface{} `json:"prev"`
}

func eventDelta(evold, evnew *event) *eventWithDelta {
	prev := make(map[string]interface{})
	if evold != nil {
		if evold.Id != evnew.Id {
			panic("eventDelta id mismatch")
		}
		for k, v := range evnew.Data {
			if !reflect.DeepEqual(evold.Data[k], v) {
				prev[k] = evold.Data[k]
			}
		}
		// Note: ignoring added/removed keys
	}
	return &eventWithDelta{
		Id:   evnew.Id,
		Type: evnew.Type,
		Keys: evnew.Keys,
		Data: evnew.Data,
		Prev: prev}
}

func eventKeyDelta(evold, evnew *event) (added, removed []string) {
	added = make([]string, 0)
	removed = make([]string, 0)
	sort.Strings(evnew.Keys)
	for _, key := range evnew.Keys {
		added = append(added, key)
	}
	if evold != nil {
		for _, key := range evold.Keys {
			i := sort.SearchStrings(evnew.Keys, key)
			if i >= len(evnew.Keys) || evnew.Keys[i] != key {
				removed = append(removed, key)
			}
		}
	}
	return
}

var state map[string]*event = make(map[string]*event)
var stateLock sync.RWMutex

func stateCount() int {
	stateLock.RLock()
	defer stateLock.RUnlock()
	return len(state)
}

func stateGet(objectId string) *event {
	stateLock.RLock()
	defer stateLock.RUnlock()
	return state[objectId]
}

func stateSnapshot() map[string]*event {
	stateLock.RLock()
	defer stateLock.RUnlock()
	ret := make(map[string]*event)
	for k, v := range state {
		ret[k] = v
	}
	return ret
}

func stateReplace(evnew *event) *event {
	stateLock.Lock()
	defer stateLock.Unlock()
	id := evnew.Id
	evold := state[id]
	if len(evnew.Keys) > 0 {
		state[id] = evnew
	} else {
		delete(state, id)
	}
	return evold
}
