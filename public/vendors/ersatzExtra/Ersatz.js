function Ersatz(settings) {
    this.server = settings.server;
    this.event = settings.event;
    this.count = settings.count;
    this.error = settings.error;
    this.postEvent = settings.postEvent;
    this.subscriptions = settings.subscriptions;
    this.countSubscriptions = settings.countSubscriptions;
    this.batchSize = settings.batchSize || 100;
    this.delayMs = settings.delayMs || 10;
    this.queueId = null;
    this.first = true;
    this.start();
}

Ersatz.prototype.start = function () {
    var self = this;
    if (this.queueId == null) {
        this.queueId = this.randomQueueId();
        $.ajax("http://"+this.server+"/queue/"+this.queueId, {
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                "subscriptions": this.subscriptions,
                "countSubscriptions": this.countSubscriptions,
            }),
            success: function (data, textStatus, jqxhr) {
                self.start();
            },
            error: function (jqxhr, textStatus, errorThrown) {
                if (self.error) {
                    self.error(errorThrown);
                } else {
                    alert("Ersatz Error: "+errorThrown);
                }
            },
        });
    } else {
        $.ajax({
            url: "http://"+this.server+"/queue/"+this.queueId,
            type: "GET",
            dataType: "json",
            success: function (data, textStatus, jqxhr) {
                self.deliver(data, function () {
                    self.start();
                });
            },
            error: function (jqxhr, textStatus, errorThrown) {
                if (self.error) {
                    self.error(errorThrown);
                } else {
                    alert("Ersatz Error: "+errorThrown);
                }
            },
        });
    }
}

Ersatz.prototype.deliver = function (events, completeCallback) {
    var self = this;
    deferredForEach(
        events,
        function (ev) {
            if ('count' in ev) {
                $.each(ev.count, function (key, count) {
                    self.count(key, count);
                });
            } else {
                self.event(ev, self.first);
            }
        },
        function () {
            if (self.postEvent) {
                self.postEvent();
            }
        },
        function () {
            self.first = false;
            completeCallback();
        },
        this.batchSize, this.delayMs);
}

function deferredForEach(elems, elemCallback, batchCallback, completeCallback, batchSize, delayMs) {
    for (var i = 0; i < batchSize && i < elems.length; i++) {
        elemCallback(elems[i]);
    }
    batchCallback();
    if (batchSize >= elems.length) {
        completeCallback();
    } else {
        var remainingElems = elems.slice(batchSize);
        setTimeout(function () {
            deferredForEach(remainingElems, elemCallback, batchCallback, completeCallback, batchSize, delayMs);
        }, delayMs);
    }
}

Ersatz.prototype.randomQueueId = function () {
    var possible = "abcdef0123456789";
    var text = "";
    for (var i = 0; i < 16; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}
