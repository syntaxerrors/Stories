#!/bin/sh
cd `dirname $0`
set -e
go fmt main.go
(cd src && find * -type d) | xargs go fmt
