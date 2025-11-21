! function(t, e) {
    "function" == typeof define && define.amd ? define("bloodhound", ["jquery"], function(n) {
        return t.Bloodhound = e(n)
    }) : "object" == typeof exports ? module.exports = e(require("jquery")) : t.Bloodhound = e(jQuery)
}(this, function(t) {
    var e = function() {
            "use strict";
            return {
                isMsie: function() {
                    return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : !1
                },
                isBlankString: function(t) {
                    return !t || /^\s*$/.test(t)
                },
                escapeRegExChars: function(t) {
                    return t.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&")
                },
                isString: function(t) {
                    return "string" == typeof t
                },
                isNumber: function(t) {
                    return "number" == typeof t
                },
                isArray: t.isArray,
                isFunction: t.isFunction,
                isObject: t.isPlainObject,
                isUndefined: function(t) {
                    return "undefined" == typeof t
                },
                isElement: function(t) {
                    return !(!t || 1 !== t.nodeType)
                },
                isJQuery: function(e) {
                    return e instanceof t
                },
                toStr: function(t) {
                    return e.isUndefined(t) || null === t ? "" : t + ""
                },
                bind: t.proxy,
                each: function(e, n) {
                    function i(t, e) {
                        return n(e, t)
                    }
                    t.each(e, i)
                },
                map: t.map,
                filter: t.grep,
                every: function(e, n) {
                    var i = !0;
                    return e ? (t.each(e, function(t, r) {
                        return (i = n.call(null, r, t, e)) ? void 0 : !1
                    }), !!i) : i
                },
                some: function(e, n) {
                    var i = !1;
                    return e ? (t.each(e, function(t, r) {
                        return (i = n.call(null, r, t, e)) ? !1 : void 0
                    }), !!i) : i
                },
                mixin: t.extend,
                identity: function(t) {
                    return t
                },
                clone: function(e) {
                    return t.extend(!0, {}, e)
                },
                getIdGenerator: function() {
                    var t = 0;
                    return function() {
                        return t++
                    }
                },
                templatify: function(e) {
                    function n() {
                        return String(e)
                    }
                    return t.isFunction(e) ? e : n
                },
                defer: function(t) {
                    setTimeout(t, 0)
                },
                debounce: function(t, e, n) {
                    var i, r;
                    return function() {
                        var u, o, s = this,
                            a = arguments;
                        return u = function() {
                            i = null, n || (r = t.apply(s, a))
                        }, o = n && !i, clearTimeout(i), i = setTimeout(u, e), o && (r = t.apply(s, a)), r
                    }
                },
                throttle: function(t, e) {
                    var n, i, r, s, a, u;
                    return a = 0, u = function() {
                        a = new Date, r = null, s = t.apply(n, i)
                    },
                        function() {
                            var o = new Date,
                                c = e - (o - a);
                            return n = this, i = arguments, 0 >= c ? (clearTimeout(r), r = null, a = o, s = t.apply(n, i)) : r || (r = setTimeout(u, c)), s
                        }
                },
                stringify: function(t) {
                    return e.isString(t) ? t : JSON.stringify(t)
                },
                noop: function() {}
            }
        }(),
        n = "0.11.1",
        i = function() {
            "use strict";

            function t(t) {
                return t = e.toStr(t), t ? t.split(/\s+/) : []
            }

            function n(t) {
                return t = e.toStr(t), t ? t.split(/\W+/) : []
            }

            function i(t) {
                return function(n) {
                    return n = e.isArray(n) ? n : [].slice.call(arguments, 0),
                        function(i) {
                            var r = [];
                            return e.each(n, function(n) {
                                r = r.concat(t(e.toStr(i[n])))
                            }), r
                        }
                }
            }
            return {
                nonword: n,
                whitespace: t,
                obj: {
                    nonword: i(n),
                    whitespace: i(t)
                }
            }
        }(),
        r = function() {
            "use strict";

            function n(n) {
                this.maxSize = e.isNumber(n) ? n : 100, this.reset(), this.maxSize <= 0 && (this.set = this.get = t.noop)
            }

            function i() {
                this.head = this.tail = null
            }

            function r(t, e) {
                this.key = t, this.val = e, this.prev = this.next = null
            }
            return e.mixin(n.prototype, {
                set: function(t, e) {
                    var i, n = this.list.tail;
                    this.size >= this.maxSize && (this.list.remove(n), delete this.hash[n.key], this.size--), (i = this.hash[t]) ? (i.val = e, this.list.moveToFront(i)) : (i = new r(t, e), this.list.add(i), this.hash[t] = i, this.size++)
                },
                get: function(t) {
                    var e = this.hash[t];
                    return e ? (this.list.moveToFront(e), e.val) : void 0
                },
                reset: function() {
                    this.size = 0, this.hash = {}, this.list = new i
                }
            }), e.mixin(i.prototype, {
                add: function(t) {
                    this.head && (t.next = this.head, this.head.prev = t), this.head = t, this.tail = this.tail || t
                },
                remove: function(t) {
                    t.prev ? t.prev.next = t.next : this.head = t.next, t.next ? t.next.prev = t.prev : this.tail = t.prev
                },
                moveToFront: function(t) {
                    this.remove(t), this.add(t)
                }
            }), n
        }(),
        s = function() {
            "use strict";

            function r(t, i) {
                this.prefix = ["__", t, "__"].join(""), this.ttlKey = "__ttl__", this.keyMatcher = new RegExp("^" + e.escapeRegExChars(this.prefix)), this.ls = i || n, !this.ls && this._noop()
            }

            function s() {
                return (new Date).getTime()
            }

            function a(t) {
                return JSON.stringify(e.isUndefined(t) ? null : t)
            }

            function u(e) {
                return t.parseJSON(e)
            }

            function o(t) {
                var e, i, r = [],
                    s = n.length;
                for (e = 0; s > e; e++)(i = n.key(e)).match(t) && r.push(i.replace(t, ""));
                return r
            }
            var n;
            try {
                n = window.localStorage, n.setItem("~~~", "!"), n.removeItem("~~~")
            } catch (i) {
                n = null
            }
            return e.mixin(r.prototype, {
                _prefix: function(t) {
                    return this.prefix + t
                },
                _ttlKey: function(t) {
                    return this._prefix(t) + this.ttlKey
                },
                _noop: function() {
                    this.get = this.set = this.remove = this.clear = this.isExpired = e.noop
                },
                _safeSet: function(t, e) {
                    try {
                        this.ls.setItem(t, e)
                    } catch (n) {
                        "QuotaExceededError" === n.name && (this.clear(), this._noop())
                    }
                },
                get: function(t) {
                    return this.isExpired(t) && this.remove(t), u(this.ls.getItem(this._prefix(t)))
                },
                set: function(t, n, i) {
                    return e.isNumber(i) ? this._safeSet(this._ttlKey(t), a(s() + i)) : this.ls.removeItem(this._ttlKey(t)), this._safeSet(this._prefix(t), a(n))
                },
                remove: function(t) {
                    return this.ls.removeItem(this._ttlKey(t)), this.ls.removeItem(this._prefix(t)), this
                },
                clear: function() {
                    var t, e = o(this.keyMatcher);
                    for (t = e.length; t--;) this.remove(e[t]);
                    return this
                },
                isExpired: function(t) {
                    var n = u(this.ls.getItem(this._ttlKey(t)));
                    return e.isNumber(n) && s() > n ? !0 : !1
                }
            }), r
        }(),
        a = function() {
            "use strict";

            function u(t) {
                t = t || {}, this.cancelled = !1, this.lastReq = null, this._send = t.transport, this._get = t.limiter ? t.limiter(this._get) : this._get, this._cache = t.cache === !1 ? new r(0) : a
            }
            var n = 0,
                i = {},
                s = 6,
                a = new r(10);
            return u.setMaxPendingRequests = function(t) {
                s = t
            }, u.resetCache = function() {
                a.reset()
            }, e.mixin(u.prototype, {
                _fingerprint: function(e) {
                    return e = e || {}, e.url + e.type + t.param(e.data || {})
                },
                _get: function(t, e) {
                    function o(t) {
                        e(null, t), r._cache.set(a, t)
                    }

                    function c() {
                        e(!0)
                    }

                    function l() {
                        n--, delete i[a], r.onDeckRequestArgs && (r._get.apply(r, r.onDeckRequestArgs), r.onDeckRequestArgs = null)
                    }
                    var a, u, r = this;
                    a = this._fingerprint(t), this.cancelled || a !== this.lastReq || ((u = i[a]) ? u.done(o).fail(c) : s > n ? (n++, i[a] = this._send(t).done(o).fail(c).always(l)) : this.onDeckRequestArgs = [].slice.call(arguments, 0))
                },
                get: function(n, i) {
                    var r, s;
                    i = i || t.noop, n = e.isString(n) ? {
                        url: n
                    } : n || {}, s = this._fingerprint(n), this.cancelled = !1, this.lastReq = s, (r = this._cache.get(s)) ? i(null, r) : this._get(n, i)
                },
                cancel: function() {
                    this.cancelled = !0
                }
            }), u
        }(),
        u = window.SearchIndex = function() {
            "use strict";

            function r(n) {
                n = n || {}, n.datumTokenizer && n.queryTokenizer || t.error("datumTokenizer and queryTokenizer are both required"), this.identify = n.identify || e.stringify, this.datumTokenizer = n.datumTokenizer, this.queryTokenizer = n.queryTokenizer, this.reset()
            }

            function s(t) {
                return t = e.filter(t, function(t) {
                    return !!t
                }), t = e.map(t, function(t) {
                    return t.toLowerCase()
                })
            }

            function a() {
                var t = {};
                return t[i] = [], t[n] = {}, t
            }

            function u(t) {
                for (var e = {}, n = [], i = 0, r = t.length; r > i; i++) e[t[i]] || (e[t[i]] = !0, n.push(t[i]));
                return n
            }

            function o(t, e) {
                var n = 0,
                    i = 0,
                    r = [];
                t = t.sort(), e = e.sort();
                for (var s = t.length, a = e.length; s > n && a > i;) t[n] < e[i] ? n++ : t[n] > e[i] ? i++ : (r.push(t[n]), n++, i++);
                return r
            }
            var n = "c",
                i = "i";
            return e.mixin(r.prototype, {
                bootstrap: function(t) {
                    this.datums = t.datums, this.trie = t.trie
                },
                add: function(t) {
                    var r = this;
                    t = e.isArray(t) ? t : [t], e.each(t, function(t) {
                        var u, o;
                        r.datums[u = r.identify(t)] = t, o = s(r.datumTokenizer(t)), e.each(o, function(t) {
                            var e, s, o;
                            for (e = r.trie, s = t.split(""); o = s.shift();) e = e[n][o] || (e[n][o] = a()), e[i].push(u)
                        })
                    })
                },
                get: function(t) {
                    var n = this;
                    return e.map(t, function(t) {
                        return n.datums[t]
                    })
                },
                search: function(t) {
                    var a, c, r = this;
                    return a = s(this.queryTokenizer(t)), e.each(a, function(t) {
                        var e, s, a, u;
                        if (c && 0 === c.length) return !1;
                        for (e = r.trie, s = t.split(""); e && (a = s.shift());) e = e[n][a];
                        return e && 0 === s.length ? (u = e[i].slice(0), void(c = c ? o(c, u) : u)) : (c = [], !1)
                    }), c ? e.map(u(c), function(t) {
                        return r.datums[t]
                    }) : []
                },
                all: function() {
                    var t = [];
                    for (var e in this.datums) t.push(this.datums[e]);
                    return t
                },
                reset: function() {
                    this.datums = {}, this.trie = a()
                },
                serialize: function() {
                    return {
                        datums: this.datums,
                        trie: this.trie
                    }
                }
            }), r
        }(),
        o = function() {
            "use strict";

            function n(t) {
                this.url = t.url, this.ttl = t.ttl, this.cache = t.cache, this.prepare = t.prepare, this.transform = t.transform, this.transport = t.transport, this.thumbprint = t.thumbprint, this.storage = new s(t.cacheKey)
            }
            var t;
            return t = {
                data: "data",
                protocol: "protocol",
                thumbprint: "thumbprint"
            }, e.mixin(n.prototype, {
                _settings: function() {
                    return {
                        url: this.url,
                        type: "GET",
                        dataType: "json"
                    }
                },
                store: function(e) {
                    this.cache && (this.storage.set(t.data, e, this.ttl), this.storage.set(t.protocol, location.protocol, this.ttl), this.storage.set(t.thumbprint, this.thumbprint, this.ttl))
                },
                fromCache: function() {
                    var n, e = {};
                    return this.cache ? (e.data = this.storage.get(t.data), e.protocol = this.storage.get(t.protocol), e.thumbprint = this.storage.get(t.thumbprint), n = e.thumbprint !== this.thumbprint || e.protocol !== location.protocol, e.data && !n ? e.data : null) : null
                },
                fromNetwork: function(t) {
                    function i() {
                        t(!0)
                    }

                    function r(n) {
                        t(null, e.transform(n))
                    }
                    var n, e = this;
                    t && (n = this.prepare(this._settings()), this.transport(n).fail(i).done(r))
                },
                clear: function() {
                    return this.storage.clear(), this
                }
            }), n
        }(),
        c = function() {
            "use strict";

            function t(t) {
                this.url = t.url, this.prepare = t.prepare, this.transform = t.transform, this.transport = new a({
                    cache: t.cache,
                    limiter: t.limiter,
                    transport: t.transport
                })
            }
            return e.mixin(t.prototype, {
                _settings: function() {
                    return {
                        url: this.url,
                        type: "GET",
                        dataType: "json"
                    }
                },
                get: function(t, e) {
                    function r(t, i) {
                        e(t ? [] : n.transform(i))
                    }
                    var i, n = this;
                    if (e) return t = t || "", i = this.prepare(t, this._settings()), this.transport.get(i, r)
                },
                cancelLastRequest: function() {
                    this.transport.cancel()
                }
            }), t
        }(),
        l = function() {
            "use strict";

            function i(i) {
                var r;
                return i ? (r = {
                    url: null,
                    ttl: 864e5,
                    cache: !0,
                    cacheKey: null,
                    thumbprint: "",
                    prepare: e.identity,
                    transform: e.identity,
                    transport: null
                }, i = e.isString(i) ? {
                    url: i
                } : i, i = e.mixin(r, i), !i.url && t.error("prefetch requires url to be set"), i.transform = i.filter || i.transform, i.cacheKey = i.cacheKey || i.url, i.thumbprint = n + i.thumbprint, i.transport = i.transport ? u(i.transport) : t.ajax, i) : null
            }

            function r(n) {
                var i;
                if (n) return i = {
                    url: null,
                    cache: !0,
                    prepare: null,
                    replace: null,
                    wildcard: null,
                    limiter: null,
                    rateLimitBy: "debounce",
                    rateLimitWait: 300,
                    transform: e.identity,
                    transport: null
                }, n = e.isString(n) ? {
                    url: n
                } : n, n = e.mixin(i, n), !n.url && t.error("remote requires url to be set"), n.transform = n.filter || n.transform, n.prepare = s(n), n.limiter = a(n), n.transport = n.transport ? u(n.transport) : t.ajax, delete n.replace, delete n.wildcard, delete n.rateLimitBy, delete n.rateLimitWait, n
            }

            function s(t) {
                function r(t, e) {
                    return e.url = n(e.url, t), e
                }

                function s(t, e) {
                    return e.url = e.url.replace(i, encodeURIComponent(t)), e
                }

                function a(t, e) {
                    return e
                }
                var e, n, i;
                return e = t.prepare, n = t.replace, i = t.wildcard, e ? e : e = n ? r : t.wildcard ? s : a
            }

            function a(t) {
                function s(t) {
                    return function(n) {
                        return e.debounce(n, t)
                    }
                }

                function a(t) {
                    return function(n) {
                        return e.throttle(n, t)
                    }
                }
                var n, i, r;
                return n = t.limiter, i = t.rateLimitBy, r = t.rateLimitWait, n || (n = /^throttle$/i.test(i) ? a(r) : s(r)), n
            }

            function u(n) {
                return function(i) {
                    function s(t) {
                        e.defer(function() {
                            r.resolve(t)
                        })
                    }

                    function a(t) {
                        e.defer(function() {
                            r.reject(t)
                        })
                    }
                    var r = t.Deferred();
                    return n(i, s, a), r
                }
            }
            return function(n) {
                var s, a;
                return s = {
                    initialize: !0,
                    identify: e.stringify,
                    datumTokenizer: null,
                    queryTokenizer: null,
                    sufficient: 5,
                    sorter: null,
                    local: [],
                    prefetch: null,
                    remote: null
                }, n = e.mixin(s, n || {}), !n.datumTokenizer && t.error("datumTokenizer is required"), !n.queryTokenizer && t.error("queryTokenizer is required"), a = n.sorter, n.sorter = a ? function(t) {
                    return t.sort(a)
                } : e.identity, n.local = e.isFunction(n.local) ? n.local() : n.local, n.prefetch = i(n.prefetch), n.remote = r(n.remote), n
            }
        }(),
        h = function() {
            "use strict";

            function r(t) {
                t = l(t), this.sorter = t.sorter, this.identify = t.identify, this.sufficient = t.sufficient, this.local = t.local, this.remote = t.remote ? new c(t.remote) : null, this.prefetch = t.prefetch ? new o(t.prefetch) : null, this.index = new u({
                    identify: this.identify,
                    datumTokenizer: t.datumTokenizer,
                    queryTokenizer: t.queryTokenizer
                }), t.initialize !== !1 && this.initialize()
            }
            var n;
            return n = window && window.Bloodhound, r.noConflict = function() {
                return window && (window.Bloodhound = n), r
            }, r.tokenizers = i, e.mixin(r.prototype, {
                __ttAdapter: function() {
                    function e(e, n, i) {
                        return t.search(e, n, i)
                    }

                    function n(e, n) {
                        return t.search(e, n)
                    }
                    var t = this;
                    return this.remote ? e : n
                },
                _loadPrefetch: function() {
                    function r(t, i) {
                        return t ? n.reject() : (e.add(i), e.prefetch.store(e.index.serialize()), void n.resolve())
                    }
                    var n, i, e = this;
                    return n = t.Deferred(), this.prefetch ? (i = this.prefetch.fromCache()) ? (this.index.bootstrap(i), n.resolve()) : this.prefetch.fromNetwork(r) : n.resolve(), n.promise()
                },
                _initialize: function() {
                    function n() {
                        t.add(t.local)
                    }
                    var t = this;
                    return this.clear(), (this.initPromise = this._loadPrefetch()).done(n), this.initPromise
                },
                initialize: function(t) {
                    return !this.initPromise || t ? this._initialize() : this.initPromise
                },
                add: function(t) {
                    return this.index.add(t), this
                },
                get: function(t) {
                    return t = e.isArray(t) ? t : [].slice.call(arguments), this.index.get(t)
                },
                search: function(t, n, i) {
                    function a(t) {
                        var n = [];
                        e.each(t, function(t) {
                            !e.some(s, function(e) {
                                return r.identify(t) === r.identify(e)
                            }) && n.push(t)
                        }), i && i(n)
                    }
                    var s, r = this;
                    return s = this.sorter(this.index.search(t)), n(this.remote ? s.slice() : s), this.remote && s.length < this.sufficient ? this.remote.get(t, a) : this.remote && this.remote.cancelLastRequest(), this
                },
                all: function() {
                    return this.index.all()
                },
                clear: function() {
                    return this.index.reset(), this
                },
                clearPrefetchCache: function() {
                    return this.prefetch && this.prefetch.clear(), this
                },
                clearRemoteCache: function() {
                    return a.resetCache(), this
                },
                ttAdapter: function() {
                    return this.__ttAdapter()
                }
            }), r
        }();
    return h
}),
    function(t, e) {
        "function" == typeof define && define.amd ? define("typeahead.js", ["jquery"], function(t) {
            return e(t)
        }) : "object" == typeof exports ? module.exports = e(require("jquery")) : e(jQuery)
    }(this, function(t) {
        var e = function() {
                "use strict";
                return {
                    isMsie: function() {
                        return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : !1
                    },
                    isBlankString: function(t) {
                        return !t || /^\s*$/.test(t)
                    },
                    escapeRegExChars: function(t) {
                        return t.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&")
                    },
                    isString: function(t) {
                        return "string" == typeof t
                    },
                    isNumber: function(t) {
                        return "number" == typeof t
                    },
                    isArray: t.isArray,
                    isFunction: t.isFunction,
                    isObject: t.isPlainObject,
                    isUndefined: function(t) {
                        return "undefined" == typeof t
                    },
                    isElement: function(t) {
                        return !(!t || 1 !== t.nodeType)
                    },
                    isJQuery: function(e) {
                        return e instanceof t
                    },
                    toStr: function(t) {
                        return e.isUndefined(t) || null === t ? "" : t + ""
                    },
                    bind: t.proxy,
                    each: function(e, n) {
                        function i(t, e) {
                            return n(e, t)
                        }
                        t.each(e, i)
                    },
                    map: t.map,
                    filter: t.grep,
                    every: function(e, n) {
                        var i = !0;
                        return e ? (t.each(e, function(t, r) {
                            return (i = n.call(null, r, t, e)) ? void 0 : !1
                        }), !!i) : i
                    },
                    some: function(e, n) {
                        var i = !1;
                        return e ? (t.each(e, function(t, r) {
                            return (i = n.call(null, r, t, e)) ? !1 : void 0
                        }), !!i) : i
                    },
                    mixin: t.extend,
                    identity: function(t) {
                        return t
                    },
                    clone: function(e) {
                        return t.extend(!0, {}, e)
                    },
                    getIdGenerator: function() {
                        var t = 0;
                        return function() {
                            return t++
                        }
                    },
                    templatify: function(e) {
                        function n() {
                            return String(e)
                        }
                        return t.isFunction(e) ? e : n
                    },
                    defer: function(t) {
                        setTimeout(t, 0)
                    },
                    debounce: function(t, e, n) {
                        var i, r;
                        return function() {
                            var u, o, s = this,
                                a = arguments;
                            return u = function() {
                                i = null, n || (r = t.apply(s, a))
                            }, o = n && !i, clearTimeout(i), i = setTimeout(u, e), o && (r = t.apply(s, a)), r
                        }
                    },
                    throttle: function(t, e) {
                        var n, i, r, s, a, u;
                        return a = 0, u = function() {
                            a = new Date, r = null, s = t.apply(n, i)
                        },
                            function() {
                                var o = new Date,
                                    c = e - (o - a);
                                return n = this, i = arguments, 0 >= c ? (clearTimeout(r), r = null, a = o, s = t.apply(n, i)) : r || (r = setTimeout(u, c)), s
                            }
                    },
                    stringify: function(t) {
                        return e.isString(t) ? t : JSON.stringify(t)
                    },
                    noop: function() {}
                }
            }(),
            n = function() {
                "use strict";

                function n(n) {
                    var a, u;
                    return u = e.mixin({}, t, n), a = {
                        css: s(),
                        classes: u,
                        html: i(u),
                        selectors: r(u)
                    }, {
                        css: a.css,
                        html: a.html,
                        classes: a.classes,
                        selectors: a.selectors,
                        mixin: function(t) {
                            e.mixin(t, a)
                        }
                    }
                }

                function i(t) {
                    return {
                        wrapper: '<span class="' + t.wrapper + '"></span>',
                        menu: '<div class="' + t.menu + '"></div>'
                    }
                }

                function r(t) {
                    var n = {};
                    return e.each(t, function(t, e) {
                        n[e] = "." + t
                    }), n
                }

                function s() {
                    var t = {
                        wrapper: {
                            position: "relative",
                            display: "inline-block"
                        },
                        hint: {
                            position: "absolute",
                            top: "0",
                            left: "0",
                            borderColor: "transparent",
                            boxShadow: "none",
                            opacity: "1"
                        },
                        input: {
                            position: "relative",
                            verticalAlign: "top",
                            backgroundColor: "transparent"
                        },
                        inputWithNoHint: {
                            position: "relative",
                            verticalAlign: "top"
                        },
                        menu: {
                            position: "absolute",
                            top: "100%",
                            left: "0",
                            zIndex: "100",
                            display: "none"
                        },
                        ltr: {
                            left: "0",
                            right: "auto"
                        },
                        rtl: {
                            left: "auto",
                            right: " 0"
                        }
                    };
                    return e.isMsie() && e.mixin(t.input, {
                        backgroundImage: "url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)"
                    }), t
                }
                var t = {
                    wrapper: "twitter-typeahead",
                    input: "tt-input",
                    hint: "tt-hint",
                    menu: "tt-menu",
                    dataset: "tt-dataset",
                    suggestion: "tt-suggestion",
                    selectable: "tt-selectable",
                    empty: "tt-empty",
                    open: "tt-open",
                    cursor: "tt-cursor",
                    highlight: "tt-highlight"
                };
                return n
            }(),
            i = function() {
                "use strict";

                function r(e) {
                    e && e.el || t.error("EventBus initialized without el"), this.$el = t(e.el)
                }
                var n, i;
                return n = "typeahead:", i = {
                    render: "rendered",
                    cursorchange: "cursorchanged",
                    select: "selected",
                    autocomplete: "autocompleted"
                }, e.mixin(r.prototype, {
                    _trigger: function(e, i) {
                        var r;
                        return r = t.Event(n + e), (i = i || []).unshift(r), this.$el.trigger.apply(this.$el, i), r
                    },
                    before: function(t) {
                        var e, n;
                        return e = [].slice.call(arguments, 1), n = this._trigger("before" + t, e), n.isDefaultPrevented()
                    },
                    trigger: function(t) {
                        var e;
                        this._trigger(t, [].slice.call(arguments, 1)), (e = i[t]) && this._trigger(e, [].slice.call(arguments, 1))
                    }
                }), r
            }(),
            r = function() {
                "use strict";

                function n(e, n, i, r) {
                    var s;
                    if (!i) return this;
                    for (n = n.split(t), i = r ? c(i, r) : i, this._callbacks = this._callbacks || {}; s = n.shift();) this._callbacks[s] = this._callbacks[s] || {
                        sync: [],
                        async: []
                    }, this._callbacks[s][e].push(i);
                    return this
                }

                function i(t, e, i) {
                    return n.call(this, "async", t, e, i)
                }

                function r(t, e, i) {
                    return n.call(this, "sync", t, e, i)
                }

                function s(e) {
                    var n;
                    if (!this._callbacks) return this;
                    for (e = e.split(t); n = e.shift();) delete this._callbacks[n];
                    return this
                }

                function a(n) {
                    var i, r, s, a, o;
                    if (!this._callbacks) return this;
                    for (n = n.split(t), s = [].slice.call(arguments, 1);
                         (i = n.shift()) && (r = this._callbacks[i]);) a = u(r.sync, this, [i].concat(s)), o = u(r.async, this, [i].concat(s)), a() && e(o);
                    return this
                }

                function u(t, e, n) {
                    function i() {
                        for (var i, r = 0, s = t.length; !i && s > r; r += 1) i = t[r].apply(e, n) === !1;
                        return !i
                    }
                    return i
                }

                function o() {
                    var t;
                    return t = window.setImmediate ? function(t) {
                        setImmediate(function() {
                            t()
                        })
                    } : function(t) {
                        setTimeout(function() {
                            t()
                        }, 0)
                    }
                }

                function c(t, e) {
                    return t.bind ? t.bind(e) : function() {
                        t.apply(e, [].slice.call(arguments, 0))
                    }
                }
                var t = /\s+/,
                    e = o();
                return {
                    onSync: r,
                    onAsync: i,
                    off: s,
                    trigger: a
                }
            }(),
            s = function(t) {
                "use strict";

                function i(t, n, i) {
                    for (var s, r = [], a = 0, u = t.length; u > a; a++) r.push(e.escapeRegExChars(t[a]));
                    return s = i ? "\\b(" + r.join("|") + ")\\b" : "(" + r.join("|") + ")", n ? new RegExp(s) : new RegExp(s, "i")
                }
                var n = {
                    node: null,
                    pattern: null,
                    tagName: "strong",
                    className: null,
                    wordsOnly: !1,
                    caseSensitive: !1
                };
                return function(r) {
                    function a(e) {
                        var n, i, a;
                        return (n = s.exec(e.data)) && (a = t.createElement(r.tagName), r.className && (a.className = r.className), i = e.splitText(n.index), i.splitText(n[0].length), a.appendChild(i.cloneNode(!0)), e.parentNode.replaceChild(a, i)), !!n
                    }

                    function u(t, e) {
                        for (var n, i = 3, r = 0; r < t.childNodes.length; r++) n = t.childNodes[r], n.nodeType === i ? r += e(n) ? 1 : 0 : u(n, e)
                    }
                    var s;
                    r = e.mixin({}, n, r), r.node && r.pattern && (r.pattern = e.isArray(r.pattern) ? r.pattern : [r.pattern], s = i(r.pattern, r.caseSensitive, r.wordsOnly), u(r.node, a))
                }
            }(window.document),
            a = function() {
                "use strict";

                function i(n, i) {
                    n = n || {}, n.input || t.error("input is missing"), i.mixin(this), this.$hint = t(n.hint), this.$input = t(n.input), this.query = this.$input.val(), this.queryWhenFocused = this.hasFocus() ? this.query : null, this.$overflowHelper = s(this.$input), this._checkLanguageDirection(), 0 === this.$hint.length && (this.setHint = this.getHint = this.clearHint = this.clearHintIfInvalid = e.noop)
                }

                function s(e) {
                    return t('<pre aria-hidden="true"></pre>').css({
                        position: "absolute",
                        visibility: "hidden",
                        whiteSpace: "pre",
                        fontFamily: e.css("font-family"),
                        fontSize: e.css("font-size"),
                        fontStyle: e.css("font-style"),
                        fontVariant: e.css("font-variant"),
                        fontWeight: e.css("font-weight"),
                        wordSpacing: e.css("word-spacing"),
                        letterSpacing: e.css("letter-spacing"),
                        textIndent: e.css("text-indent"),
                        textRendering: e.css("text-rendering"),
                        textTransform: e.css("text-transform")
                    }).insertAfter(e)
                }

                function a(t, e) {
                    return i.normalizeQuery(t) === i.normalizeQuery(e)
                }

                function u(t) {
                    return t.altKey || t.ctrlKey || t.metaKey || t.shiftKey
                }
                var n;
                return n = {
                    9: "tab",
                    27: "esc",
                    37: "left",
                    39: "right",
                    13: "enter",
                    38: "up",
                    40: "down"
                }, i.normalizeQuery = function(t) {
                    return e.toStr(t).replace(/^\s*/g, "").replace(/\s{2,}/g, " ")
                }, e.mixin(i.prototype, r, {
                    _onBlur: function() {
                        this.resetInputValue(), this.trigger("blurred")
                    },
                    _onFocus: function() {
                        this.queryWhenFocused = this.query, this.trigger("focused")
                    },
                    _onKeydown: function(t) {
                        var e = n[t.which || t.keyCode];
                        this._managePreventDefault(e, t), e && this._shouldTrigger(e, t) && this.trigger(e + "Keyed", t)
                    },
                    _onInput: function() {
                        this._setQuery(this.getInputValue()), this.clearHintIfInvalid(), this._checkLanguageDirection()
                    },
                    _managePreventDefault: function(t, e) {
                        var n;
                        switch (t) {
                            case "up":
                            case "down":
                                n = !u(e);
                                break;
                            default:
                                n = !1
                        }
                        n && e.preventDefault()
                    },
                    _shouldTrigger: function(t, e) {
                        var n;
                        switch (t) {
                            case "tab":
                                n = !u(e);
                                break;
                            default:
                                n = !0
                        }
                        return n
                    },
                    _checkLanguageDirection: function() {
                        var t = (this.$input.css("direction") || "ltr").toLowerCase();
                        this.dir !== t && (this.dir = t, this.$hint.attr("dir", t), this.trigger("langDirChanged", t))
                    },
                    _setQuery: function(t, e) {
                        var n, i;
                        n = a(t, this.query), i = n ? this.query.length !== t.length : !1, this.query = t, e || n ? !e && i && this.trigger("whitespaceChanged", this.query) : this.trigger("queryChanged", this.query)
                    },
                    bind: function() {
                        var i, r, s, a, t = this;
                        return i = e.bind(this._onBlur, this), r = e.bind(this._onFocus, this), s = e.bind(this._onKeydown, this), a = e.bind(this._onInput, this), this.$input.on("blur.tt", i).on("focus.tt", r).on("keydown.tt", s), !e.isMsie() || e.isMsie() > 9 ? this.$input.on("input.tt", a) : this.$input.on("keydown.tt keypress.tt cut.tt paste.tt", function(i) {
                            n[i.which || i.keyCode] || e.defer(e.bind(t._onInput, t, i))
                        }), this
                    },
                    focus: function() {
                        this.$input.focus()
                    },
                    blur: function() {
                        this.$input.blur()
                    },
                    getLangDir: function() {
                        return this.dir
                    },
                    getQuery: function() {
                        return this.query || ""
                    },
                    setQuery: function(t, e) {
                        this.setInputValue(t), this._setQuery(t, e)
                    },
                    hasQueryChangedSinceLastFocus: function() {
                        return this.query !== this.queryWhenFocused
                    },
                    getInputValue: function() {
                        return this.$input.val()
                    },
                    setInputValue: function(t) {
                        this.$input.val(t), this.clearHintIfInvalid(), this._checkLanguageDirection()
                    },
                    resetInputValue: function() {
                        this.setInputValue(this.query)
                    },
                    getHint: function() {
                        return this.$hint.val()
                    },
                    setHint: function(t) {
                        this.$hint.val(t)
                    },
                    clearHint: function() {
                        this.setHint("")
                    },
                    clearHintIfInvalid: function() {
                        var t, e, n, i;
                        t = this.getInputValue(), e = this.getHint(), n = t !== e && 0 === e.indexOf(t), i = "" !== t && n && !this.hasOverflow(), !i && this.clearHint()
                    },
                    hasFocus: function() {
                        return this.$input.is(":focus")
                    },
                    hasOverflow: function() {
                        var t = this.$input.width() - 2;
                        return this.$overflowHelper.text(this.getInputValue()), this.$overflowHelper.width() >= t
                    },
                    isCursorAtEnd: function() {
                        var t, n, i;
                        return t = this.$input.val().length, n = this.$input[0].selectionStart, e.isNumber(n) ? n === t : document.selection ? (i = document.selection.createRange(), i.moveStart("character", -t), t === i.text.length) : !0
                    },
                    destroy: function() {
                        this.$hint.off(".tt"), this.$input.off(".tt"), this.$overflowHelper.remove(), this.$hint = this.$input = this.$overflowHelper = t("<div>")
                    }
                }), i
            }(),
            u = function() {
                "use strict";

                function a(n, r) {
                    n = n || {}, n.templates = n.templates || {}, n.templates.notFound = n.templates.notFound || n.templates.empty, n.source || t.error("missing source"), n.node || t.error("missing node"), n.name && !c(n.name) && t.error("invalid dataset name: " + n.name), r.mixin(this), this.highlight = !!n.highlight, this.name = n.name || i(), this.limit = n.limit || 7, this.displayFn = u(n.display || n.displayKey), this.templates = o(n.templates, this.displayFn), this.source = n.source.__ttAdapter ? n.source.__ttAdapter() : n.source, this.async = e.isUndefined(n.async) ? this.source.length > 2 : !!n.async, this._resetLastSuggestion(), this.$el = t(n.node).addClass(this.classes.dataset).addClass(this.classes.dataset + "-" + this.name)
                }

                function u(t) {
                    function n(e) {
                        return e[t]
                    }
                    return t = t || e.stringify, e.isFunction(t) ? t : n
                }

                function o(n, i) {
                    function r(e) {
                        return t("<div>").text(i(e))
                    }
                    return {
                        notFound: n.notFound && e.templatify(n.notFound),
                        pending: n.pending && e.templatify(n.pending),
                        header: n.header && e.templatify(n.header),
                        footer: n.footer && e.templatify(n.footer),
                        suggestion: n.suggestion || r
                    }
                }

                function c(t) {
                    return /^[_a-zA-Z0-9-]+$/.test(t)
                }
                var n, i;
                return n = {
                    val: "tt-selectable-display",
                    obj: "tt-selectable-object"
                }, i = e.getIdGenerator(), a.extractData = function(e) {
                    var i = t(e);
                    return i.data(n.obj) ? {
                        val: i.data(n.val) || "",
                        obj: i.data(n.obj) || null
                    } : null
                }, e.mixin(a.prototype, r, {
                    _overwrite: function(t, e) {
                        e = e || [], e.length ? this._renderSuggestions(t, e) : this.async && this.templates.pending ? this._renderPending(t) : !this.async && this.templates.notFound ? this._renderNotFound(t) : this._empty(), this.trigger("rendered", this.name, e, !1)
                    },
                    _append: function(t, e) {
                        e = e || [], e.length && this.$lastSuggestion.length ? this._appendSuggestions(t, e) : e.length ? this._renderSuggestions(t, e) : !this.$lastSuggestion.length && this.templates.notFound && this._renderNotFound(t), this.trigger("rendered", this.name, e, !0)
                    },
                    _renderSuggestions: function(t, e) {
                        var n;
                        n = this._getSuggestionsFragment(t, e), this.$lastSuggestion = n.children().last(), this.$el.html(n).prepend(this._getHeader(t, e)).append(this._getFooter(t, e))
                    },
                    _appendSuggestions: function(t, e) {
                        var n, i;
                        n = this._getSuggestionsFragment(t, e), i = n.children().last(), this.$lastSuggestion.after(n), this.$lastSuggestion = i
                    },
                    _renderPending: function(t) {
                        var e = this.templates.pending;
                        this._resetLastSuggestion(), e && this.$el.html(e({
                            query: t,
                            dataset: this.name
                        }))
                    },
                    _renderNotFound: function(t) {
                        var e = this.templates.notFound;
                        this._resetLastSuggestion(), e && this.$el.html(e({
                            query: t,
                            dataset: this.name
                        }))
                    },
                    _empty: function() {
                        this.$el.empty(), this._resetLastSuggestion()
                    },
                    _getSuggestionsFragment: function(i, r) {
                        var u, a = this;
                        return u = document.createDocumentFragment(), e.each(r, function(e) {
                            var r, s;
                            s = a._injectQuery(i, e), r = t(a.templates.suggestion(s)).data(n.obj, e).data(n.val, a.displayFn(e)).addClass(a.classes.suggestion + " " + a.classes.selectable), u.appendChild(r[0])
                        }), this.highlight && s({
                            className: this.classes.highlight,
                            node: u,
                            pattern: i
                        }), t(u)
                    },
                    _getFooter: function(t, e) {
                        return this.templates.footer ? this.templates.footer({
                            query: t,
                            suggestions: e,
                            dataset: this.name
                        }) : null
                    },
                    _getHeader: function(t, e) {
                        return this.templates.header ? this.templates.header({
                            query: t,
                            suggestions: e,
                            dataset: this.name
                        }) : null
                    },
                    _resetLastSuggestion: function() {
                        this.$lastSuggestion = t()
                    },
                    _injectQuery: function(t, n) {
                        return e.isObject(n) ? e.mixin({
                            _query: t
                        }, n) : n
                    },
                    update: function(e) {
                        function a(t) {
                            r || (r = !0, t = (t || []).slice(0, n.limit), s = t.length, n._overwrite(e, t), s < n.limit && n.async && n.trigger("asyncRequested", e))
                        }

                        function u(r) {
                            r = r || [], !i && s < n.limit && (n.cancel = t.noop, s += r.length, n._append(e, r.slice(0, n.limit - s)), n.async && n.trigger("asyncReceived", e))
                        }
                        var n = this,
                            i = !1,
                            r = !1,
                            s = 0;
                        this.cancel(), this.cancel = function() {
                            i = !0, n.cancel = t.noop, n.async && n.trigger("asyncCanceled", e)
                        }, this.source(e, a, u), !r && a([])
                    },
                    cancel: t.noop,
                    clear: function() {
                        this._empty(), this.cancel(), this.trigger("cleared")
                    },
                    isEmpty: function() {
                        return this.$el.is(":empty")
                    },
                    destroy: function() {
                        this.$el = t("<div>")
                    }
                }), a
            }(),
            o = function() {
                "use strict";

                function n(n, i) {
                    function s(e) {
                        var n = r.$node.find(e.node).first();
                        return e.node = n.length ? n : t("<div>").appendTo(r.$node), new u(e, i)
                    }
                    var r = this;
                    n = n || {}, n.node || t.error("node is required"), i.mixin(this), this.$node = t(n.node), this.query = null, this.datasets = e.map(n.datasets, s)
                }
                return e.mixin(n.prototype, r, {
                    _onSelectableClick: function(e) {
                        this.trigger("selectableClicked", t(e.currentTarget))
                    },
                    _onRendered: function(t, e, n, i) {
                        this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty()), this.trigger("datasetRendered", e, n, i)
                    },
                    _onCleared: function() {
                        this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty()), this.trigger("datasetCleared")
                    },
                    _propagate: function() {
                        this.trigger.apply(this, arguments)
                    },
                    _allDatasetsEmpty: function() {
                        function t(t) {
                            return t.isEmpty()
                        }
                        return e.every(this.datasets, t)
                    },
                    _getSelectables: function() {
                        return this.$node.find(this.selectors.selectable)
                    },
                    _removeCursor: function() {
                        var t = this.getActiveSelectable();
                        t && t.removeClass(this.classes.cursor)
                    },
                    _ensureVisible: function(t) {
                        var e, n, i, r;
                        e = t.position().top, n = e + t.outerHeight(!0), i = this.$node.scrollTop(), r = this.$node.height() + parseInt(this.$node.css("paddingTop"), 10) + parseInt(this.$node.css("paddingBottom"), 10), 0 > e ? this.$node.scrollTop(i + e) : n > r && this.$node.scrollTop(i + (n - r))
                    },
                    bind: function() {
                        var n, t = this;
                        return n = e.bind(this._onSelectableClick, this), this.$node.on("click.tt", this.selectors.selectable, n), e.each(this.datasets, function(e) {
                            e.onSync("asyncRequested", t._propagate, t).onSync("asyncCanceled", t._propagate, t).onSync("asyncReceived", t._propagate, t).onSync("rendered", t._onRendered, t).onSync("cleared", t._onCleared, t)
                        }), this
                    },
                    isOpen: function() {
                        return this.$node.hasClass(this.classes.open)
                    },
                    open: function() {
                        this.$node.addClass(this.classes.open)
                    },
                    close: function() {
                        this.$node.removeClass(this.classes.open), this._removeCursor()
                    },
                    setLanguageDirection: function(t) {
                        this.$node.attr("dir", t)
                    },
                    selectableRelativeToCursor: function(t) {
                        var e, n, i, r;
                        return n = this.getActiveSelectable(), e = this._getSelectables(), i = n ? e.index(n) : -1, r = i + t, r = (r + 1) % (e.length + 1) - 1, r = -1 > r ? e.length - 1 : r, -1 === r ? null : e.eq(r)
                    },
                    setCursor: function(t) {
                        this._removeCursor(), (t = t && t.first()) && (t.addClass(this.classes.cursor), this._ensureVisible(t))
                    },
                    getSelectableData: function(t) {
                        return t && t.length ? u.extractData(t) : null
                    },
                    getActiveSelectable: function() {
                        var t = this._getSelectables().filter(this.selectors.cursor).first();
                        return t.length ? t : null
                    },
                    getTopSelectable: function() {
                        var t = this._getSelectables().first();
                        return t.length ? t : null
                    },
                    update: function(t) {
                        function i(e) {
                            e.update(t)
                        }
                        var n = t !== this.query;
                        return n && (this.query = t, e.each(this.datasets, i)), n
                    },
                    empty: function() {
                        function t(t) {
                            t.clear()
                        }
                        e.each(this.datasets, t), this.query = null, this.$node.addClass(this.classes.empty)
                    },
                    destroy: function() {
                        function n(t) {
                            t.destroy()
                        }
                        this.$node.off(".tt"), this.$node = t("<div>"), e.each(this.datasets, n)
                    }
                }), n
            }(),
            c = function() {
                "use strict";

                function n() {
                    o.apply(this, [].slice.call(arguments, 0))
                }
                var t = o.prototype;
                return e.mixin(n.prototype, o.prototype, {
                    open: function() {
                        return !this._allDatasetsEmpty() && this._show(), t.open.apply(this, [].slice.call(arguments, 0))
                    },
                    close: function() {
                        return this._hide(), t.close.apply(this, [].slice.call(arguments, 0))
                    },
                    _onRendered: function() {
                        return this._allDatasetsEmpty() ? this._hide() : this.isOpen() && this._show(), t._onRendered.apply(this, [].slice.call(arguments, 0))
                    },
                    _onCleared: function() {
                        return this._allDatasetsEmpty() ? this._hide() : this.isOpen() && this._show(), t._onCleared.apply(this, [].slice.call(arguments, 0))
                    },
                    setLanguageDirection: function(e) {
                        return this.$node.css("ltr" === e ? this.css.ltr : this.css.rtl), t.setLanguageDirection.apply(this, [].slice.call(arguments, 0))
                    },
                    _hide: function() {
                        this.$node.hide()
                    },
                    _show: function() {
                        this.$node.css("display", "block")
                    }
                }), n
            }(),
            l = function() {
                "use strict";

                function n(n, r) {
                    var s, a, u, o, c, l, h, f, p, d, g;
                    n = n || {}, n.input || t.error("missing input"), n.menu || t.error("missing menu"), n.eventBus || t.error("missing event bus"), r.mixin(this), this.eventBus = n.eventBus, this.minLength = e.isNumber(n.minLength) ? n.minLength : 1, this.input = n.input, this.menu = n.menu, this.enabled = !0, this.active = !1, this.input.hasFocus() && this.activate(), this.dir = this.input.getLangDir(), this._hacks(), this.menu.bind().onSync("selectableClicked", this._onSelectableClicked, this).onSync("asyncRequested", this._onAsyncRequested, this).onSync("asyncCanceled", this._onAsyncCanceled, this).onSync("asyncReceived", this._onAsyncReceived, this).onSync("datasetRendered", this._onDatasetRendered, this).onSync("datasetCleared", this._onDatasetCleared, this), s = i(this, "activate", "open", "_onFocused"), a = i(this, "deactivate", "_onBlurred"), u = i(this, "isActive", "isOpen", "_onEnterKeyed"), o = i(this, "isActive", "isOpen", "_onTabKeyed"), c = i(this, "isActive", "_onEscKeyed"), l = i(this, "isActive", "open", "_onUpKeyed"), h = i(this, "isActive", "open", "_onDownKeyed"), f = i(this, "isActive", "isOpen", "_onLeftKeyed"), p = i(this, "isActive", "isOpen", "_onRightKeyed"), d = i(this, "_openIfActive", "_onQueryChanged"), g = i(this, "_openIfActive", "_onWhitespaceChanged"), this.input.bind().onSync("focused", s, this).onSync("blurred", a, this).onSync("enterKeyed", u, this).onSync("tabKeyed", o, this).onSync("escKeyed", c, this).onSync("upKeyed", l, this).onSync("downKeyed", h, this).onSync("leftKeyed", f, this).onSync("rightKeyed", p, this).onSync("queryChanged", d, this).onSync("whitespaceChanged", g, this).onSync("langDirChanged", this._onLangDirChanged, this)
                }

                function i(t) {
                    var n = [].slice.call(arguments, 1);
                    return function() {
                        var i = [].slice.call(arguments);
                        e.each(n, function(e) {
                            return t[e].apply(t, i)
                        })
                    }
                }
                return e.mixin(n.prototype, {
                    _hacks: function() {
                        var n, i;
                        n = this.input.$input || t("<div>"),
                            i = this.menu.$node || t("<div>"), n.on("blur.tt", function(t) {
                            var r, s, a;
                            r = document.activeElement, s = i.is(r), a = i.has(r).length > 0, e.isMsie() && (s || a) && (t.preventDefault(), t.stopImmediatePropagation(), e.defer(function() {
                                n.focus()
                            }))
                        }), i.on("mousedown.tt", function(t) {
                            t.preventDefault()
                        })
                    },
                    _onSelectableClicked: function(t, e) {
                        this.select(e)
                    },
                    _onDatasetCleared: function() {
                        this._updateHint()
                    },
                    _onDatasetRendered: function(t, e, n, i) {
                        this._updateHint(), this.eventBus.trigger("render", n, i, e)
                    },
                    _onAsyncRequested: function(t, e, n) {
                        this.eventBus.trigger("asyncrequest", n, e)
                    },
                    _onAsyncCanceled: function(t, e, n) {
                        this.eventBus.trigger("asynccancel", n, e)
                    },
                    _onAsyncReceived: function(t, e, n) {
                        this.eventBus.trigger("asyncreceive", n, e)
                    },
                    _onFocused: function() {
                        this._minLengthMet() && this.menu.update(this.input.getQuery())
                    },
                    _onBlurred: function() {
                        this.input.hasQueryChangedSinceLastFocus() && this.eventBus.trigger("change", this.input.getQuery())
                    },
                    _onEnterKeyed: function(t, e) {
                        var n;
                        (n = this.menu.getActiveSelectable()) && this.select(n) && e.preventDefault()
                    },
                    _onTabKeyed: function(t, e) {
                        var n;
                        (n = this.menu.getActiveSelectable()) ? this.select(n) && e.preventDefault(): (n = this.menu.getTopSelectable()) && this.autocomplete(n) && e.preventDefault()
                    },
                    _onEscKeyed: function() {
                        this.close()
                    },
                    _onUpKeyed: function() {
                        this.moveCursor(-1)
                    },
                    _onDownKeyed: function() {
                        this.moveCursor(1)
                    },
                    _onLeftKeyed: function() {
                        "rtl" === this.dir && this.input.isCursorAtEnd() && this.autocomplete(this.menu.getTopSelectable())
                    },
                    _onRightKeyed: function() {
                        "ltr" === this.dir && this.input.isCursorAtEnd() && this.autocomplete(this.menu.getTopSelectable())
                    },
                    _onQueryChanged: function(t, e) {
                        this._minLengthMet(e) ? this.menu.update(e) : this.menu.empty()
                    },
                    _onWhitespaceChanged: function() {
                        this._updateHint()
                    },
                    _onLangDirChanged: function(t, e) {
                        this.dir !== e && (this.dir = e, this.menu.setLanguageDirection(e))
                    },
                    _openIfActive: function() {
                        this.isActive() && this.open()
                    },
                    _minLengthMet: function(t) {
                        return t = e.isString(t) ? t : this.input.getQuery() || "", t.length >= this.minLength
                    },
                    _updateHint: function() {
                        var t, n, i, r, s, u, o;
                        t = this.menu.getTopSelectable(), n = this.menu.getSelectableData(t), i = this.input.getInputValue(), !n || e.isBlankString(i) || this.input.hasOverflow() ? this.input.clearHint() : (r = a.normalizeQuery(i), s = e.escapeRegExChars(r), u = new RegExp("^(?:" + s + ")(.+$)", "i"), o = u.exec(n.val), o && this.input.setHint(i + o[1]))
                    },
                    isEnabled: function() {
                        return this.enabled
                    },
                    enable: function() {
                        this.enabled = !0
                    },
                    disable: function() {
                        this.enabled = !1
                    },
                    isActive: function() {
                        return this.active
                    },
                    activate: function() {
                        return this.isActive() ? !0 : !this.isEnabled() || this.eventBus.before("active") ? !1 : (this.active = !0, this.eventBus.trigger("active"), !0)
                    },
                    deactivate: function() {
                        return this.isActive() ? this.eventBus.before("idle") ? !1 : (this.active = !1, this.close(), this.eventBus.trigger("idle"), !0) : !0
                    },
                    isOpen: function() {
                        return this.menu.isOpen()
                    },
                    open: function() {
                        return this.isOpen() || this.eventBus.before("open") || (this.menu.open(), this._updateHint(), this.eventBus.trigger("open")), this.isOpen()
                    },
                    close: function() {
                        return this.isOpen() && !this.eventBus.before("close") && (this.menu.close(), this.input.clearHint(), this.input.resetInputValue(), this.eventBus.trigger("close")), !this.isOpen()
                    },
                    setVal: function(t) {
                        this.input.setQuery(e.toStr(t))
                    },
                    getVal: function() {
                        return this.input.getQuery()
                    },
                    select: function(t) {
                        var e = this.menu.getSelectableData(t);
                        return e && !this.eventBus.before("select", e.obj) ? (this.input.setQuery(e.val, !0), this.eventBus.trigger("select", e.obj), this.close(), !0) : !1
                    },
                    autocomplete: function(t) {
                        var e, n, i;
                        return e = this.input.getQuery(), n = this.menu.getSelectableData(t), i = n && e !== n.val, i && !this.eventBus.before("autocomplete", n.obj) ? (this.input.setQuery(n.val), this.eventBus.trigger("autocomplete", n.obj), !0) : !1
                    },
                    moveCursor: function(t) {
                        var e, n, i, r, s;
                        return e = this.input.getQuery(), n = this.menu.selectableRelativeToCursor(t), i = this.menu.getSelectableData(n), r = i ? i.obj : null, s = this._minLengthMet() && this.menu.update(e), s || this.eventBus.before("cursorchange", r) ? !1 : (this.menu.setCursor(n), i ? this.input.setInputValue(i.val) : (this.input.resetInputValue(), this._updateHint()), this.eventBus.trigger("cursorchange", r), !0)
                    },
                    destroy: function() {
                        this.input.destroy(), this.menu.destroy()
                    }
                }), n
            }();
        ! function() {
            "use strict";

            function h(e, n) {
                e.each(function() {
                    var i, e = t(this);
                    (i = e.data(s.typeahead)) && n(i, e)
                })
            }

            function f(t, e) {
                return t.clone().addClass(e.classes.hint).removeData().css(e.css.hint).css(d(t)).prop("readonly", !0).removeAttr("id name placeholder required").attr({
                    autocomplete: "off",
                    spellcheck: "false",
                    tabindex: -1
                })
            }

            function p(t, e) {
                t.data(s.attrs, {
                    dir: t.attr("dir"),
                    autocomplete: t.attr("autocomplete"),
                    spellcheck: t.attr("spellcheck"),
                    style: t.attr("style")
                }), t.addClass(e.classes.input).attr({
                    autocomplete: "off",
                    spellcheck: !1
                });
                try {
                    !t.attr("dir") && t.attr("dir", "auto")
                } catch (n) {}
                return t
            }

            function d(t) {
                return {
                    backgroundAttachment: t.css("background-attachment"),
                    backgroundClip: t.css("background-clip"),
                    backgroundColor: t.css("background-color"),
                    backgroundImage: t.css("background-image"),
                    backgroundOrigin: t.css("background-origin"),
                    backgroundPosition: t.css("background-position"),
                    backgroundRepeat: t.css("background-repeat"),
                    backgroundSize: t.css("background-size")
                }
            }

            function g(t) {
                var n, i;
                n = t.data(s.www), i = t.parent().filter(n.selectors.wrapper), e.each(t.data(s.attrs), function(n, i) {
                    e.isUndefined(n) ? t.removeAttr(i) : t.attr(i, n)
                }), t.removeData(s.typeahead).removeData(s.www).removeData(s.attr).removeClass(n.classes.input), i.length && (t.detach().insertAfter(i), i.remove())
            }

            function m(n) {
                var i, r;
                return i = e.isJQuery(n) || e.isElement(n), r = i ? t(n).first() : [], r.length ? r : null
            }
            var r, s, u;
            r = t.fn.typeahead, s = {
                www: "tt-www",
                attrs: "tt-attrs",
                typeahead: "tt-typeahead"
            }, u = {
                initialize: function(r, u) {
                    function d() {
                        var n, d, g, y, v, _, b, w, S, k, x;
                        e.each(u, function(t) {
                            t.highlight = !!r.highlight
                        }), n = t(this), d = t(h.html.wrapper), g = m(r.hint), y = m(r.menu), v = r.hint !== !1 && !g, _ = r.menu !== !1 && !y, v && (g = f(n, h)), _ && (y = t(h.html.menu).css(h.css.menu)), g && g.val(""), n = p(n, h), (v || _) && (d.css(h.css.wrapper), n.css(v ? h.css.input : h.css.inputWithNoHint), n.wrap(d).parent().prepend(v ? g : null).append(_ ? y : null)), x = _ ? c : o, b = new i({
                            el: n
                        }), w = new a({
                            hint: g,
                            input: n
                        }, h), S = new x({
                            node: y,
                            datasets: u
                        }, h), k = new l({
                            input: w,
                            menu: S,
                            eventBus: b,
                            minLength: r.minLength
                        }, h), n.data(s.www, h), n.data(s.typeahead, k)
                    }
                    var h;
                    return u = e.isArray(u) ? u : [].slice.call(arguments, 1), r = r || {}, h = n(r.classNames), this.each(d)
                },
                isEnabled: function() {
                    var t;
                    return h(this.first(), function(e) {
                        t = e.isEnabled()
                    }), t
                },
                enable: function() {
                    return h(this, function(t) {
                        t.enable()
                    }), this
                },
                disable: function() {
                    return h(this, function(t) {
                        t.disable()
                    }), this
                },
                isActive: function() {
                    var t;
                    return h(this.first(), function(e) {
                        t = e.isActive()
                    }), t
                },
                activate: function() {
                    return h(this, function(t) {
                        t.activate()
                    }), this
                },
                deactivate: function() {
                    return h(this, function(t) {
                        t.deactivate()
                    }), this
                },
                isOpen: function() {
                    var t;
                    return h(this.first(), function(e) {
                        t = e.isOpen()
                    }), t
                },
                open: function() {
                    return h(this, function(t) {
                        t.open()
                    }), this
                },
                close: function() {
                    return h(this, function(t) {
                        t.close()
                    }), this
                },
                select: function(e) {
                    var n = !1,
                        i = t(e);
                    return h(this.first(), function(t) {
                        n = t.select(i)
                    }), n
                },
                autocomplete: function(e) {
                    var n = !1,
                        i = t(e);
                    return h(this.first(), function(t) {
                        n = t.autocomplete(i)
                    }), n
                },
                moveCursor: function(t) {
                    var e = !1;
                    return h(this.first(), function(n) {
                        e = n.moveCursor(t)
                    }), e
                },
                val: function(t) {
                    var e;
                    return arguments.length ? (h(this, function(e) {
                        e.setVal(t)
                    }), this) : (h(this.first(), function(t) {
                        e = t.getVal()
                    }), e)
                },
                destroy: function() {
                    return h(this, function(t, e) {
                        g(e), t.destroy()
                    }), this
                }
            }, t.fn.typeahead = function(t) {
                return u[t] ? u[t].apply(this, [].slice.call(arguments, 1)) : u.initialize.apply(this, arguments)
            }, t.fn.typeahead.noConflict = function() {
                return t.fn.typeahead = r, this
            }
        }()
    });
