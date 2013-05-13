// vi: noet

package ersatz

import (
	"encoding/json"
	"errors"
	"io/ioutil"
	"log"
	"net/http"
	"strings"
)

func contentType(request *http.Request) string {
	ctype := request.Header.Get("Content-Type")
	if ctype == "" {
		return ""
	}
	idx := strings.Index(ctype, ";")
	if idx == -1 {
		return ctype
	}
	return ctype[:idx]
}

func writeJson(response http.ResponseWriter, obj interface{}) {
	bytes, err := json.Marshal(obj)
	if err != nil {
		log.Println("ERROR in json output:", err)
		internalServerError(response, "Error in json output: "+err.Error())
	} else {
		writeJsonBytes(response, http.StatusOK, bytes)
	}
}

func success(response http.ResponseWriter) {
	writeJsonBytes(response, http.StatusOK, []byte("{\"success\":\"OK\"}"))
}

func created(response http.ResponseWriter) {
	writeJsonBytes(response, http.StatusCreated, []byte("{\"success\":\"Created\"}"))
}

func writeError(response http.ResponseWriter, code int, message string) {
	bytes, err := json.Marshal(&map[string]interface{}{
		"error":   http.StatusText(code),
		"message": message})
	if err != nil {
		log.Println("ERROR in json output:", err)
		internalServerError(response, "Error in json formatting: "+err.Error())
	} else {
		writeJsonBytes(response, code, bytes)
	}
}

func badRequest(response http.ResponseWriter, message string) {
	writeError(response, http.StatusBadRequest, message)
}

func notFound(response http.ResponseWriter, message string) {
	writeError(response, http.StatusNotFound, message)
}

func methodNotAllowed(response http.ResponseWriter, message string) {
	writeError(response, http.StatusMethodNotAllowed, message)
}

func notAcceptable(response http.ResponseWriter, message string) {
	writeError(response, http.StatusNotAcceptable, message)
}

func conflict(response http.ResponseWriter, message string) {
	writeError(response, http.StatusConflict, message)
}

func internalServerError(response http.ResponseWriter, message string) {
	writeError(response, http.StatusInternalServerError, message)
}

func writeJsonBytes(response http.ResponseWriter, code int, bytes []byte) {
	response.Header().Add("Content-Type", "application/json")
	response.Header().Add("Access-Control-Allow-Origin", "*")
	response.Header().Add("Cache-Control", "no-cache")
	response.WriteHeader(code)
	_, err := response.Write(bytes)
	if err != nil {
		log.Println("ERROR writing to client:", err)
	}
}

func readJsonBody(request *http.Request, into interface{}) error {
	bodyData, err := ioutil.ReadAll(request.Body)
	if err != nil {
		return errors.New("Error reading request body: " + err.Error())
	}
	err = json.Unmarshal(bodyData, into)
	if err != nil {
		return errors.New("Error parsing request json: " + err.Error())
	}
	return nil
}
