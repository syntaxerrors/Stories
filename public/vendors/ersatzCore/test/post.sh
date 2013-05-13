#!/bin/sh
if [ ! "$1" ]; then
    echo "missing file"
    exit 1
fi
curl -XPOST -H "Content-Type: application/json" -d @"$1" http://devapp151.softlayer.local:12302/post
echo
