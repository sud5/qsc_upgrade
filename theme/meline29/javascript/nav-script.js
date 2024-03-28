(function() {
    function n(n) {
        function t(t, r, e, u, i, o) {
            for (; i >= 0 && o > i; i += n) {
                var a = u ? u[i] : i;
                e = r(e, t[a], a, t)
            }
            return e
        }
        return function(r, e, u, i) {
            e = b(e, i, 4);
            var o = !k(r) && m.keys(r),
                a = (o || r).length,
                c = n > 0 ? 0 : a - 1;
            return arguments.length < 3 && (u = r[o ? o[c] : c], c += n), t(r, e, u, o, c, a)
        }
    }

    function t(n) {
        return function(t, r, e) {
            r = x(r, e);
            for (var u = O(t), i = n > 0 ? 0 : u - 1; i >= 0 && u > i; i += n)
                if (r(t[i], i, t)) return i;
            return -1
        }
    }

    function r(n, t, r) {
        return function(e, u, i) {
            var o = 0,
                a = O(e);
            if ("number" == typeof i) n > 0 ? o = i >= 0 ? i : Math.max(i + a, o) : a = i >= 0 ? Math.min(i + 1, a) : i + a + 1;
            else if (r && i && a) return i = r(e, u), e[i] === u ? i : -1;
            if (u !== u) return i = t(l.call(e, o, a), m.isNaN), i >= 0 ? i + o : -1;
            for (i = n > 0 ? o : a - 1; i >= 0 && a > i; i += n)
                if (e[i] === u) return i;
            return -1
        }
    }

    function e(n, t) {
        var r = I.length,
            e = n.constructor,
            u = m.isFunction(e) && e.prototype || a,
            i = "constructor";
        for (m.has(n, i) && !m.contains(t, i) && t.push(i); r--;) i = I[r], i in n && n[i] !== u[i] && !m.contains(t, i) && t.push(i)
    }
    var u = this,
        i = u._,
        o = Array.prototype,
        a = Object.prototype,
        c = Function.prototype,
        f = o.push,
        l = o.slice,
        s = a.toString,
        p = a.hasOwnProperty,
        h = Array.isArray,
        v = Object.keys,
        g = c.bind,
        y = Object.create,
        d = function() {},
        m = function(n) {
            return n instanceof m ? n : this instanceof m ? void(this._wrapped = n) : new m(n)
        };
    "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = m), exports._ = m) : u._ = m, m.VERSION = "1.8.3";
    var b = function(n, t, r) {
            if (void 0 === t) return n;
            switch (null == r ? 3 : r) {
                case 1:
                    return function(r) {
                        return n.call(t, r)
                    };
                case 2:
                    return function(r, e) {
                        return n.call(t, r, e)
                    };
                case 3:
                    return function(r, e, u) {
                        return n.call(t, r, e, u)
                    };
                case 4:
                    return function(r, e, u, i) {
                        return n.call(t, r, e, u, i)
                    }
            }
            return function() {
                return n.apply(t, arguments)
            }
        },
        x = function(n, t, r) {
            return null == n ? m.identity : m.isFunction(n) ? b(n, t, r) : m.isObject(n) ? m.matcher(n) : m.property(n)
        };
    m.iteratee = function(n, t) {
        return x(n, t, 1 / 0)
    };
    var _ = function(n, t) {
            return function(r) {
                var e = arguments.length;
                if (2 > e || null == r) return r;
                for (var u = 1; e > u; u++)
                    for (var i = arguments[u], o = n(i), a = o.length, c = 0; a > c; c++) {
                        var f = o[c];
                        t && void 0 !== r[f] || (r[f] = i[f])
                    }
                return r
            }
        },
        j = function(n) {
            if (!m.isObject(n)) return {};
            if (y) return y(n);
            d.prototype = n;
            var t = new d;
            return d.prototype = null, t
        },
        w = function(n) {
            return function(t) {
                return null == t ? void 0 : t[n]
            }
        },
        A = Math.pow(2, 53) - 1,
        O = w("length"),
        k = function(n) {
            var t = O(n);
            return "number" == typeof t && t >= 0 && A >= t
        };
    m.each = m.forEach = function(n, t, r) {
        t = b(t, r);
        var e, u;
        if (k(n))
            for (e = 0, u = n.length; u > e; e++) t(n[e], e, n);
        else {
            var i = m.keys(n);
            for (e = 0, u = i.length; u > e; e++) t(n[i[e]], i[e], n)
        }
        return n
    }, m.map = m.collect = function(n, t, r) {
        t = x(t, r);
        for (var e = !k(n) && m.keys(n), u = (e || n).length, i = Array(u), o = 0; u > o; o++) {
            var a = e ? e[o] : o;
            i[o] = t(n[a], a, n)
        }
        return i
    }, m.reduce = m.foldl = m.inject = n(1), m.reduceRight = m.foldr = n(-1), m.find = m.detect = function(n, t, r) {
        var e;
        return e = k(n) ? m.findIndex(n, t, r) : m.findKey(n, t, r), void 0 !== e && -1 !== e ? n[e] : void 0
    }, m.filter = m.select = function(n, t, r) {
        var e = [];
        return t = x(t, r), m.each(n, function(n, r, u) {
            t(n, r, u) && e.push(n)
        }), e
    }, m.reject = function(n, t, r) {
        return m.filter(n, m.negate(x(t)), r)
    }, m.every = m.all = function(n, t, r) {
        t = x(t, r);
        for (var e = !k(n) && m.keys(n), u = (e || n).length, i = 0; u > i; i++) {
            var o = e ? e[i] : i;
            if (!t(n[o], o, n)) return !1
        }
        return !0
    }, m.some = m.any = function(n, t, r) {
        t = x(t, r);
        for (var e = !k(n) && m.keys(n), u = (e || n).length, i = 0; u > i; i++) {
            var o = e ? e[i] : i;
            if (t(n[o], o, n)) return !0
        }
        return !1
    }, m.contains = m.includes = m.include = function(n, t, r, e) {
        return k(n) || (n = m.values(n)), ("number" != typeof r || e) && (r = 0), m.indexOf(n, t, r) >= 0
    }, m.invoke = function(n, t) {
        var r = l.call(arguments, 2),
            e = m.isFunction(t);
        return m.map(n, function(n) {
            var u = e ? t : n[t];
            return null == u ? u : u.apply(n, r)
        })
    }, m.pluck = function(n, t) {
        return m.map(n, m.property(t))
    }, m.where = function(n, t) {
        return m.filter(n, m.matcher(t))
    }, m.findWhere = function(n, t) {
        return m.find(n, m.matcher(t))
    }, m.max = function(n, t, r) {
        var e, u, i = -1 / 0,
            o = -1 / 0;
        if (null == t && null != n) {
            n = k(n) ? n : m.values(n);
            for (var a = 0, c = n.length; c > a; a++) e = n[a], e > i && (i = e)
        } else t = x(t, r), m.each(n, function(n, r, e) {
            u = t(n, r, e), (u > o || u === -1 / 0 && i === -1 / 0) && (i = n, o = u)
        });
        return i
    }, m.min = function(n, t, r) {
        var e, u, i = 1 / 0,
            o = 1 / 0;
        if (null == t && null != n) {
            n = k(n) ? n : m.values(n);
            for (var a = 0, c = n.length; c > a; a++) e = n[a], i > e && (i = e)
        } else t = x(t, r), m.each(n, function(n, r, e) {
            u = t(n, r, e), (o > u || 1 / 0 === u && 1 / 0 === i) && (i = n, o = u)
        });
        return i
    }, m.shuffle = function(n) {
        for (var t, r = k(n) ? n : m.values(n), e = r.length, u = Array(e), i = 0; e > i; i++) t = m.random(0, i), t !== i && (u[i] = u[t]), u[t] = r[i];
        return u
    }, m.sample = function(n, t, r) {
        return null == t || r ? (k(n) || (n = m.values(n)), n[m.random(n.length - 1)]) : m.shuffle(n).slice(0, Math.max(0, t))
    }, m.sortBy = function(n, t, r) {
        return t = x(t, r), m.pluck(m.map(n, function(n, r, e) {
            return {
                value: n,
                index: r,
                criteria: t(n, r, e)
            }
        }).sort(function(n, t) {
            var r = n.criteria,
                e = t.criteria;
            if (r !== e) {
                if (r > e || void 0 === r) return 1;
                if (e > r || void 0 === e) return -1
            }
            return n.index - t.index
        }), "value")
    };
    var F = function(n) {
        return function(t, r, e) {
            var u = {};
            return r = x(r, e), m.each(t, function(e, i) {
                var o = r(e, i, t);
                n(u, e, o)
            }), u
        }
    };
    m.groupBy = F(function(n, t, r) {
        m.has(n, r) ? n[r].push(t) : n[r] = [t]
    }), m.indexBy = F(function(n, t, r) {
        n[r] = t
    }), m.countBy = F(function(n, t, r) {
        m.has(n, r) ? n[r]++ : n[r] = 1
    }), m.toArray = function(n) {
        return n ? m.isArray(n) ? l.call(n) : k(n) ? m.map(n, m.identity) : m.values(n) : []
    }, m.size = function(n) {
        return null == n ? 0 : k(n) ? n.length : m.keys(n).length
    }, m.partition = function(n, t, r) {
        t = x(t, r);
        var e = [],
            u = [];
        return m.each(n, function(n, r, i) {
            (t(n, r, i) ? e : u).push(n)
        }), [e, u]
    }, m.first = m.head = m.take = function(n, t, r) {
        return null == n ? void 0 : null == t || r ? n[0] : m.initial(n, n.length - t)
    }, m.initial = function(n, t, r) {
        return l.call(n, 0, Math.max(0, n.length - (null == t || r ? 1 : t)))
    }, m.last = function(n, t, r) {
        return null == n ? void 0 : null == t || r ? n[n.length - 1] : m.rest(n, Math.max(0, n.length - t))
    }, m.rest = m.tail = m.drop = function(n, t, r) {
        return l.call(n, null == t || r ? 1 : t)
    }, m.compact = function(n) {
        return m.filter(n, m.identity)
    };
    var S = function(n, t, r, e) {
        for (var u = [], i = 0, o = e || 0, a = O(n); a > o; o++) {
            var c = n[o];
            if (k(c) && (m.isArray(c) || m.isArguments(c))) {
                t || (c = S(c, t, r));
                var f = 0,
                    l = c.length;
                for (u.length += l; l > f;) u[i++] = c[f++]
            } else r || (u[i++] = c)
        }
        return u
    };
    m.flatten = function(n, t) {
        return S(n, t, !1)
    }, m.without = function(n) {
        return m.difference(n, l.call(arguments, 1))
    }, m.uniq = m.unique = function(n, t, r, e) {
        m.isBoolean(t) || (e = r, r = t, t = !1), null != r && (r = x(r, e));
        for (var u = [], i = [], o = 0, a = O(n); a > o; o++) {
            var c = n[o],
                f = r ? r(c, o, n) : c;
            t ? (o && i === f || u.push(c), i = f) : r ? m.contains(i, f) || (i.push(f), u.push(c)) : m.contains(u, c) || u.push(c)
        }
        return u
    }, m.union = function() {
        return m.uniq(S(arguments, !0, !0))
    }, m.intersection = function(n) {
        for (var t = [], r = arguments.length, e = 0, u = O(n); u > e; e++) {
            var i = n[e];
            if (!m.contains(t, i)) {
                for (var o = 1; r > o && m.contains(arguments[o], i); o++);
                o === r && t.push(i)
            }
        }
        return t
    }, m.difference = function(n) {
        var t = S(arguments, !0, !0, 1);
        return m.filter(n, function(n) {
            return !m.contains(t, n)
        })
    }, m.zip = function() {
        return m.unzip(arguments)
    }, m.unzip = function(n) {
        for (var t = n && m.max(n, O).length || 0, r = Array(t), e = 0; t > e; e++) r[e] = m.pluck(n, e);
        return r
    }, m.object = function(n, t) {
        for (var r = {}, e = 0, u = O(n); u > e; e++) t ? r[n[e]] = t[e] : r[n[e][0]] = n[e][1];
        return r
    }, m.findIndex = t(1), m.findLastIndex = t(-1), m.sortedIndex = function(n, t, r, e) {
        r = x(r, e, 1);
        for (var u = r(t), i = 0, o = O(n); o > i;) {
            var a = Math.floor((i + o) / 2);
            r(n[a]) < u ? i = a + 1 : o = a
        }
        return i
    }, m.indexOf = r(1, m.findIndex, m.sortedIndex), m.lastIndexOf = r(-1, m.findLastIndex), m.range = function(n, t, r) {
        null == t && (t = n || 0, n = 0), r = r || 1;
        for (var e = Math.max(Math.ceil((t - n) / r), 0), u = Array(e), i = 0; e > i; i++, n += r) u[i] = n;
        return u
    };
    var E = function(n, t, r, e, u) {
        if (!(e instanceof t)) return n.apply(r, u);
        var i = j(n.prototype),
            o = n.apply(i, u);
        return m.isObject(o) ? o : i
    };
    m.bind = function(n, t) {
        if (g && n.bind === g) return g.apply(n, l.call(arguments, 1));
        if (!m.isFunction(n)) throw new TypeError("Bind must be called on a function");
        var r = l.call(arguments, 2),
            e = function() {
                return E(n, e, t, this, r.concat(l.call(arguments)))
            };
        return e
    }, m.partial = function(n) {
        var t = l.call(arguments, 1),
            r = function() {
                for (var e = 0, u = t.length, i = Array(u), o = 0; u > o; o++) i[o] = t[o] === m ? arguments[e++] : t[o];
                for (; e < arguments.length;) i.push(arguments[e++]);
                return E(n, r, this, this, i)
            };
        return r
    }, m.bindAll = function(n) {
        var t, r, e = arguments.length;
        if (1 >= e) throw new Error("bindAll must be passed function names");
        for (t = 1; e > t; t++) r = arguments[t], n[r] = m.bind(n[r], n);
        return n
    }, m.memoize = function(n, t) {
        var r = function(e) {
            var u = r.cache,
                i = "" + (t ? t.apply(this, arguments) : e);
            return m.has(u, i) || (u[i] = n.apply(this, arguments)), u[i]
        };
        return r.cache = {}, r
    }, m.delay = function(n, t) {
        var r = l.call(arguments, 2);
        return setTimeout(function() {
            return n.apply(null, r)
        }, t)
    }, m.defer = m.partial(m.delay, m, 1), m.throttle = function(n, t, r) {
        var e, u, i, o = null,
            a = 0;
        r || (r = {});
        var c = function() {
            a = r.leading === !1 ? 0 : m.now(), o = null, i = n.apply(e, u), o || (e = u = null)
        };
        return function() {
            var f = m.now();
            a || r.leading !== !1 || (a = f);
            var l = t - (f - a);
            return e = this, u = arguments, 0 >= l || l > t ? (o && (clearTimeout(o), o = null), a = f, i = n.apply(e, u), o || (e = u = null)) : o || r.trailing === !1 || (o = setTimeout(c, l)), i
        }
    }, m.debounce = function(n, t, r) {
        var e, u, i, o, a, c = function() {
            var f = m.now() - o;
            t > f && f >= 0 ? e = setTimeout(c, t - f) : (e = null, r || (a = n.apply(i, u), e || (i = u = null)))
        };
        return function() {
            i = this, u = arguments, o = m.now();
            var f = r && !e;
            return e || (e = setTimeout(c, t)), f && (a = n.apply(i, u), i = u = null), a
        }
    }, m.wrap = function(n, t) {
        return m.partial(t, n)
    }, m.negate = function(n) {
        return function() {
            return !n.apply(this, arguments)
        }
    }, m.compose = function() {
        var n = arguments,
            t = n.length - 1;
        return function() {
            for (var r = t, e = n[t].apply(this, arguments); r--;) e = n[r].call(this, e);
            return e
        }
    }, m.after = function(n, t) {
        return function() {
            return --n < 1 ? t.apply(this, arguments) : void 0
        }
    }, m.before = function(n, t) {
        var r;
        return function() {
            return --n > 0 && (r = t.apply(this, arguments)), 1 >= n && (t = null), r
        }
    }, m.once = m.partial(m.before, 2);
    var M = !{
            toString: null
        }.propertyIsEnumerable("toString"),
        I = ["valueOf", "isPrototypeOf", "toString", "propertyIsEnumerable", "hasOwnProperty", "toLocaleString"];
    m.keys = function(n) {
        if (!m.isObject(n)) return [];
        if (v) return v(n);
        var t = [];
        for (var r in n) m.has(n, r) && t.push(r);
        return M && e(n, t), t
    }, m.allKeys = function(n) {
        if (!m.isObject(n)) return [];
        var t = [];
        for (var r in n) t.push(r);
        return M && e(n, t), t
    }, m.values = function(n) {
        for (var t = m.keys(n), r = t.length, e = Array(r), u = 0; r > u; u++) e[u] = n[t[u]];
        return e
    }, m.mapObject = function(n, t, r) {
        t = x(t, r);
        for (var e, u = m.keys(n), i = u.length, o = {}, a = 0; i > a; a++) e = u[a], o[e] = t(n[e], e, n);
        return o
    }, m.pairs = function(n) {
        for (var t = m.keys(n), r = t.length, e = Array(r), u = 0; r > u; u++) e[u] = [t[u], n[t[u]]];
        return e
    }, m.invert = function(n) {
        for (var t = {}, r = m.keys(n), e = 0, u = r.length; u > e; e++) t[n[r[e]]] = r[e];
        return t
    }, m.functions = m.methods = function(n) {
        var t = [];
        for (var r in n) m.isFunction(n[r]) && t.push(r);
        return t.sort()
    }, m.extend = _(m.allKeys), m.extendOwn = m.assign = _(m.keys), m.findKey = function(n, t, r) {
        t = x(t, r);
        for (var e, u = m.keys(n), i = 0, o = u.length; o > i; i++)
            if (e = u[i], t(n[e], e, n)) return e
    }, m.pick = function(n, t, r) {
        var e, u, i = {},
            o = n;
        if (null == o) return i;
        m.isFunction(t) ? (u = m.allKeys(o), e = b(t, r)) : (u = S(arguments, !1, !1, 1), e = function(n, t, r) {
            return t in r
        }, o = Object(o));
        for (var a = 0, c = u.length; c > a; a++) {
            var f = u[a],
                l = o[f];
            e(l, f, o) && (i[f] = l)
        }
        return i
    }, m.omit = function(n, t, r) {
        if (m.isFunction(t)) t = m.negate(t);
        else {
            var e = m.map(S(arguments, !1, !1, 1), String);
            t = function(n, t) {
                return !m.contains(e, t)
            }
        }
        return m.pick(n, t, r)
    }, m.defaults = _(m.allKeys, !0), m.create = function(n, t) {
        var r = j(n);
        return t && m.extendOwn(r, t), r
    }, m.clone = function(n) {
        return m.isObject(n) ? m.isArray(n) ? n.slice() : m.extend({}, n) : n
    }, m.tap = function(n, t) {
        return t(n), n
    }, m.isMatch = function(n, t) {
        var r = m.keys(t),
            e = r.length;
        if (null == n) return !e;
        for (var u = Object(n), i = 0; e > i; i++) {
            var o = r[i];
            if (t[o] !== u[o] || !(o in u)) return !1
        }
        return !0
    };
    var N = function(n, t, r, e) {
        if (n === t) return 0 !== n || 1 / n === 1 / t;
        if (null == n || null == t) return n === t;
        n instanceof m && (n = n._wrapped), t instanceof m && (t = t._wrapped);
        var u = s.call(n);
        if (u !== s.call(t)) return !1;
        switch (u) {
            case "[object RegExp]":
            case "[object String]":
                return "" + n == "" + t;
            case "[object Number]":
                return +n !== +n ? +t !== +t : 0 === +n ? 1 / +n === 1 / t : +n === +t;
            case "[object Date]":
            case "[object Boolean]":
                return +n === +t
        }
        var i = "[object Array]" === u;
        if (!i) {
            if ("object" != typeof n || "object" != typeof t) return !1;
            var o = n.constructor,
                a = t.constructor;
            if (o !== a && !(m.isFunction(o) && o instanceof o && m.isFunction(a) && a instanceof a) && "constructor" in n && "constructor" in t) return !1
        }
        r = r || [], e = e || [];
        for (var c = r.length; c--;)
            if (r[c] === n) return e[c] === t;
        if (r.push(n), e.push(t), i) {
            if (c = n.length, c !== t.length) return !1;
            for (; c--;)
                if (!N(n[c], t[c], r, e)) return !1
        } else {
            var f, l = m.keys(n);
            if (c = l.length, m.keys(t).length !== c) return !1;
            for (; c--;)
                if (f = l[c], !m.has(t, f) || !N(n[f], t[f], r, e)) return !1
        }
        return r.pop(), e.pop(), !0
    };
    m.isEqual = function(n, t) {
        return N(n, t)
    }, m.isEmpty = function(n) {
        return null == n ? !0 : k(n) && (m.isArray(n) || m.isString(n) || m.isArguments(n)) ? 0 === n.length : 0 === m.keys(n).length
    }, m.isElement = function(n) {
        return !(!n || 1 !== n.nodeType)
    }, m.isArray = h || function(n) {
        return "[object Array]" === s.call(n)
    }, m.isObject = function(n) {
        var t = typeof n;
        return "function" === t || "object" === t && !!n
    }, m.each(["Arguments", "Function", "String", "Number", "Date", "RegExp", "Error"], function(n) {
        m["is" + n] = function(t) {
            return s.call(t) === "[object " + n + "]"
        }
    }), m.isArguments(arguments) || (m.isArguments = function(n) {
        return m.has(n, "callee")
    }), "function" != typeof /./ && "object" != typeof Int8Array && (m.isFunction = function(n) {
        return "function" == typeof n || !1
    }), m.isFinite = function(n) {
        return isFinite(n) && !isNaN(parseFloat(n))
    }, m.isNaN = function(n) {
        return m.isNumber(n) && n !== +n
    }, m.isBoolean = function(n) {
        return n === !0 || n === !1 || "[object Boolean]" === s.call(n)
    }, m.isNull = function(n) {
        return null === n
    }, m.isUndefined = function(n) {
        return void 0 === n
    }, m.has = function(n, t) {
        return null != n && p.call(n, t)
    }, m.noConflict = function() {
        return u._ = i, this
    }, m.identity = function(n) {
        return n
    }, m.constant = function(n) {
        return function() {
            return n
        }
    }, m.noop = function() {}, m.property = w, m.propertyOf = function(n) {
        return null == n ? function() {} : function(t) {
            return n[t]
        }
    }, m.matcher = m.matches = function(n) {
        return n = m.extendOwn({}, n),
            function(t) {
                return m.isMatch(t, n)
            }
    }, m.times = function(n, t, r) {
        var e = Array(Math.max(0, n));
        t = b(t, r, 1);
        for (var u = 0; n > u; u++) e[u] = t(u);
        return e
    }, m.random = function(n, t) {
        return null == t && (t = n, n = 0), n + Math.floor(Math.random() * (t - n + 1))
    }, m.now = Date.now || function() {
        return (new Date).getTime()
    };
    var B = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#x27;",
            "`": "&#x60;"
        },
        T = m.invert(B),
        R = function(n) {
            var t = function(t) {
                    return n[t]
                },
                r = "(?:" + m.keys(n).join("|") + ")",
                e = RegExp(r),
                u = RegExp(r, "g");
            return function(n) {
                return n = null == n ? "" : "" + n, e.test(n) ? n.replace(u, t) : n
            }
        };
    m.escape = R(B), m.unescape = R(T), m.result = function(n, t, r) {
        var e = null == n ? void 0 : n[t];
        return void 0 === e && (e = r), m.isFunction(e) ? e.call(n) : e
    };
    var q = 0;
    m.uniqueId = function(n) {
        var t = ++q + "";
        return n ? n + t : t
    }, m.templateSettings = {
        evaluate: /<%([\s\S]+?)%>/g,
        interpolate: /<%=([\s\S]+?)%>/g,
        escape: /<%-([\s\S]+?)%>/g
    };
    var K = /(.)^/,
        z = {
            "'": "'",
            "\\": "\\",
            "\r": "r",
            "\n": "n",
            "\u2028": "u2028",
            "\u2029": "u2029"
        },
        D = /\\|'|\r|\n|\u2028|\u2029/g,
        L = function(n) {
            return "\\" + z[n]
        };
    m.template = function(n, t, r) {
        !t && r && (t = r), t = m.defaults({}, t, m.templateSettings);
        var e = RegExp([(t.escape || K).source, (t.interpolate || K).source, (t.evaluate || K).source].join("|") + "|$", "g"),
            u = 0,
            i = "__p+='";
        n.replace(e, function(t, r, e, o, a) {
            return i += n.slice(u, a).replace(D, L), u = a + t.length, r ? i += "'+\n((__t=(" + r + "))==null?'':_.escape(__t))+\n'" : e ? i += "'+\n((__t=(" + e + "))==null?'':__t)+\n'" : o && (i += "';\n" + o + "\n__p+='"), t
        }), i += "';\n", t.variable || (i = "with(obj||{}){\n" + i + "}\n"), i = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + i + "return __p;\n";
        try {
            var o = new Function(t.variable || "obj", "_", i)
        } catch (a) {
            throw a.source = i, a
        }
        var c = function(n) {
                return o.call(this, n, m)
            },
            f = t.variable || "obj";
        return c.source = "function(" + f + "){\n" + i + "}", c
    }, m.chain = function(n) {
        var t = m(n);
        return t._chain = !0, t
    };
    var P = function(n, t) {
        return n._chain ? m(t).chain() : t
    };
    m.mixin = function(n) {
        m.each(m.functions(n), function(t) {
            var r = m[t] = n[t];
            m.prototype[t] = function() {
                var n = [this._wrapped];
                return f.apply(n, arguments), P(this, r.apply(m, n))
            }
        })
    }, m.mixin(m), m.each(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(n) {
        var t = o[n];
        m.prototype[n] = function() {
            var r = this._wrapped;
            return t.apply(r, arguments), "shift" !== n && "splice" !== n || 0 !== r.length || delete r[0], P(this, r)
        }
    }), m.each(["concat", "join", "slice"], function(n) {
        var t = o[n];
        m.prototype[n] = function() {
            return P(this, t.apply(this._wrapped, arguments))
        }
    }), m.prototype.value = function() {
        return this._wrapped
    }, m.prototype.valueOf = m.prototype.toJSON = m.prototype.value, m.prototype.toString = function() {
        return "" + this._wrapped
    }, "function" == typeof define && define.amd && define("underscore", [], function() {
        return m
    })
}).call(this);
! function(t) {
    var e = -1,
        a = -1,
        i = function(t) {
            return parseFloat(t) || 0
        },
        o = function(e) {
            var a = null,
                o = [];
            return t(e).each(function() {
                var e = t(this),
                    n = e.offset().top - i(e.css("margin-top")),
                    r = 0 < o.length ? o[o.length - 1] : null;
                null === r ? o.push(e) : 1 >= Math.floor(Math.abs(a - n)) ? o[o.length - 1] = r.add(e) : o.push(e), a = n
            }), o
        },
        n = function(e) {
            var a = {
                byRow: !0,
                property: "height",
                target: null,
                remove: !1
            };
            return "object" == typeof e ? t.extend(a, e) : ("boolean" == typeof e ? a.byRow = e : "remove" === e && (a.remove = !0), a)
        },
        r = t.fn.matchHeight = function(e) {
            if (e = n(e), e.remove) {
                var a = this;
                return this.css(e.property, ""), t.each(r._groups, function(t, e) {
                    e.elements = e.elements.not(a)
                }), this
            }
            return 1 >= this.length && !e.target ? this : (r._groups.push({
                elements: this,
                options: e
            }), r._apply(this, e), this)
        };
    r._groups = [], r._throttle = 80, r._maintainScroll = !1, r._beforeUpdate = null, r._afterUpdate = null, r._apply = function(e, a) {
        var s = n(a),
            h = t(e),
            c = [h],
            l = t(window).scrollTop(),
            p = t("html").outerHeight(!0),
            u = h.parents().filter(":hidden");
        return u.each(function() {
            var e = t(this);
            e.data("style-cache", e.attr("style"))
        }), u.css("display", "block"), s.byRow && !s.target && (h.each(function() {
            var e = t(this),
                a = e.css("display");
            "inline-block" !== a && "inline-flex" !== a && (a = "block"), e.data("style-cache", e.attr("style")), e.css({
                display: a,
                "padding-top": "0",
                "padding-bottom": "0",
                "margin-top": "0",
                "margin-bottom": "0",
                "border-top-width": "0",
                "border-bottom-width": "0",
                height: "100px"
            })
        }), c = o(h), h.each(function() {
            var e = t(this);
            e.attr("style", e.data("style-cache") || "")
        })), t.each(c, function(e, a) {
            var o = t(a),
                n = 0;
            if (s.target) n = s.target.outerHeight(!1);
            else {
                if (s.byRow && 1 >= o.length) return void o.css(s.property, "");
                o.each(function() {
                    var e = t(this),
                        a = e.css("display");
                    "inline-block" !== a && "inline-flex" !== a && (a = "block"), a = {
                        display: a
                    }, a[s.property] = "", e.css(a), e.outerHeight(!1) > n && (n = e.outerHeight(!1)), e.css("display", "")
                })
            }
            o.each(function() {
                var e = t(this),
                    a = 0;
                s.target && e.is(s.target) || ("border-box" !== e.css("box-sizing") && (a += i(e.css("border-top-width")) + i(e.css("border-bottom-width")), a += i(e.css("padding-top")) + i(e.css("padding-bottom"))), e.css(s.property, n - a + "px"))
            })
        }), u.each(function() {
            var e = t(this);
            e.attr("style", e.data("style-cache") || null)
        }), r._maintainScroll && t(window).scrollTop(l / p * t("html").outerHeight(!0)), this
    }, r._applyDataApi = function() {
        var e = {};
        t("[data-match-height], [data-mh]").each(function() {
            var a = t(this),
                i = a.attr("data-mh") || a.attr("data-match-height");
            e[i] = i in e ? e[i].add(a) : a
        }), t.each(e, function() {
            this.matchHeight(!0)
        })
    };
    var s = function(e) {
        r._beforeUpdate && r._beforeUpdate(e, r._groups), t.each(r._groups, function() {
            r._apply(this.elements, this.options)
        }), r._afterUpdate && r._afterUpdate(e, r._groups)
    };
    r._update = function(i, o) {
        if (o && "resize" === o.type) {
            var n = t(window).width();
            if (n === e) return;
            e = n
        }
        i ? -1 === a && (a = setTimeout(function() {
            s(o), a = -1
        }, r._throttle)) : s(o)
    }, t(r._applyDataApi), t(window).bind("load", function(t) {
        r._update(!1, t)
    }), t(window).bind("resize orientationchange", function(t) {
        r._update(!0, t)
    })
}(jQuery);
! function(e) {
    e.fn.hoverIntent = function(t, n, o) {
        var r = {
            interval: 100,
            sensitivity: 6,
            timeout: 0
        };
        r = "object" == typeof t ? e.extend(r, t) : e.isFunction(n) ? e.extend(r, {
            over: t,
            out: n,
            selector: o
        }) : e.extend(r, {
            over: t,
            out: t,
            selector: n
        });
        var v, i, u, s, h = function(e) {
                v = e.pageX, i = e.pageY
            },
            I = function(t, n) {
                return n.hoverIntent_t = clearTimeout(n.hoverIntent_t), Math.sqrt((u - v) * (u - v) + (s - i) * (s - i)) < r.sensitivity ? (e(n).off("mousemove.hoverIntent", h), n.hoverIntent_s = !0, r.over.apply(n, [t])) : (u = v, s = i, n.hoverIntent_t = setTimeout(function() {
                    I(t, n)
                }, r.interval), void 0)
            },
            a = function(e, t) {
                return t.hoverIntent_t = clearTimeout(t.hoverIntent_t), t.hoverIntent_s = !1, r.out.apply(t, [e])
            },
            c = function(t) {
                var n = e.extend({}, t),
                    o = this;
                o.hoverIntent_t && (o.hoverIntent_t = clearTimeout(o.hoverIntent_t)), "mouseenter" === t.type ? (u = n.pageX, s = n.pageY, e(o).on("mousemove.hoverIntent", h), o.hoverIntent_s || (o.hoverIntent_t = setTimeout(function() {
                    I(n, o)
                }, r.interval))) : (e(o).off("mousemove.hoverIntent", h), o.hoverIntent_s && (o.hoverIntent_t = setTimeout(function() {
                    a(n, o)
                }, r.timeout)))
            };
        return this.on({
            "mouseenter.hoverIntent": c,
            "mouseleave.hoverIntent": c
        }, r.selector)
    }
}(jQuery);
! function(t, n) {
    "use strict";

    function i(t) {
        this.callback = t, this.ticking = !1
    }

    function e(n) {
        return n && "undefined" != typeof t && (n === t || n.nodeType)
    }

    function s(t) {
        if (arguments.length <= 0) throw new Error("Missing arguments in extend function");
        var n, i, o = t || {};
        for (i = 1; i < arguments.length; i++) {
            var r = arguments[i] || {};
            for (n in r) o[n] = "object" != typeof o[n] || e(o[n]) ? o[n] || r[n] : s(o[n], r[n])
        }
        return o
    }

    function o(t) {
        return t === Object(t) ? t : {
            down: t,
            up: t
        }
    }

    function r(t, n) {
        n = s(n, r.options), this.lastKnownScrollY = 0, this.elem = t, this.debouncer = new i(this.update.bind(this)), this.tolerance = o(n.tolerance), this.classes = n.classes, this.offset = n.offset, this.scroller = n.scroller, this.initialised = !1, this.onPin = n.onPin, this.onUnpin = n.onUnpin, this.onTop = n.onTop, this.onNotTop = n.onNotTop
    }
    var l = {
        bind: !! function() {}.bind,
        classList: "classList" in n.documentElement,
        rAF: !!(t.requestAnimationFrame || t.webkitRequestAnimationFrame || t.mozRequestAnimationFrame)
    };
    t.requestAnimationFrame = t.requestAnimationFrame || t.webkitRequestAnimationFrame || t.mozRequestAnimationFrame, i.prototype = {
        constructor: i,
        update: function() {
            this.callback && this.callback(), this.ticking = !1
        },
        requestTick: function() {
            this.ticking || (requestAnimationFrame(this.rafCallback || (this.rafCallback = this.update.bind(this))), this.ticking = !0)
        },
        handleEvent: function() {
            this.requestTick()
        }
    }, r.prototype = {
        constructor: r,
        init: function() {
            return r.cutsTheMustard ? (this.elem.classList.add(this.classes.initial), setTimeout(this.attachEvent.bind(this), 100), this) : void 0
        },
        destroy: function() {
            var t = this.classes;
            this.initialised = !1, this.elem.classList.remove(t.unpinned, t.pinned, t.top, t.initial), this.scroller.removeEventListener("scroll", this.debouncer, !1)
        },
        attachEvent: function() {
            this.initialised || (this.lastKnownScrollY = this.getScrollY(), this.initialised = !0, this.scroller.addEventListener("scroll", this.debouncer, !1), this.debouncer.handleEvent())
        },
        unpin: function() {
            var t = this.elem.classList,
                n = this.classes;
            (t.contains(n.pinned) || !t.contains(n.unpinned)) && (t.add(n.unpinned), t.remove(n.pinned), this.onUnpin && this.onUnpin.call(this))
        },
        pin: function() {
            var t = this.elem.classList,
                n = this.classes;
            t.contains(n.unpinned) && (t.remove(n.unpinned), t.add(n.pinned), this.onPin && this.onPin.call(this))
        },
        top: function() {
            var t = this.elem.classList,
                n = this.classes;
            t.contains(n.top) || (t.add(n.top), t.remove(n.notTop), this.onTop && this.onTop.call(this))
        },
        notTop: function() {
            var t = this.elem.classList,
                n = this.classes;
            t.contains(n.notTop) || (t.add(n.notTop), t.remove(n.top), this.onNotTop && this.onNotTop.call(this))
        },
        getScrollY: function() {
            return void 0 !== this.scroller.pageYOffset ? this.scroller.pageYOffset : void 0 !== this.scroller.scrollTop ? this.scroller.scrollTop : (n.documentElement || n.body.parentNode || n.body).scrollTop
        },
        getViewportHeight: function() {
            return t.innerHeight || n.documentElement.clientHeight || n.body.clientHeight
        },
        getDocumentHeight: function() {
            var t = n.body,
                i = n.documentElement;
            return Math.max(t.scrollHeight, i.scrollHeight, t.offsetHeight, i.offsetHeight, t.clientHeight, i.clientHeight)
        },
        getElementHeight: function(t) {
            return Math.max(t.scrollHeight, t.offsetHeight, t.clientHeight)
        },
        getScrollerHeight: function() {
            return this.scroller === t || this.scroller === n.body ? this.getDocumentHeight() : this.getElementHeight(this.scroller)
        },
        isOutOfBounds: function(t) {
            var n = 0 > t,
                i = t + this.getViewportHeight() > this.getScrollerHeight();
            return n || i
        },
        toleranceExceeded: function(t, n) {
            return Math.abs(t - this.lastKnownScrollY) >= this.tolerance[n]
        },
        shouldUnpin: function(t, n) {
            var i = t > this.lastKnownScrollY,
                e = t >= this.offset;
            return i && e && n
        },
        shouldPin: function(t, n) {
            var i = t < this.lastKnownScrollY,
                e = t <= this.offset;
            return i && n || e
        },
        update: function() {
            var t = this.getScrollY(),
                n = t > this.lastKnownScrollY ? "down" : "up",
                i = this.toleranceExceeded(t, n);
            this.isOutOfBounds(t) || (t <= this.offset ? this.top() : this.notTop(), this.shouldUnpin(t, i) ? this.unpin() : this.shouldPin(t, i) && this.pin(), this.lastKnownScrollY = t)
        }
    }, r.options = {
        tolerance: {
            up: 0,
            down: 0
        },
        offset: 0,
        scroller: t,
        classes: {
            pinned: "headroom--pinned",
            unpinned: "headroom--unpinned",
            top: "headroom--top",
            notTop: "headroom--not-top",
            initial: "headroom"
        }
    }, r.cutsTheMustard = "undefined" != typeof l && l.rAF && l.bind && l.classList, t.Headroom = r
}(window, document);
! function(i) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], i) : "undefined" != typeof exports ? module.exports = i(require("jquery")) : i(jQuery)
}(function(i) {
    "use strict";
    var e = window.Slick || {};
    e = function() {
        function e(e, o) {
            var s, n = this;
            n.defaults = {
                accessibility: !0,
                adaptiveHeight: !1,
                appendArrows: i(e),
                appendDots: i(e),
                arrows: !0,
                asNavFor: null,
                prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button">Previous</button>',
                nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button">Next</button>',
                autoplay: !1,
                autoplaySpeed: 3e3,
                centerMode: !1,
                centerPadding: "50px",
                cssEase: "ease",
                customPaging: function(i, e) {
                    return '<button type="button" data-role="none" role="button" aria-required="false" tabindex="0">' + (e + 1) + "</button>"
                },
                dots: !1,
                dotsClass: "slick-dots",
                draggable: !0,
                easing: "linear",
                edgeFriction: .35,
                fade: !1,
                focusOnSelect: !1,
                infinite: !0,
                initialSlide: 0,
                lazyLoad: "ondemand",
                mobileFirst: !1,
                pauseOnHover: !0,
                pauseOnDotsHover: !1,
                respondTo: "window",
                responsive: null,
                rows: 1,
                rtl: !1,
                slide: "",
                slidesPerRow: 1,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 500,
                swipe: !0,
                swipeToSlide: !1,
                touchMove: !0,
                touchThreshold: 5,
                useCSS: !0,
                variableWidth: !1,
                vertical: !1,
                verticalSwiping: !1,
                waitForAnimate: !0,
                zIndex: 1e3
            }, n.initials = {
                animating: !1,
                dragging: !1,
                autoPlayTimer: null,
                currentDirection: 0,
                currentLeft: null,
                currentSlide: 0,
                direction: 1,
                $dots: null,
                listWidth: null,
                listHeight: null,
                loadIndex: 0,
                $nextArrow: null,
                $prevArrow: null,
                slideCount: null,
                slideWidth: null,
                $slideTrack: null,
                $slides: null,
                sliding: !1,
                slideOffset: 0,
                swipeLeft: null,
                $list: null,
                touchObject: {},
                transformsEnabled: !1,
                unslicked: !1
            }, i.extend(n, n.initials), n.activeBreakpoint = null, n.animType = null, n.animProp = null, n.breakpoints = [], n.breakpointSettings = [], n.cssTransitions = !1, n.hidden = "hidden", n.paused = !1, n.positionProp = null, n.respondTo = null, n.rowCount = 1, n.shouldClick = !0, n.$slider = i(e), n.$slidesCache = null, n.transformType = null, n.transitionType = null, n.visibilityChange = "visibilitychange", n.windowWidth = 0, n.windowTimer = null, s = i(e).data("slick") || {}, n.options = i.extend({}, n.defaults, s, o), n.currentSlide = n.options.initialSlide, n.originalSettings = n.options, "undefined" != typeof document.mozHidden ? (n.hidden = "mozHidden", n.visibilityChange = "mozvisibilitychange") : "undefined" != typeof document.webkitHidden && (n.hidden = "webkitHidden", n.visibilityChange = "webkitvisibilitychange"), n.autoPlay = i.proxy(n.autoPlay, n), n.autoPlayClear = i.proxy(n.autoPlayClear, n), n.changeSlide = i.proxy(n.changeSlide, n), n.clickHandler = i.proxy(n.clickHandler, n), n.selectHandler = i.proxy(n.selectHandler, n), n.setPosition = i.proxy(n.setPosition, n), n.swipeHandler = i.proxy(n.swipeHandler, n), n.dragHandler = i.proxy(n.dragHandler, n), n.keyHandler = i.proxy(n.keyHandler, n), n.autoPlayIterator = i.proxy(n.autoPlayIterator, n), n.instanceUid = t++, n.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/, n.registerBreakpoints(), n.init(!0), n.checkResponsive(!0)
        }
        var t = 0;
        return e
    }(), e.prototype.addSlide = e.prototype.slickAdd = function(e, t, o) {
        var s = this;
        if ("boolean" == typeof t) o = t, t = null;
        else if (0 > t || t >= s.slideCount) return !1;
        s.unload(), "number" == typeof t ? 0 === t && 0 === s.$slides.length ? i(e).appendTo(s.$slideTrack) : o ? i(e).insertBefore(s.$slides.eq(t)) : i(e).insertAfter(s.$slides.eq(t)) : o === !0 ? i(e).prependTo(s.$slideTrack) : i(e).appendTo(s.$slideTrack), s.$slides = s.$slideTrack.children(this.options.slide), s.$slideTrack.children(this.options.slide).detach(), s.$slideTrack.append(s.$slides), s.$slides.each(function(e, t) {
            i(t).attr("data-slick-index", e)
        }), s.$slidesCache = s.$slides, s.reinit()
    }, e.prototype.animateHeight = function() {
        var i = this;
        if (1 === i.options.slidesToShow && i.options.adaptiveHeight === !0 && i.options.vertical === !1) {
            var e = i.$slides.eq(i.currentSlide).outerHeight(!0);
            i.$list.animate({
                height: e
            }, i.options.speed)
        }
    }, e.prototype.animateSlide = function(e, t) {
        var o = {},
            s = this;
        s.animateHeight(), s.options.rtl === !0 && s.options.vertical === !1 && (e = -e), s.transformsEnabled === !1 ? s.options.vertical === !1 ? s.$slideTrack.animate({
            left: e
        }, s.options.speed, s.options.easing, t) : s.$slideTrack.animate({
            top: e
        }, s.options.speed, s.options.easing, t) : s.cssTransitions === !1 ? (s.options.rtl === !0 && (s.currentLeft = -s.currentLeft), i({
            animStart: s.currentLeft
        }).animate({
            animStart: e
        }, {
            duration: s.options.speed,
            easing: s.options.easing,
            step: function(i) {
                i = Math.ceil(i), s.options.vertical === !1 ? (o[s.animType] = "translate(" + i + "px, 0px)", s.$slideTrack.css(o)) : (o[s.animType] = "translate(0px," + i + "px)", s.$slideTrack.css(o))
            },
            complete: function() {
                t && t.call()
            }
        })) : (s.applyTransition(), e = Math.ceil(e), o[s.animType] = s.options.vertical === !1 ? "translate3d(" + e + "px, 0px, 0px)" : "translate3d(0px," + e + "px, 0px)", s.$slideTrack.css(o), t && setTimeout(function() {
            s.disableTransition(), t.call()
        }, s.options.speed))
    }, e.prototype.asNavFor = function(e) {
        var t = this,
            o = t.options.asNavFor;
        o && null !== o && (o = i(o).not(t.$slider)), null !== o && "object" == typeof o && o.each(function() {
            var t = i(this).slick("getSlick");
            t.unslicked || t.slideHandler(e, !0)
        })
    }, e.prototype.applyTransition = function(i) {
        var e = this,
            t = {};
        t[e.transitionType] = e.options.fade === !1 ? e.transformType + " " + e.options.speed + "ms " + e.options.cssEase : "opacity " + e.options.speed + "ms " + e.options.cssEase, e.options.fade === !1 ? e.$slideTrack.css(t) : e.$slides.eq(i).css(t)
    }, e.prototype.autoPlay = function() {
        var i = this;
        i.autoPlayTimer && clearInterval(i.autoPlayTimer), i.slideCount > i.options.slidesToShow && i.paused !== !0 && (i.autoPlayTimer = setInterval(i.autoPlayIterator, i.options.autoplaySpeed))
    }, e.prototype.autoPlayClear = function() {
        var i = this;
        i.autoPlayTimer && clearInterval(i.autoPlayTimer)
    }, e.prototype.autoPlayIterator = function() {
        var i = this;
        i.options.infinite === !1 ? 1 === i.direction ? (i.currentSlide + 1 === i.slideCount - 1 && (i.direction = 0), i.slideHandler(i.currentSlide + i.options.slidesToScroll)) : (0 === i.currentSlide - 1 && (i.direction = 1), i.slideHandler(i.currentSlide - i.options.slidesToScroll)) : i.slideHandler(i.currentSlide + i.options.slidesToScroll)
    }, e.prototype.buildArrows = function() {
        var e = this;
        e.options.arrows === !0 && (e.$prevArrow = i(e.options.prevArrow).addClass("slick-arrow"), e.$nextArrow = i(e.options.nextArrow).addClass("slick-arrow"), e.slideCount > e.options.slidesToShow ? (e.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"), e.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"), e.htmlExpr.test(e.options.prevArrow) && e.$prevArrow.prependTo(e.options.appendArrows), e.htmlExpr.test(e.options.nextArrow) && e.$nextArrow.appendTo(e.options.appendArrows), e.options.infinite !== !0 && e.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true")) : e.$prevArrow.add(e.$nextArrow).addClass("slick-hidden").attr({
            "aria-disabled": "true",
            tabindex: "-1"
        }))
    }, e.prototype.buildDots = function() {
        var e, t, o = this;
        if (o.options.dots === !0 && o.slideCount > o.options.slidesToShow) {
            for (t = '<ul class="' + o.options.dotsClass + '">', e = 0; e <= o.getDotCount(); e += 1) t += "<li>" + o.options.customPaging.call(this, o, e) + "</li>";
            t += "</ul>", o.$dots = i(t).appendTo(o.options.appendDots), o.$dots.find("li").first().addClass("slick-active").attr("aria-hidden", "false")
        }
    }, e.prototype.buildOut = function() {
        var e = this;
        e.$slides = e.$slider.children(e.options.slide + ":not(.slick-cloned)").addClass("slick-slide"), e.slideCount = e.$slides.length, e.$slides.each(function(e, t) {
            i(t).attr("data-slick-index", e).data("originalStyling", i(t).attr("style") || "")
        }), e.$slidesCache = e.$slides, e.$slider.addClass("slick-slider"), e.$slideTrack = 0 === e.slideCount ? i('<div class="slick-track"/>').appendTo(e.$slider) : e.$slides.wrapAll('<div class="slick-track"/>').parent(), e.$list = e.$slideTrack.wrap('<div aria-live="polite" class="slick-list"/>').parent(), e.$slideTrack.css("opacity", 0), (e.options.centerMode === !0 || e.options.swipeToSlide === !0) && (e.options.slidesToScroll = 1), i("img[data-lazy]", e.$slider).not("[src]").addClass("slick-loading"), e.setupInfinite(), e.buildArrows(), e.buildDots(), e.updateDots(), e.setSlideClasses("number" == typeof e.currentSlide ? e.currentSlide : 0), e.options.draggable === !0 && e.$list.addClass("draggable")
    }, e.prototype.buildRows = function() {
        var i, e, t, o, s, n, r, l = this;
        if (o = document.createDocumentFragment(), n = l.$slider.children(), l.options.rows > 1) {
            for (r = l.options.slidesPerRow * l.options.rows, s = Math.ceil(n.length / r), i = 0; s > i; i++) {
                var a = document.createElement("div");
                for (e = 0; e < l.options.rows; e++) {
                    var d = document.createElement("div");
                    for (t = 0; t < l.options.slidesPerRow; t++) {
                        var c = i * r + (e * l.options.slidesPerRow + t);
                        n.get(c) && d.appendChild(n.get(c))
                    }
                    a.appendChild(d)
                }
                o.appendChild(a)
            }
            l.$slider.html(o), l.$slider.children().children().children().css({
                width: 100 / l.options.slidesPerRow + "%",
                display: "inline-block"
            })
        }
    }, e.prototype.checkResponsive = function(e, t) {
        var o, s, n, r = this,
            l = !1,
            a = r.$slider.width(),
            d = window.innerWidth || i(window).width();
        if ("window" === r.respondTo ? n = d : "slider" === r.respondTo ? n = a : "min" === r.respondTo && (n = Math.min(d, a)), r.options.responsive && r.options.responsive.length && null !== r.options.responsive) {
            s = null;
            for (o in r.breakpoints) r.breakpoints.hasOwnProperty(o) && (r.originalSettings.mobileFirst === !1 ? n < r.breakpoints[o] && (s = r.breakpoints[o]) : n > r.breakpoints[o] && (s = r.breakpoints[o]));
            null !== s ? null !== r.activeBreakpoint ? (s !== r.activeBreakpoint || t) && (r.activeBreakpoint = s, "unslick" === r.breakpointSettings[s] ? r.unslick(s) : (r.options = i.extend({}, r.originalSettings, r.breakpointSettings[s]), e === !0 && (r.currentSlide = r.options.initialSlide), r.refresh(e)), l = s) : (r.activeBreakpoint = s, "unslick" === r.breakpointSettings[s] ? r.unslick(s) : (r.options = i.extend({}, r.originalSettings, r.breakpointSettings[s]), e === !0 && (r.currentSlide = r.options.initialSlide), r.refresh(e)), l = s) : null !== r.activeBreakpoint && (r.activeBreakpoint = null, r.options = r.originalSettings, e === !0 && (r.currentSlide = r.options.initialSlide), r.refresh(e), l = s), e || l === !1 || r.$slider.trigger("breakpoint", [r, l])
        }
    }, e.prototype.changeSlide = function(e, t) {
        var o, s, n, r = this,
            l = i(e.target);
        switch (l.is("a") && e.preventDefault(), l.is("li") || (l = l.closest("li")), n = 0 !== r.slideCount % r.options.slidesToScroll, o = n ? 0 : (r.slideCount - r.currentSlide) % r.options.slidesToScroll, e.data.message) {
            case "previous":
                s = 0 === o ? r.options.slidesToScroll : r.options.slidesToShow - o, r.slideCount > r.options.slidesToShow && r.slideHandler(r.currentSlide - s, !1, t);
                break;
            case "next":
                s = 0 === o ? r.options.slidesToScroll : o, r.slideCount > r.options.slidesToShow && r.slideHandler(r.currentSlide + s, !1, t);
                break;
            case "index":
                var a = 0 === e.data.index ? 0 : e.data.index || l.index() * r.options.slidesToScroll;
                r.slideHandler(r.checkNavigable(a), !1, t), l.children().trigger("focus");
                break;
            default:
                return
        }
    }, e.prototype.checkNavigable = function(i) {
        var e, t, o = this;
        if (e = o.getNavigableIndexes(), t = 0, i > e[e.length - 1]) i = e[e.length - 1];
        else
            for (var s in e) {
                if (i < e[s]) {
                    i = t;
                    break
                }
                t = e[s]
            }
        return i
    }, e.prototype.cleanUpEvents = function() {
        var e = this;
        e.options.dots && null !== e.$dots && (i("li", e.$dots).off("click.slick", e.changeSlide), e.options.pauseOnDotsHover === !0 && e.options.autoplay === !0 && i("li", e.$dots).off("mouseenter.slick", i.proxy(e.setPaused, e, !0)).off("mouseleave.slick", i.proxy(e.setPaused, e, !1))), e.options.arrows === !0 && e.slideCount > e.options.slidesToShow && (e.$prevArrow && e.$prevArrow.off("click.slick", e.changeSlide), e.$nextArrow && e.$nextArrow.off("click.slick", e.changeSlide)), e.$list.off("touchstart.slick mousedown.slick", e.swipeHandler), e.$list.off("touchmove.slick mousemove.slick", e.swipeHandler), e.$list.off("touchend.slick mouseup.slick", e.swipeHandler), e.$list.off("touchcancel.slick mouseleave.slick", e.swipeHandler), e.$list.off("click.slick", e.clickHandler), i(document).off(e.visibilityChange, e.visibility), e.$list.off("mouseenter.slick", i.proxy(e.setPaused, e, !0)), e.$list.off("mouseleave.slick", i.proxy(e.setPaused, e, !1)), e.options.accessibility === !0 && e.$list.off("keydown.slick", e.keyHandler), e.options.focusOnSelect === !0 && i(e.$slideTrack).children().off("click.slick", e.selectHandler), i(window).off("orientationchange.slick.slick-" + e.instanceUid, e.orientationChange), i(window).off("resize.slick.slick-" + e.instanceUid, e.resize), i("[draggable!=true]", e.$slideTrack).off("dragstart", e.preventDefault), i(window).off("load.slick.slick-" + e.instanceUid, e.setPosition), i(document).off("ready.slick.slick-" + e.instanceUid, e.setPosition)
    }, e.prototype.cleanUpRows = function() {
        var i, e = this;
        e.options.rows > 1 && (i = e.$slides.children().children(), i.removeAttr("style"), e.$slider.html(i))
    }, e.prototype.clickHandler = function(i) {
        var e = this;
        e.shouldClick === !1 && (i.stopImmediatePropagation(), i.stopPropagation(), i.preventDefault())
    }, e.prototype.destroy = function(e) {
        var t = this;
        t.autoPlayClear(), t.touchObject = {}, t.cleanUpEvents(), i(".slick-cloned", t.$slider).detach(), t.$dots && t.$dots.remove(), t.options.arrows === !0 && (t.$prevArrow && t.$prevArrow.length && (t.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""), t.htmlExpr.test(t.options.prevArrow) && t.$prevArrow.remove()), t.$nextArrow && t.$nextArrow.length && (t.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""), t.htmlExpr.test(t.options.nextArrow) && t.$nextArrow.remove())), t.$slides && (t.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each(function() {
            i(this).attr("style", i(this).data("originalStyling"))
        }), t.$slideTrack.children(this.options.slide).detach(), t.$slideTrack.detach(), t.$list.detach(), t.$slider.append(t.$slides)), t.cleanUpRows(), t.$slider.removeClass("slick-slider"), t.$slider.removeClass("slick-initialized"), t.unslicked = !0, e || t.$slider.trigger("destroy", [t])
    }, e.prototype.disableTransition = function(i) {
        var e = this,
            t = {};
        t[e.transitionType] = "", e.options.fade === !1 ? e.$slideTrack.css(t) : e.$slides.eq(i).css(t)
    }, e.prototype.fadeSlide = function(i, e) {
        var t = this;
        t.cssTransitions === !1 ? (t.$slides.eq(i).css({
            zIndex: t.options.zIndex
        }), t.$slides.eq(i).animate({
            opacity: 1
        }, t.options.speed, t.options.easing, e)) : (t.applyTransition(i), t.$slides.eq(i).css({
            opacity: 1,
            zIndex: t.options.zIndex
        }), e && setTimeout(function() {
            t.disableTransition(i), e.call()
        }, t.options.speed))
    }, e.prototype.fadeSlideOut = function(i) {
        var e = this;
        e.cssTransitions === !1 ? e.$slides.eq(i).animate({
            opacity: 0,
            zIndex: e.options.zIndex - 2
        }, e.options.speed, e.options.easing) : (e.applyTransition(i), e.$slides.eq(i).css({
            opacity: 0,
            zIndex: e.options.zIndex - 2
        }))
    }, e.prototype.filterSlides = e.prototype.slickFilter = function(i) {
        var e = this;
        null !== i && (e.unload(), e.$slideTrack.children(this.options.slide).detach(), e.$slidesCache.filter(i).appendTo(e.$slideTrack), e.reinit())
    }, e.prototype.getCurrent = e.prototype.slickCurrentSlide = function() {
        var i = this;
        return i.currentSlide
    }, e.prototype.getDotCount = function() {
        var i = this,
            e = 0,
            t = 0,
            o = 0;
        if (i.options.infinite === !0)
            for (; e < i.slideCount;) ++o, e = t + i.options.slidesToShow, t += i.options.slidesToScroll <= i.options.slidesToShow ? i.options.slidesToScroll : i.options.slidesToShow;
        else if (i.options.centerMode === !0) o = i.slideCount;
        else
            for (; e < i.slideCount;) ++o, e = t + i.options.slidesToShow, t += i.options.slidesToScroll <= i.options.slidesToShow ? i.options.slidesToScroll : i.options.slidesToShow;
        return o - 1
    }, e.prototype.getLeft = function(i) {
        var e, t, o, s = this,
            n = 0;
        return s.slideOffset = 0, t = s.$slides.first().outerHeight(!0), s.options.infinite === !0 ? (s.slideCount > s.options.slidesToShow && (s.slideOffset = -1 * s.slideWidth * s.options.slidesToShow, n = -1 * t * s.options.slidesToShow), 0 !== s.slideCount % s.options.slidesToScroll && i + s.options.slidesToScroll > s.slideCount && s.slideCount > s.options.slidesToShow && (i > s.slideCount ? (s.slideOffset = -1 * (s.options.slidesToShow - (i - s.slideCount)) * s.slideWidth, n = -1 * (s.options.slidesToShow - (i - s.slideCount)) * t) : (s.slideOffset = -1 * s.slideCount % s.options.slidesToScroll * s.slideWidth, n = -1 * s.slideCount % s.options.slidesToScroll * t))) : i + s.options.slidesToShow > s.slideCount && (s.slideOffset = (i + s.options.slidesToShow - s.slideCount) * s.slideWidth, n = (i + s.options.slidesToShow - s.slideCount) * t), s.slideCount <= s.options.slidesToShow && (s.slideOffset = 0, n = 0), s.options.centerMode === !0 && s.options.infinite === !0 ? s.slideOffset += s.slideWidth * Math.floor(s.options.slidesToShow / 2) - s.slideWidth : s.options.centerMode === !0 && (s.slideOffset = 0, s.slideOffset += s.slideWidth * Math.floor(s.options.slidesToShow / 2)), e = s.options.vertical === !1 ? -1 * i * s.slideWidth + s.slideOffset : -1 * i * t + n, s.options.variableWidth === !0 && (o = s.$slideTrack.children(".slick-slide").eq(s.slideCount <= s.options.slidesToShow || s.options.infinite === !1 ? i : i + s.options.slidesToShow), e = o[0] ? -1 * o[0].offsetLeft : 0, s.options.centerMode === !0 && (o = s.$slideTrack.children(".slick-slide").eq(s.options.infinite === !1 ? i : i + s.options.slidesToShow + 1), e = o[0] ? -1 * o[0].offsetLeft : 0, e += (s.$list.width() - o.outerWidth()) / 2)), e
    }, e.prototype.getOption = e.prototype.slickGetOption = function(i) {
        var e = this;
        return e.options[i]
    }, e.prototype.getNavigableIndexes = function() {
        var i, e = this,
            t = 0,
            o = 0,
            s = [];
        for (e.options.infinite === !1 ? i = e.slideCount : (t = -1 * e.options.slidesToScroll, o = -1 * e.options.slidesToScroll, i = 2 * e.slideCount); i > t;) s.push(t), t = o + e.options.slidesToScroll, o += e.options.slidesToScroll <= e.options.slidesToShow ? e.options.slidesToScroll : e.options.slidesToShow;
        return s
    }, e.prototype.getSlick = function() {
        return this
    }, e.prototype.getSlideCount = function() {
        var e, t, o, s = this;
        return o = s.options.centerMode === !0 ? s.slideWidth * Math.floor(s.options.slidesToShow / 2) : 0, s.options.swipeToSlide === !0 ? (s.$slideTrack.find(".slick-slide").each(function(e, n) {
            return n.offsetLeft - o + i(n).outerWidth() / 2 > -1 * s.swipeLeft ? (t = n, !1) : void 0
        }), e = Math.abs(i(t).attr("data-slick-index") - s.currentSlide) || 1) : s.options.slidesToScroll
    }, e.prototype.goTo = e.prototype.slickGoTo = function(i, e) {
        var t = this;
        t.changeSlide({
            data: {
                message: "index",
                index: parseInt(i)
            }
        }, e)
    }, e.prototype.init = function(e) {
        var t = this;
        i(t.$slider).hasClass("slick-initialized") || (i(t.$slider).addClass("slick-initialized"), t.buildRows(), t.buildOut(), t.setProps(), t.startLoad(), t.loadSlider(), t.initializeEvents(), t.updateArrows(), t.updateDots()), e && t.$slider.trigger("init", [t]), t.options.accessibility === !0 && t.initADA()
    }, e.prototype.initArrowEvents = function() {
        var i = this;
        i.options.arrows === !0 && i.slideCount > i.options.slidesToShow && (i.$prevArrow.on("click.slick", {
            message: "previous"
        }, i.changeSlide), i.$nextArrow.on("click.slick", {
            message: "next"
        }, i.changeSlide))
    }, e.prototype.initDotEvents = function() {
        var e = this;
        e.options.dots === !0 && e.slideCount > e.options.slidesToShow && i("li", e.$dots).on("click.slick", {
            message: "index"
        }, e.changeSlide), e.options.dots === !0 && e.options.pauseOnDotsHover === !0 && e.options.autoplay === !0 && i("li", e.$dots).on("mouseenter.slick", i.proxy(e.setPaused, e, !0)).on("mouseleave.slick", i.proxy(e.setPaused, e, !1))
    }, e.prototype.initializeEvents = function() {
        var e = this;
        e.initArrowEvents(), e.initDotEvents(), e.$list.on("touchstart.slick mousedown.slick", {
            action: "start"
        }, e.swipeHandler), e.$list.on("touchmove.slick mousemove.slick", {
            action: "move"
        }, e.swipeHandler), e.$list.on("touchend.slick mouseup.slick", {
            action: "end"
        }, e.swipeHandler), e.$list.on("touchcancel.slick mouseleave.slick", {
            action: "end"
        }, e.swipeHandler), e.$list.on("click.slick", e.clickHandler), i(document).on(e.visibilityChange, i.proxy(e.visibility, e)), e.$list.on("mouseenter.slick", i.proxy(e.setPaused, e, !0)), e.$list.on("mouseleave.slick", i.proxy(e.setPaused, e, !1)), e.options.accessibility === !0 && e.$list.on("keydown.slick", e.keyHandler), e.options.focusOnSelect === !0 && i(e.$slideTrack).children().on("click.slick", e.selectHandler), i(window).on("orientationchange.slick.slick-" + e.instanceUid, i.proxy(e.orientationChange, e)), i(window).on("resize.slick.slick-" + e.instanceUid, i.proxy(e.resize, e)), i("[draggable!=true]", e.$slideTrack).on("dragstart", e.preventDefault), i(window).on("load.slick.slick-" + e.instanceUid, e.setPosition), i(document).on("ready.slick.slick-" + e.instanceUid, e.setPosition)
    }, e.prototype.initUI = function() {
        var i = this;
        i.options.arrows === !0 && i.slideCount > i.options.slidesToShow && (i.$prevArrow.show(), i.$nextArrow.show()), i.options.dots === !0 && i.slideCount > i.options.slidesToShow && i.$dots.show(), i.options.autoplay === !0 && i.autoPlay()
    }, e.prototype.keyHandler = function(i) {
        var e = this;
        i.target.tagName.match("TEXTAREA|INPUT|SELECT") || (37 === i.keyCode && e.options.accessibility === !0 ? e.changeSlide({
            data: {
                message: "previous"
            }
        }) : 39 === i.keyCode && e.options.accessibility === !0 && e.changeSlide({
            data: {
                message: "next"
            }
        }))
    }, e.prototype.lazyLoad = function() {
        function e(e) {
            i("img[data-lazy]", e).each(function() {
                var e = i(this),
                    t = i(this).attr("data-lazy"),
                    o = document.createElement("img");
                o.onload = function() {
                    e.animate({
                        opacity: 0
                    }, 100, function() {
                        e.attr("src", t).animate({
                            opacity: 1
                        }, 200, function() {
                            e.removeAttr("data-lazy").removeClass("slick-loading")
                        })
                    })
                }, o.src = t
            })
        }
        var t, o, s, n, r = this;
        r.options.centerMode === !0 ? r.options.infinite === !0 ? (s = r.currentSlide + (r.options.slidesToShow / 2 + 1), n = s + r.options.slidesToShow + 2) : (s = Math.max(0, r.currentSlide - (r.options.slidesToShow / 2 + 1)), n = 2 + (r.options.slidesToShow / 2 + 1) + r.currentSlide) : (s = r.options.infinite ? r.options.slidesToShow + r.currentSlide : r.currentSlide, n = s + r.options.slidesToShow, r.options.fade === !0 && (s > 0 && s--, n <= r.slideCount && n++)), t = r.$slider.find(".slick-slide").slice(s, n), e(t), r.slideCount <= r.options.slidesToShow ? (o = r.$slider.find(".slick-slide"), e(o)) : r.currentSlide >= r.slideCount - r.options.slidesToShow ? (o = r.$slider.find(".slick-cloned").slice(0, r.options.slidesToShow), e(o)) : 0 === r.currentSlide && (o = r.$slider.find(".slick-cloned").slice(-1 * r.options.slidesToShow), e(o))
    }, e.prototype.loadSlider = function() {
        var i = this;
        i.setPosition(), i.$slideTrack.css({
            opacity: 1
        }), i.$slider.removeClass("slick-loading"), i.initUI(), "progressive" === i.options.lazyLoad && i.progressiveLazyLoad()
    }, e.prototype.next = e.prototype.slickNext = function() {
        var i = this;
        i.changeSlide({
            data: {
                message: "next"
            }
        })
    }, e.prototype.orientationChange = function() {
        var i = this;
        i.checkResponsive(), i.setPosition()
    }, e.prototype.pause = e.prototype.slickPause = function() {
        var i = this;
        i.autoPlayClear(), i.paused = !0
    }, e.prototype.play = e.prototype.slickPlay = function() {
        var i = this;
        i.paused = !1, i.autoPlay()
    }, e.prototype.postSlide = function(i) {
        var e = this;
        e.$slider.trigger("afterChange", [e, i]), e.animating = !1, e.setPosition(), e.swipeLeft = null, e.options.autoplay === !0 && e.paused === !1 && e.autoPlay(), e.options.accessibility === !0 && e.initADA()
    }, e.prototype.prev = e.prototype.slickPrev = function() {
        var i = this;
        i.changeSlide({
            data: {
                message: "previous"
            }
        })
    }, e.prototype.preventDefault = function(i) {
        i.preventDefault()
    }, e.prototype.progressiveLazyLoad = function() {
        var e, t, o = this;
        e = i("img[data-lazy]", o.$slider).length, e > 0 && (t = i("img[data-lazy]", o.$slider).first(), t.attr("src", t.attr("data-lazy")).removeClass("slick-loading").load(function() {
            t.removeAttr("data-lazy"), o.progressiveLazyLoad(), o.options.adaptiveHeight === !0 && o.setPosition()
        }).error(function() {
            t.removeAttr("data-lazy"), o.progressiveLazyLoad()
        }))
    }, e.prototype.refresh = function(e) {
        var t = this,
            o = t.currentSlide;
        t.destroy(!0), i.extend(t, t.initials, {
            currentSlide: o
        }), t.init(), e || t.changeSlide({
            data: {
                message: "index",
                index: o
            }
        }, !1)
    }, e.prototype.registerBreakpoints = function() {
        var e, t, o, s = this,
            n = s.options.responsive || null;
        if ("array" === i.type(n) && n.length) {
            s.respondTo = s.options.respondTo || "window";
            for (e in n)
                if (o = s.breakpoints.length - 1, t = n[e].breakpoint, n.hasOwnProperty(e)) {
                    for (; o >= 0;) s.breakpoints[o] && s.breakpoints[o] === t && s.breakpoints.splice(o, 1), o--;
                    s.breakpoints.push(t), s.breakpointSettings[t] = n[e].settings
                }
            s.breakpoints.sort(function(i, e) {
                return s.options.mobileFirst ? i - e : e - i
            })
        }
    }, e.prototype.reinit = function() {
        var e = this;
        e.$slides = e.$slideTrack.children(e.options.slide).addClass("slick-slide"), e.slideCount = e.$slides.length, e.currentSlide >= e.slideCount && 0 !== e.currentSlide && (e.currentSlide = e.currentSlide - e.options.slidesToScroll), e.slideCount <= e.options.slidesToShow && (e.currentSlide = 0), e.registerBreakpoints(), e.setProps(), e.setupInfinite(), e.buildArrows(), e.updateArrows(), e.initArrowEvents(), e.buildDots(), e.updateDots(), e.initDotEvents(), e.checkResponsive(!1, !0), e.options.focusOnSelect === !0 && i(e.$slideTrack).children().on("click.slick", e.selectHandler), e.setSlideClasses(0), e.setPosition(), e.$slider.trigger("reInit", [e]), e.options.autoplay === !0 && e.focusHandler()
    }, e.prototype.resize = function() {
        var e = this;
        i(window).width() !== e.windowWidth && (clearTimeout(e.windowDelay), e.windowDelay = window.setTimeout(function() {
            e.windowWidth = i(window).width(), e.checkResponsive(), e.unslicked || e.setPosition()
        }, 50))
    }, e.prototype.removeSlide = e.prototype.slickRemove = function(i, e, t) {
        var o = this;
        return "boolean" == typeof i ? (e = i, i = e === !0 ? 0 : o.slideCount - 1) : i = e === !0 ? --i : i, o.slideCount < 1 || 0 > i || i > o.slideCount - 1 ? !1 : (o.unload(), t === !0 ? o.$slideTrack.children().remove() : o.$slideTrack.children(this.options.slide).eq(i).remove(), o.$slides = o.$slideTrack.children(this.options.slide), o.$slideTrack.children(this.options.slide).detach(), o.$slideTrack.append(o.$slides), o.$slidesCache = o.$slides, void o.reinit())
    }, e.prototype.setCSS = function(i) {
        var e, t, o = this,
            s = {};
        o.options.rtl === !0 && (i = -i), e = "left" == o.positionProp ? Math.ceil(i) + "px" : "0px", t = "top" == o.positionProp ? Math.ceil(i) + "px" : "0px", s[o.positionProp] = i, o.transformsEnabled === !1 ? o.$slideTrack.css(s) : (s = {}, o.cssTransitions === !1 ? (s[o.animType] = "translate(" + e + ", " + t + ")", o.$slideTrack.css(s)) : (s[o.animType] = "translate3d(" + e + ", " + t + ", 0px)", o.$slideTrack.css(s)))
    }, e.prototype.setDimensions = function() {
        var i = this;
        i.options.vertical === !1 ? i.options.centerMode === !0 && i.$list.css({
            padding: "0px " + i.options.centerPadding
        }) : (i.$list.height(i.$slides.first().outerHeight(!0) * i.options.slidesToShow), i.options.centerMode === !0 && i.$list.css({
            padding: i.options.centerPadding + " 0px"
        })), i.listWidth = i.$list.width(), i.listHeight = i.$list.height(), i.options.vertical === !1 && i.options.variableWidth === !1 ? (i.slideWidth = Math.ceil(i.listWidth / i.options.slidesToShow), i.$slideTrack.width(Math.ceil(i.slideWidth * i.$slideTrack.children(".slick-slide").length))) : i.options.variableWidth === !0 ? i.$slideTrack.width(5e3 * i.slideCount) : (i.slideWidth = Math.ceil(i.listWidth), i.$slideTrack.height(Math.ceil(i.$slides.first().outerHeight(!0) * i.$slideTrack.children(".slick-slide").length)));
        var e = i.$slides.first().outerWidth(!0) - i.$slides.first().width();
        i.options.variableWidth === !1 && i.$slideTrack.children(".slick-slide").width(i.slideWidth - e)
    }, e.prototype.setFade = function() {
        var e, t = this;
        t.$slides.each(function(o, s) {
            e = -1 * t.slideWidth * o, i(s).css(t.options.rtl === !0 ? {
                position: "relative",
                right: e,
                top: 0,
                zIndex: t.options.zIndex - 2,
                opacity: 0
            } : {
                position: "relative",
                left: e,
                top: 0,
                zIndex: t.options.zIndex - 2,
                opacity: 0
            })
        }), t.$slides.eq(t.currentSlide).css({
            zIndex: t.options.zIndex - 1,
            opacity: 1
        })
    }, e.prototype.setHeight = function() {
        var i = this;
        if (1 === i.options.slidesToShow && i.options.adaptiveHeight === !0 && i.options.vertical === !1) {
            var e = i.$slides.eq(i.currentSlide).outerHeight(!0);
            i.$list.css("height", e)
        }
    }, e.prototype.setOption = e.prototype.slickSetOption = function(e, t, o) {
        var s, n, r = this;
        if ("responsive" === e && "array" === i.type(t))
            for (n in t)
                if ("array" !== i.type(r.options.responsive)) r.options.responsive = [t[n]];
                else {
                    for (s = r.options.responsive.length - 1; s >= 0;) r.options.responsive[s].breakpoint === t[n].breakpoint && r.options.responsive.splice(s, 1), s--;
                    r.options.responsive.push(t[n])
                }
        else r.options[e] = t;
        o === !0 && (r.unload(), r.reinit())
    }, e.prototype.setPosition = function() {
        var i = this;
        i.setDimensions(), i.setHeight(), i.options.fade === !1 ? i.setCSS(i.getLeft(i.currentSlide)) : i.setFade(), i.$slider.trigger("setPosition", [i])
    }, e.prototype.setProps = function() {
        var i = this,
            e = document.body.style;
        i.positionProp = i.options.vertical === !0 ? "top" : "left", "top" === i.positionProp ? i.$slider.addClass("slick-vertical") : i.$slider.removeClass("slick-vertical"), (void 0 !== e.WebkitTransition || void 0 !== e.MozTransition || void 0 !== e.msTransition) && i.options.useCSS === !0 && (i.cssTransitions = !0), i.options.fade && ("number" == typeof i.options.zIndex ? i.options.zIndex < 3 && (i.options.zIndex = 3) : i.options.zIndex = i.defaults.zIndex), void 0 !== e.OTransform && (i.animType = "OTransform", i.transformType = "-o-transform", i.transitionType = "OTransition", void 0 === e.perspectiveProperty && void 0 === e.webkitPerspective && (i.animType = !1)), void 0 !== e.MozTransform && (i.animType = "MozTransform", i.transformType = "-moz-transform", i.transitionType = "MozTransition", void 0 === e.perspectiveProperty && void 0 === e.MozPerspective && (i.animType = !1)), void 0 !== e.webkitTransform && (i.animType = "webkitTransform", i.transformType = "-webkit-transform", i.transitionType = "webkitTransition", void 0 === e.perspectiveProperty && void 0 === e.webkitPerspective && (i.animType = !1)), void 0 !== e.msTransform && (i.animType = "msTransform", i.transformType = "-ms-transform", i.transitionType = "msTransition", void 0 === e.msTransform && (i.animType = !1)), void 0 !== e.transform && i.animType !== !1 && (i.animType = "transform", i.transformType = "transform", i.transitionType = "transition"), i.transformsEnabled = null !== i.animType && i.animType !== !1
    }, e.prototype.setSlideClasses = function(i) {
        var e, t, o, s, n = this;
        t = n.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden", "true"), n.$slides.eq(i).addClass("slick-current"), n.options.centerMode === !0 ? (e = Math.floor(n.options.slidesToShow / 2), n.options.infinite === !0 && (i >= e && i <= n.slideCount - 1 - e ? n.$slides.slice(i - e, i + e + 1).addClass("slick-active").attr("aria-hidden", "false") : (o = n.options.slidesToShow + i, t.slice(o - e + 1, o + e + 2).addClass("slick-active").attr("aria-hidden", "false")), 0 === i ? t.eq(t.length - 1 - n.options.slidesToShow).addClass("slick-center") : i === n.slideCount - 1 && t.eq(n.options.slidesToShow).addClass("slick-center")), n.$slides.eq(i).addClass("slick-center")) : i >= 0 && i <= n.slideCount - n.options.slidesToShow ? n.$slides.slice(i, i + n.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false") : t.length <= n.options.slidesToShow ? t.addClass("slick-active").attr("aria-hidden", "false") : (s = n.slideCount % n.options.slidesToShow, o = n.options.infinite === !0 ? n.options.slidesToShow + i : i, n.options.slidesToShow == n.options.slidesToScroll && n.slideCount - i < n.options.slidesToShow ? t.slice(o - (n.options.slidesToShow - s), o + s).addClass("slick-active").attr("aria-hidden", "false") : t.slice(o, o + n.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false")), "ondemand" === n.options.lazyLoad && n.lazyLoad()
    }, e.prototype.setupInfinite = function() {
        var e, t, o, s = this;
        if (s.options.fade === !0 && (s.options.centerMode = !1), s.options.infinite === !0 && s.options.fade === !1 && (t = null, s.slideCount > s.options.slidesToShow)) {
            for (o = s.options.centerMode === !0 ? s.options.slidesToShow + 1 : s.options.slidesToShow, e = s.slideCount; e > s.slideCount - o; e -= 1) t = e - 1, i(s.$slides[t]).clone(!0).attr("id", "").attr("data-slick-index", t - s.slideCount).prependTo(s.$slideTrack).addClass("slick-cloned");
            for (e = 0; o > e; e += 1) t = e, i(s.$slides[t]).clone(!0).attr("id", "").attr("data-slick-index", t + s.slideCount).appendTo(s.$slideTrack).addClass("slick-cloned");
            s.$slideTrack.find(".slick-cloned").find("[id]").each(function() {
                i(this).attr("id", "")
            })
        }
    }, e.prototype.setPaused = function(i) {
        var e = this;
        e.options.autoplay === !0 && e.options.pauseOnHover === !0 && (e.paused = i, i ? e.autoPlayClear() : e.autoPlay())
    }, e.prototype.selectHandler = function(e) {
        var t = this,
            o = i(e.target).is(".slick-slide") ? i(e.target) : i(e.target).parents(".slick-slide"),
            s = parseInt(o.attr("data-slick-index"));
        return s || (s = 0), t.slideCount <= t.options.slidesToShow ? (t.setSlideClasses(s), void t.asNavFor(s)) : void t.slideHandler(s)
    }, e.prototype.slideHandler = function(i, e, t) {
        var o, s, n, r, l = null,
            a = this;
        return e = e || !1, a.animating === !0 && a.options.waitForAnimate === !0 || a.options.fade === !0 && a.currentSlide === i || a.slideCount <= a.options.slidesToShow ? void 0 : (e === !1 && a.asNavFor(i), o = i, l = a.getLeft(o), r = a.getLeft(a.currentSlide), a.currentLeft = null === a.swipeLeft ? r : a.swipeLeft, a.options.infinite === !1 && a.options.centerMode === !1 && (0 > i || i > a.getDotCount() * a.options.slidesToScroll) ? void(a.options.fade === !1 && (o = a.currentSlide, t !== !0 ? a.animateSlide(r, function() {
            a.postSlide(o)
        }) : a.postSlide(o))) : a.options.infinite === !1 && a.options.centerMode === !0 && (0 > i || i > a.slideCount - a.options.slidesToScroll) ? void(a.options.fade === !1 && (o = a.currentSlide, t !== !0 ? a.animateSlide(r, function() {
            a.postSlide(o)
        }) : a.postSlide(o))) : (a.options.autoplay === !0 && clearInterval(a.autoPlayTimer), s = 0 > o ? 0 !== a.slideCount % a.options.slidesToScroll ? a.slideCount - a.slideCount % a.options.slidesToScroll : a.slideCount + o : o >= a.slideCount ? 0 !== a.slideCount % a.options.slidesToScroll ? 0 : o - a.slideCount : o, a.animating = !0, a.$slider.trigger("beforeChange", [a, a.currentSlide, s]), n = a.currentSlide, a.currentSlide = s, a.setSlideClasses(a.currentSlide), a.updateDots(), a.updateArrows(), a.options.fade === !0 ? (t !== !0 ? (a.fadeSlideOut(n), a.fadeSlide(s, function() {
            a.postSlide(s)
        })) : a.postSlide(s), void a.animateHeight()) : void(t !== !0 ? a.animateSlide(l, function() {
            a.postSlide(s)
        }) : a.postSlide(s))))
    }, e.prototype.startLoad = function() {
        var i = this;
        i.options.arrows === !0 && i.slideCount > i.options.slidesToShow && (i.$prevArrow.hide(), i.$nextArrow.hide()), i.options.dots === !0 && i.slideCount > i.options.slidesToShow && i.$dots.hide(), i.$slider.addClass("slick-loading")
    }, e.prototype.swipeDirection = function() {
        var i, e, t, o, s = this;
        return i = s.touchObject.startX - s.touchObject.curX, e = s.touchObject.startY - s.touchObject.curY, t = Math.atan2(e, i), o = Math.round(180 * t / Math.PI), 0 > o && (o = 360 - Math.abs(o)), 45 >= o && o >= 0 ? s.options.rtl === !1 ? "left" : "right" : 360 >= o && o >= 315 ? s.options.rtl === !1 ? "left" : "right" : o >= 135 && 225 >= o ? s.options.rtl === !1 ? "right" : "left" : s.options.verticalSwiping === !0 ? o >= 35 && 135 >= o ? "left" : "right" : "vertical"
    }, e.prototype.swipeEnd = function() {
        var i, e = this;
        if (e.dragging = !1, e.shouldClick = e.touchObject.swipeLength > 10 ? !1 : !0, void 0 === e.touchObject.curX) return !1;
        if (e.touchObject.edgeHit === !0 && e.$slider.trigger("edge", [e, e.swipeDirection()]), e.touchObject.swipeLength >= e.touchObject.minSwipe) switch (e.swipeDirection()) {
            case "left":
                i = e.options.swipeToSlide ? e.checkNavigable(e.currentSlide + e.getSlideCount()) : e.currentSlide + e.getSlideCount(), e.slideHandler(i), e.currentDirection = 0, e.touchObject = {}, e.$slider.trigger("swipe", [e, "left"]);
                break;
            case "right":
                i = e.options.swipeToSlide ? e.checkNavigable(e.currentSlide - e.getSlideCount()) : e.currentSlide - e.getSlideCount(), e.slideHandler(i), e.currentDirection = 1, e.touchObject = {}, e.$slider.trigger("swipe", [e, "right"])
        } else e.touchObject.startX !== e.touchObject.curX && (e.slideHandler(e.currentSlide), e.touchObject = {})
    }, e.prototype.swipeHandler = function(i) {
        var e = this;
        if (!(e.options.swipe === !1 || "ontouchend" in document && e.options.swipe === !1 || e.options.draggable === !1 && -1 !== i.type.indexOf("mouse"))) switch (e.touchObject.fingerCount = i.originalEvent && void 0 !== i.originalEvent.touches ? i.originalEvent.touches.length : 1, e.touchObject.minSwipe = e.listWidth / e.options.touchThreshold, e.options.verticalSwiping === !0 && (e.touchObject.minSwipe = e.listHeight / e.options.touchThreshold), i.data.action) {
            case "start":
                e.swipeStart(i);
                break;
            case "move":
                e.swipeMove(i);
                break;
            case "end":
                e.swipeEnd(i)
        }
    }, e.prototype.swipeMove = function(i) {
        var e, t, o, s, n, r = this;
        return n = void 0 !== i.originalEvent ? i.originalEvent.touches : null, !r.dragging || n && 1 !== n.length ? !1 : (e = r.getLeft(r.currentSlide), r.touchObject.curX = void 0 !== n ? n[0].pageX : i.clientX, r.touchObject.curY = void 0 !== n ? n[0].pageY : i.clientY, r.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(r.touchObject.curX - r.touchObject.startX, 2))), r.options.verticalSwiping === !0 && (r.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(r.touchObject.curY - r.touchObject.startY, 2)))), t = r.swipeDirection(), "vertical" !== t ? (void 0 !== i.originalEvent && r.touchObject.swipeLength > 4 && i.preventDefault(), s = (r.options.rtl === !1 ? 1 : -1) * (r.touchObject.curX > r.touchObject.startX ? 1 : -1), r.options.verticalSwiping === !0 && (s = r.touchObject.curY > r.touchObject.startY ? 1 : -1), o = r.touchObject.swipeLength, r.touchObject.edgeHit = !1, r.options.infinite === !1 && (0 === r.currentSlide && "right" === t || r.currentSlide >= r.getDotCount() && "left" === t) && (o = r.touchObject.swipeLength * r.options.edgeFriction, r.touchObject.edgeHit = !0), r.swipeLeft = r.options.vertical === !1 ? e + o * s : e + o * (r.$list.height() / r.listWidth) * s, r.options.verticalSwiping === !0 && (r.swipeLeft = e + o * s), r.options.fade === !0 || r.options.touchMove === !1 ? !1 : r.animating === !0 ? (r.swipeLeft = null, !1) : void r.setCSS(r.swipeLeft)) : void 0)
    }, e.prototype.swipeStart = function(i) {
        var e, t = this;
        return 1 !== t.touchObject.fingerCount || t.slideCount <= t.options.slidesToShow ? (t.touchObject = {}, !1) : (void 0 !== i.originalEvent && void 0 !== i.originalEvent.touches && (e = i.originalEvent.touches[0]), t.touchObject.startX = t.touchObject.curX = void 0 !== e ? e.pageX : i.clientX, t.touchObject.startY = t.touchObject.curY = void 0 !== e ? e.pageY : i.clientY, void(t.dragging = !0))
    }, e.prototype.unfilterSlides = e.prototype.slickUnfilter = function() {
        var i = this;
        null !== i.$slidesCache && (i.unload(), i.$slideTrack.children(this.options.slide).detach(), i.$slidesCache.appendTo(i.$slideTrack), i.reinit())
    }, e.prototype.unload = function() {
        var e = this;
        i(".slick-cloned", e.$slider).remove(), e.$dots && e.$dots.remove(), e.$prevArrow && e.htmlExpr.test(e.options.prevArrow) && e.$prevArrow.remove(), e.$nextArrow && e.htmlExpr.test(e.options.nextArrow) && e.$nextArrow.remove(), e.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden", "true").css("width", "")
    }, e.prototype.unslick = function(i) {
        var e = this;
        e.$slider.trigger("unslick", [e, i]), e.destroy()
    }, e.prototype.updateArrows = function() {
        var i, e = this;
        i = Math.floor(e.options.slidesToShow / 2), e.options.arrows === !0 && e.slideCount > e.options.slidesToShow && !e.options.infinite && (e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false"), e.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false"), 0 === e.currentSlide ? (e.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true"), e.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : e.currentSlide >= e.slideCount - e.options.slidesToShow && e.options.centerMode === !1 ? (e.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"), e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : e.currentSlide >= e.slideCount - 1 && e.options.centerMode === !0 && (e.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"), e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")))
    }, e.prototype.updateDots = function() {
        var i = this;
        null !== i.$dots && (i.$dots.find("li").removeClass("slick-active").attr("aria-hidden", "true"), i.$dots.find("li").eq(Math.floor(i.currentSlide / i.options.slidesToScroll)).addClass("slick-active").attr("aria-hidden", "false"))
    }, e.prototype.visibility = function() {
        var i = this;
        document[i.hidden] ? (i.paused = !0, i.autoPlayClear()) : i.options.autoplay === !0 && (i.paused = !1, i.autoPlay())
    }, e.prototype.initADA = function() {
        var e = this;
        e.$slides.add(e.$slideTrack.find(".slick-cloned")).attr({
            "aria-hidden": "true",
            tabindex: "-1"
        }).find("a, input, button, select").attr({
            tabindex: "-1"
        }), e.$slideTrack.attr("role", "listbox"), e.$slides.not(e.$slideTrack.find(".slick-cloned")).each(function(t) {
            i(this).attr({
                role: "option",
                "aria-describedby": "slick-slide" + e.instanceUid + t
            })
        }), null !== e.$dots && e.$dots.attr("role", "tablist").find("li").each(function(t) {
            i(this).attr({
                role: "presentation",
                "aria-selected": "false",
                "aria-controls": "navigation" + e.instanceUid + t,
                id: "slick-slide" + e.instanceUid + t
            })
        }).first().attr("aria-selected", "true").end().find("button").attr("role", "button").end().closest("div").attr("role", "toolbar"), e.activateADA()
    }, e.prototype.activateADA = function() {
        var i = this,
            e = i.$slider.find("*").is(":focus");
        i.$slideTrack.find(".slick-active").attr({
            "aria-hidden": "false",
            tabindex: "0"
        }).find("a, input, button, select").attr({
            tabindex: "0"
        }), e && i.$slideTrack.find(".slick-active").focus()
    }, e.prototype.focusHandler = function() {
        var e = this;
        e.$slider.on("focus.slick blur.slick", "*", function(t) {
            t.stopImmediatePropagation();
            var o = i(this);
            setTimeout(function() {
                e.isPlay && (o.is(":focus") ? (e.autoPlayClear(), e.paused = !0) : (e.paused = !1, e.autoPlay()))
            }, 0)
        })
    }, i.fn.slick = function() {
        var i, t = this,
            o = arguments[0],
            s = Array.prototype.slice.call(arguments, 1),
            n = t.length,
            r = 0;
        for (r; n > r; r++)
            if ("object" == typeof o || "undefined" == typeof o ? t[r].slick = new e(t[r], o) : i = t[r].slick[o].apply(t[r].slick, s), "undefined" != typeof i) return i;
        return t
    }
});
! function(o) {
    var n = {
        url: !1,
        callback: !1,
        target: !1,
        duration: 120,
        on: "mouseover",
        touch: !0,
        onZoomIn: !1,
        onZoomOut: !1,
        magnify: 1
    };
    o.zoom = function(n, t, e, i) {
        var c, u, a, m, s, r, l, f = o(n),
            h = f.css("position"),
            d = o(t);
        return f.css("position", /(absolute|fixed)/.test(h) ? h : "relative"), f.css("overflow", "hidden"), e.style.width = e.style.height = "", o(e).addClass("zoomImg").css({
            position: "absolute",
            top: 0,
            left: 0,
            opacity: 0,
            width: e.width * i,
            height: e.height * i,
            border: "none",
            maxWidth: "none",
            maxHeight: "none"
        }).appendTo(n), {
            init: function() {
                u = f.outerWidth(), c = f.outerHeight(), t === f[0] ? (m = u, a = c) : (m = d.outerWidth(), a = d.outerHeight()), s = (e.width - u) / m, r = (e.height - c) / a, l = d.offset()
            },
            move: function(o) {
                var n = o.pageX - l.left,
                    t = o.pageY - l.top;
                t = Math.max(Math.min(t, a), 0), n = Math.max(Math.min(n, m), 0), e.style.left = n * -s + "px", e.style.top = t * -r + "px"
            }
        }
    }, o.fn.zoom = function(t) {
        return this.each(function() {
            var e, i = o.extend({}, n, t || {}),
                c = i.target || this,
                u = this,
                a = o(u),
                m = o(c),
                s = document.createElement("img"),
                r = o(s),
                l = "mousemove.zoom",
                f = !1,
                h = !1;
            (i.url || (e = a.find("img"), e[0] && (i.url = e.data("src") || e.attr("src")), i.url)) && (! function() {
                var o = m.css("position"),
                    n = m.css("overflow");
                a.one("zoom.destroy", function() {
                    a.off(".zoom"), m.css("position", o), m.css("overflow", n), r.remove()
                })
            }(), s.onload = function() {
                function n(n) {
                    e.init(), e.move(n), r.stop().fadeTo(o.support.opacity ? i.duration : 0, 1, o.isFunction(i.onZoomIn) ? i.onZoomIn.call(s) : !1)
                }

                function t() {
                    r.stop().fadeTo(i.duration, 0, o.isFunction(i.onZoomOut) ? i.onZoomOut.call(s) : !1)
                }
                var e = o.zoom(c, u, s, i.magnify);
                "grab" === i.on ? a.on("mousedown.zoom", function(i) {
                    1 === i.which && (o(document).one("mouseup.zoom", function() {
                        t(), o(document).off(l, e.move)
                    }), n(i), o(document).on(l, e.move), i.preventDefault())
                }) : "click" === i.on ? a.on("click.zoom", function(i) {
                    return f ? void 0 : (f = !0, n(i), o(document).on(l, e.move), o(document).one("click.zoom", function() {
                        t(), f = !1, o(document).off(l, e.move)
                    }), !1)
                }) : "toggle" === i.on ? a.on("click.zoom", function(o) {
                    f ? t() : n(o), f = !f
                }) : "mouseover" === i.on && (e.init(), a.on("mouseenter.zoom", n).on("mouseleave.zoom", t).on(l, e.move)), i.touch && a.on("touchstart.zoom", function(o) {
                    o.preventDefault(), h ? (h = !1, t()) : (h = !0, n(o.originalEvent.touches[0] || o.originalEvent.changedTouches[0]))
                }).on("touchmove.zoom", function(o) {
                    o.preventDefault(), e.move(o.originalEvent.touches[0] || o.originalEvent.changedTouches[0])
                }), o.isFunction(i.callback) && i.callback.call(s)
            }, s.src = i.url)
        })
    }, o.fn.zoom.defaults = n
}(window.jQuery);
(function() {
    "use strict";
    var t = function(t) {
        var i = function(i, n) {
                this.el = t(i), this.zoomFactor = 1, this.lastScale = 1, this.offset = {
                    x: 0,
                    y: 0
                }, this.options = t.extend({}, this.defaults, n), this.setupMarkup(), this.bindEvents(), this.update(), this.enable()
            },
            n = function(t, i) {
                return t + i
            },
            o = function(t, i) {
                return t > i - .01 && i + .01 > t
            };
        i.prototype = {
            defaults: {
                tapZoomFactor: 2,
                zoomOutFactor: 1.3,
                animationDuration: 300,
                animationInterval: 5,
                maxZoom: 4,
                minZoom: .5,
                lockDragAxis: !1,
                use2d: !0,
                zoomStartEventName: "pz_zoomstart",
                zoomEndEventName: "pz_zoomend",
                dragStartEventName: "pz_dragstart",
                dragEndEventName: "pz_dragend",
                doubleTapEventName: "pz_doubletap"
            },
            handleDragStart: function(t) {
                this.el.trigger(this.options.dragStartEventName), this.stopAnimation(), this.lastDragPosition = !1, this.hasInteraction = !0, this.handleDrag(t)
            },
            handleDrag: function(t) {
                if (this.zoomFactor > 1) {
                    var i = this.getTouches(t)[0];
                    this.drag(i, this.lastDragPosition), this.offset = this.sanitizeOffset(this.offset), this.lastDragPosition = i
                }
            },
            handleDragEnd: function() {
                this.el.trigger(this.options.dragEndEventName), this.end()
            },
            handleZoomStart: function() {
                this.el.trigger(this.options.zoomStartEventName), this.stopAnimation(), this.lastScale = 1, this.nthZoom = 0, this.lastZoomCenter = !1, this.hasInteraction = !0
            },
            handleZoom: function(t, i) {
                var n = this.getTouchCenter(this.getTouches(t)),
                    o = i / this.lastScale;
                this.lastScale = i, this.nthZoom += 1, this.nthZoom > 3 && (this.scale(o, n), this.drag(n, this.lastZoomCenter)), this.lastZoomCenter = n
            },
            handleZoomEnd: function() {
                this.el.trigger(this.options.zoomEndEventName), this.end()
            },
            handleDoubleTap: function(t) {
                var i = this.getTouches(t)[0],
                    n = this.zoomFactor > 1 ? 1 : this.options.tapZoomFactor,
                    o = this.zoomFactor,
                    e = function(t) {
                        this.scaleTo(o + t * (n - o), i)
                    }.bind(this);
                this.hasInteraction || (o > n && (i = this.getCurrentZoomCenter()), this.animate(this.options.animationDuration, this.options.animationInterval, e, this.swing), this.el.trigger(this.options.doubleTapEventName))
            },
            sanitizeOffset: function(t) {
                var i = (this.zoomFactor - 1) * this.getContainerX(),
                    n = (this.zoomFactor - 1) * this.getContainerY(),
                    o = Math.max(i, 0),
                    e = Math.max(n, 0),
                    s = Math.min(i, 0),
                    a = Math.min(n, 0);
                return {
                    x: Math.min(Math.max(t.x, s), o),
                    y: Math.min(Math.max(t.y, a), e)
                }
            },
            scaleTo: function(t, i) {
                this.scale(t / this.zoomFactor, i)
            },
            scale: function(t, i) {
                t = this.scaleZoomFactor(t), this.addOffset({
                    x: (t - 1) * (i.x + this.offset.x),
                    y: (t - 1) * (i.y + this.offset.y)
                })
            },
            scaleZoomFactor: function(t) {
                var i = this.zoomFactor;
                return this.zoomFactor *= t, this.zoomFactor = Math.min(this.options.maxZoom, Math.max(this.zoomFactor, this.options.minZoom)), this.zoomFactor / i
            },
            drag: function(t, i) {
                i && this.addOffset(this.options.lockDragAxis ? Math.abs(t.x - i.x) > Math.abs(t.y - i.y) ? {
                    x: -(t.x - i.x),
                    y: 0
                } : {
                    y: -(t.y - i.y),
                    x: 0
                } : {
                    y: -(t.y - i.y),
                    x: -(t.x - i.x)
                })
            },
            getTouchCenter: function(t) {
                return this.getVectorAvg(t)
            },
            getVectorAvg: function(t) {
                return {
                    x: t.map(function(t) {
                        return t.x
                    }).reduce(n) / t.length,
                    y: t.map(function(t) {
                        return t.y
                    }).reduce(n) / t.length
                }
            },
            addOffset: function(t) {
                this.offset = {
                    x: this.offset.x + t.x,
                    y: this.offset.y + t.y
                }
            },
            sanitize: function() {
                this.zoomFactor < this.options.zoomOutFactor ? this.zoomOutAnimation() : this.isInsaneOffset(this.offset) && this.sanitizeOffsetAnimation()
            },
            isInsaneOffset: function(t) {
                var i = this.sanitizeOffset(t);
                return i.x !== t.x || i.y !== t.y
            },
            sanitizeOffsetAnimation: function() {
                var t = this.sanitizeOffset(this.offset),
                    i = {
                        x: this.offset.x,
                        y: this.offset.y
                    },
                    n = function(n) {
                        this.offset.x = i.x + n * (t.x - i.x), this.offset.y = i.y + n * (t.y - i.y), this.update()
                    }.bind(this);
                this.animate(this.options.animationDuration, this.options.animationInterval, n, this.swing)
            },
            zoomOutAnimation: function() {
                var t = this.zoomFactor,
                    i = 1,
                    n = this.getCurrentZoomCenter(),
                    o = function(o) {
                        this.scaleTo(t + o * (i - t), n)
                    }.bind(this);
                this.animate(this.options.animationDuration, this.options.animationInterval, o, this.swing)
            },
            updateAspectRatio: function() {
                this.setContainerY(this.getContainerX() / this.getAspectRatio())
            },
            getInitialZoomFactor: function() {
                return this.container[0].offsetWidth / this.el[0].offsetWidth
            },
            getAspectRatio: function() {
                return this.el[0].offsetWidth / this.el[0].offsetHeight
            },
            getCurrentZoomCenter: function() {
                var t = this.container[0].offsetWidth * this.zoomFactor,
                    i = this.offset.x,
                    n = t - i - this.container[0].offsetWidth,
                    o = i / n,
                    e = o * this.container[0].offsetWidth / (o + 1),
                    s = this.container[0].offsetHeight * this.zoomFactor,
                    a = this.offset.y,
                    h = s - a - this.container[0].offsetHeight,
                    r = a / h,
                    c = r * this.container[0].offsetHeight / (r + 1);
                return 0 === n && (e = this.container[0].offsetWidth), 0 === h && (c = this.container[0].offsetHeight), {
                    x: e,
                    y: c
                }
            },
            canDrag: function() {
                return !o(this.zoomFactor, 1)
            },
            getTouches: function(t) {
                var i = this.container.offset();
                return Array.prototype.slice.call(t.touches).map(function(t) {
                    return {
                        x: t.pageX - i.left,
                        y: t.pageY - i.top
                    }
                })
            },
            animate: function(t, i, n, o, e) {
                var s = (new Date).getTime(),
                    a = function() {
                        if (this.inAnimation) {
                            var h = (new Date).getTime() - s,
                                r = h / t;
                            h >= t ? (n(1), e && e(), this.update(), this.stopAnimation(), this.update()) : (o && (r = o(r)), n(r), this.update(), setTimeout(a, i))
                        }
                    }.bind(this);
                this.inAnimation = !0, a()
            },
            stopAnimation: function() {
                this.inAnimation = !1
            },
            swing: function(t) {
                return -Math.cos(t * Math.PI) / 2 + .5
            },
            getContainerX: function() {
                return this.container[0].offsetWidth
            },
            getContainerY: function() {
                return this.container[0].offsetHeight
            },
            setContainerY: function(t) {
                return this.container.height(t)
            },
            setupMarkup: function() {
                this.container = t('<div class="pinch-zoom-container"></div>'), this.el.before(this.container), this.container.append(this.el), this.container.css({
                    overflow: "hidden",
                    position: "relative"
                }), this.el.css({
                    "-webkit-transform-origin": "0% 0%",
                    "-moz-transform-origin": "0% 0%",
                    "-ms-transform-origin": "0% 0%",
                    "-o-transform-origin": "0% 0%",
                    "transform-origin": "0% 0%",
                    position: "absolute"
                })
            },
            end: function() {
                this.hasInteraction = !1, this.sanitize(), this.update()
            },
            bindEvents: function() {
                e(this.container.get(0), this), t(window).on("resize", this.update.bind(this)), t(this.el).find("img").on("load", this.update.bind(this))
            },
            update: function() {
                this.updatePlaned || (this.updatePlaned = !0, setTimeout(function() {
                    this.updatePlaned = !1, this.updateAspectRatio();
                    var t = this.getInitialZoomFactor() * this.zoomFactor,
                        i = -this.offset.x / t,
                        n = -this.offset.y / t,
                        o = "scale3d(" + t + ", " + t + ",1) translate3d(" + i + "px," + n + "px,0px)",
                        e = "scale(" + t + ", " + t + ") translate(" + i + "px," + n + "px)",
                        s = function() {
                            this.clone && (this.clone.remove(), delete this.clone)
                        }.bind(this);
                    !this.options.use2d || this.hasInteraction || this.inAnimation ? (this.is3d = !0, s(), this.el.css({
                        "-webkit-transform": o,
                        "-o-transform": e,
                        "-ms-transform": e,
                        "-moz-transform": e,
                        transform: o
                    })) : (this.is3d && (this.clone = this.el.clone(), this.clone.css("pointer-events", "none"), this.clone.appendTo(this.container), setTimeout(s, 200)), this.el.css({
                        "-webkit-transform": e,
                        "-o-transform": e,
                        "-ms-transform": e,
                        "-moz-transform": e,
                        transform: e
                    }), this.is3d = !1)
                }.bind(this), 0))
            },
            enable: function() {
                this.enabled = !0
            },
            disable: function() {
                this.enabled = !1
            }
        };
        var e = function(t, i) {
            var n = null,
                o = 0,
                e = null,
                s = null,
                a = function(t, o) {
                    if (n !== t) {
                        if (n && !t) switch (n) {
                            case "zoom":
                                i.handleZoomEnd(o);
                                break;
                            case "drag":
                                i.handleDragEnd(o)
                        }
                        switch (t) {
                            case "zoom":
                                i.handleZoomStart(o);
                                break;
                            case "drag":
                                i.handleDragStart(o)
                        }
                    }
                    n = t
                },
                h = function(t) {
                    2 === o ? a("zoom") : 1 === o && i.canDrag() ? a("drag", t) : a(null, t)
                },
                r = function(t) {
                    return Array.prototype.slice.call(t).map(function(t) {
                        return {
                            x: t.pageX,
                            y: t.pageY
                        }
                    })
                },
                c = function(t, i) {
                    var n, o;
                    return n = t.x - i.x, o = t.y - i.y, Math.sqrt(n * n + o * o)
                },
                f = function(t, i) {
                    var n = c(t[0], t[1]),
                        o = c(i[0], i[1]);
                    return o / n
                },
                u = function(t) {
                    t.stopPropagation(), t.preventDefault()
                },
                m = function(t) {
                    var s = (new Date).getTime();
                    if (o > 1 && (e = null), 300 > s - e) switch (u(t), i.handleDoubleTap(t), n) {
                        case "zoom":
                            i.handleZoomEnd(t);
                            break;
                        case "drag":
                            i.handleDragEnd(t)
                    }
                    1 === o && (e = s)
                },
                d = !0;
            t.addEventListener("touchstart", function(t) {
                i.enabled && (d = !0, o = t.touches.length, m(t))
            }), t.addEventListener("touchmove", function(t) {
                if (i.enabled) {
                    if (d) h(t), n && u(t), s = r(t.touches);
                    else {
                        switch (n) {
                            case "zoom":
                                i.handleZoom(t, f(s, r(t.touches)));
                                break;
                            case "drag":
                                i.handleDrag(t)
                        }
                        n && (u(t), i.update())
                    }
                    d = !1
                }
            }), t.addEventListener("touchend", function(t) {
                i.enabled && (o = t.touches.length, h(t))
            })
        };
        return i
    };
    "undefined" != typeof define && define.amd ? define(["jquery"], function(i) {
        return t(i)
    }) : (window.RTP = window.RTP || {}, window.RTP.PinchZoom = t(window.$))
}).call(this);
! function(t, e, i) {
    function n(i, n, o) {
        var r = e.createElement(i);
        return n && (r.id = Z + n), o && (r.style.cssText = o), t(r)
    }

    function o() {
        return i.innerHeight ? i.innerHeight : t(i).height()
    }

    function r(e, i) {
        i !== Object(i) && (i = {}), this.cache = {}, this.el = e, this.value = function(e) {
            var n;
            return void 0 === this.cache[e] && (n = t(this.el).attr("data-cbox-" + e), void 0 !== n ? this.cache[e] = n : void 0 !== i[e] ? this.cache[e] = i[e] : void 0 !== X[e] && (this.cache[e] = X[e])), this.cache[e]
        }, this.get = function(e) {
            var i = this.value(e);
            return t.isFunction(i) ? i.call(this.el, this) : i
        }
    }

    function h(t) {
        var e = W.length,
            i = (A + t) % e;
        return 0 > i ? e + i : i
    }

    function a(t, e) {
        return Math.round((/%/.test(t) ? ("x" === e ? E.width() : o()) / 100 : 1) * parseInt(t, 10))
    }

    function s(t, e) {
        return t.get("photo") || t.get("photoRegex").test(e)
    }

    function l(t, e) {
        return t.get("retinaUrl") && i.devicePixelRatio > 1 ? e.replace(t.get("photoRegex"), t.get("retinaSuffix")) : e
    }

    function d(t) {
        "contains" in x[0] && !x[0].contains(t.target) && t.target !== v[0] && (t.stopPropagation(), x.focus())
    }

    function c(t) {
        c.str !== t && (x.add(v).removeClass(c.str).addClass(t), c.str = t)
    }

    function g(e) {
        A = 0, e && e !== !1 && "nofollow" !== e ? (W = t("." + te).filter(function() {
            var i = t.data(this, Y),
                n = new r(this, i);
            return n.get("rel") === e
        }), A = W.index(_.el), -1 === A && (W = W.add(_.el), A = W.length - 1)) : W = t(_.el)
    }

    function u(i) {
        t(e).trigger(i), ae.triggerHandler(i)
    }

    function f(i) {
        var o;
        if (!G) {
            if (o = t(i).data(Y), _ = new r(i, o), g(_.get("rel")), !$) {
                $ = q = !0, c(_.get("className")), x.css({
                    visibility: "hidden",
                    display: "block",
                    opacity: ""
                }), I = n(se, "LoadedContent", "width:0; height:0; overflow:hidden; visibility:hidden"), b.css({
                    width: "",
                    height: ""
                }).append(I), j = T.height() + k.height() + b.outerHeight(!0) - b.height(), D = C.width() + H.width() + b.outerWidth(!0) - b.width(), N = I.outerHeight(!0), z = I.outerWidth(!0);
                var h = a(_.get("initialWidth"), "x"),
                    s = a(_.get("initialHeight"), "y"),
                    l = _.get("maxWidth"),
                    f = _.get("maxHeight");
                _.w = Math.max((l !== !1 ? Math.min(h, a(l, "x")) : h) - z - D, 0), _.h = Math.max((f !== !1 ? Math.min(s, a(f, "y")) : s) - N - j, 0), I.css({
                    width: "",
                    height: _.h
                }), J.position(), u(ee), _.get("onOpen"), O.add(F).hide(), x.focus(), _.get("trapFocus") && e.addEventListener && (e.addEventListener("focus", d, !0), ae.one(re, function() {
                    e.removeEventListener("focus", d, !0)
                })), _.get("returnFocus") && ae.one(re, function() {
                    t(_.el).focus()
                })
            }
            var p = parseFloat(_.get("opacity"));
            v.css({
                opacity: p === p ? p : "",
                cursor: _.get("overlayClose") ? "pointer" : "",
                visibility: "visible"
            }).show(), _.get("closeButton") ? B.html(_.get("close")).appendTo(b) : B.appendTo("<div/>"), w()
        }
    }

    function p() {
        x || (V = !1, E = t(i), x = n(se).attr({
            id: Y,
            "class": t.support.opacity === !1 ? Z + "IE" : "",
            role: "dialog",
            tabindex: "-1"
        }).hide(), v = n(se, "Overlay").hide(), L = t([n(se, "LoadingOverlay")[0], n(se, "LoadingGraphic")[0]]), y = n(se, "Wrapper"), b = n(se, "Content").append(F = n(se, "Title"), R = n(se, "Current"), P = t('<button type="button"/>').attr({
            id: Z + "Previous"
        }), K = t('<button type="button"/>').attr({
            id: Z + "Next"
        }), S = n("button", "Slideshow"), L), B = t('<button type="button"/>').attr({
            id: Z + "Close"
        }), y.append(n(se).append(n(se, "TopLeft"), T = n(se, "TopCenter"), n(se, "TopRight")), n(se, !1, "clear:left").append(C = n(se, "MiddleLeft"), b, H = n(se, "MiddleRight")), n(se, !1, "clear:left").append(n(se, "BottomLeft"), k = n(se, "BottomCenter"), n(se, "BottomRight"))).find("div div").css({
            "float": "left"
        }), M = n(se, !1, "position:absolute; width:9999px; visibility:hidden; display:none; max-width:none;"), O = K.add(P).add(R).add(S)), e.body && !x.parent().length && t(e.body).append(v, x.append(y, M))
    }

    function m() {
        function i(t) {
            t.which > 1 || t.shiftKey || t.altKey || t.metaKey || t.ctrlKey || (t.preventDefault(), f(this))
        }
        return x ? (V || (V = !0, K.click(function() {
            J.next()
        }), P.click(function() {
            J.prev()
        }), B.click(function() {
            J.close()
        }), v.click(function() {
            _.get("overlayClose") && J.close()
        }), t(e).bind("keydown." + Z, function(t) {
            var e = t.keyCode;
            $ && _.get("escKey") && 27 === e && (t.preventDefault(), J.close()), $ && _.get("arrowKey") && W[1] && !t.altKey && (37 === e ? (t.preventDefault(), P.click()) : 39 === e && (t.preventDefault(), K.click()))
        }), t.isFunction(t.fn.on) ? t(e).on("click." + Z, "." + te, i) : t("." + te).live("click." + Z, i)), !0) : !1
    }

    function w() {
        var e, o, r, h = J.prep,
            d = ++le;
        if (q = !0, U = !1, u(he), u(ie), _.get("onLoad"), _.h = _.get("height") ? a(_.get("height"), "y") - N - j : _.get("innerHeight") && a(_.get("innerHeight"), "y"), _.w = _.get("width") ? a(_.get("width"), "x") - z - D : _.get("innerWidth") && a(_.get("innerWidth"), "x"), _.mw = _.w, _.mh = _.h, _.get("maxWidth") && (_.mw = a(_.get("maxWidth"), "x") - z - D, _.mw = _.w && _.w < _.mw ? _.w : _.mw), _.get("maxHeight") && (_.mh = a(_.get("maxHeight"), "y") - N - j, _.mh = _.h && _.h < _.mh ? _.h : _.mh), e = _.get("href"), Q = setTimeout(function() {
                L.show()
            }, 100), _.get("inline")) {
            var c = t(e);
            r = t("<div>").hide().insertBefore(c), ae.one(he, function() {
                r.replaceWith(c)
            }), h(c)
        } else _.get("iframe") ? h(" ") : _.get("html") ? h(_.get("html")) : s(_, e) ? (e = l(_, e), U = _.get("createImg"), t(U).addClass(Z + "Photo").bind("error." + Z, function() {
            h(n(se, "Error").html(_.get("imgError")))
        }).one("load", function() {
            d === le && setTimeout(function() {
                var e;
                _.get("retinaImage") && i.devicePixelRatio > 1 && (U.height = U.height / i.devicePixelRatio, U.width = U.width / i.devicePixelRatio), _.get("scalePhotos") && (o = function() {
                    U.height -= U.height * e, U.width -= U.width * e
                }, _.mw && U.width > _.mw && (e = (U.width - _.mw) / U.width, o()), _.mh && U.height > _.mh && (e = (U.height - _.mh) / U.height, o())), _.h && (U.style.marginTop = Math.max(_.mh - U.height, 0) / 2 + "px"), W[1] && (_.get("loop") || W[A + 1]) && (U.style.cursor = "pointer", t(U).bind("click." + Z, function() {
                    J.next()
                })), U.style.width = U.width + "px", U.style.height = U.height + "px", h(U)
            }, 1)
        }), U.src = e) : e && M.load(e, _.get("data"), function(e, i) {
            d === le && h("error" === i ? n(se, "Error").html(_.get("xhrError")) : t(this).contents())
        })
    }
    var v, x, y, b, T, C, H, k, W, E, I, M, L, F, R, S, K, P, B, O, _, j, D, N, z, A, U, $, q, G, Q, J, V, X = {
            html: !1,
            photo: !1,
            iframe: !1,
            inline: !1,
            transition: "elastic",
            speed: 300,
            fadeOut: 300,
            width: !1,
            initialWidth: "600",
            innerWidth: !1,
            maxWidth: !1,
            height: !1,
            initialHeight: "450",
            innerHeight: !1,
            maxHeight: !1,
            scalePhotos: !0,
            scrolling: !0,
            opacity: .9,
            preloading: !0,
            className: !1,
            overlayClose: !0,
            escKey: !0,
            arrowKey: !0,
            top: !1,
            bottom: !1,
            left: !1,
            right: !1,
            fixed: !1,
            data: void 0,
            closeButton: !0,
            fastIframe: !0,
            open: !1,
            reposition: !0,
            loop: !0,
            slideshow: !1,
            slideshowAuto: !0,
            slideshowSpeed: 2500,
            slideshowStart: "start slideshow",
            slideshowStop: "stop slideshow",
            photoRegex: /\.(gif|png|jp(e|g|eg)|bmp|ico|webp|jxr|svg)((#|\?).*)?$/i,
            retinaImage: !1,
            retinaUrl: !1,
            retinaSuffix: "@2x.$1",
            current: "image {current} of {total}",
            previous: "previous",
            next: "next",
            close: "close",
            xhrError: "This content failed to load.",
            imgError: "This image failed to load.",
            returnFocus: !0,
            trapFocus: !0,
            onOpen: !1,
            onLoad: !1,
            onComplete: !1,
            onCleanup: !1,
            onClosed: !1,
            rel: function() {
                return this.rel
            },
            href: function() {
                return t(this).attr("href")
            },
            title: function() {
                return this.title
            },
            createImg: function() {
                var e = new Image,
                    i = t(this).data("cbox-img-attrs");
                return "object" == typeof i && t.each(i, function(t, i) {
                    e[t] = i
                }), e
            },
            createIframe: function() {
                var i = e.createElement("iframe"),
                    n = t(this).data("cbox-iframe-attrs");
                return "object" == typeof n && t.each(n, function(t, e) {
                    i[t] = e
                }), "frameBorder" in i && (i.frameBorder = 0), "allowTransparency" in i && (i.allowTransparency = "true"), i.name = (new Date).getTime(), i.allowFullscreen = !0, i
            }
        },
        Y = "colorbox",
        Z = "cbox",
        te = Z + "Element",
        ee = Z + "_open",
        ie = Z + "_load",
        ne = Z + "_complete",
        oe = Z + "_cleanup",
        re = Z + "_closed",
        he = Z + "_purge",
        ae = t("<a/>"),
        se = "div",
        le = 0,
        de = {},
        ce = function() {
            function t() {
                clearTimeout(h)
            }

            function e() {
                (_.get("loop") || W[A + 1]) && (t(), h = setTimeout(J.next, _.get("slideshowSpeed")))
            }

            function i() {
                S.html(_.get("slideshowStop")).unbind(s).one(s, n), ae.bind(ne, e).bind(ie, t), x.removeClass(a + "off").addClass(a + "on")
            }

            function n() {
                t(), ae.unbind(ne, e).unbind(ie, t), S.html(_.get("slideshowStart")).unbind(s).one(s, function() {
                    J.next(), i()
                }), x.removeClass(a + "on").addClass(a + "off")
            }

            function o() {
                r = !1, S.hide(), t(), ae.unbind(ne, e).unbind(ie, t), x.removeClass(a + "off " + a + "on")
            }
            var r, h, a = Z + "Slideshow_",
                s = "click." + Z;
            return function() {
                r ? _.get("slideshow") || (ae.unbind(oe, o), o()) : _.get("slideshow") && W[1] && (r = !0, ae.one(oe, o), _.get("slideshowAuto") ? i() : n(), S.show())
            }
        }();
    t[Y] || (t(p), J = t.fn[Y] = t[Y] = function(e, i) {
        var n, o = this;
        return e = e || {}, t.isFunction(o) && (o = t("<a/>"), e.open = !0), o[0] ? (p(), m() && (i && (e.onComplete = i), o.each(function() {
            var i = t.data(this, Y) || {};
            t.data(this, Y, t.extend(i, e))
        }).addClass(te), n = new r(o[0], e), n.get("open") && f(o[0])), o) : o
    }, J.position = function(e, i) {
        function n() {
            T[0].style.width = k[0].style.width = b[0].style.width = parseInt(x[0].style.width, 10) - D + "px", b[0].style.height = C[0].style.height = H[0].style.height = parseInt(x[0].style.height, 10) - j + "px"
        }
        var r, h, s, l = 0,
            d = 0,
            c = x.offset();
        if (E.unbind("resize." + Z), x.css({
                top: -9e4,
                left: -9e4
            }), h = E.scrollTop(), s = E.scrollLeft(), _.get("fixed") ? (c.top -= h, c.left -= s, x.css({
                position: "fixed"
            })) : (l = h, d = s, x.css({
                position: "absolute"
            })), d += _.get("right") !== !1 ? Math.max(E.width() - _.w - z - D - a(_.get("right"), "x"), 0) : _.get("left") !== !1 ? a(_.get("left"), "x") : Math.round(Math.max(E.width() - _.w - z - D, 0) / 2), l += _.get("bottom") !== !1 ? Math.max(o() - _.h - N - j - a(_.get("bottom"), "y"), 0) : _.get("top") !== !1 ? a(_.get("top"), "y") : Math.round(Math.max(o() - _.h - N - j, 0) / 2), x.css({
                top: c.top,
                left: c.left,
                visibility: "visible"
            }), y[0].style.width = y[0].style.height = "9999px", r = {
                width: _.w + z + D,
                height: _.h + N + j,
                top: l,
                left: d
            }, e) {
            var g = 0;
            t.each(r, function(t) {
                return r[t] !== de[t] ? void(g = e) : void 0
            }), e = g
        }
        de = r, e || x.css(r), x.dequeue().animate(r, {
            duration: e || 0,
            complete: function() {
                n(), q = !1, y[0].style.width = _.w + z + D + "px", y[0].style.height = _.h + N + j + "px", _.get("reposition") && setTimeout(function() {
                    E.bind("resize." + Z, J.position)
                }, 1), t.isFunction(i) && i()
            },
            step: n
        })
    }, J.resize = function(t) {
        var e;
        $ && (t = t || {}, t.width && (_.w = a(t.width, "x") - z - D), t.innerWidth && (_.w = a(t.innerWidth, "x")), I.css({
            width: _.w
        }), t.height && (_.h = a(t.height, "y") - N - j), t.innerHeight && (_.h = a(t.innerHeight, "y")), t.innerHeight || t.height || (e = I.scrollTop(), I.css({
            height: "auto"
        }), _.h = I.height()), I.css({
            height: _.h
        }), e && I.scrollTop(e), J.position("none" === _.get("transition") ? 0 : _.get("speed")))
    }, J.prep = function(i) {
        function o() {
            return _.w = _.w || I.width(), _.w = _.mw && _.mw < _.w ? _.mw : _.w, _.w
        }

        function a() {
            return _.h = _.h || I.height(), _.h = _.mh && _.mh < _.h ? _.mh : _.h, _.h
        }
        if ($) {
            var d, g = "none" === _.get("transition") ? 0 : _.get("speed");
            I.remove(), I = n(se, "LoadedContent").append(i), I.hide().appendTo(M.show()).css({
                width: o(),

                overflow: _.get("scrolling") ? "auto" : "hidden"
            }).css({
                height: a()
            }).prependTo(b), M.hide(), t(U).css({
                "float": "none"
            }), c(_.get("className")), d = function() {
                function i() {
                    t.support.opacity === !1 && x[0].style.removeAttribute("filter")
                }
                var n, o, a = W.length;
                $ && (o = function() {
                    clearTimeout(Q), L.hide(), u(ne), _.get("onComplete")
                }, F.html(_.get("title")).show(), I.show(), a > 1 ? ("string" == typeof _.get("current") && R.html(_.get("current").replace("{current}", A + 1).replace("{total}", a)).show(), K[_.get("loop") || a - 1 > A ? "show" : "hide"]().html(_.get("next")), P[_.get("loop") || A ? "show" : "hide"]().html(_.get("previous")), ce(), _.get("preloading") && t.each([h(-1), h(1)], function() {
                    var i, n = W[this],
                        o = new r(n, t.data(n, Y)),
                        h = o.get("href");
                    h && s(o, h) && (h = l(o, h), i = e.createElement("img"), i.src = h)
                })) : O.hide(), _.get("iframe") ? (n = _.get("createIframe"), _.get("scrolling") || (n.scrolling = "no"), t(n).attr({
                    src: _.get("href"),
                    "class": Z + "Iframe"
                }).one("load", o).appendTo(I), ae.one(he, function() {
                    n.src = "//about:blank"
                }), _.get("fastIframe") && t(n).trigger("load")) : o(), "fade" === _.get("transition") ? x.fadeTo(g, 1, i) : i())
            }, "fade" === _.get("transition") ? x.fadeTo(g, 0, function() {
                J.position(0, d)
            }) : J.position(g, d)
        }
    }, J.next = function() {
        !q && W[1] && (_.get("loop") || W[A + 1]) && (A = h(1), f(W[A]))
    }, J.prev = function() {
        !q && W[1] && (_.get("loop") || A) && (A = h(-1), f(W[A]))
    }, J.close = function() {
        $ && !G && (G = !0, $ = !1, u(oe), _.get("onCleanup"), E.unbind("." + Z), v.fadeTo(_.get("fadeOut") || 0, 0), x.stop().fadeTo(_.get("fadeOut") || 0, 0, function() {
            x.hide(), v.hide(), u(he), I.remove(), setTimeout(function() {
                G = !1, u(re), _.get("onClosed")
            }, 1)
        }))
    }, J.remove = function() {
        x && (x.stop(), t[Y].close(), x.stop(!1, !0).remove(), v.remove(), G = !1, x = null, t("." + te).removeData(Y).removeClass(te), t(e).unbind("click." + Z).unbind("keydown." + Z))
    }, J.element = function() {
        return t(_.el)
    }, J.settings = X)
}(jQuery, document, window);
var Veggieburger, __bind = function(t, e) {
    return function() {
        return t.apply(e, arguments)
    }
};
Veggieburger = function() {
    function t(t, e) {
        this.keyClose = __bind(this.keyClose, this), this.$el = $(t), this.settings = $.extend(this.defaultSettings(), e), this.triggers = null !== this.settings.triggers ? this.multiSet(this.settings.triggers) : [$(this.$el.find(":button")[0])], this.transitionHeight = null !== this.settings.transitionHeight ? this.multiSet(this.settings.transitionHeight) : null, this.toggleable = this.multiSet(this.settings.toggle), this.toggleable = this.toggleable.concat(this.triggers), this.transitionSpeed = this.settings.transitionSpeed, null !== this.transitionHeight && (this.toggleable = this.toggleable.concat(this.transitionHeight)), this.closers = null !== this.settings.closers ? this.multiSet(this.settings.closers) : null, this.toggledClass = this.settings.toggledClass, this.closedClass = null !== this.settings.closedClass ? this.settings.closedClass : null, this.prevent = this.settings.preventDefault, this.outside = "boolean" != typeof this.settings.outside ? this.multiSet(this.settings.outside) : this.settings.outside, this.onToggleOn = "function" == typeof this.settings.onToggleOn ? this.settings.onToggleOn : function() {}, this.onToggleOff = "function" == typeof this.settings.onToggleOff ? this.settings.onToggleOff : function() {}, this.closeKeys = null !== this.settings.closeKeys ? Number(this.settings.closeKeys) === this.settings.closeKeys && this.settings.closeKeys % 1 === 0 && "[object Array]" !== Object.prototype.toString.call(this.settings.closeKeys) ? [this.settings.closeKeys] : "[object Array]" === Object.prototype.toString.call(this.settings.closeKeys) ? this.settings.closeKeys : null : null, this.bindToggle()
    }
    return t.prototype.defaultSettings = function() {
        return {
            triggers: null,
            toggle: "[data-toggle]",
            toggledClass: "open",
            closedClass: null,
            closers: null,
            closeKeys: null,
            transitionHeight: null,
            transitionSpeed: 200,
            preventDefault: !0,
            outside: !1,
            touch: !1,
            onToggleOn: function() {},
            onToggleOff: function() {}
        }
    }, t.prototype.multiSet = function(t) {
        var e, s, i, n;
        if (e = [], "[object Array]" === Object.prototype.toString.call(t)) {
            for (i = 0, n = t.length; n > i; i++) s = t[i], e.push(this.$el.find(s));
            return e
        }
        return e.push(this.$el.find(t)), e
    }, t.prototype.toggleAll = function() {
        var t, e, s, i, n, o, l, g, h, r, u, a, c;
        for (s = !1, r = this.toggleable, i = 0, l = r.length; l > i; i++) t = r[i], t.hasClass(this.closedClass) && t.toggleClass(this.closedClass), t.toggleClass(this.toggledClass), t.hasClass(this.toggledClass) && (s = !0);
        if (s) this.onToggleOn.call(this.$el), this.bindClose();
        else if (this.onToggleOff.call(this.$el), this.unbindClose(), null !== this.closedClass)
            for (u = this.toggleable, n = 0, g = u.length; g > n; n++) t = u[n], t.toggleClass(this.closedClass);
        for (a = this.transitionHeight, c = [], o = 0, h = a.length; h > o; o++) e = a[o], c.push(e.hasClass(this.toggledClass) ? this.transitionOpenHeight(e) : this.transitionCloseHeight(e));
        return c
    }, t.prototype.transitionOpenHeight = function(t) {
        var e;
        return t.css({
            display: "inherit",
            height: "auto"
        }), e = t.outerHeight(), t.css("height", 0), t.animate({
            height: e
        }, this.transitionSpeed, function() {
            return t.css("height", "auto")
        })
    }, t.prototype.transitionCloseHeight = function(t) {
        return t.animate({
            height: 0
        }, this.transitionSpeed)
    }, t.prototype.bindToggle = function() {
        var t, e, s, i, n;
        for (i = this.triggers, n = [], e = 0, s = i.length; s > e; e++) t = i[e], n.push(t.click(function(t) {
            return function(e) {
                return t.prevent && (e.preventDefault ? e.preventDefault() : e.returnValue = !1), t.toggleAll()
            }
        }(this)));
        return n
    }, t.prototype.outHide = function(t) {
        var e, s, i, n, o, l, g, h, r;
        if (e = !0, "boolean" != typeof this.outside && this.outside)
            for (h = this.outside, n = 0, l = h.length; l > n; n++) s = h[n], $(t.target).closest(s).length && (e = !1);
        if ("boolean" == typeof this.outside && this.outside)
            for (r = this.toggleable, o = 0, g = r.length; g > o; o++) i = r[o], $(t.target).closest(i).length && (e = !1);
        return e
    }, t.prototype.keyClose = function(t) {
        return -1 !== $.inArray(t.keyCode, this.closeKeys) ? this.toggleAll() : void 0
    }, t.prototype.bindClose = function() {
        var t, e, s, i;
        if ($("body").bind("mouseup touchend", function(t) {
                return function(e) {
                    return t.outside && t.outHide(e) ? t.toggleAll() : void 0
                }
            }(this)), this.settings.touch === !0 && (this.$el.swipe("enable"), this.$el.swipe({
                swipeLeft: function(t) {
                    return function() {
                        return t.toggleAll()
                    }
                }(this)
            })), null !== this.closers)
            for (i = this.closers, e = 0, s = i.length; s > e; e++) t = i[e], t.bind("click", function(t) {
                return function(e) {
                    return t.prevent && (e.preventDefault ? e.preventDefault() : e.returnValue = !1), t.toggleAll()
                }
            }(this));
        return null !== this.closeKeys ? $(document).keyup(this.keyClose) : void 0
    }, t.prototype.unbindClose = function() {
        var t, e, s, i;
        if ($("body").unbind(), this.settings.touch === !0 && this.$el.swipe("disable"), null !== this.closers)
            for (i = this.closers, e = 0, s = i.length; s > e; e++) t = i[e], t.unbind();
        return null !== this.closeKeys ? $(document).unbind("keyup", this.keyClose) : void 0
    }, t
}(), $.fn.extend({
    veggieburger: function(t) {
        return this.each(function() {
            return new Veggieburger(this, t)
        })
    }
});
if (function(t) {
        "function" == typeof define && define.amd && define.amd.jQuery ? define(["jquery"], t) : t(jQuery)
    }(function(t) {
        function e(e) {
            return !e || void 0 !== e.allowPageScroll || void 0 === e.swipe && void 0 === e.swipeStatus || (e.allowPageScroll = h), void 0 !== e.click && void 0 === e.tap && (e.tap = e.click), e || (e = {}), e = t.extend({}, t.fn.swipe.defaults, e), this.each(function() {
                var n = t(this),
                    r = n.data(k);
                r || (r = new i(this, e), n.data(k, r))
            })
        }

        function i(e, i) {
            function C(e) {
                if (!(he() || t(e.target).closest(i.excludedElements, Ue).length > 0)) {
                    var n, r = e.originalEvent ? e.originalEvent : e,
                        s = P ? r.touches[0] : r;
                    return Ve = x, P ? We = r.touches.length : e.preventDefault(), Ie = 0, Xe = null, Be = null, Ne = 0, ze = 0, Fe = 0, Ye = 1, je = 0, He = de(), Ge = ge(), oe(), !P || We === i.fingers || i.fingers === v || G() ? (pe(0, s), Qe = Oe(), 2 == We && (pe(1, r.touches[1]), ze = Fe = xe(He[0].start, He[1].start)), (i.swipeStatus || i.pinchStatus) && (n = I(r, Ve))) : n = !1, n === !1 ? (Ve = b, I(r, Ve), n) : (i.hold && (ti = setTimeout(t.proxy(function() {
                        Ue.trigger("hold", [r.target]), i.hold && (n = i.hold.call(Ue, r, r.target))
                    }, this), i.longTapThreshold)), ue(!0), null)
                }
            }

            function A(t) {
                var e = t.originalEvent ? t.originalEvent : t;
                if (Ve !== T && Ve !== b && !le()) {
                    var n, r = P ? e.touches[0] : e,
                        s = ce(r);
                    if (qe = Oe(), P && (We = e.touches.length), i.hold && clearTimeout(ti), Ve = w, 2 == We && (0 == ze ? (pe(1, e.touches[1]), ze = Fe = xe(He[0].start, He[1].start)) : (ce(e.touches[1]), Fe = xe(He[0].end, He[1].end), Be = Te(He[0].end, He[1].end)), Ye = we(ze, Fe), je = Math.abs(ze - Fe)), We === i.fingers || i.fingers === v || !P || G()) {
                        if (Xe = Se(s.start, s.end), j(t, Xe), Ie = be(s.start, s.end), Ne = ye(), _e(Xe, Ie), (i.swipeStatus || i.pinchStatus) && (n = I(e, Ve)), !i.triggerOnTouchEnd || i.triggerOnTouchLeave) {
                            var a = !0;
                            if (i.triggerOnTouchLeave) {
                                var o = ke(this);
                                a = Ce(s.end, o)
                            }!i.triggerOnTouchEnd && a ? Ve = D(w) : i.triggerOnTouchLeave && !a && (Ve = D(T)), (Ve == b || Ve == T) && I(e, Ve)
                        }
                    } else Ve = b, I(e, Ve);
                    n === !1 && (Ve = b, I(e, Ve))
                }
            }

            function R(t) {
                var e = t.originalEvent;
                return P && e.touches.length > 0 ? (ae(), !0) : (le() && (We = $e), qe = Oe(), Ne = ye(), z() || !N() ? (Ve = b, I(e, Ve)) : i.triggerOnTouchEnd || 0 == i.triggerOnTouchEnd && Ve === w ? (t.preventDefault(), Ve = T, I(e, Ve)) : !i.triggerOnTouchEnd && Z() ? (Ve = T, X(e, Ve, f)) : Ve === w && (Ve = b, I(e, Ve)), ue(!1), null)
            }

            function M() {
                We = 0, qe = 0, Qe = 0, ze = 0, Fe = 0, Ye = 1, oe(), ue(!1)
            }

            function E(t) {
                var e = t.originalEvent;
                i.triggerOnTouchLeave && (Ve = D(T), I(e, Ve))
            }

            function L() {
                Ue.unbind(Re, C), Ue.unbind(De, M), Ue.unbind(Me, A), Ue.unbind(Ee, R), Le && Ue.unbind(Le, E), ue(!1)
            }

            function D(t) {
                var e = t,
                    n = Y(),
                    r = N(),
                    s = z();
                return !n || s ? e = b : !r || t != w || i.triggerOnTouchEnd && !i.triggerOnTouchLeave ? !r && t == T && i.triggerOnTouchLeave && (e = b) : e = T, e
            }

            function I(t, e) {
                var i = void 0;
                return H() || W() ? i = X(t, e, p) : (U() || G()) && i !== !1 && (i = X(t, e, c)), re() && i !== !1 ? i = X(t, e, d) : se() && i !== !1 ? i = X(t, e, _) : ne() && i !== !1 && (i = X(t, e, f)), e === b && M(t), e === T && (P ? 0 == t.touches.length && M(t) : M(t)), i
            }

            function X(e, h, u) {
                var m = void 0;
                if (u == p) {
                    if (Ue.trigger("swipeStatus", [h, Xe || null, Ie || 0, Ne || 0, We, He]), i.swipeStatus && (m = i.swipeStatus.call(Ue, e, h, Xe || null, Ie || 0, Ne || 0, We, He), m === !1)) return !1;
                    if (h == T && V()) {
                        if (Ue.trigger("swipe", [Xe, Ie, Ne, We, He]), i.swipe && (m = i.swipe.call(Ue, e, Xe, Ie, Ne, We, He), m === !1)) return !1;
                        switch (Xe) {
                            case n:
                                Ue.trigger("swipeLeft", [Xe, Ie, Ne, We, He]), i.swipeLeft && (m = i.swipeLeft.call(Ue, e, Xe, Ie, Ne, We, He));
                                break;
                            case r:
                                Ue.trigger("swipeRight", [Xe, Ie, Ne, We, He]), i.swipeRight && (m = i.swipeRight.call(Ue, e, Xe, Ie, Ne, We, He));
                                break;
                            case s:
                                Ue.trigger("swipeUp", [Xe, Ie, Ne, We, He]), i.swipeUp && (m = i.swipeUp.call(Ue, e, Xe, Ie, Ne, We, He));
                                break;
                            case a:
                                Ue.trigger("swipeDown", [Xe, Ie, Ne, We, He]), i.swipeDown && (m = i.swipeDown.call(Ue, e, Xe, Ie, Ne, We, He))
                        }
                    }
                }
                if (u == c) {
                    if (Ue.trigger("pinchStatus", [h, Be || null, je || 0, Ne || 0, We, Ye, He]), i.pinchStatus && (m = i.pinchStatus.call(Ue, e, h, Be || null, je || 0, Ne || 0, We, Ye, He), m === !1)) return !1;
                    if (h == T && B()) switch (Be) {
                        case o:
                            Ue.trigger("pinchIn", [Be || null, je || 0, Ne || 0, We, Ye, He]), i.pinchIn && (m = i.pinchIn.call(Ue, e, Be || null, je || 0, Ne || 0, We, Ye, He));
                            break;
                        case l:
                            Ue.trigger("pinchOut", [Be || null, je || 0, Ne || 0, We, Ye, He]), i.pinchOut && (m = i.pinchOut.call(Ue, e, Be || null, je || 0, Ne || 0, We, Ye, He))
                    }
                }
                return u == f ? (h === b || h === T) && (clearTimeout(Je), clearTimeout(ti), $() && !te() ? (Ke = Oe(), Je = setTimeout(t.proxy(function() {
                    Ke = null, Ue.trigger("tap", [e.target]), i.tap && (m = i.tap.call(Ue, e, e.target))
                }, this), i.doubleTapThreshold)) : (Ke = null, Ue.trigger("tap", [e.target]), i.tap && (m = i.tap.call(Ue, e, e.target)))) : u == d ? (h === b || h === T) && (clearTimeout(Je), Ke = null, Ue.trigger("doubletap", [e.target]), i.doubleTap && (m = i.doubleTap.call(Ue, e, e.target))) : u == _ && (h === b || h === T) && (clearTimeout(Je), Ke = null, Ue.trigger("longtap", [e.target]), i.longTap && (m = i.longTap.call(Ue, e, e.target))), m
            }

            function N() {
                var t = !0;
                return null !== i.threshold && (t = Ie >= i.threshold), t
            }

            function z() {
                var t = !1;
                return null !== i.cancelThreshold && null !== Xe && (t = me(Xe) - Ie >= i.cancelThreshold), t
            }

            function F() {
                return null !== i.pinchThreshold ? je >= i.pinchThreshold : !0
            }

            function Y() {
                var t;
                return t = i.maxTimeThreshold && Ne >= i.maxTimeThreshold ? !1 : !0
            }

            function j(t, e) {
                if (i.allowPageScroll === h || G()) t.preventDefault();
                else {
                    var o = i.allowPageScroll === u;
                    switch (e) {
                        case n:
                            (i.swipeLeft && o || !o && i.allowPageScroll != m) && t.preventDefault();
                            break;
                        case r:
                            (i.swipeRight && o || !o && i.allowPageScroll != m) && t.preventDefault();
                            break;
                        case s:
                            (i.swipeUp && o || !o && i.allowPageScroll != g) && t.preventDefault();
                            break;
                        case a:
                            (i.swipeDown && o || !o && i.allowPageScroll != g) && t.preventDefault()
                    }
                }
            }

            function B() {
                var t = Q(),
                    e = q(),
                    i = F();
                return t && e && i
            }

            function G() {
                return !!(i.pinchStatus || i.pinchIn || i.pinchOut)
            }

            function U() {
                return !(!B() || !G())
            }

            function V() {
                var t = Y(),
                    e = N(),
                    i = Q(),
                    n = q(),
                    r = z(),
                    s = !r && n && i && e && t;
                return s
            }

            function W() {
                return !!(i.swipe || i.swipeStatus || i.swipeLeft || i.swipeRight || i.swipeUp || i.swipeDown)
            }

            function H() {
                return !(!V() || !W())
            }

            function Q() {
                return We === i.fingers || i.fingers === v || !P
            }

            function q() {
                return 0 !== He[0].end.x
            }

            function Z() {
                return !!i.tap
            }

            function $() {
                return !!i.doubleTap
            }

            function K() {
                return !!i.longTap
            }

            function J() {
                if (null == Ke) return !1;
                var t = Oe();
                return $() && t - Ke <= i.doubleTapThreshold
            }

            function te() {
                return J()
            }

            function ee() {
                return (1 === We || !P) && (isNaN(Ie) || Ie < i.threshold)
            }

            function ie() {
                return Ne > i.longTapThreshold && y > Ie
            }

            function ne() {
                return !(!ee() || !Z())
            }

            function re() {
                return !(!J() || !$())
            }

            function se() {
                return !(!ie() || !K())
            }

            function ae() {
                Ze = Oe(), $e = event.touches.length + 1
            }

            function oe() {
                Ze = 0, $e = 0
            }

            function le() {
                var t = !1;
                if (Ze) {
                    var e = Oe() - Ze;
                    e <= i.fingerReleaseThreshold && (t = !0)
                }
                return t
            }

            function he() {
                return !(Ue.data(k + "_intouch") !== !0)
            }

            function ue(t) {
                t === !0 ? (Ue.bind(Me, A), Ue.bind(Ee, R), Le && Ue.bind(Le, E)) : (Ue.unbind(Me, A, !1), Ue.unbind(Ee, R, !1), Le && Ue.unbind(Le, E, !1)), Ue.data(k + "_intouch", t === !0)
            }

            function pe(t, e) {
                var i = void 0 !== e.identifier ? e.identifier : 0;
                return He[t].identifier = i, He[t].start.x = He[t].end.x = e.pageX || e.clientX, He[t].start.y = He[t].end.y = e.pageY || e.clientY, He[t]
            }

            function ce(t) {
                var e = void 0 !== t.identifier ? t.identifier : 0,
                    i = fe(e);
                return i.end.x = t.pageX || t.clientX, i.end.y = t.pageY || t.clientY, i
            }

            function fe(t) {
                for (var e = 0; e < He.length; e++)
                    if (He[e].identifier == t) return He[e]
            }

            function de() {
                for (var t = [], e = 0; 5 >= e; e++) t.push({
                    start: {
                        x: 0,
                        y: 0
                    },
                    end: {
                        x: 0,
                        y: 0
                    },
                    identifier: 0
                });
                return t
            }

            function _e(t, e) {
                e = Math.max(e, me(t)), Ge[t].distance = e
            }

            function me(t) {
                return Ge[t] ? Ge[t].distance : void 0
            }

            function ge() {
                var t = {};
                return t[n] = ve(n), t[r] = ve(r), t[s] = ve(s), t[a] = ve(a), t
            }

            function ve(t) {
                return {
                    direction: t,
                    distance: 0
                }
            }

            function ye() {
                return qe - Qe
            }

            function xe(t, e) {
                var i = Math.abs(t.x - e.x),
                    n = Math.abs(t.y - e.y);
                return Math.round(Math.sqrt(i * i + n * n))
            }

            function we(t, e) {
                var i = e / t * 1;
                return i.toFixed(2)
            }

            function Te() {
                return 1 > Ye ? l : o
            }

            function be(t, e) {
                return Math.round(Math.sqrt(Math.pow(e.x - t.x, 2) + Math.pow(e.y - t.y, 2)))
            }

            function Pe(t, e) {
                var i = t.x - e.x,
                    n = e.y - t.y,
                    r = Math.atan2(n, i),
                    s = Math.round(180 * r / Math.PI);
                return 0 > s && (s = 360 - Math.abs(s)), s
            }

            function Se(t, e) {
                var i = Pe(t, e);
                return 45 >= i && i >= 0 ? n : 360 >= i && i >= 315 ? n : i >= 135 && 225 >= i ? r : i > 45 && 135 > i ? a : s
            }

            function Oe() {
                var t = new Date;
                return t.getTime()
            }

            function ke(e) {
                e = t(e);
                var i = e.offset(),
                    n = {
                        left: i.left,
                        right: i.left + e.outerWidth(),
                        top: i.top,
                        bottom: i.top + e.outerHeight()
                    };
                return n
            }

            function Ce(t, e) {
                return t.x > e.left && t.x < e.right && t.y > e.top && t.y < e.bottom
            }
            var Ae = P || O || !i.fallbackToMouseEvents,
                Re = Ae ? O ? S ? "MSPointerDown" : "pointerdown" : "touchstart" : "mousedown",
                Me = Ae ? O ? S ? "MSPointerMove" : "pointermove" : "touchmove" : "mousemove",
                Ee = Ae ? O ? S ? "MSPointerUp" : "pointerup" : "touchend" : "mouseup",
                Le = Ae ? null : "mouseleave",
                De = O ? S ? "MSPointerCancel" : "pointercancel" : "touchcancel",
                Ie = 0,
                Xe = null,
                Ne = 0,
                ze = 0,
                Fe = 0,
                Ye = 1,
                je = 0,
                Be = 0,
                Ge = null,
                Ue = t(e),
                Ve = "start",
                We = 0,
                He = null,
                Qe = 0,
                qe = 0,
                Ze = 0,
                $e = 0,
                Ke = 0,
                Je = null,
                ti = null;
            try {
                Ue.bind(Re, C), Ue.bind(De, M)
            } catch (ei) {
                t.error("events not supported " + Re + "," + De + " on jQuery.swipe")
            }
            this.enable = function() {
                return Ue.bind(Re, C), Ue.bind(De, M), Ue
            }, this.disable = function() {
                return L(), Ue
            }, this.destroy = function() {
                return L(), Ue.data(k, null), Ue
            }, this.option = function(e, n) {
                if (void 0 !== i[e]) {
                    if (void 0 === n) return i[e];
                    i[e] = n
                } else t.error("Option " + e + " does not exist on jQuery.swipe.options");
                return null
            }
        }
        var n = "left",
            r = "right",
            s = "up",
            a = "down",
            o = "in",
            l = "out",
            h = "none",
            u = "auto",
            p = "swipe",
            c = "pinch",
            f = "tap",
            d = "doubletap",
            _ = "longtap",
            m = "horizontal",
            g = "vertical",
            v = "all",
            y = 10,
            x = "start",
            w = "move",
            T = "end",
            b = "cancel",
            P = "ontouchstart" in window,
            S = window.navigator.msPointerEnabled && !window.navigator.pointerEnabled,
            O = window.navigator.pointerEnabled || window.navigator.msPointerEnabled,
            k = "TouchSwipe",
            C = {
                fingers: 1,
                threshold: 75,
                cancelThreshold: null,
                pinchThreshold: 20,
                maxTimeThreshold: null,
                fingerReleaseThreshold: 250,
                longTapThreshold: 500,
                doubleTapThreshold: 200,
                swipe: null,
                swipeLeft: null,
                swipeRight: null,
                swipeUp: null,
                swipeDown: null,
                swipeStatus: null,
                pinchIn: null,
                pinchOut: null,
                pinchStatus: null,
                click: null,
                tap: null,
                doubleTap: null,
                longTap: null,
                hold: null,
                triggerOnTouchEnd: !0,
                triggerOnTouchLeave: !1,
                allowPageScroll: "auto",
                fallbackToMouseEvents: !0,
                excludedElements: "label, button, input, select, textarea, a, .noSwipe"
            };
        t.fn.swipe = function(i) {
            var n = t(this),
                r = n.data(k);
            if (r && "string" == typeof i) {
                if (r[i]) return r[i].apply(this, Array.prototype.slice.call(arguments, 1));
                t.error("Method " + i + " does not exist on jQuery.swipe")
            } else if (!(r || "object" != typeof i && i)) return e.apply(this, arguments);
            return n
        }, t.fn.swipe.defaults = C, t.fn.swipe.phases = {
            PHASE_START: x,
            PHASE_MOVE: w,
            PHASE_END: T,
            PHASE_CANCEL: b
        }, t.fn.swipe.directions = {
            LEFT: n,
            RIGHT: r,
            UP: s,
            DOWN: a,
            IN: o,
            OUT: l
        }, t.fn.swipe.pageScroll = {
            NONE: h,
            HORIZONTAL: m,
            VERTICAL: g,
            AUTO: u
        }, t.fn.swipe.fingers = {
            ONE: 1,
            TWO: 2,
            THREE: 3,
            ALL: v
        }
    }), "undefined" == typeof console) {
    var console = {};
    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = console.groupCollapsed = function() {}
}
if (1 == window.tplogs) try {
    console.groupCollapsed("ThemePunch GreenSocks Logs")
} catch (e) {}
var oldgs = window.GreenSockGlobals;
oldgs_queue = window._gsQueue;
var punchgs = window.GreenSockGlobals = {};
if (1 == window.tplogs) try {
    console.info("Build GreenSock SandBox for ThemePunch Plugins"), console.info("GreenSock TweenLite Engine Initalised by ThemePunch Plugin")
} catch (e) {}! function(t, e) {
    "use strict";
    var i = t.GreenSockGlobals = t.GreenSockGlobals || t;
    if (!i.TweenLite) {
        var n, r, s, a, o, l = function(t) {
                var e, n = t.split("."),
                    r = i;
                for (e = 0; n.length > e; e++) r[n[e]] = r = r[n[e]] || {};
                return r
            },
            h = l("com.greensock"),
            u = 1e-10,
            p = function(t) {
                var e, i = [],
                    n = t.length;
                for (e = 0; e !== n; i.push(t[e++]));
                return i
            },
            c = function() {},
            f = function() {
                var t = Object.prototype.toString,
                    e = t.call([]);
                return function(i) {
                    return null != i && (i instanceof Array || "object" == typeof i && !!i.push && t.call(i) === e)
                }
            }(),
            d = {},
            _ = function(n, r, s, a) {
                this.sc = d[n] ? d[n].sc : [], d[n] = this, this.gsClass = null, this.func = s;
                var o = [];
                this.check = function(h) {
                    for (var u, p, c, f, m = r.length, g = m; --m > -1;)(u = d[r[m]] || new _(r[m], [])).gsClass ? (o[m] = u.gsClass, g--) : h && u.sc.push(this);
                    if (0 === g && s)
                        for (p = ("com.greensock." + n).split("."), c = p.pop(), f = l(p.join("."))[c] = this.gsClass = s.apply(s, o), a && (i[c] = f, "function" == typeof define && define.amd ? define((t.GreenSockAMDPath ? t.GreenSockAMDPath + "/" : "") + n.split(".").pop(), [], function() {
                                return f
                            }) : n === e && "undefined" != typeof module && module.exports && (module.exports = f)), m = 0; this.sc.length > m; m++) this.sc[m].check()
                }, this.check(!0)
            },
            m = t._gsDefine = function(t, e, i, n) {
                return new _(t, e, i, n)
            },
            g = h._class = function(t, e, i) {
                return e = e || function() {}, m(t, [], function() {
                    return e
                }, i), e
            };
        m.globals = i;
        var v = [0, 0, 1, 1],
            y = [],
            x = g("easing.Ease", function(t, e, i, n) {
                this._func = t, this._type = i || 0, this._power = n || 0, this._params = e ? v.concat(e) : v
            }, !0),
            w = x.map = {},
            T = x.register = function(t, e, i, n) {
                for (var r, s, a, o, l = e.split(","), u = l.length, p = (i || "easeIn,easeOut,easeInOut").split(","); --u > -1;)
                    for (s = l[u], r = n ? g("easing." + s, null, !0) : h.easing[s] || {}, a = p.length; --a > -1;) o = p[a], w[s + "." + o] = w[o + s] = r[o] = t.getRatio ? t : t[o] || new t
            };
        for (s = x.prototype, s._calcEnd = !1, s.getRatio = function(t) {
                if (this._func) return this._params[0] = t, this._func.apply(null, this._params);
                var e = this._type,
                    i = this._power,
                    n = 1 === e ? 1 - t : 2 === e ? t : .5 > t ? 2 * t : 2 * (1 - t);
                return 1 === i ? n *= n : 2 === i ? n *= n * n : 3 === i ? n *= n * n * n : 4 === i && (n *= n * n * n * n), 1 === e ? 1 - n : 2 === e ? n : .5 > t ? n / 2 : 1 - n / 2
            }, n = ["Linear", "Quad", "Cubic", "Quart", "Quint,Strong"], r = n.length; --r > -1;) s = n[r] + ",Power" + r, T(new x(null, null, 1, r), s, "easeOut", !0), T(new x(null, null, 2, r), s, "easeIn" + (0 === r ? ",easeNone" : "")), T(new x(null, null, 3, r), s, "easeInOut");
        w.linear = h.easing.Linear.easeIn, w.swing = h.easing.Quad.easeInOut;
        var b = g("events.EventDispatcher", function(t) {
            this._listeners = {}, this._eventTarget = t || this
        });
        s = b.prototype, s.addEventListener = function(t, e, i, n, r) {
            r = r || 0;
            var s, l, h = this._listeners[t],
                u = 0;
            for (null == h && (this._listeners[t] = h = []), l = h.length; --l > -1;) s = h[l], s.c === e && s.s === i ? h.splice(l, 1) : 0 === u && r > s.pr && (u = l + 1);
            h.splice(u, 0, {
                c: e,
                s: i,
                up: n,
                pr: r
            }), this !== a || o || a.wake()
        }, s.removeEventListener = function(t, e) {
            var i, n = this._listeners[t];
            if (n)
                for (i = n.length; --i > -1;)
                    if (n[i].c === e) return void n.splice(i, 1)
        }, s.dispatchEvent = function(t) {
            var e, i, n, r = this._listeners[t];
            if (r)
                for (e = r.length, i = this._eventTarget; --e > -1;) n = r[e], n && (n.up ? n.c.call(n.s || i, {
                    type: t,
                    target: i
                }) : n.c.call(n.s || i))
        };
        var P = t.requestAnimationFrame,
            S = t.cancelAnimationFrame,
            O = Date.now || function() {
                return (new Date).getTime()
            },
            k = O();
        for (n = ["ms", "moz", "webkit", "o"], r = n.length; --r > -1 && !P;) P = t[n[r] + "RequestAnimationFrame"], S = t[n[r] + "CancelAnimationFrame"] || t[n[r] + "CancelRequestAnimationFrame"];
        g("Ticker", function(t, e) {
            var i, n, r, s, l, h = this,
                p = O(),
                f = e !== !1 && P,
                d = 500,
                _ = 33,
                m = function(t) {
                    var e, a, o = O() - k;
                    o > d && (p += o - _), k += o, h.time = (k - p) / 1e3, e = h.time - l, (!i || e > 0 || t === !0) && (h.frame++, l += e + (e >= s ? .004 : s - e), a = !0), t !== !0 && (r = n(m)), a && h.dispatchEvent("tick")
                };
            b.call(h), h.time = h.frame = 0, h.tick = function() {
                m(!0)
            }, h.lagSmoothing = function(t, e) {
                d = t || 1 / u, _ = Math.min(e, d, 0)
            }, h.sleep = function() {
                null != r && (f && S ? S(r) : clearTimeout(r), n = c, r = null, h === a && (o = !1))
            }, h.wake = function() {
                null !== r ? h.sleep() : h.frame > 10 && (k = O() - d + 5), n = 0 === i ? c : f && P ? P : function(t) {
                    return setTimeout(t, 0 | 1e3 * (l - h.time) + 1)
                }, h === a && (o = !0), m(2)
            }, h.fps = function(t) {
                return arguments.length ? (i = t, s = 1 / (i || 60), l = this.time + s, void h.wake()) : i
            }, h.useRAF = function(t) {
                return arguments.length ? (h.sleep(), f = t, void h.fps(i)) : f
            }, h.fps(t), setTimeout(function() {
                f && (!r || 5 > h.frame) && h.useRAF(!1)
            }, 1500)
        }), s = h.Ticker.prototype = new h.events.EventDispatcher, s.constructor = h.Ticker;
        var C = g("core.Animation", function(t, e) {
            if (this.vars = e = e || {}, this._duration = this._totalDuration = t || 0, this._delay = Number(e.delay) || 0, this._timeScale = 1, this._active = e.immediateRender === !0, this.data = e.data, this._reversed = e.reversed === !0, G) {
                o || a.wake();
                var i = this.vars.useFrames ? B : G;
                i.add(this, i._time), this.vars.paused && this.paused(!0)
            }
        });
        a = C.ticker = new h.Ticker, s = C.prototype, s._dirty = s._gc = s._initted = s._paused = !1, s._totalTime = s._time = 0, s._rawPrevTime = -1, s._next = s._last = s._onUpdate = s._timeline = s.timeline = null, s._paused = !1;
        var A = function() {
            o && O() - k > 2e3 && a.wake(), setTimeout(A, 2e3)
        };
        A(), s.play = function(t, e) {
            return null != t && this.seek(t, e), this.reversed(!1).paused(!1)
        }, s.pause = function(t, e) {
            return null != t && this.seek(t, e), this.paused(!0)
        }, s.resume = function(t, e) {
            return null != t && this.seek(t, e), this.paused(!1)
        }, s.seek = function(t, e) {
            return this.totalTime(Number(t), e !== !1)
        }, s.restart = function(t, e) {
            return this.reversed(!1).paused(!1).totalTime(t ? -this._delay : 0, e !== !1, !0)
        }, s.reverse = function(t, e) {
            return null != t && this.seek(t || this.totalDuration(), e), this.reversed(!0).paused(!1)
        }, s.render = function() {}, s.invalidate = function() {
            return this._time = this._totalTime = 0, this._initted = this._gc = !1, this._rawPrevTime = -1, (this._gc || !this.timeline) && this._enabled(!0), this
        }, s.isActive = function() {
            var t, e = this._timeline,
                i = this._startTime;
            return !e || !this._gc && !this._paused && e.isActive() && (t = e.rawTime()) >= i && i + this.totalDuration() / this._timeScale > t
        }, s._enabled = function(t, e) {
            return o || a.wake(), this._gc = !t, this._active = this.isActive(), e !== !0 && (t && !this.timeline ? this._timeline.add(this, this._startTime - this._delay) : !t && this.timeline && this._timeline._remove(this, !0)), !1
        }, s._kill = function() {
            return this._enabled(!1, !1)
        }, s.kill = function(t, e) {
            return this._kill(t, e), this
        }, s._uncache = function(t) {
            for (var e = t ? this : this.timeline; e;) e._dirty = !0, e = e.timeline;
            return this
        }, s._swapSelfInParams = function(t) {
            for (var e = t.length, i = t.concat(); --e > -1;) "{self}" === t[e] && (i[e] = this);
            return i
        }, s.eventCallback = function(t, e, i, n) {
            if ("on" === (t || "").substr(0, 2)) {
                var r = this.vars;
                if (1 === arguments.length) return r[t];
                null == e ? delete r[t] : (r[t] = e, r[t + "Params"] = f(i) && -1 !== i.join("").indexOf("{self}") ? this._swapSelfInParams(i) : i, r[t + "Scope"] = n), "onUpdate" === t && (this._onUpdate = e)
            }
            return this
        }, s.delay = function(t) {
            return arguments.length ? (this._timeline.smoothChildTiming && this.startTime(this._startTime + t - this._delay), this._delay = t, this) : this._delay
        }, s.duration = function(t) {
            return arguments.length ? (this._duration = this._totalDuration = t, this._uncache(!0), this._timeline.smoothChildTiming && this._time > 0 && this._time < this._duration && 0 !== t && this.totalTime(this._totalTime * (t / this._duration), !0), this) : (this._dirty = !1, this._duration)
        }, s.totalDuration = function(t) {
            return this._dirty = !1, arguments.length ? this.duration(t) : this._totalDuration
        }, s.time = function(t, e) {
            return arguments.length ? (this._dirty && this.totalDuration(), this.totalTime(t > this._duration ? this._duration : t, e)) : this._time
        }, s.totalTime = function(t, e, i) {
            if (o || a.wake(), !arguments.length) return this._totalTime;
            if (this._timeline) {
                if (0 > t && !i && (t += this.totalDuration()), this._timeline.smoothChildTiming) {
                    this._dirty && this.totalDuration();
                    var n = this._totalDuration,
                        r = this._timeline;
                    if (t > n && !i && (t = n), this._startTime = (this._paused ? this._pauseTime : r._time) - (this._reversed ? n - t : t) / this._timeScale, r._dirty || this._uncache(!1), r._timeline)
                        for (; r._timeline;) r._timeline._time !== (r._startTime + r._totalTime) / r._timeScale && r.totalTime(r._totalTime, !0), r = r._timeline
                }
                this._gc && this._enabled(!0, !1), (this._totalTime !== t || 0 === this._duration) && (this.render(t, e, !1), D.length && U())
            }
            return this
        }, s.progress = s.totalProgress = function(t, e) {
            return arguments.length ? this.totalTime(this.duration() * t, e) : this._time / this.duration()
        }, s.startTime = function(t) {
            return arguments.length ? (t !== this._startTime && (this._startTime = t, this.timeline && this.timeline._sortChildren && this.timeline.add(this, t - this._delay)), this) : this._startTime
        }, s.endTime = function(t) {
            return this._startTime + (0 != t ? this.totalDuration() : this.duration()) / this._timeScale
        }, s.timeScale = function(t) {
            if (!arguments.length) return this._timeScale;
            if (t = t || u, this._timeline && this._timeline.smoothChildTiming) {
                var e = this._pauseTime,
                    i = e || 0 === e ? e : this._timeline.totalTime();
                this._startTime = i - (i - this._startTime) * this._timeScale / t
            }
            return this._timeScale = t, this._uncache(!1)
        }, s.reversed = function(t) {
            return arguments.length ? (t != this._reversed && (this._reversed = t, this.totalTime(this._timeline && !this._timeline.smoothChildTiming ? this.totalDuration() - this._totalTime : this._totalTime, !0)), this) : this._reversed
        }, s.paused = function(t) {
            if (!arguments.length) return this._paused;
            if (t != this._paused && this._timeline) {
                o || t || a.wake();
                var e = this._timeline,
                    i = e.rawTime(),
                    n = i - this._pauseTime;
                !t && e.smoothChildTiming && (this._startTime += n, this._uncache(!1)), this._pauseTime = t ? i : null, this._paused = t, this._active = this.isActive(), !t && 0 !== n && this._initted && this.duration() && this.render(e.smoothChildTiming ? this._totalTime : (i - this._startTime) / this._timeScale, !0, !0)
            }
            return this._gc && !t && this._enabled(!0, !1), this
        };
        var R = g("core.SimpleTimeline", function(t) {
            C.call(this, 0, t), this.autoRemoveChildren = this.smoothChildTiming = !0
        });
        s = R.prototype = new C, s.constructor = R, s.kill()._gc = !1, s._first = s._last = s._recent = null, s._sortChildren = !1, s.add = s.insert = function(t, e) {
            var i, n;
            if (t._startTime = Number(e || 0) + t._delay, t._paused && this !== t._timeline && (t._pauseTime = t._startTime + (this.rawTime() - t._startTime) / t._timeScale), t.timeline && t.timeline._remove(t, !0), t.timeline = t._timeline = this, t._gc && t._enabled(!0, !0), i = this._last, this._sortChildren)
                for (n = t._startTime; i && i._startTime > n;) i = i._prev;
            return i ? (t._next = i._next, i._next = t) : (t._next = this._first, this._first = t), t._next ? t._next._prev = t : this._last = t, t._prev = i, this._recent = t, this._timeline && this._uncache(!0), this
        }, s._remove = function(t, e) {
            return t.timeline === this && (e || t._enabled(!1, !0), t._prev ? t._prev._next = t._next : this._first === t && (this._first = t._next), t._next ? t._next._prev = t._prev : this._last === t && (this._last = t._prev), t._next = t._prev = t.timeline = null, t === this._recent && (this._recent = this._last), this._timeline && this._uncache(!0)), this
        }, s.render = function(t, e, i) {
            var n, r = this._first;
            for (this._totalTime = this._time = this._rawPrevTime = t; r;) n = r._next, (r._active || t >= r._startTime && !r._paused) && (r._reversed ? r.render((r._dirty ? r.totalDuration() : r._totalDuration) - (t - r._startTime) * r._timeScale, e, i) : r.render((t - r._startTime) * r._timeScale, e, i)), r = n
        }, s.rawTime = function() {
            return o || a.wake(), this._totalTime
        };
        var M = g("TweenLite", function(e, i, n) {
                if (C.call(this, i, n), this.render = M.prototype.render, null == e) throw "Cannot tween a null target.";
                this.target = e = "string" != typeof e ? e : M.selector(e) || e;
                var r, s, a, o = e.jquery || e.length && e !== t && e[0] && (e[0] === t || e[0].nodeType && e[0].style && !e.nodeType),
                    l = this.vars.overwrite;
                if (this._overwrite = l = null == l ? j[M.defaultOverwrite] : "number" == typeof l ? l >> 0 : j[l], (o || e instanceof Array || e.push && f(e)) && "number" != typeof e[0])
                    for (this._targets = a = p(e), this._propLookup = [], this._siblings = [], r = 0; a.length > r; r++) s = a[r], s ? "string" != typeof s ? s.length && s !== t && s[0] && (s[0] === t || s[0].nodeType && s[0].style && !s.nodeType) ? (a.splice(r--, 1), this._targets = a = a.concat(p(s))) : (this._siblings[r] = V(s, this, !1), 1 === l && this._siblings[r].length > 1 && H(s, this, null, 1, this._siblings[r])) : (s = a[r--] = M.selector(s), "string" == typeof s && a.splice(r + 1, 1)) : a.splice(r--, 1);
                else this._propLookup = {}, this._siblings = V(e, this, !1), 1 === l && this._siblings.length > 1 && H(e, this, null, 1, this._siblings);
                (this.vars.immediateRender || 0 === i && 0 === this._delay && this.vars.immediateRender !== !1) && (this._time = -u, this.render(-this._delay))
            }, !0),
            E = function(e) {
                return e && e.length && e !== t && e[0] && (e[0] === t || e[0].nodeType && e[0].style && !e.nodeType)
            },
            L = function(t, e) {
                var i, n = {};
                for (i in t) Y[i] || i in e && "transform" !== i && "x" !== i && "y" !== i && "width" !== i && "height" !== i && "className" !== i && "border" !== i || !(!N[i] || N[i] && N[i]._autoCSS) || (n[i] = t[i], delete t[i]);
                t.css = n
            };
        s = M.prototype = new C, s.constructor = M, s.kill()._gc = !1, s.ratio = 0, s._firstPT = s._targets = s._overwrittenProps = s._startAt = null, s._notifyPluginsOfEnabled = s._lazy = !1, M.version = "1.14.2", M.defaultEase = s._ease = new x(null, null, 1, 1), M.defaultOverwrite = "auto", M.ticker = a, M.autoSleep = !0, M.lagSmoothing = function(t, e) {
            a.lagSmoothing(t, e)
        }, M.selector = t.$ || t.jQuery || function(e) {
            var i = t.$ || t.jQuery;
            return i ? (M.selector = i, i(e)) : "undefined" == typeof document ? e : document.querySelectorAll ? document.querySelectorAll(e) : document.getElementById("#" === e.charAt(0) ? e.substr(1) : e)
        };
        var D = [],
            I = {},
            X = M._internals = {
                isArray: f,
                isSelector: E,
                lazyTweens: D
            },
            N = M._plugins = {},
            z = X.tweenLookup = {},
            F = 0,
            Y = X.reservedProps = {
                ease: 1,
                delay: 1,
                overwrite: 1,
                onComplete: 1,
                onCompleteParams: 1,
                onCompleteScope: 1,
                useFrames: 1,
                runBackwards: 1,
                startAt: 1,
                onUpdate: 1,
                onUpdateParams: 1,
                onUpdateScope: 1,
                onStart: 1,
                onStartParams: 1,
                onStartScope: 1,
                onReverseComplete: 1,
                onReverseCompleteParams: 1,
                onReverseCompleteScope: 1,
                onRepeat: 1,
                onRepeatParams: 1,
                onRepeatScope: 1,
                easeParams: 1,
                yoyo: 1,
                immediateRender: 1,
                repeat: 1,
                repeatDelay: 1,
                data: 1,
                paused: 1,
                reversed: 1,
                autoCSS: 1,
                lazy: 1,
                onOverwrite: 1
            },
            j = {
                none: 0,
                all: 1,
                auto: 2,
                concurrent: 3,
                allOnStart: 4,
                preexisting: 5,
                "true": 1,
                "false": 0
            },
            B = C._rootFramesTimeline = new R,
            G = C._rootTimeline = new R,
            U = X.lazyRender = function() {
                var t, e = D.length;
                for (I = {}; --e > -1;) t = D[e], t && t._lazy !== !1 && (t.render(t._lazy[0], t._lazy[1], !0), t._lazy = !1);
                D.length = 0
            };
        G._startTime = a.time, B._startTime = a.frame, G._active = B._active = !0, setTimeout(U, 1), C._updateRoot = M.render = function() {
            var t, e, i;
            if (D.length && U(), G.render((a.time - G._startTime) * G._timeScale, !1, !1), B.render((a.frame - B._startTime) * B._timeScale, !1, !1), D.length && U(), !(a.frame % 120)) {
                for (i in z) {
                    for (e = z[i].tweens, t = e.length; --t > -1;) e[t]._gc && e.splice(t, 1);
                    0 === e.length && delete z[i]
                }
                if (i = G._first, (!i || i._paused) && M.autoSleep && !B._first && 1 === a._listeners.tick.length) {
                    for (; i && i._paused;) i = i._next;
                    i || a.sleep()
                }
            }
        }, a.addEventListener("tick", C._updateRoot);
        var V = function(t, e, i) {
                var n, r, s = t._gsTweenID;
                if (z[s || (t._gsTweenID = s = "t" + F++)] || (z[s] = {
                        target: t,
                        tweens: []
                    }), e && (n = z[s].tweens, n[r = n.length] = e, i))
                    for (; --r > -1;) n[r] === e && n.splice(r, 1);
                return z[s].tweens
            },
            W = function(t, e, i, n) {
                var r, s, a = t.vars.onOverwrite;
                return a && (r = a(t, e, i, n)), a = M.onOverwrite, a && (s = a(t, e, i, n)), r !== !1 && s !== !1
            },
            H = function(t, e, i, n, r) {
                var s, a, o, l;
                if (1 === n || n >= 4) {
                    for (l = r.length, s = 0; l > s; s++)
                        if ((o = r[s]) !== e) o._gc || W(o, e) && o._enabled(!1, !1) && (a = !0);
                        else if (5 === n) break;
                    return a
                }
                var h, p = e._startTime + u,
                    c = [],
                    f = 0,
                    d = 0 === e._duration;
                for (s = r.length; --s > -1;)(o = r[s]) === e || o._gc || o._paused || (o._timeline !== e._timeline ? (h = h || Q(e, 0, d), 0 === Q(o, h, d) && (c[f++] = o)) : p >= o._startTime && o._startTime + o.totalDuration() / o._timeScale > p && ((d || !o._initted) && 2e-10 >= p - o._startTime || (c[f++] = o)));
                for (s = f; --s > -1;)
                    if (o = c[s], 2 === n && o._kill(i, t, e) && (a = !0), 2 !== n || !o._firstPT && o._initted) {
                        if (2 !== n && !W(o, e)) continue;
                        o._enabled(!1, !1) && (a = !0)
                    }
                return a
            },
            Q = function(t, e, i) {
                for (var n = t._timeline, r = n._timeScale, s = t._startTime; n._timeline;) {
                    if (s += n._startTime, r *= n._timeScale, n._paused) return -100;
                    n = n._timeline
                }
                return s /= r, s > e ? s - e : i && s === e || !t._initted && 2 * u > s - e ? u : (s += t.totalDuration() / t._timeScale / r) > e + u ? 0 : s - e - u
            };
        s._init = function() {
            var t, e, i, n, r, s = this.vars,
                a = this._overwrittenProps,
                o = this._duration,
                l = !!s.immediateRender,
                h = s.ease;
            if (s.startAt) {
                this._startAt && (this._startAt.render(-1, !0), this._startAt.kill()), r = {};
                for (n in s.startAt) r[n] = s.startAt[n];
                if (r.overwrite = !1, r.immediateRender = !0, r.lazy = l && s.lazy !== !1, r.startAt = r.delay = null, this._startAt = M.to(this.target, 0, r), l)
                    if (this._time > 0) this._startAt = null;
                    else if (0 !== o) return
            } else if (s.runBackwards && 0 !== o)
                if (this._startAt) this._startAt.render(-1, !0), this._startAt.kill(), this._startAt = null;
                else {
                    0 !== this._time && (l = !1), i = {};
                    for (n in s) Y[n] && "autoCSS" !== n || (i[n] = s[n]);
                    if (i.overwrite = 0, i.data = "isFromStart", i.lazy = l && s.lazy !== !1, i.immediateRender = l, this._startAt = M.to(this.target, 0, i), l) {
                        if (0 === this._time) return
                    } else this._startAt._init(), this._startAt._enabled(!1), this.vars.immediateRender && (this._startAt = null)
                }
            if (this._ease = h = h ? h instanceof x ? h : "function" == typeof h ? new x(h, s.easeParams) : w[h] || M.defaultEase : M.defaultEase, s.easeParams instanceof Array && h.config && (this._ease = h.config.apply(h, s.easeParams)), this._easeType = this._ease._type, this._easePower = this._ease._power, this._firstPT = null, this._targets)
                for (t = this._targets.length; --t > -1;) this._initProps(this._targets[t], this._propLookup[t] = {}, this._siblings[t], a ? a[t] : null) && (e = !0);
            else e = this._initProps(this.target, this._propLookup, this._siblings, a);
            if (e && M._onPluginEvent("_onInitAllProps", this), a && (this._firstPT || "function" != typeof this.target && this._enabled(!1, !1)), s.runBackwards)
                for (i = this._firstPT; i;) i.s += i.c, i.c = -i.c, i = i._next;
            this._onUpdate = s.onUpdate, this._initted = !0
        }, s._initProps = function(e, i, n, r) {
            var s, a, o, l, h, u;
            if (null == e) return !1;
            I[e._gsTweenID] && U(), this.vars.css || e.style && e !== t && e.nodeType && N.css && this.vars.autoCSS !== !1 && L(this.vars, e);
            for (s in this.vars) {
                if (u = this.vars[s], Y[s]) u && (u instanceof Array || u.push && f(u)) && -1 !== u.join("").indexOf("{self}") && (this.vars[s] = u = this._swapSelfInParams(u, this));
                else if (N[s] && (l = new N[s])._onInitTween(e, this.vars[s], this)) {
                    for (this._firstPT = h = {
                            _next: this._firstPT,
                            t: l,
                            p: "setRatio",
                            s: 0,
                            c: 1,
                            f: !0,
                            n: s,
                            pg: !0,
                            pr: l._priority
                        }, a = l._overwriteProps.length; --a > -1;) i[l._overwriteProps[a]] = this._firstPT;
                    (l._priority || l._onInitAllProps) && (o = !0), (l._onDisable || l._onEnable) && (this._notifyPluginsOfEnabled = !0)
                } else this._firstPT = i[s] = h = {
                    _next: this._firstPT,
                    t: e,
                    p: s,
                    f: "function" == typeof e[s],
                    n: s,
                    pg: !1,
                    pr: 0
                }, h.s = h.f ? e[s.indexOf("set") || "function" != typeof e["get" + s.substr(3)] ? s : "get" + s.substr(3)]() : parseFloat(e[s]), h.c = "string" == typeof u && "=" === u.charAt(1) ? parseInt(u.charAt(0) + "1", 10) * Number(u.substr(2)) : Number(u) - h.s || 0;
                h && h._next && (h._next._prev = h)
            }
            return r && this._kill(r, e) ? this._initProps(e, i, n, r) : this._overwrite > 1 && this._firstPT && n.length > 1 && H(e, this, i, this._overwrite, n) ? (this._kill(i, e), this._initProps(e, i, n, r)) : (this._firstPT && (this.vars.lazy !== !1 && this._duration || this.vars.lazy && !this._duration) && (I[e._gsTweenID] = !0), o)
        }, s.render = function(t, e, i) {
            var n, r, s, a, o = this._time,
                l = this._duration,
                h = this._rawPrevTime;
            if (t >= l) this._totalTime = this._time = l, this.ratio = this._ease._calcEnd ? this._ease.getRatio(1) : 1, this._reversed || (n = !0, r = "onComplete"), 0 === l && (this._initted || !this.vars.lazy || i) && (this._startTime === this._timeline._duration && (t = 0), (0 === t || 0 > h || h === u) && h !== t && (i = !0, h > u && (r = "onReverseComplete")), this._rawPrevTime = a = !e || t || h === t ? t : u);
            else if (1e-7 > t) this._totalTime = this._time = 0, this.ratio = this._ease._calcEnd ? this._ease.getRatio(0) : 0, (0 !== o || 0 === l && h > 0 && h !== u) && (r = "onReverseComplete", n = this._reversed), 0 > t && (this._active = !1, 0 === l && (this._initted || !this.vars.lazy || i) && (h >= 0 && (i = !0), this._rawPrevTime = a = !e || t || h === t ? t : u)), this._initted || (i = !0);
            else if (this._totalTime = this._time = t, this._easeType) {
                var p = t / l,
                    c = this._easeType,
                    f = this._easePower;
                (1 === c || 3 === c && p >= .5) && (p = 1 - p), 3 === c && (p *= 2), 1 === f ? p *= p : 2 === f ? p *= p * p : 3 === f ? p *= p * p * p : 4 === f && (p *= p * p * p * p), this.ratio = 1 === c ? 1 - p : 2 === c ? p : .5 > t / l ? p / 2 : 1 - p / 2
            } else this.ratio = this._ease.getRatio(t / l);
            if (this._time !== o || i) {
                if (!this._initted) {
                    if (this._init(), !this._initted || this._gc) return;
                    if (!i && this._firstPT && (this.vars.lazy !== !1 && this._duration || this.vars.lazy && !this._duration)) return this._time = this._totalTime = o, this._rawPrevTime = h, D.push(this), void(this._lazy = [t, e]);
                    this._time && !n ? this.ratio = this._ease.getRatio(this._time / l) : n && this._ease._calcEnd && (this.ratio = this._ease.getRatio(0 === this._time ? 0 : 1))
                }
                for (this._lazy !== !1 && (this._lazy = !1), this._active || !this._paused && this._time !== o && t >= 0 && (this._active = !0), 0 === o && (this._startAt && (t >= 0 ? this._startAt.render(t, e, i) : r || (r = "_dummyGS")), this.vars.onStart && (0 !== this._time || 0 === l) && (e || this.vars.onStart.apply(this.vars.onStartScope || this, this.vars.onStartParams || y))), s = this._firstPT; s;) s.f ? s.t[s.p](s.c * this.ratio + s.s) : s.t[s.p] = s.c * this.ratio + s.s, s = s._next;
                this._onUpdate && (0 > t && this._startAt && t !== -1e-4 && this._startAt.render(t, e, i), e || (this._time !== o || n) && this._onUpdate.apply(this.vars.onUpdateScope || this, this.vars.onUpdateParams || y)), r && (!this._gc || i) && (0 > t && this._startAt && !this._onUpdate && t !== -1e-4 && this._startAt.render(t, e, i), n && (this._timeline.autoRemoveChildren && this._enabled(!1, !1), this._active = !1), !e && this.vars[r] && this.vars[r].apply(this.vars[r + "Scope"] || this, this.vars[r + "Params"] || y), 0 === l && this._rawPrevTime === u && a !== u && (this._rawPrevTime = 0))
            }
        }, s._kill = function(t, e, i) {
            if ("all" === t && (t = null), null == t && (null == e || e === this.target)) return this._lazy = !1, this._enabled(!1, !1);
            e = "string" != typeof e ? e || this._targets || this.target : M.selector(e) || e;
            var n, r, s, a, o, l, h, u, p;
            if ((f(e) || E(e)) && "number" != typeof e[0])
                for (n = e.length; --n > -1;) this._kill(t, e[n]) && (l = !0);
            else {
                if (this._targets) {
                    for (n = this._targets.length; --n > -1;)
                        if (e === this._targets[n]) {
                            o = this._propLookup[n] || {}, this._overwrittenProps = this._overwrittenProps || [], r = this._overwrittenProps[n] = t ? this._overwrittenProps[n] || {} : "all";
                            break
                        }
                } else {
                    if (e !== this.target) return !1;
                    o = this._propLookup, r = this._overwrittenProps = t ? this._overwrittenProps || {} : "all"
                }
                if (o) {
                    if (h = t || o, u = t !== r && "all" !== r && t !== o && ("object" != typeof t || !t._tempKill), i && (M.onOverwrite || this.vars.onOverwrite)) {
                        for (s in h) o[s] && (p || (p = []), p.push(s));
                        if (!W(this, i, e, p)) return !1
                    }
                    for (s in h)(a = o[s]) && (a.pg && a.t._kill(h) && (l = !0), a.pg && 0 !== a.t._overwriteProps.length || (a._prev ? a._prev._next = a._next : a === this._firstPT && (this._firstPT = a._next), a._next && (a._next._prev = a._prev), a._next = a._prev = null), delete o[s]), u && (r[s] = 1);
                    !this._firstPT && this._initted && this._enabled(!1, !1)
                }
            }
            return l
        }, s.invalidate = function() {
            return this._notifyPluginsOfEnabled && M._onPluginEvent("_onDisable", this), this._firstPT = this._overwrittenProps = this._startAt = this._onUpdate = null, this._notifyPluginsOfEnabled = this._active = this._lazy = !1, this._propLookup = this._targets ? {} : [], C.prototype.invalidate.call(this), this.vars.immediateRender && (this._time = -u, this.render(-this._delay)), this
        }, s._enabled = function(t, e) {
            if (o || a.wake(), t && this._gc) {
                var i, n = this._targets;
                if (n)
                    for (i = n.length; --i > -1;) this._siblings[i] = V(n[i], this, !0);
                else this._siblings = V(this.target, this, !0)
            }
            return C.prototype._enabled.call(this, t, e), this._notifyPluginsOfEnabled && this._firstPT ? M._onPluginEvent(t ? "_onEnable" : "_onDisable", this) : !1
        }, M.to = function(t, e, i) {
            return new M(t, e, i)
        }, M.from = function(t, e, i) {
            return i.runBackwards = !0, i.immediateRender = 0 != i.immediateRender, new M(t, e, i)
        }, M.fromTo = function(t, e, i, n) {
            return n.startAt = i, n.immediateRender = 0 != n.immediateRender && 0 != i.immediateRender, new M(t, e, n)
        }, M.delayedCall = function(t, e, i, n, r) {
            return new M(e, 0, {
                delay: t,
                onComplete: e,
                onCompleteParams: i,
                onCompleteScope: n,
                onReverseComplete: e,
                onReverseCompleteParams: i,
                onReverseCompleteScope: n,
                immediateRender: !1,
                useFrames: r,
                overwrite: 0
            })
        }, M.set = function(t, e) {
            return new M(t, 0, e)
        }, M.getTweensOf = function(t, e) {
            if (null == t) return [];
            t = "string" != typeof t ? t : M.selector(t) || t;
            var i, n, r, s;
            if ((f(t) || E(t)) && "number" != typeof t[0]) {
                for (i = t.length, n = []; --i > -1;) n = n.concat(M.getTweensOf(t[i], e));
                for (i = n.length; --i > -1;)
                    for (s = n[i], r = i; --r > -1;) s === n[r] && n.splice(i, 1)

            } else
                for (n = V(t).concat(), i = n.length; --i > -1;)(n[i]._gc || e && !n[i].isActive()) && n.splice(i, 1);
            return n
        }, M.killTweensOf = M.killDelayedCallsTo = function(t, e, i) {
            "object" == typeof e && (i = e, e = !1);
            for (var n = M.getTweensOf(t, e), r = n.length; --r > -1;) n[r]._kill(i, t)
        };
        var q = g("plugins.TweenPlugin", function(t, e) {
            this._overwriteProps = (t || "").split(","), this._propName = this._overwriteProps[0], this._priority = e || 0, this._super = q.prototype
        }, !0);
        if (s = q.prototype, q.version = "1.10.1", q.API = 2, s._firstPT = null, s._addTween = function(t, e, i, n, r, s) {
                var a, o;
                return null != n && (a = "number" == typeof n || "=" !== n.charAt(1) ? Number(n) - i : parseInt(n.charAt(0) + "1", 10) * Number(n.substr(2))) ? (this._firstPT = o = {
                    _next: this._firstPT,
                    t: t,
                    p: e,
                    s: i,
                    c: a,
                    f: "function" == typeof t[e],
                    n: r || e,
                    r: s
                }, o._next && (o._next._prev = o), o) : void 0
            }, s.setRatio = function(t) {
                for (var e, i = this._firstPT, n = 1e-6; i;) e = i.c * t + i.s, i.r ? e = Math.round(e) : n > e && e > -n && (e = 0), i.f ? i.t[i.p](e) : i.t[i.p] = e, i = i._next
            }, s._kill = function(t) {
                var e, i = this._overwriteProps,
                    n = this._firstPT;
                if (null != t[this._propName]) this._overwriteProps = [];
                else
                    for (e = i.length; --e > -1;) null != t[i[e]] && i.splice(e, 1);
                for (; n;) null != t[n.n] && (n._next && (n._next._prev = n._prev), n._prev ? (n._prev._next = n._next, n._prev = null) : this._firstPT === n && (this._firstPT = n._next)), n = n._next;
                return !1
            }, s._roundProps = function(t, e) {
                for (var i = this._firstPT; i;)(t[this._propName] || null != i.n && t[i.n.split(this._propName + "_").join("")]) && (i.r = e), i = i._next
            }, M._onPluginEvent = function(t, e) {
                var i, n, r, s, a, o = e._firstPT;
                if ("_onInitAllProps" === t) {
                    for (; o;) {
                        for (a = o._next, n = r; n && n.pr > o.pr;) n = n._next;
                        (o._prev = n ? n._prev : s) ? o._prev._next = o: r = o, (o._next = n) ? n._prev = o : s = o, o = a
                    }
                    o = e._firstPT = r
                }
                for (; o;) o.pg && "function" == typeof o.t[t] && o.t[t]() && (i = !0), o = o._next;
                return i
            }, q.activate = function(t) {
                for (var e = t.length; --e > -1;) t[e].API === q.API && (N[(new t[e])._propName] = t[e]);
                return !0
            }, m.plugin = function(t) {
                if (!(t && t.propName && t.init && t.API)) throw "illegal plugin definition.";
                var e, i = t.propName,
                    n = t.priority || 0,
                    r = t.overwriteProps,
                    s = {
                        init: "_onInitTween",
                        set: "setRatio",
                        kill: "_kill",
                        round: "_roundProps",
                        initAll: "_onInitAllProps"
                    },
                    a = g("plugins." + i.charAt(0).toUpperCase() + i.substr(1) + "Plugin", function() {
                        q.call(this, i, n), this._overwriteProps = r || []
                    }, t.global === !0),
                    o = a.prototype = new q(i);
                o.constructor = a, a.API = t.API;
                for (e in s) "function" == typeof t[e] && (o[s[e]] = t[e]);
                return a.version = t.version, q.activate([a]), a
            }, n = t._gsQueue) {
            for (r = 0; n.length > r; r++) n[r]();
            for (s in d) d[s].func || t.console.log("GSAP encountered missing dependency: com.greensock." + s)
        }
        o = !1
    }
}("undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window, "TweenLite");
var _gsScope = "undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window;
(_gsScope._gsQueue || (_gsScope._gsQueue = [])).push(function() {
        "use strict";
        _gsScope._gsDefine("TimelineLite", ["core.Animation", "core.SimpleTimeline", "TweenLite"], function(t, e, i) {
            var n = function(t) {
                    e.call(this, t), this._labels = {}, this.autoRemoveChildren = this.vars.autoRemoveChildren === !0, this.smoothChildTiming = this.vars.smoothChildTiming === !0, this._sortChildren = !0, this._onUpdate = this.vars.onUpdate;
                    var i, n, r = this.vars;
                    for (n in r) i = r[n], o(i) && -1 !== i.join("").indexOf("{self}") && (r[n] = this._swapSelfInParams(i));
                    o(r.tweens) && this.add(r.tweens, 0, r.align, r.stagger)
                },
                r = 1e-10,
                s = i._internals,
                a = s.isSelector,
                o = s.isArray,
                l = s.lazyTweens,
                h = s.lazyRender,
                u = [],
                p = _gsScope._gsDefine.globals,
                c = function(t) {
                    var e, i = {};
                    for (e in t) i[e] = t[e];
                    return i
                },
                f = function(t, e, i, n) {
                    var r = t._timeline._totalTime;
                    (e || !this._forcingPlayhead) && (t._timeline.pause(t._startTime), e && e.apply(n || t._timeline, i || u), this._forcingPlayhead && t._timeline.seek(r))
                },
                d = function(t) {
                    var e, i = [],
                        n = t.length;
                    for (e = 0; e !== n; i.push(t[e++]));
                    return i
                },
                _ = n.prototype = new e;
            return n.version = "1.14.2", _.constructor = n, _.kill()._gc = _._forcingPlayhead = !1, _.to = function(t, e, n, r) {
                var s = n.repeat && p.TweenMax || i;
                return e ? this.add(new s(t, e, n), r) : this.set(t, n, r)
            }, _.from = function(t, e, n, r) {
                return this.add((n.repeat && p.TweenMax || i).from(t, e, n), r)
            }, _.fromTo = function(t, e, n, r, s) {
                var a = r.repeat && p.TweenMax || i;
                return e ? this.add(a.fromTo(t, e, n, r), s) : this.set(t, r, s)
            }, _.staggerTo = function(t, e, r, s, o, l, h, u) {
                var p, f = new n({
                    onComplete: l,
                    onCompleteParams: h,
                    onCompleteScope: u,
                    smoothChildTiming: this.smoothChildTiming
                });
                for ("string" == typeof t && (t = i.selector(t) || t), t = t || [], a(t) && (t = d(t)), s = s || 0, 0 > s && (t = d(t), t.reverse(), s *= -1), p = 0; t.length > p; p++) r.startAt && (r.startAt = c(r.startAt)), f.to(t[p], e, c(r), p * s);
                return this.add(f, o)
            }, _.staggerFrom = function(t, e, i, n, r, s, a, o) {
                return i.immediateRender = 0 != i.immediateRender, i.runBackwards = !0, this.staggerTo(t, e, i, n, r, s, a, o)
            }, _.staggerFromTo = function(t, e, i, n, r, s, a, o, l) {
                return n.startAt = i, n.immediateRender = 0 != n.immediateRender && 0 != i.immediateRender, this.staggerTo(t, e, n, r, s, a, o, l)
            }, _.call = function(t, e, n, r) {
                return this.add(i.delayedCall(0, t, e, n), r)
            }, _.set = function(t, e, n) {
                return n = this._parseTimeOrLabel(n, 0, !0), null == e.immediateRender && (e.immediateRender = n === this._time && !this._paused), this.add(new i(t, 0, e), n)
            }, n.exportRoot = function(t, e) {
                t = t || {}, null == t.smoothChildTiming && (t.smoothChildTiming = !0);
                var r, s, a = new n(t),
                    o = a._timeline;
                for (null == e && (e = !0), o._remove(a, !0), a._startTime = 0, a._rawPrevTime = a._time = a._totalTime = o._time, r = o._first; r;) s = r._next, e && r instanceof i && r.target === r.vars.onComplete || a.add(r, r._startTime - r._delay), r = s;
                return o.add(a, 0), a
            }, _.add = function(r, s, a, l) {
                var h, u, p, c, f, d;
                if ("number" != typeof s && (s = this._parseTimeOrLabel(s, 0, !0, r)), !(r instanceof t)) {
                    if (r instanceof Array || r && r.push && o(r)) {
                        for (a = a || "normal", l = l || 0, h = s, u = r.length, p = 0; u > p; p++) o(c = r[p]) && (c = new n({
                            tweens: c
                        })), this.add(c, h), "string" != typeof c && "function" != typeof c && ("sequence" === a ? h = c._startTime + c.totalDuration() / c._timeScale : "start" === a && (c._startTime -= c.delay())), h += l;
                        return this._uncache(!0)
                    }
                    if ("string" == typeof r) return this.addLabel(r, s);
                    if ("function" != typeof r) throw "Cannot add " + r + " into the timeline; it is not a tween, timeline, function, or string.";
                    r = i.delayedCall(0, r)
                }
                if (e.prototype.add.call(this, r, s), (this._gc || this._time === this._duration) && !this._paused && this._duration < this.duration())
                    for (f = this, d = f.rawTime() > r._startTime; f._timeline;) d && f._timeline.smoothChildTiming ? f.totalTime(f._totalTime, !0) : f._gc && f._enabled(!0, !1), f = f._timeline;
                return this
            }, _.remove = function(e) {
                if (e instanceof t) return this._remove(e, !1);
                if (e instanceof Array || e && e.push && o(e)) {
                    for (var i = e.length; --i > -1;) this.remove(e[i]);
                    return this
                }
                return "string" == typeof e ? this.removeLabel(e) : this.kill(null, e)
            }, _._remove = function(t, i) {
                e.prototype._remove.call(this, t, i);
                var n = this._last;
                return n ? this._time > n._startTime + n._totalDuration / n._timeScale && (this._time = this.duration(), this._totalTime = this._totalDuration) : this._time = this._totalTime = this._duration = this._totalDuration = 0, this
            }, _.append = function(t, e) {
                return this.add(t, this._parseTimeOrLabel(null, e, !0, t))
            }, _.insert = _.insertMultiple = function(t, e, i, n) {
                return this.add(t, e || 0, i, n)
            }, _.appendMultiple = function(t, e, i, n) {
                return this.add(t, this._parseTimeOrLabel(null, e, !0, t), i, n)
            }, _.addLabel = function(t, e) {
                return this._labels[t] = this._parseTimeOrLabel(e), this
            }, _.addPause = function(t, e, i, n) {
                return this.call(f, ["{self}", e, i, n], this, t)
            }, _.removeLabel = function(t) {
                return delete this._labels[t], this
            }, _.getLabelTime = function(t) {
                return null != this._labels[t] ? this._labels[t] : -1
            }, _._parseTimeOrLabel = function(e, i, n, r) {
                var s;
                if (r instanceof t && r.timeline === this) this.remove(r);
                else if (r && (r instanceof Array || r.push && o(r)))
                    for (s = r.length; --s > -1;) r[s] instanceof t && r[s].timeline === this && this.remove(r[s]);
                if ("string" == typeof i) return this._parseTimeOrLabel(i, n && "number" == typeof e && null == this._labels[i] ? e - this.duration() : 0, n);
                if (i = i || 0, "string" != typeof e || !isNaN(e) && null == this._labels[e]) null == e && (e = this.duration());
                else {
                    if (s = e.indexOf("="), -1 === s) return null == this._labels[e] ? n ? this._labels[e] = this.duration() + i : i : this._labels[e] + i;
                    i = parseInt(e.charAt(s - 1) + "1", 10) * Number(e.substr(s + 1)), e = s > 1 ? this._parseTimeOrLabel(e.substr(0, s - 1), 0, n) : this.duration()
                }
                return Number(e) + i
            }, _.seek = function(t, e) {
                return this.totalTime("number" == typeof t ? t : this._parseTimeOrLabel(t), e !== !1)
            }, _.stop = function() {
                return this.paused(!0)
            }, _.gotoAndPlay = function(t, e) {
                return this.play(t, e)
            }, _.gotoAndStop = function(t, e) {
                return this.pause(t, e)
            }, _.render = function(t, e, i) {
                this._gc && this._enabled(!0, !1);
                var n, s, a, o, p, c = this._dirty ? this.totalDuration() : this._totalDuration,
                    f = this._time,
                    d = this._startTime,
                    _ = this._timeScale,
                    m = this._paused;
                if (t >= c ? (this._totalTime = this._time = c, this._reversed || this._hasPausedChild() || (s = !0, o = "onComplete", 0 === this._duration && (0 === t || 0 > this._rawPrevTime || this._rawPrevTime === r) && this._rawPrevTime !== t && this._first && (p = !0, this._rawPrevTime > r && (o = "onReverseComplete"))), this._rawPrevTime = this._duration || !e || t || this._rawPrevTime === t ? t : r, t = c + 1e-4) : 1e-7 > t ? (this._totalTime = this._time = 0, (0 !== f || 0 === this._duration && this._rawPrevTime !== r && (this._rawPrevTime > 0 || 0 > t && this._rawPrevTime >= 0)) && (o = "onReverseComplete", s = this._reversed), 0 > t ? (this._active = !1, this._rawPrevTime >= 0 && this._first && (p = !0), this._rawPrevTime = t) : (this._rawPrevTime = this._duration || !e || t || this._rawPrevTime === t ? t : r, t = 0, this._initted || (p = !0))) : this._totalTime = this._time = this._rawPrevTime = t, this._time !== f && this._first || i || p) {
                    if (this._initted || (this._initted = !0), this._active || !this._paused && this._time !== f && t > 0 && (this._active = !0), 0 === f && this.vars.onStart && 0 !== this._time && (e || this.vars.onStart.apply(this.vars.onStartScope || this, this.vars.onStartParams || u)), this._time >= f)
                        for (n = this._first; n && (a = n._next, !this._paused || m);)(n._active || n._startTime <= this._time && !n._paused && !n._gc) && (n._reversed ? n.render((n._dirty ? n.totalDuration() : n._totalDuration) - (t - n._startTime) * n._timeScale, e, i) : n.render((t - n._startTime) * n._timeScale, e, i)), n = a;
                    else
                        for (n = this._last; n && (a = n._prev, !this._paused || m);)(n._active || f >= n._startTime && !n._paused && !n._gc) && (n._reversed ? n.render((n._dirty ? n.totalDuration() : n._totalDuration) - (t - n._startTime) * n._timeScale, e, i) : n.render((t - n._startTime) * n._timeScale, e, i)), n = a;
                    this._onUpdate && (e || (l.length && h(), this._onUpdate.apply(this.vars.onUpdateScope || this, this.vars.onUpdateParams || u))), o && (this._gc || (d === this._startTime || _ !== this._timeScale) && (0 === this._time || c >= this.totalDuration()) && (s && (l.length && h(), this._timeline.autoRemoveChildren && this._enabled(!1, !1), this._active = !1), !e && this.vars[o] && this.vars[o].apply(this.vars[o + "Scope"] || this, this.vars[o + "Params"] || u)))
                }
            }, _._hasPausedChild = function() {
                for (var t = this._first; t;) {
                    if (t._paused || t instanceof n && t._hasPausedChild()) return !0;
                    t = t._next
                }
                return !1
            }, _.getChildren = function(t, e, n, r) {
                r = r || -9999999999;
                for (var s = [], a = this._first, o = 0; a;) r > a._startTime || (a instanceof i ? e !== !1 && (s[o++] = a) : (n !== !1 && (s[o++] = a), t !== !1 && (s = s.concat(a.getChildren(!0, e, n)), o = s.length))), a = a._next;
                return s
            }, _.getTweensOf = function(t, e) {
                var n, r, s = this._gc,
                    a = [],
                    o = 0;
                for (s && this._enabled(!0, !0), n = i.getTweensOf(t), r = n.length; --r > -1;)(n[r].timeline === this || e && this._contains(n[r])) && (a[o++] = n[r]);
                return s && this._enabled(!1, !0), a
            }, _.recent = function() {
                return this._recent
            }, _._contains = function(t) {
                for (var e = t.timeline; e;) {
                    if (e === this) return !0;
                    e = e.timeline
                }
                return !1
            }, _.shiftChildren = function(t, e, i) {
                i = i || 0;
                for (var n, r = this._first, s = this._labels; r;) r._startTime >= i && (r._startTime += t), r = r._next;
                if (e)
                    for (n in s) s[n] >= i && (s[n] += t);
                return this._uncache(!0)
            }, _._kill = function(t, e) {
                if (!t && !e) return this._enabled(!1, !1);
                for (var i = e ? this.getTweensOf(e) : this.getChildren(!0, !0, !1), n = i.length, r = !1; --n > -1;) i[n]._kill(t, e) && (r = !0);
                return r
            }, _.clear = function(t) {
                var e = this.getChildren(!1, !0, !0),
                    i = e.length;
                for (this._time = this._totalTime = 0; --i > -1;) e[i]._enabled(!1, !1);
                return t !== !1 && (this._labels = {}), this._uncache(!0)
            }, _.invalidate = function() {
                for (var e = this._first; e;) e.invalidate(), e = e._next;
                return t.prototype.invalidate.call(this)
            }, _._enabled = function(t, i) {
                if (t === this._gc)
                    for (var n = this._first; n;) n._enabled(t, !0), n = n._next;
                return e.prototype._enabled.call(this, t, i)
            }, _.totalTime = function() {
                this._forcingPlayhead = !0;
                var e = t.prototype.totalTime.apply(this, arguments);
                return this._forcingPlayhead = !1, e
            }, _.duration = function(t) {
                return arguments.length ? (0 !== this.duration() && 0 !== t && this.timeScale(this._duration / t), this) : (this._dirty && this.totalDuration(), this._duration)
            }, _.totalDuration = function(t) {
                if (!arguments.length) {
                    if (this._dirty) {
                        for (var e, i, n = 0, r = this._last, s = 999999999999; r;) e = r._prev, r._dirty && r.totalDuration(), r._startTime > s && this._sortChildren && !r._paused ? this.add(r, r._startTime - r._delay) : s = r._startTime, 0 > r._startTime && !r._paused && (n -= r._startTime, this._timeline.smoothChildTiming && (this._startTime += r._startTime / this._timeScale), this.shiftChildren(-r._startTime, !1, -9999999999), s = 0), i = r._startTime + r._totalDuration / r._timeScale, i > n && (n = i), r = e;
                        this._duration = this._totalDuration = n, this._dirty = !1
                    }
                    return this._totalDuration
                }
                return 0 !== this.totalDuration() && 0 !== t && this.timeScale(this._totalDuration / t), this
            }, _.usesFrames = function() {
                for (var e = this._timeline; e._timeline;) e = e._timeline;
                return e === t._rootFramesTimeline
            }, _.rawTime = function() {
                return this._paused ? this._totalTime : (this._timeline.rawTime() - this._startTime) * this._timeScale
            }, n
        }, !0)
    }), _gsScope._gsDefine && _gsScope._gsQueue.pop()(),
    function(t) {
        "use strict";
        var e = function() {
            return (_gsScope.GreenSockGlobals || _gsScope)[t]
        };
        "function" == typeof define && define.amd ? define(["TweenLite"], e) : "undefined" != typeof module && module.exports && (require("./TweenLite.js"), module.exports = e())
    }("TimelineLite");
var _gsScope = "undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window;
(_gsScope._gsQueue || (_gsScope._gsQueue = [])).push(function() {
    "use strict";
    _gsScope._gsDefine("easing.Back", ["easing.Ease"], function(t) {
        var e, i, n, r = _gsScope.GreenSockGlobals || _gsScope,
            s = r.com.greensock,
            a = 2 * Math.PI,
            o = Math.PI / 2,
            l = s._class,
            h = function(e, i) {
                var n = l("easing." + e, function() {}, !0),
                    r = n.prototype = new t;
                return r.constructor = n, r.getRatio = i, n
            },
            u = t.register || function() {},
            p = function(t, e, i, n) {
                var r = l("easing." + t, {
                    easeOut: new e,
                    easeIn: new i,
                    easeInOut: new n
                }, !0);
                return u(r, t), r
            },
            c = function(t, e, i) {
                this.t = t, this.v = e, i && (this.next = i, i.prev = this, this.c = i.v - e, this.gap = i.t - t)
            },
            f = function(e, i) {
                var n = l("easing." + e, function(t) {
                        this._p1 = t || 0 === t ? t : 1.70158, this._p2 = 1.525 * this._p1
                    }, !0),
                    r = n.prototype = new t;
                return r.constructor = n, r.getRatio = i, r.config = function(t) {
                    return new n(t)
                }, n
            },
            d = p("Back", f("BackOut", function(t) {
                return (t -= 1) * t * ((this._p1 + 1) * t + this._p1) + 1
            }), f("BackIn", function(t) {
                return t * t * ((this._p1 + 1) * t - this._p1)
            }), f("BackInOut", function(t) {
                return 1 > (t *= 2) ? .5 * t * t * ((this._p2 + 1) * t - this._p2) : .5 * ((t -= 2) * t * ((this._p2 + 1) * t + this._p2) + 2)
            })),
            _ = l("easing.SlowMo", function(t, e, i) {
                e = e || 0 === e ? e : .7, null == t ? t = .7 : t > 1 && (t = 1), this._p = 1 !== t ? e : 0, this._p1 = (1 - t) / 2, this._p2 = t, this._p3 = this._p1 + this._p2, this._calcEnd = i === !0
            }, !0),
            m = _.prototype = new t;
        return m.constructor = _, m.getRatio = function(t) {
            var e = t + (.5 - t) * this._p;
            return this._p1 > t ? this._calcEnd ? 1 - (t = 1 - t / this._p1) * t : e - (t = 1 - t / this._p1) * t * t * t * e : t > this._p3 ? this._calcEnd ? 1 - (t = (t - this._p3) / this._p1) * t : e + (t - e) * (t = (t - this._p3) / this._p1) * t * t * t : this._calcEnd ? 1 : e
        }, _.ease = new _(.7, .7), m.config = _.config = function(t, e, i) {
            return new _(t, e, i)
        }, e = l("easing.SteppedEase", function(t) {
            t = t || 1, this._p1 = 1 / t, this._p2 = t + 1
        }, !0), m = e.prototype = new t, m.constructor = e, m.getRatio = function(t) {
            return 0 > t ? t = 0 : t >= 1 && (t = .999999999), (this._p2 * t >> 0) * this._p1
        }, m.config = e.config = function(t) {
            return new e(t)
        }, i = l("easing.RoughEase", function(e) {
            e = e || {};
            for (var i, n, r, s, a, o, l = e.taper || "none", h = [], u = 0, p = 0 | (e.points || 20), f = p, d = e.randomize !== !1, _ = e.clamp === !0, m = e.template instanceof t ? e.template : null, g = "number" == typeof e.strength ? .4 * e.strength : .4; --f > -1;) i = d ? Math.random() : 1 / p * f, n = m ? m.getRatio(i) : i, "none" === l ? r = g : "out" === l ? (s = 1 - i, r = s * s * g) : "in" === l ? r = i * i * g : .5 > i ? (s = 2 * i, r = .5 * s * s * g) : (s = 2 * (1 - i), r = .5 * s * s * g), d ? n += Math.random() * r - .5 * r : f % 2 ? n += .5 * r : n -= .5 * r, _ && (n > 1 ? n = 1 : 0 > n && (n = 0)), h[u++] = {
                x: i,
                y: n
            };
            for (h.sort(function(t, e) {
                    return t.x - e.x
                }), o = new c(1, 1, null), f = p; --f > -1;) a = h[f], o = new c(a.x, a.y, o);
            this._prev = new c(0, 0, 0 !== o.t ? o : o.next)
        }, !0), m = i.prototype = new t, m.constructor = i, m.getRatio = function(t) {
            var e = this._prev;
            if (t > e.t) {
                for (; e.next && t >= e.t;) e = e.next;
                e = e.prev
            } else
                for (; e.prev && e.t >= t;) e = e.prev;
            return this._prev = e, e.v + (t - e.t) / e.gap * e.c
        }, m.config = function(t) {
            return new i(t)
        }, i.ease = new i, p("Bounce", h("BounceOut", function(t) {
            return 1 / 2.75 > t ? 7.5625 * t * t : 2 / 2.75 > t ? 7.5625 * (t -= 1.5 / 2.75) * t + .75 : 2.5 / 2.75 > t ? 7.5625 * (t -= 2.25 / 2.75) * t + .9375 : 7.5625 * (t -= 2.625 / 2.75) * t + .984375
        }), h("BounceIn", function(t) {
            return 1 / 2.75 > (t = 1 - t) ? 1 - 7.5625 * t * t : 2 / 2.75 > t ? 1 - (7.5625 * (t -= 1.5 / 2.75) * t + .75) : 2.5 / 2.75 > t ? 1 - (7.5625 * (t -= 2.25 / 2.75) * t + .9375) : 1 - (7.5625 * (t -= 2.625 / 2.75) * t + .984375)
        }), h("BounceInOut", function(t) {
            var e = .5 > t;
            return t = e ? 1 - 2 * t : 2 * t - 1, t = 1 / 2.75 > t ? 7.5625 * t * t : 2 / 2.75 > t ? 7.5625 * (t -= 1.5 / 2.75) * t + .75 : 2.5 / 2.75 > t ? 7.5625 * (t -= 2.25 / 2.75) * t + .9375 : 7.5625 * (t -= 2.625 / 2.75) * t + .984375, e ? .5 * (1 - t) : .5 * t + .5
        })), p("Circ", h("CircOut", function(t) {
            return Math.sqrt(1 - (t -= 1) * t)
        }), h("CircIn", function(t) {
            return -(Math.sqrt(1 - t * t) - 1)
        }), h("CircInOut", function(t) {
            return 1 > (t *= 2) ? -.5 * (Math.sqrt(1 - t * t) - 1) : .5 * (Math.sqrt(1 - (t -= 2) * t) + 1)
        })), n = function(e, i, n) {
            var r = l("easing." + e, function(t, e) {
                    this._p1 = t || 1, this._p2 = e || n, this._p3 = this._p2 / a * (Math.asin(1 / this._p1) || 0)
                }, !0),
                s = r.prototype = new t;
            return s.constructor = r, s.getRatio = i, s.config = function(t, e) {
                return new r(t, e)
            }, r
        }, p("Elastic", n("ElasticOut", function(t) {
            return this._p1 * Math.pow(2, -10 * t) * Math.sin((t - this._p3) * a / this._p2) + 1
        }, .3), n("ElasticIn", function(t) {
            return -(this._p1 * Math.pow(2, 10 * (t -= 1)) * Math.sin((t - this._p3) * a / this._p2))
        }, .3), n("ElasticInOut", function(t) {
            return 1 > (t *= 2) ? -.5 * this._p1 * Math.pow(2, 10 * (t -= 1)) * Math.sin((t - this._p3) * a / this._p2) : .5 * this._p1 * Math.pow(2, -10 * (t -= 1)) * Math.sin((t - this._p3) * a / this._p2) + 1
        }, .45)), p("Expo", h("ExpoOut", function(t) {
            return 1 - Math.pow(2, -10 * t)
        }), h("ExpoIn", function(t) {
            return Math.pow(2, 10 * (t - 1)) - .001
        }), h("ExpoInOut", function(t) {
            return 1 > (t *= 2) ? .5 * Math.pow(2, 10 * (t - 1)) : .5 * (2 - Math.pow(2, -10 * (t - 1)))
        })), p("Sine", h("SineOut", function(t) {
            return Math.sin(t * o)
        }), h("SineIn", function(t) {
            return -Math.cos(t * o) + 1
        }), h("SineInOut", function(t) {
            return -.5 * (Math.cos(Math.PI * t) - 1)
        })), l("easing.EaseLookup", {
            find: function(e) {
                return t.map[e]
            }
        }, !0), u(r.SlowMo, "SlowMo", "ease,"), u(i, "RoughEase", "ease,"), u(e, "SteppedEase", "ease,"), d
    }, !0)
}), _gsScope._gsDefine && _gsScope._gsQueue.pop()();
var _gsScope = "undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window;
(_gsScope._gsQueue || (_gsScope._gsQueue = [])).push(function() {
        "use strict";
        _gsScope._gsDefine("plugins.CSSPlugin", ["plugins.TweenPlugin", "TweenLite"], function(t, e) {
            var i, n, r, s, a = function() {
                    t.call(this, "css"), this._overwriteProps.length = 0, this.setRatio = a.prototype.setRatio
                },
                o = {},
                l = a.prototype = new t("css");
            l.constructor = a, a.version = "1.14.2", a.API = 2, a.defaultTransformPerspective = 0, a.defaultSkewType = "compensated", l = "px", a.suffixMap = {
                top: l,
                right: l,
                bottom: l,
                left: l,
                width: l,
                height: l,
                fontSize: l,
                padding: l,
                margin: l,
                perspective: l,
                lineHeight: ""
            };
            var h, u, p, c, f, d, _ = /(?:\d|\-\d|\.\d|\-\.\d)+/g,
                m = /(?:\d|\-\d|\.\d|\-\.\d|\+=\d|\-=\d|\+=.\d|\-=\.\d)+/g,
                g = /(?:\+=|\-=|\-|\b)[\d\-\.]+[a-zA-Z0-9]*(?:%|\b)/gi,
                v = /(?![+-]?\d*\.?\d+|e[+-]\d+)[^0-9]/g,
                y = /(?:\d|\-|\+|=|#|\.)*/g,
                x = /opacity *= *([^)]*)/i,
                w = /opacity:([^;]*)/i,
                T = /alpha\(opacity *=.+?\)/i,
                b = /^(rgb|hsl)/,
                P = /([A-Z])/g,
                S = /-([a-z])/gi,
                O = /(^(?:url\(\"|url\())|(?:(\"\))$|\)$)/gi,
                k = function(t, e) {
                    return e.toUpperCase()
                },
                C = /(?:Left|Right|Width)/i,
                A = /(M11|M12|M21|M22)=[\d\-\.e]+/gi,
                R = /progid\:DXImageTransform\.Microsoft\.Matrix\(.+?\)/i,
                M = /,(?=[^\)]*(?:\(|$))/gi,
                E = Math.PI / 180,
                L = 180 / Math.PI,
                D = {},
                I = document,
                X = I.createElement("div"),
                N = I.createElement("img"),
                z = a._internals = {
                    _specialProps: o
                },
                F = navigator.userAgent,
                Y = function() {
                    var t, e = F.indexOf("Android"),
                        i = I.createElement("div");
                    return p = -1 !== F.indexOf("Safari") && -1 === F.indexOf("Chrome") && (-1 === e || Number(F.substr(e + 8, 1)) > 3), f = p && 6 > Number(F.substr(F.indexOf("Version/") + 8, 1)), c = -1 !== F.indexOf("Firefox"), (/MSIE ([0-9]{1,}[\.0-9]{0,})/.exec(F) || /Trident\/.*rv:([0-9]{1,}[\.0-9]{0,})/.exec(F)) && (d = parseFloat(RegExp.$1)), i.innerHTML = "<a style='top:1px;opacity:.55;'>a</a>", t = i.getElementsByTagName("a")[0], t ? /^0.55/.test(t.style.opacity) : !1
                }(),
                j = function(t) {
                    return x.test("string" == typeof t ? t : (t.currentStyle ? t.currentStyle.filter : t.style.filter) || "") ? parseFloat(RegExp.$1) / 100 : 1
                },
                B = function(t) {
                    window.console && console.log(t)
                },
                G = "",
                U = "",
                V = function(t, e) {
                    e = e || X;
                    var i, n, r = e.style;
                    if (void 0 !== r[t]) return t;
                    for (t = t.charAt(0).toUpperCase() + t.substr(1), i = ["O", "Moz", "ms", "Ms", "Webkit"], n = 5; --n > -1 && void 0 === r[i[n] + t];);
                    return n >= 0 ? (U = 3 === n ? "ms" : i[n], G = "-" + U.toLowerCase() + "-", U + t) : null
                },
                W = I.defaultView ? I.defaultView.getComputedStyle : function() {},
                H = a.getStyle = function(t, e, i, n, r) {
                    var s;
                    return Y || "opacity" !== e ? (!n && t.style[e] ? s = t.style[e] : (i = i || W(t)) ? s = i[e] || i.getPropertyValue(e) || i.getPropertyValue(e.replace(P, "-$1").toLowerCase()) : t.currentStyle && (s = t.currentStyle[e]), null == r || s && "none" !== s && "auto" !== s && "auto auto" !== s ? s : r) : j(t)
                },
                Q = z.convertToPixels = function(t, i, n, r, s) {
                    if ("px" === r || !r) return n;
                    if ("auto" === r || !n) return 0;
                    var o, l, h, u = C.test(i),
                        p = t,
                        c = X.style,
                        f = 0 > n;
                    if (f && (n = -n), "%" === r && -1 !== i.indexOf("border")) o = n / 100 * (u ? t.clientWidth : t.clientHeight);
                    else {
                        if (c.cssText = "border:0 solid red;position:" + H(t, "position") + ";line-height:0;", "%" !== r && p.appendChild) c[u ? "borderLeftWidth" : "borderTopWidth"] = n + r;
                        else {
                            if (p = t.parentNode || I.body, l = p._gsCache, h = e.ticker.frame, l && u && l.time === h) return l.width * n / 100;
                            c[u ? "width" : "height"] = n + r
                        }
                        p.appendChild(X), o = parseFloat(X[u ? "offsetWidth" : "offsetHeight"]), p.removeChild(X), u && "%" === r && a.cacheWidths !== !1 && (l = p._gsCache = p._gsCache || {}, l.time = h, l.width = 100 * (o / n)), 0 !== o || s || (o = Q(t, i, n, r, !0))
                    }
                    return f ? -o : o
                },
                q = z.calculateOffset = function(t, e, i) {
                    if ("absolute" !== H(t, "position", i)) return 0;
                    var n = "left" === e ? "Left" : "Top",
                        r = H(t, "margin" + n, i);
                    return t["offset" + n] - (Q(t, e, parseFloat(r), r.replace(y, "")) || 0)
                },
                Z = function(t, e) {
                    var i, n, r = {};
                    if (e = e || W(t, null))
                        if (i = e.length)
                            for (; --i > -1;) r[e[i].replace(S, k)] = e.getPropertyValue(e[i]);
                        else
                            for (i in e) r[i] = e[i];
                    else if (e = t.currentStyle || t.style)
                        for (i in e) "string" == typeof i && void 0 === r[i] && (r[i.replace(S, k)] = e[i]);
                    return Y || (r.opacity = j(t)), n = Re(t, e, !1), r.rotation = n.rotation, r.skewX = n.skewX, r.scaleX = n.scaleX, r.scaleY = n.scaleY, r.x = n.x, r.y = n.y, be && (r.z = n.z, r.rotationX = n.rotationX, r.rotationY = n.rotationY, r.scaleZ = n.scaleZ), r.filters && delete r.filters, r
                },
                $ = function(t, e, i, n, r) {
                    var s, a, o, l = {},
                        h = t.style;
                    for (a in i) "cssText" !== a && "length" !== a && isNaN(a) && (e[a] !== (s = i[a]) || r && r[a]) && -1 === a.indexOf("Origin") && ("number" == typeof s || "string" == typeof s) && (l[a] = "auto" !== s || "left" !== a && "top" !== a ? "" !== s && "auto" !== s && "none" !== s || "string" != typeof e[a] || "" === e[a].replace(v, "") ? s : 0 : q(t, a), void 0 !== h[a] && (o = new pe(h, a, h[a], o)));
                    if (n)
                        for (a in n) "className" !== a && (l[a] = n[a]);
                    return {
                        difs: l,
                        firstMPT: o
                    }
                },
                K = {
                    width: ["Left", "Right"],
                    height: ["Top", "Bottom"]
                },
                J = ["marginLeft", "marginRight", "marginTop", "marginBottom"],
                te = function(t, e, i) {
                    var n = parseFloat("width" === e ? t.offsetWidth : t.offsetHeight),
                        r = K[e],
                        s = r.length;
                    for (i = i || W(t, null); --s > -1;) n -= parseFloat(H(t, "padding" + r[s], i, !0)) || 0, n -= parseFloat(H(t, "border" + r[s] + "Width", i, !0)) || 0;
                    return n
                },
                ee = function(t, e) {
                    (null == t || "" === t || "auto" === t || "auto auto" === t) && (t = "0 0");
                    var i = t.split(" "),
                        n = -1 !== t.indexOf("left") ? "0%" : -1 !== t.indexOf("right") ? "100%" : i[0],
                        r = -1 !== t.indexOf("top") ? "0%" : -1 !== t.indexOf("bottom") ? "100%" : i[1];
                    return null == r ? r = "0" : "center" === r && (r = "50%"), ("center" === n || isNaN(parseFloat(n)) && -1 === (n + "").indexOf("=")) && (n = "50%"), e && (e.oxp = -1 !== n.indexOf("%"), e.oyp = -1 !== r.indexOf("%"), e.oxr = "=" === n.charAt(1), e.oyr = "=" === r.charAt(1), e.ox = parseFloat(n.replace(v, "")), e.oy = parseFloat(r.replace(v, ""))), n + " " + r + (i.length > 2 ? " " + i[2] : "")
                },
                ie = function(t, e) {
                    return "string" == typeof t && "=" === t.charAt(1) ? parseInt(t.charAt(0) + "1", 10) * parseFloat(t.substr(2)) : parseFloat(t) - parseFloat(e)
                },
                ne = function(t, e) {
                    return null == t ? e : "string" == typeof t && "=" === t.charAt(1) ? parseInt(t.charAt(0) + "1", 10) * parseFloat(t.substr(2)) + e : parseFloat(t)
                },
                re = function(t, e, i, n) {
                    var r, s, a, o, l = 1e-6;
                    return null == t ? o = e : "number" == typeof t ? o = t : (r = 360, s = t.split("_"), a = Number(s[0].replace(v, "")) * (-1 === t.indexOf("rad") ? 1 : L) - ("=" === t.charAt(1) ? 0 : e), s.length && (n && (n[i] = e + a), -1 !== t.indexOf("short") && (a %= r, a !== a % (r / 2) && (a = 0 > a ? a + r : a - r)), -1 !== t.indexOf("_cw") && 0 > a ? a = (a + 9999999999 * r) % r - (0 | a / r) * r : -1 !== t.indexOf("ccw") && a > 0 && (a = (a - 9999999999 * r) % r - (0 | a / r) * r)), o = e + a), l > o && o > -l && (o = 0), o
                },
                se = {
                    aqua: [0, 255, 255],
                    lime: [0, 255, 0],
                    silver: [192, 192, 192],
                    black: [0, 0, 0],
                    maroon: [128, 0, 0],
                    teal: [0, 128, 128],
                    blue: [0, 0, 255],
                    navy: [0, 0, 128],
                    white: [255, 255, 255],
                    fuchsia: [255, 0, 255],
                    olive: [128, 128, 0],
                    yellow: [255, 255, 0],
                    orange: [255, 165, 0],
                    gray: [128, 128, 128],
                    purple: [128, 0, 128],
                    green: [0, 128, 0],
                    red: [255, 0, 0],
                    pink: [255, 192, 203],
                    cyan: [0, 255, 255],
                    transparent: [255, 255, 255, 0]
                },
                ae = function(t, e, i) {
                    return t = 0 > t ? t + 1 : t > 1 ? t - 1 : t, 0 | 255 * (1 > 6 * t ? e + 6 * (i - e) * t : .5 > t ? i : 2 > 3 * t ? e + 6 * (i - e) * (2 / 3 - t) : e) + .5
                },
                oe = a.parseColor = function(t) {
                    var e, i, n, r, s, a;
                    return t && "" !== t ? "number" == typeof t ? [t >> 16, 255 & t >> 8, 255 & t] : ("," === t.charAt(t.length - 1) && (t = t.substr(0, t.length - 1)), se[t] ? se[t] : "#" === t.charAt(0) ? (4 === t.length && (e = t.charAt(1), i = t.charAt(2), n = t.charAt(3), t = "#" + e + e + i + i + n + n), t = parseInt(t.substr(1), 16), [t >> 16, 255 & t >> 8, 255 & t]) : "hsl" === t.substr(0, 3) ? (t = t.match(_), r = Number(t[0]) % 360 / 360, s = Number(t[1]) / 100, a = Number(t[2]) / 100, i = .5 >= a ? a * (s + 1) : a + s - a * s, e = 2 * a - i, t.length > 3 && (t[3] = Number(t[3])), t[0] = ae(r + 1 / 3, e, i), t[1] = ae(r, e, i), t[2] = ae(r - 1 / 3, e, i), t) : (t = t.match(_) || se.transparent, t[0] = Number(t[0]), t[1] = Number(t[1]), t[2] = Number(t[2]), t.length > 3 && (t[3] = Number(t[3])), t)) : se.black
                },
                le = "(?:\\b(?:(?:rgb|rgba|hsl|hsla)\\(.+?\\))|\\B#.+?\\b";
            for (l in se) le += "|" + l + "\\b";
            le = RegExp(le + ")", "gi");
            var he = function(t, e, i, n) {
                    if (null == t) return function(t) {
                        return t
                    };
                    var r, s = e ? (t.match(le) || [""])[0] : "",
                        a = t.split(s).join("").match(g) || [],
                        o = t.substr(0, t.indexOf(a[0])),
                        l = ")" === t.charAt(t.length - 1) ? ")" : "",
                        h = -1 !== t.indexOf(" ") ? " " : ",",
                        u = a.length,
                        p = u > 0 ? a[0].replace(_, "") : "";
                    return u ? r = e ? function(t) {
                        var e, c, f, d;
                        if ("number" == typeof t) t += p;
                        else if (n && M.test(t)) {
                            for (d = t.replace(M, "|").split("|"), f = 0; d.length > f; f++) d[f] = r(d[f]);
                            return d.join(",")
                        }
                        if (e = (t.match(le) || [s])[0], c = t.split(e).join("").match(g) || [], f = c.length, u > f--)
                            for (; u > ++f;) c[f] = i ? c[0 | (f - 1) / 2] : a[f];
                        return o + c.join(h) + h + e + l + (-1 !== t.indexOf("inset") ? " inset" : "")
                    } : function(t) {
                        var e, s, c;
                        if ("number" == typeof t) t += p;
                        else if (n && M.test(t)) {
                            for (s = t.replace(M, "|").split("|"), c = 0; s.length > c; c++) s[c] = r(s[c]);
                            return s.join(",")
                        }
                        if (e = t.match(g) || [], c = e.length, u > c--)
                            for (; u > ++c;) e[c] = i ? e[0 | (c - 1) / 2] : a[c];
                        return o + e.join(h) + l
                    } : function(t) {
                        return t
                    }
                },
                ue = function(t) {
                    return t = t.split(","),
                        function(e, i, n, r, s, a, o) {
                            var l, h = (i + "").split(" ");
                            for (o = {}, l = 0; 4 > l; l++) o[t[l]] = h[l] = h[l] || h[(l - 1) / 2 >> 0];
                            return r.parse(e, o, s, a)
                        }
                },
                pe = (z._setPluginRatio = function(t) {
                    this.plugin.setRatio(t);
                    for (var e, i, n, r, s = this.data, a = s.proxy, o = s.firstMPT, l = 1e-6; o;) e = a[o.v], o.r ? e = Math.round(e) : l > e && e > -l && (e = 0), o.t[o.p] = e, o = o._next;
                    if (s.autoRotate && (s.autoRotate.rotation = a.rotation), 1 === t)
                        for (o = s.firstMPT; o;) {
                            if (i = o.t, i.type) {
                                if (1 === i.type) {
                                    for (r = i.xs0 + i.s + i.xs1, n = 1; i.l > n; n++) r += i["xn" + n] + i["xs" + (n + 1)];
                                    i.e = r
                                }
                            } else i.e = i.s + i.xs0;
                            o = o._next
                        }
                }, function(t, e, i, n, r) {
                    this.t = t, this.p = e, this.v = i, this.r = r, n && (n._prev = this, this._next = n)
                }),
                ce = (z._parseToProxy = function(t, e, i, n, r, s) {
                    var a, o, l, h, u, p = n,
                        c = {},
                        f = {},
                        d = i._transform,
                        _ = D;
                    for (i._transform = null, D = e, n = u = i.parse(t, e, n, r), D = _, s && (i._transform = d, p && (p._prev = null, p._prev && (p._prev._next = null))); n && n !== p;) {
                        if (1 >= n.type && (o = n.p, f[o] = n.s + n.c, c[o] = n.s, s || (h = new pe(n, "s", o, h, n.r), n.c = 0), 1 === n.type))
                            for (a = n.l; --a > 0;) l = "xn" + a, o = n.p + "_" + l, f[o] = n.data[l], c[o] = n[l], s || (h = new pe(n, l, o, h, n.rxp[l]));
                        n = n._next
                    }
                    return {
                        proxy: c,
                        end: f,
                        firstMPT: h,
                        pt: u
                    }
                }, z.CSSPropTween = function(t, e, n, r, a, o, l, h, u, p, c) {
                    this.t = t, this.p = e, this.s = n, this.c = r, this.n = l || e, t instanceof ce || s.push(this.n), this.r = h, this.type = o || 0, u && (this.pr = u, i = !0), this.b = void 0 === p ? n : p, this.e = void 0 === c ? n + r : c, a && (this._next = a, a._prev = this)
                }),
                fe = a.parseComplex = function(t, e, i, n, r, s, a, o, l, u) {
                    i = i || s || "", a = new ce(t, e, 0, 0, a, u ? 2 : 1, null, !1, o, i, n), n += "";
                    var p, c, f, d, g, v, y, x, w, T, P, S, O = i.split(", ").join(",").split(" "),
                        k = n.split(", ").join(",").split(" "),
                        C = O.length,
                        A = h !== !1;
                    for ((-1 !== n.indexOf(",") || -1 !== i.indexOf(",")) && (O = O.join(" ").replace(M, ", ").split(" "), k = k.join(" ").replace(M, ", ").split(" "), C = O.length), C !== k.length && (O = (s || "").split(" "), C = O.length), a.plugin = l, a.setRatio = u, p = 0; C > p; p++)
                        if (d = O[p], g = k[p], x = parseFloat(d), x || 0 === x) a.appendXtra("", x, ie(g, x), g.replace(m, ""), A && -1 !== g.indexOf("px"), !0);
                        else if (r && ("#" === d.charAt(0) || se[d] || b.test(d))) S = "," === g.charAt(g.length - 1) ? ")," : ")", d = oe(d), g = oe(g), w = d.length + g.length > 6, w && !Y && 0 === g[3] ? (a["xs" + a.l] += a.l ? " transparent" : "transparent", a.e = a.e.split(k[p]).join("transparent")) : (Y || (w = !1), a.appendXtra(w ? "rgba(" : "rgb(", d[0], g[0] - d[0], ",", !0, !0).appendXtra("", d[1], g[1] - d[1], ",", !0).appendXtra("", d[2], g[2] - d[2], w ? "," : S, !0), w && (d = 4 > d.length ? 1 : d[3], a.appendXtra("", d, (4 > g.length ? 1 : g[3]) - d, S, !1)));
                    else if (v = d.match(_)) {
                        if (y = g.match(m), !y || y.length !== v.length) return a;
                        for (f = 0, c = 0; v.length > c; c++) P = v[c], T = d.indexOf(P, f), a.appendXtra(d.substr(f, T - f), Number(P), ie(y[c], P), "", A && "px" === d.substr(T + P.length, 2), 0 === c), f = T + P.length;
                        a["xs" + a.l] += d.substr(f)
                    } else a["xs" + a.l] += a.l ? " " + d : d;
                    if (-1 !== n.indexOf("=") && a.data) {
                        for (S = a.xs0 + a.data.s, p = 1; a.l > p; p++) S += a["xs" + p] + a.data["xn" + p];
                        a.e = S + a["xs" + p]
                    }
                    return a.l || (a.type = -1, a.xs0 = a.e), a.xfirst || a
                },
                de = 9;
            for (l = ce.prototype, l.l = l.pr = 0; --de > 0;) l["xn" + de] = 0, l["xs" + de] = "";
            l.xs0 = "", l._next = l._prev = l.xfirst = l.data = l.plugin = l.setRatio = l.rxp = null, l.appendXtra = function(t, e, i, n, r, s) {
                var a = this,
                    o = a.l;
                return a["xs" + o] += s && o ? " " + t : t || "", i || 0 === o || a.plugin ? (a.l++, a.type = a.setRatio ? 2 : 1, a["xs" + a.l] = n || "", o > 0 ? (a.data["xn" + o] = e + i, a.rxp["xn" + o] = r, a["xn" + o] = e, a.plugin || (a.xfirst = new ce(a, "xn" + o, e, i, a.xfirst || a, 0, a.n, r, a.pr), a.xfirst.xs0 = 0), a) : (a.data = {
                    s: e + i
                }, a.rxp = {}, a.s = e, a.c = i, a.r = r, a)) : (a["xs" + o] += e + (n || ""), a)
            };
            var _e = function(t, e) {
                    e = e || {}, this.p = e.prefix ? V(t) || t : t, o[t] = o[this.p] = this, this.format = e.formatter || he(e.defaultValue, e.color, e.collapsible, e.multi), e.parser && (this.parse = e.parser), this.clrs = e.color, this.multi = e.multi, this.keyword = e.keyword, this.dflt = e.defaultValue, this.pr = e.priority || 0
                },
                me = z._registerComplexSpecialProp = function(t, e, i) {
                    "object" != typeof e && (e = {
                        parser: i
                    });
                    var n, r, s = t.split(","),
                        a = e.defaultValue;
                    for (i = i || [a], n = 0; s.length > n; n++) e.prefix = 0 === n && e.prefix, e.defaultValue = i[n] || a, r = new _e(s[n], e)
                },
                ge = function(t) {
                    if (!o[t]) {
                        var e = t.charAt(0).toUpperCase() + t.substr(1) + "Plugin";
                        me(t, {
                            parser: function(t, i, n, r, s, a, l) {
                                var h = (_gsScope.GreenSockGlobals || _gsScope).com.greensock.plugins[e];
                                return h ? (h._cssRegister(), o[n].parse(t, i, n, r, s, a, l)) : (B("Error: " + e + " js file not loaded."), s)
                            }
                        })
                    }
                };
            l = _e.prototype, l.parseComplex = function(t, e, i, n, r, s) {
                var a, o, l, h, u, p, c = this.keyword;
                if (this.multi && (M.test(i) || M.test(e) ? (o = e.replace(M, "|").split("|"), l = i.replace(M, "|").split("|")) : c && (o = [e], l = [i])), l) {
                    for (h = l.length > o.length ? l.length : o.length, a = 0; h > a; a++) e = o[a] = o[a] || this.dflt, i = l[a] = l[a] || this.dflt, c && (u = e.indexOf(c), p = i.indexOf(c), u !== p && (i = -1 === p ? l : o, i[a] += " " + c));
                    e = o.join(", "), i = l.join(", ")
                }
                return fe(t, this.p, e, i, this.clrs, this.dflt, n, this.pr, r, s)
            }, l.parse = function(t, e, i, n, s, a) {
                return this.parseComplex(t.style, this.format(H(t, this.p, r, !1, this.dflt)), this.format(e), s, a)
            }, a.registerSpecialProp = function(t, e, i) {
                me(t, {
                    parser: function(t, n, r, s, a, o) {
                        var l = new ce(t, r, 0, 0, a, 2, r, !1, i);
                        return l.plugin = o, l.setRatio = e(t, n, s._tween, r), l
                    },
                    priority: i
                })
            };
            var ve, ye = "scaleX,scaleY,scaleZ,x,y,z,skewX,skewY,rotation,rotationX,rotationY,perspective,xPercent,yPercent".split(","),
                xe = V("transform"),
                we = G + "transform",
                Te = V("transformOrigin"),
                be = null !== V("perspective"),
                Pe = z.Transform = function() {
                    this.skewY = 0
                },
                Se = window.SVGElement,
                Oe = function(t, e, i) {
                    var n, r = I.createElementNS("http://www.w3.org/2000/svg", t),
                        s = /([a-z])([A-Z])/g;
                    for (n in i) r.setAttributeNS(null, n.replace(s, "$1-$2").toLowerCase(), i[n]);
                    return e.appendChild(r), r
                },
                ke = document.documentElement,
                Ce = function() {
                    var t, e, i, n = d || /Android/i.test(F) && !window.chrome;
                    return I.createElementNS && !n && (t = Oe("svg", ke), e = Oe("rect", t, {
                        width: 100,
                        height: 50,
                        x: 100
                    }), i = e.getBoundingClientRect().left, e.style[Te] = "50% 50%", e.style[xe] = "scale(0.5,0.5)", n = i === e.getBoundingClientRect().left, ke.removeChild(t)), n
                }(),
                Ae = function(t, e, i) {
                    var n = t.getBBox();
                    e = ee(e).split(" "), i.xOrigin = (-1 !== e[0].indexOf("%") ? parseFloat(e[0]) / 100 * n.width : parseFloat(e[0])) + n.x, i.yOrigin = (-1 !== e[1].indexOf("%") ? parseFloat(e[1]) / 100 * n.height : parseFloat(e[1])) + n.y
                },
                Re = z.getTransform = function(t, e, i, n) {
                    if (t._gsTransform && i && !n) return t._gsTransform;
                    var s, o, l, h, u, p, c, f, d, _, m, g, v, y = i ? t._gsTransform || new Pe : new Pe,
                        x = 0 > y.scaleX,
                        w = 2e-5,
                        T = 1e5,
                        b = 179.99,
                        P = b * E,
                        S = be ? parseFloat(H(t, Te, e, !1, "0 0 0").split(" ")[2]) || y.zOrigin || 0 : 0,
                        O = parseFloat(a.defaultTransformPerspective) || 0;
                    if (xe ? s = H(t, we, e, !0) : t.currentStyle && (s = t.currentStyle.filter.match(A), s = s && 4 === s.length ? [s[0].substr(4), Number(s[2].substr(4)), Number(s[1].substr(4)), s[3].substr(4), y.x || 0, y.y || 0].join(",") : ""), s && "none" !== s && "matrix(1, 0, 0, 1, 0, 0)" !== s) {
                        for (o = (s || "").match(/(?:\-|\b)[\d\-\.e]+\b/gi) || [], l = o.length; --l > -1;) h = Number(o[l]), o[l] = (u = h - (h |= 0)) ? (0 | u * T + (0 > u ? -.5 : .5)) / T + h : h;
                        if (16 === o.length) {
                            var k = o[8],
                                C = o[9],
                                R = o[10],
                                M = o[12],
                                D = o[13],
                                I = o[14];
                            if (y.zOrigin && (I = -y.zOrigin, M = k * I - o[12], D = C * I - o[13], I = R * I + y.zOrigin - o[14]), !i || n || null == y.rotationX) {
                                var X, N, z, F, Y, j, B, G = o[0],
                                    U = o[1],
                                    V = o[2],
                                    W = o[3],
                                    Q = o[4],
                                    q = o[5],
                                    Z = o[6],
                                    $ = o[7],
                                    K = o[11],
                                    J = Math.atan2(Z, R),
                                    te = -P > J || J > P;
                                y.rotationX = J * L, J && (F = Math.cos(-J), Y = Math.sin(-J), X = Q * F + k * Y, N = q * F + C * Y, z = Z * F + R * Y, k = Q * -Y + k * F, C = q * -Y + C * F, R = Z * -Y + R * F, K = $ * -Y + K * F, Q = X, q = N, Z = z), J = Math.atan2(k, G), y.rotationY = J * L, J && (j = -P > J || J > P, F = Math.cos(-J), Y = Math.sin(-J), X = G * F - k * Y, N = U * F - C * Y, z = V * F - R * Y, C = U * Y + C * F, R = V * Y + R * F, K = W * Y + K * F, G = X, U = N, V = z), J = Math.atan2(U, q), y.rotation = J * L, J && (B = -P > J || J > P, F = Math.cos(-J), Y = Math.sin(-J), G = G * F + Q * Y, N = U * F + q * Y, q = U * -Y + q * F, Z = V * -Y + Z * F, U = N), B && te ? y.rotation = y.rotationX = 0 : B && j ? y.rotation = y.rotationY = 0 : j && te && (y.rotationY = y.rotationX = 0), y.scaleX = (0 | Math.sqrt(G * G + U * U) * T + .5) / T, y.scaleY = (0 | Math.sqrt(q * q + C * C) * T + .5) / T, y.scaleZ = (0 | Math.sqrt(Z * Z + R * R) * T + .5) / T, y.skewX = 0, y.perspective = K ? 1 / (0 > K ? -K : K) : 0, y.x = M, y.y = D, y.z = I
                            }
                        } else if (!(be && !n && o.length && y.x === o[4] && y.y === o[5] && (y.rotationX || y.rotationY) || void 0 !== y.x && "none" === H(t, "display", e))) {
                            var ee = o.length >= 6,
                                ie = ee ? o[0] : 1,
                                ne = o[1] || 0,
                                re = o[2] || 0,
                                se = ee ? o[3] : 1;
                            y.x = o[4] || 0, y.y = o[5] || 0, p = Math.sqrt(ie * ie + ne * ne), c = Math.sqrt(se * se + re * re), f = ie || ne ? Math.atan2(ne, ie) * L : y.rotation || 0, d = re || se ? Math.atan2(re, se) * L + f : y.skewX || 0, _ = p - Math.abs(y.scaleX || 0), m = c - Math.abs(y.scaleY || 0), Math.abs(d) > 90 && 270 > Math.abs(d) && (x ? (p *= -1, d += 0 >= f ? 180 : -180, f += 0 >= f ? 180 : -180) : (c *= -1, d += 0 >= d ? 180 : -180)), g = (f - y.rotation) % 180, v = (d - y.skewX) % 180, (void 0 === y.skewX || _ > w || -w > _ || m > w || -w > m || g > -b && b > g && !1 | g * T || v > -b && b > v && !1 | v * T) && (y.scaleX = p, y.scaleY = c, y.rotation = f, y.skewX = d), be && (y.rotationX = y.rotationY = y.z = 0, y.perspective = O, y.scaleZ = 1)
                        }
                        y.zOrigin = S;
                        for (l in y) w > y[l] && y[l] > -w && (y[l] = 0)
                    } else y = {
                        x: 0,
                        y: 0,
                        z: 0,
                        scaleX: 1,
                        scaleY: 1,
                        scaleZ: 1,
                        skewX: 0,
                        skewY: 0,
                        perspective: O,
                        rotation: 0,
                        rotationX: 0,
                        rotationY: 0,
                        zOrigin: 0
                    };
                    return i && (t._gsTransform = y), y.svg = Se && t instanceof Se && t.parentNode instanceof Se, y.svg && (Ae(t, H(t, Te, r, !1, "50% 50%") + "", y), ve = a.useSVGTransformAttr || Ce), y.xPercent = y.yPercent = 0, y
                },
                Me = function(t) {
                    var e, i, n = this.data,
                        r = -n.rotation * E,
                        s = r + n.skewX * E,
                        a = 1e5,
                        o = (0 | Math.cos(r) * n.scaleX * a) / a,
                        l = (0 | Math.sin(r) * n.scaleX * a) / a,
                        h = (0 | Math.sin(s) * -n.scaleY * a) / a,
                        u = (0 | Math.cos(s) * n.scaleY * a) / a,
                        p = this.t.style,
                        c = this.t.currentStyle;
                    if (c) {
                        i = l, l = -h, h = -i, e = c.filter, p.filter = "";
                        var f, _, m = this.t.offsetWidth,
                            g = this.t.offsetHeight,
                            v = "absolute" !== c.position,
                            w = "progid:DXImageTransform.Microsoft.Matrix(M11=" + o + ", M12=" + l + ", M21=" + h + ", M22=" + u,
                            T = n.x + m * n.xPercent / 100,
                            b = n.y + g * n.yPercent / 100;
                        if (null != n.ox && (f = (n.oxp ? .01 * m * n.ox : n.ox) - m / 2, _ = (n.oyp ? .01 * g * n.oy : n.oy) - g / 2, T += f - (f * o + _ * l), b += _ - (f * h + _ * u)), v ? (f = m / 2, _ = g / 2, w += ", Dx=" + (f - (f * o + _ * l) + T) + ", Dy=" + (_ - (f * h + _ * u) + b) + ")") : w += ", sizingMethod='auto expand')", p.filter = -1 !== e.indexOf("DXImageTransform.Microsoft.Matrix(") ? e.replace(R, w) : w + " " + e, (0 === t || 1 === t) && 1 === o && 0 === l && 0 === h && 1 === u && (v && -1 === w.indexOf("Dx=0, Dy=0") || x.test(e) && 100 !== parseFloat(RegExp.$1) || -1 === e.indexOf("gradient(" && e.indexOf("Alpha")) && p.removeAttribute("filter")), !v) {
                            var P, S, O, k = 8 > d ? 1 : -1;
                            for (f = n.ieOffsetX || 0, _ = n.ieOffsetY || 0, n.ieOffsetX = Math.round((m - ((0 > o ? -o : o) * m + (0 > l ? -l : l) * g)) / 2 + T), n.ieOffsetY = Math.round((g - ((0 > u ? -u : u) * g + (0 > h ? -h : h) * m)) / 2 + b), de = 0; 4 > de; de++) S = J[de], P = c[S], i = -1 !== P.indexOf("px") ? parseFloat(P) : Q(this.t, S, parseFloat(P), P.replace(y, "")) || 0, O = i !== n[S] ? 2 > de ? -n.ieOffsetX : -n.ieOffsetY : 2 > de ? f - n.ieOffsetX : _ - n.ieOffsetY, p[S] = (n[S] = Math.round(i - O * (0 === de || 2 === de ? 1 : k))) + "px"
                        }
                    }
                },
                Ee = z.set3DTransformRatio = function(t) {
                    var e, i, n, r, s, a, o, l, h, u, p, f, d, _, m, g, v, y, x, w, T, b, P, S = this.data,
                        O = this.t.style,
                        k = S.rotation * E,
                        C = S.scaleX,
                        A = S.scaleY,
                        R = S.scaleZ,
                        M = S.x,
                        L = S.y,
                        D = S.z,
                        I = S.perspective;
                    if (!(1 !== t && 0 !== t || "auto" !== S.force3D || S.rotationY || S.rotationX || 1 !== R || I || D)) return void Le.call(this, t);
                    if (c) {
                        var X = 1e-4;
                        X > C && C > -X && (C = R = 2e-5), X > A && A > -X && (A = R = 2e-5), !I || S.z || S.rotationX || S.rotationY || (I = 0)
                    }
                    if (k || S.skewX) y = Math.cos(k), x = Math.sin(k), e = y, s = x, S.skewX && (k -= S.skewX * E, y = Math.cos(k), x = Math.sin(k), "simple" === S.skewType && (w = Math.tan(S.skewX * E), w = Math.sqrt(1 + w * w), y *= w, x *= w)), i = -x, a = y;
                    else {
                        if (!(S.rotationY || S.rotationX || 1 !== R || I || S.svg)) return void(O[xe] = (S.xPercent || S.yPercent ? "translate(" + S.xPercent + "%," + S.yPercent + "%) translate3d(" : "translate3d(") + M + "px," + L + "px," + D + "px)" + (1 !== C || 1 !== A ? " scale(" + C + "," + A + ")" : ""));
                        e = a = 1, i = s = 0
                    }
                    p = 1, n = r = o = l = h = u = f = d = _ = 0, m = I ? -1 / I : 0, g = S.zOrigin, v = 1e5, k = S.rotationY * E, k && (y = Math.cos(k), x = Math.sin(k), h = p * -x, d = m * -x, n = e * x, o = s * x, p *= y, m *= y, e *= y, s *= y), k = S.rotationX * E, k && (y = Math.cos(k), x = Math.sin(k), w = i * y + n * x, T = a * y + o * x, b = u * y + p * x, P = _ * y + m * x, n = i * -x + n * y, o = a * -x + o * y, p = u * -x + p * y, m = _ * -x + m * y, i = w, a = T, u = b, _ = P), 1 !== R && (n *= R, o *= R, p *= R, m *= R), 1 !== A && (i *= A, a *= A, u *= A, _ *= A), 1 !== C && (e *= C, s *= C, h *= C, d *= C), g && (f -= g, r = n * f, l = o * f, f = p * f + g), S.svg && (r += S.xOrigin - (S.xOrigin * e + S.yOrigin * i), l += S.yOrigin - (S.xOrigin * s + S.yOrigin * a)), r = (w = (r += M) - (r |= 0)) ? (0 | w * v + (0 > w ? -.5 : .5)) / v + r : r, l = (w = (l += L) - (l |= 0)) ? (0 | w * v + (0 > w ? -.5 : .5)) / v + l : l, f = (w = (f += D) - (f |= 0)) ? (0 | w * v + (0 > w ? -.5 : .5)) / v + f : f, O[xe] = (S.xPercent || S.yPercent ? "translate(" + S.xPercent + "%," + S.yPercent + "%) matrix3d(" : "matrix3d(") + [(0 | e * v) / v, (0 | s * v) / v, (0 | h * v) / v, (0 | d * v) / v, (0 | i * v) / v, (0 | a * v) / v, (0 | u * v) / v, (0 | _ * v) / v, (0 | n * v) / v, (0 | o * v) / v, (0 | p * v) / v, (0 | m * v) / v, r, l, f, I ? 1 + -f / I : 1].join(",") + ")"
                },
                Le = z.set2DTransformRatio = function(t) {
                    var e, i, n, r, s, a, o, l, h, u, p, c = this.data,
                        f = this.t,
                        d = f.style,
                        _ = c.x,
                        m = c.y;
                    return !(c.rotationX || c.rotationY || c.z || c.force3D === !0 || "auto" === c.force3D && 1 !== t && 0 !== t) || c.svg && ve || !be ? (r = c.scaleX, s = c.scaleY, void(c.rotation || c.skewX || c.svg ? (e = c.rotation * E, i = e - c.skewX * E, n = 1e5, a = Math.cos(e) * r, o = Math.sin(e) * r, l = Math.sin(i) * -s, h = Math.cos(i) * s, c.svg && (_ += c.xOrigin - (c.xOrigin * a + c.yOrigin * l), m += c.yOrigin - (c.xOrigin * o + c.yOrigin * h), p = 1e-6, p > _ && _ > -p && (_ = 0), p > m && m > -p && (m = 0)), u = (0 | a * n) / n + "," + (0 | o * n) / n + "," + (0 | l * n) / n + "," + (0 | h * n) / n + "," + _ + "," + m + ")", c.svg && ve ? f.setAttribute("transform", "matrix(" + u) : d[xe] = (c.xPercent || c.yPercent ? "translate(" + c.xPercent + "%," + c.yPercent + "%) matrix(" : "matrix(") + u) : d[xe] = (c.xPercent || c.yPercent ? "translate(" + c.xPercent + "%," + c.yPercent + "%) matrix(" : "matrix(") + r + ",0,0," + s + "," + _ + "," + m + ")")) : (this.setRatio = Ee, void Ee.call(this, t))
                };
            me("transform,scale,scaleX,scaleY,scaleZ,x,y,z,rotation,rotationX,rotationY,rotationZ,skewX,skewY,shortRotation,shortRotationX,shortRotationY,shortRotationZ,transformOrigin,transformPerspective,directionalRotation,parseTransform,force3D,skewType,xPercent,yPercent", {
                parser: function(t, e, i, n, s, o, l) {
                    if (n._transform) return s;
                    var h, u, p, c, f, d, _, m = n._transform = Re(t, r, !0, l.parseTransform),
                        g = t.style,
                        v = 1e-6,
                        y = ye.length,
                        x = l,
                        w = {};
                    if ("string" == typeof x.transform && xe) p = X.style, p[xe] = x.transform, p.display = "block", p.position = "absolute", I.body.appendChild(X), h = Re(X, null, !1), I.body.removeChild(X);
                    else if ("object" == typeof x) {
                        if (h = {
                                scaleX: ne(null != x.scaleX ? x.scaleX : x.scale, m.scaleX),
                                scaleY: ne(null != x.scaleY ? x.scaleY : x.scale, m.scaleY),
                                scaleZ: ne(x.scaleZ, m.scaleZ),
                                x: ne(x.x, m.x),
                                y: ne(x.y, m.y),
                                z: ne(x.z, m.z),
                                xPercent: ne(x.xPercent, m.xPercent),
                                yPercent: ne(x.yPercent, m.yPercent),
                                perspective: ne(x.transformPerspective, m.perspective)
                            }, _ = x.directionalRotation, null != _)
                            if ("object" == typeof _)
                                for (p in _) x[p] = _[p];
                            else x.rotation = _;
                            "string" == typeof x.x && -1 !== x.x.indexOf("%") && (h.x = 0, h.xPercent = ne(x.x, m.xPercent)), "string" == typeof x.y && -1 !== x.y.indexOf("%") && (h.y = 0, h.yPercent = ne(x.y, m.yPercent)), h.rotation = re("rotation" in x ? x.rotation : "shortRotation" in x ? x.shortRotation + "_short" : "rotationZ" in x ? x.rotationZ : m.rotation, m.rotation, "rotation", w), be && (h.rotationX = re("rotationX" in x ? x.rotationX : "shortRotationX" in x ? x.shortRotationX + "_short" : m.rotationX || 0, m.rotationX, "rotationX", w), h.rotationY = re("rotationY" in x ? x.rotationY : "shortRotationY" in x ? x.shortRotationY + "_short" : m.rotationY || 0, m.rotationY, "rotationY", w)), h.skewX = null == x.skewX ? m.skewX : re(x.skewX, m.skewX), h.skewY = null == x.skewY ? m.skewY : re(x.skewY, m.skewY), (u = h.skewY - m.skewY) && (h.skewX += u, h.rotation += u)
                    }
                    for (be && null != x.force3D && (m.force3D = x.force3D, d = !0), m.skewType = x.skewType || m.skewType || a.defaultSkewType, f = m.force3D || m.z || m.rotationX || m.rotationY || h.z || h.rotationX || h.rotationY || h.perspective, f || null == x.scale || (h.scaleZ = 1); --y > -1;) i = ye[y], c = h[i] - m[i], (c > v || -v > c || null != x[i] || null != D[i]) && (d = !0, s = new ce(m, i, m[i], c, s), i in w && (s.e = w[i]), s.xs0 = 0, s.plugin = o, n._overwriteProps.push(s.n));
                    return c = x.transformOrigin, c && m.svg && (Ae(t, c, h), s = new ce(m, "xOrigin", m.xOrigin, h.xOrigin - m.xOrigin, s, -1, "transformOrigin"), s.b = m.xOrigin, s.e = s.xs0 = h.xOrigin, s = new ce(m, "yOrigin", m.yOrigin, h.yOrigin - m.yOrigin, s, -1, "transformOrigin"), s.b = m.yOrigin, s.e = s.xs0 = h.yOrigin, c = "0px 0px"), (c || be && f && m.zOrigin) && (xe ? (d = !0, i = Te, c = (c || H(t, i, r, !1, "50% 50%")) + "", s = new ce(g, i, 0, 0, s, -1, "transformOrigin"), s.b = g[i], s.plugin = o, be ? (p = m.zOrigin, c = c.split(" "), m.zOrigin = (c.length > 2 && (0 === p || "0px" !== c[2]) ? parseFloat(c[2]) : p) || 0, s.xs0 = s.e = c[0] + " " + (c[1] || "50%") + " 0px", s = new ce(m, "zOrigin", 0, 0, s, -1, s.n), s.b = p, s.xs0 = s.e = m.zOrigin) : s.xs0 = s.e = c) : ee(c + "", m)), d && (n._transformType = m.svg && ve || !f && 3 !== this._transformType ? 2 : 3), s
                },
                prefix: !0
            }), me("boxShadow", {
                defaultValue: "0px 0px 0px 0px #999",
                prefix: !0,
                color: !0,
                multi: !0,
                keyword: "inset"
            }), me("borderRadius", {
                defaultValue: "0px",
                parser: function(t, e, i, s, a) {
                    e = this.format(e);
                    var o, l, h, u, p, c, f, d, _, m, g, v, y, x, w, T, b = ["borderTopLeftRadius", "borderTopRightRadius", "borderBottomRightRadius", "borderBottomLeftRadius"],
                        P = t.style;
                    for (_ = parseFloat(t.offsetWidth), m = parseFloat(t.offsetHeight), o = e.split(" "), l = 0; b.length > l; l++) this.p.indexOf("border") && (b[l] = V(b[l])), p = u = H(t, b[l], r, !1, "0px"), -1 !== p.indexOf(" ") && (u = p.split(" "), p = u[0], u = u[1]), c = h = o[l], f = parseFloat(p), v = p.substr((f + "").length), y = "=" === c.charAt(1), y ? (d = parseInt(c.charAt(0) + "1", 10), c = c.substr(2), d *= parseFloat(c), g = c.substr((d + "").length - (0 > d ? 1 : 0)) || "") : (d = parseFloat(c), g = c.substr((d + "").length)), "" === g && (g = n[i] || v), g !== v && (x = Q(t, "borderLeft", f, v), w = Q(t, "borderTop", f, v), "%" === g ? (p = 100 * (x / _) + "%", u = 100 * (w / m) + "%") : "em" === g ? (T = Q(t, "borderLeft", 1, "em"), p = x / T + "em", u = w / T + "em") : (p = x + "px", u = w + "px"), y && (c = parseFloat(p) + d + g, h = parseFloat(u) + d + g)), a = fe(P, b[l], p + " " + u, c + " " + h, !1, "0px", a);
                    return a
                },
                prefix: !0,
                formatter: he("0px 0px 0px 0px", !1, !0)
            }), me("backgroundPosition", {
                defaultValue: "0 0",
                parser: function(t, e, i, n, s, a) {
                    var o, l, h, u, p, c, f = "background-position",
                        _ = r || W(t, null),
                        m = this.format((_ ? d ? _.getPropertyValue(f + "-x") + " " + _.getPropertyValue(f + "-y") : _.getPropertyValue(f) : t.currentStyle.backgroundPositionX + " " + t.currentStyle.backgroundPositionY) || "0 0"),
                        g = this.format(e);
                    if (-1 !== m.indexOf("%") != (-1 !== g.indexOf("%")) && (c = H(t, "backgroundImage").replace(O, ""), c && "none" !== c)) {
                        for (o = m.split(" "), l = g.split(" "), N.setAttribute("src", c), h = 2; --h > -1;) m = o[h], u = -1 !== m.indexOf("%"), u !== (-1 !== l[h].indexOf("%")) && (p = 0 === h ? t.offsetWidth - N.width : t.offsetHeight - N.height, o[h] = u ? parseFloat(m) / 100 * p + "px" : 100 * (parseFloat(m) / p) + "%");
                        m = o.join(" ")
                    }
                    return this.parseComplex(t.style, m, g, s, a)
                },
                formatter: ee
            }), me("backgroundSize", {
                defaultValue: "0 0",
                formatter: ee
            }), me("perspective", {
                defaultValue: "0px",
                prefix: !0
            }), me("perspectiveOrigin", {
                defaultValue: "50% 50%",
                prefix: !0
            }), me("transformStyle", {
                prefix: !0
            }), me("backfaceVisibility", {
                prefix: !0
            }), me("userSelect", {
                prefix: !0
            }), me("margin", {
                parser: ue("marginTop,marginRight,marginBottom,marginLeft")
            }), me("padding", {
                parser: ue("paddingTop,paddingRight,paddingBottom,paddingLeft")
            }), me("clip", {
                defaultValue: "rect(0px,0px,0px,0px)",
                parser: function(t, e, i, n, s, a) {
                    var o, l, h;
                    return 9 > d ? (l = t.currentStyle, h = 8 > d ? " " : ",", o = "rect(" + l.clipTop + h + l.clipRight + h + l.clipBottom + h + l.clipLeft + ")", e = this.format(e).split(",").join(h)) : (o = this.format(H(t, this.p, r, !1, this.dflt)), e = this.format(e)), this.parseComplex(t.style, o, e, s, a)
                }
            }), me("textShadow", {
                defaultValue: "0px 0px 0px #999",
                color: !0,
                multi: !0
            }), me("autoRound,strictUnits", {
                parser: function(t, e, i, n, r) {
                    return r
                }
            }), me("border", {
                defaultValue: "0px solid #000",
                parser: function(t, e, i, n, s, a) {
                    return this.parseComplex(t.style, this.format(H(t, "borderTopWidth", r, !1, "0px") + " " + H(t, "borderTopStyle", r, !1, "solid") + " " + H(t, "borderTopColor", r, !1, "#000")), this.format(e), s, a)
                },
                color: !0,
                formatter: function(t) {
                    var e = t.split(" ");
                    return e[0] + " " + (e[1] || "solid") + " " + (t.match(le) || ["#000"])[0]
                }
            }), me("borderWidth", {
                parser: ue("borderTopWidth,borderRightWidth,borderBottomWidth,borderLeftWidth")
            }), me("float,cssFloat,styleFloat", {
                parser: function(t, e, i, n, r) {
                    var s = t.style,
                        a = "cssFloat" in s ? "cssFloat" : "styleFloat";
                    return new ce(s, a, 0, 0, r, -1, i, !1, 0, s[a], e)
                }
            });
            var De = function(t) {
                var e, i = this.t,
                    n = i.filter || H(this.data, "filter") || "",
                    r = 0 | this.s + this.c * t;
                100 === r && (-1 === n.indexOf("atrix(") && -1 === n.indexOf("radient(") && -1 === n.indexOf("oader(") ? (i.removeAttribute("filter"), e = !H(this.data, "filter")) : (i.filter = n.replace(T, ""), e = !0)), e || (this.xn1 && (i.filter = n = n || "alpha(opacity=" + r + ")"), -1 === n.indexOf("pacity") ? 0 === r && this.xn1 || (i.filter = n + " alpha(opacity=" + r + ")") : i.filter = n.replace(x, "opacity=" + r))
            };
            me("opacity,alpha,autoAlpha", {
                defaultValue: "1",
                parser: function(t, e, i, n, s, a) {
                    var o = parseFloat(H(t, "opacity", r, !1, "1")),
                        l = t.style,
                        h = "autoAlpha" === i;
                    return "string" == typeof e && "=" === e.charAt(1) && (e = ("-" === e.charAt(0) ? -1 : 1) * parseFloat(e.substr(2)) + o), h && 1 === o && "hidden" === H(t, "visibility", r) && 0 !== e && (o = 0), Y ? s = new ce(l, "opacity", o, e - o, s) : (s = new ce(l, "opacity", 100 * o, 100 * (e - o), s), s.xn1 = h ? 1 : 0, l.zoom = 1, s.type = 2, s.b = "alpha(opacity=" + s.s + ")", s.e = "alpha(opacity=" + (s.s + s.c) + ")", s.data = t, s.plugin = a, s.setRatio = De), h && (s = new ce(l, "visibility", 0, 0, s, -1, null, !1, 0, 0 !== o ? "inherit" : "hidden", 0 === e ? "hidden" : "inherit"), s.xs0 = "inherit", n._overwriteProps.push(s.n), n._overwriteProps.push(i)), s
                }
            });
            var Ie = function(t, e) {
                    e && (t.removeProperty ? ("ms" === e.substr(0, 2) && (e = "M" + e.substr(1)), t.removeProperty(e.replace(P, "-$1").toLowerCase())) : t.removeAttribute(e))
                },
                Xe = function(t) {
                    if (this.t._gsClassPT = this, 1 === t || 0 === t) {
                        this.t.setAttribute("class", 0 === t ? this.b : this.e);
                        for (var e = this.data, i = this.t.style; e;) e.v ? i[e.p] = e.v : Ie(i, e.p), e = e._next;
                        1 === t && this.t._gsClassPT === this && (this.t._gsClassPT = null)
                    } else this.t.getAttribute("class") !== this.e && this.t.setAttribute("class", this.e)
                };
            me("className", {
                parser: function(t, e, n, s, a, o, l) {
                    var h, u, p, c, f, d = t.getAttribute("class") || "",
                        _ = t.style.cssText;
                    if (a = s._classNamePT = new ce(t, n, 0, 0, a, 2), a.setRatio = Xe, a.pr = -11, i = !0, a.b = d, u = Z(t, r), p = t._gsClassPT) {
                        for (c = {}, f = p.data; f;) c[f.p] = 1, f = f._next;
                        p.setRatio(1)
                    }
                    return t._gsClassPT = a, a.e = "=" !== e.charAt(1) ? e : d.replace(RegExp("\\s*\\b" + e.substr(2) + "\\b"), "") + ("+" === e.charAt(0) ? " " + e.substr(2) : ""), s._tween._duration && (t.setAttribute("class", a.e), h = $(t, u, Z(t), l, c), t.setAttribute("class", d), a.data = h.firstMPT, t.style.cssText = _, a = a.xfirst = s.parse(t, h.difs, a, o)), a
                }
            });
            var Ne = function(t) {
                if ((1 === t || 0 === t) && this.data._totalTime === this.data._totalDuration && "isFromStart" !== this.data.data) {
                    var e, i, n, r, s = this.t.style,
                        a = o.transform.parse;
                    if ("all" === this.e) s.cssText = "", r = !0;
                    else
                        for (e = this.e.split(" ").join("").split(","), n = e.length; --n > -1;) i = e[n], o[i] && (o[i].parse === a ? r = !0 : i = "transformOrigin" === i ? Te : o[i].p), Ie(s, i);
                    r && (Ie(s, xe), this.t._gsTransform && delete this.t._gsTransform)
                }
            };
            for (me("clearProps", {
                    parser: function(t, e, n, r, s) {
                        return s = new ce(t, n, 0, 0, s, 2), s.setRatio = Ne, s.e = e, s.pr = -10, s.data = r._tween, i = !0, s
                    }
                }), l = "bezier,throwProps,physicsProps,physics2D".split(","), de = l.length; de--;) ge(l[de]);
            l = a.prototype, l._firstPT = null, l._onInitTween = function(t, e, o) {
                if (!t.nodeType) return !1;
                this._target = t, this._tween = o, this._vars = e, h = e.autoRound, i = !1, n = e.suffixMap || a.suffixMap, r = W(t, ""), s = this._overwriteProps;
                var l, c, d, _, m, g, v, y, x, T = t.style;
                if (u && "" === T.zIndex && (l = H(t, "zIndex", r), ("auto" === l || "" === l) && this._addLazySet(T, "zIndex", 0)), "string" == typeof e && (_ = T.cssText, l = Z(t, r), T.cssText = _ + ";" + e, l = $(t, l, Z(t)).difs, !Y && w.test(e) && (l.opacity = parseFloat(RegExp.$1)), e = l, T.cssText = _), this._firstPT = c = this.parse(t, e, null), this._transformType) {
                    for (x = 3 === this._transformType, xe ? p && (u = !0, "" === T.zIndex && (v = H(t, "zIndex", r), ("auto" === v || "" === v) && this._addLazySet(T, "zIndex", 0)), f && this._addLazySet(T, "WebkitBackfaceVisibility", this._vars.WebkitBackfaceVisibility || (x ? "visible" : "hidden"))) : T.zoom = 1, d = c; d && d._next;) d = d._next;
                    y = new ce(t, "transform", 0, 0, null, 2), this._linkCSSP(y, null, d), y.setRatio = x && be ? Ee : xe ? Le : Me, y.data = this._transform || Re(t, r, !0), s.pop()
                }
                if (i) {
                    for (; c;) {
                        for (g = c._next, d = _; d && d.pr > c.pr;) d = d._next;
                        (c._prev = d ? d._prev : m) ? c._prev._next = c: _ = c, (c._next = d) ? d._prev = c : m = c, c = g
                    }
                    this._firstPT = _
                }
                return !0
            }, l.parse = function(t, e, i, s) {
                var a, l, u, p, c, f, d, _, m, g, v = t.style;
                for (a in e) f = e[a], l = o[a], l ? i = l.parse(t, f, a, this, i, s, e) : (c = H(t, a, r) + "", m = "string" == typeof f, "color" === a || "fill" === a || "stroke" === a || -1 !== a.indexOf("Color") || m && b.test(f) ? (m || (f = oe(f), f = (f.length > 3 ? "rgba(" : "rgb(") + f.join(",") + ")"), i = fe(v, a, c, f, !0, "transparent", i, 0, s)) : !m || -1 === f.indexOf(" ") && -1 === f.indexOf(",") ? (u = parseFloat(c), d = u || 0 === u ? c.substr((u + "").length) : "", ("" === c || "auto" === c) && ("width" === a || "height" === a ? (u = te(t, a, r), d = "px") : "left" === a || "top" === a ? (u = q(t, a, r), d = "px") : (u = "opacity" !== a ? 0 : 1, d = "")), g = m && "=" === f.charAt(1), g ? (p = parseInt(f.charAt(0) + "1", 10), f = f.substr(2), p *= parseFloat(f), _ = f.replace(y, "")) : (p = parseFloat(f), _ = m ? f.substr((p + "").length) || "" : ""), "" === _ && (_ = a in n ? n[a] : d), f = p || 0 === p ? (g ? p + u : p) + _ : e[a], d !== _ && "" !== _ && (p || 0 === p) && u && (u = Q(t, a, u, d), "%" === _ ? (u /= Q(t, a, 100, "%") / 100, e.strictUnits !== !0 && (c = u + "%")) : "em" === _ ? u /= Q(t, a, 1, "em") : "px" !== _ && (p = Q(t, a, p, _), _ = "px"), g && (p || 0 === p) && (f = p + u + _)), g && (p += u), !u && 0 !== u || !p && 0 !== p ? void 0 !== v[a] && (f || "NaN" != f + "" && null != f) ? (i = new ce(v, a, p || u || 0, 0, i, -1, a, !1, 0, c, f), i.xs0 = "none" !== f || "display" !== a && -1 === a.indexOf("Style") ? f : c) : B("invalid " + a + " tween value: " + e[a]) : (i = new ce(v, a, u, p - u, i, 0, a, h !== !1 && ("px" === _ || "zIndex" === a), 0, c, f), i.xs0 = _)) : i = fe(v, a, c, f, !0, null, i, 0, s)), s && i && !i.plugin && (i.plugin = s);
                return i
            }, l.setRatio = function(t) {
                var e, i, n, r = this._firstPT,
                    s = 1e-6;
                if (1 !== t || this._tween._time !== this._tween._duration && 0 !== this._tween._time)
                    if (t || this._tween._time !== this._tween._duration && 0 !== this._tween._time || this._tween._rawPrevTime === -1e-6)
                        for (; r;) {
                            if (e = r.c * t + r.s, r.r ? e = Math.round(e) : s > e && e > -s && (e = 0), r.type)
                                if (1 === r.type)
                                    if (n = r.l, 2 === n) r.t[r.p] = r.xs0 + e + r.xs1 + r.xn1 + r.xs2;
                                    else if (3 === n) r.t[r.p] = r.xs0 + e + r.xs1 + r.xn1 + r.xs2 + r.xn2 + r.xs3;
                            else if (4 === n) r.t[r.p] = r.xs0 + e + r.xs1 + r.xn1 + r.xs2 + r.xn2 + r.xs3 + r.xn3 + r.xs4;
                            else if (5 === n) r.t[r.p] = r.xs0 + e + r.xs1 + r.xn1 + r.xs2 + r.xn2 + r.xs3 + r.xn3 + r.xs4 + r.xn4 + r.xs5;
                            else {
                                for (i = r.xs0 + e + r.xs1, n = 1; r.l > n; n++) i += r["xn" + n] + r["xs" + (n + 1)];
                                r.t[r.p] = i
                            } else -1 === r.type ? r.t[r.p] = r.xs0 : r.setRatio && r.setRatio(t);
                            else r.t[r.p] = e + r.xs0;
                            r = r._next
                        } else
                            for (; r;) 2 !== r.type ? r.t[r.p] = r.b : r.setRatio(t), r = r._next;
                    else
                        for (; r;) 2 !== r.type ? r.t[r.p] = r.e : r.setRatio(t), r = r._next
            }, l._enableTransforms = function(t) {
                this._transform = this._transform || Re(this._target, r, !0), this._transformType = this._transform.svg && ve || !t && 3 !== this._transformType ? 2 : 3
            };
            var ze = function() {
                this.t[this.p] = this.e, this.data._linkCSSP(this, this._next, null, !0)
            };
            l._addLazySet = function(t, e, i) {
                var n = this._firstPT = new ce(t, e, 0, 0, this._firstPT, 2);
                n.e = i, n.setRatio = ze, n.data = this
            }, l._linkCSSP = function(t, e, i, n) {
                return t && (e && (e._prev = t), t._next && (t._next._prev = t._prev), t._prev ? t._prev._next = t._next : this._firstPT === t && (this._firstPT = t._next, n = !0), i ? i._next = t : n || null !== this._firstPT || (this._firstPT = t), t._next = e, t._prev = i), t
            }, l._kill = function(e) {
                var i, n, r, s = e;
                if (e.autoAlpha || e.alpha) {
                    s = {};
                    for (n in e) s[n] = e[n];
                    s.opacity = 1, s.autoAlpha && (s.visibility = 1)
                }
                return e.className && (i = this._classNamePT) && (r = i.xfirst, r && r._prev ? this._linkCSSP(r._prev, i._next, r._prev._prev) : r === this._firstPT && (this._firstPT = i._next), i._next && this._linkCSSP(i._next, i._next._next, r._prev), this._classNamePT = null), t.prototype._kill.call(this, s)
            };
            var Fe = function(t, e, i) {
                var n, r, s, a;
                if (t.slice)
                    for (r = t.length; --r > -1;) Fe(t[r], e, i);
                else
                    for (n = t.childNodes, r = n.length; --r > -1;) s = n[r], a = s.type, s.style && (e.push(Z(s)), i && i.push(s)), 1 !== a && 9 !== a && 11 !== a || !s.childNodes.length || Fe(s, e, i)
            };
            return a.cascadeTo = function(t, i, n) {
                var r, s, a, o = e.to(t, i, n),
                    l = [o],
                    h = [],
                    u = [],
                    p = [],
                    c = e._internals.reservedProps;
                for (t = o._targets || o.target, Fe(t, h, p), o.render(i, !0), Fe(t, u), o.render(0, !0), o._enabled(!0), r = p.length; --r > -1;)
                    if (s = $(p[r], h[r], u[r]), s.firstMPT) {
                        s = s.difs;
                        for (a in n) c[a] && (s[a] = n[a]);
                        l.push(e.to(p[r], i, s))
                    }
                return l
            }, t.activate([a]), a
        }, !0)
    }), _gsScope._gsDefine && _gsScope._gsQueue.pop()(),
    function(t) {
        "use strict";
        var e = function() {
            return (_gsScope.GreenSockGlobals || _gsScope)[t]
        };
        "function" == typeof define && define.amd ? define(["TweenLite"], e) : "undefined" != typeof module && module.exports && (require("../TweenLite.js"), module.exports = e())
    }("CSSPlugin");
var _gsScope = "undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window;
! function(t) {
    "use strict";
    var e = t.GreenSockGlobals || t,
        i = function(t) {
            var i, n = t.split("."),
                r = e;
            for (i = 0; n.length > i; i++) r[n[i]] = r = r[n[i]] || {};
            return r
        },
        n = i("com.greensock.utils"),
        r = function(t) {
            var e = t.nodeType,
                i = "";
            if (1 === e || 9 === e || 11 === e) {
                if ("string" == typeof t.textContent) return t.textContent;
                for (t = t.firstChild; t; t = t.nextSibling) i += r(t)
            } else if (3 === e || 4 === e) return t.nodeValue;
            return i
        },
        s = document,
        a = s.defaultView ? s.defaultView.getComputedStyle : function() {},
        o = /([A-Z])/g,
        l = function(t, e, i, n) {
            var r;
            return (i = i || a(t, null)) ? (t = i.getPropertyValue(e.replace(o, "-$1").toLowerCase()), r = t || i.length ? t : i[e]) : t.currentStyle && (i = t.currentStyle, r = i[e]), n ? r : parseInt(r, 10) || 0
        },
        h = function(t) {
            return t.length && t[0] && (t[0].nodeType && t[0].style && !t.nodeType || t[0].length && t[0][0]) ? !0 : !1
        },
        u = function(t) {
            var e, i, n, r = [],
                s = t.length;
            for (e = 0; s > e; e++)
                if (i = t[e], h(i))
                    for (n = i.length, n = 0; i.length > n; n++) r.push(i[n]);
                else r.push(i);
            return r
        },
        p = ")eefec303079ad17405c",
        c = /(?:<br>|<br\/>|<br \/>)/gi,
        f = s.all && !s.addEventListener,
        d = "<div style='position:relative;display:inline-block;" + (f ? "*display:inline;*zoom:1;'" : "'"),
        _ = function(t) {
            t = t || "";
            var e = -1 !== t.indexOf("++"),
                i = 1;
            return e && (t = t.split("++").join("")),
                function() {
                    return d + (t ? " class='" + t + (e ? i++ : "") + "'>" : ">")
                }
        },
        m = n.SplitText = e.SplitText = function(t, e) {
            if ("string" == typeof t && (t = m.selector(t)), !t) throw "cannot split a null element.";
            this.elements = h(t) ? u(t) : [t], this.chars = [], this.words = [], this.lines = [], this._originals = [], this.vars = e || {}, this.split(e)
        },
        g = function(t, e, i) {
            var n = t.nodeType;
            if (1 === n || 9 === n || 11 === n)
                for (t = t.firstChild; t; t = t.nextSibling) g(t, e, i);
            else(3 === n || 4 === n) && (t.nodeValue = t.nodeValue.split(e).join(i))
        },
        v = function(t, e) {
            for (var i = e.length; --i > -1;) t.push(e[i])
        },
        y = function(t, e, i, n, o) {
            c.test(t.innerHTML) && (t.innerHTML = t.innerHTML.replace(c, p));
            var h, u, f, d, m, y, x, w, T, b, P, S, O, k, C = r(t),
                A = e.type || e.split || "chars,words,lines",
                R = -1 !== A.indexOf("lines") ? [] : null,
                M = -1 !== A.indexOf("words"),
                E = -1 !== A.indexOf("chars"),
                L = "absolute" === e.position || e.absolute === !0,
                D = L ? "&#173; " : " ",
                I = -999,
                X = a(t),
                N = l(t, "paddingLeft", X),
                z = l(t, "borderBottomWidth", X) + l(t, "borderTopWidth", X),
                F = l(t, "borderLeftWidth", X) + l(t, "borderRightWidth", X),
                Y = l(t, "paddingTop", X) + l(t, "paddingBottom", X),
                j = l(t, "paddingLeft", X) + l(t, "paddingRight", X),
                B = l(t, "textAlign", X, !0),
                G = t.clientHeight,
                U = t.clientWidth,
                V = "</div>",
                W = _(e.wordsClass),
                H = _(e.charsClass),
                Q = -1 !== (e.linesClass || "").indexOf("++"),
                q = e.linesClass,
                Z = -1 !== C.indexOf("<"),
                $ = !0,
                K = [],
                J = [],
                te = [];
            for (Q && (q = q.split("++").join("")), Z && (C = C.split("<").join("{{LT}}")), h = C.length, d = W(), m = 0; h > m; m++)
                if (x = C.charAt(m), ")" === x && C.substr(m, 20) === p) d += ($ ? V : "") + "<BR/>", $ = !1, m !== h - 20 && C.substr(m + 20, 20) !== p && (d += " " + W(), $ = !0), m += 19;
                else if (" " === x && " " !== C.charAt(m - 1) && m !== h - 1 && C.substr(m - 20, 20) !== p) {
                for (d += $ ? V : "", $ = !1;
                    " " === C.charAt(m + 1);) d += D, m++;
                (")" !== C.charAt(m + 1) || C.substr(m + 1, 20) !== p) && (d += D + W(), $ = !0)
            } else d += E && " " !== x ? H() + x + "</div>" : x;
            for (t.innerHTML = d + ($ ? V : ""), Z && g(t, "{{LT}}", "<"), y = t.getElementsByTagName("*"), h = y.length, w = [], m = 0; h > m; m++) w[m] = y[m];
            if (R || L)
                for (m = 0; h > m; m++) T = w[m], f = T.parentNode === t, (f || L || E && !M) && (b = T.offsetTop, R && f && b !== I && "BR" !== T.nodeName && (u = [], R.push(u), I = b), L && (T._x = T.offsetLeft, T._y = b, T._w = T.offsetWidth, T._h = T.offsetHeight), R && (M !== f && E || (u.push(T), T._x -= N), f && m && (w[m - 1]._wordEnd = !0), "BR" === T.nodeName && T.nextSibling && "BR" === T.nextSibling.nodeName && R.push([])));
            for (m = 0; h > m; m++) T = w[m], f = T.parentNode === t, "BR" !== T.nodeName ? (L && (S = T.style, M || f || (T._x += T.parentNode._x, T._y += T.parentNode._y), S.left = T._x + "px", S.top = T._y + "px", S.position = "absolute", S.display = "block", S.width = T._w + 1 + "px", S.height = T._h + "px"), M ? f && "" !== T.innerHTML ? J.push(T) : E && K.push(T) : f ? (t.removeChild(T), w.splice(m--, 1), h--) : !f && E && (b = !R && !L && T.nextSibling, t.appendChild(T), b || t.appendChild(s.createTextNode(" ")), K.push(T))) : R || L ? (t.removeChild(T), w.splice(m--, 1), h--) : M || t.appendChild(T);
            if (R) {
                for (L && (P = s.createElement("div"), t.appendChild(P), O = P.offsetWidth + "px", b = P.offsetParent === t ? 0 : t.offsetLeft, t.removeChild(P)), S = t.style.cssText, t.style.cssText = "display:none;"; t.firstChild;) t.removeChild(t.firstChild);
                for (k = !L || !M && !E, m = 0; R.length > m; m++) {
                    for (u = R[m], P = s.createElement("div"), P.style.cssText = "display:block;text-align:" + B + ";position:" + (L ? "absolute;" : "relative;"), q && (P.className = q + (Q ? m + 1 : "")), te.push(P), h = u.length, y = 0; h > y; y++) "BR" !== u[y].nodeName && (T = u[y], P.appendChild(T), k && (T._wordEnd || M) && P.appendChild(s.createTextNode(" ")), L && (0 === y && (P.style.top = T._y + "px", P.style.left = N + b + "px"), T.style.top = "0px", b && (T.style.left = T._x - b + "px")));
                    0 === h && (P.innerHTML = "&nbsp;"), M || E || (P.innerHTML = r(P).split(String.fromCharCode(160)).join(" ")), L && (P.style.width = O, P.style.height = T._h + "px"), t.appendChild(P)
                }
                t.style.cssText = S
            }
            L && (G > t.clientHeight && (t.style.height = G - Y + "px", G > t.clientHeight && (t.style.height = G + z + "px")), U > t.clientWidth && (t.style.width = U - j + "px", U > t.clientWidth && (t.style.width = U + F + "px"))), v(i, K), v(n, J), v(o, te)
        },
        x = m.prototype;
    x.split = function(t) {
        this.isSplit && this.revert(), this.vars = t || this.vars, this._originals.length = this.chars.length = this.words.length = this.lines.length = 0;
        for (var e = this.elements.length; --e > -1;) this._originals[e] = this.elements[e].innerHTML, y(this.elements[e], this.vars, this.chars, this.words, this.lines);
        return this.chars.reverse(), this.words.reverse(), this.lines.reverse(), this.isSplit = !0, this
    }, x.revert = function() {
        if (!this._originals) throw "revert() call wasn't scoped properly.";
        for (var t = this._originals.length; --t > -1;) this.elements[t].innerHTML = this._originals[t];
        return this.chars = [], this.words = [], this.lines = [], this.isSplit = !1, this
    }, m.selector = t.$ || t.jQuery || function(e) {
        var i = t.$ || t.jQuery;
        return i ? (m.selector = i, i(e)) : "undefined" == typeof document ? e : document.querySelectorAll ? document.querySelectorAll(e) : document.getElementById("#" === e.charAt(0) ? e.substr(1) : e)
    }, m.version = "0.3.3"
}(_gsScope),
function(t) {
    "use strict";
    var e = function() {
        return (_gsScope.GreenSockGlobals || _gsScope)[t]
    };
    "function" == typeof define && define.amd ? define(["TweenLite"], e) : "undefined" != typeof module && module.exports && (module.exports = e())
}("SplitText");
try {
    window.GreenSockGlobals = null, window._gsQueue = null, window._gsDefine = null, delete window.GreenSockGlobals, delete window._gsQueue, delete window._gsDefine
} catch (e) {}
try {
    window.GreenSockGlobals = oldgs, window._gsQueue = oldgs_queue
} catch (e) {}
if (1 == window.tplogs) try {
    console.groupEnd()
} catch (e) {}! function(t) {
    t.waitForImages = {
        hasImageProperties: ["backgroundImage", "listStyleImage", "borderImage", "borderCornerImage"]
    }, t.expr[":"].uncached = function(e) {
        var i = document.createElement("img");
        return i.src = e.src, t(e).is('img[src!=""]') && !i.complete
    }, t.fn.waitForImages = function(e, i, n) {
        if (t.isPlainObject(arguments[0]) && (i = e.each, n = e.waitForAll, e = e.finished), e = e || t.noop, i = i || t.noop, n = !!n, !t.isFunction(e) || !t.isFunction(i)) throw new TypeError("An invalid callback was supplied.");
        return this.each(function() {
            var r = t(this),
                s = [];
            if (n) {
                var a = t.waitForImages.hasImageProperties || [],
                    o = /url\((['"]?)(.*?)\1\)/g;
                r.find("*").each(function() {
                    var e = t(this);
                    e.is("img:uncached") && s.push({
                        src: e.attr("src"),
                        element: e[0]
                    }), t.each(a, function(t, i) {
                        var n = e.css(i);
                        if (!n) return !0;
                        for (var r; r = o.exec(n);) s.push({
                            src: r[2],
                            element: e[0]
                        })
                    })
                })
            } else r.find("img:uncached").each(function() {
                s.push({
                    src: this.src,
                    element: this
                })
            });
            var l = s.length,
                h = 0;
            0 == l && e.call(r[0]), t.each(s, function(n, s) {
                var a = new Image;
                t(a).bind("load error", function(t) {
                    return h++, i.call(s.element, h, l, "load" == t.type), h == l ? (e.call(r[0]), !1) : void 0
                }), a.src = s.src
            })
        })
    }
}(jQuery);

function revslider_showDoubleJqueryError(t) {
    var e = "Revolution Slider Error: You have some jquery.js library include that comes after the revolution files js include.";
    e += "<br> This includes make eliminates the revolution slider libraries, and make it not work.", e += "<br><br> To fix it you can:<br>&nbsp;&nbsp;&nbsp; 1. In the Slider Settings -> Troubleshooting set option:  <strong><b>Put JS Includes To Body</b></strong> option to true.", e += "<br>&nbsp;&nbsp;&nbsp; 2. Find the double jquery.js include and remove it.", e = "<span style='font-size:16px;color:#BC0C06;'>" + e + "</span>", jQuery(t).show().html(e)
}! function(t, e) {
    function a() {
        var t = !1;
        return navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i) ? navigator.userAgent.match(/OS 4_\d like Mac OS X/i) && (t = !0) : t = !1, t
    }

    function i(i, d) {
        if (i == e) return !1;
        if (i.data("aimg") != e && ("enabled" == i.data("aie8") && s(8) || "enabled" == i.data("amobile") && Z()) && i.html('<img class="tp-slider-alternative-image" src="' + i.data("aimg") + '">'), ("preview1" == d.navigationStyle || "preview3" == d.navigationStyle || "preview4" == d.navigationStyle) && (d.soloArrowLeftHalign = "left", d.soloArrowLeftValign = "center", d.soloArrowLeftHOffset = 0, d.soloArrowLeftVOffset = 0, d.soloArrowRightHalign = "right", d.soloArrowRightValign = "center", d.soloArrowRightHOffset = 0, d.soloArrowRightVOffset = 0, d.navigationArrows = "solo"), "on" == d.simplifyAll && (s(8) || a()) && (i.find(".tp-caption").each(function() {
                var e = t(this);
                e.removeClass("customin").removeClass("customout").addClass("fadein").addClass("fadeout"), e.data("splitin", ""), e.data("speed", 400)
            }), i.find(">ul>li").each(function() {
                var e = t(this);
                e.data("transition", "fade"), e.data("masterspeed", 500), e.data("slotamount", 1);
                var a = e.find(">img").first();
                a.data("kenburns", "off")
            })), d.desktop = !navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|BB10|mobi|tablet|opera mini|nexus 7)/i), "on" != d.fullWidth && "on" != d.fullScreen && (d.autoHeight = "off"), "on" == d.fullScreen && (d.autoHeight = "on"), "on" != d.fullWidth && "on" != d.fullScreen && (forceFulWidth = "off"), "on" == d.fullWidth && "off" == d.autoHeight && i.css({
                maxHeight: d.startheight + "px"
            }), Z() && "on" == d.hideThumbsOnMobile && "thumb" == d.navigationType && (d.navigationType = "none"), Z() && "on" == d.hideBulletsOnMobile && "bullet" == d.navigationType && (d.navigationType = "none"), Z() && "on" == d.hideBulletsOnMobile && "both" == d.navigationType && (d.navigationType = "none"), Z() && "on" == d.hideArrowsOnMobile && (d.navigationArrows = "none"), "on" == d.forceFullWidth && 0 == i.closest(".forcefullwidth_wrapper_tp_banner").length) {
            var f = i.parent().offset().left,
                g = i.parent().css("marginBottom"),
                m = i.parent().css("marginTop");
            g == e && (g = 0), m == e && (m = 0), i.parent().wrap('<div style="position:relative;width:100%;height:auto;margin-top:' + m + ";margin-bottom:" + g + '" class="forcefullwidth_wrapper_tp_banner"></div>'), i.closest(".forcefullwidth_wrapper_tp_banner").append('<div class="tp-fullwidth-forcer" style="width:100%;height:' + i.height() + 'px"></div>'), i.css({
                backgroundColor: i.parent().css("backgroundColor"),
                backgroundImage: i.parent().css("backgroundImage")
            }), i.parent().css({
                left: 0 - f + "px",
                position: "absolute",
                width: t(window).width()
            }), d.width = t(window).width()
        }
        try {
            i.parent().find(".tp-bullets.tp-thumbs").css(d.hideThumbsUnderResolution > t(window).width() && 0 != d.hideThumbsUnderResolution ? {
                display: "none"
            } : {
                display: "block"
            })
        } catch (w) {}
        if (!i.hasClass("revslider-initialised")) {
            i.addClass("revslider-initialised"), i.attr("id") == e && i.attr("id", "revslider-" + Math.round(1e3 * Math.random() + 5)), d.firefox13 = !1, d.ie = !t.support.opacity, d.ie9 = 9 == document.documentMode, d.origcd = d.delay; {
                var b = t.fn.jquery.split("."),
                    y = parseFloat(b[0]),
                    x = parseFloat(b[1]);
                parseFloat(b[2] || "0")
            }
            1 == y && 7 > x && i.html('<div style="text-align:center; padding:40px 0px; font-size:20px; color:#992222;"> The Current Version of jQuery:' + b + " <br>Please update your jQuery Version to min. 1.7 in Case you wish to use the Revolution Slider Plugin</div>"), y > 1 && (d.ie = !1), t.support.transition || (t.fn.transition = t.fn.animate), i.find(".caption").each(function() {
                t(this).addClass("tp-caption")
            }), Z() && i.find(".tp-caption").each(function() {
                var e = t(this);
                (1 == e.data("autoplayonlyfirsttime") || "true" == e.data("autoplayonlyfirsttime")) && e.data("autoplayonlyfirsttime", "false"), (1 == e.data("autoplay") || "true" == e.data("autoplay")) && e.data("autoplay", !1)
            });
            var T = 0,
                k = 0,
                C = "http";
            if ("https:" === location.protocol && (C = "https"), i.find(".tp-caption").each(function() {
                    try {
                        if ((t(this).data("ytid") != e || t(this).find("iframe").attr("src").toLowerCase().indexOf("youtube") > 0) && 0 == T) {
                            T = 1;
                            var a = document.createElement("script"),
                                i = "https";
                            a.src = i + "://www.youtube.com/iframe_api";
                            var n = document.getElementsByTagName("script")[0],
                                o = !0;
                            t("head").find("*").each(function() {
                                t(this).attr("src") == i + "://www.youtube.com/iframe_api" && (o = !1)
                            }), o && n.parentNode.insertBefore(a, n)
                        }
                    } catch (r) {}
                    try {
                        if ((t(this).data("vimeoid") != e || t(this).find("iframe").attr("src").toLowerCase().indexOf("vimeo") > 0) && 0 == k) {
                            k = 1;
                            var s = document.createElement("script");
                            s.src = C + "://a.vimeocdn.com/js/froogaloop2.min.js";
                            var n = document.getElementsByTagName("script")[0],
                                o = !0;
                            t("head").find("*").each(function() {
                                t(this).attr("src") == C + "://a.vimeocdn.com/js/froogaloop2.min.js" && (o = !1)
                            }), o && n.parentNode.insertBefore(s, n)
                        }
                    } catch (r) {}
                    try {
                        t(this).data("videomp4") != e || t(this).data("videowebm") != e
                    } catch (r) {}
                }), i.find(".tp-caption video").each(function() {
                    t(this).removeClass("video-js").removeClass("vjs-default-skin"), t(this).attr("preload", ""), t(this).css({
                        display: "none"
                    })
                }), i.find(">ul:first-child >li").each(function() {
                    var e = t(this);
                    e.data("origindex", e.index())
                }), "on" == d.shuffle) {
                var z = new Object,
                    O = i.find(">ul:first-child >li:first-child");
                z.fstransition = O.data("fstransition"), z.fsmasterspeed = O.data("fsmasterspeed"), z.fsslotamount = O.data("fsslotamount");
                for (var I = 0; I < i.find(">ul:first-child >li").length; I++) {
                    var A = Math.round(Math.random() * i.find(">ul:first-child >li").length);
                    i.find(">ul:first-child >li:eq(" + A + ")").prependTo(i.find(">ul:first-child"))
                }
                var M = i.find(">ul:first-child >li:first-child");
                M.data("fstransition", z.fstransition), M.data("fsmasterspeed", z.fsmasterspeed), M.data("fsslotamount", z.fsslotamount)
            }
            d.slots = 4, d.act = -1, d.next = 0, d.startWithSlide != e && (d.next = d.startWithSlide);
            var S = o("#")[0];
            if (S.length < 9 && S.split("slide").length > 1) {
                var P = parseInt(S.split("slide")[1], 0);
                1 > P && (P = 1), P > i.find(">ul:first >li").length && (P = i.find(">ul:first >li").length), d.next = P - 1
            }
            d.firststart = 1, d.navigationHOffset == e && (d.navOffsetHorizontal = 0), d.navigationVOffset == e && (d.navOffsetVertical = 0), i.append('<div class="tp-loader ' + d.spinner + '"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>'), 0 == i.find(".tp-bannertimer").length && i.append('<div class="tp-bannertimer" style="visibility:hidden"></div>');
            var D = i.find(".tp-bannertimer");
            if (D.length > 0 && D.css({
                    width: "0%"
                }), i.addClass("tp-simpleresponsive"), d.container = i, d.slideamount = i.find(">ul:first >li").length, 0 == i.height() && i.height(d.startheight), (d.startwidth == e || 0 == d.startwidth) && (d.startwidth = i.width()), (d.startheight == e || 0 == d.startheight) && (d.startheight = i.height()), d.width = i.width(), d.height = i.height(), d.bw = d.startwidth / i.width(), d.bh = d.startheight / i.height(), d.width != d.startwidth && (d.height = Math.round(d.startheight * (d.width / d.startwidth)), i.height(d.height)), 0 != d.shadow) {
                i.parent().append('<div class="tp-bannershadow tp-shadow' + d.shadow + '"></div>');
                var f = 0;
                "on" == d.forceFullWidth && (f = 0 - d.container.parent().offset().left), i.parent().find(".tp-bannershadow").css({
                    width: d.width,
                    left: f
                })
            }
            i.find("ul").css({
                display: "none"
            });
            i.find("ul").css({
                display: "block"
            }), v(i, d), "off" != d.parallax && Q(i, d), d.slideamount > 1 && l(i, d), d.slideamount > 1 && "thumb" == d.navigationType && te(i, d), d.slideamount > 1 && h(i, d), "on" == d.keyboardNavigation && c(i, d), p(i, d), d.hideThumbs > 0 && u(i, d), setTimeout(function() {
                L(i, d)
            }, d.startDelay), d.startDelay = 0, d.slideamount > 1 && j(i, d), setTimeout(function() {
                i.trigger("revolution.slide.onloaded")
            }, 500), t("body").data("rs-fullScreenMode", !1), t(window).on("mozfullscreenchange webkitfullscreenchange fullscreenchange", function() {
                t("body").data("rs-fullScreenMode", !t("body").data("rs-fullScreenMode")), t("body").data("rs-fullScreenMode") && setTimeout(function() {
                    t(window).trigger("resize")
                }, 200)
            });
            var H = "resize.revslider-" + i.attr("id");
            t(window).on(H, function() {
                if (i == e) return !1;
                if (0 != t("body").find(i) && "on" == d.forceFullWidth) {
                    var a = d.container.closest(".forcefullwidth_wrapper_tp_banner").offset().left;
                    d.container.parent().css({
                        left: 0 - a + "px",
                        width: t(window).width()
                    })
                }(i.outerWidth(!0) != d.width || i.is(":hidden")) && r(i, d)
            });
            try {
                0 != d.hideThumbsUnderResoluition && "thumb" == d.navigationType && t(".tp-bullets").css(d.hideThumbsUnderResoluition > t(window).width() ? {
                    display: "none"
                } : {
                    display: "block"
                })
            } catch (w) {}
            i.find(".tp-scrollbelowslider").on("click", function() {
                var e = 0;
                try {
                    e = t("body").find(d.fullScreenOffsetContainer).height()
                } catch (a) {}
                try {
                    e -= parseInt(t(this).data("scrolloffset"), 0)
                } catch (a) {}
                t("body,html").animate({
                    scrollTop: i.offset().top + i.find(">ul >li").height() - e + "px"
                }, {
                    duration: 400
                })
            });
            var W = i.parent();
            t(window).width() < d.hideSliderAtLimit && (i.trigger("stoptimer"), "none" != W.css("display") && W.data("olddisplay", W.css("display")), W.css({
                display: "none"
            })), n(i, d)
        }
    }
    t.fn.extend({
        revolution: function(a) {
            var n = {
                delay: 9e3,
                startheight: 500,
                startwidth: 960,
                fullScreenAlignForce: "off",
                autoHeight: "off",
                hideTimerBar: "off",
                hideThumbs: 200,
                hideNavDelayOnMobile: 1500,
                thumbWidth: 100,
                thumbHeight: 50,
                thumbAmount: 3,
                navigationType: "bullet",
                navigationArrows: "solo",
                navigationInGrid: "off",
                hideThumbsOnMobile: "off",
                hideBulletsOnMobile: "off",
                hideArrowsOnMobile: "off",
                hideThumbsUnderResoluition: 0,
                navigationStyle: "round",
                navigationHAlign: "center",
                navigationVAlign: "bottom",
                navigationHOffset: 0,
                navigationVOffset: 20,
                soloArrowLeftHalign: "left",
                soloArrowLeftValign: "center",
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,
                soloArrowRightHalign: "right",
                soloArrowRightValign: "center",
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,
                keyboardNavigation: "on",
                touchenabled: "on",
                onHoverStop: "on",
                stopAtSlide: -1,
                stopAfterLoops: -1,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLimit: 0,
                hideSliderAtLimit: 0,
                shadow: 0,
                fullWidth: "off",
                fullScreen: "off",
                minFullScreenHeight: 0,
                fullScreenOffsetContainer: "",
                fullScreenOffset: "0",
                dottedOverlay: "none",
                forceFullWidth: "off",

                spinner: "spinner0",
                swipe_treshold: 75,
                swipe_min_touches: 1,
                drag_block_vertical: !1,
                isJoomla: !1,
                parallax: "off",
                parallaxLevels: [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85],
                parallaxBgFreeze: "off",
                parallaxOpacity: "on",
                parallaxDisableOnMobile: "off",
                panZoomDisableOnMobile: "off",
                simplifyAll: "on",
                minHeight: 0,
                nextSlideOnWindowFocus: "off",
                startDelay: 0
            };
            return a = t.extend({}, n, a), this.each(function() {
                if (1 == window.tplogs) try {
                    console.groupCollapsed("Slider Revolution 4.6.3 Initialisation on " + t(this).attr("id")), console.groupCollapsed("Used Options:"), console.info(a), console.groupEnd(), console.groupCollapsed("Tween Engine:")
                } catch (n) {}
                if (punchgs.TweenLite == e) {
                    if (1 == window.tplogs) try {
                        console.error("GreenSock Engine Does not Exist!")
                    } catch (n) {}
                    return !1
                }
                if (punchgs.force3D = !0, 1 == window.tplogs) try {
                    console.info("GreenSock Engine Version in Slider Revolution:" + punchgs.TweenLite.version)
                } catch (n) {}
                if ("on" == a.simplifyAll || (punchgs.TweenLite.lagSmoothing(1e3, 16), punchgs.force3D = "true"), 1 == window.tplogs) try {
                    console.groupEnd(), console.groupEnd()
                } catch (n) {}
                i(t(this), a)
            })
        },
        revscroll: function(a) {
            return this.each(function() {
                var i = t(this);
                i != e && i.length > 0 && t("body").find("#" + i.attr("id")).length > 0 && t("body,html").animate({
                    scrollTop: i.offset().top + i.find(">ul >li").height() - a + "px"
                }, {
                    duration: 400
                })
            })
        },
        revredraw: function() {
            return this.each(function() {
                var a = t(this);
                if (a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0) {
                    var i = a.parent().find(".tp-bannertimer"),
                        n = i.data("opt");
                    r(a, n)
                }
            })
        },
        revkill: function() {
            var a = this,
                i = t(this);
            if (i != e && i.length > 0 && t("body").find("#" + i.attr("id")).length > 0) {
                i.data("conthover", 1), i.data("conthover-changed", 1), i.trigger("revolution.slide.onpause");
                var n = i.parent().find(".tp-bannertimer"),
                    o = n.data("opt");
                o.bannertimeronpause = !0, i.trigger("stoptimer"), punchgs.TweenLite.killTweensOf(i.find("*"), !1), punchgs.TweenLite.killTweensOf(i, !1), i.unbind("hover, mouseover, mouseenter,mouseleave, resize");
                var r = "resize.revslider-" + i.attr("id");
                t(window).off(r), i.find("*").each(function() {
                    var a = t(this);
                    a.unbind("on, hover, mouseenter,mouseleave,mouseover, resize,restarttimer, stoptimer"), a.off("on, hover, mouseenter,mouseleave,mouseover, resize"), a.data("mySplitText", null), a.data("ctl", null), a.data("tween") != e && a.data("tween").kill(), a.data("kenburn") != e && a.data("kenburn").kill(), a.remove(), a.empty(), a = null
                }), punchgs.TweenLite.killTweensOf(i.find("*"), !1), punchgs.TweenLite.killTweensOf(i, !1), n.remove();
                try {
                    i.closest(".forcefullwidth_wrapper_tp_banner").remove()
                } catch (s) {}
                try {
                    i.closest(".rev_slider_wrapper").remove()
                } catch (s) {}
                try {
                    i.remove()
                } catch (s) {}
                return i.empty(), i.html(), i = null, o = null, delete a.container, delete a.opt, !0
            }
            return !1
        },
        revpause: function() {
            return this.each(function() {
                var a = t(this);
                if (a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0) {
                    a.data("conthover", 1), a.data("conthover-changed", 1), a.trigger("revolution.slide.onpause");
                    var i = a.parent().find(".tp-bannertimer"),
                        n = i.data("opt");
                    n.bannertimeronpause = !0, a.trigger("stoptimer")
                }
            })
        },
        revresume: function() {
            return this.each(function() {
                var a = t(this);
                if (a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0) {
                    a.data("conthover", 0), a.data("conthover-changed", 1), a.trigger("revolution.slide.onresume");
                    var i = a.parent().find(".tp-bannertimer"),
                        n = i.data("opt");
                    n.bannertimeronpause = !1, a.trigger("starttimer")
                }
            })
        },
        revnext: function() {
            return this.each(function() {
                var a = t(this);
                a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0 && a.parent().find(".tp-rightarrow").click()
            })
        },
        revprev: function() {
            return this.each(function() {
                var a = t(this);
                a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0 && a.parent().find(".tp-leftarrow").click()
            })
        },
        revmaxslide: function() {
            return t(this).find(">ul:first-child >li").length
        },
        revcurrentslide: function() {
            var a = t(this);
            if (a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0) {
                var i = a.parent().find(".tp-bannertimer"),
                    n = i.data("opt");
                return n.act
            }
        },
        revlastslide: function() {
            var a = t(this);
            if (a != e && a.length > 0 && t("body").find("#" + a.attr("id")).length > 0) {
                var i = a.parent().find(".tp-bannertimer"),
                    n = i.data("opt");
                return n.lastslide
            }
        },
        revshowslide: function(a) {
            return this.each(function() {
                var i = t(this);
                i != e && i.length > 0 && t("body").find("#" + i.attr("id")).length > 0 && (i.data("showus", a), i.parent().find(".tp-rightarrow").click())
            })
        }
    });
    var n = (function() {
            var t, e, a = {
                hidden: "visibilitychange",
                webkitHidden: "webkitvisibilitychange",
                mozHidden: "mozvisibilitychange",
                msHidden: "msvisibilitychange"
            };
            for (t in a)
                if (t in document) {
                    e = a[t];
                    break
                }
            return function(a) {
                return a && document.addEventListener(e, a), !document[t]
            }
        }(), function(a, i) {
            var n = document.documentMode === e,
                o = window.chrome;
            n && !o ? t(window).on("focusin", function() {
                return a == e ? !1 : void setTimeout(function() {
                    "on" == i.nextSlideOnWindowFocus && a.revnext(), a.revredraw()
                }, 300)
            }).on("focusout", function() {}) : window.addEventListener ? (window.addEventListener("focus", function() {
                return a == e ? !1 : void setTimeout(function() {
                    "on" == i.nextSlideOnWindowFocus && a.revnext(), a.revredraw()
                }, 300)
            }, !1), window.addEventListener("blur", function() {}, !1)) : (window.attachEvent("focus", function() {
                setTimeout(function() {
                    return a == e ? !1 : ("on" == i.nextSlideOnWindowFocus && a.revnext(), void a.revredraw())
                }, 300)
            }), window.attachEvent("blur", function() {}))
        }),
        o = function(t) {
            for (var e, a = [], i = window.location.href.slice(window.location.href.indexOf(t) + 1).split("_"), n = 0; n < i.length; n++) i[n] = i[n].replace("%3D", "="), e = i[n].split("="), a.push(e[0]), a[e[0]] = e[1];
            return a
        },
        r = function(a, i) {
            if (a == e) return !1;
            try {
                0 != i.hideThumbsUnderResoluition && "thumb" == i.navigationType && t(".tp-bullets").css(i.hideThumbsUnderResoluition > t(window).width() ? {
                    display: "none"
                } : {
                    display: "block"
                })
            } catch (n) {}
            a.find(".defaultimg").each(function() {
                m(t(this), i)
            });
            var o = a.parent();
            t(window).width() < i.hideSliderAtLimit ? (a.trigger("stoptimer"), "none" != o.css("display") && o.data("olddisplay", o.css("display")), o.css({
                display: "none"
            })) : a.is(":hidden") && (o.css(o.data("olddisplay") != e && "undefined" != o.data("olddisplay") && "none" != o.data("olddisplay") ? {
                display: o.data("olddisplay")
            } : {
                display: "block"
            }), a.trigger("restarttimer"), setTimeout(function() {
                r(a, i)
            }, 150));
            var s = 0;
            "on" == i.forceFullWidth && (s = 0 - i.container.parent().offset().left);
            try {
                a.parent().find(".tp-bannershadow").css({
                    width: i.width,
                    left: s
                })
            } catch (n) {}
            var d = a.find(">ul >li:eq(" + i.act + ") .slotholder"),
                l = a.find(">ul >li:eq(" + i.next + ") .slotholder");
            y(a, i, a), punchgs.TweenLite.set(l.find(".defaultimg"), {
                opacity: 0
            }), d.find(".defaultimg").css({
                opacity: 1
            }), l.find(".defaultimg").each(function() {
                var n = t(this);
                "on" == i.panZoomDisableOnMobile || n.data("kenburn") != e && (n.data("kenburn").restart(), N(a, i, !0))
            });
            var h = a.find(">ul >li:eq(" + i.next + ")"),
                c = a.parent().find(".tparrows");
            c.hasClass("preview2") && c.css({
                width: parseInt(c.css("minWidth"), 0)
            }), _(h, i, !0), f(a, i)
        },
        s = function(e, a) {
            var i = t('<div style="display:none;"/>').appendTo(t("body"));
            i.html("<!--[if " + (a || "") + " IE " + (e || "") + "]><a>&nbsp;</a><![endif]-->");
            var n = i.find("a").length;
            return i.remove(), n
        },
        d = function(t, e) {
            t.next == e.find(">ul >li").length - 1 && (t.looptogo = t.looptogo - 1, t.looptogo <= 0 && (t.stopLoop = "on")), L(e, t)
        },
        l = function(e, a) {
            var i = "hidebullets";
            0 == a.hideThumbs && (i = ""), ("bullet" == a.navigationType || "both" == a.navigationType) && e.parent().append('<div class="tp-bullets ' + i + " simplebullets " + a.navigationStyle + '"></div>');
            var n = e.parent().find(".tp-bullets");
            e.find(">ul:first >li").each(function(t) {
                e.find(">ul:first >li:eq(" + t + ") img:first").attr("src");
                n.append('<div class="bullet"></div>');
                n.find(".bullet:first")
            }), n.find(".bullet").each(function(i) {
                var n = t(this);
                i == a.slideamount - 1 && n.addClass("last"), 0 == i && n.addClass("first"), n.click(function() {
                    var t = !1,
                        i = n.index();
                    ("withbullet" == a.navigationArrows || "nexttobullets" == a.navigationArrows) && (i = n.index() - 1), i == a.act && (t = !0), 0 != a.transition || t || (a.next = i, d(a, e))
                })
            }), n.append('<div class="tpclear"></div>'), f(e, a)
        },
        h = function(t, a) {
            function i(e) {
                t.parent().append('<div style="' + n + '" class="tp-' + e + "arrow " + o + " tparrows " + r + '"><div class="tp-arr-allwrapper"><div class="tp-arr-iwrapper"><div class="tp-arr-imgholder"></div><div class="tp-arr-imgholder2"></div><div class="tp-arr-titleholder"></div><div class="tp-arr-subtitleholder"></div></div></div></div>')
            }
            var n = (t.find(".tp-bullets"), ""),
                o = "hidearrows",
                r = a.navigationStyle;
            0 == a.hideThumbs && (o = ""), "none" == a.navigationArrows && (n = "visibility:hidden;display:none"), a.soloArrowStyle = "default " + a.navigationStyle, "none" != a.navigationArrows && "nexttobullets" != a.navigationArrows && (r = a.soloArrowStyle), i("left"), i("right"), t.parent().find(".tp-rightarrow").click(function() {
                0 == a.transition && (a.next = t.data("showus") != e && -1 != t.data("showus") ? t.data("showus") - 1 : a.next + 1, t.data("showus", -1), a.next >= a.slideamount && (a.next = 0), a.next < 0 && (a.next = 0), a.act != a.next && d(a, t))
            }), t.parent().find(".tp-leftarrow").click(function() {
                0 == a.transition && (a.next = a.next - 1, a.leftarrowpressed = 1, a.next < 0 && (a.next = a.slideamount - 1), d(a, t))
            }), f(t, a)
        },
        c = function(a, i) {
            t(document).keydown(function(t) {
                0 == i.transition && 39 == t.keyCode && (i.next = a.data("showus") != e && -1 != a.data("showus") ? a.data("showus") - 1 : i.next + 1, a.data("showus", -1), i.next >= i.slideamount && (i.next = 0), i.next < 0 && (i.next = 0), i.act != i.next && d(i, a)), 0 == i.transition && 37 == t.keyCode && (i.next = i.next - 1, i.leftarrowpressed = 1, i.next < 0 && (i.next = i.slideamount - 1), d(i, a))
            }), f(a, i)
        },
        p = function(e, a) {
            var i = "vertical";
            "on" == a.touchenabled && (1 == a.drag_block_vertical && (i = "none"), e.swipe({
                allowPageScroll: i,
                fingers: a.swipe_min_touches,
                treshold: a.swipe_treshold,
                swipe: function(n, o) {
                    switch (o) {
                        case "left":
                            0 == a.transition && (a.next = a.next + 1, a.next == a.slideamount && (a.next = 0), d(a, e));
                            break;
                        case "right":
                            0 == a.transition && (a.next = a.next - 1, a.leftarrowpressed = 1, a.next < 0 && (a.next = a.slideamount - 1), d(a, e));
                            break;
                        case "up":
                            "none" == i && t("html, body").animate({
                                scrollTop: e.offset().top + e.height() + "px"
                            });
                            break;
                        case "down":
                            "none" == i && t("html, body").animate({
                                scrollTop: e.offset().top - t(window).height() + "px"
                            })
                    }
                }
            }))
        },
        u = function(t, e) {
            var a = t.parent().find(".tp-bullets"),
                i = t.parent().find(".tparrows");
            if (null == a) {
                t.append('<div class=".tp-bullets"></div>');
                var a = t.parent().find(".tp-bullets")
            }
            if (null == i) {
                t.append('<div class=".tparrows"></div>');
                var i = t.parent().find(".tparrows")
            }
            if (t.data("hideThumbs", e.hideThumbs), a.addClass("hidebullets"), i.addClass("hidearrows"), Z()) try {
                t.hammer().on("touch", function() {
                    t.addClass("hovered"), "on" == e.onHoverStop && t.trigger("stoptimer"), clearTimeout(t.data("hideThumbs")), a.removeClass("hidebullets"), i.removeClass("hidearrows")
                }), t.hammer().on("release", function() {
                    t.removeClass("hovered"), t.trigger("starttimer"), t.hasClass("hovered") || a.hasClass("hovered") || t.data("hideThumbs", setTimeout(function() {
                        a.addClass("hidebullets"), i.addClass("hidearrows"), t.trigger("starttimer")
                    }, e.hideNavDelayOnMobile))
                })
            } catch (n) {} else a.hover(function() {
                e.overnav = !0, "on" == e.onHoverStop && t.trigger("stoptimer"), a.addClass("hovered"), clearTimeout(t.data("hideThumbs")), a.removeClass("hidebullets"), i.removeClass("hidearrows")
            }, function() {
                e.overnav = !1, t.trigger("starttimer"), a.removeClass("hovered"), t.hasClass("hovered") || a.hasClass("hovered") || t.data("hideThumbs", setTimeout(function() {
                    a.addClass("hidebullets"), i.addClass("hidearrows")
                }, e.hideThumbs))
            }), i.hover(function() {
                e.overnav = !0, "on" == e.onHoverStop && t.trigger("stoptimer"), a.addClass("hovered"), clearTimeout(t.data("hideThumbs")), a.removeClass("hidebullets"), i.removeClass("hidearrows")
            }, function() {
                e.overnav = !1, t.trigger("starttimer"), a.removeClass("hovered")
            }), t.on("mouseenter", function() {
                t.addClass("hovered"), "on" == e.onHoverStop && t.trigger("stoptimer"), clearTimeout(t.data("hideThumbs")), a.removeClass("hidebullets"), i.removeClass("hidearrows")
            }), t.on("mouseleave", function() {
                t.removeClass("hovered"), t.trigger("starttimer"), t.hasClass("hovered") || a.hasClass("hovered") || t.data("hideThumbs", setTimeout(function() {
                    a.addClass("hidebullets"), i.addClass("hidearrows")
                }, e.hideThumbs))
            })
        },
        f = function(e, a) {
            var i = e.parent(),
                n = i.find(".tp-bullets");
            if ("thumb" == a.navigationType) {
                n.find(".thumb").each(function() {
                    var e = t(this);
                    e.css({
                        width: a.thumbWidth * a.bw + "px",
                        height: a.thumbHeight * a.bh + "px"
                    })
                });
                var o = n.find(".tp-mask");
                o.width(a.thumbWidth * a.thumbAmount * a.bw), o.height(a.thumbHeight * a.bh), o.parent().width(a.thumbWidth * a.thumbAmount * a.bw), o.parent().height(a.thumbHeight * a.bh)
            }
            var r = i.find(".tp-leftarrow"),
                s = i.find(".tp-rightarrow");
            "thumb" == a.navigationType && "nexttobullets" == a.navigationArrows && (a.navigationArrows = "solo"), "nexttobullets" == a.navigationArrows && (r.prependTo(n).css({
                "float": "left"
            }), s.insertBefore(n.find(".tpclear")).css({
                "float": "left"
            }));
            var d = 0;
            "on" == a.forceFullWidth && (d = 0 - a.container.parent().offset().left);
            var l = 0,
                h = 0;
            if ("on" == a.navigationInGrid && (l = e.width() > a.startwidth ? (e.width() - a.startwidth) / 2 : 0, h = e.height() > a.startheight ? (e.height() - a.startheight) / 2 : 0), "none" != a.navigationArrows && "nexttobullets" != a.navigationArrows) {
                var c = a.soloArrowLeftValign,
                    p = a.soloArrowLeftHalign,
                    u = a.soloArrowRightValign,
                    f = a.soloArrowRightHalign,
                    g = a.soloArrowLeftVOffset,
                    m = a.soloArrowLeftHOffset,
                    v = a.soloArrowRightVOffset,
                    w = a.soloArrowRightHOffset;
                r.css({
                    position: "absolute"
                }), s.css({
                    position: "absolute"
                }), "center" == c ? r.css({
                    top: "50%",
                    marginTop: g - Math.round(r.innerHeight() / 2) + "px"
                }) : "bottom" == c ? r.css({
                    top: "auto",
                    bottom: 0 + g + "px"
                }) : "top" == c && r.css({
                    bottom: "auto",
                    top: 0 + g + "px"
                }), "center" == p ? r.css({
                    left: "50%",
                    marginLeft: d + m - Math.round(r.innerWidth() / 2) + "px"
                }) : "left" == p ? r.css({
                    left: l + m + d + "px"
                }) : "right" == p && r.css({
                    right: l + m - d + "px"
                }), "center" == u ? s.css({
                    top: "50%",
                    marginTop: v - Math.round(s.innerHeight() / 2) + "px"
                }) : "bottom" == u ? s.css({
                    top: "auto",
                    bottom: 0 + v + "px"
                }) : "top" == u && s.css({
                    bottom: "auto",
                    top: 0 + v + "px"
                }), "center" == f ? s.css({
                    left: "50%",
                    marginLeft: d + w - Math.round(s.innerWidth() / 2) + "px"
                }) : "left" == f ? s.css({
                    left: l + w + d + "px"
                }) : "right" == f && s.css({
                    right: l + w - d + "px"
                }), null != r.position() && r.css({
                    top: Math.round(parseInt(r.position().top, 0)) + "px"
                }), null != s.position() && s.css({
                    top: Math.round(parseInt(s.position().top, 0)) + "px"
                })
            }
            "none" == a.navigationArrows && (r.css({
                visibility: "hidden"
            }), s.css({
                visibility: "hidden"
            }));
            var b = a.navigationVAlign,
                y = a.navigationHAlign,
                x = a.navigationVOffset * a.bh,
                T = a.navigationHOffset * a.bw;
            "center" == b && n.css({
                top: "50%",
                marginTop: x - Math.round(n.innerHeight() / 2) + "px"
            }), "bottom" == b && n.css({
                bottom: 0 + x + "px"
            }), "top" == b && n.css({
                top: 0 + x + "px"
            }), "center" == y && n.css({
                left: "50%",
                marginLeft: d + T - Math.round(n.innerWidth() / 2) + "px"
            }), "left" == y && n.css({
                left: 0 + T + d + "px"
            }), "right" == y && n.css({
                right: 0 + T - d + "px"
            })
        },
        g = function(a) {
            var i = a.container;
            a.beforli = a.next - 1, a.comingli = a.next + 1, a.beforli < 0 && (a.beforli = a.slideamount - 1), a.comingli >= a.slideamount && (a.comingli = 0);
            var n = i.find(">ul:first-child >li:eq(" + a.comingli + ")"),
                o = i.find(">ul:first-child >li:eq(" + a.beforli + ")"),
                r = o.find(".defaultimg").attr("src"),
                s = n.find(".defaultimg").attr("src");
            a.arr == e && (a.arr = i.parent().find(".tparrows"), a.rar = i.parent().find(".tp-rightarrow"), a.lar = i.parent().find(".tp-leftarrow"), a.raimg = a.rar.find(".tp-arr-imgholder"), a.laimg = a.lar.find(".tp-arr-imgholder"), a.raimg_b = a.rar.find(".tp-arr-imgholder2"), a.laimg_b = a.lar.find(".tp-arr-imgholder2"), a.ratit = a.rar.find(".tp-arr-titleholder"), a.latit = a.lar.find(".tp-arr-titleholder"));
            var d = a.arr,
                l = a.rar,
                h = a.lar,
                c = a.raimg,
                p = a.laimg,
                u = a.raimg_b,
                f = a.laimg_b,
                g = a.ratit,
                m = a.latit;
            if (n.data("title") != e && g.html(n.data("title")), o.data("title") != e && m.html(o.data("title")), l.hasClass("itishovered") && l.width(g.outerWidth(!0) + parseInt(l.css("minWidth"), 0)), h.hasClass("itishovered") && h.width(m.outerWidth(!0) + parseInt(h.css("minWidth"), 0)), d.hasClass("preview2") && !d.hasClass("hashoveralready"))
                if (d.addClass("hashoveralready"), Z()) {
                    var d = t(this),
                        v = d.find(".tp-arr-titleholder");
                    v.addClass("alwayshidden"), punchgs.TweenLite.set(v, {
                        autoAlpha: 0
                    })
                } else d.hover(function() {
                    var e = t(this),
                        a = e.find(".tp-arr-titleholder");
                    t(window).width() > 767 && e.width(a.outerWidth(!0) + parseInt(e.css("minWidth"), 0)), e.addClass("itishovered")
                }, function() {
                    {
                        var e = t(this);
                        e.find(".tp-arr-titleholder")
                    }
                    e.css({
                        width: parseInt(e.css("minWidth"), 0)
                    }), e.removeClass("itishovered")
                });
            o.data("thumb") != e && (r = o.data("thumb")), n.data("thumb") != e && (s = n.data("thumb")), d.hasClass("preview4") ? (u.css({
                backgroundImage: "url(" + s + ")"
            }), f.css({
                backgroundImage: "url(" + r + ")"
            }), punchgs.TweenLite.fromTo(u, .8, {
                force3D: punchgs.force3d,
                x: 0
            }, {
                x: -c.width(),
                ease: punchgs.Power3.easeOut,
                delay: 1,
                onComplete: function() {
                    c.css({
                        backgroundImage: "url(" + s + ")"
                    }), punchgs.TweenLite.set(u, {
                        x: 0
                    })
                }
            }), punchgs.TweenLite.fromTo(f, .8, {
                force3D: punchgs.force3d,
                x: 0
            }, {
                x: c.width(),
                ease: punchgs.Power3.easeOut,
                delay: 1,
                onComplete: function() {
                    p.css({
                        backgroundImage: "url(" + r + ")"
                    }), punchgs.TweenLite.set(f, {
                        x: 0
                    })
                }
            }), punchgs.TweenLite.fromTo(c, .8, {
                x: 0
            }, {
                force3D: punchgs.force3d,
                x: -c.width(),
                ease: punchgs.Power3.easeOut,
                delay: 1,
                onComplete: function() {
                    punchgs.TweenLite.set(c, {
                        x: 0
                    })
                }
            }), punchgs.TweenLite.fromTo(p, .8, {
                x: 0
            }, {
                force3D: punchgs.force3d,
                x: c.width(),
                ease: punchgs.Power3.easeOut,
                delay: 1,
                onComplete: function() {
                    punchgs.TweenLite.set(p, {
                        x: 0
                    })
                }
            })) : (punchgs.TweenLite.to(c, .5, {
                autoAlpha: 0,
                onComplete: function() {
                    c.css({
                        backgroundImage: "url(" + s + ")"
                    }), p.css({
                        backgroundImage: "url(" + r + ")"
                    })
                }
            }), punchgs.TweenLite.to(p, .5, {
                autoAlpha: 0,
                onComplete: function() {
                    punchgs.TweenLite.to(c, .5, {
                        autoAlpha: 1,
                        delay: .2
                    }), punchgs.TweenLite.to(p, .5, {
                        autoAlpha: 1,
                        delay: .2
                    })
                }
            })), l.hasClass("preview4") && !l.hasClass("hashoveralready") && (l.addClass("hashoveralready"), l.hover(function() {
                var e = t(this).find(".tp-arr-iwrapper"),
                    a = t(this).find(".tp-arr-allwrapper");
                punchgs.TweenLite.fromTo(e, .4, {
                    x: e.width()
                }, {
                    x: 0,
                    delay: .3,
                    ease: punchgs.Power3.easeOut,
                    overwrite: "all"
                }), punchgs.TweenLite.to(a, .2, {
                    autoAlpha: 1,
                    overwrite: "all"
                })
            }, function() {
                var e = t(this).find(".tp-arr-iwrapper"),
                    a = t(this).find(".tp-arr-allwrapper");
                punchgs.TweenLite.to(e, .4, {
                    x: e.width(),
                    ease: punchgs.Power3.easeOut,
                    delay: .2,
                    overwrite: "all"
                }), punchgs.TweenLite.to(a, .2, {
                    delay: .6,
                    autoAlpha: 0,
                    overwrite: "all"
                })
            }), h.hover(function() {
                var e = t(this).find(".tp-arr-iwrapper"),
                    a = t(this).find(".tp-arr-allwrapper");
                punchgs.TweenLite.fromTo(e, .4, {
                    x: 0 - e.width()
                }, {
                    x: 0,
                    delay: .3,
                    ease: punchgs.Power3.easeOut,
                    overwrite: "all"
                }), punchgs.TweenLite.to(a, .2, {
                    autoAlpha: 1,
                    overwrite: "all"
                })
            }, function() {
                var e = t(this).find(".tp-arr-iwrapper"),
                    a = t(this).find(".tp-arr-allwrapper");
                punchgs.TweenLite.to(e, .4, {
                    x: 0 - e.width(),
                    ease: punchgs.Power3.easeOut,
                    delay: .2,
                    overwrite: "all"
                }), punchgs.TweenLite.to(a, .2, {
                    delay: .6,
                    autoAlpha: 0,
                    overwrite: "all"
                })
            }))
        },
        m = function(a, i) {
            if (i.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").css({
                    height: i.container.height()
                }), i.container.closest(".rev_slider_wrapper").css({
                    height: i.container.height()
                }), i.width = parseInt(i.container.width(), 0), i.height = parseInt(i.container.height(), 0), i.bw = i.width / i.startwidth, i.bh = i.height / i.startheight, i.bh > i.bw && (i.bh = i.bw), i.bh < i.bw && (i.bw = i.bh), i.bw < i.bh && (i.bh = i.bw), i.bh > 1 && (i.bw = 1, i.bh = 1), i.bw > 1 && (i.bw = 1, i.bh = 1), i.height = Math.round(i.startheight * (i.width / i.startwidth)), i.height > i.startheight && "on" != i.autoHeight && (i.height = i.startheight), "on" == i.fullScreen) {
                i.height = i.bw * i.startheight;
                var n = (i.container.parent().width(), t(window).height());
                if (i.fullScreenOffsetContainer != e) {
                    try {
                        var o = i.fullScreenOffsetContainer.split(",");
                        t.each(o, function(e, a) {
                            n -= t(a).outerHeight(!0), n < i.minFullScreenHeight && (n = i.minFullScreenHeight)
                        })
                    } catch (r) {}
                    try {
                        i.fullScreenOffset.split("%").length > 1 && i.fullScreenOffset != e && i.fullScreenOffset.length > 0 ? n -= t(window).height() * parseInt(i.fullScreenOffset, 0) / 100 : i.fullScreenOffset != e && i.fullScreenOffset.length > 0 && (n -= parseInt(i.fullScreenOffset, 0)), n < i.minFullScreenHeight && (n = i.minFullScreenHeight)
                    } catch (r) {}
                }
                i.container.parent().height(n), i.container.closest(".rev_slider_wrapper").height(n), i.container.css({
                    height: "100%"
                }), i.height = n, i.minHeight != e && i.height < i.minHeight && (i.height = i.minHeight)
            } else i.minHeight != e && i.height < i.minHeight && (i.height = i.minHeight), i.container.height(i.height);
            i.slotw = Math.ceil(i.width / i.slots), i.sloth = Math.ceil("on" == i.fullScreen ? t(window).height() / i.slots : i.height / i.slots), "on" == i.autoHeight && (i.sloth = Math.ceil(a.height() / i.slots))
        },
        v = function(a, i) {
            a.find(".tp-caption").each(function() {
                t(this).addClass(t(this).data("transition")), t(this).addClass("start")
            }), a.find(">ul:first").css({
                overflow: "hidden",
                width: "100%",
                height: "100%",
                maxHeight: a.parent().css("maxHeight")
            }).addClass("tp-revslider-mainul"), "on" == i.autoHeight && (a.find(">ul:first").css({
                overflow: "hidden",
                width: "100%",
                height: "100%",
                maxHeight: "none"
            }), a.css({
                maxHeight: "none"
            }), a.parent().css({
                maxHeight: "none"
            })), a.find(">ul:first >li").each(function() {
                var i = t(this);
                if (i.addClass("tp-revslider-slidesli"), i.css({
                        width: "100%",
                        height: "100%",
                        overflow: "hidden"
                    }), i.data("link") != e) {
                    var n = i.data("link"),
                        o = "_self",
                        r = 60;
                    "back" == i.data("slideindex") && (r = 0);
                    var s = checksl = i.data("linktoslide");
                    s != e && "next" != s && "prev" != s && a.find(">ul:first-child >li").each(function() {
                        var e = t(this);
                        e.data("origindex") + 1 == checksl && (s = e.index() + 1)
                    }), i.data("target") != e && (o = i.data("target")), "slide" != n && (s = "no");
                    var d = '<div class="tp-caption sft slidelink" style="width:100%;height:100%;z-index:' + r + ';" data-x="center" data-y="center" data-linktoslide="' + s + '" data-start="0"><a style="width:100%;height:100%;display:block"';
                    "slide" != n && (d = d + ' target="' + o + '" href="' + n + '"'), d += '><span style="width:100%;height:100%;display:block"></span></a></div>', i.append(d)
                }
            }), a.parent().css({
                overflow: "visible"
            }), a.find(">ul:first >li >img").each(function(a) {
                var n = t(this);
                n.addClass("defaultimg"), n.data("lazyload") != e && 1 != n.data("lazydone") || m(n, i), s(8) && n.data("kenburns", "off"), "on" == i.panZoomDisableOnMobile && Z() && (n.data("kenburns", "off"), n.data("bgfit", "cover")), n.wrap('<div class="slotholder" style="width:100%;height:100%;"data-duration="' + n.data("duration") + '"data-zoomstart="' + n.data("zoomstart") + '"data-zoomend="' + n.data("zoomend") + '"data-rotationstart="' + n.data("rotationstart") + '"data-rotationend="' + n.data("rotationend") + '"data-ease="' + n.data("ease") + '"data-duration="' + n.data("duration") + '"data-bgpositionend="' + n.data("bgpositionend") + '"data-bgposition="' + n.data("bgposition") + '"data-duration="' + n.data("duration") + '"data-kenburns="' + n.data("kenburns") + '"data-easeme="' + n.data("ease") + '"data-bgfit="' + n.data("bgfit") + '"data-bgfitend="' + n.data("bgfitend") + '"data-owidth="' + n.data("owidth") + '"data-oheight="' + n.data("oheight") + '"></div>'), "none" != i.dottedOverlay && i.dottedOverlay != e && n.closest(".slotholder").append('<div class="tp-dottedoverlay ' + i.dottedOverlay + '"></div>');
                var o = n.attr("src"),
                    r = (n.data("lazyload"), n.data("bgfit")),
                    d = n.data("bgrepeat"),
                    l = n.data("bgposition");
                r == e && (r = "cover"), d == e && (d = "no-repeat"), l == e && (l = "center center");
                var h = n.closest(".slotholder");
                n.replaceWith('<div class="tp-bgimg defaultimg" data-lazyload="' + n.data("lazyload") + '" data-bgfit="' + r + '"data-bgposition="' + l + '" data-bgrepeat="' + d + '" data-lazydone="' + n.data("lazydone") + '" src="' + o + '" data-src="' + o + '" style="background-color:' + n.css("backgroundColor") + ";background-repeat:" + d + ";background-image:url(" + o + ");background-size:" + r + ";background-position:" + l + ';width:100%;height:100%;"></div>'), s(8) && (h.find(".tp-bgimg").css({
                    backgroundImage: "none",
                    "background-image": "none"
                }), h.find(".tp-bgimg").append('<img class="ieeightfallbackimage defaultimg" src="' + o + '" style="width:100%">')), n.css({
                    opacity: 0
                }), n.data("li-id", a)
            })
        },
        w = function(t, a, i, n) {
            var o = t,
                r = o.find(".defaultimg"),
                d = o.data("zoomstart"),
                l = o.data("rotationstart");
            r.data("currotate") != e && (l = r.data("currotate")), r.data("curscale") != e && "box" == n ? d = 100 * r.data("curscale") : r.data("curscale") != e && (d = r.data("curscale")), m(r, a);
            var h = r.data("src"),
                c = r.css("backgroundColor"),
                p = a.width,
                u = a.height,
                f = r.data("fxof"),
                g = 0;
            "on" == a.autoHeight && (u = a.container.height()), f == e && (f = 0);
            var v = 0,
                w = r.data("bgfit"),
                y = r.data("bgrepeat"),
                x = r.data("bgposition");
            if (w == e && (w = "cover"), y == e && (y = "no-repeat"), x == e && (x = "center center"), s(8)) {
                o.data("kenburns", "off");
                var T = h;
                h = ""
            }
            switch (n) {
                case "box":
                    var k = 0,
                        L = 0,
                        C = 0;
                    if (k = a.sloth > a.slotw ? a.sloth : a.slotw, !i) var v = 0 - k;
                    a.slotw = k, a.sloth = k;
                    var L = 0,
                        C = 0;
                    "on" == o.data("kenburns") && (w = d, w.toString().length < 4 && (w = U(w, o, a)));
                    for (var z = 0; z < a.slots; z++) {
                        C = 0;
                        for (var O = 0; O < a.slots; O++) o.append('<div class="slot" style="position:absolute;top:' + (g + C) + "px;left:" + (f + L) + "px;width:" + k + "px;height:" + k + 'px;overflow:hidden;"><div class="slotslide" data-x="' + L + '" data-y="' + C + '" style="position:absolute;top:0px;left:0px;width:' + k + "px;height:" + k + 'px;overflow:hidden;"><div style="position:absolute;top:' + (0 - C) + "px;left:" + (0 - L) + "px;width:" + p + "px;height:" + u + "px;background-color:" + c + ";background-image:url(" + h + ");background-repeat:" + y + ";background-size:" + w + ";background-position:" + x + ';"></div></div></div>'), C += k, s(8) && (o.find(".slot ").last().find(".slotslide").append('<img src="' + T + '">'), b(o, a)), d != e && l != e && punchgs.TweenLite.set(o.find(".slot").last(), {
                            rotationZ: l
                        });
                        L += k
                    }
                    break;
                case "vertical":
                case "horizontal":
                    if ("on" == o.data("kenburns") && (w = d, w.toString().length < 4 && (w = U(w, o, a))), "horizontal" == n) {
                        if (!i) var v = 0 - a.slotw;
                        for (var O = 0; O < a.slots; O++) o.append('<div class="slot" style="position:absolute;top:' + (0 + g) + "px;left:" + (f + O * a.slotw) + "px;overflow:hidden;width:" + (a.slotw + .6) + "px;height:" + u + 'px"><div class="slotslide" style="position:absolute;top:0px;left:' + v + "px;width:" + (a.slotw + .6) + "px;height:" + u + 'px;overflow:hidden;"><div style="background-color:' + c + ";position:absolute;top:0px;left:" + (0 - O * a.slotw) + "px;width:" + p + "px;height:" + u + "px;background-image:url(" + h + ");background-repeat:" + y + ";background-size:" + w + ";background-position:" + x + ';"></div></div></div>'), d != e && l != e && punchgs.TweenLite.set(o.find(".slot").last(), {
                            rotationZ: l
                        }), s(8) && (o.find(".slot ").last().find(".slotslide").append('<img class="ieeightfallbackimage" src="' + T + '" style="width:100%;height:auto">'), b(o, a))
                    } else {
                        if (!i) var v = 0 - a.sloth;
                        for (var O = 0; O < a.slots + 2; O++) o.append('<div class="slot" style="position:absolute;top:' + (g + O * a.sloth) + "px;left:" + f + "px;overflow:hidden;width:" + p + "px;height:" + a.sloth + 'px"><div class="slotslide" style="position:absolute;top:' + v + "px;left:0px;width:" + p + "px;height:" + a.sloth + 'px;overflow:hidden;"><div style="background-color:' + c + ";position:absolute;top:" + (0 - O * a.sloth) + "px;left:0px;width:" + p + "px;height:" + u + "px;background-image:url(" + h + ");background-repeat:" + y + ";background-size:" + w + ";background-position:" + x + ';"></div></div></div>'), d != e && l != e && punchgs.TweenLite.set(o.find(".slot").last(), {
                            rotationZ: l
                        }), s(8) && (o.find(".slot ").last().find(".slotslide").append('<img class="ieeightfallbackimage" src="' + T + '" style="width:100%;height:auto;">'), b(o, a))
                    }
            }
        },
        b = function(t, e) {
            if (s(8)) {
                {
                    var a = t.find(".ieeightfallbackimage");
                    a.width(), a.height()
                }
                a.css(e.startwidth / e.startheight < t.data("owidth") / t.data("oheight") ? {
                    width: "auto",
                    height: "100%"
                } : {
                    width: "100%",
                    height: "auto"
                }), setTimeout(function() {
                    var i = a.width(),
                        n = a.height(),
                        o = t.data("bgposition");
                    "center center" == o && a.css({
                        position: "absolute",
                        top: e.height / 2 - n / 2 + "px",
                        left: e.width / 2 - i / 2 + "px"
                    }), ("center top" == o || "top center" == o) && a.css({
                        position: "absolute",
                        top: "0px",
                        left: e.width / 2 - i / 2 + "px"
                    }), ("center bottom" == o || "bottom center" == o) && a.css({
                        position: "absolute",
                        bottom: "0px",
                        left: e.width / 2 - i / 2 + "px"
                    }), ("right top" == o || "top right" == o) && a.css({
                        position: "absolute",
                        top: "0px",
                        right: "0px"
                    }), ("right bottom" == o || "bottom right" == o) && a.css({
                        position: "absolute",
                        bottom: "0px",
                        right: "0px"
                    }), ("right center" == o || "center right" == o) && a.css({
                        position: "absolute",
                        top: e.height / 2 - n / 2 + "px",
                        right: "0px"
                    }), ("left bottom" == o || "bottom left" == o) && a.css({
                        position: "absolute",
                        bottom: "0px",
                        left: "0px"
                    }), ("left center" == o || "center left" == o) && a.css({
                        position: "absolute",
                        top: e.height / 2 - n / 2 + "px",
                        left: "0px"
                    })
                }, 20)
            }
        },
        y = function(e, a, i) {
            i.find(".slot").each(function() {
                t(this).remove()
            }), a.transition = 0
        },
        x = function(a, i) {
            a.find("img, .defaultimg").each(function() {
                var a = t(this),
                    n = a.data("lazyload");
                if (n != a.attr("src") && 3 > i && n != e && "undefined" != n) {
                    if (n != e && "undefined" != n) {
                        a.attr("src", n);
                        var o = new Image;
                        o.onload = function() {
                            a.data("lazydone", 1), a.hasClass("defaultimg") && T(a, o)
                        }, o.error = function() {
                            a.data("lazydone", 1)
                        }, o.src = a.attr("src"), o.complete && (a.hasClass("defaultimg") && T(a, o), a.data("lazydone", 1))
                    }
                } else if ((n === e || "undefined" === n) && 1 != a.data("lazydone")) {
                    var o = new Image;
                    o.onload = function() {
                        a.hasClass("defaultimg") && T(a, o), a.data("lazydone", 1)
                    }, o.error = function() {
                        a.data("lazydone", 1)
                    }, o.src = a.attr("src") != e && "undefined" != a.attr("src") ? a.attr("src") : a.data("src"), o.complete && (a.hasClass("defaultimg") && T(a, o), a.data("lazydone", 1))
                }
            })
        },
        T = function(t, e) {
            var a = t.closest("li"),
                i = e.width,
                n = e.height;
            a.data("owidth", i), a.data("oheight", n), a.find(".slotholder").data("owidth", i), a.find(".slotholder").data("oheight", n), a.data("loadeddone", 1)
        },
        k = function(a, i, n) {
            x(a, 0);
            var o = setInterval(function() {
                n.bannertimeronpause = !0, n.container.trigger("stoptimer"), n.cd = 0;
                var r = 0;
                a.find("img, .defaultimg").each(function() {
                    1 != t(this).data("lazydone") && r++
                }), r > 0 ? x(a, r) : (clearInterval(o), i != e && i())
            }, 100)
        },
        L = function(t, a) {
            try {
                {
                    t.find(">ul:first-child >li:eq(" + a.act + ")")
                }
            } catch (i) {
                {
                    t.find(">ul:first-child >li:eq(1)")
                }
            }
            a.lastslide = a.act;
            var n = t.find(">ul:first-child >li:eq(" + a.next + ")"),
                o = n.find(".defaultimg");
            a.bannertimeronpause = !0, t.trigger("stoptimer"), a.cd = 0, o.data("lazyload") != e && "undefined" != o.data("lazyload") && 1 != o.data("lazydone") ? (s(8) ? o.attr("src", n.find(".defaultimg").data("lazyload")) : o.css({
                backgroundImage: 'url("' + n.find(".defaultimg").data("lazyload") + '")'
            }), o.data("src", n.find(".defaultimg").data("lazyload")), o.data("lazydone", 1), o.data("orgw", 0), n.data("loadeddone", 1), t.find(".tp-loader").css({
                display: "block"
            }), k(t.find(".tp-static-layers"), function() {
                k(n, function() {
                    var e = n.find(".slotholder");
                    if ("on" == e.data("kenburns")) var i = setInterval(function() {
                        var n = e.data("owidth");
                        n >= 0 && (clearInterval(i), C(a, o, t))
                    }, 10);
                    else C(a, o, t)
                }, a)
            }, a)) : n.data("loadeddone") === e ? (n.data("loadeddone", 1), k(n, function() {
                C(a, o, t)
            }, a)) : C(a, o, t)
        },
        C = function(t, e, a) {
            t.bannertimeronpause = !1, t.cd = 0, a.trigger("nulltimer"), a.find(".tp-loader").css({
                display: "none"
            }), m(e, t), f(a, t), m(e, t), z(a, t)
        },
        z = function(t, a) {
            t.trigger("revolution.slide.onbeforeswap"), a.transition = 1, a.videoplaying = !1;
            try {
                var i = t.find(">ul:first-child >li:eq(" + a.act + ")")
            } catch (n) {
                var i = t.find(">ul:first-child >li:eq(1)")
            }
            a.lastslide = a.act;
            var o = t.find(">ul:first-child >li:eq(" + a.next + ")");
            setTimeout(function() {
                g(a)
            }, 200);
            var r = i.find(".slotholder"),
                s = o.find(".slotholder");
            ("on" == s.data("kenburns") || "on" == r.data("kenburns")) && (J(t, a), t.find(".kenburnimg").remove()), o.data("delay") != e ? (a.cd = 0, a.delay = o.data("delay")) : a.delay = a.origcd, 1 == a.firststart && punchgs.TweenLite.set(i, {
                autoAlpha: 0
            }), punchgs.TweenLite.set(i, {
                zIndex: 18
            }), punchgs.TweenLite.set(o, {
                autoAlpha: 0,
                zIndex: 20
            });
            var d = 0;
            i.index() != o.index() && 1 != a.firststart && (d = B(i, a)), "on" != i.data("saveperformance") && (d = 0), setTimeout(function() {
                t.trigger("restarttimer"), O(t, a, o, i, r, s)
            }, d)
        },
        O = function(a, i, n, o, r, d) {
            function l() {
                t.each(g, function(t, e) {
                    (e[0] == u || e[8] == u) && (h = e[1], f = e[2], b = y), y += 1
                })
            }
            "prepared" == n.data("differentissplayed") && (n.data("differentissplayed", "done"), n.data("transition", n.data("savedtransition")), n.data("slotamount", n.data("savedslotamount")), n.data("masterspeed", n.data("savedmasterspeed"))), n.data("fstransition") != e && "done" != n.data("differentissplayed") && (n.data("savedtransition", n.data("transition")), n.data("savedslotamount", n.data("slotamount")), n.data("savedmasterspeed", n.data("masterspeed")), n.data("transition", n.data("fstransition")), n.data("slotamount", n.data("fsslotamount")), n.data("masterspeed", n.data("fsmasterspeed")), n.data("differentissplayed", "prepared")), a.find(".active-revslide").removeClass(".active-revslide"), n.addClass("active-revslide"), n.data("transition") == e && n.data("transition", "random");
            var h = 0,
                c = n.data("transition").split(","),
                p = n.data("nexttransid") == e ? -1 : n.data("nexttransid");
            "on" == n.data("randomtransition") ? p = Math.round(Math.random() * c.length) : p += 1, p == c.length && (p = 0), n.data("nexttransid", p);
            var u = c[p];
            i.ie && ("boxfade" == u && (u = "boxslide"), "slotfade-vertical" == u && (u = "slotzoom-vertical"), "slotfade-horizontal" == u && (u = "slotzoom-horizontal")), s(8) && (u = 11);
            var f = 0;
            "scroll" == i.parallax && i.parallaxFirstGo == e && (i.parallaxFirstGo = !0, K(a, i), setTimeout(function() {
                K(a, i)
            }, 210), setTimeout(function() {
                K(a, i)
            }, 420)), "slidehorizontal" == u && (u = "slideleft", 1 == i.leftarrowpressed && (u = "slideright")), "slidevertical" == u && (u = "slideup", 1 == i.leftarrowpressed && (u = "slidedown")), "parallaxhorizontal" == u && (u = "parallaxtoleft", 1 == i.leftarrowpressed && (u = "parallaxtoright")), "parallaxvertical" == u && (u = "parallaxtotop", 1 == i.leftarrowpressed && (u = "parallaxtobottom"));
            var g = [
                    ["boxslide", 0, 1, 10, 0, "box", !1, null, 0],
                    ["boxfade", 1, 0, 10, 0, "box", !1, null, 1],
                    ["slotslide-horizontal", 2, 0, 0, 200, "horizontal", !0, !1, 2],
                    ["slotslide-vertical", 3, 0, 0, 200, "vertical", !0, !1, 3],
                    ["curtain-1", 4, 3, 0, 0, "horizontal", !0, !0, 4],
                    ["curtain-2", 5, 3, 0, 0, "horizontal", !0, !0, 5],
                    ["curtain-3", 6, 3, 25, 0, "horizontal", !0, !0, 6],
                    ["slotzoom-horizontal", 7, 0, 0, 400, "horizontal", !0, !0, 7],
                    ["slotzoom-vertical", 8, 0, 0, 0, "vertical", !0, !0, 8],
                    ["slotfade-horizontal", 9, 0, 0, 500, "horizontal", !0, null, 9],
                    ["slotfade-vertical", 10, 0, 0, 500, "vertical", !0, null, 10],
                    ["fade", 11, 0, 1, 300, "horizontal", !0, null, 11],
                    ["slideleft", 12, 0, 1, 0, "horizontal", !0, !0, 12],
                    ["slideup", 13, 0, 1, 0, "horizontal", !0, !0, 13],
                    ["slidedown", 14, 0, 1, 0, "horizontal", !0, !0, 14],
                    ["slideright", 15, 0, 1, 0, "horizontal", !0, !0, 15],
                    ["papercut", 16, 0, 0, 600, "", null, null, 16],
                    ["3dcurtain-horizontal", 17, 0, 20, 100, "vertical", !1, !0, 17],
                    ["3dcurtain-vertical", 18, 0, 10, 100, "horizontal", !1, !0, 18],
                    ["cubic", 19, 0, 20, 600, "horizontal", !1, !0, 19],
                    ["cube", 19, 0, 20, 600, "horizontal", !1, !0, 20],
                    ["flyin", 20, 0, 4, 600, "vertical", !1, !0, 21],
                    ["turnoff", 21, 0, 1, 1600, "horizontal", !1, !0, 22],
                    ["incube", 22, 0, 20, 200, "horizontal", !1, !0, 23],
                    ["cubic-horizontal", 23, 0, 20, 500, "vertical", !1, !0, 24],
                    ["cube-horizontal", 23, 0, 20, 500, "vertical", !1, !0, 25],
                    ["incube-horizontal", 24, 0, 20, 500, "vertical", !1, !0, 26],
                    ["turnoff-vertical", 25, 0, 1, 200, "horizontal", !1, !0, 27],
                    ["fadefromright", 12, 1, 1, 0, "horizontal", !0, !0, 28],
                    ["fadefromleft", 15, 1, 1, 0, "horizontal", !0, !0, 29],
                    ["fadefromtop", 14, 1, 1, 0, "horizontal", !0, !0, 30],
                    ["fadefrombottom", 13, 1, 1, 0, "horizontal", !0, !0, 31],
                    ["fadetoleftfadefromright", 12, 2, 1, 0, "horizontal", !0, !0, 32],
                    ["fadetorightfadetoleft", 15, 2, 1, 0, "horizontal", !0, !0, 33],
                    ["fadetobottomfadefromtop", 14, 2, 1, 0, "horizontal", !0, !0, 34],
                    ["fadetotopfadefrombottom", 13, 2, 1, 0, "horizontal", !0, !0, 35],
                    ["parallaxtoright", 12, 3, 1, 0, "horizontal", !0, !0, 36],
                    ["parallaxtoleft", 15, 3, 1, 0, "horizontal", !0, !0, 37],
                    ["parallaxtotop", 14, 3, 1, 0, "horizontal", !0, !0, 38],
                    ["parallaxtobottom", 13, 3, 1, 0, "horizontal", !0, !0, 39],
                    ["scaledownfromright", 12, 4, 1, 0, "horizontal", !0, !0, 40],
                    ["scaledownfromleft", 15, 4, 1, 0, "horizontal", !0, !0, 41],
                    ["scaledownfromtop", 14, 4, 1, 0, "horizontal", !0, !0, 42],
                    ["scaledownfrombottom", 13, 4, 1, 0, "horizontal", !0, !0, 43],
                    ["zoomout", 13, 5, 1, 0, "horizontal", !0, !0, 44],
                    ["zoomin", 13, 6, 1, 0, "horizontal", !0, !0, 45],
                    ["notransition", 26, 0, 1, 0, "horizontal", !0, null, 46]
                ],
                m = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45],
                v = [16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27],
                h = 0,
                f = 1,
                b = 0,
                y = 0,
                x = new Array;
            "on" == d.data("kenburns") && (("boxslide" == u || 0 == u || "boxfade" == u || 1 == u || "papercut" == u || 16 == u) && (u = 11), N(a, i, !0, !0)), "random" == u && (u = Math.round(Math.random() * g.length - 1), u > g.length - 1 && (u = g.length - 1)), "random-static" == u && (u = Math.round(Math.random() * m.length - 1), u > m.length - 1 && (u = m.length - 1), u = m[u]), "random-premium" == u && (u = Math.round(Math.random() * v.length - 1), u > v.length - 1 && (u = v.length - 1), u = v[u]);
            var T = [12, 13, 14, 15, 16, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45];
            if (1 == i.isJoomla && window.MooTools != e && -1 != T.indexOf(u)) {
                var k = Math.round(Math.random() * (v.length - 2)) + 1;
                k > v.length - 1 && (k = v.length - 1), 0 == k && (k = 1), u = v[k]
            }
            l(), s(8) && h > 15 && 28 > h && (u = Math.round(Math.random() * m.length - 1), u > m.length - 1 && (u = m.length - 1), u = m[u], y = 0, l());
            var L = -1;
            (1 == i.leftarrowpressed || i.act > i.next) && (L = 1), i.leftarrowpressed = 0, h > 26 && (h = 26), 0 > h && (h = 0);
            var C = 300;
            n.data("masterspeed") != e && n.data("masterspeed") > 99 && n.data("masterspeed") < i.delay && (C = n.data("masterspeed")), n.data("masterspeed") != e && n.data("masterspeed") > i.delay && (C = i.delay), x = g[b], a.parent().find(".bullet").each(function() {
                var e = t(this),
                    a = e.index();
                e.removeClass("selected"), ("withbullet" == i.navigationArrows || "nexttobullets" == i.navigationArrows) && (a = e.index() - 1), a == i.next && e.addClass("selected")
            });
            var z = new punchgs.TimelineLite({
                onComplete: function() {
                    I(a, i, d, r, n, o, z)
                }
            });
            if (z.add(punchgs.TweenLite.set(d.find(".defaultimg"), {
                    opacity: 0
                })), z.pause(), n.data("slotamount") == e || n.data("slotamount") < 1 ? (i.slots = Math.round(12 * Math.random() + 4), "boxslide" == u ? i.slots = Math.round(6 * Math.random() + 3) : "flyin" == u && (i.slots = Math.round(4 * Math.random() + 1))) : i.slots = n.data("slotamount"), i.rotate = n.data("rotate") == e ? 0 : 999 == n.data("rotate") ? Math.round(360 * Math.random()) : n.data("rotate"), (!t.support.transition || i.ie || i.ie9) && (i.rotate = 0), 1 == i.firststart && (i.firststart = 0), C += x[4], (4 == h || 5 == h || 6 == h) && i.slots < 3 && (i.slots = 3), 0 != x[3] && (i.slots = Math.min(i.slots, x[3])), 9 == h && (i.slots = i.width / 20), 10 == h && (i.slots = i.height / 20), null != x[7] && w(r, i, x[7], x[5]), null != x[6] && w(d, i, x[6], x[5]), 0 == h) {
                var O = Math.ceil(i.height / i.sloth),
                    A = 0;
                d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    A += 1, A == O && (A = 0), z.add(punchgs.TweenLite.from(a, C / 600, {
                        opacity: 0,
                        top: 0 - i.sloth,
                        left: 0 - i.slotw,
                        rotation: i.rotate,
                        force3D: "auto",
                        ease: punchgs.Power2.easeOut
                    }), (15 * e + 30 * A) / 1500)
                })
            }
            if (1 == h) {
                var M, S = 0;
                d.find(".slotslide").each(function(e) {
                    var a = t(this),
                        n = Math.random() * C + 300,
                        o = 500 * Math.random() + 200;
                    n + o > M && (M = o + o, S = e), z.add(punchgs.TweenLite.from(a, n / 1e3, {
                        autoAlpha: 0,
                        force3D: "auto",
                        rotation: i.rotate,
                        ease: punchgs.Power2.easeInOut
                    }), o / 1e3)
                })
            }
            if (2 == h) {
                var P = new punchgs.TimelineLite;
                r.find(".slotslide").each(function() {
                    var e = t(this);
                    P.add(punchgs.TweenLite.to(e, C / 1e3, {
                        left: i.slotw,
                        force3D: "auto",
                        rotation: 0 - i.rotate
                    }), 0), z.add(P, 0)
                }), d.find(".slotslide").each(function() {
                    var e = t(this);
                    P.add(punchgs.TweenLite.from(e, C / 1e3, {
                        left: 0 - i.slotw,
                        force3D: "auto",
                        rotation: i.rotate
                    }), 0), z.add(P, 0)
                })
            }
            if (3 == h) {
                var P = new punchgs.TimelineLite;
                r.find(".slotslide").each(function() {
                    var e = t(this);
                    P.add(punchgs.TweenLite.to(e, C / 1e3, {
                        top: i.sloth,
                        rotation: i.rotate,
                        force3D: "auto",
                        transformPerspective: 600
                    }), 0), z.add(P, 0)
                }), d.find(".slotslide").each(function() {
                    var e = t(this);
                    P.add(punchgs.TweenLite.from(e, C / 1e3, {
                        top: 0 - i.sloth,
                        rotation: i.rotate,
                        ease: punchgs.Power2.easeOut,
                        force3D: "auto",
                        transformPerspective: 600
                    }), 0), z.add(P, 0)
                })
            }
            if (4 == h || 5 == h) {
                setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100);
                var D = C / 1e3,
                    P = new punchgs.TimelineLite;
                r.find(".slotslide").each(function(e) {
                    var a = t(this),
                        n = e * D / i.slots;
                    5 == h && (n = (i.slots - e - 1) * D / i.slots / 1.5), P.add(punchgs.TweenLite.to(a, 3 * D, {
                        transformPerspective: 600,
                        force3D: "auto",
                        top: 0 + i.height,
                        opacity: .5,
                        rotation: i.rotate,
                        ease: punchgs.Power2.easeInOut,
                        delay: n
                    }), 0), z.add(P, 0)
                }), d.find(".slotslide").each(function(e) {
                    var a = t(this),
                        n = e * D / i.slots;
                    5 == h && (n = (i.slots - e - 1) * D / i.slots / 1.5), P.add(punchgs.TweenLite.from(a, 3 * D, {
                        top: 0 - i.height,
                        opacity: .5,
                        rotation: i.rotate,
                        force3D: "auto",
                        ease: punchgs.Power2.easeInOut,
                        delay: n
                    }), 0), z.add(P, 0)
                })
            }
            if (6 == h) {
                i.slots < 2 && (i.slots = 2), i.slots % 2 && (i.slots = i.slots + 1);
                var P = new punchgs.TimelineLite;
                setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100), r.find(".slotslide").each(function(e) {
                    var a = t(this);
                    if (e + 1 < i.slots / 2) var n = 90 * (e + 2);
                    else var n = 90 * (2 + i.slots - e);
                    P.add(punchgs.TweenLite.to(a, (C + n) / 1e3, {
                        top: 0 + i.height,
                        opacity: 1,
                        force3D: "auto",
                        rotation: i.rotate,
                        ease: punchgs.Power2.easeInOut
                    }), 0), z.add(P, 0)
                }), d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    if (e + 1 < i.slots / 2) var n = 90 * (e + 2);
                    else var n = 90 * (2 + i.slots - e);
                    P.add(punchgs.TweenLite.from(a, (C + n) / 1e3, {
                        top: 0 - i.height,
                        opacity: 1,
                        force3D: "auto",
                        rotation: i.rotate,
                        ease: punchgs.Power2.easeInOut
                    }), 0), z.add(P, 0)
                })
            }
            if (7 == h) {
                C = 2 * C, C > i.delay && (C = i.delay);
                var P = new punchgs.TimelineLite;
                setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100), r.find(".slotslide").each(function() {
                    var e = t(this).find("div");
                    P.add(punchgs.TweenLite.to(e, C / 1e3, {
                        left: 0 - i.slotw / 2 + "px",
                        top: 0 - i.height / 2 + "px",
                        width: 2 * i.slotw + "px",
                        height: 2 * i.height + "px",
                        opacity: 0,
                        rotation: i.rotate,
                        force3D: "auto",
                        ease: punchgs.Power2.easeOut
                    }), 0), z.add(P, 0)
                }), d.find(".slotslide").each(function(e) {
                    var a = t(this).find("div");
                    P.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        left: 0,
                        top: 0,
                        opacity: 0,
                        transformPerspective: 600
                    }, {
                        left: 0 - e * i.slotw + "px",
                        ease: punchgs.Power2.easeOut,
                        force3D: "auto",
                        top: "0px",
                        width: i.width,
                        height: i.height,
                        opacity: 1,
                        rotation: 0,
                        delay: .1
                    }), 0), z.add(P, 0)
                })
            }
            if (8 == h) {
                C = 3 * C, C > i.delay && (C = i.delay);
                var P = new punchgs.TimelineLite;
                r.find(".slotslide").each(function() {
                    var e = t(this).find("div");
                    P.add(punchgs.TweenLite.to(e, C / 1e3, {
                        left: 0 - i.width / 2 + "px",
                        top: 0 - i.sloth / 2 + "px",
                        width: 2 * i.width + "px",
                        height: 2 * i.sloth + "px",
                        force3D: "auto",
                        opacity: 0,
                        rotation: i.rotate
                    }), 0), z.add(P, 0)
                }), d.find(".slotslide").each(function(e) {
                    var a = t(this).find("div");
                    P.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        left: 0,
                        top: 0,
                        opacity: 0,
                        force3D: "auto"
                    }, {
                        left: "0px",
                        top: 0 - e * i.sloth + "px",
                        width: d.find(".defaultimg").data("neww") + "px",
                        height: d.find(".defaultimg").data("newh") + "px",
                        opacity: 1,
                        rotation: 0
                    }), 0), z.add(P, 0)
                })
            }
            if (9 == h || 10 == h) {
                var H = 0;
                d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    H++, z.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        autoAlpha: 0,
                        force3D: "auto",
                        transformPerspective: 600
                    }, {
                        autoAlpha: 1,
                        ease: punchgs.Power2.easeInOut,
                        delay: 5 * e / 1e3
                    }), 0)
                })
            }
            if (11 == h || 26 == h) {
                var H = 0;
                26 == h && (C = 0), d.find(".slotslide").each(function() {
                    var e = t(this);
                    z.add(punchgs.TweenLite.from(e, C / 1e3, {
                        autoAlpha: 0,
                        force3D: "auto",
                        ease: punchgs.Power2.easeInOut
                    }), 0)
                })
            }
            if (12 == h || 13 == h || 14 == h || 15 == h) {
                C = C, C > i.delay && (C = i.delay), setTimeout(function() {
                    punchgs.TweenLite.set(r.find(".defaultimg"), {
                        autoAlpha: 0
                    })
                }, 100);
                var W = i.width,
                    X = i.height,
                    Y = d.find(".slotslide"),
                    F = 0,
                    R = 0,
                    B = 1,
                    V = 1,
                    q = 1,
                    E = punchgs.Power2.easeInOut,
                    j = punchgs.Power2.easeInOut,
                    Z = C / 1e3,
                    U = Z;
                ("on" == i.fullWidth || "on" == i.fullScreen) && (W = Y.width(), X = Y.height()), 12 == h ? F = W : 15 == h ? F = 0 - W : 13 == h ? R = X : 14 == h && (R = 0 - X), 1 == f && (B = 0), 2 == f && (B = 0), 3 == f && (E = punchgs.Power2.easeInOut, j = punchgs.Power1.easeInOut, Z = C / 1200), (4 == f || 5 == f) && (V = .6), 6 == f && (V = 1.4), (5 == f || 6 == f) && (q = 1.4, B = 0, W = 0, X = 0, F = 0, R = 0), 6 == f && (q = .6);
                z.add(punchgs.TweenLite.from(Y, Z, {
                    left: F,
                    top: R,
                    scale: q,
                    opacity: B,
                    rotation: i.rotate,
                    ease: j,
                    force3D: "auto"
                }), 0);
                var G = r.find(".slotslide");
                if ((4 == f || 5 == f) && (W = 0, X = 0), 1 != f) switch (h) {
                    case 12:
                        z.add(punchgs.TweenLite.to(G, U, {
                            left: 0 - W + "px",
                            force3D: "auto",
                            scale: V,
                            opacity: B,
                            rotation: i.rotate,
                            ease: E
                        }), 0);
                        break;
                    case 15:
                        z.add(punchgs.TweenLite.to(G, U, {
                            left: W + "px",
                            force3D: "auto",
                            scale: V,
                            opacity: B,
                            rotation: i.rotate,
                            ease: E
                        }), 0);
                        break;
                    case 13:
                        z.add(punchgs.TweenLite.to(G, U, {
                            top: 0 - X + "px",
                            force3D: "auto",
                            scale: V,
                            opacity: B,
                            rotation: i.rotate,
                            ease: E
                        }), 0);
                        break;
                    case 14:
                        z.add(punchgs.TweenLite.to(G, U, {
                            top: X + "px",
                            force3D: "auto",
                            scale: V,
                            opacity: B,
                            rotation: i.rotate,
                            ease: E
                        }), 0)
                }
            }
            if (16 == h) {
                var P = new punchgs.TimelineLite;
                z.add(punchgs.TweenLite.set(o, {
                    position: "absolute",
                    "z-index": 20
                }), 0), z.add(punchgs.TweenLite.set(n, {
                    position: "absolute",
                    "z-index": 15
                }), 0), o.wrapInner('<div class="tp-half-one" style="position:relative; width:100%;height:100%"></div>'), o.find(".tp-half-one").clone(!0).appendTo(o).addClass("tp-half-two"), o.find(".tp-half-two").removeClass("tp-half-one");
                var W = i.width,
                    X = i.height;
                "on" == i.autoHeight && (X = a.height()), o.find(".tp-half-one .defaultimg").wrap('<div class="tp-papercut" style="width:' + W + "px;height:" + X + 'px;"></div>'), o.find(".tp-half-two .defaultimg").wrap('<div class="tp-papercut" style="width:' + W + "px;height:" + X + 'px;"></div>'), o.find(".tp-half-two .defaultimg").css({
                    position: "absolute",
                    top: "-50%"
                }), o.find(".tp-half-two .tp-caption").wrapAll('<div style="position:absolute;top:-50%;left:0px;"></div>'), z.add(punchgs.TweenLite.set(o.find(".tp-half-two"), {
                    width: W,
                    height: X,
                    overflow: "hidden",
                    zIndex: 15,
                    position: "absolute",
                    top: X / 2,
                    left: "0px",
                    transformPerspective: 600,
                    transformOrigin: "center bottom"
                }), 0), z.add(punchgs.TweenLite.set(o.find(".tp-half-one"), {
                    width: W,
                    height: X / 2,
                    overflow: "visible",
                    zIndex: 10,
                    position: "absolute",
                    top: "0px",
                    left: "0px",
                    transformPerspective: 600,
                    transformOrigin: "center top"
                }), 0);
                var $ = (o.find(".defaultimg"), Math.round(20 * Math.random() - 10)),
                    J = Math.round(20 * Math.random() - 10),
                    Q = Math.round(20 * Math.random() - 10),
                    te = .4 * Math.random() - .2,
                    ee = .4 * Math.random() - .2,
                    ae = 1 * Math.random() + 1,
                    ie = 1 * Math.random() + 1,
                    ne = .3 * Math.random() + .3;
                z.add(punchgs.TweenLite.set(o.find(".tp-half-one"), {
                    overflow: "hidden"
                }), 0), z.add(punchgs.TweenLite.fromTo(o.find(".tp-half-one"), C / 800, {
                    width: W,
                    height: X / 2,
                    position: "absolute",
                    top: "0px",
                    left: "0px",
                    force3D: "auto",
                    transformOrigin: "center top"
                }, {
                    scale: ae,
                    rotation: $,
                    y: 0 - X - X / 4,
                    autoAlpha: 0,
                    ease: punchgs.Power2.easeInOut
                }), 0), z.add(punchgs.TweenLite.fromTo(o.find(".tp-half-two"), C / 800, {
                    width: W,
                    height: X,
                    overflow: "hidden",
                    position: "absolute",
                    top: X / 2,
                    left: "0px",
                    force3D: "auto",
                    transformOrigin: "center bottom"
                }, {
                    scale: ie,
                    rotation: J,
                    y: X + X / 4,
                    ease: punchgs.Power2.easeInOut,
                    autoAlpha: 0,
                    onComplete: function() {
                        punchgs.TweenLite.set(o, {
                            position: "absolute",
                            "z-index": 15
                        }), punchgs.TweenLite.set(n, {
                            position: "absolute",
                            "z-index": 20
                        }), o.find(".tp-half-one").length > 0 && (o.find(".tp-half-one .defaultimg").unwrap(), o.find(".tp-half-one .slotholder").unwrap()), o.find(".tp-half-two").remove()
                    }
                }), 0), P.add(punchgs.TweenLite.set(d.find(".defaultimg"), {
                    autoAlpha: 1
                }), 0), null != o.html() && z.add(punchgs.TweenLite.fromTo(n, (C - 200) / 1e3, {
                    scale: ne,
                    x: i.width / 4 * te,
                    y: X / 4 * ee,
                    rotation: Q,
                    force3D: "auto",
                    transformOrigin: "center center",
                    ease: punchgs.Power2.easeOut
                }, {
                    autoAlpha: 1,
                    scale: 1,
                    x: 0,
                    y: 0,
                    rotation: 0
                }), 0), z.add(P, 0)
            }
            if (17 == h && d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    z.add(punchgs.TweenLite.fromTo(a, C / 800, {
                        opacity: 0,
                        rotationY: 0,
                        scale: .9,
                        rotationX: -110,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: "center center"
                    }, {
                        opacity: 1,
                        top: 0,
                        left: 0,
                        scale: 1,
                        rotation: 0,
                        rotationX: 0,
                        force3D: "auto",
                        rotationY: 0,
                        ease: punchgs.Power3.easeOut,
                        delay: .06 * e
                    }), 0)
                }), 18 == h && d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    z.add(punchgs.TweenLite.fromTo(a, C / 500, {
                        autoAlpha: 0,
                        rotationY: 310,
                        scale: .9,
                        rotationX: 10,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: "center center"
                    }, {
                        autoAlpha: 1,
                        top: 0,
                        left: 0,
                        scale: 1,
                        rotation: 0,
                        rotationX: 0,
                        force3D: "auto",
                        rotationY: 0,
                        ease: punchgs.Power3.easeOut,
                        delay: .06 * e
                    }), 0)
                }), 19 == h || 22 == h) {
                var P = new punchgs.TimelineLite;
                z.add(punchgs.TweenLite.set(o, {
                    zIndex: 20
                }), 0), z.add(punchgs.TweenLite.set(n, {
                    zIndex: 20
                }), 0), setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100);
                var oe = (n.css("z-index"), o.css("z-index"), 90),
                    B = 1,
                    re = "center center ";
                1 == L && (oe = -90), 19 == h ? (re = re + "-" + i.height / 2, B = 0) : re += i.height / 2, punchgs.TweenLite.set(a, {
                    transformStyle: "flat",
                    backfaceVisibility: "hidden",
                    transformPerspective: 600
                }), d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    P.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        transformStyle: "flat",
                        backfaceVisibility: "hidden",
                        left: 0,
                        rotationY: i.rotate,
                        z: 10,
                        top: 0,
                        scale: 1,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationX: oe
                    }, {
                        left: 0,
                        rotationY: 0,
                        top: 0,
                        z: 0,
                        scale: 1,
                        force3D: "auto",
                        rotationX: 0,
                        delay: 50 * e / 1e3,
                        ease: punchgs.Power2.easeInOut
                    }), 0), P.add(punchgs.TweenLite.to(a, .1, {
                        autoAlpha: 1,
                        delay: 50 * e / 1e3
                    }), 0), z.add(P)
                }), r.find(".slotslide").each(function(e) {
                    var a = t(this),
                        n = -90;
                    1 == L && (n = 90), P.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        transformStyle: "flat",
                        backfaceVisibility: "hidden",
                        autoAlpha: 1,
                        rotationY: 0,
                        top: 0,
                        z: 0,
                        scale: 1,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationX: 0
                    }, {
                        autoAlpha: 1,
                        rotationY: i.rotate,
                        top: 0,
                        z: 10,
                        scale: 1,
                        rotationX: n,
                        delay: 50 * e / 1e3,
                        force3D: "auto",
                        ease: punchgs.Power2.easeInOut
                    }), 0), z.add(P)
                })
            }
            if (20 == h) {
                setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100); {
                    n.css("z-index"), o.css("z-index")
                }
                if (1 == L) var se = -i.width,
                    oe = 70,
                    re = "left center -" + i.height / 2;
                else var se = i.width,
                    oe = -70,
                    re = "right center -" + i.height / 2;
                d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    z.add(punchgs.TweenLite.fromTo(a, C / 1500, {
                        left: se,
                        rotationX: 40,
                        z: -600,
                        opacity: B,
                        top: 0,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationY: oe
                    }, {
                        left: 0,
                        delay: 50 * e / 1e3,
                        ease: punchgs.Power2.easeInOut
                    }), 0), z.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        rotationX: 40,
                        z: -600,
                        opacity: B,
                        top: 0,
                        scale: 1,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationY: oe
                    }, {
                        rotationX: 0,
                        opacity: 1,
                        top: 0,
                        z: 0,
                        scale: 1,
                        rotationY: 0,
                        delay: 50 * e / 1e3,
                        ease: punchgs.Power2.easeInOut
                    }), 0), z.add(punchgs.TweenLite.to(a, .1, {
                        opacity: 1,
                        force3D: "auto",
                        delay: 50 * e / 1e3 + C / 2e3
                    }), 0)
                }), r.find(".slotslide").each(function(e) {
                    var a = t(this);
                    if (1 != L) var n = -i.width,
                        o = 70,
                        r = "left center -" + i.height / 2;
                    else var n = i.width,
                        o = -70,
                        r = "right center -" + i.height / 2;
                    z.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        opacity: 1,
                        rotationX: 0,
                        top: 0,
                        z: 0,
                        scale: 1,
                        left: 0,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: r,
                        rotationY: 0
                    }, {
                        opacity: 1,
                        rotationX: 40,
                        top: 0,
                        z: -600,
                        left: n,
                        force3D: "auto",
                        scale: .8,
                        rotationY: o,
                        delay: 50 * e / 1e3,
                        ease: punchgs.Power2.easeInOut
                    }), 0), z.add(punchgs.TweenLite.to(a, .1, {
                        force3D: "auto",
                        opacity: 0,
                        delay: 50 * e / 1e3 + (C / 1e3 - C / 1e4)
                    }), 0)
                })
            }
            if (21 == h || 25 == h) {
                setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100);
                var oe = (n.css("z-index"), o.css("z-index"), 90),
                    se = -i.width,
                    de = -oe;
                if (1 == L)
                    if (25 == h) {
                        var re = "center top 0";
                        oe = i.rotate
                    } else {
                        var re = "left center 0";
                        de = i.rotate
                    }
                else if (se = i.width, oe = -90, 25 == h) {
                    var re = "center bottom 0";
                    de = -oe, oe = i.rotate
                } else {
                    var re = "right center 0";
                    de = i.rotate
                }
                d.find(".slotslide").each(function() {
                    var e = t(this);
                    z.add(punchgs.TweenLite.fromTo(e, C / 1e3, {
                        left: 0,
                        transformStyle: "flat",
                        rotationX: de,
                        z: 0,
                        autoAlpha: 0,
                        top: 0,
                        scale: 1,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationY: oe
                    }, {
                        left: 0,
                        rotationX: 0,
                        top: 0,
                        z: 0,
                        autoAlpha: 1,
                        scale: 1,
                        rotationY: 0,
                        force3D: "auto",
                        ease: punchgs.Power3.easeInOut
                    }), 0)
                }), 1 != L ? (se = -i.width, oe = 90, 25 == h ? (re = "center top 0", de = -oe, oe = i.rotate) : (re = "left center 0", de = i.rotate)) : (se = i.width, oe = -90, 25 == h ? (re = "center bottom 0", de = -oe, oe = i.rotate) : (re = "right center 0", de = i.rotate)), r.find(".slotslide").each(function() {
                    var e = t(this);
                    z.add(punchgs.TweenLite.fromTo(e, C / 1e3, {
                        left: 0,
                        transformStyle: "flat",
                        rotationX: 0,
                        z: 0,
                        autoAlpha: 1,
                        top: 0,
                        scale: 1,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationY: 0
                    }, {
                        left: 0,
                        rotationX: de,
                        top: 0,
                        z: 0,
                        autoAlpha: 1,
                        force3D: "auto",
                        scale: 1,
                        rotationY: oe,
                        ease: punchgs.Power1.easeInOut
                    }), 0)
                })
            }
            if (23 == h || 24 == h) {
                setTimeout(function() {
                    r.find(".defaultimg").css({
                        opacity: 0
                    })
                }, 100);
                var oe = (n.css("z-index"), o.css("z-index"), -90),
                    B = 1,
                    le = 0;
                if (1 == L && (oe = 90), 23 == h) {
                    var re = "center center -" + i.width / 2;
                    B = 0
                } else var re = "center center " + i.width / 2;
                punchgs.TweenLite.set(a, {
                    transformStyle: "preserve-3d",
                    backfaceVisibility: "hidden",
                    perspective: 2500
                }), d.find(".slotslide").each(function(e) {
                    var a = t(this);
                    z.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        left: le,
                        rotationX: i.rotate,
                        force3D: "auto",
                        opacity: B,
                        top: 0,
                        scale: 1,
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationY: oe
                    }, {
                        left: 0,
                        rotationX: 0,
                        autoAlpha: 1,
                        top: 0,
                        z: 0,
                        scale: 1,
                        rotationY: 0,
                        delay: 50 * e / 500,
                        ease: punchgs.Power2.easeInOut
                    }), 0)
                }), oe = 90, 1 == L && (oe = -90), r.find(".slotslide").each(function(e) {
                    var a = t(this);
                    z.add(punchgs.TweenLite.fromTo(a, C / 1e3, {
                        left: 0,
                        autoAlpha: 1,
                        rotationX: 0,
                        top: 0,
                        z: 0,
                        scale: 1,
                        force3D: "auto",
                        transformPerspective: 600,
                        transformOrigin: re,
                        rotationY: 0
                    }, {
                        left: le,
                        autoAlpha: 1,
                        rotationX: i.rotate,
                        top: 0,
                        scale: 1,
                        rotationY: oe,
                        delay: 50 * e / 500,
                        ease: punchgs.Power2.easeInOut
                    }), 0)
                })
            }
            z.pause(), _(n, i, null, z), punchgs.TweenLite.to(n, .001, {
                autoAlpha: 1
            });
            var he = {};
            he.slideIndex = i.next + 1, he.slide = n, a.trigger("revolution.slide.onchange", he), setTimeout(function() {
                a.trigger("revolution.slide.onafterswap")
            }, C), a.trigger("revolution.slide.onvideostop")
        },
        I = function(t, e, a, i, n, o, r) {
            punchgs.TweenLite.to(a.find(".defaultimg"), .001, {
                autoAlpha: 1,
                onComplete: function() {
                    y(t, e, n)
                }
            }), n.index() != o.index() && punchgs.TweenLite.to(o, .2, {
                autoAlpha: 0,
                onComplete: function() {
                    y(t, e, o)
                }
            }), e.act = e.next, "thumb" == e.navigationType && ee(t), "on" == a.data("kenburns") && N(t, e), t.find(".current-sr-slide-visible").removeClass("current-sr-slide-visible"), n.addClass("current-sr-slide-visible"), ("scroll" == e.parallax || "scroll+mouse" == e.parallax || "mouse+scroll" == e.parallax) && K(t, e), r.clear()
        },
        A = function(e) {
            var a = e.target.getVideoEmbedCode(),
                i = t("#" + a.split('id="')[1].split('"')[0]),
                n = i.closest(".tp-simpleresponsive"),
                o = i.parent().data("player");
            if (e.data == YT.PlayerState.PLAYING) {
                var r = n.find(".tp-bannertimer"),
                    s = r.data("opt");
                "mute" == i.closest(".tp-caption").data("volume") && o.mute(), s.videoplaying = !0, n.trigger("stoptimer"), n.trigger("revolution.slide.onvideoplay")
            } else {
                var r = n.find(".tp-bannertimer"),
                    s = r.data("opt"); - 1 != e.data && 3 != e.data && (s.videoplaying = !1, n.trigger("starttimer"), n.trigger("revolution.slide.onvideostop")), 0 == e.data && 1 == s.nextslideatend ? s.container.revnext() : (s.videoplaying = !1, n.trigger("starttimer"), n.trigger("revolution.slide.onvideostop"))
            }
        },
        M = function(t, e, a) {
            t.addEventListener ? t.addEventListener(e, a, !1) : t.attachEvent(e, a, !1)
        },
        S = function(e, a) {
            var i = $f(e),
                n = t("#" + e),
                o = n.closest(".tp-simpleresponsive"),
                r = n.closest(".tp-caption");
            setTimeout(function() {
                i.addEvent("ready", function() {
                    a && i.api("play"), i.addEvent("play", function() {
                        var t = o.find(".tp-bannertimer"),
                            e = t.data("opt");
                        e.videoplaying = !0, o.trigger("stoptimer"), "mute" == r.data("volume") && i.api("setVolume", "0")
                    }), i.addEvent("finish", function() {
                        var t = o.find(".tp-bannertimer"),
                            e = t.data("opt");
                        e.videoplaying = !1, o.trigger("starttimer"), o.trigger("revolution.slide.onvideoplay"), 1 == e.nextslideatend && e.container.revnext()
                    }), i.addEvent("pause", function() {
                        var t = o.find(".tp-bannertimer"),
                            e = t.data("opt");
                        e.videoplaying = !1, o.trigger("starttimer"), o.trigger("revolution.slide.onvideostop")
                    }), r.find(".tp-thumb-image").click(function() {
                        punchgs.TweenLite.to(t(this), .3, {
                            autoAlpha: 0,
                            force3D: "auto",
                            ease: punchgs.Power3.easeInOut
                        }), i.api("play")
                    })
                })
            }, 150)
        },
        P = function(t, a) {
            var i = a.width(),
                n = a.height(),
                o = t.data("mediaAspect");
            o == e && (o = 1);
            var r = i / n;
            t.css({
                position: "absolute"
            });
            t.find("video");
            o > r ? punchgs.TweenLite.to(t, 1e-4, {
                width: n * o,
                force3D: "auto",
                top: 0,
                left: 0 - (n * o - i) / 2,
                height: n
            }) : punchgs.TweenLite.to(t, 1e-4, {
                width: i,
                force3D: "auto",
                top: 0 - (i / o - n) / 2,
                left: 0,
                height: i / o
            })
        },
        D = function() {
            var t = new Object;
            return t.x = 0, t.y = 0, t.rotationX = 0, t.rotationY = 0, t.rotationZ = 0, t.scale = 1, t.scaleX = 1, t.scaleY = 1, t.skewX = 0, t.skewY = 0, t.opacity = 0, t.transformOrigin = "center, center", t.transformPerspective = 400, t.rotation = 0, t
        },
        H = function(e, a) {
            var i = a.split(";");
            return t.each(i, function(t, a) {
                a = a.split(":");
                var i = a[0],
                    n = a[1];
                "rotationX" == i && (e.rotationX = parseInt(n, 0)), "rotationY" == i && (e.rotationY = parseInt(n, 0)), "rotationZ" == i && (e.rotationZ = parseInt(n, 0)), "rotationZ" == i && (e.rotation = parseInt(n, 0)), "scaleX" == i && (e.scaleX = parseFloat(n)), "scaleY" == i && (e.scaleY = parseFloat(n)), "opacity" == i && (e.opacity = parseFloat(n)), "skewX" == i && (e.skewX = parseInt(n, 0)), "skewY" == i && (e.skewY = parseInt(n, 0)), "x" == i && (e.x = parseInt(n, 0)), "y" == i && (e.y = parseInt(n, 0)), "z" == i && (e.z = parseInt(n, 0)), "transformOrigin" == i && (e.transformOrigin = n.toString()), "transformPerspective" == i && (e.transformPerspective = parseInt(n, 0))
            }), e
        },
        W = function(e) {
            var a = e.split("animation:"),
                i = new Object;
            i.animation = H(D(), a[1]);
            var n = a[0].split(";");
            return t.each(n, function(t, e) {
                e = e.split(":");
                var a = e[0],
                    n = e[1];
                "typ" == a && (i.typ = n), "speed" == a && (i.speed = parseInt(n, 0) / 1e3), "start" == a && (i.start = parseInt(n, 0) / 1e3), "elementdelay" == a && (i.elementdelay = parseFloat(n)), "ease" == a && (i.ease = n)
            }), i
        },
        _ = function(a, i, n, o) {
            function r() {}

            function d() {}
            a.data("ctl") == e && a.data("ctl", new punchgs.TimelineLite);
            var l = a.data("ctl"),
                h = 0,
                c = 0,
                p = a.find(".tp-caption"),
                u = i.container.find(".tp-static-layers").find(".tp-caption");
            l.pause(), t.each(u, function(t, e) {
                p.push(e)
            }), p.each(function(a) {
                var o = n,
                    l = -1,
                    p = t(this);
                if (p.hasClass("tp-static-layer")) {
                    var u = p.data("startslide"),
                        f = p.data("endslide");
                    (-1 == u || "-1" == u) && p.data("startslide", 0), (-1 == f || "-1" == f) && p.data("endslide", i.slideamount), 0 == u && f == i.slideamount - 1 && p.data("endslide", i.slideamount + 1), u = p.data("startslide"), f = p.data("endslide"), p.hasClass("tp-is-shown") ? l = f == i.next || u > i.next || f < i.next ? 2 : 0 : u <= i.next && f >= i.next || u == i.next || f == i.next ? (p.addClass("tp-is-shown"), l = 1) : l = 0
                }
                h = i.width / 2 - i.startwidth * i.bw / 2; {
                    var g = i.bw;
                    i.bh
                }
                "on" == i.fullScreen && (c = i.height / 2 - i.startheight * i.bh / 2), ("on" == i.autoHeight || i.minHeight != e && i.minHeight > 0) && (c = i.container.height() / 2 - i.startheight * i.bh / 2), 0 > c && (c = 0);
                var m = 0;
                if (i.width < i.hideCaptionAtLimit && "on" == p.data("captionhidden") ? (p.addClass("tp-hidden-caption"), m = 1) : i.width < i.hideAllCaptionAtLimit || i.width < i.hideAllCaptionAtLilmit ? (p.addClass("tp-hidden-caption"), m = 1) : p.removeClass("tp-hidden-caption"), 0 == m) {
                    if (p.data("linktoslide") == e || p.hasClass("hasclicklistener") || (p.addClass("hasclicklistener"), p.css({
                            cursor: "pointer"
                        }), "no" != p.data("linktoslide") && p.click(function() {
                            var e = t(this),
                                a = e.data("linktoslide");
                            "next" != a && "prev" != a ? (i.container.data("showus", a), i.container.parent().find(".tp-rightarrow").click()) : "next" == a ? i.container.parent().find(".tp-rightarrow").click() : "prev" == a && i.container.parent().find(".tp-leftarrow").click()
                        })), 0 > h && (h = 0), p.hasClass("tp-videolayer") || p.find("iframe").length > 0 || p.find("video").length > 0) {
                        var v = "iframe" + Math.round(1e5 * Math.random() + 1),
                            w = p.data("videowidth"),
                            b = p.data("videoheight"),
                            y = p.data("videoattributes"),
                            x = p.data("ytid"),
                            T = p.data("vimeoid"),
                            k = p.data("videpreload"),
                            L = p.data("videomp4"),
                            C = p.data("videowebm"),
                            z = p.data("videoogv"),
                            O = p.data("videocontrols"),
                            I = "http",
                            _ = "loop" == p.data("videoloop") ? "loop" : "loopandnoslidestop" == p.data("videoloop") ? "loop" : "";
                        if (p.data("thumbimage") != e && p.data("videoposter") == e && p.data("videoposter", p.data("thumbimage")), x != e && String(x).length > 1 && 0 == p.find("iframe").length && (I = "https", "none" == O && (y = y.replace("controls=1", "controls=0"), -1 == y.toLowerCase().indexOf("controls") && (y += "&controls=0")), p.append('<iframe style="visible:hidden" src="' + I + "://www.youtube.com/embed/" + x + "?" + y + '" width="' + w + '" height="' + b + '" style="width:' + w + "px;height:" + b + 'px"></iframe>')), T != e && String(T).length > 1 && 0 == p.find("iframe").length && ("https:" === location.protocol && (I = "https"), p.append('<iframe style="visible:hidden" src="' + I + "://player.vimeo.com/video/" + T + "?" + y + '" width="' + w + '" height="' + b + '" style="width:' + w + "px;height:" + b + 'px"></iframe>')), (L != e || C != e) && 0 == p.find("video").length) {
                            "controls" != O && (O = "");
                            var B = '<video style="visible:hidden" class="" ' + _ + ' preload="' + k + '" width="' + w + '" height="' + b + '"';
                            p.data("videoposter") != e && p.data("videoposter") != e && (B = B + 'poster="' + p.data("videoposter") + '">'), C != e && "firefox" == X().toLowerCase() && (B = B + '<source src="' + C + '" type="video/webm" />'), L != e && (B = B + '<source src="' + L + '" type="video/mp4" />'), z != e && (B = B + '<source src="' + z + '" type="video/ogg" />'), B += "</video>", p.append(B), "controls" == O && p.append('<div class="tp-video-controls"><div class="tp-video-button-wrap"><button type="button" class="tp-video-button tp-vid-play-pause">Play</button></div><div class="tp-video-seek-bar-wrap"><input  type="range" class="tp-seek-bar" value="0"></div><div class="tp-video-button-wrap"><button  type="button" class="tp-video-button tp-vid-mute">Mute</button></div><div class="tp-video-vol-bar-wrap"><input  type="range" class="tp-volume-bar" min="0" max="1" step="0.1" value="1"></div><div class="tp-video-button-wrap"><button  type="button" class="tp-video-button tp-vid-full-screen">Full-Screen</button></div></div>')
                        }
                        var E = !1;
                        (1 == p.data("autoplayonlyfirsttime") || "true" == p.data("autoplayonlyfirsttime") || 1 == p.data("autoplay")) && (p.data("autoplay", !0), E = !0), p.find("iframe").each(function() {
                            var a = t(this);
                            if (punchgs.TweenLite.to(a, .1, {
                                    autoAlpha: 1,
                                    zIndex: 0,
                                    transformStyle: "preserve-3d",
                                    z: 0,
                                    rotationX: 0,
                                    force3D: "auto"
                                }), Z()) {
                                var r = a.attr("src");
                                a.attr("src", ""), a.attr("src", r)
                            }
                            if (i.nextslideatend = p.data("nextslideatend"), p.data("videoposter") != e && p.data("videoposter").length > 2 && 1 != p.data("autoplay") && !o && (0 == p.find(".tp-thumb-image").length ? p.append('<div class="tp-thumb-image" style="cursor:pointer; position:absolute;top:0px;left:0px;width:100%;height:100%;background-image:url(' + p.data("videoposter") + '); background-size:cover"></div>') : punchgs.TweenLite.set(p.find(".tp-thumb-image"), {
                                    autoAlpha: 1
                                })), a.attr("src").toLowerCase().indexOf("youtube") >= 0)
                                if (a.hasClass("HasListener")) {
                                    if (!n) {
                                        var s = p.data("player");
                                        "on" != p.data("forcerewind") || Z() || s.seekTo(0), (!Z() && 1 == p.data("autoplay") || E) && p.data("timerplay", setTimeout(function() {
                                            s.playVideo()
                                        }, p.data("start")))
                                    }
                                } else try {
                                    a.attr("id", v);
                                    var s, d = setInterval(function() {
                                        YT != e && typeof YT.Player != e && "undefined" != typeof YT.Player && (s = new YT.Player(v, {
                                            events: {
                                                onStateChange: A,
                                                onReady: function(a) {
                                                    {
                                                        var i = a.target.getVideoEmbedCode(),
                                                            n = t("#" + i.split('id="')[1].split('"')[0]),
                                                            o = n.closest(".tp-caption"),
                                                            r = o.data("videorate");
                                                        o.data("videostart")
                                                    }
                                                    r != e && a.target.setPlaybackRate(parseFloat(r)), (!Z() && 1 == o.data("autoplay") || E) && o.data("timerplay", setTimeout(function() {
                                                        a.target.playVideo()
                                                    }, o.data("start"))), o.find(".tp-thumb-image").click(function() {
                                                        punchgs.TweenLite.to(t(this), .3, {
                                                            autoAlpha: 0,
                                                            force3D: "auto",
                                                            ease: punchgs.Power3.easeInOut
                                                        }), Z() || s.playVideo()
                                                    })
                                                }
                                            }
                                        })), a.addClass("HasListener"), p.data("player", s), clearInterval(d)
                                    }, 100)
                                } catch (l) {} else if (a.attr("src").toLowerCase().indexOf("vimeo") >= 0)
                                    if (a.hasClass("HasListener")) {
                                        if (!(n || Z() || 1 != p.data("autoplay") && "on" != p.data("forcerewind"))) {
                                            var a = p.find("iframe"),
                                                h = a.attr("id"),
                                                c = $f(h);
                                            "on" == p.data("forcerewind") && c.api("seekTo", 0), p.data("timerplay", setTimeout(function() {
                                                1 == p.data("autoplay") && c.api("play")
                                            }, p.data("start")))
                                        }
                                    } else {
                                        a.addClass("HasListener"), a.attr("id", v);
                                        for (var u, f = a.attr("src"), g = {}, m = f, w = /([^&=]+)=([^&]*)/g; u = w.exec(m);) g[decodeURIComponent(u[1])] = decodeURIComponent(u[2]);
                                        f = g.player_id != e ? f.replace(g.player_id, v) : f + "&player_id=" + v;
                                        try {
                                            f = f.replace("api=0", "api=1")
                                        } catch (l) {}
                                        f += "&api=1", a.attr("src", f);
                                        var s = p.find("iframe")[0],
                                            b = setInterval(function() {
                                                $f != e && typeof $f(v).api != e && "undefined" != typeof $f(v).api && ($f(s).addEvent("ready", function() {
                                                    S(v, E)
                                                }), clearInterval(b))
                                            }, 100)
                                    }
                        }), (Z() && 1 == p.data("disablevideoonmobile") || s(8)) && p.find("video").remove(), p.find("video").length > 0 && p.find("video").each(function() {
                            var a = this,
                                n = t(this);
                            n.parent().hasClass("html5vid") || n.wrap('<div class="html5vid" style="position:relative;top:0px;left:0px;width:auto;height:auto"></div>');
                            var o = n.parent();
                            M(a, "loadedmetadata", function(t) {
                                t.data("metaloaded", 1)
                            }(o)), clearInterval(o.data("interval")), o.data("interval", setInterval(function() {
                                if (1 == o.data("metaloaded") || 0 / 0 != a.duration) {
                                    if (clearInterval(o.data("interval")), !o.hasClass("HasListener")) {
                                        o.addClass("HasListener"), "none" != p.data("dottedoverlay") && p.data("dottedoverlay") != e && 1 != p.find(".tp-dottedoverlay").length && o.append('<div class="tp-dottedoverlay ' + p.data("dottedoverlay") + '"></div>'), n.attr("control") == e && (0 == o.find(".tp-video-play-button").length && o.append('<div class="tp-video-play-button"><i class="revicon-right-dir"></i><div class="tp-revstop"></div></div>'), o.find("video, .tp-poster, .tp-video-play-button").click(function() {
                                            o.hasClass("videoisplaying") ? a.pause() : a.play()
                                        })), (1 == p.data("forcecover") || p.hasClass("fullscreenvideo")) && (1 == p.data("forcecover") && (P(o, i.container), o.addClass("fullcoveredvideo"), p.addClass("fullcoveredvideo")), o.css({
                                            width: "100%",
                                            height: "100%"
                                        }));
                                        var t = p.find(".tp-vid-play-pause")[0],
                                            r = p.find(".tp-vid-mute")[0],
                                            s = p.find(".tp-vid-full-screen")[0],
                                            d = p.find(".tp-seek-bar")[0],
                                            l = p.find(".tp-volume-bar")[0];
                                        t != e && (M(t, "click", function() {
                                            1 == a.paused ? a.play() : a.pause()
                                        }), M(r, "click", function() {
                                            0 == a.muted ? (a.muted = !0, r.innerHTML = "Unmute") : (a.muted = !1, r.innerHTML = "Mute")
                                        }), M(s, "click", function() {
                                            a.requestFullscreen ? a.requestFullscreen() : a.mozRequestFullScreen ? a.mozRequestFullScreen() : a.webkitRequestFullscreen && a.webkitRequestFullscreen()
                                        }), M(d, "change", function() {
                                            var t = a.duration * (d.value / 100);
                                            a.currentTime = t
                                        }), M(a, "timeupdate", function() {
                                            var t = 100 / a.duration * a.currentTime;
                                            d.value = t
                                        }), M(d, "mousedown", function() {
                                            a.pause()
                                        }), M(d, "mouseup", function() {
                                            a.play()
                                        }), M(l, "change", function() {
                                            a.volume = l.value
                                        })), M(a, "play", function() {
                                            "mute" == p.data("volume") && (a.muted = !0), o.addClass("videoisplaying"), "loopandnoslidestop" == p.data("videoloop") ? (i.videoplaying = !1, i.container.trigger("starttimer"), i.container.trigger("revolution.slide.onvideostop")) : (i.videoplaying = !0, i.container.trigger("stoptimer"), i.container.trigger("revolution.slide.onvideoplay"));
                                            var t = p.find(".tp-vid-play-pause")[0],
                                                n = p.find(".tp-vid-mute")[0];
                                            t != e && (t.innerHTML = "Pause"), n != e && a.muted && (n.innerHTML = "Unmute")
                                        }), M(a, "pause", function() {
                                            o.removeClass("videoisplaying"), i.videoplaying = !1, i.container.trigger("starttimer"), i.container.trigger("revolution.slide.onvideostop");
                                            var t = p.find(".tp-vid-play-pause")[0];
                                            t != e && (t.innerHTML = "Play")
                                        }), M(a, "ended", function() {
                                            o.removeClass("videoisplaying"), i.videoplaying = !1, i.container.trigger("starttimer"), i.container.trigger("revolution.slide.onvideostop"), 1 == i.nextslideatend && i.container.revnext()
                                        })
                                    }
                                    var h = !1;
                                    (1 == p.data("autoplayonlyfirsttime") || "true" == p.data("autoplayonlyfirsttime")) && (h = !0);
                                    var c = 16 / 9;
                                    if ("4:3" == p.data("aspectratio") && (c = 4 / 3), o.data("mediaAspect", c), 1 == o.closest(".tp-caption").data("forcecover") && (P(o, i.container), o.addClass("fullcoveredvideo")), n.css({
                                            display: "block"
                                        }), i.nextslideatend = p.data("nextslideatend"), (1 == p.data("autoplay") || 1 == h) && ("loopandnoslidestop" == p.data("videoloop") ? (i.videoplaying = !1, i.container.trigger("starttimer"), i.container.trigger("revolution.slide.onvideostop")) : (i.videoplaying = !0, i.container.trigger("stoptimer"), i.container.trigger("revolution.slide.onvideoplay")), "on" != p.data("forcerewind") || o.hasClass("videoisplaying") || a.currentTime > 0 && (a.currentTime = 0), "mute" == p.data("volume") && (a.muted = !0), o.data("timerplay", setTimeout(function() {
                                            "on" != p.data("forcerewind") || o.hasClass("videoisplaying") || a.currentTime > 0 && (a.currentTime = 0), "mute" == p.data("volume") && (a.muted = !0), a.play()
                                        }, 10 + p.data("start")))), o.data("ww") == e && o.data("ww", n.attr("width")), o.data("hh") == e && o.data("hh", n.attr("height")), !p.hasClass("fullscreenvideo") && 1 == p.data("forcecover")) try {
                                        o.width(o.data("ww") * i.bw), o.height(o.data("hh") * i.bh)
                                    } catch (u) {}
                                    clearInterval(o.data("interval"))
                                }
                            }), 100)
                        }), 1 == p.data("autoplay") && (setTimeout(function() {
                            "loopandnoslidestop" != p.data("videoloop") && (i.videoplaying = !0, i.container.trigger("stoptimer"))
                        }, 200), "loopandnoslidestop" != p.data("videoloop") && (i.videoplaying = !0, i.container.trigger("stoptimer")), (1 == p.data("autoplayonlyfirsttime") || "true" == p.data("autoplayonlyfirsttime")) && (p.data("autoplay", !1), p.data("autoplayonlyfirsttime", !1)))
                    }
                    var j = 0,
                        U = 0;
                    if (p.find("img").length > 0) {
                        var N = p.find("img");
                        0 == N.width() && N.css({
                            width: "auto"
                        }), 0 == N.height() && N.css({
                            height: "auto"
                        }), N.data("ww") == e && N.width() > 0 && N.data("ww", N.width()), N.data("hh") == e && N.height() > 0 && N.data("hh", N.height());
                        var G = N.data("ww"),
                            $ = N.data("hh");
                        G == e && (G = 0), $ == e && ($ = 0), N.width(G * i.bw), N.height($ * i.bh), j = N.width(), U = N.height()
                    } else if (p.find("iframe").length > 0 || p.find("video").length > 0) {
                        var J = !1,
                            N = p.find("iframe");
                        0 == N.length && (N = p.find("video"), J = !0), N.css({
                            display: "block"
                        }), p.data("ww") == e && p.data("ww", N.width()), p.data("hh") == e && p.data("hh", N.height());
                        var G = p.data("ww"),
                            $ = p.data("hh"),
                            Q = p;
                        Q.data("fsize") == e && Q.data("fsize", parseInt(Q.css("font-size"), 0) || 0), Q.data("pt") == e && Q.data("pt", parseInt(Q.css("paddingTop"), 0) || 0), Q.data("pb") == e && Q.data("pb", parseInt(Q.css("paddingBottom"), 0) || 0), Q.data("pl") == e && Q.data("pl", parseInt(Q.css("paddingLeft"), 0) || 0), Q.data("pr") == e && Q.data("pr", parseInt(Q.css("paddingRight"), 0) || 0), Q.data("mt") == e && Q.data("mt", parseInt(Q.css("marginTop"), 0) || 0), Q.data("mb") == e && Q.data("mb", parseInt(Q.css("marginBottom"), 0) || 0), Q.data("ml") == e && Q.data("ml", parseInt(Q.css("marginLeft"), 0) || 0), Q.data("mr") == e && Q.data("mr", parseInt(Q.css("marginRight"), 0) || 0), Q.data("bt") == e && Q.data("bt", parseInt(Q.css("borderTop"), 0) || 0), Q.data("bb") == e && Q.data("bb", parseInt(Q.css("borderBottom"), 0) || 0), Q.data("bl") == e && Q.data("bl", parseInt(Q.css("borderLeft"), 0) || 0), Q.data("br") == e && Q.data("br", parseInt(Q.css("borderRight"), 0) || 0), Q.data("lh") == e && Q.data("lh", parseInt(Q.css("lineHeight"), 0) || 0), "auto" == Q.data("lh") && Q.data("lh", Q.data("fsize") + 4);
                        var K = i.width,
                            te = i.height;
                        if (K > i.startwidth && (K = i.startwidth), te > i.startheight && (te = i.startheight), p.hasClass("fullscreenvideo")) {
                            h = 0, c = 0, p.data("x", 0), p.data("y", 0);
                            var ee = i.height;
                            "on" == i.autoHeight && (ee = i.container.height()), p.css({
                                width: i.width,
                                height: ee
                            })
                        } else p.css({
                            "font-size": Q.data("fsize") * i.bw + "px",
                            "padding-top": Q.data("pt") * i.bh + "px",
                            "padding-bottom": Q.data("pb") * i.bh + "px",
                            "padding-left": Q.data("pl") * i.bw + "px",
                            "padding-right": Q.data("pr") * i.bw + "px",
                            "margin-top": Q.data("mt") * i.bh + "px",
                            "margin-bottom": Q.data("mb") * i.bh + "px",
                            "margin-left": Q.data("ml") * i.bw + "px",
                            "margin-right": Q.data("mr") * i.bw + "px",
                            "border-top": Q.data("bt") * i.bh + "px",
                            "border-bottom": Q.data("bb") * i.bh + "px",
                            "border-left": Q.data("bl") * i.bw + "px",
                            "border-right": Q.data("br") * i.bw + "px",
                            "line-height": Q.data("lh") * i.bh + "px",
                            height: $ * i.bh + "px"
                        });
                        0 == J ? (N.width(G * i.bw), N.height($ * i.bh)) : 1 == p.data("forcecover") || p.hasClass("fullscreenvideo") || (N.width(G * i.bw), N.height($ * i.bh)), j = N.width(), U = N.height()
                    } else {
                        p.find(".tp-resizeme, .tp-resizeme *").each(function() {
                            Y(t(this), i)
                        }), p.hasClass("tp-resizeme") && p.find("*").each(function() {
                            Y(t(this), i)
                        }), Y(p, i), U = p.outerHeight(!0), j = p.outerWidth(!0);
                        var ae = p.outerHeight(),
                            ie = p.css("backgroundColor");
                        p.find(".frontcorner").css({
                            borderWidth: ae + "px",
                            left: 0 - ae + "px",
                            borderRight: "0px solid transparent",
                            borderTopColor: ie
                        }), p.find(".frontcornertop").css({
                            borderWidth: ae + "px",
                            left: 0 - ae + "px",
                            borderRight: "0px solid transparent",
                            borderBottomColor: ie
                        }), p.find(".backcorner").css({
                            borderWidth: ae + "px",
                            right: 0 - ae + "px",
                            borderLeft: "0px solid transparent",
                            borderBottomColor: ie
                        }), p.find(".backcornertop").css({
                            borderWidth: ae + "px",
                            right: 0 - ae + "px",
                            borderLeft: "0px solid transparent",
                            borderTopColor: ie
                        })
                    }
                    "on" == i.fullScreenAlignForce && (h = 0, c = 0), p.data("voffset") == e && p.data("voffset", 0), p.data("hoffset") == e && p.data("hoffset", 0);
                    var ne = p.data("voffset") * g,
                        oe = p.data("hoffset") * g,
                        re = i.startwidth * g,
                        se = i.startheight * g;
                    "on" == i.fullScreenAlignForce && (re = i.container.width(), se = i.container.height()), ("center" == p.data("x") || "center" == p.data("xcenter")) && (p.data("xcenter", "center"), p.data("x", re / 2 - p.outerWidth(!0) / 2 + oe)), ("left" == p.data("x") || "left" == p.data("xleft")) && (p.data("xleft", "left"), p.data("x", 0 / g + oe)), ("right" == p.data("x") || "right" == p.data("xright")) && (p.data("xright", "right"), p.data("x", (re - p.outerWidth(!0) + oe) / g)), ("center" == p.data("y") || "center" == p.data("ycenter")) && (p.data("ycenter", "center"), p.data("y", se / 2 - p.outerHeight(!0) / 2 + ne)), ("top" == p.data("y") || "top" == p.data("ytop")) && (p.data("ytop", "top"), p.data("y", 0 / i.bh + ne)), ("bottom" == p.data("y") || "bottom" == p.data("ybottom")) && (p.data("ybottom", "bottom"), p.data("y", (se - p.outerHeight(!0) + ne) / g)), p.data("start") == e && p.data("start", 1e3);
                    var de = p.data("easing");
                    de == e && (de = "punchgs.Power1.easeOut");
                    var le = p.data("start") / 1e3,
                        he = p.data("speed") / 1e3;
                    if ("center" == p.data("x") || "center" == p.data("xcenter")) var ce = p.data("x") + h;
                    else var ce = g * p.data("x") + h;
                    if ("center" == p.data("y") || "center" == p.data("ycenter")) var pe = p.data("y") + c;
                    else var pe = i.bh * p.data("y") + c;
                    if (punchgs.TweenLite.set(p, {
                            top: pe,
                            left: ce,
                            overwrite: "auto"
                        }), 0 == l && (o = !0), p.data("timeline") == e || o || (2 != l && p.data("timeline").gotoAndPlay(0), o = !0), !o) {
                        p.data("timeline") != e;
                        var ue = new punchgs.TimelineLite({
                            smoothChildTiming: !0,
                            onStart: d
                        });
                        ue.pause(), "on" == i.fullScreenAlignForce;
                        var fe = p;
                        p.data("mySplitText") != e && p.data("mySplitText").revert(), ("chars" == p.data("splitin") || "words" == p.data("splitin") || "lines" == p.data("splitin") || "chars" == p.data("splitout") || "words" == p.data("splitout") || "lines" == p.data("splitout")) && (p.find("a").length > 0 ? p.data("mySplitText", new punchgs.SplitText(p.find("a"), {
                            type: "lines,words,chars",
                            charsClass: "tp-splitted",
                            wordsClass: "tp-splitted",
                            linesClass: "tp-splitted"
                        })) : p.find(".tp-layer-inner-rotation").length > 0 ? p.data("mySplitText", new punchgs.SplitText(p.find(".tp-layer-inner-rotation"), {
                            type: "lines,words,chars",
                            charsClass: "tp-splitted",
                            wordsClass: "tp-splitted",
                            linesClass: "tp-splitted"
                        })) : p.data("mySplitText", new punchgs.SplitText(p, {
                            type: "lines,words,chars",
                            charsClass: "tp-splitted",
                            wordsClass: "tp-splitted",
                            linesClass: "tp-splitted"
                        })), p.addClass("splitted")), "chars" == p.data("splitin") && (fe = p.data("mySplitText").chars), "words" == p.data("splitin") && (fe = p.data("mySplitText").words), "lines" == p.data("splitin") && (fe = p.data("mySplitText").lines);
                        var ge = D(),
                            me = D();
                        p.data("repeat") != e && (repeatV = p.data("repeat")), p.data("yoyo") != e && (yoyoV = p.data("yoyo")), p.data("repeatdelay") != e && (repeatdelayV = p.data("repeatdelay"));
                        var ve = p.attr("class");
                        ve.match("customin") ? ge = H(ge, p.data("customin")) : ve.match("randomrotate") ? (ge.scale = 3 * Math.random() + 1, ge.rotation = Math.round(200 * Math.random() - 100), ge.x = Math.round(200 * Math.random() - 100), ge.y = Math.round(200 * Math.random() - 100)) : ve.match("lfr") || ve.match("skewfromright") ? ge.x = 15 + i.width : ve.match("lfl") || ve.match("skewfromleft") ? ge.x = -15 - j : ve.match("sfl") || ve.match("skewfromleftshort") ? ge.x = -50 : ve.match("sfr") || ve.match("skewfromrightshort") ? ge.x = 50 : ve.match("lft") ? ge.y = -25 - U : ve.match("lfb") ? ge.y = 25 + i.height : ve.match("sft") ? ge.y = -50 : ve.match("sfb") && (ge.y = 50), ve.match("skewfromright") || p.hasClass("skewfromrightshort") ? ge.skewX = -85 : (ve.match("skewfromleft") || p.hasClass("skewfromleftshort")) && (ge.skewX = 85), (ve.match("fade") || ve.match("sft") || ve.match("sfl") || ve.match("sfb") || ve.match("skewfromleftshort") || ve.match("sfr") || ve.match("skewfromrightshort")) && (ge.opacity = 0), "safari" == X().toLowerCase();
                        var we = p.data("elementdelay") == e ? 0 : p.data("elementdelay");
                        me.ease = ge.ease = p.data("easing") == e ? punchgs.Power1.easeInOut : p.data("easing"), ge.data = new Object, ge.data.oldx = ge.x, ge.data.oldy = ge.y, me.data = new Object, me.data.oldx = me.x, me.data.oldy = me.y, ge.x = ge.x * g, ge.y = ge.y * g;
                        var be = new punchgs.TimelineLite;
                        if (2 != l)
                            if (ve.match("customin")) fe != p && ue.add(punchgs.TweenLite.set(p, {
                                force3D: "auto",
                                opacity: 1,
                                scaleX: 1,
                                scaleY: 1,
                                rotationX: 0,
                                rotationY: 0,
                                rotationZ: 0,
                                skewX: 0,
                                skewY: 0,
                                z: 0,
                                x: 0,
                                y: 0,
                                visibility: "visible",
                                delay: 0,
                                overwrite: "all"
                            })), ge.visibility = "hidden", me.visibility = "visible", me.overwrite = "all", me.opacity = 1, me.onComplete = r(), me.delay = le, me.force3D = "auto", ue.add(be.staggerFromTo(fe, he, ge, me, we), "frame0");
                            else if (ge.visibility = "visible", ge.transformPerspective = 600, fe != p && ue.add(punchgs.TweenLite.set(p, {
                                force3D: "auto",
                                opacity: 1,
                                scaleX: 1,
                                scaleY: 1,
                                rotationX: 0,
                                rotationY: 0,
                                rotationZ: 0,
                                skewX: 0,
                                skewY: 0,
                                z: 0,
                                x: 0,
                                y: 0,
                                visibility: "visible",
                                delay: 0,
                                overwrite: "all"
                            })), me.visibility = "visible", me.delay = le, me.onComplete = r(), me.opacity = 1, me.force3D = "auto", ve.match("randomrotate") && fe != p)
                            for (var a = 0; a < fe.length; a++) {
                                var ye = new Object,
                                    xe = new Object;
                                t.extend(ye, ge), t.extend(xe, me), ge.scale = 3 * Math.random() + 1, ge.rotation = Math.round(200 * Math.random() - 100), ge.x = Math.round(200 * Math.random() - 100), ge.y = Math.round(200 * Math.random() - 100), 0 != a && (xe.delay = le + a * we), ue.append(punchgs.TweenLite.fromTo(fe[a], he, ye, xe), "frame0")
                            } else ue.add(be.staggerFromTo(fe, he, ge, me, we), "frame0");
                        p.data("timeline", ue); {
                            new Array
                        }
                        if (p.data("frames") != e) {
                            var Te = p.data("frames");
                            Te = Te.replace(/\s+/g, ""), Te = Te.replace("{", "");
                            var ke = Te.split("}");
                            t.each(ke, function(t, e) {
                                if (e.length > 0) {
                                    var a = W(e);
                                    V(p, i, a, "frame" + (t + 10), g)
                                }
                            })
                        }
                        ue = p.data("timeline"), p.data("end") == e || -1 != l && 2 != l ? -1 == l || 2 == l ? q(p, i, 999999, ge, "frame99", g) : q(p, i, 200, ge, "frame99", g) : q(p, i, p.data("end") / 1e3, ge, "frame99", g), ue = p.data("timeline"), p.data("timeline", ue), F(p, g), ue.resume()
                    }
                }
                if (o && (R(p), F(p, g), p.data("timeline") != e)) {
                    var Le = p.data("timeline").getTweensOf();
                    t.each(Le, function(t, a) {
                        if (a.vars.data != e) {
                            var i = a.vars.data.oldx * g,
                                n = a.vars.data.oldy * g;
                            if (1 != a.progress() && 0 != a.progress()) try {
                                a.vars.x = i, a.vary.y = n
                            } catch (o) {} else 1 == a.progress() && punchgs.TweenLite.set(a.target, {
                                x: i,
                                y: n
                            })
                        }
                    })
                }
            });
            var f = t("body").find("#" + i.container.attr("id")).find(".tp-bannertimer");
            f.data("opt", i), o != e && setTimeout(function() {
                o.resume()
            }, 30)
        },
        X = function() {
            var t, e = navigator.appName,
                a = navigator.userAgent,
                i = a.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
            return i && null != (t = a.match(/version\/([\.\d]+)/i)) && (i[2] = t[1]), i = i ? [i[1], i[2]] : [e, navigator.appVersion, "-?"], i[0]
        },
        Y = function(t, a) {
            t.data("fsize") == e && t.data("fsize", parseInt(t.css("font-size"), 0) || 0), t.data("pt") == e && t.data("pt", parseInt(t.css("paddingTop"), 0) || 0), t.data("pb") == e && t.data("pb", parseInt(t.css("paddingBottom"), 0) || 0), t.data("pl") == e && t.data("pl", parseInt(t.css("paddingLeft"), 0) || 0), t.data("pr") == e && t.data("pr", parseInt(t.css("paddingRight"), 0) || 0), t.data("mt") == e && t.data("mt", parseInt(t.css("marginTop"), 0) || 0), t.data("mb") == e && t.data("mb", parseInt(t.css("marginBottom"), 0) || 0), t.data("ml") == e && t.data("ml", parseInt(t.css("marginLeft"), 0) || 0), t.data("mr") == e && t.data("mr", parseInt(t.css("marginRight"), 0) || 0), t.data("bt") == e && t.data("bt", parseInt(t.css("borderTopWidth"), 0) || 0), t.data("bb") == e && t.data("bb", parseInt(t.css("borderBottomWidth"), 0) || 0), t.data("bl") == e && t.data("bl", parseInt(t.css("borderLeftWidth"), 0) || 0), t.data("br") == e && t.data("br", parseInt(t.css("borderRightWidth"), 0) || 0), t.data("ls") == e && t.data("ls", parseInt(t.css("letterSpacing"), 0) || 0), t.data("lh") == e && t.data("lh", parseInt(t.css("lineHeight"), 0) || "auto"), t.data("minwidth") == e && t.data("minwidth", parseInt(t.css("minWidth"), 0) || 0), t.data("minheight") == e && t.data("minheight", parseInt(t.css("minHeight"), 0) || 0), t.data("maxwidth") == e && t.data("maxwidth", parseInt(t.css("maxWidth"), 0) || "none"), t.data("maxheight") == e && t.data("maxheight", parseInt(t.css("maxHeight"), 0) || "none"), t.data("wii") == e && t.data("wii", parseInt(t.css("width"), 0) || 0), t.data("hii") == e && t.data("hii", parseInt(t.css("height"), 0) || 0), t.data("wan") == e && t.data("wan", t.css("-webkit-transition")), t.data("moan") == e && t.data("moan", t.css("-moz-animation-transition")), t.data("man") == e && t.data("man", t.css("-ms-animation-transition")), t.data("ani") == e && t.data("ani", t.css("transition")), "auto" == t.data("lh") && t.data("lh", t.data("fsize") + 4), t.hasClass("tp-splitted") || (t.css("-webkit-transition", "none"), t.css("-moz-transition", "none"), t.css("-ms-transition", "none"), t.css("transition", "none"), punchgs.TweenLite.set(t, {
                fontSize: Math.round(t.data("fsize") * a.bw) + "px",
                letterSpacing: Math.floor(t.data("ls") * a.bw) + "px",
                paddingTop: Math.round(t.data("pt") * a.bh) + "px",
                paddingBottom: Math.round(t.data("pb") * a.bh) + "px",
                paddingLeft: Math.round(t.data("pl") * a.bw) + "px",
                paddingRight: Math.round(t.data("pr") * a.bw) + "px",
                marginTop: t.data("mt") * a.bh + "px",
                marginBottom: t.data("mb") * a.bh + "px",
                marginLeft: t.data("ml") * a.bw + "px",
                marginRight: t.data("mr") * a.bw + "px",
                borderTopWidth: Math.round(t.data("bt") * a.bh) + "px",
                borderBottomWidth: Math.round(t.data("bb") * a.bh) + "px",
                borderLeftWidth: Math.round(t.data("bl") * a.bw) + "px",
                borderRightWidth: Math.round(t.data("br") * a.bw) + "px",
                lineHeight: Math.round(t.data("lh") * a.bh) + "px",
                minWidth: t.data("minwidth") * a.bw + "px",
                minHeight: t.data("minheight") * a.bh + "px",
                overwrite: "auto"
            }), setTimeout(function() {
                t.css("-webkit-transition", t.data("wan")), t.css("-moz-transition", t.data("moan")), t.css("-ms-transition", t.data("man")), t.css("transition", t.data("ani"))
            }, 30), "none" != t.data("maxheight") && t.css({
                maxHeight: t.data("maxheight") * a.bh + "px"
            }), "none" != t.data("maxwidth") && t.css({
                maxWidth: t.data("maxwidth") * a.bw + "px"
            }))
        },
        F = function(a, i) {
            a.find(".rs-pendulum").each(function() {
                var a = t(this);
                if (a.data("timeline") == e) {
                    a.data("timeline", new punchgs.TimelineLite);
                    var n = a.data("startdeg") == e ? -20 : a.data("startdeg"),
                        o = a.data("enddeg") == e ? 20 : a.data("enddeg"),
                        r = a.data("speed") == e ? 2 : a.data("speed"),
                        s = a.data("origin") == e ? "50% 50%" : a.data("origin"),
                        d = a.data("easing") == e ? punchgs.Power2.easeInOut : a.data("ease");
                    n *= i, o *= i, a.data("timeline").append(new punchgs.TweenLite.fromTo(a, r, {
                        force3D: "auto",
                        rotation: n,
                        transformOrigin: s
                    }, {
                        rotation: o,
                        ease: d
                    })), a.data("timeline").append(new punchgs.TweenLite.fromTo(a, r, {
                        force3D: "auto",
                        rotation: o,
                        transformOrigin: s
                    }, {
                        rotation: n,
                        ease: d,
                        onComplete: function() {
                            a.data("timeline").restart()
                        }
                    }))
                }
            }), a.find(".rs-rotate").each(function() {
                var a = t(this);
                if (a.data("timeline") == e) {
                    a.data("timeline", new punchgs.TimelineLite);
                    var n = a.data("startdeg") == e ? 0 : a.data("startdeg"),
                        o = a.data("enddeg") == e ? 360 : a.data("enddeg");
                    speed = a.data("speed") == e ? 2 : a.data("speed"), origin = a.data("origin") == e ? "50% 50%" : a.data("origin"), easing = a.data("easing") == e ? punchgs.Power2.easeInOut : a.data("easing"), n *= i, o *= i, a.data("timeline").append(new punchgs.TweenLite.fromTo(a, speed, {
                        force3D: "auto",
                        rotation: n,
                        transformOrigin: origin
                    }, {
                        rotation: o,
                        ease: easing,
                        onComplete: function() {
                            a.data("timeline").restart()
                        }
                    }))
                }
            }), a.find(".rs-slideloop").each(function() {
                var a = t(this);
                if (a.data("timeline") == e) {
                    a.data("timeline", new punchgs.TimelineLite);
                    var n = a.data("xs") == e ? 0 : a.data("xs"),
                        o = a.data("ys") == e ? 0 : a.data("ys"),
                        r = a.data("xe") == e ? 0 : a.data("xe"),
                        s = a.data("ye") == e ? 0 : a.data("ye"),
                        d = a.data("speed") == e ? 2 : a.data("speed"),
                        l = a.data("easing") == e ? punchgs.Power2.easeInOut : a.data("easing");
                    n *= i, o *= i, r *= i, s *= i, a.data("timeline").append(new punchgs.TweenLite.fromTo(a, d, {
                        force3D: "auto",
                        x: n,
                        y: o
                    }, {
                        x: r,
                        y: s,
                        ease: l
                    })), a.data("timeline").append(new punchgs.TweenLite.fromTo(a, d, {
                        force3D: "auto",
                        x: r,
                        y: s
                    }, {
                        x: n,
                        y: o,
                        onComplete: function() {
                            a.data("timeline").restart()
                        }
                    }))
                }
            }), a.find(".rs-pulse").each(function() {
                var a = t(this);
                if (a.data("timeline") == e) {
                    a.data("timeline", new punchgs.TimelineLite);
                    var i = a.data("zoomstart") == e ? 0 : a.data("zoomstart"),
                        n = a.data("zoomend") == e ? 0 : a.data("zoomend"),
                        o = a.data("speed") == e ? 2 : a.data("speed"),
                        r = a.data("easing") == e ? punchgs.Power2.easeInOut : a.data("easing");
                    a.data("timeline").append(new punchgs.TweenLite.fromTo(a, o, {
                        force3D: "auto",
                        scale: i
                    }, {
                        scale: n,
                        ease: r
                    })), a.data("timeline").append(new punchgs.TweenLite.fromTo(a, o, {
                        force3D: "auto",
                        scale: n
                    }, {
                        scale: i,
                        onComplete: function() {
                            a.data("timeline").restart()
                        }
                    }))
                }
            }), a.find(".rs-wave").each(function() {
                var a = t(this);
                if (a.data("timeline") == e) {
                    a.data("timeline", new punchgs.TimelineLite); {
                        var n = a.data("angle") == e ? 10 : a.data("angle"),
                            o = a.data("radius") == e ? 10 : a.data("radius"),
                            r = a.data("speed") == e ? -20 : a.data("speed");
                        a.data("origin") == e ? -20 : a.data("origin")
                    }
                    n *= i, o *= i;
                    var s = {
                        a: 0,
                        ang: n,
                        element: a,
                        unit: o
                    };
                    a.data("timeline").append(new punchgs.TweenLite.fromTo(s, r, {
                        a: 360
                    }, {
                        a: 0,
                        force3D: "auto",
                        ease: punchgs.Linear.easeNone,
                        onUpdate: function() {
                            var t = s.a * (Math.PI / 180);
                            punchgs.TweenLite.to(s.element, .1, {
                                force3D: "auto",
                                x: Math.cos(t) * s.unit,
                                y: s.unit * (1 - Math.sin(t))
                            })
                        },
                        onComplete: function() {
                            a.data("timeline").restart()
                        }
                    }))
                }
            })
        },
        R = function(a) {
            a.find(".rs-pendulum, .rs-slideloop, .rs-pulse, .rs-wave").each(function() {
                var a = t(this);
                a.data("timeline") != e && (a.data("timeline").pause(), a.data("timeline", null))
            })
        },
        B = function(a, i) {
            var n = 0,
                o = a.find(".tp-caption"),
                r = i.container.find(".tp-static-layers").find(".tp-caption");
            return t.each(r, function(t, e) {
                o.push(e)
            }), o.each(function() {
                var a = -1,
                    o = t(this);
                if (o.hasClass("tp-static-layer") && ((-1 == o.data("startslide") || "-1" == o.data("startslide")) && o.data("startslide", 0), (-1 == o.data("endslide") || "-1" == o.data("endslide")) && o.data("endslide", i.slideamount), o.hasClass("tp-is-shown") ? o.data("startslide") > i.next || o.data("endslide") < i.next ? (a = 2, o.removeClass("tp-is-shown")) : a = 0 : a = 2), 0 != a) {
                    if (R(o), o.find("iframe").length > 0) {
                        punchgs.TweenLite.to(o.find("iframe"), .2, {
                            autoAlpha: 0
                        }), Z() && o.find("iframe").remove();
                        try {
                            var r = o.find("iframe"),
                                s = r.attr("id"),
                                d = $f(s);
                            d.api("pause"), clearTimeout(o.data("timerplay"))
                        } catch (l) {}
                        try {
                            var h = o.data("player");
                            h.stopVideo(), clearTimeout(o.data("timerplay"))
                        } catch (l) {}
                    }
                    if (o.find("video").length > 0) try {
                        o.find("video").each(function() {
                            {
                                var e = t(this).parent();
                                e.attr("id")
                            }
                            clearTimeout(e.data("timerplay"));
                            var a = this;
                            a.pause()
                        })
                    } catch (l) {}
                    try {
                        var c = o.data("timeline"),
                            p = c.getLabelTime("frame99"),
                            u = c.time();
                        if (p > u) {
                            var f = c.getTweensOf(o);
                            if (t.each(f, function(t, e) {
                                    0 != t && e.pause()
                                }), 0 != o.css("opacity")) {
                                var g = o.data(o.data("endspeed") == e ? "speed" : "endspeed");
                                g > n && (n = g), c.play("frame99")
                            } else c.progress(1, !1)
                        }
                    } catch (l) {}
                }
            }), n
        },
        V = function(t, a, i, n, o) {
            var r = t.data("timeline"),
                s = new punchgs.TimelineLite,
                d = t;
            "chars" == i.typ ? d = t.data("mySplitText").chars : "words" == i.typ ? d = t.data("mySplitText").words : "lines" == i.typ && (d = t.data("mySplitText").lines), i.animation.ease = i.ease, i.animation.rotationZ != e && (i.animation.rotation = i.animation.rotationZ), i.animation.data = new Object, i.animation.data.oldx = i.animation.x, i.animation.data.oldy = i.animation.y, i.animation.x = i.animation.x * o, i.animation.y = i.animation.y * o, r.add(s.staggerTo(d, i.speed, i.animation, i.elementdelay), i.start), r.addLabel(n, i.start), t.data("timeline", r)
        },
        q = function(t, a, i, n, o, r) {
            var s = t.data("timeline"),
                d = new punchgs.TimelineLite,
                l = D(),
                h = t.data(t.data("endspeed") == e ? "speed" : "endspeed"),
                c = t.attr("class");
            if (l.ease = t.data("endeasing") == e ? punchgs.Power1.easeInOut : t.data("endeasing"), h /= 1e3, c.match("ltr") || c.match("ltl") || c.match("str") || c.match("stl") || c.match("ltt") || c.match("ltb") || c.match("stt") || c.match("stb") || c.match("skewtoright") || c.match("skewtorightshort") || c.match("skewtoleft") || c.match("skewtoleftshort") || c.match("fadeout") || c.match("randomrotateout")) {
                c.match("skewtoright") || c.match("skewtorightshort") ? l.skewX = 35 : (c.match("skewtoleft") || c.match("skewtoleftshort")) && (l.skewX = -35), c.match("ltr") || c.match("skewtoright") ? l.x = a.width + 60 : c.match("ltl") || c.match("skewtoleft") ? l.x = 0 - (a.width + 60) : c.match("ltt") ? l.y = 0 - (a.height + 60) : c.match("ltb") ? l.y = a.height + 60 : c.match("str") || c.match("skewtorightshort") ? (l.x = 50, l.opacity = 0) : c.match("stl") || c.match("skewtoleftshort") ? (l.x = -50, l.opacity = 0) : c.match("stt") ? (l.y = -50, l.opacity = 0) : c.match("stb") ? (l.y = 50, l.opacity = 0) : c.match("randomrotateout") ? (l.x = Math.random() * a.width, l.y = Math.random() * a.height, l.scale = 2 * Math.random() + .3, l.rotation = 360 * Math.random() - 180, l.opacity = 0) : c.match("fadeout") && (l.opacity = 0), c.match("skewtorightshort") ? l.x = 270 : c.match("skewtoleftshort") && (l.x = -270), l.data = new Object, l.data.oldx = l.x, l.data.oldy = l.y, l.x = l.x * r, l.y = l.y * r, l.overwrite = "auto";
                var p = t,
                    p = t;
                "chars" == t.data("splitout") ? p = t.data("mySplitText").chars : "words" == t.data("splitout") ? p = t.data("mySplitText").words : "lines" == t.data("splitout") && (p = t.data("mySplitText").lines);
                var u = t.data("endelementdelay") == e ? 0 : t.data("endelementdelay");
                s.add(d.staggerTo(p, h, l, u), i)
            } else if (t.hasClass("customout")) {
                l = H(l, t.data("customout"));
                var p = t;
                "chars" == t.data("splitout") ? p = t.data("mySplitText").chars : "words" == t.data("splitout") ? p = t.data("mySplitText").words : "lines" == t.data("splitout") && (p = t.data("mySplitText").lines);
                var u = t.data("endelementdelay") == e ? 0 : t.data("endelementdelay");
                l.onStart = function() {
                    punchgs.TweenLite.set(t, {
                        transformPerspective: l.transformPerspective,
                        transformOrigin: l.transformOrigin,
                        overwrite: "auto"
                    })
                }, l.data = new Object, l.data.oldx = l.x, l.data.oldy = l.y, l.x = l.x * r, l.y = l.y * r, s.add(d.staggerTo(p, h, l, u), i)
            } else n.delay = 0, s.add(punchgs.TweenLite.to(t, h, n), i);
            s.addLabel(o, i), t.data("timeline", s)
        },
        E = function(e, a) {
            e.children().each(function() {
                try {
                    t(this).die("click")
                } catch (e) {}
                try {
                    t(this).die("mouseenter")
                } catch (e) {}
                try {
                    t(this).die("mouseleave")
                } catch (e) {}
                try {
                    t(this).unbind("hover")
                } catch (e) {}
            });
            try {
                e.die("click", "mouseenter", "mouseleave")
            } catch (i) {}
            clearInterval(a.cdint), e = null
        },
        j = function(a, i) {
            if (i.cd = 0, i.loop = 0, i.looptogo = i.stopAfterLoops != e && i.stopAfterLoops > -1 ? i.stopAfterLoops : 9999999, i.lastslidetoshow = i.stopAtSlide != e && i.stopAtSlide > -1 ? i.stopAtSlide : 999, i.stopLoop = "off", 0 == i.looptogo && (i.stopLoop = "on"), i.slideamount > 1 && (0 != i.stopAfterLoops || 1 != i.stopAtSlide)) {
                var n = a.find(".tp-bannertimer");
                a.on("stoptimer", function() {
                    var e = t(this).find(".tp-bannertimer");
                    e.data("tween").pause(), "on" == i.hideTimerBar && e.css({
                        visibility: "hidden"
                    })
                }), a.on("starttimer", function() {
                    1 != i.conthover && 1 != i.videoplaying && i.width > i.hideSliderAtLimit && 1 != i.bannertimeronpause && 1 != i.overnav && ("on" == i.stopLoop && i.next == i.lastslidetoshow - 1 || 1 == i.noloopanymore ? i.noloopanymore = 1 : (n.css({
                        visibility: "visible"
                    }), n.data("tween").resume())), "on" == i.hideTimerBar && n.css({
                        visibility: "hidden"
                    })
                }), a.on("restarttimer", function() {
                    var e = t(this).find(".tp-bannertimer");
                    "on" == i.stopLoop && i.next == i.lastslidetoshow - 1 || 1 == i.noloopanymore ? i.noloopanymore = 1 : (e.css({
                        visibility: "visible"
                    }), e.data("tween").kill(), e.data("tween", punchgs.TweenLite.fromTo(e, i.delay / 1e3, {
                        width: "0%"
                    }, {
                        force3D: "auto",
                        width: "100%",
                        ease: punchgs.Linear.easeNone,
                        onComplete: o,
                        delay: 1
                    }))), "on" == i.hideTimerBar && e.css({
                        visibility: "hidden"
                    })
                }), a.on("nulltimer", function() {
                    n.data("tween").pause(0), "on" == i.hideTimerBar && n.css({
                        visibility: "hidden"
                    })
                });
                var o = function() {
                    0 == t("body").find(a).length && (E(a, i), clearInterval(i.cdint)), a.trigger("revolution.slide.slideatend"), 1 == a.data("conthover-changed") && (i.conthover = a.data("conthover"), a.data("conthover-changed", 0)), i.act = i.next, i.next = i.next + 1, i.next > a.find(">ul >li").length - 1 && (i.next = 0, i.looptogo = i.looptogo - 1, i.looptogo <= 0 && (i.stopLoop = "on")), "on" == i.stopLoop && i.next == i.lastslidetoshow - 1 ? (a.find(".tp-bannertimer").css({
                        visibility: "hidden"
                    }), a.trigger("revolution.slide.onstop"), i.noloopanymore = 1) : n.data("tween").restart(), L(a, i)
                };
                n.data("tween", punchgs.TweenLite.fromTo(n, i.delay / 1e3, {
                    width: "0%"
                }, {
                    force3D: "auto",
                    width: "100%",
                    ease: punchgs.Linear.easeNone,
                    onComplete: o,
                    delay: 1
                })), n.data("opt", i), a.hover(function() {
                    if ("on" == i.onHoverStop && !Z()) {
                        a.trigger("stoptimer"), a.trigger("revolution.slide.onpause");
                        var n = a.find(">ul >li:eq(" + i.next + ") .slotholder");
                        n.find(".defaultimg").each(function() {
                            var a = t(this);
                            a.data("kenburn") != e && a.data("kenburn").pause()
                        })
                    }
                }, function() {
                    if (1 != a.data("conthover")) {
                        a.trigger("revolution.slide.onresume"), a.trigger("starttimer");
                        var n = a.find(">ul >li:eq(" + i.next + ") .slotholder");
                        n.find(".defaultimg").each(function() {
                            var a = t(this);
                            a.data("kenburn") != e && a.data("kenburn").play()
                        })
                    }
                })
            }
        },
        Z = function() {
            var t = ["android", "webos", "iphone", "ipad", "blackberry", "Android", "webos", , "iPod", "iPhone", "iPad", "Blackberry", "BlackBerry"],
                e = !1;
            for (var a in t) navigator.userAgent.split(t[a]).length > 1 && (e = !0);
            return e
        },
        U = function(t, e, a) {
            var i = e.data("owidth"),
                n = e.data("oheight");
            if (i / n > a.width / a.height) {
                var o = a.container.width() / i,
                    r = n * o,
                    s = r / a.container.height() * t;
                return t *= 100 / s, s = 100, t = t, t + "% " + s + "% 1"
            }
            var o = a.container.width() / i,
                r = n * o,
                s = r / a.container.height() * t;
            return t + "% " + s + "%"
        },
        N = function(a, i, n, o) {
            try {
                {
                    a.find(">ul:first-child >li:eq(" + i.act + ")")
                }
            } catch (r) {
                {
                    a.find(">ul:first-child >li:eq(1)")
                }
            }
            i.lastslide = i.act;
            var d = a.find(">ul:first-child >li:eq(" + i.next + ")"),
                l = d.find(".slotholder"),
                h = l.data("bgposition"),
                c = l.data("bgpositionend"),
                p = l.data("zoomstart") / 100,
                u = l.data("zoomend") / 100,
                f = l.data("rotationstart"),
                g = l.data("rotationend"),
                m = l.data("bgfit"),
                v = l.data("bgfitend"),
                w = l.data("easeme"),
                b = l.data("duration") / 1e3,
                y = 100;
            m == e && (m = 100), v == e && (v = 100);
            var x = m,
                T = v;
            m = U(m, l, i), v = U(v, l, i), y = U(100, l, i), p == e && (p = 1), u == e && (u = 1), f == e && (f = 0), g == e && (g = 0), 1 > p && (p = 1), 1 > u && (u = 1);
            var k = new Object;
            k.w = parseInt(y.split(" ")[0], 0), k.h = parseInt(y.split(" ")[1], 0);
            var L = !1;
            "1" == y.split(" ")[2] && (L = !0), l.find(".defaultimg").each(function() {
                var e = t(this);
                0 == l.find(".kenburnimg").length ? l.append('<div class="kenburnimg" style="position:absolute;z-index:1;width:100%;height:100%;top:0px;left:0px;"><img src="' + e.attr("src") + '" style="-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;position:absolute;width:' + k.w + "%;height:" + k.h + '%;"></div>') : l.find(".kenburnimg img").css({
                    width: k.w + "%",
                    height: k.h + "%"
                });
                var a = l.find(".kenburnimg img"),
                    n = G(i, h, m, a, L),
                    r = G(i, c, v, a, L);
                if (L && (n.w = x / 100, r.w = T / 100), o) {
                    punchgs.TweenLite.set(a, {
                        autoAlpha: 0,
                        transformPerspective: 1200,
                        transformOrigin: "0% 0%",
                        top: 0,
                        left: 0,
                        scale: n.w,
                        x: n.x,
                        y: n.y
                    });
                    var d = n.w,
                        p = d * a.width() - i.width,
                        u = d * a.height() - i.height,
                        f = Math.abs(n.x / p * 100),
                        y = Math.abs(n.y / u * 100);
                    0 == u && (y = 0), 0 == p && (f = 0), e.data("bgposition", f + "% " + y + "%"), s(8) || e.data("currotate", $(a)), s(8) || e.data("curscale", k.w * d + "%  " + (k.h * d + "%")), l.find(".kenburnimg").remove()
                } else e.data("kenburn", punchgs.TweenLite.fromTo(a, b, {
                    autoAlpha: 1,
                    force3D: punchgs.force3d,
                    transformOrigin: "0% 0%",
                    top: 0,
                    left: 0,
                    scale: n.w,
                    x: n.x,
                    y: n.y
                }, {
                    autoAlpha: 1,
                    rotationZ: g,
                    ease: w,
                    x: r.x,
                    y: r.y,
                    scale: r.w,
                    onUpdate: function() {
                        var t = a[0]._gsTransform.scaleX,
                            n = t * a.width() - i.width,
                            o = t * a.height() - i.height,
                            r = Math.abs(a[0]._gsTransform.x / n * 100),
                            d = Math.abs(a[0]._gsTransform.y / o * 100);
                        0 == o && (d = 0), 0 == n && (r = 0), e.data("bgposition", r + "% " + d + "%"), s(8) || e.data("currotate", $(a)), s(8) || e.data("curscale", k.w * t + "%  " + (k.h * t + "%"))
                    }
                }))
            })
        },
        G = function(t, e, a, i, n) {
            var o = new Object;
            switch (o.w = n ? parseInt(a.split(" ")[1], 0) / 100 : parseInt(a.split(" ")[0], 0) / 100, e) {
                case "left top":
                case "top left":
                    o.x = 0, o.y = 0;
                    break;
                case "center top":
                case "top center":
                    o.x = ((0 - i.width()) * o.w + parseInt(t.width, 0)) / 2, o.y = 0;
                    break;
                case "top right":
                case "right top":
                    o.x = (0 - i.width()) * o.w + parseInt(t.width, 0), o.y = 0;
                    break;
                case "center left":
                case "left center":
                    o.x = 0, o.y = ((0 - i.height()) * o.w + parseInt(t.height, 0)) / 2;
                    break;
                case "center center":
                    o.x = ((0 - i.width()) * o.w + parseInt(t.width, 0)) / 2, o.y = ((0 - i.height()) * o.w + parseInt(t.height, 0)) / 2;
                    break;
                case "center right":
                case "right center":
                    o.x = (0 - i.width()) * o.w + parseInt(t.width, 0), o.y = ((0 - i.height()) * o.w + parseInt(t.height, 0)) / 2;
                    break;
                case "bottom left":
                case "left bottom":
                    o.x = 0, o.y = (0 - i.height()) * o.w + parseInt(t.height, 0);
                    break;
                case "bottom center":
                case "center bottom":
                    o.x = ((0 - i.width()) * o.w + parseInt(t.width, 0)) / 2, o.y = (0 - i.height()) * o.w + parseInt(t.height, 0);
                    break;
                case "bottom right":
                case "right bottom":
                    o.x = (0 - i.width()) * o.w + parseInt(t.width, 0), o.y = (0 - i.height()) * o.w + parseInt(t.height, 0)
            }
            return o
        },
        $ = function(t) {
            var e = t.css("-webkit-transform") || t.css("-moz-transform") || t.css("-ms-transform") || t.css("-o-transform") || t.css("transform");
            if ("none" !== e) var a = e.split("(")[1].split(")")[0].split(","),
                i = a[0],
                n = a[1],
                o = Math.round(Math.atan2(n, i) * (180 / Math.PI));
            else var o = 0;
            return 0 > o ? o += 360 : o
        },
        J = function(a, i) {
            try {
                var n = a.find(">ul:first-child >li:eq(" + i.act + ")")
            } catch (o) {
                var n = a.find(">ul:first-child >li:eq(1)")
            }
            i.lastslide = i.act; {
                var r = a.find(">ul:first-child >li:eq(" + i.next + ")");
                n.find(".slotholder"), r.find(".slotholder")
            }
            a.find(".defaultimg").each(function() {
                var a = t(this);
                punchgs.TweenLite.killTweensOf(a, !1), punchgs.TweenLite.set(a, {
                    scale: 1,
                    rotationZ: 0
                }), punchgs.TweenLite.killTweensOf(a.data("kenburn img"), !1), a.data("kenburn") != e && a.data("kenburn").pause(), a.data("currotate") != e && a.data("bgposition") != e && a.data("curscale") != e && punchgs.TweenLite.set(a, {
                    rotation: a.data("currotate"),
                    backgroundPosition: a.data("bgposition"),
                    backgroundSize: a.data("curscale")
                }), a != e && a.data("kenburn img") != e && a.data("kenburn img").length > 0 && punchgs.TweenLite.set(a.data("kenburn img"), {
                    autoAlpha: 0
                })
            })
        },
        Q = function(e, a) {
            return Z() && "on" == a.parallaxDisableOnMobile ? !1 : (e.find(">ul:first-child >li").each(function() {
                for (var e = t(this), i = 1; 10 >= i; i++) e.find(".rs-parallaxlevel-" + i).each(function() {
                    var e = t(this);
                    e.wrap('<div style="position:absolute;top:0px;left:0px;width:100%;height:100%;z-index:' + e.css("zIndex") + '" class="tp-parallax-container" data-parallaxlevel="' + a.parallaxLevels[i - 1] + '"></div>')
                })
            }), ("mouse" == a.parallax || "scroll+mouse" == a.parallax || "mouse+scroll" == a.parallax) && (e.mouseenter(function(t) {
                var a = e.find(".current-sr-slide-visible"),
                    i = e.offset().top,
                    n = e.offset().left,
                    o = t.pageX - n,
                    r = t.pageY - i;
                a.data("enterx", o), a.data("entery", r)
            }), e.on("mousemove.hoverdir, mouseleave.hoverdir", function(i) {
                var n = e.find(".current-sr-slide-visible");
                switch (i.type) {
                    case "mousemove":
                        var o = e.offset().top,
                            r = e.offset().left,
                            s = n.data("enterx"),
                            d = n.data("entery"),
                            l = s - (i.pageX - r),
                            h = d - (i.pageY - o);
                        n.find(".tp-parallax-container").each(function() {
                            var e = t(this),
                                i = parseInt(e.data("parallaxlevel"), 0) / 100,
                                n = l * i,
                                o = h * i;
                            "scroll+mouse" == a.parallax || "mouse+scroll" == a.parallax ? punchgs.TweenLite.to(e, .4, {
                                force3D: "auto",
                                x: n,
                                ease: punchgs.Power3.easeOut,
                                overwrite: "all"
                            }) : punchgs.TweenLite.to(e, .4, {
                                force3D: "auto",
                                x: n,
                                y: o,
                                ease: punchgs.Power3.easeOut,
                                overwrite: "all"
                            })
                        });
                        break;
                    case "mouseleave":
                        n.find(".tp-parallax-container").each(function() {
                            var e = t(this);
                            "scroll+mouse" == a.parallax || "mouse+scroll" == a.parallax ? punchgs.TweenLite.to(e, 1.5, {
                                force3D: "auto",
                                x: 0,
                                ease: punchgs.Power3.easeOut
                            }) : punchgs.TweenLite.to(e, 1.5, {
                                force3D: "auto",
                                x: 0,
                                y: 0,
                                ease: punchgs.Power3.easeOut
                            })
                        })
                }
            }), Z() && (window.ondeviceorientation = function(a) {
                var i = Math.round(a.beta || 0),
                    n = Math.round(a.gamma || 0),
                    o = e.find(".current-sr-slide-visible");
                if (t(window).width() > t(window).height()) {
                    var r = n;
                    n = i, i = r
                }
                var s = 360 / e.width() * n,
                    d = 180 / e.height() * i;
                o.find(".tp-parallax-container").each(function() {
                    var e = t(this),
                        a = parseInt(e.data("parallaxlevel"), 0) / 100,
                        i = s * a,
                        n = d * a;
                    punchgs.TweenLite.to(e, .2, {
                        force3D: "auto",
                        x: i,
                        y: n,
                        ease: punchgs.Power3.easeOut
                    })
                })
            })), void(("scroll" == a.parallax || "scroll+mouse" == a.parallax || "mouse+scroll" == a.parallax) && t(window).on("scroll", function() {
                K(e, a)
            })))
        },
        K = function(e, a) {
            if (Z() && "on" == a.parallaxDisableOnMobile) return !1;
            var i = e.offset().top,
                n = t(window).scrollTop(),
                o = i + e.height() / 2,
                r = i + e.height() / 2 - n,
                s = t(window).height() / 2,
                d = s - r;
            s > o && (d -= s - o);
            e.find(".current-sr-slide-visible");
            if (e.find(".tp-parallax-container").each(function() {
                    var e = t(this),
                        a = parseInt(e.data("parallaxlevel"), 0) / 100,
                        i = d * a;
                    e.data("parallaxoffset", i), punchgs.TweenLite.to(e, .2, {
                        force3D: "auto",
                        y: i,
                        ease: punchgs.Power3.easeOut
                    })
                }), "on" != a.parallaxBgFreeze) {
                var l = a.parallaxLevels[0] / 100,
                    h = d * l;
                punchgs.TweenLite.to(e, .2, {
                    force3D: "auto",
                    y: h,
                    ease: punchgs.Power3.easeOut
                })
            }
        },
        te = function(a, i) {
            var n = a.parent();
            ("thumb" == i.navigationType || "both" == i.navsecond) && n.append('<div class="tp-bullets tp-thumbs ' + i.navigationStyle + '"><div class="tp-mask"><div class="tp-thumbcontainer"></div></div></div>');
            var o = n.find(".tp-bullets.tp-thumbs .tp-mask .tp-thumbcontainer"),
                r = o.parent();
            r.width(i.thumbWidth * i.thumbAmount), r.height(i.thumbHeight), r.parent().width(i.thumbWidth * i.thumbAmount), r.parent().height(i.thumbHeight), a.find(">ul:first >li").each(function(t) {
                var n = a.find(">ul:first >li:eq(" + t + ")"),
                    r = n.find(".defaultimg").css("backgroundColor");
                if (n.data("thumb") != e) var s = n.data("thumb");
                else var s = n.find("img:first").attr("src");
                o.append('<div class="bullet thumb" style="background-color:' + r + ";position:relative;width:" + i.thumbWidth + "px;height:" + i.thumbHeight + "px;background-image:url(" + s + ') !important;background-size:cover;background-position:center center;"></div>');
                o.find(".bullet:first")
            });
            var s = 10;
            o.find(".bullet").each(function(e) {
                var n = t(this);
                e == i.slideamount - 1 && n.addClass("last"), 0 == e && n.addClass("first"), n.width(i.thumbWidth), n.height(i.thumbHeight), s < n.outerWidth(!0) && (s = n.outerWidth(!0)), n.click(function() {
                    0 == i.transition && n.index() != i.act && (i.next = n.index(), d(i, a))
                })
            });
            var l = s * a.find(">ul:first >li").length,
                h = o.parent().width();
            i.thumbWidth = s, l > h && (t(document).mousemove(function(e) {
                t("body").data("mousex", e.pageX)
            }), o.parent().mouseenter(function() {
                var e = t(this),
                    i = e.offset(),
                    n = t("body").data("mousex") - i.left,
                    o = e.width(),
                    r = e.find(".bullet:first").outerWidth(!0),
                    s = r * a.find(">ul:first >li").length,
                    d = s - o + 15,
                    l = d / o;
                e.addClass("over"), n -= 30;
                var h = 0 - n * l;
                h > 0 && (h = 0), 0 - s + o > h && (h = 0 - s + o), ae(e, h, 200)
            }), o.parent().mousemove(function() {
                var e = t(this),
                    i = e.offset(),
                    n = t("body").data("mousex") - i.left,
                    o = e.width(),
                    r = e.find(".bullet:first").outerWidth(!0),
                    s = r * a.find(">ul:first >li").length - 1,
                    d = s - o + 15,
                    l = d / o;
                n -= 3, 6 > n && (n = 0), n + 3 > o - 6 && (n = o);
                var h = 0 - n * l;
                h > 0 && (h = 0), 0 - s + o > h && (h = 0 - s + o), ae(e, h, 0)
            }), o.parent().mouseleave(function() {
                var e = t(this);
                e.removeClass("over"), ee(a)
            }))
        },
        ee = function(t) {
            var e = t.parent().find(".tp-bullets.tp-thumbs .tp-mask .tp-thumbcontainer"),
                a = e.parent(),
                i = (a.offset(), a.find(".bullet:first").outerWidth(!0)),
                n = a.find(".bullet.selected").index() * i,
                o = a.width(),
                i = a.find(".bullet:first").outerWidth(!0),
                r = i * t.find(">ul:first >li").length,
                s = 0 - n;
            s > 0 && (s = 0), 0 - r + o > s && (s = 0 - r + o), a.hasClass("over") || ae(a, s, 200)
        },
        ae = function(t, e) {
            punchgs.TweenLite.to(t.find(".tp-thumbcontainer"), .2, {
                force3D: "auto",
                left: e,
                ease: punchgs.Power3.easeOut,
                overwrite: "auto"
            })
        }
}(jQuery);
jQuery(document).ready(function() {
    var o;
    if (jQuery(".fullwidthbanner").length > 0 && (o = jQuery(".fullwidthbanner").revolution({
            delay: 9e3,
            startwidth: 1170,
            startheight: 500,
            hideThumbs: 10,
            thumbWidth: 100,
            thumbHeight: 50,
            thumbAmount: 5,
            navigationType: "both",
            navigationArrows: "solo",
            navigationStyle: "round",
            touchenabled: "on",
            onHoverStop: "on",
            navigationHAlign: "center",
            navigationVAlign: "bottom",
            navigationHOffset: 0,
            navigationVOffset: 0,
            soloArrowLeftHalign: "left",
            soloArrowLeftValign: "center",
            soloArrowLeftHOffset: 20,
            soloArrowLeftVOffset: 0,
            soloArrowRightHalign: "right",
            soloArrowRightValign: "center",
            soloArrowRightHOffset: 20,
            soloArrowRightVOffset: 0,
            shadow: 1,
            fullWidth: "on",
            fullScreen: "off",
            stopLoop: "off",
            stopAfterLoops: -1,
            stopAtSlide: -1,
            shuffle: "off",
            autoHeight: "off",
            forceFullWidth: "off",
            hideThumbsOnMobile: "off",
            hideBulletsOnMobile: "on",
            hideArrowsOnMobile: "on",
            hideThumbsUnderResolution: 0,
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 768,
            hideAllCaptionAtLilmit: 0,
            startWithSlide: 0,
            fullScreenOffsetContainer: ""
        }), jQuery("#is_wide, #is_boxed").bind("click", function() {
            o.revredraw()
        })), jQuery(".fullscreenbanner").length > 0) {
        var e = jQuery;
        e.noConflict();
        var t;
        e(document).ready(function() {
            void 0 == e(".fullscreenbanner").revolution ? revslider_showDoubleJqueryError(".fullscreenbanner") : (t = e(".fullscreenbanner").show().revolution({
                delay: 9e3,
                startwidth: 1200,
                startheight: 700,
                hideThumbs: 10,
                thumbWidth: 100,
                thumbHeight: 50,
                thumbAmount: 4,
                navigationType: "none",
                navigationArrows: "none",
                navigationStyle: "round",
                touchenabled: "on",
                onHoverStop: "on",
                navigationHAlign: "center",
                navigationVAlign: "bottom",
                navigationHOffset: 0,
                navigationVOffset: 0,
                soloArrowLeftHalign: "left",
                soloArrowLeftValign: "center",
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,
                soloArrowRightHalign: "right",
                soloArrowRightValign: "center",
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,
                shadow: 1,
                fullWidth: "off",
                fullScreen: "on",
                stopLoop: "off",
                stopAfterLoops: -1,
                stopAtSlide: -1,
                shuffle: "off",
                forceFullWidth: "on",
                fullScreenAlignForce: "off",
                hideThumbsOnMobile: "off",
                hideBulletsOnMobile: "on",
                hideArrowsOnMobile: "on",
                hideThumbsUnderResolution: 0,
                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 768,
                hideAllCaptionAtLilmit: 0,
                startWithSlide: 0,
                fullScreenOffsetContainer: "header, .pagetitlewrap"
            }), jQuery("#is_wide, #is_boxed").bind("click", function() {
                t.revredraw()
            }))
        })
    }
    jQuery(".fullscreenbanner.ken-burns").length > 0 && (o = jQuery(".fullwidthbanner").revolution({
        dottedOverlay: "none",
        delay: 9e3,
        startwidth: 1170,
        startheight: 400,
        hideThumbs: 200,
        thumbWidth: 100,
        thumbHeight: 50,
        thumbAmount: 5,
        navigationType: "bullet",
        navigationArrows: "solo",
        navigationStyle: "round",
        touchenabled: "on",
        onHoverStop: "off",
        navigationHAlign: "center",
        navigationVAlign: "bottom",
        navigationHOffset: 0,
        navigationVOffset: 0,
        soloArrowLeftHalign: "left",
        soloArrowLeftValign: "center",
        soloArrowLeftHOffset: 20,
        soloArrowLeftVOffset: 0,
        soloArrowRightHalign: "right",
        soloArrowRightValign: "center",
        soloArrowRightHOffset: 20,
        soloArrowRightVOffset: 0,
        shadow: 1,
        fullWidth: "on",
        fullScreen: "off",
        stopLoop: "off",
        stopAfterLoops: -1,
        stopAtSlide: -1,
        shuffle: "off",
        autoHeight: "off",
        forceFullWidth: "off",
        hideThumbsOnMobile: "off",
        hideBulletsOnMobile: "off",
        hideArrowsOnMobile: "off",
        hideThumbsUnderResolution: 0,
        hideSliderAtLimit: 0,
        hideCaptionAtLimit: 0,
        hideAllCaptionAtLilmit: 0,
        startWithSlide: 0,
        videoJsPath: "http://server.local/revslider/wp-content/plugins/revslider/rs-plugin/videojs/",
        fullScreenOffsetContainer: ""
    }), jQuery("#is_wide, #is_boxed").bind("click", function() {
        o.revredraw()
    }))
});
! function(t) {
    function e() {}

    function i(t) {
        function i(e) {
            e.prototype.option || (e.prototype.option = function(e) {
                t.isPlainObject(e) && (this.options = t.extend(!0, this.options, e))
            })
        }

        function o(e, i) {
            t.fn[e] = function(o) {
                if ("string" == typeof o) {
                    for (var s = n.call(arguments, 1), a = 0, u = this.length; u > a; a++) {
                        var h = this[a],
                            p = t.data(h, e);
                        if (p)
                            if (t.isFunction(p[o]) && "_" !== o.charAt(0)) {
                                var f = p[o].apply(p, s);
                                if (void 0 !== f) return f
                            } else r("no such method '" + o + "' for " + e + " instance");
                        else r("cannot call methods on " + e + " prior to initialization; attempted to call '" + o + "'")
                    }
                    return this
                }
                return this.each(function() {
                    var n = t.data(this, e);
                    n ? (n.option(o), n._init()) : (n = new i(this, o), t.data(this, e, n))
                })
            }
        }
        if (t) {
            var r = "undefined" == typeof console ? e : function(t) {
                console.error(t)
            };
            return t.bridget = function(t, e) {
                i(e), o(t, e)
            }, t.bridget
        }
    }
    var n = Array.prototype.slice;
    "function" == typeof define && define.amd ? define("jquery-bridget/jquery.bridget", ["jquery"], i) : i("object" == typeof exports ? require("jquery") : t.jQuery)
}(window),
function(t) {
    function e(e) {
        var i = t.event;
        return i.target = i.target || i.srcElement || e, i
    }
    var i = document.documentElement,
        n = function() {};
    i.addEventListener ? n = function(t, e, i) {
        t.addEventListener(e, i, !1)
    } : i.attachEvent && (n = function(t, i, n) {
        t[i + n] = n.handleEvent ? function() {
            var i = e(t);
            n.handleEvent.call(n, i)
        } : function() {
            var i = e(t);
            n.call(t, i)
        }, t.attachEvent("on" + i, t[i + n])
    });
    var o = function() {};
    i.removeEventListener ? o = function(t, e, i) {
        t.removeEventListener(e, i, !1)
    } : i.detachEvent && (o = function(t, e, i) {
        t.detachEvent("on" + e, t[e + i]);
        try {
            delete t[e + i]
        } catch (n) {
            t[e + i] = void 0
        }
    });
    var r = {
        bind: n,
        unbind: o
    };
    "function" == typeof define && define.amd ? define("eventie/eventie", r) : "object" == typeof exports ? module.exports = r : t.eventie = r
}(window),
function() {
    function t() {}

    function e(t, e) {
        for (var i = t.length; i--;)
            if (t[i].listener === e) return i;
        return -1
    }

    function i(t) {
        return function() {
            return this[t].apply(this, arguments)
        }
    }
    var n = t.prototype,
        o = this,
        r = o.EventEmitter;
    n.getListeners = function(t) {
        var e, i, n = this._getEvents();
        if (t instanceof RegExp) {
            e = {};
            for (i in n) n.hasOwnProperty(i) && t.test(i) && (e[i] = n[i])
        } else e = n[t] || (n[t] = []);
        return e
    }, n.flattenListeners = function(t) {
        var e, i = [];
        for (e = 0; e < t.length; e += 1) i.push(t[e].listener);
        return i
    }, n.getListenersAsObject = function(t) {
        var e, i = this.getListeners(t);
        return i instanceof Array && (e = {}, e[t] = i), e || i
    }, n.addListener = function(t, i) {
        var n, o = this.getListenersAsObject(t),
            r = "object" == typeof i;
        for (n in o) o.hasOwnProperty(n) && -1 === e(o[n], i) && o[n].push(r ? i : {
            listener: i,
            once: !1
        });
        return this
    }, n.on = i("addListener"), n.addOnceListener = function(t, e) {
        return this.addListener(t, {
            listener: e,
            once: !0
        })
    }, n.once = i("addOnceListener"), n.defineEvent = function(t) {
        return this.getListeners(t), this
    }, n.defineEvents = function(t) {
        for (var e = 0; e < t.length; e += 1) this.defineEvent(t[e]);
        return this
    }, n.removeListener = function(t, i) {
        var n, o, r = this.getListenersAsObject(t);
        for (o in r) r.hasOwnProperty(o) && (n = e(r[o], i), -1 !== n && r[o].splice(n, 1));
        return this
    }, n.off = i("removeListener"), n.addListeners = function(t, e) {
        return this.manipulateListeners(!1, t, e)
    }, n.removeListeners = function(t, e) {
        return this.manipulateListeners(!0, t, e)
    }, n.manipulateListeners = function(t, e, i) {
        var n, o, r = t ? this.removeListener : this.addListener,
            s = t ? this.removeListeners : this.addListeners;
        if ("object" != typeof e || e instanceof RegExp)
            for (n = i.length; n--;) r.call(this, e, i[n]);
        else
            for (n in e) e.hasOwnProperty(n) && (o = e[n]) && ("function" == typeof o ? r.call(this, n, o) : s.call(this, n, o));
        return this
    }, n.removeEvent = function(t) {
        var e, i = typeof t,
            n = this._getEvents();
        if ("string" === i) delete n[t];
        else if (t instanceof RegExp)
            for (e in n) n.hasOwnProperty(e) && t.test(e) && delete n[e];
        else delete this._events;
        return this
    }, n.removeAllListeners = i("removeEvent"), n.emitEvent = function(t, e) {
        var i, n, o, r, s = this.getListenersAsObject(t);
        for (o in s)
            if (s.hasOwnProperty(o))
                for (n = s[o].length; n--;) i = s[o][n], i.once === !0 && this.removeListener(t, i.listener), r = i.listener.apply(this, e || []), r === this._getOnceReturnValue() && this.removeListener(t, i.listener);
        return this
    }, n.trigger = i("emitEvent"), n.emit = function(t) {
        var e = Array.prototype.slice.call(arguments, 1);
        return this.emitEvent(t, e)
    }, n.setOnceReturnValue = function(t) {
        return this._onceReturnValue = t, this
    }, n._getOnceReturnValue = function() {
        return this.hasOwnProperty("_onceReturnValue") ? this._onceReturnValue : !0
    }, n._getEvents = function() {
        return this._events || (this._events = {})
    }, t.noConflict = function() {
        return o.EventEmitter = r, t
    }, "function" == typeof define && define.amd ? define("eventEmitter/EventEmitter", [], function() {
        return t
    }) : "object" == typeof module && module.exports ? module.exports = t : o.EventEmitter = t
}.call(this),
    function(t) {
        function e(t) {
            if (t) {
                if ("string" == typeof n[t]) return t;
                t = t.charAt(0).toUpperCase() + t.slice(1);
                for (var e, o = 0, r = i.length; r > o; o++)
                    if (e = i[o] + t, "string" == typeof n[e]) return e
            }
        }
        var i = "Webkit Moz ms Ms O".split(" "),
            n = document.documentElement.style;
        "function" == typeof define && define.amd ? define("get-style-property/get-style-property", [], function() {
            return e
        }) : "object" == typeof exports ? module.exports = e : t.getStyleProperty = e
    }(window),
    function(t) {
        function e(t) {
            var e = parseFloat(t),
                i = -1 === t.indexOf("%") && !isNaN(e);
            return i && e
        }

        function i() {}

        function n() {
            for (var t = {
                    width: 0,
                    height: 0,
                    innerWidth: 0,
                    innerHeight: 0,
                    outerWidth: 0,
                    outerHeight: 0
                }, e = 0, i = s.length; i > e; e++) {
                var n = s[e];
                t[n] = 0
            }
            return t
        }

        function o(i) {
            function o() {
                if (!d) {
                    d = !0;
                    var n = t.getComputedStyle;
                    if (h = function() {
                            var t = n ? function(t) {
                                return n(t, null)
                            } : function(t) {
                                return t.currentStyle
                            };
                            return function(e) {
                                var i = t(e);
                                return i || r("Style returned " + i + ". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"), i
                            }
                        }(), p = i("boxSizing")) {
                        var o = document.createElement("div");
                        o.style.width = "200px", o.style.padding = "1px 2px 3px 4px", o.style.borderStyle = "solid", o.style.borderWidth = "1px 2px 3px 4px", o.style[p] = "border-box";
                        var s = document.body || document.documentElement;
                        s.appendChild(o);
                        var a = h(o);
                        f = 200 === e(a.width), s.removeChild(o)
                    }
                }
            }

            function a(t) {
                if (o(), "string" == typeof t && (t = document.querySelector(t)), t && "object" == typeof t && t.nodeType) {
                    var i = h(t);
                    if ("none" === i.display) return n();
                    var r = {};
                    r.width = t.offsetWidth, r.height = t.offsetHeight;
                    for (var a = r.isBorderBox = !(!p || !i[p] || "border-box" !== i[p]), d = 0, l = s.length; l > d; d++) {
                        var c = s[d],
                            m = i[c];
                        m = u(t, m);
                        var y = parseFloat(m);
                        r[c] = isNaN(y) ? 0 : y
                    }
                    var g = r.paddingLeft + r.paddingRight,
                        v = r.paddingTop + r.paddingBottom,
                        E = r.marginLeft + r.marginRight,
                        b = r.marginTop + r.marginBottom,
                        z = r.borderLeftWidth + r.borderRightWidth,
                        x = r.borderTopWidth + r.borderBottomWidth,
                        _ = a && f,
                        L = e(i.width);
                    L !== !1 && (r.width = L + (_ ? 0 : g + z));
                    var T = e(i.height);
                    return T !== !1 && (r.height = T + (_ ? 0 : v + x)), r.innerWidth = r.width - (g + z), r.innerHeight = r.height - (v + x), r.outerWidth = r.width + E, r.outerHeight = r.height + b, r
                }
            }

            function u(e, i) {
                if (t.getComputedStyle || -1 === i.indexOf("%")) return i;
                var n = e.style,
                    o = n.left,
                    r = e.runtimeStyle,
                    s = r && r.left;
                return s && (r.left = e.currentStyle.left), n.left = i, i = n.pixelLeft, n.left = o, s && (r.left = s), i
            }
            var h, p, f, d = !1;
            return a
        }
        var r = "undefined" == typeof console ? i : function(t) {
                console.error(t)
            },
            s = ["paddingLeft", "paddingRight", "paddingTop", "paddingBottom", "marginLeft", "marginRight", "marginTop", "marginBottom", "borderLeftWidth", "borderRightWidth", "borderTopWidth", "borderBottomWidth"];
        "function" == typeof define && define.amd ? define("get-size/get-size", ["get-style-property/get-style-property"], o) : "object" == typeof exports ? module.exports = o(require("desandro-get-style-property")) : t.getSize = o(t.getStyleProperty)
    }(window),
    function(t) {
        function e(t) {
            "function" == typeof t && (e.isReady ? t() : s.push(t))
        }

        function i(t) {
            var i = "readystatechange" === t.type && "complete" !== r.readyState;
            e.isReady || i || n()
        }

        function n() {
            e.isReady = !0;
            for (var t = 0, i = s.length; i > t; t++) {
                var n = s[t];
                n()
            }
        }

        function o(o) {
            return "complete" === r.readyState ? n() : (o.bind(r, "DOMContentLoaded", i), o.bind(r, "readystatechange", i), o.bind(t, "load", i)), e
        }
        var r = t.document,
            s = [];
        e.isReady = !1, "function" == typeof define && define.amd ? define("doc-ready/doc-ready", ["eventie/eventie"], o) : "object" == typeof exports ? module.exports = o(require("eventie")) : t.docReady = o(t.eventie)
    }(window),
    function(t) {
        function e(t, e) {
            return t[s](e)
        }

        function i(t) {
            if (!t.parentNode) {
                var e = document.createDocumentFragment();
                e.appendChild(t)
            }
        }

        function n(t, e) {
            i(t);
            for (var n = t.parentNode.querySelectorAll(e), o = 0, r = n.length; r > o; o++)
                if (n[o] === t) return !0;
            return !1
        }

        function o(t, n) {
            return i(t), e(t, n)
        }
        var r, s = function() {
            if (t.matches) return "matches";
            if (t.matchesSelector) return "matchesSelector";
            for (var e = ["webkit", "moz", "ms", "o"], i = 0, n = e.length; n > i; i++) {
                var o = e[i],
                    r = o + "MatchesSelector";
                if (t[r]) return r
            }
        }();
        if (s) {
            var a = document.createElement("div"),
                u = e(a, "div");
            r = u ? e : o
        } else r = n;
        "function" == typeof define && define.amd ? define("matches-selector/matches-selector", [], function() {
            return r
        }) : "object" == typeof exports ? module.exports = r : window.matchesSelector = r
    }(Element.prototype),
    function(t, e) {
        "function" == typeof define && define.amd ? define("fizzy-ui-utils/utils", ["doc-ready/doc-ready", "matches-selector/matches-selector"], function(i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("doc-ready"), require("desandro-matches-selector")) : t.fizzyUIUtils = e(t, t.docReady, t.matchesSelector)
    }(window, function(t, e, i) {
        var n = {};
        n.extend = function(t, e) {
            for (var i in e) t[i] = e[i];
            return t
        }, n.modulo = function(t, e) {
            return (t % e + e) % e
        };
        var o = Object.prototype.toString;
        n.isArray = function(t) {
            return "[object Array]" == o.call(t)
        }, n.makeArray = function(t) {
            var e = [];
            if (n.isArray(t)) e = t;
            else if (t && "number" == typeof t.length)
                for (var i = 0, o = t.length; o > i; i++) e.push(t[i]);
            else e.push(t);
            return e
        }, n.indexOf = Array.prototype.indexOf ? function(t, e) {
            return t.indexOf(e)
        } : function(t, e) {
            for (var i = 0, n = t.length; n > i; i++)
                if (t[i] === e) return i;
            return -1
        }, n.removeFrom = function(t, e) {
            var i = n.indexOf(t, e); - 1 != i && t.splice(i, 1)
        }, n.isElement = "function" == typeof HTMLElement || "object" == typeof HTMLElement ? function(t) {
            return t instanceof HTMLElement
        } : function(t) {
            return t && "object" == typeof t && 1 == t.nodeType && "string" == typeof t.nodeName
        }, n.setText = function() {
            function t(t, i) {
                e = e || (void 0 !== document.documentElement.textContent ? "textContent" : "innerText"), t[e] = i
            }
            var e;
            return t
        }(), n.getParent = function(t, e) {
            for (; t != document.body;)
                if (t = t.parentNode, i(t, e)) return t
        }, n.getQueryElement = function(t) {
            return "string" == typeof t ? document.querySelector(t) : t
        }, n.handleEvent = function(t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, n.filterFindElements = function(t, e) {
            t = n.makeArray(t);
            for (var o = [], r = 0, s = t.length; s > r; r++) {
                var a = t[r];
                if (n.isElement(a))
                    if (e) {
                        i(a, e) && o.push(a);
                        for (var u = a.querySelectorAll(e), h = 0, p = u.length; p > h; h++) o.push(u[h])
                    } else o.push(a)
            }
            return o
        }, n.debounceMethod = function(t, e, i) {
            var n = t.prototype[e],
                o = e + "Timeout";
            t.prototype[e] = function() {
                var t = this[o];
                t && clearTimeout(t);
                var e = arguments,
                    r = this;
                this[o] = setTimeout(function() {
                    n.apply(r, e), delete r[o]
                }, i || 100)
            }
        }, n.toDashed = function(t) {
            return t.replace(/(.)([A-Z])/g, function(t, e, i) {
                return e + "-" + i
            }).toLowerCase()
        };
        var r = t.console;
        return n.htmlInit = function(i, o) {
            e(function() {
                for (var e = n.toDashed(o), s = document.querySelectorAll(".js-" + e), a = "data-" + e + "-options", u = 0, h = s.length; h > u; u++) {
                    var p, f = s[u],
                        d = f.getAttribute(a);
                    try {
                        p = d && JSON.parse(d)
                    } catch (l) {
                        r && r.error("Error parsing " + a + " on " + f.nodeName.toLowerCase() + (f.id ? "#" + f.id : "") + ": " + l);
                        continue
                    }
                    var c = new i(f, p),
                        m = t.jQuery;
                    m && m.data(f, o, c)
                }
            })
        }, n
    }),
    function(t, e) {
        "function" == typeof define && define.amd ? define("outlayer/item", ["eventEmitter/EventEmitter", "get-size/get-size", "get-style-property/get-style-property", "fizzy-ui-utils/utils"], function(i, n, o, r) {
            return e(t, i, n, o, r)
        }) : "object" == typeof exports ? module.exports = e(t, require("wolfy87-eventemitter"), require("get-size"), require("desandro-get-style-property"), require("fizzy-ui-utils")) : (t.Outlayer = {}, t.Outlayer.Item = e(t, t.EventEmitter, t.getSize, t.getStyleProperty, t.fizzyUIUtils))
    }(window, function(t, e, i, n, o) {
        function r(t) {
            for (var e in t) return !1;
            return e = null, !0
        }

        function s(t, e) {
            t && (this.element = t, this.layout = e, this.position = {
                x: 0,
                y: 0
            }, this._create())
        }

        function a(t) {
            return t.replace(/([A-Z])/g, function(t) {
                return "-" + t.toLowerCase()
            })
        }
        var u = t.getComputedStyle,
            h = u ? function(t) {
                return u(t, null)
            } : function(t) {
                return t.currentStyle
            },
            p = n("transition"),
            f = n("transform"),
            d = p && f,
            l = !!n("perspective"),
            c = {
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "otransitionend",
                transition: "transitionend"
            }[p],
            m = ["transform", "transition", "transitionDuration", "transitionProperty"],
            y = function() {
                for (var t = {}, e = 0, i = m.length; i > e; e++) {
                    var o = m[e],
                        r = n(o);
                    r && r !== o && (t[o] = r)
                }
                return t
            }();
        o.extend(s.prototype, e.prototype), s.prototype._create = function() {
            this._transn = {
                ingProperties: {},
                clean: {},
                onEnd: {}
            }, this.css({
                position: "absolute"
            })
        }, s.prototype.handleEvent = function(t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, s.prototype.getSize = function() {
            this.size = i(this.element)
        }, s.prototype.css = function(t) {
            var e = this.element.style;
            for (var i in t) {
                var n = y[i] || i;
                e[n] = t[i]
            }
        }, s.prototype.getPosition = function() {
            var t = h(this.element),
                e = this.layout.options,
                i = e.isOriginLeft,
                n = e.isOriginTop,
                o = t[i ? "left" : "right"],
                r = t[n ? "top" : "bottom"],
                s = this.layout.size,
                a = -1 != o.indexOf("%") ? parseFloat(o) / 100 * s.width : parseInt(o, 10),
                u = -1 != r.indexOf("%") ? parseFloat(r) / 100 * s.height : parseInt(r, 10);
            a = isNaN(a) ? 0 : a, u = isNaN(u) ? 0 : u, a -= i ? s.paddingLeft : s.paddingRight, u -= n ? s.paddingTop : s.paddingBottom, this.position.x = a, this.position.y = u
        }, s.prototype.layoutPosition = function() {
            var t = this.layout.size,
                e = this.layout.options,
                i = {},
                n = e.isOriginLeft ? "paddingLeft" : "paddingRight",
                o = e.isOriginLeft ? "left" : "right",
                r = e.isOriginLeft ? "right" : "left",
                s = this.position.x + t[n];
            i[o] = this.getXValue(s), i[r] = "";
            var a = e.isOriginTop ? "paddingTop" : "paddingBottom",
                u = e.isOriginTop ? "top" : "bottom",
                h = e.isOriginTop ? "bottom" : "top",
                p = this.position.y + t[a];
            i[u] = this.getYValue(p), i[h] = "", this.css(i), this.emitEvent("layout", [this])
        }, s.prototype.getXValue = function(t) {
            var e = this.layout.options;
            return e.percentPosition && !e.isHorizontal ? t / this.layout.size.width * 100 + "%" : t + "px"
        }, s.prototype.getYValue = function(t) {
            var e = this.layout.options;
            return e.percentPosition && e.isHorizontal ? t / this.layout.size.height * 100 + "%" : t + "px"
        }, s.prototype._transitionTo = function(t, e) {
            this.getPosition();
            var i = this.position.x,
                n = this.position.y,
                o = parseInt(t, 10),
                r = parseInt(e, 10),
                s = o === this.position.x && r === this.position.y;
            if (this.setPosition(t, e), s && !this.isTransitioning) return void this.layoutPosition();
            var a = t - i,
                u = e - n,
                h = {};
            h.transform = this.getTranslate(a, u), this.transition({
                to: h,
                onTransitionEnd: {
                    transform: this.layoutPosition
                },
                isCleaning: !0
            })
        }, s.prototype.getTranslate = function(t, e) {
            var i = this.layout.options;
            return t = i.isOriginLeft ? t : -t, e = i.isOriginTop ? e : -e, l ? "translate3d(" + t + "px, " + e + "px, 0)" : "translate(" + t + "px, " + e + "px)"
        }, s.prototype.goTo = function(t, e) {
            this.setPosition(t, e), this.layoutPosition()
        }, s.prototype.moveTo = d ? s.prototype._transitionTo : s.prototype.goTo, s.prototype.setPosition = function(t, e) {
            this.position.x = parseInt(t, 10), this.position.y = parseInt(e, 10)
        }, s.prototype._nonTransition = function(t) {
            this.css(t.to), t.isCleaning && this._removeStyles(t.to);
            for (var e in t.onTransitionEnd) t.onTransitionEnd[e].call(this)
        }, s.prototype._transition = function(t) {
            if (!parseFloat(this.layout.options.transitionDuration)) return void this._nonTransition(t);
            var e = this._transn;
            for (var i in t.onTransitionEnd) e.onEnd[i] = t.onTransitionEnd[i];
            for (i in t.to) e.ingProperties[i] = !0, t.isCleaning && (e.clean[i] = !0);
            if (t.from) {
                this.css(t.from);
                var n = this.element.offsetHeight;
                n = null
            }
            this.enableTransition(t.to), this.css(t.to), this.isTransitioning = !0
        };
        var g = "opacity," + a(y.transform || "transform");
        s.prototype.enableTransition = function() {
            this.isTransitioning || (this.css({
                transitionProperty: g,
                transitionDuration: this.layout.options.transitionDuration
            }), this.element.addEventListener(c, this, !1))
        }, s.prototype.transition = s.prototype[p ? "_transition" : "_nonTransition"], s.prototype.onwebkitTransitionEnd = function(t) {
            this.ontransitionend(t)
        }, s.prototype.onotransitionend = function(t) {
            this.ontransitionend(t)
        };
        var v = {
            "-webkit-transform": "transform",
            "-moz-transform": "transform",
            "-o-transform": "transform"
        };
        s.prototype.ontransitionend = function(t) {
            if (t.target === this.element) {
                var e = this._transn,
                    i = v[t.propertyName] || t.propertyName;
                if (delete e.ingProperties[i], r(e.ingProperties) && this.disableTransition(), i in e.clean && (this.element.style[t.propertyName] = "", delete e.clean[i]), i in e.onEnd) {
                    var n = e.onEnd[i];
                    n.call(this), delete e.onEnd[i]
                }
                this.emitEvent("transitionEnd", [this])
            }
        }, s.prototype.disableTransition = function() {
            this.removeTransitionStyles(), this.element.removeEventListener(c, this, !1), this.isTransitioning = !1
        }, s.prototype._removeStyles = function(t) {
            var e = {};
            for (var i in t) e[i] = "";
            this.css(e)
        };
        var E = {
            transitionProperty: "",
            transitionDuration: ""
        };
        return s.prototype.removeTransitionStyles = function() {
            this.css(E)
        }, s.prototype.removeElem = function() {
            this.element.parentNode.removeChild(this.element), this.css({
                display: ""
            }), this.emitEvent("remove", [this])
        }, s.prototype.remove = function() {
            if (!p || !parseFloat(this.layout.options.transitionDuration)) return void this.removeElem();
            var t = this;
            this.once("transitionEnd", function() {
                t.removeElem()
            }), this.hide()
        }, s.prototype.reveal = function() {
            delete this.isHidden, this.css({
                display: ""
            });
            var t = this.layout.options,
                e = {},
                i = this.getHideRevealTransitionEndProperty("visibleStyle");
            e[i] = this.onRevealTransitionEnd, this.transition({
                from: t.hiddenStyle,
                to: t.visibleStyle,
                isCleaning: !0,
                onTransitionEnd: e
            })
        }, s.prototype.onRevealTransitionEnd = function() {
            this.isHidden || this.emitEvent("reveal")
        }, s.prototype.getHideRevealTransitionEndProperty = function(t) {
            var e = this.layout.options[t];
            if (e.opacity) return "opacity";
            for (var i in e) return i
        }, s.prototype.hide = function() {
            this.isHidden = !0, this.css({
                display: ""
            });
            var t = this.layout.options,
                e = {},
                i = this.getHideRevealTransitionEndProperty("hiddenStyle");
            e[i] = this.onHideTransitionEnd, this.transition({
                from: t.visibleStyle,
                to: t.hiddenStyle,
                isCleaning: !0,
                onTransitionEnd: e
            })
        }, s.prototype.onHideTransitionEnd = function() {
            this.isHidden && (this.css({
                display: "none"
            }), this.emitEvent("hide"))
        }, s.prototype.destroy = function() {
            this.css({
                position: "",
                left: "",
                right: "",
                top: "",
                bottom: "",
                transition: "",
                transform: ""
            })
        }, s
    }),
    function(t, e) {
        "function" == typeof define && define.amd ? define("outlayer/outlayer", ["eventie/eventie", "eventEmitter/EventEmitter", "get-size/get-size", "fizzy-ui-utils/utils", "./item"], function(i, n, o, r, s) {
            return e(t, i, n, o, r, s)
        }) : "object" == typeof exports ? module.exports = e(t, require("eventie"), require("wolfy87-eventemitter"), require("get-size"), require("fizzy-ui-utils"), require("./item")) : t.Outlayer = e(t, t.eventie, t.EventEmitter, t.getSize, t.fizzyUIUtils, t.Outlayer.Item)
    }(window, function(t, e, i, n, o, r) {
        function s(t, e) {
            var i = o.getQueryElement(t);
            if (!i) return void(a && a.error("Bad element for " + this.constructor.namespace + ": " + (i || t)));
            this.element = i, u && (this.$element = u(this.element)), this.options = o.extend({}, this.constructor.defaults), this.option(e);
            var n = ++p;
            this.element.outlayerGUID = n, f[n] = this, this._create(), this.options.isInitLayout && this.layout()
        }
        var a = t.console,
            u = t.jQuery,
            h = function() {},
            p = 0,
            f = {};
        return s.namespace = "outlayer", s.Item = r, s.defaults = {
            containerStyle: {
                position: "relative"
            },
            isInitLayout: !0,
            isOriginLeft: !0,
            isOriginTop: !0,
            isResizeBound: !0,
            isResizingContainer: !0,
            transitionDuration: "0.4s",
            hiddenStyle: {
                opacity: 0,
                transform: "scale(0.001)"
            },
            visibleStyle: {
                opacity: 1,
                transform: "scale(1)"
            }
        }, o.extend(s.prototype, i.prototype), s.prototype.option = function(t) {
            o.extend(this.options, t)
        }, s.prototype._create = function() {
            this.reloadItems(), this.stamps = [], this.stamp(this.options.stamp), o.extend(this.element.style, this.options.containerStyle), this.options.isResizeBound && this.bindResize()
        }, s.prototype.reloadItems = function() {
            this.items = this._itemize(this.element.children)
        }, s.prototype._itemize = function(t) {
            for (var e = this._filterFindItemElements(t), i = this.constructor.Item, n = [], o = 0, r = e.length; r > o; o++) {
                var s = e[o],
                    a = new i(s, this);
                n.push(a)
            }
            return n
        }, s.prototype._filterFindItemElements = function(t) {
            return o.filterFindElements(t, this.options.itemSelector)
        }, s.prototype.getItemElements = function() {
            for (var t = [], e = 0, i = this.items.length; i > e; e++) t.push(this.items[e].element);
            return t
        }, s.prototype.layout = function() {
            this._resetLayout(), this._manageStamps();
            var t = void 0 !== this.options.isLayoutInstant ? this.options.isLayoutInstant : !this._isLayoutInited;
            this.layoutItems(this.items, t), this._isLayoutInited = !0
        }, s.prototype._init = s.prototype.layout, s.prototype._resetLayout = function() {
            this.getSize()
        }, s.prototype.getSize = function() {
            this.size = n(this.element)
        }, s.prototype._getMeasurement = function(t, e) {
            var i, r = this.options[t];
            r ? ("string" == typeof r ? i = this.element.querySelector(r) : o.isElement(r) && (i = r), this[t] = i ? n(i)[e] : r) : this[t] = 0
        }, s.prototype.layoutItems = function(t, e) {
            t = this._getItemsForLayout(t), this._layoutItems(t, e), this._postLayout()
        }, s.prototype._getItemsForLayout = function(t) {
            for (var e = [], i = 0, n = t.length; n > i; i++) {
                var o = t[i];
                o.isIgnored || e.push(o)
            }
            return e
        }, s.prototype._layoutItems = function(t, e) {
            if (this._emitCompleteOnItems("layout", t), t && t.length) {
                for (var i = [], n = 0, o = t.length; o > n; n++) {
                    var r = t[n],
                        s = this._getItemLayoutPosition(r);
                    s.item = r, s.isInstant = e || r.isLayoutInstant, i.push(s)
                }
                this._processLayoutQueue(i)
            }
        }, s.prototype._getItemLayoutPosition = function() {
            return {
                x: 0,
                y: 0
            }
        }, s.prototype._processLayoutQueue = function(t) {
            for (var e = 0, i = t.length; i > e; e++) {
                var n = t[e];
                this._positionItem(n.item, n.x, n.y, n.isInstant)
            }
        }, s.prototype._positionItem = function(t, e, i, n) {
            n ? t.goTo(e, i) : t.moveTo(e, i)
        }, s.prototype._postLayout = function() {
            this.resizeContainer()
        }, s.prototype.resizeContainer = function() {
            if (this.options.isResizingContainer) {
                var t = this._getContainerSize();
                t && (this._setContainerMeasure(t.width, !0), this._setContainerMeasure(t.height, !1))
            }
        }, s.prototype._getContainerSize = h, s.prototype._setContainerMeasure = function(t, e) {
            if (void 0 !== t) {
                var i = this.size;
                i.isBorderBox && (t += e ? i.paddingLeft + i.paddingRight + i.borderLeftWidth + i.borderRightWidth : i.paddingBottom + i.paddingTop + i.borderTopWidth + i.borderBottomWidth), t = Math.max(t, 0), this.element.style[e ? "width" : "height"] = t + "px"
            }
        }, s.prototype._emitCompleteOnItems = function(t, e) {
            function i() {
                o.dispatchEvent(t + "Complete", null, [e])
            }

            function n() {
                s++, s === r && i()
            }
            var o = this,
                r = e.length;
            if (!e || !r) return void i();
            for (var s = 0, a = 0, u = e.length; u > a; a++) {
                var h = e[a];
                h.once(t, n)
            }
        }, s.prototype.dispatchEvent = function(t, e, i) {
            var n = e ? [e].concat(i) : i;
            if (this.emitEvent(t, n), u)
                if (this.$element = this.$element || u(this.element), e) {
                    var o = u.Event(e);
                    o.type = t, this.$element.trigger(o, i)
                } else this.$element.trigger(t, i)
        }, s.prototype.ignore = function(t) {
            var e = this.getItem(t);
            e && (e.isIgnored = !0)
        }, s.prototype.unignore = function(t) {
            var e = this.getItem(t);
            e && delete e.isIgnored
        }, s.prototype.stamp = function(t) {
            if (t = this._find(t)) {
                this.stamps = this.stamps.concat(t);
                for (var e = 0, i = t.length; i > e; e++) {
                    var n = t[e];
                    this.ignore(n)
                }
            }
        }, s.prototype.unstamp = function(t) {
            if (t = this._find(t))
                for (var e = 0, i = t.length; i > e; e++) {
                    var n = t[e];
                    o.removeFrom(this.stamps, n), this.unignore(n)
                }
        }, s.prototype._find = function(t) {
            return t ? ("string" == typeof t && (t = this.element.querySelectorAll(t)), t = o.makeArray(t)) : void 0
        }, s.prototype._manageStamps = function() {
            if (this.stamps && this.stamps.length) {
                this._getBoundingRect();
                for (var t = 0, e = this.stamps.length; e > t; t++) {
                    var i = this.stamps[t];
                    this._manageStamp(i)
                }
            }
        }, s.prototype._getBoundingRect = function() {
            var t = this.element.getBoundingClientRect(),
                e = this.size;
            this._boundingRect = {
                left: t.left + e.paddingLeft + e.borderLeftWidth,
                top: t.top + e.paddingTop + e.borderTopWidth,
                right: t.right - (e.paddingRight + e.borderRightWidth),
                bottom: t.bottom - (e.paddingBottom + e.borderBottomWidth)
            }
        }, s.prototype._manageStamp = h, s.prototype._getElementOffset = function(t) {
            var e = t.getBoundingClientRect(),
                i = this._boundingRect,
                o = n(t),
                r = {
                    left: e.left - i.left - o.marginLeft,
                    top: e.top - i.top - o.marginTop,
                    right: i.right - e.right - o.marginRight,
                    bottom: i.bottom - e.bottom - o.marginBottom
                };
            return r
        }, s.prototype.handleEvent = function(t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, s.prototype.bindResize = function() {
            this.isResizeBound || (e.bind(t, "resize", this), this.isResizeBound = !0)
        }, s.prototype.unbindResize = function() {
            this.isResizeBound && e.unbind(t, "resize", this), this.isResizeBound = !1
        }, s.prototype.onresize = function() {
            function t() {
                e.resize(), delete e.resizeTimeout
            }
            this.resizeTimeout && clearTimeout(this.resizeTimeout);
            var e = this;
            this.resizeTimeout = setTimeout(t, 100)
        }, s.prototype.resize = function() {
            this.isResizeBound && this.needsResizeLayout() && this.layout()
        }, s.prototype.needsResizeLayout = function() {
            var t = n(this.element),
                e = this.size && t;
            return e && t.innerWidth !== this.size.innerWidth
        }, s.prototype.addItems = function(t) {
            var e = this._itemize(t);
            return e.length && (this.items = this.items.concat(e)), e
        }, s.prototype.appended = function(t) {
            var e = this.addItems(t);
            e.length && (this.layoutItems(e, !0), this.reveal(e))
        }, s.prototype.prepended = function(t) {
            var e = this._itemize(t);
            if (e.length) {
                var i = this.items.slice(0);
                this.items = e.concat(i), this._resetLayout(), this._manageStamps(), this.layoutItems(e, !0), this.reveal(e), this.layoutItems(i)
            }
        }, s.prototype.reveal = function(t) {
            this._emitCompleteOnItems("reveal", t);
            for (var e = t && t.length, i = 0; e && e > i; i++) {
                var n = t[i];
                n.reveal()
            }
        }, s.prototype.hide = function(t) {
            this._emitCompleteOnItems("hide", t);
            for (var e = t && t.length, i = 0; e && e > i; i++) {
                var n = t[i];
                n.hide()
            }
        }, s.prototype.revealItemElements = function(t) {
            var e = this.getItems(t);
            this.reveal(e)
        }, s.prototype.hideItemElements = function(t) {
            var e = this.getItems(t);
            this.hide(e)
        }, s.prototype.getItem = function(t) {
            for (var e = 0, i = this.items.length; i > e; e++) {
                var n = this.items[e];
                if (n.element === t) return n
            }
        }, s.prototype.getItems = function(t) {
            t = o.makeArray(t);
            for (var e = [], i = 0, n = t.length; n > i; i++) {
                var r = t[i],
                    s = this.getItem(r);
                s && e.push(s)
            }
            return e
        }, s.prototype.remove = function(t) {
            var e = this.getItems(t);
            if (this._emitCompleteOnItems("remove", e), e && e.length)
                for (var i = 0, n = e.length; n > i; i++) {
                    var r = e[i];
                    r.remove(), o.removeFrom(this.items, r)
                }
        }, s.prototype.destroy = function() {
            var t = this.element.style;
            t.height = "", t.position = "", t.width = "";
            for (var e = 0, i = this.items.length; i > e; e++) {
                var n = this.items[e];
                n.destroy()
            }
            this.unbindResize();
            var o = this.element.outlayerGUID;
            delete f[o], delete this.element.outlayerGUID, u && u.removeData(this.element, this.constructor.namespace)
        }, s.data = function(t) {
            t = o.getQueryElement(t);
            var e = t && t.outlayerGUID;
            return e && f[e]
        }, s.create = function(t, e) {
            function i() {
                s.apply(this, arguments)
            }
            return Object.create ? i.prototype = Object.create(s.prototype) : o.extend(i.prototype, s.prototype), i.prototype.constructor = i, i.defaults = o.extend({}, s.defaults), o.extend(i.defaults, e), i.prototype.settings = {}, i.namespace = t, i.data = s.data, i.Item = function() {
                r.apply(this, arguments)
            }, i.Item.prototype = new r, o.htmlInit(i, t), u && u.bridget && u.bridget(t, i), i
        }, s.Item = r, s
    }),
    function(t, e) {
        "function" == typeof define && define.amd ? define(["outlayer/outlayer", "get-size/get-size", "fizzy-ui-utils/utils"], e) : "object" == typeof exports ? module.exports = e(require("outlayer"), require("get-size"), require("fizzy-ui-utils")) : t.Masonry = e(t.Outlayer, t.getSize, t.fizzyUIUtils)
    }(window, function(t, e, i) {
        var n = t.create("masonry");
        return n.prototype._resetLayout = function() {
            this.getSize(), this._getMeasurement("columnWidth", "outerWidth"), this._getMeasurement("gutter", "outerWidth"), this.measureColumns();
            var t = this.cols;
            for (this.colYs = []; t--;) this.colYs.push(0);
            this.maxY = 0
        }, n.prototype.measureColumns = function() {
            if (this.getContainerWidth(), !this.columnWidth) {
                var t = this.items[0],
                    i = t && t.element;
                this.columnWidth = i && e(i).outerWidth || this.containerWidth
            }
            var n = this.columnWidth += this.gutter,
                o = this.containerWidth + this.gutter,
                r = o / n,
                s = n - o % n,
                a = s && 1 > s ? "round" : "floor";
            r = Math[a](r), this.cols = Math.max(r, 1)
        }, n.prototype.getContainerWidth = function() {
            var t = this.options.isFitWidth ? this.element.parentNode : this.element,
                i = e(t);
            this.containerWidth = i && i.innerWidth
        }, n.prototype._getItemLayoutPosition = function(t) {
            t.getSize();
            var e = t.size.outerWidth % this.columnWidth,
                n = e && 1 > e ? "round" : "ceil",
                o = Math[n](t.size.outerWidth / this.columnWidth);
            o = Math.min(o, this.cols);
            for (var r = this._getColGroup(o), s = Math.min.apply(Math, r), a = i.indexOf(r, s), u = {
                    x: this.columnWidth * a,
                    y: s
                }, h = s + t.size.outerHeight, p = this.cols + 1 - r.length, f = 0; p > f; f++) this.colYs[a + f] = h;
            return u
        }, n.prototype._getColGroup = function(t) {
            if (2 > t) return this.colYs;
            for (var e = [], i = this.cols + 1 - t, n = 0; i > n; n++) {
                var o = this.colYs.slice(n, n + t);
                e[n] = Math.max.apply(Math, o)
            }
            return e
        }, n.prototype._manageStamp = function(t) {
            var i = e(t),
                n = this._getElementOffset(t),
                o = this.options.isOriginLeft ? n.left : n.right,
                r = o + i.outerWidth,
                s = Math.floor(o / this.columnWidth);
            s = Math.max(0, s);
            var a = Math.floor(r / this.columnWidth);
            a -= r % this.columnWidth ? 0 : 1, a = Math.min(this.cols - 1, a);
            for (var u = (this.options.isOriginTop ? n.top : n.bottom) + i.outerHeight, h = s; a >= h; h++) this.colYs[h] = Math.max(u, this.colYs[h])
        }, n.prototype._getContainerSize = function() {
            this.maxY = Math.max.apply(Math, this.colYs);
            var t = {
                height: this.maxY
            };
            return this.options.isFitWidth && (t.width = this._getContainerFitWidth()), t
        }, n.prototype._getContainerFitWidth = function() {
            for (var t = 0, e = this.cols; --e && 0 === this.colYs[e];) t++;
            return (this.cols - t) * this.columnWidth - this.gutter
        }, n.prototype.needsResizeLayout = function() {
            var t = this.containerWidth;
            return this.getContainerWidth(), t !== this.containerWidth
        }, n
    });
! function(t, e, n) {
    "use strict";

    function r(t) {
        return function() {
            var e, n = arguments[0];
            for (e = "[" + (t ? t + ":" : "") + n + "] http://errors.angularjs.org/1.3.9/" + (t ? t + "/" : "") + n, n = 1; n < arguments.length; n++) {
                e = e + (1 == n ? "?" : "&") + "p" + (n - 1) + "=";
                var r, i = encodeURIComponent;
                r = arguments[n], r = "function" == typeof r ? r.toString().replace(/ \{[\s\S]*$/, "") : "undefined" == typeof r ? "undefined" : "string" != typeof r ? JSON.stringify(r) : r, e += i(r)
            }
            return Error(e)
        }
    }

    function i(t) {
        if (null == t || S(t)) return !1;
        var e = t.length;
        return t.nodeType === Hr && e ? !0 : g(t) || jr(t) || 0 === e || "number" == typeof e && e > 0 && e - 1 in t
    }

    function o(t, e, n) {
        var r, a;
        if (t)
            if (b(t))
                for (r in t) "prototype" == r || "length" == r || "name" == r || t.hasOwnProperty && !t.hasOwnProperty(r) || e.call(n, t[r], r, t);
            else if (jr(t) || i(t)) {
            var s = "object" != typeof t;
            for (r = 0, a = t.length; a > r; r++)(s || r in t) && e.call(n, t[r], r, t)
        } else if (t.forEach && t.forEach !== o) t.forEach(e, n, t);
        else
            for (r in t) t.hasOwnProperty(r) && e.call(n, t[r], r, t);
        return t
    }

    function a(t, e, n) {
        for (var r = Object.keys(t).sort(), i = 0; i < r.length; i++) e.call(n, t[r[i]], r[i]);
        return r
    }

    function s(t) {
        return function(e, n) {
            t(n, e)
        }
    }

    function u() {
        return ++Nr
    }

    function c(t, e) {
        e ? t.$$hashKey = e : delete t.$$hashKey
    }

    function l(t) {
        for (var e = t.$$hashKey, n = 1, r = arguments.length; r > n; n++) {
            var i = arguments[n];
            if (i)
                for (var o = Object.keys(i), a = 0, s = o.length; s > a; a++) {
                    var u = o[a];
                    t[u] = i[u]
                }
        }
        return c(t, e), t
    }

    function f(t) {
        return parseInt(t, 10)
    }

    function h() {}

    function $(t) {
        return t
    }

    function p(t) {
        return function() {
            return t
        }
    }

    function d(t) {
        return "undefined" == typeof t
    }

    function v(t) {
        return "undefined" != typeof t
    }

    function m(t) {
        return null !== t && "object" == typeof t
    }

    function g(t) {
        return "string" == typeof t
    }

    function y(t) {
        return "number" == typeof t
    }

    function w(t) {
        return "[object Date]" === Tr.call(t)
    }

    function b(t) {
        return "function" == typeof t
    }

    function x(t) {
        return "[object RegExp]" === Tr.call(t)
    }

    function S(t) {
        return t && t.window === t
    }

    function C(t) {
        return t && t.$evalAsync && t.$watch
    }

    function k(t) {
        return "boolean" == typeof t
    }

    function A(t) {
        return !(!t || !(t.nodeName || t.prop && t.attr && t.find))
    }

    function E(t) {
        var e = {};
        t = t.split(",");
        var n;
        for (n = 0; n < t.length; n++) e[t[n]] = !0;
        return e
    }

    function O(t) {
        return Sr(t.nodeName || t[0] && t[0].nodeName)
    }

    function T(t, e) {
        var n = t.indexOf(e);
        return n >= 0 && t.splice(n, 1), e
    }

    function M(t, e, n, r) {
        if (S(t) || C(t)) throw Mr("cpws");
        if (e) {
            if (t === e) throw Mr("cpi");
            if (n = n || [], r = r || [], m(t)) {
                var i = n.indexOf(t);
                if (-1 !== i) return r[i];
                n.push(t), r.push(e)
            }
            if (jr(t))
                for (var a = e.length = 0; a < t.length; a++) i = M(t[a], null, n, r), m(t[a]) && (n.push(t[a]), r.push(i)), e.push(i);
            else {
                var s = e.$$hashKey;
                jr(e) ? e.length = 0 : o(e, function(t, n) {
                    delete e[n]
                });
                for (a in t) t.hasOwnProperty(a) && (i = M(t[a], null, n, r), m(t[a]) && (n.push(t[a]), r.push(i)), e[a] = i);
                c(e, s)
            }
        } else(e = t) && (jr(t) ? e = M(t, [], n, r) : w(t) ? e = new Date(t.getTime()) : x(t) ? (e = new RegExp(t.source, t.toString().match(/[^\/]*$/)[0]), e.lastIndex = t.lastIndex) : m(t) && (i = Object.create(Object.getPrototypeOf(t)), e = M(t, i, n, r)));
        return e
    }

    function V(t, e) {
        if (jr(t)) {
            e = e || [];
            for (var n = 0, r = t.length; r > n; n++) e[n] = t[n]
        } else if (m(t))
            for (n in e = e || {}, t)("$" !== n.charAt(0) || "$" !== n.charAt(1)) && (e[n] = t[n]);
        return e || t
    }

    function N(t, e) {
        if (t === e) return !0;
        if (null === t || null === e) return !1;
        if (t !== t && e !== e) return !0;
        var r, i = typeof t;
        if (i == typeof e && "object" == i) {
            if (!jr(t)) {
                if (w(t)) return w(e) ? N(t.getTime(), e.getTime()) : !1;
                if (x(t) && x(e)) return t.toString() == e.toString();
                if (C(t) || C(e) || S(t) || S(e) || jr(e)) return !1;
                i = {};
                for (r in t)
                    if ("$" !== r.charAt(0) && !b(t[r])) {
                        if (!N(t[r], e[r])) return !1;
                        i[r] = !0
                    }
                for (r in e)
                    if (!i.hasOwnProperty(r) && "$" !== r.charAt(0) && e[r] !== n && !b(e[r])) return !1;
                return !0
            }
            if (!jr(e)) return !1;
            if ((i = t.length) == e.length) {
                for (r = 0; i > r; r++)
                    if (!N(t[r], e[r])) return !1;
                return !0
            }
        }
        return !1
    }

    function D(t, e, n) {
        return t.concat(Ar.call(e, n))
    }

    function j(t, e) {
        var n = 2 < arguments.length ? Ar.call(arguments, 2) : [];
        return !b(e) || e instanceof RegExp ? e : n.length ? function() {
            return arguments.length ? e.apply(t, D(n, arguments, 0)) : e.apply(t, n)
        } : function() {
            return arguments.length ? e.apply(t, arguments) : e.call(t)
        }
    }

    function P(t, r) {
        var i = r;
        return "string" == typeof t && "$" === t.charAt(0) && "$" === t.charAt(1) ? i = n : S(r) ? i = "$WINDOW" : r && e === r ? i = "$DOCUMENT" : C(r) && (i = "$SCOPE"), i
    }

    function R(t, e) {
        return "undefined" == typeof t ? n : (y(e) || (e = e ? 2 : null), JSON.stringify(t, P, e))
    }

    function q(t) {
        return g(t) ? JSON.parse(t) : t
    }

    function I(t) {
        t = yr(t).clone();
        try {
            t.empty()
        } catch (e) {}
        var n = yr("<div>").append(t).html();
        try {
            return t[0].nodeType === _r ? Sr(n) : n.match(/^(<[^>]+>)/)[1].replace(/^<([\w\-]+)/, function(t, e) {
                return "<" + Sr(e)
            })
        } catch (r) {
            return Sr(n)
        }
    }

    function U(t) {
        try {
            return decodeURIComponent(t)
        } catch (e) {}
    }

    function F(t) {
        var e, n, r = {};
        return o((t || "").split("&"), function(t) {
            t && (e = t.replace(/\+/g, "%20").split("="), n = U(e[0]), v(n) && (t = v(e[1]) ? U(e[1]) : !0, Cr.call(r, n) ? jr(r[n]) ? r[n].push(t) : r[n] = [r[n], t] : r[n] = t))
        }), r
    }

    function H(t) {
        var e = [];
        return o(t, function(t, n) {
            jr(t) ? o(t, function(t) {
                e.push(L(n, !0) + (!0 === t ? "" : "=" + L(t, !0)))
            }) : e.push(L(n, !0) + (!0 === t ? "" : "=" + L(t, !0)))
        }), e.length ? e.join("&") : ""
    }

    function _(t) {
        return L(t, !0).replace(/%26/gi, "&").replace(/%3D/gi, "=").replace(/%2B/gi, "+")
    }

    function L(t, e) {
        return encodeURIComponent(t).replace(/%40/gi, "@").replace(/%3A/gi, ":").replace(/%24/g, "$").replace(/%2C/gi, ",").replace(/%3B/gi, ";").replace(/%20/g, e ? "%20" : "+")
    }

    function B(t, e) {
        var n, r, i = Ir.length;
        for (t = yr(t), r = 0; i > r; ++r)
            if (n = Ir[r] + e, g(n = t.attr(n))) return n;
        return null
    }

    function z(t, e) {
        var n, r, i = {};
        o(Ir, function(e) {
            e += "app", !n && t.hasAttribute && t.hasAttribute(e) && (n = t, r = t.getAttribute(e))
        }), o(Ir, function(e) {
            e += "app";
            var i;
            !n && (i = t.querySelector("[" + e.replace(":", "\\:") + "]")) && (n = i, r = i.getAttribute(e))
        }), n && (i.strictDi = null !== B(n, "strict-di"), e(n, r ? [r] : [], i))
    }

    function G(n, r, i) {
        m(i) || (i = {}), i = l({
            strictDi: !1
        }, i);
        var a = function() {
                if (n = yr(n), n.injector()) {
                    var t = n[0] === e ? "document" : I(n);
                    throw Mr("btstrpd", t.replace(/</, "&lt;").replace(/>/, "&gt;"))
                }
                return r = r || [], r.unshift(["$provide", function(t) {
                    t.value("$rootElement", n)
                }]), i.debugInfoEnabled && r.push(["$compileProvider", function(t) {
                    t.debugInfoEnabled(!0)
                }]), r.unshift("ng"), t = Ne(r, i.strictDi), t.invoke(["$rootScope", "$rootElement", "$compile", "$injector", function(t, e, n, r) {
                    t.$apply(function() {
                        e.data("$injector", r), n(e)(t)
                    })
                }]), t
            },
            s = /^NG_ENABLE_DEBUG_INFO!/,
            u = /^NG_DEFER_BOOTSTRAP!/;
        return t && s.test(t.name) && (i.debugInfoEnabled = !0, t.name = t.name.replace(s, "")), t && !u.test(t.name) ? a() : (t.name = t.name.replace(u, ""), void(Vr.resumeBootstrap = function(t) {
            o(t, function(t) {
                r.push(t)
            }), a()
        }))
    }

    function W() {
        t.name = "NG_ENABLE_DEBUG_INFO!" + t.name, t.location.reload()
    }

    function J(t) {
        if (t = Vr.element(t).injector(), !t) throw Mr("test");
        return t.get("$$testability")
    }

    function Z(t, e) {
        return e = e || "_", t.replace(Ur, function(t, n) {
            return (n ? e : "") + t.toLowerCase()
        })
    }

    function Y() {
        var e;
        Fr || ((wr = t.jQuery) && wr.fn.on ? (yr = wr, l(wr.fn, {
            scope: ei.scope,
            isolateScope: ei.isolateScope,
            controller: ei.controller,
            injector: ei.injector,
            inheritedData: ei.inheritedData
        }), e = wr.cleanData, wr.cleanData = function(t) {
            var n;
            if (Dr) Dr = !1;
            else
                for (var r, i = 0; null != (r = t[i]); i++)(n = wr._data(r, "events")) && n.$destroy && wr(r).triggerHandler("$destroy");
            e(t)
        }) : yr = ue, Vr.element = yr, Fr = !0)
    }

    function K(t, e, n) {
        if (!t) throw Mr("areq", e || "?", n || "required");
        return t
    }

    function X(t, e, n) {
        return n && jr(t) && (t = t[t.length - 1]), K(b(t), e, "not a function, got " + (t && "object" == typeof t ? t.constructor.name || "Object" : typeof t)), t
    }

    function Q(t, e) {
        if ("hasOwnProperty" === t) throw Mr("badname", e)
    }


    function te(t, e, n) {
        if (!e) return t;
        e = e.split(".");
        for (var r, i = t, o = e.length, a = 0; o > a; a++) r = e[a], t && (t = (i = t)[r]);
        return !n && b(t) ? j(i, t) : t
    }

    function ee(t) {
        var e = t[0];
        t = t[t.length - 1];
        var n = [e];
        do {
            if (e = e.nextSibling, !e) break;
            n.push(e)
        } while (e !== t);
        return yr(n)
    }

    function ne() {
        return Object.create(null)
    }

    function re(t) {
        function e(t, e, n) {
            return t[e] || (t[e] = n())
        }
        var n = r("$injector"),
            i = r("ng");
        return t = e(t, "angular", Object), t.$$minErr = t.$$minErr || r, e(t, "module", function() {
            var t = {};
            return function(r, o, a) {
                if ("hasOwnProperty" === r) throw i("badname", "module");
                return o && t.hasOwnProperty(r) && (t[r] = null), e(t, r, function() {
                    function t(t, n, r, i) {
                        return i || (i = e),
                            function() {
                                return i[r || "push"]([t, n, arguments]), c
                            }
                    }
                    if (!o) throw n("nomod", r);
                    var e = [],
                        i = [],
                        s = [],
                        u = t("$injector", "invoke", "push", i),
                        c = {
                            _invokeQueue: e,
                            _configBlocks: i,
                            _runBlocks: s,
                            requires: o,
                            name: r,
                            provider: t("$provide", "provider"),
                            factory: t("$provide", "factory"),
                            service: t("$provide", "service"),
                            value: t("$provide", "value"),
                            constant: t("$provide", "constant", "unshift"),
                            animation: t("$animateProvider", "register"),
                            filter: t("$filterProvider", "register"),
                            controller: t("$controllerProvider", "register"),
                            directive: t("$compileProvider", "directive"),
                            config: u,
                            run: function(t) {
                                return s.push(t), this
                            }
                        };
                    return a && u(a), c
                })
            }
        })
    }

    function ie(e) {
        l(e, {
            bootstrap: G,
            copy: M,
            extend: l,
            equals: N,
            element: yr,
            forEach: o,
            injector: Ne,
            noop: h,
            bind: j,
            toJson: R,
            fromJson: q,
            identity: $,
            isUndefined: d,
            isDefined: v,
            isString: g,
            isFunction: b,
            isObject: m,
            isNumber: y,
            isElement: A,
            isArray: jr,
            version: Lr,
            isDate: w,
            lowercase: Sr,
            uppercase: kr,
            callbacks: {
                counter: 0
            },
            getTestability: J,
            $$minErr: r,
            $$csp: qr,
            reloadWithDebugInfo: W
        }), br = re(t);
        try {
            br("ngLocale")
        } catch (n) {
            br("ngLocale", []).provider("$locale", nn)
        }
        br("ng", ["ngLocale"], ["$provide", function(t) {
            t.provider({
                $$sanitizeUri: Dn
            }), t.provider("$compile", Ue).directive({
                a: Gi,
                input: uo,
                textarea: uo,
                form: Yi,
                script: Jo,
                select: Ko,
                style: Qo,
                option: Xo,
                ngBind: fo,
                ngBindHtml: $o,
                ngBindTemplate: ho,
                ngClass: vo,
                ngClassEven: go,
                ngClassOdd: mo,
                ngCloak: yo,
                ngController: wo,
                ngForm: Ki,
                ngHide: _o,
                ngIf: So,
                ngInclude: Co,
                ngInit: Ao,
                ngNonBindable: Io,
                ngPluralize: Uo,
                ngRepeat: Fo,
                ngShow: Ho,
                ngStyle: Lo,
                ngSwitch: Bo,
                ngSwitchWhen: zo,
                ngSwitchDefault: Go,
                ngOptions: Yo,
                ngTransclude: Wo,
                ngModel: Po,
                ngList: Eo,
                ngChange: po,
                pattern: ea,
                ngPattern: ea,
                required: ta,
                ngRequired: ta,
                minlength: ra,
                ngMinlength: ra,
                maxlength: na,
                ngMaxlength: na,
                ngValue: lo,
                ngModelOptions: qo
            }).directive({
                ngInclude: ko
            }).directive(Wi).directive(bo), t.provider({
                $anchorScroll: De,
                $animate: fi,
                $browser: Re,
                $cacheFactory: qe,
                $controller: Le,
                $document: Be,
                $exceptionHandler: ze,
                $filter: zn,
                $interpolate: tn,
                $interval: en,
                $http: Ye,
                $httpBackend: Xe,
                $location: vn,
                $log: mn,
                $parse: En,
                $rootScope: Nn,
                $q: On,
                $$q: Tn,
                $sce: qn,
                $sceDelegate: Rn,
                $sniffer: In,
                $templateCache: Ie,
                $templateRequest: Un,
                $$testability: Fn,
                $timeout: Hn,
                $window: Bn,
                $$rAF: Vn,
                $$asyncCallback: je,
                $$jqLite: Ee
            })
        }])
    }

    function oe(t) {
        return t.replace(Gr, function(t, e, n, r) {
            return r ? n.toUpperCase() : n
        }).replace(Wr, "Moz$1")
    }

    function ae(t) {
        return t = t.nodeType, t === Hr || !t || 9 === t
    }

    function se(t, e) {
        var n, r, i = e.createDocumentFragment(),
            a = [];
        if (Kr.test(t)) {
            for (n = n || i.appendChild(e.createElement("div")), r = (Xr.exec(t) || ["", ""])[1].toLowerCase(), r = ti[r] || ti._default, n.innerHTML = r[1] + t.replace(Qr, "<$1></$2>") + r[2], r = r[0]; r--;) n = n.lastChild;
            a = D(a, n.childNodes), n = i.firstChild, n.textContent = ""
        } else a.push(e.createTextNode(t));
        return i.textContent = "", i.innerHTML = "", o(a, function(t) {
            i.appendChild(t)
        }), i
    }

    function ue(t) {
        if (t instanceof ue) return t;
        var n;
        if (g(t) && (t = Pr(t), n = !0), !(this instanceof ue)) {
            if (n && "<" != t.charAt(0)) throw Zr("nosel");
            return new ue(t)
        }
        if (n) {
            n = e;
            var r;
            t = (r = Yr.exec(t)) ? [n.createElement(r[1])] : (r = se(t, n)) ? r.childNodes : []
        }
        ge(this, t)
    }

    function ce(t) {
        return t.cloneNode(!0)
    }

    function le(t, e) {
        if (e || he(t), t.querySelectorAll)
            for (var n = t.querySelectorAll("*"), r = 0, i = n.length; i > r; r++) he(n[r])
    }

    function fe(t, e, n, r) {
        if (v(r)) throw Zr("offargs");
        var i = (r = $e(t)) && r.events,
            a = r && r.handle;
        if (a)
            if (e) o(e.split(" "), function(e) {
                if (v(n)) {
                    var r = i[e];
                    if (T(r || [], n), r && 0 < r.length) return
                }
                t.removeEventListener(e, a, !1), delete i[e]
            });
            else
                for (e in i) "$destroy" !== e && t.removeEventListener(e, a, !1), delete i[e]
    }

    function he(t, e) {
        var r = t.ng339,
            i = r && Br[r];
        i && (e ? delete i.data[e] : (i.handle && (i.events.$destroy && i.handle({}, "$destroy"), fe(t)), delete Br[r], t.ng339 = n))
    }

    function $e(t, e) {
        var r = t.ng339,
            r = r && Br[r];
        return e && !r && (t.ng339 = r = ++zr, r = Br[r] = {
            events: {},
            data: {},
            handle: n
        }), r
    }

    function pe(t, e, n) {
        if (ae(t)) {
            var r = v(n),
                i = !r && e && !m(e),
                o = !e;
            if (t = (t = $e(t, !i)) && t.data, r) t[e] = n;
            else {
                if (o) return t;
                if (i) return t && t[e];
                l(t, e)
            }
        }
    }

    function de(t, e) {
        return t.getAttribute ? -1 < (" " + (t.getAttribute("class") || "") + " ").replace(/[\n\t]/g, " ").indexOf(" " + e + " ") : !1
    }

    function ve(t, e) {
        e && t.setAttribute && o(e.split(" "), function(e) {
            t.setAttribute("class", Pr((" " + (t.getAttribute("class") || "") + " ").replace(/[\n\t]/g, " ").replace(" " + Pr(e) + " ", " ")))
        })
    }

    function me(t, e) {
        if (e && t.setAttribute) {
            var n = (" " + (t.getAttribute("class") || "") + " ").replace(/[\n\t]/g, " ");
            o(e.split(" "), function(t) {
                t = Pr(t), -1 === n.indexOf(" " + t + " ") && (n += t + " ")
            }), t.setAttribute("class", Pr(n))
        }
    }

    function ge(t, e) {
        if (e)
            if (e.nodeType) t[t.length++] = e;
            else {
                var n = e.length;
                if ("number" == typeof n && e.window !== e) {
                    if (n)
                        for (var r = 0; n > r; r++) t[t.length++] = e[r]
                } else t[t.length++] = e
            }
    }

    function ye(t, e) {
        return we(t, "$" + (e || "ngController") + "Controller")
    }

    function we(t, e, r) {
        for (9 == t.nodeType && (t = t.documentElement), e = jr(e) ? e : [e]; t;) {
            for (var i = 0, o = e.length; o > i; i++)
                if ((r = yr.data(t, e[i])) !== n) return r;
            t = t.parentNode || 11 === t.nodeType && t.host
        }
    }

    function be(t) {
        for (le(t, !0); t.firstChild;) t.removeChild(t.firstChild)
    }

    function xe(t, e) {
        e || le(t);
        var n = t.parentNode;
        n && n.removeChild(t)
    }

    function Se(e, n) {
        n = n || t, "complete" === n.document.readyState ? n.setTimeout(e) : yr(n).on("load", e)
    }

    function Ce(t, e) {
        var n = ni[e.toLowerCase()];
        return n && ri[O(t)] && n
    }

    function ke(t, e) {
        var n = t.nodeName;
        return ("INPUT" === n || "TEXTAREA" === n) && ii[e]
    }

    function Ae(t, e) {
        var n = function(n, r) {
            n.isDefaultPrevented = function() {
                return n.defaultPrevented
            };
            var i = e[r || n.type],
                o = i ? i.length : 0;
            if (o) {
                if (d(n.immediatePropagationStopped)) {
                    var a = n.stopImmediatePropagation;
                    n.stopImmediatePropagation = function() {
                        n.immediatePropagationStopped = !0, n.stopPropagation && n.stopPropagation(), a && a.call(n)
                    }
                }
                n.isImmediatePropagationStopped = function() {
                    return !0 === n.immediatePropagationStopped
                }, o > 1 && (i = V(i));
                for (var s = 0; o > s; s++) n.isImmediatePropagationStopped() || i[s].call(t, n)
            }
        };
        return n.elem = t, n
    }

    function Ee() {
        this.$get = function() {
            return l(ue, {
                hasClass: function(t, e) {
                    return t.attr && (t = t[0]), de(t, e)
                },
                addClass: function(t, e) {
                    return t.attr && (t = t[0]), me(t, e)
                },
                removeClass: function(t, e) {
                    return t.attr && (t = t[0]), ve(t, e)
                }
            })
        }
    }

    function Oe(t, e) {
        var n = t && t.$$hashKey;
        return n ? ("function" == typeof n && (n = t.$$hashKey()), n) : (n = typeof t, n = "function" == n || "object" == n && null !== t ? t.$$hashKey = n + ":" + (e || u)() : n + ":" + t)
    }

    function Te(t, e) {
        if (e) {
            var n = 0;
            this.nextUid = function() {
                return ++n
            }
        }
        o(t, this.put, this)
    }

    function Me(t) {
        return (t = t.toString().replace(ui, "").match(oi)) ? "function(" + (t[1] || "").replace(/[\s\r\n]+/, " ") + ")" : "fn"
    }

    function Ve(t, e, n) {
        var r;
        if ("function" == typeof t) {
            if (!(r = t.$inject)) {
                if (r = [], t.length) {
                    if (e) throw g(n) && n || (n = t.name || Me(t)), ci("strictdi", n);
                    e = t.toString().replace(ui, ""), e = e.match(oi), o(e[1].split(ai), function(t) {
                        t.replace(si, function(t, e, n) {
                            r.push(n)
                        })
                    })
                }
                t.$inject = r
            }
        } else jr(t) ? (e = t.length - 1, X(t[e], "fn"), r = t.slice(0, e)) : X(t, "fn", !0);
        return r
    }

    function Ne(t, e) {
        function r(t) {
            return function(e, n) {
                return m(e) ? void o(e, s(t)) : t(e, n)
            }
        }

        function i(t, e) {
            if (Q(t, "service"), (b(e) || jr(e)) && (e = w.instantiate(e)), !e.$get) throw ci("pget", t);
            return y[t + "Provider"] = e
        }

        function a(t, e) {
            return function() {
                var n = S.invoke(e, this);
                if (d(n)) throw ci("undef", t);
                return n
            }
        }

        function u(t, e, n) {
            return i(t, {
                $get: !1 !== n ? a(t, e) : e
            })
        }

        function c(t) {
            var e, n = [];
            return o(t, function(t) {
                function r(t) {
                    var e, n;
                    for (e = 0, n = t.length; n > e; e++) {
                        var r = t[e],
                            i = w.get(r[0]);
                        i[r[1]].apply(i, r[2])
                    }
                }
                if (!v.get(t)) {
                    v.put(t, !0);
                    try {
                        g(t) ? (e = br(t), n = n.concat(c(e.requires)).concat(e._runBlocks), r(e._invokeQueue), r(e._configBlocks)) : b(t) ? n.push(w.invoke(t)) : jr(t) ? n.push(w.invoke(t)) : X(t, "module")
                    } catch (i) {
                        throw jr(t) && (t = t[t.length - 1]), i.message && i.stack && -1 == i.stack.indexOf(i.message) && (i = i.message + "\n" + i.stack), ci("modulerr", t, i.stack || i.message || i)
                    }
                }
            }), n
        }

        function l(t, n) {
            function r(e, r) {
                if (t.hasOwnProperty(e)) {
                    if (t[e] === f) throw ci("cdep", e + " <- " + $.join(" <- "));
                    return t[e]
                }
                try {
                    return $.unshift(e), t[e] = f, t[e] = n(e, r)
                } catch (i) {
                    throw t[e] === f && delete t[e], i
                } finally {
                    $.shift()
                }
            }

            function i(t, n, i, o) {
                "string" == typeof i && (o = i, i = null);
                var a, s, u, c = [],
                    l = Ve(t, e, o);
                for (s = 0, a = l.length; a > s; s++) {
                    if (u = l[s], "string" != typeof u) throw ci("itkn", u);
                    c.push(i && i.hasOwnProperty(u) ? i[u] : r(u, o))
                }
                return jr(t) && (t = t[a]), t.apply(n, c)
            }
            return {
                invoke: i,
                instantiate: function(t, e, n) {
                    var r = Object.create((jr(t) ? t[t.length - 1] : t).prototype);
                    return t = i(t, r, e, n), m(t) || b(t) ? t : r
                },
                get: r,
                annotate: Ve,
                has: function(e) {
                    return y.hasOwnProperty(e + "Provider") || t.hasOwnProperty(e)
                }
            }
        }
        e = !0 === e;
        var f = {},
            $ = [],
            v = new Te([], !0),
            y = {
                $provide: {
                    provider: r(i),
                    factory: r(u),
                    service: r(function(t, e) {
                        return u(t, ["$injector", function(t) {
                            return t.instantiate(e)
                        }])
                    }),
                    value: r(function(t, e) {
                        return u(t, p(e), !1)
                    }),
                    constant: r(function(t, e) {
                        Q(t, "constant"), y[t] = e, x[t] = e
                    }),
                    decorator: function(t, e) {
                        var n = w.get(t + "Provider"),
                            r = n.$get;
                        n.$get = function() {
                            var t = S.invoke(r, n);
                            return S.invoke(e, null, {
                                $delegate: t
                            })
                        }
                    }
                }
            },
            w = y.$injector = l(y, function(t, e) {
                throw Vr.isString(e) && $.push(e), ci("unpr", $.join(" <- "))
            }),
            x = {},
            S = x.$injector = l(x, function(t, e) {
                var r = w.get(t + "Provider", e);
                return S.invoke(r.$get, r, n, t)
            });
        return o(c(t), function(t) {
            S.invoke(t || h)
        }), S
    }

    function De() {
        var t = !0;
        this.disableAutoScrolling = function() {
            t = !1
        }, this.$get = ["$window", "$location", "$rootScope", function(e, n, r) {
            function i(t) {
                var e = null;
                return Array.prototype.some.call(t, function(t) {
                    return "a" === O(t) ? (e = t, !0) : void 0
                }), e
            }

            function o(t) {
                if (t) {
                    t.scrollIntoView();
                    var n;
                    n = a.yOffset, b(n) ? n = n() : A(n) ? (n = n[0], n = "fixed" !== e.getComputedStyle(n).position ? 0 : n.getBoundingClientRect().bottom) : y(n) || (n = 0), n && (t = t.getBoundingClientRect().top, e.scrollBy(0, t - n))
                } else e.scrollTo(0, 0)
            }

            function a() {
                var t, e = n.hash();
                e ? (t = s.getElementById(e)) ? o(t) : (t = i(s.getElementsByName(e))) ? o(t) : "top" === e && o(null) : o(null)
            }
            var s = e.document;
            return t && r.$watch(function() {
                return n.hash()
            }, function(t, e) {
                t === e && "" === t || Se(function() {
                    r.$evalAsync(a)
                })
            }), a
        }]
    }

    function je() {
        this.$get = ["$$rAF", "$timeout", function(t, e) {
            return t.supported ? function(e) {
                return t(e)
            } : function(t) {
                return e(t, 0, !1)
            }
        }]
    }

    function Pe(t, e, r, i) {
        function a(t) {
            try {
                t.apply(null, Ar.call(arguments, 1))
            } finally {
                if (x--, 0 === x)
                    for (; S.length;) try {
                        S.pop()()
                    } catch (e) {
                        r.error(e)
                    }
            }
        }

        function s(t, e) {
            ! function n() {
                o(k, function(t) {
                    t()
                }), C = e(n, t)
            }()
        }

        function u() {
            c(), l()
        }

        function c() {
            A = t.history.state, A = d(A) ? null : A, N(A, j) && (A = j), j = A
        }

        function l() {
            (O !== $.url() || E !== A) && (O = $.url(), E = A, o(V, function(t) {
                t($.url(), A)
            }))
        }

        function f(t) {
            try {
                return decodeURIComponent(t)
            } catch (e) {
                return t
            }
        }
        var $ = this,
            p = e[0],
            v = t.location,
            m = t.history,
            y = t.setTimeout,
            w = t.clearTimeout,
            b = {};
        $.isMock = !1;
        var x = 0,
            S = [];
        $.$$completeOutstandingRequest = a, $.$$incOutstandingRequestCount = function() {
            x++
        }, $.notifyWhenNoOutstandingRequests = function(t) {
            o(k, function(t) {
                t()
            }), 0 === x ? t() : S.push(t)
        };
        var C, k = [];
        $.addPollFn = function(t) {
            return d(C) && s(100, y), k.push(t), t
        };
        var A, E, O = v.href,
            T = e.find("base"),
            M = null;
        c(), E = A, $.url = function(e, n, r) {
            if (d(r) && (r = null), v !== t.location && (v = t.location), m !== t.history && (m = t.history), e) {
                var o = E === r;
                if (O === e && (!i.history || o)) return $;
                var a = O && un(O) === un(e);
                return O = e, E = r, !i.history || a && o ? (a || (M = e), n ? v.replace(e) : a ? (n = v, r = e.indexOf("#"), e = -1 === r ? "" : e.substr(r + 1), n.hash = e) : v.href = e) : (m[n ? "replaceState" : "pushState"](r, "", e), c(), E = A), $
            }
            return M || v.href.replace(/%27/g, "'")
        }, $.state = function() {
            return A
        };
        var V = [],
            D = !1,
            j = null;
        $.onUrlChange = function(e) {
            return D || (i.history && yr(t).on("popstate", u), yr(t).on("hashchange", u), D = !0), V.push(e), e
        }, $.$$checkUrlChange = l, $.baseHref = function() {
            var t = T.attr("href");
            return t ? t.replace(/^(https?\:)?\/\/[^\/]*/, "") : ""
        };
        var P = {},
            R = "",
            q = $.baseHref();
        $.cookies = function(t, e) {
            var i, o, a, s;
            if (!t) {
                if (p.cookie !== R)
                    for (R = p.cookie, i = R.split("; "), P = {}, a = 0; a < i.length; a++) o = i[a], s = o.indexOf("="), s > 0 && (t = f(o.substring(0, s)), P[t] === n && (P[t] = f(o.substring(s + 1))));
                return P
            }
            e === n ? p.cookie = encodeURIComponent(t) + "=;path=" + q + ";expires=Thu, 01 Jan 1970 00:00:00 GMT" : g(e) && (i = (p.cookie = encodeURIComponent(t) + "=" + encodeURIComponent(e) + ";path=" + q).length + 1, i > 4096 && r.warn("Cookie '" + t + "' possibly not set or overflowed because it was too large (" + i + " > 4096 bytes)!"))
        }, $.defer = function(t, e) {
            var n;
            return x++, n = y(function() {
                delete b[n], a(t)
            }, e || 0), b[n] = !0, n
        }, $.defer.cancel = function(t) {
            return b[t] ? (delete b[t], w(t), a(h), !0) : !1
        }
    }

    function Re() {
        this.$get = ["$window", "$log", "$sniffer", "$document", function(t, e, n, r) {
            return new Pe(t, r, e, n)
        }]
    }

    function qe() {
        this.$get = function() {
            function t(t, n) {
                function i(t) {
                    t != h && ($ ? $ == t && ($ = t.n) : $ = t, o(t.n, t.p), o(t, h), h = t, h.n = null)
                }

                function o(t, e) {
                    t != e && (t && (t.p = e), e && (e.n = t))
                }
                if (t in e) throw r("$cacheFactory")("iid", t);
                var a = 0,
                    s = l({}, n, {
                        id: t
                    }),
                    u = {},
                    c = n && n.capacity || Number.MAX_VALUE,
                    f = {},
                    h = null,
                    $ = null;
                return e[t] = {
                    put: function(t, e) {
                        if (c < Number.MAX_VALUE) {
                            var n = f[t] || (f[t] = {
                                key: t
                            });
                            i(n)
                        }
                        return d(e) ? void 0 : (t in u || a++, u[t] = e, a > c && this.remove($.key), e)
                    },
                    get: function(t) {
                        if (c < Number.MAX_VALUE) {
                            var e = f[t];
                            if (!e) return;
                            i(e)
                        }
                        return u[t]
                    },
                    remove: function(t) {
                        if (c < Number.MAX_VALUE) {
                            var e = f[t];
                            if (!e) return;
                            e == h && (h = e.p), e == $ && ($ = e.n), o(e.n, e.p), delete f[t]
                        }
                        delete u[t], a--
                    },
                    removeAll: function() {
                        u = {}, a = 0, f = {}, h = $ = null
                    },
                    destroy: function() {
                        f = s = u = null, delete e[t]
                    },
                    info: function() {
                        return l({}, s, {
                            size: a
                        })
                    }
                }
            }
            var e = {};
            return t.info = function() {
                var t = {};
                return o(e, function(e, n) {
                    t[n] = e.info()
                }), t
            }, t.get = function(t) {
                return e[t]
            }, t
        }
    }

    function Ie() {
        this.$get = ["$cacheFactory", function(t) {
            return t("templates")
        }]
    }

    function Ue(t, r) {
        function i(t, e) {
            var n = /^\s*([@&]|=(\*?))(\??)\s*(\w*)\s*$/,
                r = {};
            return o(t, function(t, i) {
                var o = t.match(n);
                if (!o) throw hi("iscp", e, i, t);
                r[i] = {
                    mode: o[1][0],
                    collection: "*" === o[2],
                    optional: "?" === o[3],
                    attrName: o[4] || i
                }
            }), r
        }
        var a = {},
            u = /^\s*directive\:\s*([\w\-]+)\s+(.*)$/,
            c = /(([\w\-]+)(?:\:([^;]+))?;?)/,
            f = E("ngSrc,ngSrcset,src,srcset"),
            d = /^(?:(\^\^?)?(\?)?(\^\^?)?)?/,
            y = /^(on[a-z]+|formaction)$/;
        this.directive = function x(e, n) {
            return Q(e, "directive"), g(e) ? (K(n, "directiveFactory"), a.hasOwnProperty(e) || (a[e] = [], t.factory(e + "Directive", ["$injector", "$exceptionHandler", function(t, n) {
                var r = [];
                return o(a[e], function(o, a) {
                    try {
                        var s = t.invoke(o);
                        b(s) ? s = {
                            compile: p(s)
                        } : !s.compile && s.link && (s.compile = p(s.link)), s.priority = s.priority || 0, s.index = a, s.name = s.name || e, s.require = s.require || s.controller && s.name, s.restrict = s.restrict || "EA", m(s.scope) && (s.$$isolateBindings = i(s.scope, s.name)), r.push(s)
                    } catch (u) {
                        n(u)
                    }
                }), r
            }])), a[e].push(n)) : o(e, s(x)), this
        }, this.aHrefSanitizationWhitelist = function(t) {
            return v(t) ? (r.aHrefSanitizationWhitelist(t), this) : r.aHrefSanitizationWhitelist()
        }, this.imgSrcSanitizationWhitelist = function(t) {
            return v(t) ? (r.imgSrcSanitizationWhitelist(t), this) : r.imgSrcSanitizationWhitelist()
        };
        var w = !0;
        this.debugInfoEnabled = function(t) {
            return v(t) ? (w = t, this) : w
        }, this.$get = ["$injector", "$interpolate", "$exceptionHandler", "$templateRequest", "$parse", "$controller", "$rootScope", "$document", "$sce", "$animate", "$$sanitizeUri", function(t, r, i, s, p, v, x, S, k, A, E) {
            function M(t, e) {
                try {
                    t.addClass(e)
                } catch (n) {}
            }

            function V(t, e, n, r, i) {
                t instanceof yr || (t = yr(t)), o(t, function(e, n) {
                    e.nodeType == _r && e.nodeValue.match(/\S+/) && (t[n] = yr(e).wrap("<span></span>").parent()[0])
                });
                var a = D(t, e, t, n, r, i);
                V.$$addScopeClass(t);
                var s = null;
                return function(e, n, r) {
                    K(e, "scope"), r = r || {};
                    var i = r.parentBoundTranscludeFn,
                        o = r.transcludeControllers;
                    if (r = r.futureParentElement, i && i.$$boundTransclude && (i = i.$$boundTransclude), s || (s = (r = r && r[0]) && "foreignobject" !== O(r) && r.toString().match(/SVG/) ? "svg" : "html"), r = "html" !== s ? yr(J(s, yr("<div>").append(t).html())) : n ? ei.clone.call(t) : t, o)
                        for (var u in o) r.data("$" + u + "Controller", o[u].instance);
                    return V.$$addScopeInfo(r, e), n && n(r, e), a && a(e, r, r, i), r
                }
            }

            function D(t, e, r, i, o, a) {
                function s(t, r, i, o) {
                    var a, s, u, c, l, f, p;
                    if (h)
                        for (p = Array(r.length), c = 0; c < $.length; c += 3) a = $[c], p[a] = r[a];
                    else p = r;
                    for (c = 0, l = $.length; l > c;) s = p[$[c++]], r = $[c++], a = $[c++], r ? (r.scope ? (u = t.$new(), V.$$addScopeInfo(yr(s), u)) : u = t, f = r.transcludeOnThisElement ? j(t, r.transclude, o, r.elementTranscludeOnThisElement) : !r.templateOnThisElement && o ? o : !o && e ? j(t, e) : null, r(a, u, s, i, f)) : a && a(t, s.childNodes, n, o)
                }
                for (var u, c, l, f, h, $ = [], p = 0; p < t.length; p++) u = new re, c = P(t[p], [], u, 0 === p ? i : n, o), (a = c.length ? U(c, t[p], u, e, r, null, [], [], a) : null) && a.scope && V.$$addScopeClass(u.$$element), u = a && a.terminal || !(l = t[p].childNodes) || !l.length ? null : D(l, a ? (a.transcludeOnThisElement || !a.templateOnThisElement) && a.transclude : e), (a || u) && ($.push(p, a, u), f = !0, h = h || a), a = null;
                return f ? s : null
            }

            function j(t, e, n) {
                return function(r, i, o, a, s) {
                    return r || (r = t.$new(!1, s), r.$$transcluded = !0), e(r, i, {
                        parentBoundTranscludeFn: n,
                        transcludeControllers: o,
                        futureParentElement: a
                    })
                }
            }

            function P(t, e, n, r, i) {
                var o, a = n.$attr;
                switch (t.nodeType) {
                    case Hr:
                        H(e, Fe(O(t)), "E", r, i);
                        for (var s, l, f, h = t.attributes, $ = 0, p = h && h.length; p > $; $++) {
                            var d = !1,
                                v = !1;
                            s = h[$], o = s.name, l = Pr(s.value), s = Fe(o), (f = se.test(s)) && (o = o.replace($i, "").substr(8).replace(/_(.)/g, function(t, e) {
                                return e.toUpperCase()
                            }));
                            var m = s.replace(/(Start|End)$/, "");
                            _(m) && s === m + "Start" && (d = o, v = o.substr(0, o.length - 5) + "end", o = o.substr(0, o.length - 6)), s = Fe(o.toLowerCase()), a[s] = o, (f || !n.hasOwnProperty(s)) && (n[s] = l, Ce(t, s) && (n[s] = !0)), X(t, e, l, s, f), H(e, s, "A", r, i, d, v)
                        }
                        if (t = t.className, g(t) && "" !== t)
                            for (; o = c.exec(t);) s = Fe(o[2]), H(e, s, "C", r, i) && (n[s] = Pr(o[3])), t = t.substr(o.index + o[0].length);
                        break;
                    case _r:
                        W(e, t.nodeValue);
                        break;
                    case 8:
                        try {
                            (o = u.exec(t.nodeValue)) && (s = Fe(o[1]), H(e, s, "M", r, i) && (n[s] = Pr(o[2])))
                        } catch (y) {}
                }
                return e.sort(z), e
            }

            function R(t, e, n) {
                var r = [],
                    i = 0;
                if (e && t.hasAttribute && t.hasAttribute(e)) {
                    do {
                        if (!t) throw hi("uterdir", e, n);
                        t.nodeType == Hr && (t.hasAttribute(e) && i++, t.hasAttribute(n) && i--), r.push(t), t = t.nextSibling
                    } while (i > 0)
                } else r.push(t);
                return yr(r)
            }

            function q(t, e, n) {
                return function(r, i, o, a, s) {
                    return i = R(i[0], e, n), t(r, i, o, a, s)
                }
            }

            function U(t, a, s, u, c, l, f, h, $) {
                function y(t, e, n, r) {
                    t && (n && (t = q(t, n, r)), t.require = A.require, t.directiveName = E, (j === A || A.$$isolateScope) && (t = te(t, {
                        isolateScope: !0
                    })), f.push(t)), e && (n && (e = q(e, n, r)), e.require = A.require, e.directiveName = E, (j === A || A.$$isolateScope) && (e = te(e, {
                        isolateScope: !0
                    })), h.push(e))
                }

                function w(t, e, n, r) {
                    var i, a, s = "data",
                        u = !1,
                        c = n;
                    if (g(e)) {
                        if (a = e.match(d), e = e.substring(a[0].length), a[3] && (a[1] ? a[3] = null : a[1] = a[3]), "^" === a[1] ? s = "inheritedData" : "^^" === a[1] && (s = "inheritedData", c = n.parent()), "?" === a[2] && (u = !0), i = null, r && "data" === s && (i = r[e]) && (i = i.instance), i = i || c[s]("$" + e + "Controller"), !i && !u) throw hi("ctreq", e, t);
                        return i || null
                    }
                    return jr(e) && (i = [], o(e, function(e) {
                        i.push(w(t, e, n, r))
                    })), i
                }

                function x(t, e, i, u, c) {
                    function l(t, e, r) {
                        var i;
                        return C(t) || (r = e, e = t, t = n), W && (i = y), r || (r = W ? x.parent() : x), c(t, e, i, r, E)
                    }
                    var $, d, m, g, y, b, x, S;
                    if (a === i ? (S = s, x = s.$$element) : (x = yr(i), S = new re(x, s)), j && (g = e.$new(!0)), c && (b = l, b.$$boundTransclude = c), D && (k = {}, y = {}, o(D, function(t) {
                            var n = {
                                $scope: t === j || t.$$isolateScope ? g : e,
                                $element: x,
                                $attrs: S,
                                $transclude: b
                            };
                            m = t.controller, "@" == m && (m = S[t.name]), n = v(m, n, !0, t.controllerAs), y[t.name] = n, W || x.data("$" + t.name + "Controller", n.instance), k[t.name] = n
                        })), j) {
                        V.$$addScopeInfo(x, g, !0, !(U && (U === j || U === j.$$originalDirective))), V.$$addScopeClass(x, !0), u = k && k[j.name];
                        var A = g;
                        u && u.identifier && !0 === j.bindToController && (A = u.instance), o(g.$$isolateBindings = j.$$isolateBindings, function(t, n) {
                            var i, o, a, s, u = t.attrName,
                                c = t.optional;
                            switch (t.mode) {
                                case "@":
                                    S.$observe(u, function(t) {
                                        A[n] = t
                                    }), S.$$observers[u].$$scope = e, S[u] && (A[n] = r(S[u])(e));
                                    break;
                                case "=":
                                    if (c && !S[u]) break;
                                    o = p(S[u]), s = o.literal ? N : function(t, e) {
                                        return t === e || t !== t && e !== e
                                    }, a = o.assign || function() {
                                        throw i = A[n] = o(e), hi("nonassign", S[u], j.name)
                                    }, i = A[n] = o(e), c = function(t) {
                                        return s(t, A[n]) || (s(t, i) ? a(e, t = A[n]) : A[n] = t), i = t
                                    }, c.$stateful = !0, c = t.collection ? e.$watchCollection(S[u], c) : e.$watch(p(S[u], c), null, o.literal), g.$on("$destroy", c);
                                    break;
                                case "&":
                                    o = p(S[u]), A[n] = function(t) {
                                        return o(e, t)
                                    }
                            }
                        })
                    }
                    for (k && (o(k, function(t) {
                            t()
                        }), k = null), u = 0, $ = f.length; $ > u; u++) d = f[u], ee(d, d.isolateScope ? g : e, x, S, d.require && w(d.directiveName, d.require, x, y), b);
                    var E = e;
                    for (j && (j.template || null === j.templateUrl) && (E = g), t && t(E, i.childNodes, n, c), u = h.length - 1; u >= 0; u--) d = h[u], ee(d, d.isolateScope ? g : e, x, S, d.require && w(d.directiveName, d.require, x, y), b)
                }
                $ = $ || {};
                for (var S, k, A, E, O, T, M = -Number.MAX_VALUE, D = $.controllerDirectives, j = $.newIsolateScopeDirective, U = $.templateDirective, H = $.nonTlbTranscludeDirective, _ = !1, z = !1, W = $.hasElementTranscludeDirective, Z = s.$$element = yr(a), Y = u, K = 0, X = t.length; X > K; K++) {
                    A = t[K];
                    var ne = A.$$start,
                        ie = A.$$end;
                    if (ne && (Z = R(a, ne, ie)), O = n, M > A.priority) break;
                    if ((O = A.scope) && (A.templateUrl || (m(O) ? (G("new/isolated scope", j || S, A, Z), j = A) : G("new/isolated scope", j, A, Z)), S = S || A), E = A.name, !A.templateUrl && A.controller && (O = A.controller, D = D || {}, G("'" + E + "' controller", D[E], A, Z), D[E] = A), (O = A.transclude) && (_ = !0, A.$$tlb || (G("transclusion", H, A, Z), H = A), "element" == O ? (W = !0, M = A.priority, O = Z, Z = s.$$element = yr(e.createComment(" " + E + ": " + s[E] + " ")), a = Z[0], Q(c, Ar.call(O, 0), a), Y = V(O, u, M, l && l.name, {
                            nonTlbTranscludeDirective: H
                        })) : (O = yr(ce(a)).contents(), Z.empty(), Y = V(O, u))), A.template)
                        if (z = !0, G("template", U, A, Z), U = A, O = b(A.template) ? A.template(Z, s) : A.template, O = ae(O), A.replace) {
                            if (l = A, O = Kr.test(O) ? _e(J(A.templateNamespace, Pr(O))) : [], a = O[0], 1 != O.length || a.nodeType !== Hr) throw hi("tplrt", E, "");
                            Q(c, Z, a), X = {
                                $attr: {}
                            }, O = P(a, [], X);
                            var oe = t.splice(K + 1, t.length - (K + 1));
                            j && F(O), t = t.concat(O).concat(oe), L(s, X), X = t.length
                        } else Z.html(O);
                    if (A.templateUrl) z = !0, G("template", U, A, Z), U = A, A.replace && (l = A), x = B(t.splice(K, t.length - K), Z, s, c, _ && Y, f, h, {
                        controllerDirectives: D,
                        newIsolateScopeDirective: j,
                        templateDirective: U,
                        nonTlbTranscludeDirective: H
                    }), X = t.length;
                    else if (A.compile) try {
                        T = A.compile(Z, s, Y), b(T) ? y(null, T, ne, ie) : T && y(T.pre, T.post, ne, ie)
                    } catch (se) {
                        i(se, I(Z))
                    }
                    A.terminal && (x.terminal = !0, M = Math.max(M, A.priority))
                }
                return x.scope = S && !0 === S.scope, x.transcludeOnThisElement = _, x.elementTranscludeOnThisElement = W, x.templateOnThisElement = z, x.transclude = Y, $.hasElementTranscludeDirective = W, x
            }

            function F(t) {
                for (var e = 0, n = t.length; n > e; e++) {
                    var r, i = e;
                    r = l(Object.create(t[e]), {
                        $$isolateScope: !0
                    }), t[i] = r
                }
            }

            function H(e, r, o, s, u, c, f) {
                if (r === u) return null;
                if (u = null, a.hasOwnProperty(r)) {
                    var h;
                    r = t.get(r + "Directive");
                    for (var $ = 0, p = r.length; p > $; $++) try {
                        if (h = r[$], (s === n || s > h.priority) && -1 != h.restrict.indexOf(o)) {
                            if (c) {
                                var d = {
                                    $$start: c,
                                    $$end: f
                                };
                                h = l(Object.create(h), d)
                            }
                            e.push(h), u = h
                        }
                    } catch (v) {
                        i(v)
                    }
                }
                return u
            }

            function _(e) {
                if (a.hasOwnProperty(e))
                    for (var n = t.get(e + "Directive"), r = 0, i = n.length; i > r; r++)
                        if (e = n[r], e.multiElement) return !0;
                return !1
            }

            function L(t, e) {
                var n = e.$attr,
                    r = t.$attr,
                    i = t.$$element;
                o(t, function(r, i) {
                    "$" != i.charAt(0) && (e[i] && e[i] !== r && (r += ("style" === i ? ";" : " ") + e[i]), t.$set(i, r, !0, n[i]))
                }), o(e, function(e, o) {
                    "class" == o ? (M(i, e), t["class"] = (t["class"] ? t["class"] + " " : "") + e) : "style" == o ? (i.attr("style", i.attr("style") + ";" + e), t.style = (t.style ? t.style + ";" : "") + e) : "$" == o.charAt(0) || t.hasOwnProperty(o) || (t[o] = e, r[o] = n[o])
                })
            }

            function B(t, e, n, r, i, a, u, c) {
                var f, h, $ = [],
                    p = e[0],
                    d = t.shift(),
                    v = l({}, d, {
                        templateUrl: null,
                        transclude: null,
                        replace: null,
                        $$originalDirective: d
                    }),
                    g = b(d.templateUrl) ? d.templateUrl(e, n) : d.templateUrl,
                    y = d.templateNamespace;
                return e.empty(), s(k.getTrustedResourceUrl(g)).then(function(s) {
                        var l, w;
                        if (s = ae(s), d.replace) {
                            if (s = Kr.test(s) ? _e(J(y, Pr(s))) : [], l = s[0], 1 != s.length || l.nodeType !== Hr) throw hi("tplrt", d.name, g);
                            s = {
                                $attr: {}
                            }, Q(r, e, l);
                            var b = P(l, [], s);
                            m(d.scope) && F(b), t = b.concat(t), L(n, s)
                        } else l = p, e.html(s);
                        for (t.unshift(v), f = U(t, l, n, i, e, d, a, u, c), o(r, function(t, n) {
                                t == l && (r[n] = e[0])
                            }), h = D(e[0].childNodes, i); $.length;) {
                            s = $.shift(), w = $.shift();
                            var x = $.shift(),
                                S = $.shift(),
                                b = e[0];
                            if (!s.$$destroyed) {
                                if (w !== p) {
                                    var C = w.className;
                                    c.hasElementTranscludeDirective && d.replace || (b = ce(l)), Q(x, yr(w), b), M(yr(b), C)
                                }
                                w = f.transcludeOnThisElement ? j(s, f.transclude, S) : S, f(h, s, b, r, w)
                            }
                        }
                        $ = null
                    }),
                    function(t, e, n, r, i) {
                        t = i, e.$$destroyed || ($ ? $.push(e, n, r, t) : (f.transcludeOnThisElement && (t = j(e, f.transclude, i)), f(h, e, n, r, t)))
                    }
            }

            function z(t, e) {
                var n = e.priority - t.priority;
                return 0 !== n ? n : t.name !== e.name ? t.name < e.name ? -1 : 1 : t.index - e.index
            }

            function G(t, e, n, r) {
                if (e) throw hi("multidir", e.name, n.name, t, I(r))
            }

            function W(t, e) {
                var n = r(e, !0);
                n && t.push({
                    priority: 0,
                    compile: function(t) {
                        t = t.parent();
                        var e = !!t.length;
                        return e && V.$$addBindingClass(t),
                            function(t, r) {
                                var i = r.parent();
                                e || V.$$addBindingClass(i), V.$$addBindingInfo(i, n.expressions), t.$watch(n, function(t) {
                                    r[0].nodeValue = t
                                })
                            }
                    }
                })
            }

            function J(t, n) {
                switch (t = Sr(t || "html")) {
                    case "svg":
                    case "math":
                        var r = e.createElement("div");
                        return r.innerHTML = "<" + t + ">" + n + "</" + t + ">", r.childNodes[0].childNodes;
                    default:
                        return n
                }
            }

            function Y(t, e) {
                if ("srcdoc" == e) return k.HTML;
                var n = O(t);
                return "xlinkHref" == e || "form" == n && "action" == e || "img" != n && ("src" == e || "ngSrc" == e) ? k.RESOURCE_URL : void 0
            }

            function X(t, e, n, i, o) {
                var a = Y(t, i);
                o = f[i] || o;
                var s = r(n, !0, a, o);
                if (s) {
                    if ("multiple" === i && "select" === O(t)) throw hi("selmulti", I(t));
                    e.push({
                        priority: 100,
                        compile: function() {
                            return {
                                pre: function(t, e, u) {
                                    if (e = u.$$observers || (u.$$observers = {}), y.test(i)) throw hi("nodomevents");
                                    var c = u[i];
                                    c !== n && (s = c && r(c, !0, a, o), n = c), s && (u[i] = s(t), (e[i] || (e[i] = [])).$$inter = !0, (u.$$observers && u.$$observers[i].$$scope || t).$watch(s, function(t, e) {
                                        "class" === i && t != e ? u.$updateClass(t, e) : u.$set(i, t)
                                    }))
                                }
                            }
                        }
                    })
                }
            }

            function Q(t, n, r) {
                var i, o, a = n[0],
                    s = n.length,
                    u = a.parentNode;
                if (t)
                    for (i = 0, o = t.length; o > i; i++)
                        if (t[i] == a) {
                            t[i++] = r, o = i + s - 1;
                            for (var c = t.length; c > i; i++, o++) c > o ? t[i] = t[o] : delete t[i];
                            t.length -= s - 1, t.context === a && (t.context = r);
                            break
                        }
                for (u && u.replaceChild(r, a), t = e.createDocumentFragment(), t.appendChild(a), yr(r).data(yr(a).data()), wr ? (Dr = !0, wr.cleanData([a])) : delete yr.cache[a[yr.expando]], a = 1, s = n.length; s > a; a++) u = n[a], yr(u).remove(), t.appendChild(u), delete n[a];
                n[0] = r, n.length = 1
            }

            function te(t, e) {
                return l(function() {
                    return t.apply(null, arguments)
                }, t, e)
            }

            function ee(t, e, n, r, o, a) {
                try {
                    t(e, n, r, o, a)
                } catch (s) {
                    i(s, I(n))
                }
            }
            var re = function(t, e) {
                if (e) {
                    var n, r, i, o = Object.keys(e);
                    for (n = 0, r = o.length; r > n; n++) i = o[n], this[i] = e[i]
                } else this.$attr = {};
                this.$$element = t
            };
            re.prototype = {
                $normalize: Fe,
                $addClass: function(t) {
                    t && 0 < t.length && A.addClass(this.$$element, t)
                },
                $removeClass: function(t) {
                    t && 0 < t.length && A.removeClass(this.$$element, t)
                },
                $updateClass: function(t, e) {
                    var n = He(t, e);
                    n && n.length && A.addClass(this.$$element, n), (n = He(e, t)) && n.length && A.removeClass(this.$$element, n)
                },
                $set: function(t, e, r, a) {
                    var s = this.$$element[0],
                        u = Ce(s, t),
                        c = ke(s, t),
                        s = t;
                    if (u ? (this.$$element.prop(t, e), a = u) : c && (this[c] = e, s = c), this[t] = e, a ? this.$attr[t] = a : (a = this.$attr[t]) || (this.$attr[t] = a = Z(t, "-")), u = O(this.$$element), "a" === u && "href" === t || "img" === u && "src" === t) this[t] = e = E(e, "src" === t);
                    else if ("img" === u && "srcset" === t) {
                        for (var u = "", c = Pr(e), l = /(\s+\d+x\s*,|\s+\d+w\s*,|\s+,|,\s+)/, l = /\s/.test(c) ? l : /(,)/, c = c.split(l), l = Math.floor(c.length / 2), f = 0; l > f; f++) var h = 2 * f,
                            u = u + E(Pr(c[h]), !0),
                            u = u + (" " + Pr(c[h + 1]));
                        c = Pr(c[2 * f]).split(/\s/), u += E(Pr(c[0]), !0), 2 === c.length && (u += " " + Pr(c[1])), this[t] = e = u
                    }!1 !== r && (null === e || e === n ? this.$$element.removeAttr(a) : this.$$element.attr(a, e)), (t = this.$$observers) && o(t[s], function(t) {
                        try {
                            t(e)
                        } catch (n) {
                            i(n)
                        }
                    })
                },
                $observe: function(t, e) {
                    var n = this,
                        r = n.$$observers || (n.$$observers = ne()),
                        i = r[t] || (r[t] = []);
                    return i.push(e), x.$evalAsync(function() {
                            !i.$$inter && n.hasOwnProperty(t) && e(n[t])
                        }),
                        function() {
                            T(i, e)
                        }
                }
            };
            var ie = r.startSymbol(),
                oe = r.endSymbol(),
                ae = "{{" == ie || "}}" == oe ? $ : function(t) {
                    return t.replace(/\{\{/g, ie).replace(/}}/g, oe)
                },
                se = /^ngAttr[A-Z]/;
            return V.$$addBindingInfo = w ? function(t, e) {
                var n = t.data("$binding") || [];
                jr(e) ? n = n.concat(e) : n.push(e), t.data("$binding", n)
            } : h, V.$$addBindingClass = w ? function(t) {
                M(t, "ng-binding")
            } : h, V.$$addScopeInfo = w ? function(t, e, n, r) {
                t.data(n ? r ? "$isolateScopeNoTemplate" : "$isolateScope" : "$scope", e)
            } : h, V.$$addScopeClass = w ? function(t, e) {
                M(t, e ? "ng-isolate-scope" : "ng-scope")
            } : h, V
        }]
    }

    function Fe(t) {
        return oe(t.replace($i, ""))
    }

    function He(t, e) {
        var n = "",
            r = t.split(/\s+/),
            i = e.split(/\s+/),
            o = 0;
        t: for (; o < r.length; o++) {
            for (var a = r[o], s = 0; s < i.length; s++)
                if (a == i[s]) continue t;
            n += (0 < n.length ? " " : "") + a
        }
        return n
    }

    function _e(t) {
        t = yr(t);
        var e = t.length;
        if (1 >= e) return t;
        for (; e--;) 8 === t[e].nodeType && Er.call(t, e, 1);
        return t
    }

    function Le() {
        var t = {},
            e = !1,
            i = /^(\S+)(\s+as\s+(\w+))?$/;
        this.register = function(e, n) {
            Q(e, "controller"), m(e) ? l(t, e) : t[e] = n
        }, this.allowGlobals = function() {
            e = !0
        }, this.$get = ["$injector", "$window", function(o, a) {
            function s(t, e, n, i) {
                if (!t || !m(t.$scope)) throw r("$controller")("noscp", i, e);
                t.$scope[e] = n
            }
            return function(r, u, c, f) {
                var h, $, p;
                return c = !0 === c, f && g(f) && (p = f), g(r) && (f = r.match(i), $ = f[1], p = p || f[3], r = t.hasOwnProperty($) ? t[$] : te(u.$scope, $, !0) || (e ? te(a, $, !0) : n), X(r, $, !0)), c ? (c = (jr(r) ? r[r.length - 1] : r).prototype, h = Object.create(c), p && s(u, p, h, $ || r.name), l(function() {
                    return o.invoke(r, h, u, $), h
                }, {
                    instance: h,
                    identifier: p
                })) : (h = o.instantiate(r, u, $), p && s(u, p, h, $ || r.name), h)
            }
        }]
    }

    function Be() {
        this.$get = ["$window", function(t) {
            return yr(t.document)
        }]
    }

    function ze() {
        this.$get = ["$log", function(t) {
            return function() {
                t.error.apply(t, arguments)
            }
        }]
    }

    function Ge(t, e) {
        if (g(t)) {
            var n = t.replace(gi, "").trim();
            if (n) {
                var r = e("Content-Type");
                (r = r && 0 === r.indexOf(pi)) || (r = (r = n.match(vi)) && mi[r[0]].test(n)), r && (t = q(n))
            }
        }
        return t
    }

    function We(t) {
        var e, n, r, i = ne();
        return t ? (o(t.split("\n"), function(t) {
            r = t.indexOf(":"), e = Sr(Pr(t.substr(0, r))), n = Pr(t.substr(r + 1)), e && (i[e] = i[e] ? i[e] + ", " + n : n)
        }), i) : i
    }

    function Je(t) {
        var e = m(t) ? t : n;
        return function(n) {
            return e || (e = We(t)), n ? (n = e[Sr(n)], void 0 === n && (n = null), n) : e
        }
    }

    function Ze(t, e, n, r) {
        return b(r) ? r(t, e, n) : (o(r, function(r) {
            t = r(t, e, n)
        }), t)
    }

    function Ye() {
        var t = this.defaults = {
                transformResponse: [Ge],
                transformRequest: [function(t) {
                    return m(t) && "[object File]" !== Tr.call(t) && "[object Blob]" !== Tr.call(t) && "[object FormData]" !== Tr.call(t) ? R(t) : t
                }],
                headers: {
                    common: {
                        Accept: "application/json, text/plain, */*"
                    },
                    post: V(di),
                    put: V(di),
                    patch: V(di)
                },
                xsrfCookieName: "XSRF-TOKEN",
                xsrfHeaderName: "X-XSRF-TOKEN"
            },
            e = !1;
        this.useApplyAsync = function(t) {
            return v(t) ? (e = !!t, this) : e
        };
        var i = this.interceptors = [];
        this.$get = ["$httpBackend", "$browser", "$cacheFactory", "$rootScope", "$q", "$injector", function(s, u, c, f, h, $) {
            function p(e) {
                function i(t) {
                    var e = l({}, t);
                    return e.data = t.data ? Ze(t.data, t.headers, t.status, s.transformResponse) : t.data, t = t.status, t >= 200 && 300 > t ? e : h.reject(e)
                }

                function a(t) {
                    var e, n = {};
                    return o(t, function(t, r) {
                        b(t) ? (e = t(), null != e && (n[r] = e)) : n[r] = t
                    }), n
                }
                if (!Vr.isObject(e)) throw r("$http")("badreq", e);
                var s = l({
                    method: "get",
                    transformRequest: t.transformRequest,
                    transformResponse: t.transformResponse
                }, e);
                s.headers = function(e) {
                    var n, r, i = t.headers,
                        o = l({}, e.headers),
                        i = l({}, i.common, i[Sr(e.method)]);
                    t: for (n in i) {
                        e = Sr(n);
                        for (r in o)
                            if (Sr(r) === e) continue t;
                        o[n] = i[n]
                    }
                    return a(o)
                }(e), s.method = kr(s.method);
                var u = [function(e) {
                        var r = e.headers,
                            a = Ze(e.data, Je(r), n, e.transformRequest);
                        return d(a) && o(r, function(t, e) {
                            "content-type" === Sr(e) && delete r[e]
                        }), d(e.withCredentials) && !d(t.withCredentials) && (e.withCredentials = t.withCredentials), y(e, a).then(i, i)
                    }, n],
                    c = h.when(s);
                for (o(C, function(t) {
                        (t.request || t.requestError) && u.unshift(t.request, t.requestError), (t.response || t.responseError) && u.push(t.response, t.responseError)
                    }); u.length;) {
                    e = u.shift();
                    var f = u.shift(),
                        c = c.then(e, f)
                }
                return c.success = function(t) {
                    return c.then(function(e) {
                        t(e.data, e.status, e.headers, s)
                    }), c
                }, c.error = function(t) {
                    return c.then(null, function(e) {
                        t(e.data, e.status, e.headers, s)
                    }), c
                }, c
            }

            function y(r, i) {
                function o(t, n, r, i) {
                    function o() {
                        a(n, t, r, i)
                    }
                    $ && (t >= 200 && 300 > t ? $.put(k, [t, n, We(r), i]) : $.remove(k)), e ? f.$applyAsync(o) : (o(), f.$$phase || f.$apply())
                }

                function a(t, e, n, i) {
                    e = Math.max(e, 0), (e >= 200 && 300 > e ? y.resolve : y.reject)({
                        data: t,
                        status: e,
                        headers: Je(n),
                        config: r,
                        statusText: i
                    })
                }

                function c(t) {
                    a(t.data, t.status, V(t.headers()), t.statusText)
                }

                function l() {
                    var t = p.pendingRequests.indexOf(r); - 1 !== t && p.pendingRequests.splice(t, 1)
                }
                var $, g, y = h.defer(),
                    w = y.promise,
                    C = r.headers,
                    k = x(r.url, r.params);
                return p.pendingRequests.push(r), w.then(l, l), !r.cache && !t.cache || !1 === r.cache || "GET" !== r.method && "JSONP" !== r.method || ($ = m(r.cache) ? r.cache : m(t.cache) ? t.cache : S), $ && (g = $.get(k), v(g) ? g && b(g.then) ? g.then(c, c) : jr(g) ? a(g[1], g[0], V(g[2]), g[3]) : a(g, 200, {}, "OK") : $.put(k, w)), d(g) && ((g = Ln(r.url) ? u.cookies()[r.xsrfCookieName || t.xsrfCookieName] : n) && (C[r.xsrfHeaderName || t.xsrfHeaderName] = g), s(r.method, k, i, o, C, r.timeout, r.withCredentials, r.responseType)), w
            }

            function x(t, e) {
                if (!e) return t;
                var n = [];
                return a(e, function(t, e) {
                    null === t || d(t) || (jr(t) || (t = [t]), o(t, function(t) {
                        m(t) && (t = w(t) ? t.toISOString() : R(t)), n.push(L(e) + "=" + L(t))
                    }))
                }), 0 < n.length && (t += (-1 == t.indexOf("?") ? "?" : "&") + n.join("&")), t
            }
            var S = c("$http"),
                C = [];
            return o(i, function(t) {
                    C.unshift(g(t) ? $.get(t) : $.invoke(t))
                }), p.pendingRequests = [],
                function() {
                    o(arguments, function(t) {
                        p[t] = function(e, n) {
                            return p(l(n || {}, {
                                method: t,
                                url: e
                            }))
                        }
                    })
                }("get", "delete", "head", "jsonp"),
                function() {
                    o(arguments, function(t) {
                        p[t] = function(e, n, r) {
                            return p(l(r || {}, {
                                method: t,
                                url: e,
                                data: n
                            }))
                        }
                    })
                }("post", "put", "patch"), p.defaults = t, p
        }]
    }

    function Ke() {
        return new t.XMLHttpRequest
    }

    function Xe() {
        this.$get = ["$browser", "$window", "$document", function(t, e, n) {
            return Qe(t, Ke, t.defer, e.angular.callbacks, n[0])
        }]
    }

    function Qe(t, e, r, i, a) {
        function s(t, e, n) {
            var r = a.createElement("script"),
                o = null;
            return r.type = "text/javascript", r.src = t, r.async = !0, o = function(t) {
                r.removeEventListener("load", o, !1), r.removeEventListener("error", o, !1), a.body.removeChild(r), r = null;
                var s = -1,
                    u = "unknown";
                t && ("load" !== t.type || i[e].called || (t = {
                    type: "error"
                }), u = t.type, s = "error" === t.type ? 404 : 200), n && n(s, u)
            }, r.addEventListener("load", o, !1), r.addEventListener("error", o, !1), a.body.appendChild(r), o
        }
        return function(a, u, c, l, f, $, p, d) {
            function m() {
                w && w(), x && x.abort()
            }

            function g(e, i, o, a, s) {
                C !== n && r.cancel(C), w = x = null, e(i, o, a, s), t.$$completeOutstandingRequest(h)
            }
            if (t.$$incOutstandingRequestCount(), u = u || t.url(), "jsonp" == Sr(a)) {
                var y = "_" + (i.counter++).toString(36);
                i[y] = function(t) {
                    i[y].data = t, i[y].called = !0
                };
                var w = s(u.replace("JSON_CALLBACK", "angular.callbacks." + y), y, function(t, e) {
                    g(l, t, i[y].data, "", e), i[y] = h
                })
            } else {
                var x = e();
                if (x.open(a, u, !0), o(f, function(t, e) {
                        v(t) && x.setRequestHeader(e, t)
                    }), x.onload = function() {
                        var t = x.statusText || "",
                            e = "response" in x ? x.response : x.responseText,
                            n = 1223 === x.status ? 204 : x.status;
                        0 === n && (n = e ? 200 : "file" == _n(u).protocol ? 404 : 0), g(l, n, e, x.getAllResponseHeaders(), t)
                    }, a = function() {
                        g(l, -1, null, null, "")
                    }, x.onerror = a, x.onabort = a, p && (x.withCredentials = !0), d) try {
                    x.responseType = d
                } catch (S) {
                    if ("json" !== d) throw S
                }
                x.send(c || null)
            }
            if ($ > 0) var C = r(m, $);
            else $ && b($.then) && $.then(m)
        }
    }

    function tn() {
        var t = "{{",
            e = "}}";
        this.startSymbol = function(e) {
            return e ? (t = e, this) : t
        }, this.endSymbol = function(t) {
            return t ? (e = t, this) : e
        }, this.$get = ["$parse", "$exceptionHandler", "$sce", function(n, r, i) {
            function o(t) {
                return "\\\\\\" + t
            }

            function a(o, a, h, $) {
                function p(n) {
                    return n.replace(c, t).replace(f, e)
                }

                function m(t) {
                    try {
                        var e = t;
                        t = h ? i.getTrusted(h, e) : i.valueOf(e);
                        var n;
                        if ($ && !v(t)) n = t;
                        else if (null == t) n = "";
                        else {
                            switch (typeof t) {
                                case "string":
                                    break;
                                case "number":
                                    t = "" + t;
                                    break;
                                default:
                                    t = R(t)
                            }
                            n = t
                        }
                        return n
                    } catch (a) {
                        n = yi("interr", o, a.toString()), r(n)
                    }
                }
                $ = !!$;
                for (var g, y, w = 0, x = [], S = [], C = o.length, k = [], A = []; C > w;) {
                    if (-1 == (g = o.indexOf(t, w)) || -1 == (y = o.indexOf(e, g + s))) {
                        w !== C && k.push(p(o.substring(w)));
                        break
                    }
                    w !== g && k.push(p(o.substring(w, g))), w = o.substring(g + s, y), x.push(w), S.push(n(w, m)), w = y + u, A.push(k.length), k.push("")
                }
                if (h && 1 < k.length) throw yi("noconcat", o);
                if (!a || x.length) {
                    var E = function(t) {
                        for (var e = 0, n = x.length; n > e; e++) {
                            if ($ && d(t[e])) return;
                            k[A[e]] = t[e]
                        }
                        return k.join("")
                    };
                    return l(function(t) {
                        var e = 0,
                            n = x.length,
                            i = Array(n);
                        try {
                            for (; n > e; e++) i[e] = S[e](t);
                            return E(i)
                        } catch (a) {
                            t = yi("interr", o, a.toString()), r(t)
                        }
                    }, {
                        exp: o,
                        expressions: x,
                        $$watchDelegate: function(t, e, n) {
                            var r;
                            return t.$watchGroup(S, function(n, i) {
                                var o = E(n);
                                b(e) && e.call(this, o, n !== i ? r : o, t), r = o
                            }, n)
                        }
                    })
                }
            }
            var s = t.length,
                u = e.length,
                c = new RegExp(t.replace(/./g, o), "g"),
                f = new RegExp(e.replace(/./g, o), "g");
            return a.startSymbol = function() {
                return t
            }, a.endSymbol = function() {
                return e
            }, a
        }]
    }

    function en() {
        this.$get = ["$rootScope", "$window", "$q", "$$q", function(t, e, n, r) {
            function i(i, a, s, u) {
                var c = e.setInterval,
                    l = e.clearInterval,
                    f = 0,
                    h = v(u) && !u,
                    $ = (h ? r : n).defer(),
                    p = $.promise;
                return s = v(s) ? s : 0, p.then(null, null, i), p.$$intervalId = c(function() {
                    $.notify(f++), s > 0 && f >= s && ($.resolve(f), l(p.$$intervalId), delete o[p.$$intervalId]), h || t.$apply()
                }, a), o[p.$$intervalId] = $, p
            }
            var o = {};
            return i.cancel = function(t) {
                return t && t.$$intervalId in o ? (o[t.$$intervalId].reject("canceled"), e.clearInterval(t.$$intervalId), delete o[t.$$intervalId], !0) : !1
            }, i
        }]
    }

    function nn() {
        this.$get = function() {
            return {
                id: "en-us",
                NUMBER_FORMATS: {
                    DECIMAL_SEP: ".",
                    GROUP_SEP: ",",
                    PATTERNS: [{
                        minInt: 1,
                        minFrac: 0,
                        maxFrac: 3,
                        posPre: "",
                        posSuf: "",
                        negPre: "-",
                        negSuf: "",
                        gSize: 3,
                        lgSize: 3
                    }, {
                        minInt: 1,
                        minFrac: 2,
                        maxFrac: 2,
                        posPre: "¤",
                        posSuf: "",
                        negPre: "(¤",
                        negSuf: ")",
                        gSize: 3,
                        lgSize: 3
                    }],
                    CURRENCY_SYM: "$"
                },
                DATETIME_FORMATS: {
                    MONTH: "January February March April May June July August September October November December".split(" "),
                    SHORTMONTH: "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),
                    DAY: "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),
                    SHORTDAY: "Sun Mon Tue Wed Thu Fri Sat".split(" "),
                    AMPMS: ["AM", "PM"],
                    medium: "MMM d, y h:mm:ss a",
                    "short": "M/d/yy h:mm a",
                    fullDate: "EEEE, MMMM d, y",
                    longDate: "MMMM d, y",
                    mediumDate: "MMM d, y",
                    shortDate: "M/d/yy",
                    mediumTime: "h:mm:ss a",
                    shortTime: "h:mm a"
                },
                pluralCat: function(t) {
                    return 1 === t ? "one" : "other"
                }
            }
        }
    }

    function rn(t) {
        t = t.split("/");
        for (var e = t.length; e--;) t[e] = _(t[e]);
        return t.join("/")
    }

    function on(t, e) {
        var n = _n(t);
        e.$$protocol = n.protocol, e.$$host = n.hostname, e.$$port = f(n.port) || bi[n.protocol] || null
    }

    function an(t, e) {
        var n = "/" !== t.charAt(0);
        n && (t = "/" + t);
        var r = _n(t);
        e.$$path = decodeURIComponent(n && "/" === r.pathname.charAt(0) ? r.pathname.substring(1) : r.pathname), e.$$search = F(r.search), e.$$hash = decodeURIComponent(r.hash), e.$$path && "/" != e.$$path.charAt(0) && (e.$$path = "/" + e.$$path)
    }

    function sn(t, e) {
        return 0 === e.indexOf(t) ? e.substr(t.length) : void 0
    }

    function un(t) {
        var e = t.indexOf("#");
        return -1 == e ? t : t.substr(0, e)
    }

    function cn(t) {
        return t.replace(/(#.+)|#$/, "$1")
    }

    function ln(t) {
        return t.substr(0, un(t).lastIndexOf("/") + 1)
    }

    function fn(t, e) {
        this.$$html5 = !0, e = e || "";
        var r = ln(t);
        on(t, this), this.$$parse = function(t) {
            var e = sn(r, t);
            if (!g(e)) throw xi("ipthprfx", t, r);
            an(e, this), this.$$path || (this.$$path = "/"), this.$$compose()
        }, this.$$compose = function() {
            var t = H(this.$$search),
                e = this.$$hash ? "#" + _(this.$$hash) : "";
            this.$$url = rn(this.$$path) + (t ? "?" + t : "") + e, this.$$absUrl = r + this.$$url.substr(1)
        }, this.$$parseLinkUrl = function(i, o) {
            if (o && "#" === o[0]) return this.hash(o.slice(1)), !0;
            var a, s;
            return (a = sn(t, i)) !== n ? (s = a, s = (a = sn(e, a)) !== n ? r + (sn("/", a) || a) : t + s) : (a = sn(r, i)) !== n ? s = r + a : r == i + "/" && (s = r), s && this.$$parse(s), !!s
        }
    }

    function hn(t, e) {
        var n = ln(t);
        on(t, this), this.$$parse = function(r) {
            r = sn(t, r) || sn(n, r);
            var i;
            "#" === r.charAt(0) ? (i = sn(e, r), d(i) && (i = r)) : i = this.$$html5 ? r : "", an(i, this), r = this.$$path;
            var o = /^\/[A-Z]:(\/.*)/;
            0 === i.indexOf(t) && (i = i.replace(t, "")), o.exec(i) || (r = (i = o.exec(r)) ? i[1] : r), this.$$path = r, this.$$compose()
        }, this.$$compose = function() {
            var n = H(this.$$search),
                r = this.$$hash ? "#" + _(this.$$hash) : "";
            this.$$url = rn(this.$$path) + (n ? "?" + n : "") + r, this.$$absUrl = t + (this.$$url ? e + this.$$url : "")
        }, this.$$parseLinkUrl = function(e) {
            return un(t) == un(e) ? (this.$$parse(e), !0) : !1
        }
    }

    function $n(t, e) {
        this.$$html5 = !0, hn.apply(this, arguments);
        var n = ln(t);
        this.$$parseLinkUrl = function(r, i) {
            if (i && "#" === i[0]) return this.hash(i.slice(1)), !0;
            var o, a;
            return t == un(r) ? o = r : (a = sn(n, r)) ? o = t + e + a : n === r + "/" && (o = n), o && this.$$parse(o), !!o
        }, this.$$compose = function() {
            var n = H(this.$$search),
                r = this.$$hash ? "#" + _(this.$$hash) : "";
            this.$$url = rn(this.$$path) + (n ? "?" + n : "") + r, this.$$absUrl = t + e + this.$$url
        }
    }

    function pn(t) {
        return function() {
            return this[t]
        }
    }

    function dn(t, e) {
        return function(n) {
            return d(n) ? this[t] : (this[t] = e(n), this.$$compose(), this)
        }
    }

    function vn() {
        var t = "",
            e = {
                enabled: !1,
                requireBase: !0,
                rewriteLinks: !0
            };
        this.hashPrefix = function(e) {
            return v(e) ? (t = e, this) : t
        }, this.html5Mode = function(t) {
            return k(t) ? (e.enabled = t, this) : m(t) ? (k(t.enabled) && (e.enabled = t.enabled), k(t.requireBase) && (e.requireBase = t.requireBase), k(t.rewriteLinks) && (e.rewriteLinks = t.rewriteLinks), this) : e
        }, this.$get = ["$rootScope", "$browser", "$sniffer", "$rootElement", "$window", function(n, r, i, o, a) {
            function s(t, e, n) {
                var i = c.url(),
                    o = c.$$state;
                try {
                    r.url(t, e, n), c.$$state = r.state()
                } catch (a) {
                    throw c.url(i), c.$$state = o, a
                }
            }

            function u(t, e) {
                n.$broadcast("$locationChangeSuccess", c.absUrl(), t, c.$$state, e)
            }
            var c, l;
            l = r.baseHref();
            var f, h = r.url();
            if (e.enabled) {
                if (!l && e.requireBase) throw xi("nobase");
                f = h.substring(0, h.indexOf("/", h.indexOf("//") + 2)) + (l || "/"), l = i.history ? fn : $n
            } else f = un(h), l = hn;
            c = new l(f, "#" + t), c.$$parseLinkUrl(h, h), c.$$state = r.state();
            var $ = /^\s*(javascript|mailto):/i;
            o.on("click", function(t) {
                if (e.rewriteLinks && !t.ctrlKey && !t.metaKey && 2 != t.which) {
                    for (var i = yr(t.target);
                        "a" !== O(i[0]);)
                        if (i[0] === o[0] || !(i = i.parent())[0]) return;
                    var s = i.prop("href"),
                        u = i.attr("href") || i.attr("xlink:href");
                    m(s) && "[object SVGAnimatedString]" === s.toString() && (s = _n(s.animVal).href), $.test(s) || !s || i.attr("target") || t.isDefaultPrevented() || !c.$$parseLinkUrl(s, u) || (t.preventDefault(), c.absUrl() != r.url() && (n.$apply(), a.angular["ff-684208-preventDefault"] = !0))
                }
            }), c.absUrl() != h && r.url(c.absUrl(), !0);
            var p = !0;
            return r.onUrlChange(function(t, e) {
                n.$evalAsync(function() {
                    var r, i = c.absUrl(),
                        o = c.$$state;
                    c.$$parse(t), c.$$state = e, r = n.$broadcast("$locationChangeStart", t, i, e, o).defaultPrevented, c.absUrl() === t && (r ? (c.$$parse(i), c.$$state = o, s(i, !1, o)) : (p = !1, u(i, o)))
                }), n.$$phase || n.$digest()
            }), n.$watch(function() {
                var t = cn(r.url()),
                    e = cn(c.absUrl()),
                    o = r.state(),
                    a = c.$$replace,
                    l = t !== e || c.$$html5 && i.history && o !== c.$$state;
                (p || l) && (p = !1, n.$evalAsync(function() {
                    var e = c.absUrl(),
                        r = n.$broadcast("$locationChangeStart", e, t, c.$$state, o).defaultPrevented;
                    c.absUrl() === e && (r ? (c.$$parse(t), c.$$state = o) : (l && s(e, a, o === c.$$state ? null : c.$$state), u(t, o)))
                })), c.$$replace = !1
            }), c
        }]
    }

    function mn() {
        var t = !0,
            e = this;
        this.debugEnabled = function(e) {
            return v(e) ? (t = e, this) : t
        }, this.$get = ["$window", function(n) {
            function r(t) {
                return t instanceof Error && (t.stack ? t = t.message && -1 === t.stack.indexOf(t.message) ? "Error: " + t.message + "\n" + t.stack : t.stack : t.sourceURL && (t = t.message + "\n" + t.sourceURL + ":" + t.line)), t
            }

            function i(t) {
                var e = n.console || {},
                    i = e[t] || e.log || h;
                t = !1;
                try {
                    t = !!i.apply
                } catch (a) {}
                return t ? function() {
                    var t = [];
                    return o(arguments, function(e) {
                        t.push(r(e))
                    }), i.apply(e, t)
                } : function(t, e) {
                    i(t, null == e ? "" : e)
                }
            }
            return {
                log: i("log"),
                info: i("info"),
                warn: i("warn"),
                error: i("error"),
                debug: function() {
                    var n = i("debug");
                    return function() {
                        t && n.apply(e, arguments)
                    }
                }()
            }
        }]
    }

    function gn(t, e) {
        if ("__defineGetter__" === t || "__defineSetter__" === t || "__lookupGetter__" === t || "__lookupSetter__" === t || "__proto__" === t) throw Ci("isecfld", e);
        return t
    }

    function yn(t, e) {
        if (t) {
            if (t.constructor === t) throw Ci("isecfn", e);
            if (t.window === t) throw Ci("isecwindow", e);
            if (t.children && (t.nodeName || t.prop && t.attr && t.find)) throw Ci("isecdom", e);
            if (t === Object) throw Ci("isecobj", e)
        }
        return t
    }

    function wn(t) {
        return t.constant
    }

    function bn(t, e, n, r, i) {
        yn(t, i), yn(e, i), n = n.split(".");
        for (var o, a = 0; 1 < n.length; a++) {
            o = gn(n.shift(), i);
            var s = 0 === a && e && e[o] || t[o];
            s || (s = {}, t[o] = s), t = yn(s, i)
        }
        return o = gn(n.shift(), i), yn(t[o], i), t[o] = r
    }

    function xn(t) {
        return "constructor" == t
    }

    function Sn(t, e, r, i, o, a, s) {
        gn(t, a), gn(e, a), gn(r, a), gn(i, a), gn(o, a);
        var u = function(t) {
                return yn(t, a)
            },
            c = s || xn(t) ? u : $,
            l = s || xn(e) ? u : $,
            f = s || xn(r) ? u : $,
            h = s || xn(i) ? u : $,
            p = s || xn(o) ? u : $;
        return function(a, s) {
            var u = s && s.hasOwnProperty(t) ? s : a;
            return null == u ? u : (u = c(u[t]), e ? null == u ? n : (u = l(u[e]), r ? null == u ? n : (u = f(u[r]), i ? null == u ? n : (u = h(u[i]), o ? null == u ? n : u = p(u[o]) : u) : u) : u) : u)
        }
    }

    function Cn(t, e) {
        return function(n, r) {
            return t(n, r, yn, e)
        }
    }

    function kn(t, e, r) {
        var i = e.expensiveChecks,
            a = i ? ji : Di,
            s = a[t];
        if (s) return s;
        var u = t.split("."),
            c = u.length;
        if (e.csp) s = 6 > c ? Sn(u[0], u[1], u[2], u[3], u[4], r, i) : function(t, e) {
            var o, a = 0;
            do o = Sn(u[a++], u[a++], u[a++], u[a++], u[a++], r, i)(t, e), e = n, t = o; while (c > a);
            return o
        };
        else {
            var l = "";
            i && (l += "s = eso(s, fe);\nl = eso(l, fe);\n");
            var f = i;
            o(u, function(t, e) {
                gn(t, r);
                var n = (e ? "s" : '((l&&l.hasOwnProperty("' + t + '"))?l:s)') + "." + t;
                (i || xn(t)) && (n = "eso(" + n + ", fe)", f = !0), l += "if(s == null) return undefined;\ns=" + n + ";\n"
            }), l += "return s;", e = new Function("s", "l", "eso", "fe", l), e.toString = p(l), f && (e = Cn(e, r)), s = e
        }
        return s.sharedGetter = !0, s.assign = function(e, n, r) {
            return bn(e, r, t, n, t)
        }, a[t] = s
    }

    function An(t) {
        return b(t.valueOf) ? t.valueOf() : Pi.call(t)
    }

    function En() {
        var t = ne(),
            e = ne();
        this.$get = ["$filter", "$sniffer", function(n, r) {
            function i(t) {
                var e = t;
                return t.sharedGetter && (e = function(e, n) {
                    return t(e, n)
                }, e.literal = t.literal, e.constant = t.constant, e.assign = t.assign), e
            }

            function a(t, e) {
                for (var n = 0, r = t.length; r > n; n++) {
                    var i = t[n];
                    i.constant || (i.inputs ? a(i.inputs, e) : -1 === e.indexOf(i) && e.push(i))
                }
                return e
            }

            function s(t, e) {
                return null == t || null == e ? t === e : "object" == typeof t && (t = An(t), "object" == typeof t) ? !1 : t === e || t !== t && e !== e
            }

            function u(t, e, n, r) {
                var i, o = r.$$inputs || (r.$$inputs = a(r.inputs, []));
                if (1 === o.length) {
                    var u = s,
                        o = o[0];
                    return t.$watch(function(t) {
                        var e = o(t);
                        return s(e, u) || (i = r(t), u = e && An(e)), i
                    }, e, n)
                }
                for (var c = [], l = 0, f = o.length; f > l; l++) c[l] = s;
                return t.$watch(function(t) {
                    for (var e = !1, n = 0, a = o.length; a > n; n++) {
                        var u = o[n](t);
                        (e || (e = !s(u, c[n]))) && (c[n] = u && An(u))
                    }
                    return e && (i = r(t)), i
                }, e, n)
            }

            function c(t, e, n, r) {
                var i, o;
                return i = t.$watch(function(t) {
                    return r(t)
                }, function(t, n, r) {
                    o = t, b(e) && e.apply(this, arguments), v(t) && r.$$postDigest(function() {
                        v(o) && i()
                    })
                }, n)
            }

            function l(t, e, n, r) {
                function i(t) {
                    var e = !0;
                    return o(t, function(t) {
                        v(t) || (e = !1)
                    }), e
                }
                var a, s;
                return a = t.$watch(function(t) {
                    return r(t)
                }, function(t, n, r) {
                    s = t, b(e) && e.call(this, t, n, r), i(t) && r.$$postDigest(function() {
                        i(s) && a()
                    })
                }, n)
            }

            function f(t, e, n, r) {
                var i;
                return i = t.$watch(function(t) {
                    return r(t)
                }, function() {
                    b(e) && e.apply(this, arguments), i()
                }, n)
            }

            function $(t, e) {
                if (!e) return t;
                var n = t.$$watchDelegate,
                    n = n !== l && n !== c ? function(n, r) {
                        var i = t(n, r);
                        return e(i, n, r)
                    } : function(n, r) {
                        var i = t(n, r),
                            o = e(i, n, r);
                        return v(i) ? o : i
                    };
                return t.$$watchDelegate && t.$$watchDelegate !== u ? n.$$watchDelegate = t.$$watchDelegate : e.$stateful || (n.$$watchDelegate = u, n.inputs = [t]), n
            }
            var p = {
                    csp: r.csp,
                    expensiveChecks: !1
                },
                d = {
                    csp: r.csp,
                    expensiveChecks: !0
                };
            return function(r, o, a) {
                var s, v, m;
                switch (typeof r) {
                    case "string":
                        m = r = r.trim();
                        var g = a ? e : t;
                        return s = g[m], s || (":" === r.charAt(0) && ":" === r.charAt(1) && (v = !0, r = r.substring(2)), a = a ? d : p, s = new Vi(a), s = new Ni(s, n, a).parse(r), s.constant ? s.$$watchDelegate = f : v ? (s = i(s), s.$$watchDelegate = s.literal ? l : c) : s.inputs && (s.$$watchDelegate = u), g[m] = s), $(s, o);
                    case "function":
                        return $(r, o);
                    default:
                        return $(h, o)
                }
            }
        }]
    }

    function On() {
        this.$get = ["$rootScope", "$exceptionHandler", function(t, e) {
            return Mn(function(e) {
                t.$evalAsync(e)
            }, e)
        }]
    }

    function Tn() {
        this.$get = ["$browser", "$exceptionHandler", function(t, e) {
            return Mn(function(e) {
                t.defer(e)
            }, e)
        }]
    }

    function Mn(t, e) {
        function i(t, e, n) {
            function r(e) {
                return function(n) {
                    i || (i = !0, e.call(t, n))
                }
            }
            var i = !1;
            return [r(e), r(n)]
        }

        function a() {
            this.$$state = {
                status: 0
            }
        }

        function s(t, e) {
            return function(n) {
                e.call(t, n)
            }
        }

        function u(r) {
            !r.processScheduled && r.pending && (r.processScheduled = !0, t(function() {
                var t, i, o;
                o = r.pending, r.processScheduled = !1, r.pending = n;
                for (var a = 0, s = o.length; s > a; ++a) {
                    i = o[a][0], t = o[a][r.status];
                    try {
                        b(t) ? i.resolve(t(r.value)) : 1 === r.status ? i.resolve(r.value) : i.reject(r.value)
                    } catch (u) {
                        i.reject(u), e(u)
                    }
                }
            }))
        }

        function c() {
            this.promise = new a, this.resolve = s(this, this.resolve), this.reject = s(this, this.reject), this.notify = s(this, this.notify)
        }
        var l = r("$q", TypeError);
        a.prototype = {
            then: function(t, e, n) {
                var r = new c;
                return this.$$state.pending = this.$$state.pending || [], this.$$state.pending.push([r, t, e, n]), 0 < this.$$state.status && u(this.$$state), r.promise
            },
            "catch": function(t) {
                return this.then(null, t)
            },
            "finally": function(t, e) {
                return this.then(function(e) {
                    return h(e, !0, t)
                }, function(e) {
                    return h(e, !1, t)
                }, e)
            }
        }, c.prototype = {
            resolve: function(t) {
                this.promise.$$state.status || (t === this.promise ? this.$$reject(l("qcycle", t)) : this.$$resolve(t))
            },
            $$resolve: function(t) {
                var n, r;
                r = i(this, this.$$resolve, this.$$reject);
                try {
                    (m(t) || b(t)) && (n = t && t.then), b(n) ? (this.promise.$$state.status = -1, n.call(t, r[0], r[1], this.notify)) : (this.promise.$$state.value = t, this.promise.$$state.status = 1, u(this.promise.$$state))
                } catch (o) {
                    r[1](o), e(o)
                }
            },
            reject: function(t) {
                this.promise.$$state.status || this.$$reject(t)
            },
            $$reject: function(t) {
                this.promise.$$state.value = t, this.promise.$$state.status = 2, u(this.promise.$$state)
            },
            notify: function(n) {
                var r = this.promise.$$state.pending;
                0 >= this.promise.$$state.status && r && r.length && t(function() {
                    for (var t, i, o = 0, a = r.length; a > o; o++) {
                        i = r[o][0], t = r[o][3];
                        try {
                            i.notify(b(t) ? t(n) : n)
                        } catch (s) {
                            e(s)
                        }
                    }
                })
            }
        };
        var f = function(t, e) {
                var n = new c;
                return e ? n.resolve(t) : n.reject(t), n.promise
            },
            h = function(t, e, n) {
                var r = null;
                try {
                    b(n) && (r = n())
                } catch (i) {
                    return f(i, !1)
                }
                return r && b(r.then) ? r.then(function() {
                    return f(t, e)
                }, function(t) {
                    return f(t, !1)
                }) : f(t, e)
            },
            $ = function(t, e, n, r) {
                var i = new c;
                return i.resolve(t), i.promise.then(e, n, r)
            },
            p = function d(t) {
                if (!b(t)) throw l("norslvr", t);
                if (!(this instanceof d)) return new d(t);
                var e = new c;
                return t(function(t) {
                    e.resolve(t)
                }, function(t) {
                    e.reject(t)
                }), e.promise
            };
        return p.defer = function() {
            return new c
        }, p.reject = function(t) {
            var e = new c;
            return e.reject(t), e.promise
        }, p.when = $, p.all = function(t) {
            var e = new c,
                n = 0,
                r = jr(t) ? [] : {};
            return o(t, function(t, i) {
                n++, $(t).then(function(t) {
                    r.hasOwnProperty(i) || (r[i] = t, --n || e.resolve(r))
                }, function(t) {
                    r.hasOwnProperty(i) || e.reject(t)
                })
            }), 0 === n && e.resolve(r), e.promise
        }, p
    }

    function Vn() {
        this.$get = ["$window", "$timeout", function(t, e) {
            var n = t.requestAnimationFrame || t.webkitRequestAnimationFrame,
                r = t.cancelAnimationFrame || t.webkitCancelAnimationFrame || t.webkitCancelRequestAnimationFrame,
                i = !!n,
                o = i ? function(t) {
                    var e = n(t);
                    return function() {
                        r(e)
                    }
                } : function(t) {
                    var n = e(t, 16.66, !1);
                    return function() {
                        e.cancel(n)
                    }
                };
            return o.supported = i, o
        }]
    }

    function Nn() {
        var t = 10,
            e = r("$rootScope"),
            n = null,
            a = null;
        this.digestTtl = function(e) {
            return arguments.length && (t = e), t
        }, this.$get = ["$injector", "$exceptionHandler", "$parse", "$browser", function(r, s, u, c) {
            function l() {
                this.$id = ++Nr, this.$$phase = this.$parent = this.$$watchers = this.$$nextSibling = this.$$prevSibling = this.$$childHead = this.$$childTail = null, this.$root = this, this.$$destroyed = !1, this.$$listeners = {}, this.$$listenerCount = {}, this.$$isolateBindings = null
            }

            function f(t) {
                if (y.$$phase) throw e("inprog", y.$$phase);
                y.$$phase = t
            }

            function $(t, e, n) {
                do t.$$listenerCount[n] -= e, 0 === t.$$listenerCount[n] && delete t.$$listenerCount[n]; while (t = t.$parent)
            }

            function p() {}

            function v() {
                for (; S.length;) try {
                    S.shift()()
                } catch (t) {
                    s(t)
                }
                a = null
            }

            function g() {
                null === a && (a = c.defer(function() {
                    y.$apply(v)
                }))
            }
            l.prototype = {
                constructor: l,
                $new: function(t, e) {
                    function n() {
                        r.$$destroyed = !0
                    }
                    var r;
                    return e = e || this, t ? (r = new l, r.$root = this.$root) : (this.$$ChildScope || (this.$$ChildScope = function() {
                        this.$$watchers = this.$$nextSibling = this.$$childHead = this.$$childTail = null, this.$$listeners = {}, this.$$listenerCount = {}, this.$id = ++Nr, this.$$ChildScope = null
                    }, this.$$ChildScope.prototype = this), r = new this.$$ChildScope), r.$parent = e, r.$$prevSibling = e.$$childTail, e.$$childHead ? (e.$$childTail.$$nextSibling = r, e.$$childTail = r) : e.$$childHead = e.$$childTail = r, (t || e != this) && r.$on("$destroy", n), r
                },
                $watch: function(t, e, r) {
                    var i = u(t);
                    if (i.$$watchDelegate) return i.$$watchDelegate(this, e, r, i);
                    var o = this.$$watchers,
                        a = {
                            fn: e,
                            last: p,
                            get: i,
                            exp: t,
                            eq: !!r
                        };
                    return n = null, b(e) || (a.fn = h), o || (o = this.$$watchers = []), o.unshift(a),
                        function() {
                            T(o, a), n = null
                        }
                },
                $watchGroup: function(t, e) {
                    function n() {
                        u = !1, c ? (c = !1, e(i, i, s)) : e(i, r, s)
                    }
                    var r = Array(t.length),
                        i = Array(t.length),
                        a = [],
                        s = this,
                        u = !1,
                        c = !0;
                    if (!t.length) {
                        var l = !0;
                        return s.$evalAsync(function() {
                                l && e(i, i, s)
                            }),
                            function() {
                                l = !1
                            }
                    }
                    return 1 === t.length ? this.$watch(t[0], function(t, n, o) {
                        i[0] = t, r[0] = n, e(i, t === n ? i : r, o)
                    }) : (o(t, function(t, e) {
                        var o = s.$watch(t, function(t, o) {
                            i[e] = t, r[e] = o, u || (u = !0, s.$evalAsync(n))
                        });
                        a.push(o)
                    }), function() {
                        for (; a.length;) a.shift()()
                    })
                },
                $watchCollection: function(t, e) {
                    function n(t) {
                        r = t;
                        var e, n, a, s;
                        if (!d(r)) {
                            if (m(r))
                                if (i(r))
                                    for (o !== h && (o = h, v = o.length = 0, l++), t = r.length, v !== t && (l++, o.length = v = t), e = 0; t > e; e++) s = o[e], a = r[e], n = s !== s && a !== a, n || s === a || (l++, o[e] = a);
                                else {
                                    o !== $ && (o = $ = {}, v = 0, l++), t = 0;
                                    for (e in r) r.hasOwnProperty(e) && (t++, a = r[e], s = o[e], e in o ? (n = s !== s && a !== a, n || s === a || (l++, o[e] = a)) : (v++, o[e] = a, l++));
                                    if (v > t)
                                        for (e in l++, o) r.hasOwnProperty(e) || (v--, delete o[e])
                                }
                            else o !== r && (o = r, l++);
                            return l
                        }
                    }
                    n.$stateful = !0;
                    var r, o, a, s = this,
                        c = 1 < e.length,
                        l = 0,
                        f = u(t, n),
                        h = [],
                        $ = {},
                        p = !0,
                        v = 0;
                    return this.$watch(f, function() {
                        if (p ? (p = !1, e(r, r, s)) : e(r, a, s), c)
                            if (m(r))
                                if (i(r)) {
                                    a = Array(r.length);
                                    for (var t = 0; t < r.length; t++) a[t] = r[t]
                                } else
                                    for (t in a = {}, r) Cr.call(r, t) && (a[t] = r[t]);
                        else a = r
                    })
                },
                $digest: function() {
                    var r, i, o, u, l, h, $, d, m, g = t,
                        S = [];
                    f("$digest"), c.$$checkUrlChange(), this === y && null !== a && (c.defer.cancel(a), v()), n = null;
                    do {
                        for (h = !1, $ = this; w.length;) {
                            try {
                                m = w.shift(), m.scope.$eval(m.expression, m.locals)
                            } catch (C) {
                                s(C)
                            }
                            n = null
                        }
                        t: do {
                            if (u = $.$$watchers)
                                for (l = u.length; l--;) try {
                                    if (r = u[l])
                                        if ((i = r.get($)) === (o = r.last) || (r.eq ? N(i, o) : "number" == typeof i && "number" == typeof o && isNaN(i) && isNaN(o))) {
                                            if (r === n) {
                                                h = !1;
                                                break t
                                            }
                                        } else h = !0, n = r, r.last = r.eq ? M(i, null) : i, r.fn(i, o === p ? i : o, $), 5 > g && (d = 4 - g, S[d] || (S[d] = []), S[d].push({
                                            msg: b(r.exp) ? "fn: " + (r.exp.name || r.exp.toString()) : r.exp,
                                            newVal: i,
                                            oldVal: o
                                        }))
                                } catch (k) {
                                    s(k)
                                }
                            if (!(u = $.$$childHead || $ !== this && $.$$nextSibling))
                                for (; $ !== this && !(u = $.$$nextSibling);) $ = $.$parent
                        } while ($ = u);
                        if ((h || w.length) && !g--) throw y.$$phase = null, e("infdig", t, S)
                    } while (h || w.length);
                    for (y.$$phase = null; x.length;) try {
                        x.shift()()
                    } catch (A) {
                        s(A)
                    }
                },
                $destroy: function() {
                    if (!this.$$destroyed) {
                        var t = this.$parent;
                        if (this.$broadcast("$destroy"), this.$$destroyed = !0, this !== y) {
                            for (var e in this.$$listenerCount) $(this, this.$$listenerCount[e], e);
                            t.$$childHead == this && (t.$$childHead = this.$$nextSibling), t.$$childTail == this && (t.$$childTail = this.$$prevSibling), this.$$prevSibling && (this.$$prevSibling.$$nextSibling = this.$$nextSibling), this.$$nextSibling && (this.$$nextSibling.$$prevSibling = this.$$prevSibling), this.$destroy = this.$digest = this.$apply = this.$evalAsync = this.$applyAsync = h, this.$on = this.$watch = this.$watchGroup = function() {
                                return h
                            }, this.$$listeners = {}, this.$parent = this.$$nextSibling = this.$$prevSibling = this.$$childHead = this.$$childTail = this.$root = this.$$watchers = null
                        }
                    }
                },
                $eval: function(t, e) {
                    return u(t)(this, e)
                },
                $evalAsync: function(t, e) {
                    y.$$phase || w.length || c.defer(function() {
                        w.length && y.$digest()
                    }), w.push({
                        scope: this,
                        expression: t,
                        locals: e
                    })
                },
                $$postDigest: function(t) {
                    x.push(t)
                },
                $apply: function(t) {
                    try {
                        return f("$apply"), this.$eval(t)
                    } catch (e) {
                        s(e)
                    } finally {
                        y.$$phase = null;
                        try {
                            y.$digest()
                        } catch (n) {
                            throw s(n), n
                        }
                    }
                },
                $applyAsync: function(t) {
                    function e() {
                        n.$eval(t)
                    }
                    var n = this;
                    t && S.push(e), g()
                },
                $on: function(t, e) {
                    var n = this.$$listeners[t];
                    n || (this.$$listeners[t] = n = []), n.push(e);
                    var r = this;
                    do r.$$listenerCount[t] || (r.$$listenerCount[t] = 0), r.$$listenerCount[t]++; while (r = r.$parent);
                    var i = this;
                    return function() {
                        var r = n.indexOf(e); - 1 !== r && (n[r] = null, $(i, 1, t))
                    }
                },
                $emit: function(t) {
                    var e, n, r, i = [],
                        o = this,
                        a = !1,
                        u = {
                            name: t,
                            targetScope: o,
                            stopPropagation: function() {
                                a = !0
                            },
                            preventDefault: function() {
                                u.defaultPrevented = !0
                            },
                            defaultPrevented: !1
                        },
                        c = D([u], arguments, 1);
                    do {
                        for (e = o.$$listeners[t] || i, u.currentScope = o, n = 0, r = e.length; r > n; n++)
                            if (e[n]) try {
                                e[n].apply(null, c)
                            } catch (l) {
                                s(l)
                            } else e.splice(n, 1), n--, r--;
                        if (a) return u.currentScope = null, u;
                        o = o.$parent
                    } while (o);
                    return u.currentScope = null, u
                },
                $broadcast: function(t) {
                    var e = this,
                        n = this,
                        r = {
                            name: t,
                            targetScope: this,
                            preventDefault: function() {
                                r.defaultPrevented = !0
                            },
                            defaultPrevented: !1
                        };
                    if (!this.$$listenerCount[t]) return r;
                    for (var i, o, a = D([r], arguments, 1); e = n;) {
                        for (r.currentScope = e, n = e.$$listeners[t] || [], i = 0, o = n.length; o > i; i++)
                            if (n[i]) try {
                                n[i].apply(null, a)
                            } catch (u) {
                                s(u)
                            } else n.splice(i, 1), i--, o--;
                        if (!(n = e.$$listenerCount[t] && e.$$childHead || e !== this && e.$$nextSibling))
                            for (; e !== this && !(n = e.$$nextSibling);) e = e.$parent
                    }
                    return r.currentScope = null, r
                }
            };
            var y = new l,
                w = y.$$asyncQueue = [],
                x = y.$$postDigestQueue = [],
                S = y.$$applyAsyncQueue = [];
            return y
        }]
    }

    function Dn() {
        var t = /^\s*(https?|ftp|mailto|tel|file):/,
            e = /^\s*((https?|ftp|file|blob):|data:image\/)/;
        this.aHrefSanitizationWhitelist = function(e) {
            return v(e) ? (t = e, this) : t
        }, this.imgSrcSanitizationWhitelist = function(t) {
            return v(t) ? (e = t, this) : e
        }, this.$get = function() {
            return function(n, r) {
                var i, o = r ? e : t;
                return i = _n(n).href, "" === i || i.match(o) ? n : "unsafe:" + i
            }
        }
    }

    function jn(t) {
        if ("self" === t) return t;
        if (g(t)) {
            if (-1 < t.indexOf("***")) throw Ri("iwcard", t);
            return t = Rr(t).replace("\\*\\*", ".*").replace("\\*", "[^:/.?&;]*"), new RegExp("^" + t + "$")
        }
        if (x(t)) return new RegExp("^" + t.source + "$");
        throw Ri("imatcher")
    }

    function Pn(t) {
        var e = [];
        return v(t) && o(t, function(t) {
            e.push(jn(t))
        }), e
    }

    function Rn() {
        this.SCE_CONTEXTS = qi;
        var t = ["self"],
            e = [];
        this.resourceUrlWhitelist = function(e) {
            return arguments.length && (t = Pn(e)), t
        }, this.resourceUrlBlacklist = function(t) {
            return arguments.length && (e = Pn(t)), e
        }, this.$get = ["$injector", function(r) {
            function i(t, e) {
                return "self" === t ? Ln(e) : !!t.exec(e.href)
            }

            function o(t) {
                var e = function(t) {
                    this.$$unwrapTrustedValue = function() {
                        return t
                    }
                };
                return t && (e.prototype = new t), e.prototype.valueOf = function() {
                    return this.$$unwrapTrustedValue()
                }, e.prototype.toString = function() {
                    return this.$$unwrapTrustedValue().toString()
                }, e
            }
            var a = function() {
                throw Ri("unsafe")
            };
            r.has("$sanitize") && (a = r.get("$sanitize"));
            var s = o(),
                u = {};
            return u[qi.HTML] = o(s), u[qi.CSS] = o(s), u[qi.URL] = o(s), u[qi.JS] = o(s), u[qi.RESOURCE_URL] = o(u[qi.URL]), {
                trustAs: function(t, e) {
                    var r = u.hasOwnProperty(t) ? u[t] : null;
                    if (!r) throw Ri("icontext", t, e);
                    if (null === e || e === n || "" === e) return e;
                    if ("string" != typeof e) throw Ri("itype", t);
                    return new r(e)
                },
                getTrusted: function(r, o) {
                    if (null === o || o === n || "" === o) return o;
                    var s = u.hasOwnProperty(r) ? u[r] : null;
                    if (s && o instanceof s) return o.$$unwrapTrustedValue();
                    if (r === qi.RESOURCE_URL) {
                        var c, l, s = _n(o.toString()),
                            f = !1;
                        for (c = 0, l = t.length; l > c; c++)
                            if (i(t[c], s)) {
                                f = !0;
                                break
                            }
                        if (f)
                            for (c = 0, l = e.length; l > c; c++)
                                if (i(e[c], s)) {
                                    f = !1;
                                    break
                                }
                        if (f) return o;
                        throw Ri("insecurl", o.toString())
                    }
                    if (r === qi.HTML) return a(o);
                    throw Ri("unsafe")
                },
                valueOf: function(t) {
                    return t instanceof s ? t.$$unwrapTrustedValue() : t
                }
            }
        }]
    }

    function qn() {
        var t = !0;
        this.enabled = function(e) {
            return arguments.length && (t = !!e), t
        }, this.$get = ["$parse", "$sceDelegate", function(e, n) {
            if (t && 8 > gr) throw Ri("iequirks");
            var r = V(qi);
            r.isEnabled = function() {
                return t
            }, r.trustAs = n.trustAs, r.getTrusted = n.getTrusted, r.valueOf = n.valueOf, t || (r.trustAs = r.getTrusted = function(t, e) {
                return e
            }, r.valueOf = $), r.parseAs = function(t, n) {
                var i = e(n);
                return i.literal && i.constant ? i : e(n, function(e) {
                    return r.getTrusted(t, e)
                })
            };
            var i = r.parseAs,
                a = r.getTrusted,
                s = r.trustAs;
            return o(qi, function(t, e) {
                var n = Sr(e);
                r[oe("parse_as_" + n)] = function(e) {
                    return i(t, e)
                }, r[oe("get_trusted_" + n)] = function(e) {
                    return a(t, e)
                }, r[oe("trust_as_" + n)] = function(e) {
                    return s(t, e)
                }
            }), r
        }]
    }

    function In() {
        this.$get = ["$window", "$document", function(t, e) {
            var n, r = {},
                i = f((/android (\d+)/.exec(Sr((t.navigator || {}).userAgent)) || [])[1]),
                o = /Boxee/i.test((t.navigator || {}).userAgent),
                a = e[0] || {},
                s = /^(Moz|webkit|ms)(?=[A-Z])/,
                u = a.body && a.body.style,
                c = !1,
                l = !1;
            if (u) {
                for (var h in u)
                    if (c = s.exec(h)) {
                        n = c[0], n = n.substr(0, 1).toUpperCase() + n.substr(1);
                        break
                    }
                n || (n = "WebkitOpacity" in u && "webkit"), c = !!("transition" in u || n + "Transition" in u), l = !!("animation" in u || n + "Animation" in u), !i || c && l || (c = g(a.body.style.webkitTransition), l = g(a.body.style.webkitAnimation))
            }
            return {
                history: !(!t.history || !t.history.pushState || 4 > i || o),
                hasEvent: function(t) {
                    if ("input" === t && 11 >= gr) return !1;
                    if (d(r[t])) {
                        var e = a.createElement("div");
                        r[t] = "on" + t in e
                    }
                    return r[t]
                },
                csp: qr(),
                vendorPrefix: n,
                transitions: c,
                animations: l,
                android: i
            }
        }]
    }

    function Un() {
        this.$get = ["$templateCache", "$http", "$q", function(t, e, n) {
            function r(i, o) {
                r.totalPendingRequests++;
                var a = e.defaults && e.defaults.transformResponse;
                return jr(a) ? a = a.filter(function(t) {
                    return t !== Ge
                }) : a === Ge && (a = null), e.get(i, {
                    cache: t,
                    transformResponse: a
                }).then(function(t) {
                    return r.totalPendingRequests--, t.data
                }, function(t) {
                    if (r.totalPendingRequests--, !o) throw hi("tpload", i);
                    return n.reject(t)
                })
            }
            return r.totalPendingRequests = 0, r
        }]
    }

    function Fn() {
        this.$get = ["$rootScope", "$browser", "$location", function(t, e, n) {
            return {
                findBindings: function(t, e, n) {
                    t = t.getElementsByClassName("ng-binding");
                    var r = [];
                    return o(t, function(t) {
                        var i = Vr.element(t).data("$binding");
                        i && o(i, function(i) {
                            n ? new RegExp("(^|\\s)" + Rr(e) + "(\\s|\\||$)").test(i) && r.push(t) : -1 != i.indexOf(e) && r.push(t)
                        })
                    }), r
                },
                findModels: function(t, e, n) {
                    for (var r = ["ng-", "data-ng-", "ng\\:"], i = 0; i < r.length; ++i) {
                        var o = t.querySelectorAll("[" + r[i] + "model" + (n ? "=" : "*=") + '"' + e + '"]');
                        if (o.length) return o
                    }
                },
                getLocation: function() {
                    return n.url()
                },
                setLocation: function(e) {
                    e !== n.url() && (n.url(e), t.$digest())
                },
                whenStable: function(t) {
                    e.notifyWhenNoOutstandingRequests(t)
                }
            }
        }]
    }

    function Hn() {
        this.$get = ["$rootScope", "$browser", "$q", "$$q", "$exceptionHandler", function(t, e, n, r, i) {
            function o(o, s, u) {
                var c = v(u) && !u,
                    l = (c ? r : n).defer(),
                    f = l.promise;
                return s = e.defer(function() {
                    try {
                        l.resolve(o())
                    } catch (e) {
                        l.reject(e), i(e)
                    } finally {
                        delete a[f.$$timeoutId]
                    }
                    c || t.$apply()
                }, s), f.$$timeoutId = s, a[s] = l, f
            }
            var a = {};
            return o.cancel = function(t) {
                return t && t.$$timeoutId in a ? (a[t.$$timeoutId].reject("canceled"), delete a[t.$$timeoutId], e.defer.cancel(t.$$timeoutId)) : !1
            }, o
        }]
    }

    function _n(t) {
        return gr && (Ii.setAttribute("href", t), t = Ii.href), Ii.setAttribute("href", t), {
            href: Ii.href,
            protocol: Ii.protocol ? Ii.protocol.replace(/:$/, "") : "",
            host: Ii.host,
            search: Ii.search ? Ii.search.replace(/^\?/, "") : "",
            hash: Ii.hash ? Ii.hash.replace(/^#/, "") : "",
            hostname: Ii.hostname,
            port: Ii.port,
            pathname: "/" === Ii.pathname.charAt(0) ? Ii.pathname : "/" + Ii.pathname
        }
    }

    function Ln(t) {
        return t = g(t) ? _n(t) : t, t.protocol === Ui.protocol && t.host === Ui.host
    }

    function Bn() {
        this.$get = p(t)
    }

    function zn(t) {
        function e(n, r) {
            if (m(n)) {
                var i = {};
                return o(n, function(t, n) {
                    i[n] = e(n, t)
                }), i
            }
            return t.factory(n + "Filter", r)
        }
        this.register = e, this.$get = ["$injector", function(t) {
            return function(e) {
                return t.get(e + "Filter")
            }
        }], e("currency", Zn), e("date", rr), e("filter", Gn), e("json", ir), e("limitTo", or), e("lowercase", Bi), e("number", Yn), e("orderBy", ar), e("uppercase", zi)
    }

    function Gn() {
        return function(t, e, n) {
            if (!jr(t)) return t;
            var r;
            switch (typeof e) {
                case "function":
                    break;
                case "boolean":
                case "number":
                case "string":
                    r = !0;
                case "object":
                    e = Wn(e, n, r);
                    break;
                default:
                    return t
            }
            return t.filter(e)
        }
    }

    function Wn(t, e, n) {
        var r = m(t) && "$" in t;
        return !0 === e ? e = N : b(e) || (e = function(t, e) {
                return m(t) || m(e) ? !1 : (t = Sr("" + t), e = Sr("" + e), -1 !== t.indexOf(e))
            }),
            function(i) {
                return r && !m(i) ? Jn(i, t.$, e, !1) : Jn(i, t, e, n)
            }
    }

    function Jn(t, e, n, r, i) {
        var o = typeof t,
            a = typeof e;
        if ("string" === a && "!" === e.charAt(0)) return !Jn(t, e.substring(1), n, r);
        if (jr(t)) return t.some(function(t) {
            return Jn(t, e, n, r)
        });
        switch (o) {
            case "object":
                var s;
                if (r) {
                    for (s in t)
                        if ("$" !== s.charAt(0) && Jn(t[s], e, n, !0)) return !0;
                    return i ? !1 : Jn(t, e, n, !1)
                }
                if ("object" === a) {
                    for (s in e)
                        if (i = e[s], !b(i) && (o = "$" === s, !Jn(o ? t : t[s], i, n, o, o))) return !1;
                    return !0
                }
                return n(t, e);
            case "function":
                return !1;
            default:
                return n(t, e)
        }
    }

    function Zn(t) {
        var e = t.NUMBER_FORMATS;
        return function(t, n, r) {
            return d(n) && (n = e.CURRENCY_SYM), d(r) && (r = e.PATTERNS[1].maxFrac), null == t ? t : Kn(t, e.PATTERNS[1], e.GROUP_SEP, e.DECIMAL_SEP, r).replace(/\u00A4/g, n)
        }
    }

    function Yn(t) {
        var e = t.NUMBER_FORMATS;
        return function(t, n) {
            return null == t ? t : Kn(t, e.PATTERNS[0], e.GROUP_SEP, e.DECIMAL_SEP, n)
        }
    }

    function Kn(t, e, n, r, i) {
        if (!isFinite(t) || m(t)) return "";
        var o = 0 > t;
        t = Math.abs(t);
        var a = t + "",
            s = "",
            u = [],
            c = !1;
        if (-1 !== a.indexOf("e")) {
            var l = a.match(/([\d\.]+)e(-?)(\d+)/);
            l && "-" == l[2] && l[3] > i + 1 ? t = 0 : (s = a, c = !0)
        }
        if (c) i > 0 && 1 > t && (s = t.toFixed(i), t = parseFloat(s));
        else {
            a = (a.split(Fi)[1] || "").length, d(i) && (i = Math.min(Math.max(e.minFrac, a), e.maxFrac)), t = +(Math.round(+(t.toString() + "e" + i)).toString() + "e" + -i);
            var a = ("" + t).split(Fi),
                c = a[0],
                a = a[1] || "",
                f = 0,
                h = e.lgSize,
                $ = e.gSize;
            if (c.length >= h + $)
                for (f = c.length - h, l = 0; f > l; l++) 0 === (f - l) % $ && 0 !== l && (s += n), s += c.charAt(l);
            for (l = f; l < c.length; l++) 0 === (c.length - l) % h && 0 !== l && (s += n), s += c.charAt(l);
            for (; a.length < i;) a += "0";
            i && "0" !== i && (s += r + a.substr(0, i))
        }
        return 0 === t && (o = !1), u.push(o ? e.negPre : e.posPre, s, o ? e.negSuf : e.posSuf), u.join("")
    }

    function Xn(t, e, n) {
        var r = "";
        for (0 > t && (r = "-", t = -t), t = "" + t; t.length < e;) t = "0" + t;
        return n && (t = t.substr(t.length - e)), r + t
    }

    function Qn(t, e, n, r) {
        return n = n || 0,
            function(i) {
                return i = i["get" + t](), (n > 0 || i > -n) && (i += n), 0 === i && -12 == n && (i = 12), Xn(i, e, r)
            }
    }

    function tr(t, e) {
        return function(n, r) {
            var i = n["get" + t](),
                o = kr(e ? "SHORT" + t : t);
            return r[o][i]
        }
    }

    function er(t) {
        var e = new Date(t, 0, 1).getDay();
        return new Date(t, 0, (4 >= e ? 5 : 12) - e)
    }

    function nr(t) {
        return function(e) {
            var n = er(e.getFullYear());
            return e = +new Date(e.getFullYear(), e.getMonth(), e.getDate() + (4 - e.getDay())) - +n, e = 1 + Math.round(e / 6048e5), Xn(e, t)
        }
    }

    function rr(t) {
        function e(t) {
            var e;
            if (e = t.match(n)) {
                t = new Date(0);
                var r = 0,
                    i = 0,
                    o = e[8] ? t.setUTCFullYear : t.setFullYear,
                    a = e[8] ? t.setUTCHours : t.setHours;
                e[9] && (r = f(e[9] + e[10]), i = f(e[9] + e[11])), o.call(t, f(e[1]), f(e[2]) - 1, f(e[3])), r = f(e[4] || 0) - r, i = f(e[5] || 0) - i, o = f(e[6] || 0), e = Math.round(1e3 * parseFloat("0." + (e[7] || 0))), a.call(t, r, i, o, e)
            }
            return t
        }
        var n = /^(\d{4})-?(\d\d)-?(\d\d)(?:T(\d\d)(?::?(\d\d)(?::?(\d\d)(?:\.(\d+))?)?)?(Z|([+-])(\d\d):?(\d\d))?)?$/;
        return function(n, r, i) {
            var a, s, u = "",
                c = [];
            if (r = r || "mediumDate", r = t.DATETIME_FORMATS[r] || r, g(n) && (n = Li.test(n) ? f(n) : e(n)), y(n) && (n = new Date(n)), !w(n)) return n;
            for (; r;)(s = _i.exec(r)) ? (c = D(c, s, 1), r = c.pop()) : (c.push(r), r = null);
            return i && "UTC" === i && (n = new Date(n.getTime()), n.setMinutes(n.getMinutes() + n.getTimezoneOffset())), o(c, function(e) {
                a = Hi[e], u += a ? a(n, t.DATETIME_FORMATS) : e.replace(/(^'|'$)/g, "").replace(/''/g, "'")
            }), u
        }
    }

    function ir() {
        return function(t, e) {
            return d(e) && (e = 2), R(t, e)
        }
    }

    function or() {
        return function(t, e) {
            return y(t) && (t = t.toString()), jr(t) || g(t) ? (e = 1 / 0 === Math.abs(Number(e)) ? Number(e) : f(e)) ? e > 0 ? t.slice(0, e) : t.slice(e) : g(t) ? "" : [] : t
        }
    }

    function ar(t) {
        return function(e, n, r) {
            function o(t, e) {
                return e ? function(e, n) {
                    return t(n, e)
                } : t
            }

            function a(t) {
                switch (typeof t) {
                    case "number":
                    case "boolean":
                    case "string":
                        return !0;
                    default:
                        return !1
                }
            }

            function s(t) {
                return null === t ? "null" : "function" == typeof t.valueOf && (t = t.valueOf(), a(t)) || "function" == typeof t.toString && (t = t.toString(), a(t)) ? t : ""
            }

            function u(t, e) {
                var n = typeof t,
                    r = typeof e;
                return n === r && "object" === n && (t = s(t), e = s(e)), n === r ? ("string" === n && (t = t.toLowerCase(), e = e.toLowerCase()), t === e ? 0 : e > t ? -1 : 1) : r > n ? -1 : 1
            }
            return i(e) ? (n = jr(n) ? n : [n], 0 === n.length && (n = ["+"]), n = n.map(function(e) {
                var n = !1,
                    r = e || $;
                if (g(e)) {
                    if (("+" == e.charAt(0) || "-" == e.charAt(0)) && (n = "-" == e.charAt(0), e = e.substring(1)), "" === e) return o(u, n);
                    if (r = t(e), r.constant) {
                        var i = r();
                        return o(function(t, e) {
                            return u(t[i], e[i])
                        }, n)
                    }
                }
                return o(function(t, e) {
                    return u(r(t), r(e))
                }, n)
            }), Ar.call(e).sort(o(function(t, e) {
                for (var r = 0; r < n.length; r++) {
                    var i = n[r](t, e);
                    if (0 !== i) return i
                }
                return 0
            }, r))) : e
        }
    }

    function sr(t) {
        return b(t) && (t = {
            link: t
        }), t.restrict = t.restrict || "AC", p(t)
    }

    function ur(t, e, r, i, a) {
        var s = this,
            u = [],
            c = s.$$parentForm = t.parent().controller("form") || Ji;
        s.$error = {}, s.$$success = {}, s.$pending = n, s.$name = a(e.name || e.ngForm || "")(r), s.$dirty = !1, s.$pristine = !0, s.$valid = !0, s.$invalid = !1, s.$submitted = !1, c.$addControl(s), s.$rollbackViewValue = function() {
            o(u, function(t) {
                t.$rollbackViewValue()
            })
        }, s.$commitViewValue = function() {
            o(u, function(t) {
                t.$commitViewValue()
            })
        }, s.$addControl = function(t) {
            Q(t.$name, "input"), u.push(t), t.$name && (s[t.$name] = t)
        }, s.$$renameControl = function(t, e) {
            var n = t.$name;
            s[n] === t && delete s[n], s[e] = t, t.$name = e
        }, s.$removeControl = function(t) {
            t.$name && s[t.$name] === t && delete s[t.$name], o(s.$pending, function(e, n) {
                s.$setValidity(n, null, t)
            }), o(s.$error, function(e, n) {
                s.$setValidity(n, null, t)
            }), T(u, t)
        }, vr({
            ctrl: this,
            $element: t,
            set: function(t, e, n) {
                var r = t[e];
                r ? -1 === r.indexOf(n) && r.push(n) : t[e] = [n]
            },
            unset: function(t, e, n) {
                var r = t[e];
                r && (T(r, n), 0 === r.length && delete t[e])
            },
            parentForm: c,
            $animate: i
        }), s.$setDirty = function() {
            i.removeClass(t, Mo), i.addClass(t, Vo), s.$dirty = !0, s.$pristine = !1, c.$setDirty()
        }, s.$setPristine = function() {
            i.setClass(t, Mo, Vo + " ng-submitted"), s.$dirty = !1, s.$pristine = !0, s.$submitted = !1, o(u, function(t) {
                t.$setPristine()
            })
        }, s.$setUntouched = function() {
            o(u, function(t) {
                t.$setUntouched()
            })
        }, s.$setSubmitted = function() {
            i.addClass(t, "ng-submitted"), s.$submitted = !0, c.$setSubmitted()
        }
    }

    function cr(t) {
        t.$formatters.push(function(e) {
            return t.$isEmpty(e) ? e : e.toString()
        })
    }

    function lr(t, e, n, r, i, o) {
        var a = Sr(e[0].type);
        if (!i.android) {
            var s = !1;
            e.on("compositionstart", function() {
                s = !0
            }), e.on("compositionend", function() {
                s = !1, u()
            })
        }
        var u = function(t) {
            if (c && (o.defer.cancel(c), c = null), !s) {
                var i = e.val();
                t = t && t.type, "password" === a || n.ngTrim && "false" === n.ngTrim || (i = Pr(i)), (r.$viewValue !== i || "" === i && r.$$hasNativeValidators) && r.$setViewValue(i, t)
            }
        };
        if (i.hasEvent("input")) e.on("input", u);
        else {
            var c, l = function(t, e, n) {
                c || (c = o.defer(function() {
                    c = null, e && e.value === n || u(t)
                }))
            };
            e.on("keydown", function(t) {
                var e = t.keyCode;
                91 === e || e > 15 && 19 > e || e >= 37 && 40 >= e || l(t, this, this.value)
            }), i.hasEvent("paste") && e.on("paste cut", l)
        }
        e.on("change", u), r.$render = function() {
            e.val(r.$isEmpty(r.$viewValue) ? "" : r.$viewValue)
        }
    }

    function fr(t, e) {
        return function(n, r) {
            var i, a;
            if (w(n)) return n;
            if (g(n)) {
                if ('"' == n.charAt(0) && '"' == n.charAt(n.length - 1) && (n = n.substring(1, n.length - 1)), Xi.test(n)) return new Date(n);
                if (t.lastIndex = 0, i = t.exec(n)) return i.shift(), a = r ? {
                    yyyy: r.getFullYear(),
                    MM: r.getMonth() + 1,
                    dd: r.getDate(),
                    HH: r.getHours(),
                    mm: r.getMinutes(),
                    ss: r.getSeconds(),
                    sss: r.getMilliseconds() / 1e3
                } : {
                    yyyy: 1970,
                    MM: 1,
                    dd: 1,
                    HH: 0,
                    mm: 0,
                    ss: 0,
                    sss: 0
                }, o(i, function(t, n) {
                    n < e.length && (a[e[n]] = +t)
                }), new Date(a.yyyy, a.MM - 1, a.dd, a.HH, a.mm, a.ss || 0, 1e3 * a.sss || 0)
            }
            return 0 / 0
        }
    }

    function hr(t, e, r, i) {
        return function(o, a, s, u, c, l, f) {
            function h(t) {
                return t && !(t.getTime && t.getTime() !== t.getTime())
            }

            function $(t) {
                return v(t) ? w(t) ? t : r(t) : n
            }
            $r(o, a, s, u), lr(o, a, s, u, c, l);
            var p, m = u && u.$options && u.$options.timezone;
            if (u.$$parserName = t, u.$parsers.push(function(t) {
                    return u.$isEmpty(t) ? null : e.test(t) ? (t = r(t, p), "UTC" === m && t.setMinutes(t.getMinutes() - t.getTimezoneOffset()), t) : n
                }), u.$formatters.push(function(t) {
                    if (t && !w(t)) throw Do("datefmt", t);
                    if (h(t)) {
                        if ((p = t) && "UTC" === m) {
                            var e = 6e4 * p.getTimezoneOffset();
                            p = new Date(p.getTime() + e)
                        }
                        return f("date")(t, i, m)
                    }
                    return p = null, ""
                }), v(s.min) || s.ngMin) {
                var g;
                u.$validators.min = function(t) {
                    return !h(t) || d(g) || r(t) >= g
                }, s.$observe("min", function(t) {
                    g = $(t), u.$validate()
                })
            }
            if (v(s.max) || s.ngMax) {
                var y;
                u.$validators.max = function(t) {
                    return !h(t) || d(y) || r(t) <= y
                }, s.$observe("max", function(t) {
                    y = $(t), u.$validate()
                })
            }
        }
    }

    function $r(t, e, r, i) {
        (i.$$hasNativeValidators = m(e[0].validity)) && i.$parsers.push(function(t) {
            var r = e.prop("validity") || {};
            return r.badInput && !r.typeMismatch ? n : t
        })
    }

    function pr(t, e, n, i, o) {
        if (v(i)) {
            if (t = t(i), !t.constant) throw r("ngModel")("constexpr", n, i);
            return t(e)
        }
        return o
    }

    function dr(t, e) {
        return t = "ngClass" + t, ["$animate", function(n) {
            function r(t, e) {
                var n = [],
                    r = 0;
                t: for (; r < t.length; r++) {
                    for (var i = t[r], o = 0; o < e.length; o++)
                        if (i == e[o]) continue t;
                    n.push(i)
                }
                return n
            }

            function i(t) {
                if (!jr(t)) {
                    if (g(t)) return t.split(" ");
                    if (m(t)) {
                        var e = [];
                        return o(t, function(t, n) {
                            t && (e = e.concat(n.split(" ")))
                        }), e
                    }
                }
                return t
            }
            return {
                restrict: "AC",
                link: function(a, s, u) {
                    function c(t, e) {
                        var n = s.data("$classCounts") || {},
                            r = [];
                        return o(t, function(t) {
                            (e > 0 || n[t]) && (n[t] = (n[t] || 0) + e, n[t] === +(e > 0) && r.push(t))
                        }), s.data("$classCounts", n), r.join(" ")
                    }

                    function l(t) {
                        if (!0 === e || a.$index % 2 === e) {
                            var o = i(t || []);
                            if (f) {
                                if (!N(t, f)) {
                                    var l = i(f),
                                        h = r(o, l),
                                        o = r(l, o),
                                        h = c(h, 1),
                                        o = c(o, -1);
                                    h && h.length && n.addClass(s, h), o && o.length && n.removeClass(s, o)
                                }
                            } else {
                                var h = c(o, 1);
                                u.$addClass(h)
                            }
                        }
                        f = V(t)
                    }
                    var f;
                    a.$watch(u[t], l, !0), u.$observe("class", function() {
                        l(a.$eval(u[t]))
                    }), "ngClass" !== t && a.$watch("$index", function(n, r) {
                        var o = 1 & n;
                        if (o !== (1 & r)) {
                            var s = i(a.$eval(u[t]));
                            o === e ? (o = c(s, 1), u.$addClass(o)) : (o = c(s, -1), u.$removeClass(o))
                        }
                    })
                }
            }
        }]
    }

    function vr(t) {
        function e(t, e) {
            e && !a[t] ? (l.addClass(o, t), a[t] = !0) : !e && a[t] && (l.removeClass(o, t), a[t] = !1)
        }

        function r(t, n) {
            t = t ? "-" + Z(t, "-") : "", e(Oo + t, !0 === n), e(To + t, !1 === n)
        }
        var i = t.ctrl,
            o = t.$element,
            a = {},
            s = t.set,
            u = t.unset,
            c = t.parentForm,
            l = t.$animate;
        a[To] = !(a[Oo] = o.hasClass(Oo)), i.$setValidity = function(t, o, a) {
            o === n ? (i.$pending || (i.$pending = {}), s(i.$pending, t, a)) : (i.$pending && u(i.$pending, t, a), mr(i.$pending) && (i.$pending = n)), k(o) ? o ? (u(i.$error, t, a), s(i.$$success, t, a)) : (s(i.$error, t, a), u(i.$$success, t, a)) : (u(i.$error, t, a), u(i.$$success, t, a)), i.$pending ? (e(No, !0), i.$valid = i.$invalid = n, r("", null)) : (e(No, !1), i.$valid = mr(i.$error), i.$invalid = !i.$valid, r("", i.$valid)), o = i.$pending && i.$pending[t] ? n : i.$error[t] ? !1 : i.$$success[t] ? !0 : null, r(t, o), c.$setValidity(t, o, i)
        }
    }

    function mr(t) {
        if (t)
            for (var e in t) return !1;
        return !0
    }
    var gr, yr, wr, br, xr = /^\/(.+)\/([a-z]*)$/,
        Sr = function(t) {
            return g(t) ? t.toLowerCase() : t
        },
        Cr = Object.prototype.hasOwnProperty,
        kr = function(t) {
            return g(t) ? t.toUpperCase() : t
        },
        Ar = [].slice,
        Er = [].splice,
        Or = [].push,
        Tr = Object.prototype.toString,
        Mr = r("ng"),
        Vr = t.angular || (t.angular = {}),
        Nr = 0;
    gr = e.documentMode, h.$inject = [], $.$inject = [];
    var Dr, jr = Array.isArray,
        Pr = function(t) {
            return g(t) ? t.trim() : t
        },
        Rr = function(t) {
            return t.replace(/([-()\[\]{}+?*.$\^|,:#<!\\])/g, "\\$1").replace(/\x08/g, "\\x08")
        },
        qr = function() {
            if (v(qr.isActive_)) return qr.isActive_;
            var t = !(!e.querySelector("[ng-csp]") && !e.querySelector("[data-ng-csp]"));
            if (!t) try {
                new Function("")
            } catch (n) {
                t = !0
            }
            return qr.isActive_ = t
        },
        Ir = ["ng-", "data-ng-", "ng:", "x-ng-"],
        Ur = /[A-Z]/g,
        Fr = !1,
        Hr = 1,
        _r = 3,
        Lr = {
            full: "1.3.9",
            major: 1,
            minor: 3,
            dot: 9,
            codeName: "multidimensional-awareness"
        };
    ue.expando = "ng339";
    var Br = ue.cache = {},
        zr = 1;
    ue._data = function(t) {
        return this.cache[t[this.expando]] || {}
    };
    var Gr = /([\:\-\_]+(.))/g,
        Wr = /^moz([A-Z])/,
        Jr = {
            mouseleave: "mouseout",
            mouseenter: "mouseover"
        },
        Zr = r("jqLite"),
        Yr = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        Kr = /<|&#?\w+;/,
        Xr = /<([\w:]+)/,
        Qr = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        ti = {
            option: [1, '<select multiple="multiple">', "</select>"],
            thead: [1, "<table>", "</table>"],
            col: [2, "<table><colgroup>", "</colgroup></table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: [0, "", ""]
        };
    ti.optgroup = ti.option, ti.tbody = ti.tfoot = ti.colgroup = ti.caption = ti.thead, ti.th = ti.td;
    var ei = ue.prototype = {
            ready: function(n) {
                function r() {
                    i || (i = !0, n())
                }
                var i = !1;
                "complete" === e.readyState ? setTimeout(r) : (this.on("DOMContentLoaded", r), ue(t).on("load", r))
            },
            toString: function() {
                var t = [];
                return o(this, function(e) {
                    t.push("" + e)
                }), "[" + t.join(", ") + "]"
            },
            eq: function(t) {
                return yr(t >= 0 ? this[t] : this[this.length + t])
            },
            length: 0,
            push: Or,
            sort: [].sort,
            splice: [].splice
        },
        ni = {};
    o("multiple selected checked disabled readOnly required open".split(" "), function(t) {
        ni[Sr(t)] = t
    });
    var ri = {};
    o("input select option textarea button form details".split(" "), function(t) {
        ri[t] = !0
    });
    var ii = {
        ngMinlength: "minlength",
        ngMaxlength: "maxlength",
        ngMin: "min",
        ngMax: "max",
        ngPattern: "pattern"
    };
    o({
        data: pe,
        removeData: he
    }, function(t, e) {
        ue[e] = t
    }), o({
        data: pe,
        inheritedData: we,
        scope: function(t) {
            return yr.data(t, "$scope") || we(t.parentNode || t, ["$isolateScope", "$scope"])
        },
        isolateScope: function(t) {
            return yr.data(t, "$isolateScope") || yr.data(t, "$isolateScopeNoTemplate")
        },
        controller: ye,
        injector: function(t) {
            return we(t, "$injector")
        },
        removeAttr: function(t, e) {
            t.removeAttribute(e)
        },
        hasClass: de,
        css: function(t, e, n) {
            return e = oe(e), v(n) ? void(t.style[e] = n) : t.style[e]
        },
        attr: function(t, e, r) {
            var i = Sr(e);
            if (ni[i]) {
                if (!v(r)) return t[e] || (t.attributes.getNamedItem(e) || h).specified ? i : n;
                r ? (t[e] = !0, t.setAttribute(e, i)) : (t[e] = !1, t.removeAttribute(i))
            } else if (v(r)) t.setAttribute(e, r);
            else if (t.getAttribute) return t = t.getAttribute(e, 2), null === t ? n : t
        },
        prop: function(t, e, n) {
            return v(n) ? void(t[e] = n) : t[e]
        },
        text: function() {
            function t(t, e) {
                if (d(e)) {
                    var n = t.nodeType;
                    return n === Hr || n === _r ? t.textContent : ""
                }
                t.textContent = e
            }
            return t.$dv = "", t
        }(),
        val: function(t, e) {
            if (d(e)) {
                if (t.multiple && "select" === O(t)) {
                    var n = [];
                    return o(t.options, function(t) {
                        t.selected && n.push(t.value || t.text)
                    }), 0 === n.length ? null : n
                }
                return t.value
            }
            t.value = e
        },
        html: function(t, e) {
            return d(e) ? t.innerHTML : (le(t, !0), void(t.innerHTML = e))
        },
        empty: be
    }, function(t, e) {
        ue.prototype[e] = function(e, r) {
            var i, o, a = this.length;
            if (t !== be && (2 == t.length && t !== de && t !== ye ? e : r) === n) {
                if (m(e)) {
                    for (i = 0; a > i; i++)
                        if (t === pe) t(this[i], e);
                        else
                            for (o in e) t(this[i], o, e[o]);
                    return this
                }
                for (i = t.$dv, a = i === n ? Math.min(a, 1) : a, o = 0; a > o; o++) {
                    var s = t(this[o], e, r);
                    i = i ? i + s : s
                }
                return i
            }
            for (i = 0; a > i; i++) t(this[i], e, r);
            return this
        }
    }), o({
        removeData: he,
        on: function ia(t, e, n, r) {
            if (v(r)) throw Zr("onargs");
            if (ae(t)) {
                var i = $e(t, !0);
                r = i.events;
                var o = i.handle;
                o || (o = i.handle = Ae(t, r));
                for (var i = 0 <= e.indexOf(" ") ? e.split(" ") : [e], a = i.length; a--;) {
                    e = i[a];
                    var s = r[e];
                    s || (r[e] = [], "mouseenter" === e || "mouseleave" === e ? ia(t, Jr[e], function(t) {
                        var n = t.relatedTarget;
                        n && (n === this || this.contains(n)) || o(t, e)
                    }) : "$destroy" !== e && t.addEventListener(e, o, !1), s = r[e]), s.push(n)
                }
            }
        },
        off: fe,
        one: function(t, e, n) {
            t = yr(t), t.on(e, function r() {
                t.off(e, n), t.off(e, r)
            }), t.on(e, n)
        },
        replaceWith: function(t, e) {
            var n, r = t.parentNode;
            le(t), o(new ue(e), function(e) {
                n ? r.insertBefore(e, n.nextSibling) : r.replaceChild(e, t), n = e
            })
        },
        children: function(t) {
            var e = [];
            return o(t.childNodes, function(t) {
                t.nodeType === Hr && e.push(t)
            }), e
        },
        contents: function(t) {
            return t.contentDocument || t.childNodes || []
        },
        append: function(t, e) {
            var n = t.nodeType;
            if (n === Hr || 11 === n) {
                e = new ue(e);
                for (var n = 0, r = e.length; r > n; n++) t.appendChild(e[n])
            }
        },
        prepend: function(t, e) {
            if (t.nodeType === Hr) {
                var n = t.firstChild;
                o(new ue(e), function(e) {
                    t.insertBefore(e, n)
                })
            }
        },
        wrap: function(t, e) {
            e = yr(e).eq(0).clone()[0];
            var n = t.parentNode;
            n && n.replaceChild(e, t), e.appendChild(t)
        },
        remove: xe,
        detach: function(t) {
            xe(t, !0)
        },
        after: function(t, e) {
            var n = t,
                r = t.parentNode;
            e = new ue(e);
            for (var i = 0, o = e.length; o > i; i++) {
                var a = e[i];
                r.insertBefore(a, n.nextSibling), n = a
            }
        },
        addClass: me,
        removeClass: ve,
        toggleClass: function(t, e, n) {
            e && o(e.split(" "), function(e) {
                var r = n;
                d(r) && (r = !de(t, e)), (r ? me : ve)(t, e)
            })
        },
        parent: function(t) {
            return (t = t.parentNode) && 11 !== t.nodeType ? t : null
        },
        next: function(t) {
            return t.nextElementSibling
        },
        find: function(t, e) {
            return t.getElementsByTagName ? t.getElementsByTagName(e) : []
        },
        clone: ce,
        triggerHandler: function(t, e, n) {
            var r, i, a = e.type || e,
                s = $e(t);
            (s = (s = s && s.events) && s[a]) && (r = {
                preventDefault: function() {
                    this.defaultPrevented = !0
                },
                isDefaultPrevented: function() {
                    return !0 === this.defaultPrevented
                },
                stopImmediatePropagation: function() {
                    this.immediatePropagationStopped = !0
                },
                isImmediatePropagationStopped: function() {
                    return !0 === this.immediatePropagationStopped
                },
                stopPropagation: h,
                type: a,
                target: t
            }, e.type && (r = l(r, e)), e = V(s), i = n ? [r].concat(n) : [r], o(e, function(e) {
                r.isImmediatePropagationStopped() || e.apply(t, i)
            }))
        }
    }, function(t, e) {
        ue.prototype[e] = function(e, n, r) {
            for (var i, o = 0, a = this.length; a > o; o++) d(i) ? (i = t(this[o], e, n, r), v(i) && (i = yr(i))) : ge(i, t(this[o], e, n, r));
            return v(i) ? i : this
        }, ue.prototype.bind = ue.prototype.on, ue.prototype.unbind = ue.prototype.off
    }), Te.prototype = {
        put: function(t, e) {
            this[Oe(t, this.nextUid)] = e
        },
        get: function(t) {
            return this[Oe(t, this.nextUid)]
        },
        remove: function(t) {
            var e = this[t = Oe(t, this.nextUid)];
            return delete this[t], e
        }
    };
    var oi = /^function\s*[^\(]*\(\s*([^\)]*)\)/m,
        ai = /,/,
        si = /^\s*(_?)(\S+?)\1\s*$/,
        ui = /((\/\/.*$)|(\/\*[\s\S]*?\*\/))/gm,
        ci = r("$injector");
    Ne.$$annotate = Ve;
    var li = r("$animate"),
        fi = ["$provide", function(t) {
            this.$$selectors = {}, this.register = function(e, n) {
                var r = e + "-animation";
                if (e && "." != e.charAt(0)) throw li("notcsel", e);
                this.$$selectors[e.substr(1)] = r, t.factory(r, n)
            }, this.classNameFilter = function(t) {
                return 1 === arguments.length && (this.$$classNameFilter = t instanceof RegExp ? t : null), this.$$classNameFilter
            }, this.$get = ["$$q", "$$asyncCallback", "$rootScope", function(t, e, n) {
                function r(e) {
                    var r, i = t.defer();
                    return i.promise.$$cancelFn = function() {
                        r && r()
                    }, n.$$postDigest(function() {
                        r = e(function() {
                            i.resolve()
                        })
                    }), i.promise
                }

                function i(t, e) {
                    var n = [],
                        r = [],
                        i = ne();
                    return o((t.attr("class") || "").split(/\s+/), function(t) {
                        i[t] = !0
                    }), o(e, function(t, e) {
                        var o = i[e];
                        !1 === t && o ? r.push(e) : !0 !== t || o || n.push(e)
                    }), 0 < n.length + r.length && [n.length ? n : null, r.length ? r : null]
                }

                function a(t, e, n) {
                    for (var r = 0, i = e.length; i > r; ++r) t[e[r]] = n
                }

                function s() {
                    return c || (c = t.defer(), e(function() {
                        c.resolve(), c = null
                    })), c.promise
                }

                function u(t, e) {
                    if (Vr.isObject(e)) {
                        var n = l(e.from || {}, e.to || {});
                        t.css(n)
                    }
                }
                var c;
                return {
                    animate: function(t, e, n) {
                        return u(t, {
                            from: e,
                            to: n
                        }), s()
                    },
                    enter: function(t, e, n, r) {
                        return u(t, r), n ? n.after(t) : e.prepend(t), s()
                    },
                    leave: function(t) {
                        return t.remove(), s()
                    },
                    move: function(t, e, n, r) {
                        return this.enter(t, e, n, r)
                    },
                    addClass: function(t, e, n) {
                        return this.setClass(t, e, [], n)
                    },
                    $$addClassImmediately: function(t, e, n) {
                        return t = yr(t), e = g(e) ? e : jr(e) ? e.join(" ") : "", o(t, function(t) {
                            me(t, e)
                        }), u(t, n), s()
                    },
                    removeClass: function(t, e, n) {
                        return this.setClass(t, [], e, n)
                    },
                    $$removeClassImmediately: function(t, e, n) {
                        return t = yr(t), e = g(e) ? e : jr(e) ? e.join(" ") : "", o(t, function(t) {
                            ve(t, e)
                        }), u(t, n), s()
                    },
                    setClass: function(t, e, n, o) {
                        var s = this,
                            u = !1;
                        t = yr(t);
                        var c = t.data("$$animateClasses");
                        return c ? o && c.options && (c.options = Vr.extend(c.options || {}, o)) : (c = {
                            classes: {},
                            options: o
                        }, u = !0), o = c.classes, e = jr(e) ? e : e.split(" "), n = jr(n) ? n : n.split(" "), a(o, e, !0), a(o, n, !1), u && (c.promise = r(function(e) {
                            var n = t.data("$$animateClasses");
                            if (t.removeData("$$animateClasses"), n) {
                                var r = i(t, n.classes);
                                r && s.$$setClassImmediately(t, r[0], r[1], n.options)
                            }
                            e()
                        }), t.data("$$animateClasses", c)), c.promise
                    },
                    $$setClassImmediately: function(t, e, n, r) {
                        return e && this.$$addClassImmediately(t, e), n && this.$$removeClassImmediately(t, n), u(t, r), s()
                    },
                    enabled: h,
                    cancel: h
                }
            }]
        }],
        hi = r("$compile");
    Ue.$inject = ["$provide", "$$sanitizeUriProvider"];
    var $i = /^((?:x|data)[\:\-_])/i,
        pi = "application/json",
        di = {
            "Content-Type": pi + ";charset=utf-8"
        },
        vi = /^\[|^\{(?!\{)/,
        mi = {
            "[": /]$/,
            "{": /}$/
        },
        gi = /^\)\]\}',?\n/,
        yi = r("$interpolate"),
        wi = /^([^\?#]*)(\?([^#]*))?(#(.*))?$/,
        bi = {
            http: 80,
            https: 443,
            ftp: 21
        },
        xi = r("$location"),
        Si = {
            $$html5: !1,
            $$replace: !1,
            absUrl: pn("$$absUrl"),
            url: function(t) {
                if (d(t)) return this.$$url;
                var e = wi.exec(t);
                return (e[1] || "" === t) && this.path(decodeURIComponent(e[1])), (e[2] || e[1] || "" === t) && this.search(e[3] || ""), this.hash(e[5] || ""), this
            },
            protocol: pn("$$protocol"),
            host: pn("$$host"),
            port: pn("$$port"),
            path: dn("$$path", function(t) {
                return t = null !== t ? t.toString() : "", "/" == t.charAt(0) ? t : "/" + t
            }),
            search: function(t, e) {
                switch (arguments.length) {
                    case 0:
                        return this.$$search;
                    case 1:
                        if (g(t) || y(t)) t = t.toString(), this.$$search = F(t);
                        else {
                            if (!m(t)) throw xi("isrcharg");
                            t = M(t, {}), o(t, function(e, n) {
                                null == e && delete t[n]
                            }), this.$$search = t
                        }
                        break;
                    default:
                        d(e) || null === e ? delete this.$$search[t] : this.$$search[t] = e
                }
                return this.$$compose(), this
            },
            hash: dn("$$hash", function(t) {
                return null !== t ? t.toString() : ""
            }),
            replace: function() {
                return this.$$replace = !0, this
            }
        };
    o([$n, hn, fn], function(t) {
        t.prototype = Object.create(Si), t.prototype.state = function(e) {
            if (!arguments.length) return this.$$state;
            if (t !== fn || !this.$$html5) throw xi("nostate");
            return this.$$state = d(e) ? null : e, this
        }
    });
    var Ci = r("$parse"),
        ki = Function.prototype.call,
        Ai = Function.prototype.apply,
        Ei = Function.prototype.bind,
        Oi = ne();
    o({
        "null": function() {
            return null
        },
        "true": function() {
            return !0
        },
        "false": function() {
            return !1
        },
        undefined: function() {}
    }, function(t, e) {
        t.constant = t.literal = t.sharedGetter = !0, Oi[e] = t
    }), Oi["this"] = function(t) {
        return t
    }, Oi["this"].sharedGetter = !0;
    var Ti = l(ne(), {
            "+": function(t, e, r, i) {
                return r = r(t, e), i = i(t, e), v(r) ? v(i) ? r + i : r : v(i) ? i : n
            },
            "-": function(t, e, n, r) {
                return n = n(t, e), r = r(t, e), (v(n) ? n : 0) - (v(r) ? r : 0)
            },
            "*": function(t, e, n, r) {
                return n(t, e) * r(t, e)
            },
            "/": function(t, e, n, r) {
                return n(t, e) / r(t, e)
            },
            "%": function(t, e, n, r) {
                return n(t, e) % r(t, e)
            },
            "===": function(t, e, n, r) {
                return n(t, e) === r(t, e)
            },
            "!==": function(t, e, n, r) {
                return n(t, e) !== r(t, e)
            },
            "==": function(t, e, n, r) {
                return n(t, e) == r(t, e)
            },
            "!=": function(t, e, n, r) {
                return n(t, e) != r(t, e)
            },
            "<": function(t, e, n, r) {
                return n(t, e) < r(t, e)
            },
            ">": function(t, e, n, r) {
                return n(t, e) > r(t, e)
            },
            "<=": function(t, e, n, r) {
                return n(t, e) <= r(t, e)
            },
            ">=": function(t, e, n, r) {
                return n(t, e) >= r(t, e)
            },
            "&&": function(t, e, n, r) {
                return n(t, e) && r(t, e)
            },
            "||": function(t, e, n, r) {
                return n(t, e) || r(t, e)
            },
            "!": function(t, e, n) {
                return !n(t, e)
            },
            "=": !0,
            "|": !0
        }),
        Mi = {
            n: "\n",
            f: "\f",
            r: "\r",
            t: "	",
            v: "",
            "'": "'",
            '"': '"'
        },
        Vi = function(t) {
            this.options = t
        };
    Vi.prototype = {
        constructor: Vi,
        lex: function(t) {
            for (this.text = t, this.index = 0, this.tokens = []; this.index < this.text.length;)
                if (t = this.text.charAt(this.index), '"' === t || "'" === t) this.readString(t);
                else if (this.isNumber(t) || "." === t && this.isNumber(this.peek())) this.readNumber();
            else if (this.isIdent(t)) this.readIdent();
            else if (this.is(t, "(){}[].,;:?")) this.tokens.push({
                index: this.index,
                text: t
            }), this.index++;
            else if (this.isWhitespace(t)) this.index++;
            else {
                var e = t + this.peek(),
                    n = e + this.peek(2),
                    r = Ti[e],
                    i = Ti[n];
                Ti[t] || r || i ? (t = i ? n : r ? e : t, this.tokens.push({
                    index: this.index,
                    text: t,
                    operator: !0
                }), this.index += t.length) : this.throwError("Unexpected next character ", this.index, this.index + 1)
            }
            return this.tokens
        },
        is: function(t, e) {
            return -1 !== e.indexOf(t)
        },
        peek: function(t) {
            return t = t || 1, this.index + t < this.text.length ? this.text.charAt(this.index + t) : !1
        },
        isNumber: function(t) {
            return t >= "0" && "9" >= t && "string" == typeof t
        },
        isWhitespace: function(t) {
            return " " === t || "\r" === t || "	" === t || "\n" === t || "" === t || " " === t
        },
        isIdent: function(t) {
            return t >= "a" && "z" >= t || t >= "A" && "Z" >= t || "_" === t || "$" === t
        },
        isExpOperator: function(t) {
            return "-" === t || "+" === t || this.isNumber(t)
        },
        throwError: function(t, e, n) {
            throw n = n || this.index, e = v(e) ? "s " + e + "-" + this.index + " [" + this.text.substring(e, n) + "]" : " " + n, Ci("lexerr", t, e, this.text)
        },
        readNumber: function() {
            for (var t = "", e = this.index; this.index < this.text.length;) {
                var n = Sr(this.text.charAt(this.index));
                if ("." == n || this.isNumber(n)) t += n;
                else {
                    var r = this.peek();
                    if ("e" == n && this.isExpOperator(r)) t += n;
                    else if (this.isExpOperator(n) && r && this.isNumber(r) && "e" == t.charAt(t.length - 1)) t += n;
                    else {
                        if (!this.isExpOperator(n) || r && this.isNumber(r) || "e" != t.charAt(t.length - 1)) break;
                        this.throwError("Invalid exponent")
                    }
                }
                this.index++
            }
            this.tokens.push({
                index: e,
                text: t,
                constant: !0,
                value: Number(t)
            })
        },
        readIdent: function() {
            for (var t = this.index; this.index < this.text.length;) {
                var e = this.text.charAt(this.index);
                if (!this.isIdent(e) && !this.isNumber(e)) break;
                this.index++
            }
            this.tokens.push({
                index: t,
                text: this.text.slice(t, this.index),
                identifier: !0
            })
        },
        readString: function(t) {
            var e = this.index;
            this.index++;
            for (var n = "", r = t, i = !1; this.index < this.text.length;) {
                var o = this.text.charAt(this.index),
                    r = r + o;
                if (i) "u" === o ? (i = this.text.substring(this.index + 1, this.index + 5), i.match(/[\da-f]{4}/i) || this.throwError("Invalid unicode escape [\\u" + i + "]"), this.index += 4, n += String.fromCharCode(parseInt(i, 16))) : n += Mi[o] || o, i = !1;
                else if ("\\" === o) i = !0;
                else {
                    if (o === t) return this.index++, void this.tokens.push({
                        index: e,
                        text: r,
                        constant: !0,
                        value: n
                    });
                    n += o
                }
                this.index++
            }
            this.throwError("Unterminated quote", e)
        }
    };
    var Ni = function(t, e, n) {
        this.lexer = t, this.$filter = e, this.options = n
    };
    Ni.ZERO = l(function() {
        return 0
    }, {
        sharedGetter: !0,
        constant: !0
    }), Ni.prototype = {
        constructor: Ni,
        parse: function(t) {
            return this.text = t, this.tokens = this.lexer.lex(t), t = this.statements(), 0 !== this.tokens.length && this.throwError("is an unexpected token", this.tokens[0]), t.literal = !!t.literal, t.constant = !!t.constant, t
        },
        primary: function() {
            var t;
            this.expect("(") ? (t = this.filterChain(), this.consume(")")) : this.expect("[") ? t = this.arrayDeclaration() : this.expect("{") ? t = this.object() : this.peek().identifier && this.peek().text in Oi ? t = Oi[this.consume().text] : this.peek().identifier ? t = this.identifier() : this.peek().constant ? t = this.constant() : this.throwError("not a primary expression", this.peek());
            for (var e, n; e = this.expect("(", "[", ".");) "(" === e.text ? (t = this.functionCall(t, n), n = null) : "[" === e.text ? (n = t, t = this.objectIndex(t)) : "." === e.text ? (n = t, t = this.fieldAccess(t)) : this.throwError("IMPOSSIBLE");
            return t
        },
        throwError: function(t, e) {
            throw Ci("syntax", e.text, t, e.index + 1, this.text, this.text.substring(e.index))
        },
        peekToken: function() {
            if (0 === this.tokens.length) throw Ci("ueoe", this.text);
            return this.tokens[0]
        },
        peek: function(t, e, n, r) {
            return this.peekAhead(0, t, e, n, r)
        },
        peekAhead: function(t, e, n, r, i) {
            if (this.tokens.length > t) {
                t = this.tokens[t];
                var o = t.text;
                if (o === e || o === n || o === r || o === i || !(e || n || r || i)) return t
            }
            return !1
        },
        expect: function(t, e, n, r) {
            return (t = this.peek(t, e, n, r)) ? (this.tokens.shift(), t) : !1
        },
        consume: function(t) {
            if (0 === this.tokens.length) throw Ci("ueoe", this.text);
            var e = this.expect(t);
            return e || this.throwError("is unexpected, expecting [" + t + "]", this.peek()), e
        },
        unaryFn: function(t, e) {
            var n = Ti[t];
            return l(function(t, r) {
                return n(t, r, e)
            }, {
                constant: e.constant,
                inputs: [e]
            })
        },
        binaryFn: function(t, e, n, r) {
            var i = Ti[e];
            return l(function(e, r) {
                return i(e, r, t, n)
            }, {
                constant: t.constant && n.constant,
                inputs: !r && [t, n]
            })
        },
        identifier: function() {
            for (var t = this.consume().text; this.peek(".") && this.peekAhead(1).identifier && !this.peekAhead(2, "(");) t += this.consume().text + this.consume().text;
            return kn(t, this.options, this.text)
        },
        constant: function() {
            var t = this.consume().value;
            return l(function() {
                return t
            }, {
                constant: !0,
                literal: !0
            })
        },
        statements: function() {
            for (var t = [];;)
                if (0 < this.tokens.length && !this.peek("}", ")", ";", "]") && t.push(this.filterChain()), !this.expect(";")) return 1 === t.length ? t[0] : function(e, n) {
                    for (var r, i = 0, o = t.length; o > i; i++) r = t[i](e, n);
                    return r
                }
        },
        filterChain: function() {
            for (var t = this.expression(); this.expect("|");) t = this.filter(t);
            return t
        },
        filter: function(t) {
            var e, r, i = this.$filter(this.consume().text);
            if (this.peek(":"))
                for (e = [], r = []; this.expect(":");) e.push(this.expression());
            var o = [t].concat(e || []);
            return l(function(o, a) {
                var s = t(o, a);
                if (r) {
                    for (r[0] = s, s = e.length; s--;) r[s + 1] = e[s](o, a);
                    return i.apply(n, r)
                }
                return i(s)
            }, {
                constant: !i.$stateful && o.every(wn),
                inputs: !i.$stateful && o
            })
        },
        expression: function() {
            return this.assignment()
        },
        assignment: function() {
            var t, e, n = this.ternary();
            return (e = this.expect("=")) ? (n.assign || this.throwError("implies assignment but [" + this.text.substring(0, e.index) + "] can not be assigned to", e), t = this.ternary(), l(function(e, r) {
                return n.assign(e, t(e, r), r)
            }, {
                inputs: [n, t]
            })) : n
        },
        ternary: function() {
            var t, e = this.logicalOR();
            if (this.expect("?") && (t = this.assignment(), this.consume(":"))) {
                var n = this.assignment();
                return l(function(r, i) {
                    return e(r, i) ? t(r, i) : n(r, i)
                }, {
                    constant: e.constant && t.constant && n.constant
                })
            }
            return e
        },
        logicalOR: function() {
            for (var t, e = this.logicalAND(); t = this.expect("||");) e = this.binaryFn(e, t.text, this.logicalAND(), !0);
            return e
        },
        logicalAND: function() {
            for (var t, e = this.equality(); t = this.expect("&&");) e = this.binaryFn(e, t.text, this.equality(), !0);
            return e
        },
        equality: function() {
            for (var t, e = this.relational(); t = this.expect("==", "!=", "===", "!==");) e = this.binaryFn(e, t.text, this.relational());
            return e
        },
        relational: function() {
            for (var t, e = this.additive(); t = this.expect("<", ">", "<=", ">=");) e = this.binaryFn(e, t.text, this.additive());
            return e
        },
        additive: function() {
            for (var t, e = this.multiplicative(); t = this.expect("+", "-");) e = this.binaryFn(e, t.text, this.multiplicative());
            return e
        },
        multiplicative: function() {
            for (var t, e = this.unary(); t = this.expect("*", "/", "%");) e = this.binaryFn(e, t.text, this.unary());
            return e
        },
        unary: function() {
            var t;
            return this.expect("+") ? this.primary() : (t = this.expect("-")) ? this.binaryFn(Ni.ZERO, t.text, this.unary()) : (t = this.expect("!")) ? this.unaryFn(t.text, this.unary()) : this.primary()
        },
        fieldAccess: function(t) {
            var e = this.identifier();
            return l(function(r, i, o) {
                return r = o || t(r, i), null == r ? n : e(r)
            }, {
                assign: function(n, r, i) {
                    var o = t(n, i);
                    return o || t.assign(n, o = {}, i), e.assign(o, r)
                }
            })
        },
        objectIndex: function(t) {
            var e = this.text,
                r = this.expression();
            return this.consume("]"), l(function(i, o) {
                var a = t(i, o),
                    s = r(i, o);
                return gn(s, e), a ? yn(a[s], e) : n
            }, {
                assign: function(n, i, o) {
                    var a = gn(r(n, o), e),
                        s = yn(t(n, o), e);
                    return s || t.assign(n, s = {}, o), s[a] = i
                }
            })
        },
        functionCall: function(t, e) {
            var r = [];
            if (")" !== this.peekToken().text)
                do r.push(this.expression()); while (this.expect(","));
            this.consume(")");
            var i = this.text,
                o = r.length ? [] : null;
            return function(a, s) {
                var u = e ? e(a, s) : v(e) ? n : a,
                    c = t(a, s, u) || h;
                if (o)
                    for (var l = r.length; l--;) o[l] = yn(r[l](a, s), i);
                if (yn(u, i), c) {
                    if (c.constructor === c) throw Ci("isecfn", i);
                    if (c === ki || c === Ai || c === Ei) throw Ci("isecff", i)
                }
                return u = c.apply ? c.apply(u, o) : c(o[0], o[1], o[2], o[3], o[4]), yn(u, i)
            }
        },
        arrayDeclaration: function() {
            var t = [];
            if ("]" !== this.peekToken().text)
                do {
                    if (this.peek("]")) break;
                    t.push(this.expression())
                } while (this.expect(","));
            return this.consume("]"), l(function(e, n) {
                for (var r = [], i = 0, o = t.length; o > i; i++) r.push(t[i](e, n));
                return r
            }, {
                literal: !0,
                constant: t.every(wn),
                inputs: t
            })
        },
        object: function() {
            var t = [],
                e = [];
            if ("}" !== this.peekToken().text)
                do {
                    if (this.peek("}")) break;
                    var n = this.consume();
                    n.constant ? t.push(n.value) : n.identifier ? t.push(n.text) : this.throwError("invalid key", n), this.consume(":"), e.push(this.expression())
                } while (this.expect(","));
            return this.consume("}"), l(function(n, r) {
                for (var i = {}, o = 0, a = e.length; a > o; o++) i[t[o]] = e[o](n, r);
                return i
            }, {
                literal: !0,
                constant: e.every(wn),
                inputs: e
            })
        }
    };
    var Di = ne(),
        ji = ne(),
        Pi = Object.prototype.valueOf,
        Ri = r("$sce"),
        qi = {
            HTML: "html",
            CSS: "css",
            URL: "url",
            RESOURCE_URL: "resourceUrl",
            JS: "js"
        },
        hi = r("$compile"),
        Ii = e.createElement("a"),
        Ui = _n(t.location.href);
    zn.$inject = ["$provide"], Zn.$inject = ["$locale"], Yn.$inject = ["$locale"];
    var Fi = ".",
        Hi = {
            yyyy: Qn("FullYear", 4),
            yy: Qn("FullYear", 2, 0, !0),
            y: Qn("FullYear", 1),
            MMMM: tr("Month"),
            MMM: tr("Month", !0),
            MM: Qn("Month", 2, 1),
            M: Qn("Month", 1, 1),
            dd: Qn("Date", 2),
            d: Qn("Date", 1),
            HH: Qn("Hours", 2),
            H: Qn("Hours", 1),
            hh: Qn("Hours", 2, -12),
            h: Qn("Hours", 1, -12),
            mm: Qn("Minutes", 2),
            m: Qn("Minutes", 1),
            ss: Qn("Seconds", 2),
            s: Qn("Seconds", 1),
            sss: Qn("Milliseconds", 3),
            EEEE: tr("Day"),
            EEE: tr("Day", !0),
            a: function(t, e) {
                return 12 > t.getHours() ? e.AMPMS[0] : e.AMPMS[1]
            },
            Z: function(t) {
                return t = -1 * t.getTimezoneOffset(), t = (t >= 0 ? "+" : "") + (Xn(Math[t > 0 ? "floor" : "ceil"](t / 60), 2) + Xn(Math.abs(t % 60), 2))
            },
            ww: nr(2),
            w: nr(1)
        },
        _i = /((?:[^yMdHhmsaZEw']+)|(?:'(?:[^']|'')*')|(?:E+|y+|M+|d+|H+|h+|m+|s+|a|Z|w+))(.*)/,
        Li = /^\-?\d+$/;
    rr.$inject = ["$locale"];
    var Bi = p(Sr),
        zi = p(kr);
    ar.$inject = ["$parse"];
    var Gi = p({
            restrict: "E",
            compile: function(t, e) {
                return e.href || e.xlinkHref || e.name ? void 0 : function(t, e) {
                    var n = "[object SVGAnimatedString]" === Tr.call(e.prop("href")) ? "xlink:href" : "href";
                    e.on("click", function(t) {
                        e.attr(n) || t.preventDefault()
                    })
                }
            }
        }),
        Wi = {};
    o(ni, function(t, e) {
        if ("multiple" != t) {
            var n = Fe("ng-" + e);
            Wi[n] = function() {
                return {
                    restrict: "A",
                    priority: 100,
                    link: function(t, r, i) {
                        t.$watch(i[n], function(t) {
                            i.$set(e, !!t)
                        })
                    }
                }
            }
        }
    }), o(ii, function(t, e) {
        Wi[e] = function() {
            return {
                priority: 100,
                link: function(t, n, r) {
                    return "ngPattern" === e && "/" == r.ngPattern.charAt(0) && (n = r.ngPattern.match(xr)) ? void r.$set("ngPattern", new RegExp(n[1], n[2])) : void t.$watch(r[e], function(t) {
                        r.$set(e, t)
                    })
                }
            }
        }
    }), o(["src", "srcset", "href"], function(t) {
        var e = Fe("ng-" + t);
        Wi[e] = function() {
            return {
                priority: 99,
                link: function(n, r, i) {
                    var o = t,
                        a = t;
                    "href" === t && "[object SVGAnimatedString]" === Tr.call(r.prop("href")) && (a = "xlinkHref", i.$attr[a] = "xlink:href", o = null), i.$observe(e, function(e) {
                        e ? (i.$set(a, e), gr && o && r.prop(o, i[a])) : "href" === t && i.$set(a, null)
                    })
                }
            }
        }
    });
    var Ji = {
        $addControl: h,
        $$renameControl: function(t, e) {
            t.$name = e
        },
        $removeControl: h,
        $setValidity: h,
        $setDirty: h,
        $setPristine: h,
        $setSubmitted: h
    };
    ur.$inject = ["$element", "$attrs", "$scope", "$animate", "$interpolate"];
    var Zi = function(t) {
            return ["$timeout", function(e) {
                return {
                    name: "form",
                    restrict: t ? "EAC" : "E",
                    controller: ur,
                    compile: function(t) {
                        return t.addClass(Mo).addClass(Oo), {
                            pre: function(t, r, i, o) {
                                if (!("action" in i)) {
                                    var a = function(e) {
                                        t.$apply(function() {
                                            o.$commitViewValue(), o.$setSubmitted()
                                        }), e.preventDefault()
                                    };
                                    r[0].addEventListener("submit", a, !1), r.on("$destroy", function() {
                                        e(function() {
                                            r[0].removeEventListener("submit", a, !1)
                                        }, 0, !1)
                                    })
                                }
                                var s = o.$$parentForm,
                                    u = o.$name;
                                u && (bn(t, null, u, o, u), i.$observe(i.name ? "name" : "ngForm", function(e) {
                                    u !== e && (bn(t, null, u, n, u), u = e, bn(t, null, u, o, u), s.$$renameControl(o, u))
                                })), r.on("$destroy", function() {
                                    s.$removeControl(o), u && bn(t, null, u, n, u), l(o, Ji)
                                })
                            }
                        }
                    }
                }
            }]
        },
        Yi = Zi(),
        Ki = Zi(!0),
        Xi = /\d{4}-[01]\d-[0-3]\dT[0-2]\d:[0-5]\d:[0-5]\d\.\d+([+-][0-2]\d:[0-5]\d|Z)/,
        Qi = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/,
        to = /^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i,
        eo = /^\s*(\-|\+)?(\d+|(\d*(\.\d*)))\s*$/,
        no = /^(\d{4})-(\d{2})-(\d{2})$/,
        ro = /^(\d{4})-(\d\d)-(\d\d)T(\d\d):(\d\d)(?::(\d\d)(\.\d{1,3})?)?$/,
        io = /^(\d{4})-W(\d\d)$/,
        oo = /^(\d{4})-(\d\d)$/,
        ao = /^(\d\d):(\d\d)(?::(\d\d)(\.\d{1,3})?)?$/,
        so = {
            text: function(t, e, n, r, i, o) {
                lr(t, e, n, r, i, o), cr(r)
            },
            date: hr("date", no, fr(no, ["yyyy", "MM", "dd"]), "yyyy-MM-dd"),
            "datetime-local": hr("datetimelocal", ro, fr(ro, "yyyy MM dd HH mm ss sss".split(" ")), "yyyy-MM-ddTHH:mm:ss.sss"),
            time: hr("time", ao, fr(ao, ["HH", "mm", "ss", "sss"]), "HH:mm:ss.sss"),
            week: hr("week", io, function(t, e) {
                if (w(t)) return t;
                if (g(t)) {
                    io.lastIndex = 0;
                    var n = io.exec(t);
                    if (n) {
                        var r = +n[1],
                            i = +n[2],
                            o = n = 0,
                            a = 0,
                            s = 0,
                            u = er(r),
                            i = 7 * (i - 1);
                        return e && (n = e.getHours(), o = e.getMinutes(), a = e.getSeconds(), s = e.getMilliseconds()), new Date(r, 0, u.getDate() + i, n, o, a, s)
                    }
                }
                return 0 / 0
            }, "yyyy-Www"),
            month: hr("month", oo, fr(oo, ["yyyy", "MM"]), "yyyy-MM"),
            number: function(t, e, r, i, o, a) {
                if ($r(t, e, r, i), lr(t, e, r, i, o, a), i.$$parserName = "number", i.$parsers.push(function(t) {
                        return i.$isEmpty(t) ? null : eo.test(t) ? parseFloat(t) : n
                    }), i.$formatters.push(function(t) {
                        if (!i.$isEmpty(t)) {
                            if (!y(t)) throw Do("numfmt", t);
                            t = t.toString()
                        }
                        return t
                    }), r.min || r.ngMin) {
                    var s;
                    i.$validators.min = function(t) {
                        return i.$isEmpty(t) || d(s) || t >= s
                    }, r.$observe("min", function(t) {
                        v(t) && !y(t) && (t = parseFloat(t, 10)), s = y(t) && !isNaN(t) ? t : n, i.$validate()
                    })
                }
                if (r.max || r.ngMax) {
                    var u;
                    i.$validators.max = function(t) {
                        return i.$isEmpty(t) || d(u) || u >= t
                    }, r.$observe("max", function(t) {
                        v(t) && !y(t) && (t = parseFloat(t, 10)), u = y(t) && !isNaN(t) ? t : n, i.$validate()
                    })
                }
            },
            url: function(t, e, n, r, i, o) {
                lr(t, e, n, r, i, o), cr(r), r.$$parserName = "url", r.$validators.url = function(t, e) {
                    var n = t || e;
                    return r.$isEmpty(n) || Qi.test(n)
                }
            },
            email: function(t, e, n, r, i, o) {
                lr(t, e, n, r, i, o), cr(r), r.$$parserName = "email", r.$validators.email = function(t, e) {
                    var n = t || e;
                    return r.$isEmpty(n) || to.test(n)
                }
            },
            radio: function(t, e, n, r) {
                d(n.name) && e.attr("name", ++Nr), e.on("click", function(t) {
                    e[0].checked && r.$setViewValue(n.value, t && t.type)
                }), r.$render = function() {
                    e[0].checked = n.value == r.$viewValue
                }, n.$observe("value", r.$render)
            },
            checkbox: function(t, e, n, r, i, o, a, s) {
                var u = pr(s, t, "ngTrueValue", n.ngTrueValue, !0),
                    c = pr(s, t, "ngFalseValue", n.ngFalseValue, !1);
                e.on("click", function(t) {
                    r.$setViewValue(e[0].checked, t && t.type)
                }), r.$render = function() {
                    e[0].checked = r.$viewValue
                }, r.$isEmpty = function(t) {
                    return !1 === t
                }, r.$formatters.push(function(t) {
                    return N(t, u)
                }), r.$parsers.push(function(t) {
                    return t ? u : c
                })
            },
            hidden: h,
            button: h,
            submit: h,
            reset: h,
            file: h
        },
        uo = ["$browser", "$sniffer", "$filter", "$parse", function(t, e, n, r) {
            return {
                restrict: "E",
                require: ["?ngModel"],
                link: {
                    pre: function(i, o, a, s) {
                        s[0] && (so[Sr(a.type)] || so.text)(i, o, a, s[0], e, t, n, r)
                    }
                }
            }
        }],
        co = /^(true|false|\d+)$/,
        lo = function() {
            return {
                restrict: "A",
                priority: 100,
                compile: function(t, e) {
                    return co.test(e.ngValue) ? function(t, e, n) {
                        n.$set("value", t.$eval(n.ngValue))
                    } : function(t, e, n) {
                        t.$watch(n.ngValue, function(t) {
                            n.$set("value", t)
                        })
                    }
                }
            }
        },
        fo = ["$compile", function(t) {
            return {
                restrict: "AC",
                compile: function(e) {
                    return t.$$addBindingClass(e),
                        function(e, r, i) {
                            t.$$addBindingInfo(r, i.ngBind), r = r[0], e.$watch(i.ngBind, function(t) {
                                r.textContent = t === n ? "" : t
                            })
                        }
                }
            }
        }],
        ho = ["$interpolate", "$compile", function(t, e) {
            return {
                compile: function(r) {
                    return e.$$addBindingClass(r),
                        function(r, i, o) {
                            r = t(i.attr(o.$attr.ngBindTemplate)), e.$$addBindingInfo(i, r.expressions), i = i[0], o.$observe("ngBindTemplate", function(t) {
                                i.textContent = t === n ? "" : t
                            })
                        }
                }
            }
        }],
        $o = ["$sce", "$parse", "$compile", function(t, e, n) {
            return {
                restrict: "A",
                compile: function(r, i) {
                    var o = e(i.ngBindHtml),
                        a = e(i.ngBindHtml, function(t) {
                            return (t || "").toString()
                        });
                    return n.$$addBindingClass(r),
                        function(e, r, i) {
                            n.$$addBindingInfo(r, i.ngBindHtml), e.$watch(a, function() {
                                r.html(t.getTrustedHtml(o(e)) || "")
                            })
                        }
                }
            }
        }],
        po = p({
            restrict: "A",
            require: "ngModel",
            link: function(t, e, n, r) {
                r.$viewChangeListeners.push(function() {
                    t.$eval(n.ngChange)
                })
            }
        }),
        vo = dr("", !0),
        mo = dr("Odd", 0),
        go = dr("Even", 1),
        yo = sr({
            compile: function(t, e) {
                e.$set("ngCloak", n), t.removeClass("ng-cloak")
            }
        }),
        wo = [function() {
            return {
                restrict: "A",
                scope: !0,
                controller: "@",
                priority: 500
            }
        }],
        bo = {},
        xo = {
            blur: !0,
            focus: !0
        };
    o("click dblclick mousedown mouseup mouseover mouseout mousemove mouseenter mouseleave keydown keyup keypress submit focus blur copy cut paste".split(" "), function(t) {
        var e = Fe("ng-" + t);
        bo[e] = ["$parse", "$rootScope", function(n, r) {
            return {
                restrict: "A",
                compile: function(i, o) {
                    var a = n(o[e], null, !0);
                    return function(e, n) {
                        n.on(t, function(n) {
                            var i = function() {
                                a(e, {
                                    $event: n
                                })
                            };
                            xo[t] && r.$$phase ? e.$evalAsync(i) : e.$apply(i)
                        })
                    }
                }
            }
        }]
    });
    var So = ["$animate", function(t) {
            return {
                multiElement: !0,
                transclude: "element",
                priority: 600,
                terminal: !0,
                restrict: "A",
                $$tlb: !0,
                link: function(n, r, i, o, a) {
                    var s, u, c;
                    n.$watch(i.ngIf, function(n) {
                        n ? u || a(function(n, o) {
                            u = o, n[n.length++] = e.createComment(" end ngIf: " + i.ngIf + " "), s = {
                                clone: n
                            }, t.enter(n, r.parent(), r)
                        }) : (c && (c.remove(), c = null), u && (u.$destroy(), u = null), s && (c = ee(s.clone), t.leave(c).then(function() {
                            c = null
                        }), s = null))
                    })
                }
            }
        }],
        Co = ["$templateRequest", "$anchorScroll", "$animate", "$sce", function(t, e, n, r) {
            return {
                restrict: "ECA",
                priority: 400,
                terminal: !0,
                transclude: "element",
                controller: Vr.noop,
                compile: function(i, o) {
                    var a = o.ngInclude || o.src,
                        s = o.onload || "",
                        u = o.autoscroll;
                    return function(i, o, c, l, f) {
                        var h, $, p, d = 0,
                            m = function() {
                                $ && ($.remove(), $ = null), h && (h.$destroy(), h = null), p && (n.leave(p).then(function() {
                                    $ = null
                                }), $ = p, p = null)
                            };
                        i.$watch(r.parseAsResourceUrl(a), function(r) {
                            var a = function() {
                                    !v(u) || u && !i.$eval(u) || e()
                                },
                                c = ++d;
                            r ? (t(r, !0).then(function(t) {
                                if (c === d) {
                                    var e = i.$new();
                                    l.template = t, t = f(e, function(t) {
                                        m(), n.enter(t, null, o).then(a)
                                    }), h = e, p = t, h.$emit("$includeContentLoaded", r), i.$eval(s)
                                }
                            }, function() {
                                c === d && (m(), i.$emit("$includeContentError", r))
                            }), i.$emit("$includeContentRequested", r)) : (m(), l.template = null)
                        })
                    }
                }
            }
        }],
        ko = ["$compile", function(t) {
            return {
                restrict: "ECA",
                priority: -400,
                require: "ngInclude",
                link: function(n, r, i, o) {
                    /SVG/.test(r[0].toString()) ? (r.empty(), t(se(o.template, e).childNodes)(n, function(t) {
                        r.append(t)
                    }, {
                        futureParentElement: r
                    })) : (r.html(o.template), t(r.contents())(n))
                }
            }
        }],
        Ao = sr({
            priority: 450,
            compile: function() {
                return {
                    pre: function(t, e, n) {
                        t.$eval(n.ngInit)
                    }
                }
            }
        }),
        Eo = function() {
            return {
                restrict: "A",
                priority: 100,
                require: "ngModel",
                link: function(t, e, r, i) {
                    var a = e.attr(r.$attr.ngList) || ", ",
                        s = "false" !== r.ngTrim,
                        u = s ? Pr(a) : a;
                    i.$parsers.push(function(t) {
                        if (!d(t)) {
                            var e = [];
                            return t && o(t.split(u), function(t) {
                                t && e.push(s ? Pr(t) : t)
                            }), e
                        }
                    }), i.$formatters.push(function(t) {
                        return jr(t) ? t.join(a) : n
                    }), i.$isEmpty = function(t) {
                        return !t || !t.length
                    }
                }
            }
        },
        Oo = "ng-valid",
        To = "ng-invalid",
        Mo = "ng-pristine",
        Vo = "ng-dirty",
        No = "ng-pending",
        Do = new r("ngModel"),
        jo = ["$scope", "$exceptionHandler", "$attrs", "$element", "$parse", "$animate", "$timeout", "$rootScope", "$q", "$interpolate", function(t, e, r, i, a, s, u, c, l, f) {
            this.$modelValue = this.$viewValue = Number.NaN, this.$$rawModelValue = n, this.$validators = {}, this.$asyncValidators = {}, this.$parsers = [], this.$formatters = [], this.$viewChangeListeners = [], this.$untouched = !0, this.$touched = !1, this.$pristine = !0, this.$dirty = !1, this.$valid = !0, this.$invalid = !1, this.$error = {}, this.$$success = {}, this.$pending = n, this.$name = f(r.name || "", !1)(t);
            var $ = a(r.ngModel),
                p = $.assign,
                m = $,
                g = p,
                w = null,
                x = this;
            this.$$setOptions = function(t) {
                if ((x.$options = t) && t.getterSetter) {
                    var e = a(r.ngModel + "()"),
                        n = a(r.ngModel + "($$$p)");
                    m = function(t) {
                        var n = $(t);
                        return b(n) && (n = e(t)), n
                    }, g = function(t) {
                        b($(t)) ? n(t, {
                            $$$p: x.$modelValue
                        }) : p(t, x.$modelValue)
                    }
                } else if (!$.assign) throw Do("nonassign", r.ngModel, I(i))
            }, this.$render = h, this.$isEmpty = function(t) {
                return d(t) || "" === t || null === t || t !== t
            };
            var S = i.inheritedData("$formController") || Ji,
                C = 0;
            vr({
                ctrl: this,
                $element: i,
                set: function(t, e) {
                    t[e] = !0
                },
                unset: function(t, e) {
                    delete t[e]
                },
                parentForm: S,
                $animate: s
            }), this.$setPristine = function() {
                x.$dirty = !1, x.$pristine = !0, s.removeClass(i, Vo), s.addClass(i, Mo)
            }, this.$setDirty = function() {
                x.$dirty = !0, x.$pristine = !1, s.removeClass(i, Mo), s.addClass(i, Vo), S.$setDirty()
            }, this.$setUntouched = function() {
                x.$touched = !1, x.$untouched = !0, s.setClass(i, "ng-untouched", "ng-touched")
            }, this.$setTouched = function() {
                x.$touched = !0, x.$untouched = !1, s.setClass(i, "ng-touched", "ng-untouched")
            }, this.$rollbackViewValue = function() {
                u.cancel(w), x.$viewValue = x.$$lastCommittedViewValue, x.$render()
            }, this.$validate = function() {
                if (!y(x.$modelValue) || !isNaN(x.$modelValue)) {
                    var t = x.$$rawModelValue,
                        e = x.$valid,
                        r = x.$modelValue,
                        i = x.$options && x.$options.allowInvalid;
                    x.$$runValidators(x.$error[x.$$parserName || "parse"] ? !1 : n, t, x.$$lastCommittedViewValue, function(o) {
                        i || e === o || (x.$modelValue = o ? t : n, x.$modelValue !== r && x.$$writeModelToScope())
                    })
                }
            }, this.$$runValidators = function(t, e, r, i) {
                function a() {
                    var t = !0;
                    return o(x.$validators, function(n, i) {
                        var o = n(e, r);
                        t = t && o, u(i, o)
                    }), t ? !0 : (o(x.$asyncValidators, function(t, e) {
                        u(e, null)
                    }), !1)
                }

                function s() {
                    var t = [],
                        i = !0;
                    o(x.$asyncValidators, function(o, a) {
                        var s = o(e, r);
                        if (!s || !b(s.then)) throw Do("$asyncValidators", s);
                        u(a, n), t.push(s.then(function() {
                            u(a, !0)
                        }, function() {
                            i = !1, u(a, !1)
                        }))
                    }), t.length ? l.all(t).then(function() {
                        c(i)
                    }, h) : c(!0)
                }

                function u(t, e) {
                    f === C && x.$setValidity(t, e)
                }

                function c(t) {
                    f === C && i(t)
                }
                C++;
                var f = C;
                (function(t) {
                    var e = x.$$parserName || "parse";
                    if (t === n) u(e, null);
                    else if (u(e, t), !t) return o(x.$validators, function(t, e) {
                        u(e, null)
                    }), o(x.$asyncValidators, function(t, e) {
                        u(e, null)
                    }), !1;
                    return !0
                })(t) && a() ? s() : c(!1)
            }, this.$commitViewValue = function() {
                var t = x.$viewValue;
                u.cancel(w), (x.$$lastCommittedViewValue !== t || "" === t && x.$$hasNativeValidators) && (x.$$lastCommittedViewValue = t, x.$pristine && this.$setDirty(), this.$$parseAndValidate())
            }, this.$$parseAndValidate = function() {
                var e = x.$$lastCommittedViewValue,
                    r = d(e) ? n : !0;
                if (r)
                    for (var i = 0; i < x.$parsers.length; i++)
                        if (e = x.$parsers[i](e), d(e)) {
                            r = !1;
                            break
                        }
                y(x.$modelValue) && isNaN(x.$modelValue) && (x.$modelValue = m(t));
                var o = x.$modelValue,
                    a = x.$options && x.$options.allowInvalid;
                x.$$rawModelValue = e, a && (x.$modelValue = e, x.$modelValue !== o && x.$$writeModelToScope()), x.$$runValidators(r, e, x.$$lastCommittedViewValue, function(t) {
                    a || (x.$modelValue = t ? e : n, x.$modelValue !== o && x.$$writeModelToScope())
                })
            }, this.$$writeModelToScope = function() {
                g(t, x.$modelValue), o(x.$viewChangeListeners, function(t) {
                    try {
                        t()
                    } catch (n) {
                        e(n)
                    }
                })
            }, this.$setViewValue = function(t, e) {
                x.$viewValue = t, x.$options && !x.$options.updateOnDefault || x.$$debounceViewValueCommit(e)
            }, this.$$debounceViewValueCommit = function(e) {
                var n = 0,
                    r = x.$options;
                r && v(r.debounce) && (r = r.debounce, y(r) ? n = r : y(r[e]) ? n = r[e] : y(r["default"]) && (n = r["default"])), u.cancel(w), n ? w = u(function() {
                    x.$commitViewValue()
                }, n) : c.$$phase ? x.$commitViewValue() : t.$apply(function() {
                    x.$commitViewValue()
                })
            }, t.$watch(function() {
                var e = m(t);
                if (e !== x.$modelValue) {
                    x.$modelValue = x.$$rawModelValue = e;
                    for (var r = x.$formatters, i = r.length, o = e; i--;) o = r[i](o);
                    x.$viewValue !== o && (x.$viewValue = x.$$lastCommittedViewValue = o, x.$render(), x.$$runValidators(n, e, o, h))
                }
                return e
            })
        }],
        Po = ["$rootScope", function(t) {
            return {
                restrict: "A",
                require: ["ngModel", "^?form", "^?ngModelOptions"],
                controller: jo,
                priority: 1,
                compile: function(e) {
                    return e.addClass(Mo).addClass("ng-untouched").addClass(Oo), {
                        pre: function(t, e, n, r) {
                            var i = r[0],
                                o = r[1] || Ji;
                            i.$$setOptions(r[2] && r[2].$options), o.$addControl(i), n.$observe("name", function(t) {
                                i.$name !== t && o.$$renameControl(i, t)
                            }), t.$on("$destroy", function() {
                                o.$removeControl(i)
                            })
                        },
                        post: function(e, n, r, i) {
                            var o = i[0];
                            o.$options && o.$options.updateOn && n.on(o.$options.updateOn, function(t) {
                                o.$$debounceViewValueCommit(t && t.type)
                            }), n.on("blur", function() {
                                o.$touched || (t.$$phase ? e.$evalAsync(o.$setTouched) : e.$apply(o.$setTouched))
                            })
                        }
                    }
                }
            }
        }],
        Ro = /(\s+|^)default(\s+|$)/,
        qo = function() {
            return {
                restrict: "A",
                controller: ["$scope", "$attrs", function(t, e) {
                    var r = this;
                    this.$options = t.$eval(e.ngModelOptions), this.$options.updateOn !== n ? (this.$options.updateOnDefault = !1, this.$options.updateOn = Pr(this.$options.updateOn.replace(Ro, function() {
                        return r.$options.updateOnDefault = !0, " "
                    }))) : this.$options.updateOnDefault = !0
                }]
            }
        },
        Io = sr({
            terminal: !0,
            priority: 1e3
        }),
        Uo = ["$locale", "$interpolate", function(t, e) {
            var n = /{}/g,
                r = /^when(Minus)?(.+)$/;
            return {
                restrict: "EA",
                link: function(i, a, s) {
                    function u(t) {
                        a.text(t || "")
                    }
                    var c, l = s.count,
                        f = s.$attr.when && a.attr(s.$attr.when),
                        h = s.offset || 0,
                        $ = i.$eval(f) || {},
                        p = {},
                        f = e.startSymbol(),
                        d = e.endSymbol(),
                        v = f + l + "-" + h + d,
                        m = Vr.noop;
                    o(s, function(t, e) {
                        var n = r.exec(e);
                        n && (n = (n[1] ? "-" : "") + Sr(n[2]), $[n] = a.attr(s.$attr[e]))
                    }), o($, function(t, r) {
                        p[r] = e(t.replace(n, v))
                    }), i.$watch(l, function(e) {
                        e = parseFloat(e);
                        var n = isNaN(e);
                        n || e in $ || (e = t.pluralCat(e - h)), e === c || n && isNaN(c) || (m(), m = i.$watch(p[e], u), c = e)
                    })
                }
            }
        }],
        Fo = ["$parse", "$animate", function(t, a) {
            var s = r("ngRepeat"),
                u = function(t, e, n, r, i, o, a) {
                    t[n] = r, i && (t[i] = o), t.$index = e, t.$first = 0 === e, t.$last = e === a - 1, t.$middle = !(t.$first || t.$last), t.$odd = !(t.$even = 0 === (1 & e))
                };
            return {
                restrict: "A",
                multiElement: !0,
                transclude: "element",
                priority: 1e3,
                terminal: !0,
                $$tlb: !0,
                compile: function(r, c) {
                    var l = c.ngRepeat,
                        f = e.createComment(" end ngRepeat: " + l + " "),
                        h = l.match(/^\s*([\s\S]+?)\s+in\s+([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+track\s+by\s+([\s\S]+?))?\s*$/);
                    if (!h) throw s("iexp", l);
                    var $ = h[1],
                        p = h[2],
                        d = h[3],
                        v = h[4],
                        h = $.match(/^(?:(\s*[\$\w]+)|\(\s*([\$\w]+)\s*,\s*([\$\w]+)\s*\))$/);
                    if (!h) throw s("iidexp", $);
                    var m = h[3] || h[1],
                        g = h[2];
                    if (d && (!/^[$a-zA-Z_][$a-zA-Z0-9_]*$/.test(d) || /^(null|undefined|this|\$index|\$first|\$middle|\$last|\$even|\$odd|\$parent)$/.test(d))) throw s("badident", d);
                    var y, w, b, x, S = {
                        $id: Oe
                    };
                    return v ? y = t(v) : (b = function(t, e) {
                            return Oe(e)
                        }, x = function(t) {
                            return t
                        }),
                        function(t, e, r, c, h) {
                            y && (w = function(e, n, r) {
                                return g && (S[g] = e), S[m] = n, S.$index = r, y(t, S)
                            });
                            var $ = ne();
                            t.$watchCollection(p, function(r) {
                                var c, p, v, y, S, C, k, A, E, O, T = e[0],
                                    M = ne();
                                if (d && (t[d] = r), i(r)) A = r, p = w || b;
                                else {
                                    p = w || x, A = [];
                                    for (O in r) r.hasOwnProperty(O) && "$" != O.charAt(0) && A.push(O);
                                    A.sort()
                                }
                                for (y = A.length, O = Array(y), c = 0; y > c; c++)
                                    if (S = r === A ? c : A[c], C = r[S], k = p(S, C, c), $[k]) E = $[k], delete $[k], M[k] = E, O[c] = E;
                                    else {
                                        if (M[k]) throw o(O, function(t) {
                                            t && t.scope && ($[t.id] = t)
                                        }), s("dupes", l, k, C);
                                        O[c] = {
                                            id: k,
                                            scope: n,
                                            clone: n
                                        }, M[k] = !0
                                    }
                                for (v in $) {
                                    if (E = $[v], k = ee(E.clone), a.leave(k), k[0].parentNode)
                                        for (c = 0, p = k.length; p > c; c++) k[c].$$NG_REMOVED = !0;
                                    E.scope.$destroy()
                                }
                                for (c = 0; y > c; c++)
                                    if (S = r === A ? c : A[c], C = r[S], E = O[c], E.scope) {
                                        v = T;
                                        do v = v.nextSibling; while (v && v.$$NG_REMOVED);
                                        E.clone[0] != v && a.move(ee(E.clone), null, yr(T)), T = E.clone[E.clone.length - 1], u(E.scope, c, m, C, g, S, y)
                                    } else h(function(t, e) {
                                        E.scope = e;
                                        var n = f.cloneNode(!1);
                                        t[t.length++] = n, a.enter(t, null, yr(T)), T = n, E.clone = t, M[E.id] = E, u(E.scope, c, m, C, g, S, y)
                                    });
                                $ = M
                            })
                        }
                }
            }
        }],
        Ho = ["$animate", function(t) {
            return {
                restrict: "A",
                multiElement: !0,
                link: function(e, n, r) {
                    e.$watch(r.ngShow, function(e) {
                        t[e ? "removeClass" : "addClass"](n, "ng-hide", {
                            tempClasses: "ng-hide-animate"
                        })
                    })
                }
            }
        }],
        _o = ["$animate", function(t) {
            return {
                restrict: "A",
                multiElement: !0,
                link: function(e, n, r) {
                    e.$watch(r.ngHide, function(e) {
                        t[e ? "addClass" : "removeClass"](n, "ng-hide", {
                            tempClasses: "ng-hide-animate"
                        })
                    })
                }
            }
        }],
        Lo = sr(function(t, e, n) {
            t.$watchCollection(n.ngStyle, function(t, n) {
                n && t !== n && o(n, function(t, n) {
                    e.css(n, "")
                }), t && e.css(t)
            })
        }),
        Bo = ["$animate", function(t) {
            return {
                restrict: "EA",
                require: "ngSwitch",
                controller: ["$scope", function() {
                    this.cases = {}
                }],
                link: function(n, r, i, a) {
                    var s = [],
                        u = [],
                        c = [],
                        l = [],
                        f = function(t, e) {
                            return function() {
                                t.splice(e, 1)
                            }
                        };
                    n.$watch(i.ngSwitch || i.on, function(n) {
                        var r, i;
                        for (r = 0, i = c.length; i > r; ++r) t.cancel(c[r]);
                        for (r = c.length = 0, i = l.length; i > r; ++r) {
                            var h = ee(u[r].clone);
                            l[r].$destroy(), (c[r] = t.leave(h)).then(f(c, r))
                        }
                        u.length = 0, l.length = 0, (s = a.cases["!" + n] || a.cases["?"]) && o(s, function(n) {
                            n.transclude(function(r, i) {
                                l.push(i);
                                var o = n.element;
                                r[r.length++] = e.createComment(" end ngSwitchWhen: "), u.push({
                                    clone: r
                                }), t.enter(r, o.parent(), o)
                            })
                        })
                    })
                }
            }
        }],
        zo = sr({
            transclude: "element",
            priority: 1200,
            require: "^ngSwitch",
            multiElement: !0,
            link: function(t, e, n, r, i) {
                r.cases["!" + n.ngSwitchWhen] = r.cases["!" + n.ngSwitchWhen] || [], r.cases["!" + n.ngSwitchWhen].push({
                    transclude: i,
                    element: e
                })
            }
        }),
        Go = sr({
            transclude: "element",
            priority: 1200,
            require: "^ngSwitch",
            multiElement: !0,
            link: function(t, e, n, r, i) {
                r.cases["?"] = r.cases["?"] || [], r.cases["?"].push({
                    transclude: i,
                    element: e
                })
            }
        }),
        Wo = sr({
            restrict: "EAC",
            link: function(t, e, n, i, o) {
                if (!o) throw r("ngTransclude")("orphan", I(e));
                o(function(t) {
                    e.empty(), e.append(t)
                })
            }
        }),
        Jo = ["$templateCache", function(t) {
            return {
                restrict: "E",
                terminal: !0,
                compile: function(e, n) {
                    "text/ng-template" == n.type && t.put(n.id, e[0].text)
                }
            }
        }],
        Zo = r("ngOptions"),
        Yo = p({
            restrict: "A",
            terminal: !0
        }),
        Ko = ["$compile", "$parse", function(t, r) {
            var i = /^\s*([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+group\s+by\s+([\s\S]+?))?\s+for\s+(?:([\$\w][\$\w]*)|(?:\(\s*([\$\w][\$\w]*)\s*,\s*([\$\w][\$\w]*)\s*\)))\s+in\s+([\s\S]+?)(?:\s+track\s+by\s+([\s\S]+?))?$/,
                a = {
                    $setViewValue: h
                };
            return {
                restrict: "E",
                require: ["select", "?ngModel"],
                controller: ["$element", "$scope", "$attrs", function(t, e, n) {
                    var r, i = this,
                        o = {},
                        s = a;
                    i.databound = n.ngModel, i.init = function(t, e, n) {
                        s = t, r = n
                    }, i.addOption = function(e, n) {
                        Q(e, '"option value"'), o[e] = !0, s.$viewValue == e && (t.val(e), r.parent() && r.remove()), n && n[0].hasAttribute("selected") && (n[0].selected = !0)
                    }, i.removeOption = function(t) {
                        this.hasOption(t) && (delete o[t], s.$viewValue === t && this.renderUnknownOption(t))
                    }, i.renderUnknownOption = function(e) {
                        e = "? " + Oe(e) + " ?", r.val(e), t.prepend(r), t.val(e), r.prop("selected", !0)
                    }, i.hasOption = function(t) {
                        return o.hasOwnProperty(t)
                    }, e.$on("$destroy", function() {
                        i.renderUnknownOption = h
                    })
                }],
                link: function(a, s, u, c) {
                    function l(t, e, n, r) {
                        n.$render = function() {
                            var t = n.$viewValue;
                            r.hasOption(t) ? (S.parent() && S.remove(), e.val(t), "" === t && p.prop("selected", !0)) : d(t) && p ? e.val("") : r.renderUnknownOption(t)
                        }, e.on("change", function() {
                            t.$apply(function() {
                                S.parent() && S.remove(), n.$setViewValue(e.val())
                            })
                        })
                    }

                    function f(t, e, n) {
                        var r;
                        n.$render = function() {
                            var t = new Te(n.$viewValue);
                            o(e.find("option"), function(e) {
                                e.selected = v(t.get(e.value))
                            })
                        }, t.$watch(function() {
                            N(r, n.$viewValue) || (r = V(n.$viewValue), n.$render())
                        }), e.on("change", function() {
                            t.$apply(function() {
                                var t = [];
                                o(e.find("option"), function(e) {
                                    e.selected && t.push(e.value)
                                }), n.$setViewValue(t)
                            })
                        })
                    }

                    function h(e, a, s) {
                        function u(t, n, r) {
                            return D[S] = r, A && (D[A] = n), t(e, D)
                        }

                        function c(t) {
                            var e;
                            if (m)
                                if (M && jr(t)) {
                                    e = new Te([]);
                                    for (var n = 0; n < t.length; n++) e.put(u(M, null, t[n]), !0)
                                } else e = new Te(t);
                            else M && (t = u(M, null, t));
                            return function(n, r) {
                                var i;
                                return i = M ? M : k ? k : O, m ? v(e.remove(u(i, n, r))) : t === u(i, n, r)
                            }
                        }

                        function l() {
                            w || (e.$$postDigest(h), w = !0)
                        }

                        function f(t, e, n) {
                            t[e] = t[e] || 0, t[e] += n ? 1 : -1
                        }

                        function h() {
                            w = !1;
                            var t, n, r, i, l, h = {
                                    "": []
                                },
                                p = [""];
                            r = s.$viewValue, i = T(e) || [];
                            var g, S, C, k, O = A ? Object.keys(i).sort() : i,
                                j = {};
                            l = c(r);
                            var P, R, q = !1;
                            for (V = {}, k = 0; C = O.length, C > k; k++) g = k, A && (g = O[k], "$" === g.charAt(0)) || (S = i[g], t = u(E, g, S) || "", (n = h[t]) || (n = h[t] = [], p.push(t)), t = l(g, S), q = q || t, S = u(d, g, S), S = v(S) ? S : "", R = M ? M(e, D) : A ? O[k] : k, M && (V[R] = g), n.push({
                                id: R,
                                label: S,
                                selected: t
                            }));
                            for (m || (y || null === r ? h[""].unshift({
                                    id: "",
                                    label: "",
                                    selected: !q
                                }) : q || h[""].unshift({
                                    id: "?",
                                    label: "",
                                    selected: !0
                                })), g = 0, O = p.length; O > g; g++) {
                                for (t = p[g], n = h[t], N.length <= g ? (r = {
                                        element: x.clone().attr("label", t),
                                        label: n.label
                                    }, i = [r], N.push(i), a.append(r.element)) : (i = N[g], r = i[0], r.label != t && r.element.attr("label", r.label = t)), q = null, k = 0, C = n.length; C > k; k++) t = n[k], (l = i[k + 1]) ? (q = l.element, l.label !== t.label && (f(j, l.label, !1), f(j, t.label, !0), q.text(l.label = t.label), q.prop("label", l.label)), l.id !== t.id && q.val(l.id = t.id), q[0].selected !== t.selected && (q.prop("selected", l.selected = t.selected), gr && q.prop("selected", l.selected))) : ("" === t.id && y ? P = y : (P = b.clone()).val(t.id).prop("selected", t.selected).attr("selected", t.selected).prop("label", t.label).text(t.label), i.push(l = {
                                    element: P,
                                    label: t.label,
                                    id: t.id,
                                    selected: t.selected
                                }), f(j, t.label, !0), q ? q.after(P) : r.element.append(P), q = P);
                                for (k++; i.length > k;) t = i.pop(), f(j, t.label, !1), t.element.remove()
                            }
                            for (; N.length > g;) {
                                for (n = N.pop(), k = 1; k < n.length; ++k) f(j, n[k].label, !1);
                                n[0].element.remove()
                            }
                            o(j, function(t, e) {
                                t > 0 ? $.addOption(e) : 0 > t && $.removeOption(e)
                            })
                        }
                        var p;
                        if (!(p = g.match(i))) throw Zo("iexp", g, I(a));
                        var d = r(p[2] || p[1]),
                            S = p[4] || p[6],
                            C = / as /.test(p[0]) && p[1],
                            k = C ? r(C) : null,
                            A = p[5],
                            E = r(p[3] || ""),
                            O = r(p[2] ? p[1] : S),
                            T = r(p[7]),
                            M = p[8] ? r(p[8]) : null,
                            V = {},
                            N = [
                                [{
                                    element: a,
                                    label: ""
                                }]
                            ],
                            D = {};
                        y && (t(y)(e), y.removeClass("ng-scope"), y.remove()), a.empty(), a.on("change", function() {
                            e.$apply(function() {
                                var t, r = T(e) || [];
                                if (m) t = [], o(a.val(), function(e) {
                                    e = M ? V[e] : e, t.push("?" === e ? n : "" === e ? null : u(k ? k : O, e, r[e]))
                                });
                                else {
                                    var i = M ? V[a.val()] : a.val();
                                    t = "?" === i ? n : "" === i ? null : u(k ? k : O, i, r[i])
                                }
                                s.$setViewValue(t), h()
                            })
                        }), s.$render = h, e.$watchCollection(T, l), e.$watchCollection(function() {
                            var t, n = T(e);
                            if (n && jr(n)) {
                                t = Array(n.length);
                                for (var r = 0, i = n.length; i > r; r++) t[r] = u(d, r, n[r])
                            } else if (n)
                                for (r in t = {}, n) n.hasOwnProperty(r) && (t[r] = u(d, r, n[r]));
                            return t
                        }, l), m && e.$watchCollection(function() {
                            return s.$modelValue
                        }, l)
                    }
                    if (c[1]) {
                        var $ = c[0];
                        c = c[1];
                        var p, m = u.multiple,
                            g = u.ngOptions,
                            y = !1,
                            w = !1,
                            b = yr(e.createElement("option")),
                            x = yr(e.createElement("optgroup")),
                            S = b.clone();
                        u = 0;
                        for (var C = s.children(), k = C.length; k > u; u++)
                            if ("" === C[u].value) {
                                p = y = C.eq(u);
                                break
                            }
                        $.init(c, y, S), m && (c.$isEmpty = function(t) {
                            return !t || 0 === t.length
                        }), g ? h(a, s, c) : m ? f(a, s, c) : l(a, s, c, $)
                    }
                }
            }
        }],
        Xo = ["$interpolate", function(t) {
            var e = {
                addOption: h,
                removeOption: h
            };
            return {
                restrict: "E",
                priority: 100,
                compile: function(n, r) {
                    if (d(r.value)) {
                        var i = t(n.text(), !0);
                        i || r.$set("value", n.text())
                    }
                    return function(t, n, r) {
                        var o = n.parent(),
                            a = o.data("$selectController") || o.parent().data("$selectController");
                        a && a.databound || (a = e), i ? t.$watch(i, function(t, e) {
                            r.$set("value", t), e !== t && a.removeOption(e), a.addOption(t, n)
                        }) : a.addOption(r.value, n), n.on("$destroy", function() {
                            a.removeOption(r.value)
                        })
                    }
                }
            }
        }],
        Qo = p({
            restrict: "E",
            terminal: !1
        }),
        ta = function() {
            return {
                restrict: "A",
                require: "?ngModel",
                link: function(t, e, n, r) {
                    r && (n.required = !0, r.$validators.required = function(t, e) {
                        return !n.required || !r.$isEmpty(e)
                    }, n.$observe("required", function() {
                        r.$validate()
                    }))
                }
            }
        },
        ea = function() {
            return {
                restrict: "A",
                require: "?ngModel",
                link: function(t, e, i, o) {
                    if (o) {
                        var a, s = i.ngPattern || i.pattern;
                        i.$observe("pattern", function(t) {
                            if (g(t) && 0 < t.length && (t = new RegExp("^" + t + "$")), t && !t.test) throw r("ngPattern")("noregexp", s, t, I(e));
                            a = t || n, o.$validate()
                        }), o.$validators.pattern = function(t) {
                            return o.$isEmpty(t) || d(a) || a.test(t)
                        }
                    }
                }
            }
        },
        na = function() {
            return {
                restrict: "A",
                require: "?ngModel",
                link: function(t, e, n, r) {
                    if (r) {
                        var i = -1;
                        n.$observe("maxlength", function(t) {
                            t = f(t), i = isNaN(t) ? -1 : t, r.$validate()
                        }), r.$validators.maxlength = function(t, e) {
                            return 0 > i || r.$isEmpty(t) || e.length <= i
                        }
                    }
                }
            }
        },
        ra = function() {
            return {
                restrict: "A",
                require: "?ngModel",
                link: function(t, e, n, r) {
                    if (r) {
                        var i = 0;
                        n.$observe("minlength", function(t) {
                            i = f(t) || 0, r.$validate()
                        }), r.$validators.minlength = function(t, e) {
                            return r.$isEmpty(e) || e.length >= i
                        }
                    }
                }
            }
        };
    t.angular.bootstrap ? console.log("WARNING: Tried to load angular more than once.") : (Y(), ie(Vr), yr(e).ready(function() {
        z(e, G)
    }))
}(window, document), !window.angular.$$csp() && window.angular.element(document).find("head").prepend('<style type="text/css">@charset "UTF-8";[ng\\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide:not(.ng-hide-animate){display:none !important;}ng\\:form{display:block;}</style>');
! function(n, t, e) {
    "use strict";
    t.module("ngAnimate", ["ng"]).directive("ngAnimateChildren", function() {
        return function(n, e, a) {
            a = a.ngAnimateChildren, t.isString(a) && 0 === a.length ? e.data("$$ngAnimateChildren", !0) : n.$watch(a, function(n) {
                e.data("$$ngAnimateChildren", !!n)
            })
        }
    }).factory("$$animateReflow", ["$$rAF", "$document", function(n) {
        return function(t) {
            return n(function() {
                t()
            })
        }
    }]).config(["$provide", "$animateProvider", function(a, i) {
        function s(n) {
            for (var t = 0; t < n.length; t++) {
                var e = n[t];
                if (1 == e.nodeType) return e
            }
        }

        function r(n, t) {
            return s(n) == s(t)
        }
        var o, l = t.noop,
            u = t.forEach,
            c = i.$$selectors,
            f = t.isArray,
            m = t.isString,
            d = t.isObject,
            v = {
                running: !0
            };
        a.decorator("$animate", ["$delegate", "$$q", "$injector", "$sniffer", "$rootElement", "$$asyncCallback", "$rootScope", "$document", "$templateRequest", "$$jqLite", function(n, e, a, $, g, C, p, h, A, D) {
            function S(n, t) {
                var e = n.data("$$ngAnimateState") || {};
                return t && (e.running = !0, e.structural = !0, n.data("$$ngAnimateState", e)), e.disabled || e.running && e.structural
            }

            function y(n) {
                var t, a = e.defer();
                return a.promise.$$cancelFn = function() {
                    t && t()
                }, p.$$postDigest(function() {
                    t = n(function() {
                        a.resolve()
                    })
                }), a.promise
            }

            function b(n) {
                return d(n) ? (n.tempClasses && m(n.tempClasses) && (n.tempClasses = n.tempClasses.split(/\s+/)), n) : void 0
            }

            function w(n, t, e) {
                e = e || {};
                var a = {};
                u(e, function(n, t) {
                    u(t.split(" "), function(t) {
                        a[t] = n
                    })
                });
                var i = Object.create(null);
                u((n.attr("class") || "").split(/\s+/), function(n) {
                    i[n] = !0
                });
                var s = [],
                    r = [];
                return u(t && t.classes || [], function(n, t) {
                    var e = i[t],
                        o = a[t] || {};
                    !1 === n ? (e || "addClass" == o.event) && r.push(t) : !0 === n && (e && "removeClass" != o.event || s.push(t))
                }), 0 < s.length + r.length && [s.join(" "), r.join(" ")]
            }

            function k(n) {
                if (n) {
                    var t = [],
                        e = {};
                    n = n.substr(1).split("."), ($.transitions || $.animations) && t.push(a.get(c[""]));
                    for (var i = 0; i < n.length; i++) {
                        var s = n[i],
                            r = c[s];
                        r && !e[s] && (t.push(a.get(r)), e[s] = !0)
                    }
                    return t
                }
            }

            function x(n, e, a, i) {
                function s(n, t) {
                    var e = n[t],
                        a = n["before" + t.charAt(0).toUpperCase() + t.substr(1)];
                    return e || a ? ("leave" == t && (a = e, e = null), D.push({
                        event: t,
                        fn: e
                    }), p.push({
                        event: t,
                        fn: a
                    }), !0) : void 0
                }

                function r(t, e, s) {
                    var r = [];
                    u(t, function(n) {
                        n.fn && r.push(n)
                    });
                    var o = 0;
                    u(r, function(t, u) {
                        var f = function() {
                            n: {
                                if (e) {
                                    if ((e[u] || l)(), ++o < r.length) break n;
                                    e = null
                                }
                                s()
                            }
                        };
                        switch (t.event) {
                            case "setClass":
                                e.push(t.fn(n, c, m, f, i));
                                break;
                            case "animate":
                                e.push(t.fn(n, a, i.from, i.to, f));
                                break;
                            case "addClass":
                                e.push(t.fn(n, c || a, f, i));
                                break;
                            case "removeClass":
                                e.push(t.fn(n, m || a, f, i));
                                break;
                            default:
                                e.push(t.fn(n, f, i))
                        }
                    }), e && 0 === e.length && s()
                }
                var o = n[0];
                if (o) {
                    i && (i.to = i.to || {}, i.from = i.from || {});
                    var c, m;
                    f(a) && (c = a[0], m = a[1], c ? m ? a = c + " " + m : (a = c, e = "addClass") : (a = m, e = "removeClass"));
                    var d = "setClass" == e,
                        v = d || "addClass" == e || "removeClass" == e || "animate" == e,
                        $ = n.attr("class") + " " + a;
                    if (T($)) {
                        var g = l,
                            C = [],
                            p = [],
                            h = l,
                            A = [],
                            D = [],
                            $ = (" " + $).replace(/\s+/g, ".");
                        return u(k($), function(n) {
                            !s(n, e) && d && (s(n, "addClass"), s(n, "removeClass"))
                        }), {
                            node: o,
                            event: e,
                            className: a,
                            isClassBased: v,
                            isSetClassOperation: d,
                            applyStyles: function() {
                                i && n.css(t.extend(i.from || {}, i.to || {}))
                            },
                            before: function(n) {
                                g = n, r(p, C, function() {
                                    g = l, n()
                                })
                            },
                            after: function(n) {
                                h = n, r(D, A, function() {
                                    h = l, n()
                                })
                            },
                            cancel: function() {
                                C && (u(C, function(n) {
                                    (n || l)(!0)
                                }), g(!0)), A && (u(A, function(n) {
                                    (n || l)(!0)
                                }), h(!0))
                            }
                        }
                    }
                }
            }

            function B(n, e, a, i, s, r, c, f) {
                function m(t) {
                    var i = "$animate:" + t;
                    h && h[i] && 0 < h[i].length && C(function() {
                        a.triggerHandler(i, {
                            event: n,
                            className: e
                        })
                    })
                }

                function d() {
                    m("before")
                }

                function v() {
                    m("after")
                }

                function $() {
                    $.hasBeenRun || ($.hasBeenRun = !0, r())
                }

                function g() {
                    if (!g.hasBeenRun) {
                        p && p.applyStyles(), g.hasBeenRun = !0, c && c.tempClasses && u(c.tempClasses, function(n) {
                            o.removeClass(a, n)
                        });
                        var t = a.data("$$ngAnimateState");
                        t && (p && p.isClassBased ? M(a, e) : (C(function() {
                            var t = a.data("$$ngAnimateState") || {};
                            b == t.index && M(a, e, n)
                        }), a.data("$$ngAnimateState", t))), m("close"), f()
                    }
                }
                var p = x(a, n, e, c);
                if (!p) return $(), d(), v(), g(), l;
                n = p.event, e = p.className;
                var h = t.element._data(p.node),
                    h = h && h.events;
                if (i || (i = s ? s.parent() : a.parent()), E(a, i)) return $(), d(), v(), g(), l;
                i = a.data("$$ngAnimateState") || {};
                var A = i.active || {},
                    D = i.totalActive || 0,
                    S = i.last;
                if (s = !1, D > 0) {
                    if (D = [], p.isClassBased) "setClass" == S.event ? (D.push(S), M(a, e)) : A[e] && (y = A[e], y.event == n ? s = !0 : (D.push(y), M(a, e)));
                    else if ("leave" == n && A["ng-leave"]) s = !0;
                    else {
                        for (var y in A) D.push(A[y]);
                        i = {}, M(a, !0)
                    }
                    0 < D.length && u(D, function(n) {
                        n.cancel()
                    })
                }
                if (!p.isClassBased || p.isSetClassOperation || "animate" == n || s || (s = "addClass" == n == a.hasClass(e)), s) return $(), d(), v(), m("close"), f(), l;
                A = i.active || {}, D = i.totalActive || 0, "leave" == n && a.one("$destroy", function(n) {
                    n = t.element(this);
                    var e = n.data("$$ngAnimateState");
                    e && (e = e.active["ng-leave"]) && (e.cancel(), M(n, "ng-leave"))
                }), o.addClass(a, "ng-animate"), c && c.tempClasses && u(c.tempClasses, function(n) {
                    o.addClass(a, n)
                });
                var b = N++;
                return D++, A[e] = p, a.data("$$ngAnimateState", {
                    last: p,
                    active: A,
                    index: b,
                    totalActive: D
                }), d(), p.before(function(t) {
                    var i = a.data("$$ngAnimateState");
                    t = t || !i || !i.active[e] || p.isClassBased && i.active[e].event != n, $(), !0 === t ? g() : (v(), p.after(g))
                }), p.cancel
            }

            function F(n) {
                (n = s(n)) && (n = t.isFunction(n.getElementsByClassName) ? n.getElementsByClassName("ng-animate") : n.querySelectorAll(".ng-animate"), u(n, function(n) {
                    n = t.element(n), (n = n.data("$$ngAnimateState")) && n.active && u(n.active, function(n) {
                        n.cancel()
                    })
                }))
            }

            function M(n, t) {
                if (r(n, g)) v.disabled || (v.running = !1, v.structural = !1);
                else if (t) {
                    var e = n.data("$$ngAnimateState") || {},
                        a = !0 === t;
                    !a && e.active && e.active[t] && (e.totalActive--, delete e.active[t]), (a || !e.totalActive) && (o.removeClass(n, "ng-animate"), n.removeData("$$ngAnimateState"))
                }
            }

            function E(n, e) {
                if (v.disabled) return !0;
                if (r(n, g)) return v.running;
                var a, i, s;
                do {
                    if (0 === e.length) break;
                    var o = r(e, g),
                        l = o ? v : e.data("$$ngAnimateState") || {};
                    if (l.disabled) return !0;
                    o && (s = !0), !1 !== a && (o = e.data("$$ngAnimateChildren"), t.isDefined(o) && (a = o)), i = i || l.running || l.last && !l.last.isClassBased
                } while (e = e.parent());
                return !s || !a && i
            }
            o = D, g.data("$$ngAnimateState", v);
            var R = p.$watch(function() {
                    return A.totalPendingRequests
                }, function(n) {
                    0 === n && (R(), p.$$postDigest(function() {
                        p.$$postDigest(function() {
                            v.running = !1
                        })
                    }))
                }),
                N = 0,
                O = i.classNameFilter(),
                T = O ? function(n) {
                    return O.test(n)
                } : function() {
                    return !0
                };
            return {
                animate: function(n, e, a, i, r) {
                    return i = i || "ng-inline-animate", r = b(r) || {}, r.from = a ? e : null, r.to = a ? a : e, y(function(e) {
                        return B("animate", i, t.element(s(n)), null, null, l, r, e)
                    })
                },
                enter: function(e, a, i, r) {
                    return r = b(r), e = t.element(e), a = a && t.element(a), i = i && t.element(i), S(e, !0), n.enter(e, a, i), y(function(n) {
                        return B("enter", "ng-enter", t.element(s(e)), a, i, l, r, n)
                    })
                },
                leave: function(e, a) {
                    return a = b(a), e = t.element(e), F(e), S(e, !0), y(function(i) {
                        return B("leave", "ng-leave", t.element(s(e)), null, null, function() {
                            n.leave(e)
                        }, a, i)
                    })
                },
                move: function(e, a, i, r) {
                    return r = b(r), e = t.element(e), a = a && t.element(a), i = i && t.element(i), F(e), S(e, !0), n.move(e, a, i), y(function(n) {
                        return B("move", "ng-move", t.element(s(e)), a, i, l, r, n)
                    })
                },
                addClass: function(n, t, e) {
                    return this.setClass(n, t, [], e)
                },
                removeClass: function(n, t, e) {
                    return this.setClass(n, [], t, e)
                },
                setClass: function(e, a, i, r) {
                    if (r = b(r), e = t.element(e), e = t.element(s(e)), S(e)) return n.$$setClassImmediately(e, a, i, r);
                    var o, l = e.data("$$animateClasses"),
                        c = !!l;
                    return l || (l = {
                        classes: {}
                    }), o = l.classes, a = f(a) ? a : a.split(" "), u(a, function(n) {
                        n && n.length && (o[n] = !0)
                    }), i = f(i) ? i : i.split(" "), u(i, function(n) {
                        n && n.length && (o[n] = !1)
                    }), c ? (r && l.options && (l.options = t.extend(l.options || {}, r)), l.promise) : (e.data("$$animateClasses", l = {
                        classes: o,
                        options: r
                    }), l.promise = y(function(t) {
                        var a = e.parent(),
                            i = s(e),
                            r = i.parentNode;
                        if (r && !r.$$NG_REMOVED && !i.$$NG_REMOVED) {
                            i = e.data("$$animateClasses"), e.removeData("$$animateClasses");
                            var r = e.data("$$ngAnimateState") || {},
                                o = w(e, i, r.active);
                            return o ? B("setClass", o, e, a, null, function() {
                                o[0] && n.$$addClassImmediately(e, o[0]), o[1] && n.$$removeClassImmediately(e, o[1])
                            }, i.options, t) : t()
                        }
                        t()
                    }))
                },
                cancel: function(n) {
                    n.$$cancelFn()
                },
                enabled: function(n, t) {
                    switch (arguments.length) {
                        case 2:
                            if (n) M(t);
                            else {
                                var e = t.data("$$ngAnimateState") || {};
                                e.disabled = !0, t.data("$$ngAnimateState", e)
                            }
                            break;
                        case 1:
                            v.disabled = !n;
                            break;
                        default:
                            n = !v.disabled
                    }
                    return !!n
                }
            }
        }]), i.register("", ["$window", "$sniffer", "$timeout", "$$animateReflow", function(a, i, r, c) {
            function d() {
                R || (R = c(function() {
                    T = [], R = null, N = {}
                }))
            }

            function v(n, t) {
                R && R(), T.push(t), R = c(function() {
                    u(T, function(n) {
                        n()
                    }), T = [], R = null, N = {}
                })
            }

            function $(n, e) {
                var a = s(n);
                n = t.element(a), j.push(n), a = Date.now() + e, P >= a || (r.cancel(I), P = a, I = r(function() {
                    g(j), j = []
                }, e, !1))
            }

            function g(n) {
                u(n, function(n) {
                    (n = n.data("$$ngAnimateCSS3Data")) && u(n.closeAnimationFns, function(n) {
                        n()
                    })
                })
            }

            function C(n, t) {
                var e = t ? N[t] : null;
                if (!e) {
                    var i = 0,
                        s = 0,
                        r = 0,
                        o = 0;
                    u(n, function(n) {
                        if (1 == n.nodeType) {
                            n = a.getComputedStyle(n) || {}, i = Math.max(p(n[x + "Duration"]), i), s = Math.max(p(n[x + "Delay"]), s), o = Math.max(p(n[F + "Delay"]), o);
                            var t = p(n[F + "Duration"]);
                            t > 0 && (t *= parseInt(n[F + "IterationCount"], 10) || 1), r = Math.max(t, r)
                        }
                    }), e = {
                        total: 0,
                        transitionDelay: s,
                        transitionDuration: i,
                        animationDelay: o,
                        animationDuration: r
                    }, t && (N[t] = e)
                }
                return e
            }

            function p(n) {
                var t = 0;
                return n = m(n) ? n.split(/\s*,\s*/) : [], u(n, function(n) {
                    t = Math.max(parseFloat(n) || 0, t)
                }), t
            }

            function h(n, t, e, a) {
                n = 0 <= ["ng-enter", "ng-leave", "ng-move"].indexOf(e);
                var i, r = t.parent(),
                    l = r.data("$$ngAnimateKey");
                l || (r.data("$$ngAnimateKey", ++O), l = O), i = l + "-" + s(t).getAttribute("class");
                var r = i + " " + e,
                    l = N[r] ? ++N[r].total : 0,
                    u = {};
                if (l > 0) {
                    var c = e + "-stagger",
                        u = i + " " + c;
                    (i = !N[u]) && o.addClass(t, c), u = C(t, u), i && o.removeClass(t, c)
                }
                o.addClass(t, e);
                var c = t.data("$$ngAnimateCSS3Data") || {},
                    f = C(t, r);
                return i = f.transitionDuration, f = f.animationDuration, n && 0 === i && 0 === f ? (o.removeClass(t, e), !1) : (e = a || n && i > 0, n = f > 0 && 0 < u.animationDelay && 0 === u.animationDuration, t.data("$$ngAnimateCSS3Data", {
                    stagger: u,
                    cacheKey: r,
                    running: c.running || 0,
                    itemIndex: l,
                    blockTransition: e,
                    closeAnimationFns: c.closeAnimationFns || []
                }), r = s(t), e && (D(r, !0), a && t.css(a)), n && (r.style[F + "PlayState"] = "paused"), !0)
            }

            function A(n, t, e, a, i) {
                function l() {
                    t.off(R, c), o.removeClass(t, m), o.removeClass(t, d), k && r.cancel(k), w(t, e);
                    var n, a = s(t);
                    for (n in v) a.style.removeProperty(v[n])
                }

                function c(n) {
                    n.stopPropagation();
                    var t = n.originalEvent || n;
                    n = t.$manualTimeStamp || t.timeStamp || Date.now(), t = parseFloat(t.elapsedTime.toFixed(3)), Math.max(n - x, 0) >= b && t >= y && a()
                }
                var f = s(t);
                if (n = t.data("$$ngAnimateCSS3Data"), -1 != f.getAttribute("class").indexOf(e) && n) {
                    var m = "",
                        d = "";
                    u(e.split(" "), function(n, t) {
                        var e = (t > 0 ? " " : "") + n;
                        m += e + "-active", d += e + "-pending"
                    });
                    var v = [],
                        g = n.itemIndex,
                        p = n.stagger,
                        h = 0;
                    if (g > 0) {
                        h = 0, 0 < p.transitionDelay && 0 === p.transitionDuration && (h = p.transitionDelay * g);
                        var A = 0;
                        0 < p.animationDelay && 0 === p.animationDuration && (A = p.animationDelay * g, v.push(E + "animation-play-state")), h = Math.round(100 * Math.max(h, A)) / 100
                    }
                    h || (o.addClass(t, m), n.blockTransition && D(f, !1));
                    var S = C(t, n.cacheKey + " " + m),
                        y = Math.max(S.transitionDuration, S.animationDuration);
                    if (0 !== y) {
                        !h && i && (S.transitionDuration || (t.css("transition", S.animationDuration + "s linear all"), v.push("transition")), t.css(i));
                        var g = Math.max(S.transitionDelay, S.animationDelay),
                            b = 1e3 * g;
                        0 < v.length && (p = f.getAttribute("style") || "", ";" !== p.charAt(p.length - 1) && (p += ";"), f.setAttribute("style", p + " "));
                        var k, x = Date.now(),
                            R = M + " " + B,
                            g = 1e3 * (h + 1.5 * (g + y));
                        return h > 0 && (o.addClass(t, d), k = r(function() {
                            k = null, 0 < S.transitionDuration && D(f, !1), 0 < S.animationDuration && (f.style[F + "PlayState"] = ""), o.addClass(t, m), o.removeClass(t, d), i && (0 === S.transitionDuration && t.css("transition", S.animationDuration + "s linear all"), t.css(i), v.push("transition"))
                        }, 1e3 * h, !1)), t.on(R, c), n.closeAnimationFns.push(function() {
                            l(), a()
                        }), n.running++, $(t, g), l
                    }
                    o.removeClass(t, m), w(t, e), a()
                } else a()
            }

            function D(n, t) {
                n.style[x + "Property"] = t ? "none" : ""
            }

            function S(n, t, e, a) {
                return h(n, t, e, a) ? function(n) {
                    n && w(t, e)
                } : void 0
            }

            function y(n, t, e, a, i) {
                return t.data("$$ngAnimateCSS3Data") ? A(n, t, e, a, i) : (w(t, e), void a())
            }

            function b(n, t, e, a, i) {
                var s = S(n, t, e, i.from);
                if (s) {
                    var r = s;
                    return v(t, function() {
                            r = y(n, t, e, a, i.to)
                        }),
                        function(n) {
                            (r || l)(n)
                        }
                }
                d(), a()
            }

            function w(n, t) {
                o.removeClass(n, t);
                var e = n.data("$$ngAnimateCSS3Data");
                e && (e.running && e.running--, e.running && 0 !== e.running || n.removeData("$$ngAnimateCSS3Data"))
            }

            function k(n, t) {
                var e = "";
                return n = f(n) ? n : n.split(/\s+/), u(n, function(n, a) {
                    n && 0 < n.length && (e += (a > 0 ? " " : "") + n + t)
                }), e
            }
            var x, B, F, M, E = "";
            n.ontransitionend === e && n.onwebkittransitionend !== e ? (E = "-webkit-", x = "WebkitTransition", B = "webkitTransitionEnd transitionend") : (x = "transition", B = "transitionend"), n.onanimationend === e && n.onwebkitanimationend !== e ? (E = "-webkit-", F = "WebkitAnimation", M = "webkitAnimationEnd animationend") : (F = "animation", M = "animationend");
            var R, N = {},
                O = 0,
                T = [],
                I = null,
                P = 0,
                j = [];
            return {
                animate: function(n, t, e, a, i, s) {
                    return s = s || {}, s.from = e, s.to = a, b("animate", n, t, i, s)
                },
                enter: function(n, t, e) {
                    return e = e || {}, b("enter", n, "ng-enter", t, e)
                },
                leave: function(n, t, e) {
                    return e = e || {}, b("leave", n, "ng-leave", t, e)
                },
                move: function(n, t, e) {
                    return e = e || {}, b("move", n, "ng-move", t, e)
                },
                beforeSetClass: function(n, t, e, a, i) {
                    return i = i || {}, t = k(e, "-remove") + " " + k(t, "-add"), (i = S("setClass", n, t, i.from)) ? (v(n, a), i) : (d(), void a())
                },
                beforeAddClass: function(n, t, e, a) {
                    return a = a || {}, (t = S("addClass", n, k(t, "-add"), a.from)) ? (v(n, e), t) : (d(), void e())
                },
                beforeRemoveClass: function(n, t, e, a) {
                    return a = a || {}, (t = S("removeClass", n, k(t, "-remove"), a.from)) ? (v(n, e), t) : (d(), void e())
                },
                setClass: function(n, t, e, a, i) {
                    return i = i || {}, e = k(e, "-remove"), t = k(t, "-add"), y("setClass", n, e + " " + t, a, i.to)
                },
                addClass: function(n, t, e, a) {
                    return a = a || {}, y("addClass", n, k(t, "-add"), e, a.to)
                },
                removeClass: function(n, t, e, a) {
                    return a = a || {}, y("removeClass", n, k(t, "-remove"), e, a.to)
                }
            }
        }])
    }])
}(window, window.angular);
var resourcesApp;
resourcesApp = angular.module("resourcesApp", []).factory("_", ["$window", function(e) {
    return e._
}]).factory("hoverIntent", ["$window", function(e) {
    return e.hoverIntent
}]).constant("templatePrefix", "/typo3conf/ext/t3site/Sites/Main/Resources/Assets/Script/Coffeescript/Resources/templates"), resourcesApp.factory("templatePath", ["templatePrefix", function(e) {
    return function(t) {
        return "" + e + "/" + t
    }
}]);
resourcesApp.factory("FiltersDataService", ["$window", "$q", "$http", "_", function(r, e, s, t) {
    return {
        getFilters: t.memoize(function(e) {
            var s;
            return s = {
                productfamily: this.idsFromObjects(e.productfamily),
                product: this.idsFromObjects(e.product),
                resourcetype: this.idsFromObjects(e.resourcetype),
                productseries: this.idsFromObjects(e.productseries),
                businessUnits: r.ngResourcesData.businessUnits || ""
            }, this.getFrom("/?eID=qscresources/filters&args=" + JSON.stringify(s || {}))
        }, function(r) {
            return JSON.stringify(r)
        }),
        getFrom: function(r) {
            var t;
            return t = e.defer(), s.get(r).success(function(r) {
                return t.resolve(r)
            }), t.promise
        },
        idsFromObjects: function(r, e) {
            return null == e && (e = "uid"), t.pluck(r, e)
        }
    }
}]);
resourcesApp.factory("FiltersStateService", ["_", "$window", "FiltersDataService", function(t, e, r) {
    var s;
    return s = [{
        uid: 0
    }], {
        state: {
            filters: t.extend({
                resourcetype: s,
                productfamily: s,
                productseries: s,
                product: s
            }, e.ngResourcesData.activeFilters),
            options: t.extend({
                resourcetype: s,
                productfamily: s,
                productseries: s,
                product: s
            }, e.ngResourcesData.options)
        },
        resetFilters: function() {
            return t.each(this.state.filters, function(t) {
                return function(e, r) {
                    return t.resetFilter(r)
                }
            }(this))
        },
        resetFilter: function(t) {
            return this.state.filters[t] = s
        },
        updateOptions: function() {
            return r.getFilters(this.state.filters).then(function(t) {
                return function(e) {
                    return t.state.options = e
                }
            }(this))
        }
    }
}]);
resourcesApp.controller("filtersController", ["$scope", "$element", "FiltersStateService", function(e, t, r) {
    return e.filtersStateService = r, e.state = r.state
}]);




var NavigationNestedDropdown, NavigationSubdivision, NavigationSubsection, __bind = function(t, i) {
    return function() {
        return t.apply(i, arguments)
    }
};
NavigationNestedDropdown = function() {
    function t(t) {
        this.$el = $(t), this.trigger = this.$el.find("[data-dropdown-trigger]:first"), this.menu = this.$el.find("[data-dropdown-menu]:first"), this.initTrigger()
    }
    var i;
    return i = "open", t.prototype.initTrigger = function() {
        return this.trigger.click(function(t) {
            return function(n) {
                return n.preventDefault(), t.$el.hasClass(i) ? (t.$el.find("[data-nav-nested-dropdown]").each(function(i, n) {
                    return t.close($(n))
                }), t.close(t.$el)) : t.open()
            }
        }(this))
    }, t.prototype.open = function() {
        return this.$el.addClass(i), this.menu.css({
            display: "block",
            height: "auto"
        }), this.menuHeight = this.menu.outerHeight(), this.menu.css("height", 0), this.menu.animate({
            height: this.menuHeight
        }, 200, function(t) {
            return function() {
                return t.menu.css("height", "auto")
            }
        }(this))
    }, t.prototype.close = function(t) {
        var n;
        return n = t.find("[data-dropdown-menu]:first"), t.removeClass(i), n.animate({
            height: 0
        }, 200)
    }, t
}(), NavigationSubdivision = function() {
    ku=0;
    var namejs = [];
    function t(t) {
        this.triggerHandle = __bind(this.triggerHandle, this), this.$el = $(t), this.trigger = this.$el.find("[data-subdivision-trigger]"), this.menu = this.$el.find("[data-subdivision-menu]"), this.siblings = this.$el.siblings("[data-nav-subdivision]"), this.outside = [this.trigger, this.menu, this.siblings], this.trigger.bind("click", this.triggerHandle), this.trigger.hoverIntent(this.triggerHandle)
namejs[ku] = this.menu.outerHeight();
ku++;
localStorage.setItem("names", JSON.stringify(namejs));
    }
    var i;
    return i = "active", t.prototype.triggerHandle = function(t) {
        var n, e, r;
        return t.preventDefault(), this.$el.hasClass("active") ? void 0 : (r = !1, this.siblings.each(function() {
            return $(this).hasClass(i) ? r = $(this) : void 0
        }), r ? (e = r.find("[data-subdivision-menu]"), n = e.outerHeight(), this.close(e), this.open(n)) : ($("[data-nav-subdivision]").each(function(t) {
            return function(n, e) {
                return $(e).hasClass(i) ? t.close($(e).find("[data-subdivision-menu]")) : void 0
            }
        }(this)), this.open(0)))
    }, t.prototype.bindClose = function() {
        return $("body").mouseup(function(t) {
            return function(i) {
                return t.outHide(i) ? t.close(t.menu) : void 0
            }
        }(this))
    }, t.prototype.outHide = function(t) {
        var i, n, e, r, s;
        for (i = !0, s = this.outside, e = 0, r = s.length; r > e; e++) n = s[e], $(t.target).closest(n).length && (i = !1);
        return i
    }, t.prototype.open = function(t) {
        var n;
        return this.$el.addClass(i), this.menu.css("display", "block"),
		//alert(this.menu.attr("class"));
		//alert(this.menu.attr("class")+" "+this.menu.outerHeight());
		 n = this.menu.outerHeight(), this.menu.css("height", t), this.menu.animate({
            height: n
        }, 250, function(t) {
            return function() {
                return t.trigger.bind("click", t.triggerHandle), t.trigger.hoverIntent(t.triggerHandle), t.bindClose()
            }
        }(this))
    }, t.prototype.close = function(t) {
        return t.animate({
            opacity: 0
        }, 250, function() {
            return $(this).parents("[data-nav-subdivision]").removeClass(i), $(this).css({
                opacity: 1,
				display: "none"
            })
        })
    }, t
}(), NavigationSubsection = function() {
    function t(t) {
        this.$el = $(t), this.trigger = this.$el.find("[data-subsection-trigger]:first"), this.innerMenu = this.$el.find("[data-subsection-menu]:first"), this.menuContainer = this.$el.parents("[data-subdivision-menu]:first"), this.siblings = this.$el.siblings("[data-nav-subsection]"), this.trigger.click(function(t) {
            return function(i) {
                return i.preventDefault(), t.setMenuHeight(), t.setActiveMenu()
            }
        }(this))
    }
    var i;
var ju;
    ju=localStorage.getItem("names");
    return i = "active", t.prototype.setMenuHeight = function() {
        var t, i;
        return t = 0, this.$el.parents("[data-subsection-menu]").each(function() {
            var i;
            return i = $(this).outerHeight(), i > t ? t = i : void 0
 }), t += JSON.parse(ju)[0], this.innerMenu.outerHeight() > JSON.parse(ju)[0] ? i = this.innerMenu.outerHeight() + 20 : i=JSON.parse(ju)[0], this.menuContainer.css("height", this.menuContainer.outerHeight()), this.menuContainer.animate({
            height: i > t ? i : t + 20            
        })
    /*    }), t += JSON.parse(ju)[0], this.innerMenu.outerHeight() > JSON.parse(ju)[0] ? i = this.innerMenu.outerHeight() + 20 : i=JSON.parse(ju)[0], this.menuContainer.css("height", this.menuContainer.outerHeight()), this.menuContainer.animate({
            height: i > t ? i : t
        })*/

    }, t.prototype.setActiveMenu = function() {
        return this.siblings.each(function() {
            return function(t, n) {
                return $(n).hasClass(i) ? $(n).removeClass(i) : void 0
            }
        }(this)), this.$el.addClass(i)
    }, t
}(), $.fn.extend({
    navigationNestedDropdown: function() {
        return this.each(function() {
            return new NavigationNestedDropdown(this)
        })
    },
    navigationSubdivision: function() {
        return this.each(function() {
            return new NavigationSubdivision(this)

        })
    },
    navigationSubsection: function() {
        return this.each(function() {
            return new NavigationSubsection(this)
        })
    }
}), $(function() {
    return $("a[data-subsection-trigger]").each(function(t, i) {
        var n;
        return n = $(i), n.next("ul").prepend($('<li><a href="' + n.attr("href") + '">' + n.text().split(" ▸")[0] + "</a></li>"))
    }), $("a[data-subdivision-header]").each(function(t, i) {
        var n;
        return n = $(i), n.next("div").find("ul:eq(0)").prepend($('<li><a href="' + n.attr("href") + '">' + n.text() + "</a></li>"))
    }), $("a[data-clone-overviews]").each(function(t, i) {
        var n;
        return n = $(i), n.next("ul").prepend($('<li class="nav-subdivision-overview"><a href="' + n.attr("href") + '">' + n.text() + "</a></li>"))
    })
});

var TableScrollRemaining;
TableScrollRemaining = function() {
    function t(t) {
        this.$el = $(t), this.pane = this.$el.find("[data-pane]"), this.initState(), this.bindScroll()
    }
    var e, n, i;
    return i = "scroll-remaining-right", n = "scroll-remaining-left", e = 550, t.prototype.checkRight = function() {
        return e - this.pane.scrollLeft() > this.pane.innerWidth() ? this.$el.addClass(i) : this.$el.removeClass(i)
    }, t.prototype.checkLeft = function() {
        return this.pane.scrollLeft() > 0 ? this.$el.addClass(n) : this.$el.removeClass(n)
    }, t.prototype.initState = function() {
        return this.checkLeft()
    }, t.prototype.bindScroll = function() {
        return this.pane.on("scroll", function(t) {
            return function() {
                return t.checkRight(), t.checkLeft()
            }
        }(this))
    }, t
}(), $.fn.extend({
    tableScroll: function() {
        return this.each(function() {
            return new TableScrollRemaining(this)
        })
    }
});

var Tabs;
Tabs = function() {
    function t(t) {
        this.$el = $(t), this.tabLinks = this.$el.find("[data-tab-link]"), this.tabContent = this.$el.find("[data-tab-content]"), this.tabImage = this.$el.find("[data-tab-image]"), this.switchTab(1), this.bindLinks()
    }
    var a;
    return a = "active", t.prototype.bindLinks = function() {
        return this.tabLinks.click(function(t) {
            return function(a) {
                var i;
                return a.preventDefault(), i = $(a.target).closest("li").find("[data-tab-link]"), t.switchTab(i.data("tabLink"))
            }
        }(this))
    }, t.prototype.switchTab = function(t) {
        return this.tabLinks.each(function() {
            return $(this).parent("li").removeClass(a), $(this).data("tabLink") === t ? $(this).parent("li").addClass(a) : void 0
        }), this.tabContent.each(function() {
            return $(this).removeClass(a), $(this).data("tabContent") === t ? $(this).addClass(a) : void 0
        }), this.tabImage.each(function() {
            return $(this).removeClass(a), $(this).data("tabImage") === t ? $(this).addClass(a) : void 0
        })
    }, t
}(), $.fn.extend({
    tabs: function() {
        return this.each(function() {
            return new Tabs(this)
        })
    }
});
var QscTableSupport;
QscTableSupport = function() {
    function t(t) {
        this.$el = $(t), this.update()
    }
    return t.prototype.update = function() {
        var t;
        return t = this.$el.wrap('<div class="container-table-well"><div class="container-table" data-pane></div></div>'), t.parents(".container-table-well:eq(0)").tableScroll()
    }, t
}(), $.fn.extend({
    qscTableSupport: function() {
        return new QscTableSupport(this)
    }
});
angular.bootstrap(document.getElementById("ngResourcesApp"), ["resourcesApp"]);
$(function() {
    var t, e, a, o, s;
    return $("[data-mh]").matchHeight(), $("[data-nav-subsection]").navigationSubsection(), $("[data-nav-subdivision]").navigationSubdivision(), $("[data-nav-nested-dropdown]").navigationNestedDropdown(), a = document.querySelector("[data-site-header]"), a && (Headroom.options.offset = 250, o = new Headroom(a), o.init()), $("[data-button-dropdown]").veggieburger({
        transitionHeight: "[data-dropdown-detail]"
    }), $("[data-list-nested]").veggieburger({
        triggers: "[data-list-trigger]",
        transitionHeight: "[data-list]"
    }), $("[data-tabs]").tabs(), e = $("[data-hero-slider]").slick({
        dots: !0,
        prevArrow: "[data-slider-left]",
        nextArrow: "[data-slider-right]",
        autoplay: !0,
        autoplaySpeed: 5e3
    }), $("[data-hero-slider-fade]").slick({
        dots: !0,
        prevArrow: "[data-slider-left]",
        nextArrow: "[data-slider-right]",
        autoplay: !0,
        autoplaySpeed: 5e3,
        fade: !0,
        cssEase: "linear"
    }), s = $("[data-rotating-grid]").slick({
        dots: !1,
        prevArrow: "",
        nextArrow: "",
        autoplay: !0,
        autoplaySpeed: 2e3,
        fade: !0,
        cssEase: "linear"
    }), $("[data-image-slider-small]").slick({
        infinite: !1,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 860,
            settings: {
                slidesToShow: 3
            }
        }, {
            breakpoint: 630,
            settings: {
                slidesToShow: 1
            }
        }]
    }), $("[data-hero-slider], [data-hero-slider-fade]").mousedown(function() {
        var t;
        return t = $(this).slick("slickGetOption", "autoplay"), t === !0 ? $(this).slick("slickSetOption", "autoplay", !1) : $(this).slick("slickSetOption", "autoplay", !0)
    }), $("[data-slider-right], [data-slider-left]").click(function() {
        return $(this).siblings("[data-hero-slider]").slick("slickSetOption", "autoplay", !1), $(this).siblings("[data-hero-slider-fade]").slick("slickSetOption", "autoplay", !1)
    }), Modernizr.touchevents || $("[data-hero-zoom]").zoom({
        magnify: 1.5,
        on: "click",
        touch: "true",
        onZoomIn: function() {
            return $(this).parent("[data-hero-zoom]").addClass("zoom-active"), !1
        },
        onZoomOut: function() {
            return $(this).parent("[data-hero-zoom]").removeClass("zoom-active"), !1
        }
    }), Modernizr.touchevents && $("[data-hero-zoom]").each(function() {
        return new RTP.PinchZoom($(this), {})
    }), $(".colorbox-example-gallery img").colorbox({
        rel: "group-1",
        transition: "elastic",
        speed: 200,
        maxWidth: "80%",
        current: "<span>{current} of {total}</span>",
        previous: '<span class="button-caret-thin-left"><span class="screen-reader-text"></span></span>',
        next: '<span class="button-caret-thin-right"><span class="screen-reader-text"></span></span>',
        close: '<span class="screen-reader-text">Click to close overlay</span>',
        overlayClose: !0
    }), t = $(".colorbox-gallery ul[data-list] li a").colorbox({
        rel: "group-1",
        transition: "elastic",
        speed: 200,
        maxWidth: "70%",
        maxHeight: "70%",
        current: "<span>{current} of {total}</span>",
        previous: '<span class="button-caret-thin-left"><span class="screen-reader-text"></span></span>',
        next: '<span class="button-caret-thin-right"><span class="screen-reader-text"></span></span>',
        close: '<span class="screen-reader-text">Click to close overlay</span>',
        overlayClose: !0
    }), $(".colorbox-gallery a[data-list-trigger]").click(function() {
        return $("a#openGallery").click(function() {
            return t.eq(0).click()
        })
    }), $(".hero-slider.full figure").matchHeight(), $("[data-table-well]").tableScroll(), $(function() {
        return $(".csc-typography > table").qscTableSupport()
    })
});
$(function() {
    return $("[data-form-clear]").click(function(r) {
        var t;
        return r.preventDefault(), t = $(this).parents("form"), t.children("#reset").val(!0), t.trigger("submit")
    })
});
$(function() {
    return $("[data-modal-link]").each(function() {
        var t;
        return t = {
            close: '<span class="screen-reader-text">Click to close overlay</span>',
            overlayClose: !0,
            inline: !0,
            maxWidth: "80%",
            className: "arbitrary-content"
        }, t.width = null != $(this).data("width") ? $(this).data("width") : window.outerWidth > 550 ? "50%" : "90%", $(this).colorbox(t)
    }), $("[data-autopop-link]").each(function() {
        var t;
        return t = {
            close: '<span class="screen-reader-text">Click to close overlay</span>',
            overlayClose: !0,
            maxWidth: "80%",
            className: "autopop-content",
            open: !0,
            html: $("[data-autopop-modal]")
        }, t.width = null != $(this).data("width") ? $(this).data("width") : window.outerWidth > 550 ? "50%" : "90%", $(this).colorbox(t)
    })
});
