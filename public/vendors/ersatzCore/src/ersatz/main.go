// vi: noet

package ersatz

import (
	"log"
	"net"
	"net/http"
	"time"
)

var startupTime time.Time
var shutdownChannel chan bool

type Config struct {
	Host       string
	Port       int
	GetTimeout time.Duration
	DelTimeout time.Duration
}

func DefaultConfig() Config {
	return Config{
		Host:       "0.0.0.0",
		Port:       12301,
		GetTimeout: 15 * time.Second,
		DelTimeout: 60 * time.Second}
}

var config Config

func Main(cfg Config) error {
	config = cfg
	log.Println("Starting.")
	startupTime = time.Now()

	shutdownChannel = make(chan bool)

	log.Printf("Binding to %s:%d", config.Host, config.Port)
	listenAddress := &net.TCPAddr{IP: net.ParseIP(config.Host), Port: config.Port}
	listener, err := net.ListenTCP("tcp", listenAddress)
	if err != nil {
		return err
	}

	log.Println("Ready.")
	go http.Serve(listener, &handler{})
	<-shutdownChannel
	return nil
}
