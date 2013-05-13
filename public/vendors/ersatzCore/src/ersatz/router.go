// vi: noet

package ersatz

func routeAll(events []*event) {
	for _, ev := range events {
		route(ev)
	}
}

func route(ev *event) {
	evold := stateReplace(ev)
	added, removed := eventKeyDelta(evold, ev)
	subsLock.RLock()
	defer subsLock.RUnlock()
	for _, kv := range removed {
		sub := subscriptionsGetAlreadyLocked(kv)
		sub.unpublish(ev)
	}
	for _, kv := range added {
		sub := subscriptionsGetAlreadyLocked(kv)
		sub.publish(ev)
	}
}
