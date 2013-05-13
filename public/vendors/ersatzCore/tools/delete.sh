#!/bin/sh
curl -XDELETE -H "Content-Type: application/json" -d @dump.json http://devapp151.softlayer.local:12302/queue/$1
echo
