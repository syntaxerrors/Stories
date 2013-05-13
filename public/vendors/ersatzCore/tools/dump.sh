#!/bin/sh
curl http://devapp151.softlayer.local:12301/dump | sed 's/},{/},\n{/g' >dump.json
echo
