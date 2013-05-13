# Ersatz - real-time data updates in the browser
----
## What is Ersatz?

It's what you use when:

* You've got a small (<100k) set of objects,
* Encodable in JSON,
* That you want to display in a browser,
* And have them update in real time.

----
## How does this work?

Ersatz maintains in memory a collection of objects sent to it by your backend
system, and streams changes directly to browsers through
[COMET](http://en.wikipedia.org/wiki/Comet_%28programming%29) with
[CORS](http://en.wikipedia.org/wiki/Cross-origin_resource_sharing).  Any time
your backend system changes something, it can publish the change to every
listening browser in seconds (usually a lot less).

----
## Where's the documentation

Uh, I'm working on that.

----
## Caveats

* Your set of objects *must* be small enough to fit in a reasonable amount of memory.
* The rate at which these objects update must not overwhelm the browser's ability to repaint.
* Updates to objects are independent. (Ersatz doesn't guarantee strict ordering of updates.)
