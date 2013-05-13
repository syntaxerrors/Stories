// vi: noet

package ersatz

import (
	"log"
	"net/http"
	"runtime"
	"sort"
	"strings"
	"time"
)

type handler struct{}

func (self *handler) ServeHTTP(response http.ResponseWriter, request *http.Request) {
	log.Printf("%s %s", request.Method, request.URL.Path)
	switch {
	case request.Method == "OPTIONS":
		writeCorsResponse(response)
	case request.Method == "POST" && contentType(request) != "application/json":
		notAcceptable(response, "POST requires \"Content-Type: application/json\".")
	case request.URL.Path == "/dump":
		if request.Method == "GET" {
			handleDump(response)
		} else {
			methodNotAllowed(response, "Only GET is allowed.")
		}
	case request.URL.Path == "/post":
		if request.Method == "POST" {
			handlePost(response, request)
		} else {
			methodNotAllowed(response, "Only POST is allowed.")
		}
	case strings.HasPrefix(request.URL.Path, "/queue/"):
		queueId := request.URL.Path[7:]
		if queueId == "" || strings.Index(queueId, "/") != -1 {
			notFound(response, "Queue names must be non-empty and may not contain \"/\".")
		} else {
			if request.Method == "POST" {
				handleQueueCreate(response, request, queueId)
			} else if request.Method == "GET" {
				handleQueueGet(response, queueId)
			} else if request.Method == "DELETE" {
				handleQueueDelete(response, queueId)
			} else {
				methodNotAllowed(response, "Only GET, POST, and DELETE are allowed.")
			}
		}
	case request.URL.Path == "/queue":
		handleQueueList(response)
	case request.URL.Path == "/subscription":
		handleSubscriptionList(response)
	case strings.HasPrefix(request.URL.Path, "/object/"):
		objectId := request.URL.Path[8:]
		handleObject(response, objectId)
	case request.URL.Path == "/status":
		handleStatus(response)
	case request.URL.Path == "/config":
		handleConfig(response)
	default:
		notFound(response, "Unknown path.")
	}
}

func writeCorsResponse(response http.ResponseWriter) {
	response.Header().Add("Access-Control-Allow-Origin", "*")
	response.Header().Add("Access-Control-Allow-Headers", "accept, origin, content-type")
	response.WriteHeader(200)
	_, err := response.Write([]byte(""))
	if err != nil {
		log.Println("ERROR writing to client:", err)
	}
}

func handleDump(response http.ResponseWriter) {
	snapshot := stateSnapshot()
	keys := make([]string, 0, len(snapshot))
	for k := range snapshot {
		keys = append(keys, k)
	}
	sort.Strings(keys)
	ret := make([]*event, 0, len(snapshot))
	for _, k := range keys {
		ret = append(ret, snapshot[k])
	}
	writeJson(response, ret)
}

func handlePost(response http.ResponseWriter, request *http.Request) {
	var eventData []*event
	err := readJsonBody(request, &eventData)
	if err != nil {
		log.Println(err)
		badRequest(response, err.Error())
		return
	}
	go routeAll(eventData)
	success(response)
}

type queueCreateParams struct {
	Subscriptions      []string `json:"subscriptions"`
	CountSubscriptions []string `json:"countSubscriptions"`
}

func handleQueueCreate(response http.ResponseWriter, request *http.Request, queueId string) {
	var params queueCreateParams
	err := readJsonBody(request, &params)
	if err != nil {
		log.Println(err)
		badRequest(response, err.Error())
		return
	}
	q, err := queueCreate(queueId)
	if err != nil {
		conflict(response, "Queue already exists.")
		return
	}
	for _, key := range params.Subscriptions {
		subscriptionsGet(key).subscribe(q)
	}
	for _, key := range params.CountSubscriptions {
		subscriptionsGet(key).subscribeCount(q)
	}
	created(response)
}

func handleQueueDelete(response http.ResponseWriter, queueId string) {
	findErr, deleteErr := queueDelete(queueId, "api")
	if findErr != nil {
		notFound(response, "No such queue.")
	} else if deleteErr != nil {
		internalServerError(response, deleteErr.Error())
	} else {
		success(response)
	}
}

func handleQueueGet(response http.ResponseWriter, queueId string) {
	q := queueGet(queueId)
	if q == nil {
		notFound(response, "Queue does not exist.")
		return
	}
	events, counts := q.WaitForEvents(config.GetTimeout)
	if len(counts) == 0 {
		writeJson(response, events)
	} else {
		out := make([]interface{}, len(events)+1)
		for i := range events {
			out[i] = events[i]
		}
		out[len(events)] = &map[string]interface{}{"count": counts}
		writeJson(response, out)
	}
}

func handleQueueList(response http.ResponseWriter) {
	qm := queueListAll()
	writeJson(response, qm)
}

func handleSubscriptionList(response http.ResponseWriter) {
	writeJson(response, subscriptionsListAll())
}

func handleObject(response http.ResponseWriter, objectId string) {
	obj := stateGet(objectId)
	if obj != nil {
		writeJson(response, obj)
	} else {
		notFound(response, "No such object id.")
	}
}

func handleStatus(response http.ResponseWriter) {
	queues := queueCount()
	subscriptions := subscriptionsCount()
	objects := stateCount()
	uptime := time.Now().Sub(startupTime)
	writeJson(response, &map[string]interface{}{
		"startupAt":        startupTime.Format(time.RFC3339),
		"startupTimestamp": startupTime.Unix(),
		"uptimeSeconds":    uptime.Seconds(),
		"uptime":           uptime.String(),
		"queues":           queues,
		"subscriptions":    subscriptions,
		"objects":          objects,
		"goroutines":       runtime.NumGoroutine()})
}

func handleConfig(response http.ResponseWriter) {
	writeJson(response, config)
}
