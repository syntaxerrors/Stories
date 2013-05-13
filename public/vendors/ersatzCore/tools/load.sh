#!/bin/sh
curl -XPOST -H "Content-Type: application/json" -d @dump.json http://devapp151.softlayer.local:12302/post
echo
