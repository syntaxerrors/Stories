// vi: noet

package main

import (
	"ersatz"
	"flag"
	"log"
	"runtime"
)

func main() {
	cfg := ersatz.DefaultConfig()

	var maxProcs int
	flag.IntVar(&maxProcs, "maxprocs", 0, "GOMAXPROCS")
	flag.StringVar(&cfg.Host, "host", cfg.Host, "bind ip")
	flag.StringVar(&cfg.Host, "h", cfg.Host, "bind ip")
	flag.IntVar(&cfg.Port, "port", cfg.Port, "bind port")
	flag.IntVar(&cfg.Port, "p", cfg.Port, "bind port")
	flag.DurationVar(&cfg.GetTimeout, "getTimeout", cfg.GetTimeout, "timeout for receiving messages")
	flag.DurationVar(&cfg.DelTimeout, "delTimeout", cfg.DelTimeout, "timeout for deleting idle queues")

	flag.Parse()

	runtime.GOMAXPROCS(maxProcs)

	err := ersatz.Main(cfg)
	if err != nil {
		log.Fatal(err)
	}
}
