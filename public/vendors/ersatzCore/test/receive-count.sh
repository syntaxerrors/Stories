#!/bin/sh
if [ ! "$1" ]; then
    echo "missing key"
    exit
fi
KEY="$1"
set -e
curl -XPOST -H "Content-Type: application/json" \
    -d '{"countSubscriptions":{"group":["ABUSE"]}}' \
    http://devapp151.softlayer.local:12302/queue/$KEY
echo

while :; do
    echo "Requesting..."
    curl http://devapp151.softlayer.local:12302/queue/$KEY
    echo
done
