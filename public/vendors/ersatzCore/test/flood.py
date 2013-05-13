#!/usr/bin/env python
import random
import httplib
import time
try:
    import json
except ImportError:
    import simplejson as json

host = "localhost"
port = 12302

num_objects = 10000
num_fields = 200
field_len = 100
num_keys = 100
keys_per_object = 5
move_probability = 0.05
batch_size = 1

def randomstring(n):
    return ''.join([random.choice("abcdefghijklmnopqrstuvwxyz012345") for i in xrange(n)])

data = dict([('value-%i' % i,randomstring(field_len)) for i in xrange(num_fields)])


conn = httplib.HTTPConnection(host, port)

where = dict([(i, i % num_keys) for i in xrange(num_objects)])

batch = []
while True:
    id = random.randrange(num_objects)
    if random.random() < move_probability:
        keyvalue = random.randrange(num_keys)
    else:
        keyvalue = where[id]
    obj = {
        'id': 'TestObject:%i' % id,
        'type': 'TestObject',
        'keys': {'test': 'value-%i' % keyvalue },
        'data': data,
    }
    batch.append(obj)
    if len(batch) < batch_size:
        continue
    #import pprint
    #pprint.pprint(batch)
    objJson = json.dumps(batch)
    batch = []
    start = time.time()
    conn.request("POST", '/post', objJson, {'Content-Type': 'application/json'})
    resp = conn.getresponse()
    if resp.status != 200:
        raise ValueError("bad status %i: %r" % (resp.status, resp.read()))
    resp.read()
    end = time.time()
    print end-start
