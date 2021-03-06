<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="https://gems.tools/favicon.png" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <title>GemsTools | NFT Rankings Platform</title>
    <meta property="og:title" content="GemsTools | NFT Rankings Platform" />
    <meta property="og:url" content="https://gems.tools" />
    <meta name="description" content="NFT rankings for current projects in the market with real-time analysis on project potential, project growth and project evaluation. " />
    <meta property="og:description" content="NFT rankings for current projects in the market with real-time analysis on project potential, project growth and project evaluation. " />
    <meta property="og:type" content="website" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="/static/css/2.82e607c1.chunk.css" rel="stylesheet">
    <link href="/static/css/main.beaf258e.chunk.css" rel="stylesheet">

</head>

<body><noscript>You need to enable JavaScript to run this app.</noscript>
    <div id="root"></div>
    <script>
        $(document).ready(function() {
            $('.mobile-nav').css('display', 'none');

            $(document).on('click', function(e) {
                const target1 = e.target.closest('#logout-user')
                const target2 = e.target.closest('#mobile-logout-user')
                const target3 = e.target.closest('.hemburger')
                if (target3 !== null) {
                    $('.mobile-nav').slideToggle();
                } else if (target1 !== null || target2 !== null) {
                    $('.dropdown-menu-right').toggleClass('d-block');
                } else {
                    $('.mobile-nav').slideUp();
                    $('.dropdown-menu-right').removeClass('d-block');
                }
            });
        });
    </script>
    <script>
        ! function(c) {
            function e(e) {
                for (var t, r, n = e[0], o = e[1], u = e[2], i = 0, a = []; i < n.length; i++) r = n[i], f[r] && a.push(f[r]
                    [0]), f[r] = 0;
                for (t in o) Object.prototype.hasOwnProperty.call(o, t) && (c[t] = o[t]);
                for (d && d(e); a.length;) a.shift()();
                return p.push.apply(p, u || []), l()
            }

            function l() {
                for (var e, t = 0; t < p.length; t++) {
                    for (var r = p[t], n = !0, o = 1; o < r.length; o++) {
                        var u = r[o];
                        0 !== f[u] && (n = !1)
                    }
                    n && (p.splice(t--, 1), e = s(s.s = r[0]))
                }
                return e
            }
            var r = {},
                f = {
                    3: 0
                },
                p = [];

            function s(e) {
                if (r[e]) return r[e].exports;
                var t = r[e] = {
                    i: e,
                    l: !1,
                    exports: {}
                };
                return c[e].call(t.exports, t, t.exports, s), t.l = !0, t.exports
            }
            s.e = function(u) {
                var e = [],
                    r = f[u];
                if (0 !== r)
                    if (r) e.push(r[2]);
                    else {
                        var t = new Promise(function(e, t) {
                            r = f[u] = [e, t]
                        });
                        e.push(r[2] = t);
                        var n, o = document.getElementsByTagName("head")[0],
                            i = document.createElement("script");
                        i.charset = "utf-8", i.timeout = 120, s.nc && i.setAttribute("nonce", s.nc), i.src = s.p +
                            "static/js/" + ({} [u] || u) + "." + {
                                1: "67d52811"
                            } [u] + ".chunk.js", n = function(e) {
                                i.onerror = i.onload = null, clearTimeout(a);
                                var t = f[u];
                                if (0 !== t) {
                                    if (t) {
                                        var r = e && ("load" === e.type ? "missing" : e.type),
                                            n = e && e.target && e.target.src,
                                            o = new Error("Loading chunk " + u + " failed.\n(" + r + ": " + n + ")");
                                        o.type = r, o.request = n, t[1](o)
                                    }
                                    f[u] = void 0
                                }
                            };
                        var a = setTimeout(function() {
                            n({
                                type: "timeout",
                                target: i
                            })
                        }, 12e4);
                        i.onerror = i.onload = n, o.appendChild(i)
                    } return Promise.all(e)
            }, s.m = c, s.c = r, s.d = function(e, t, r) {
                s.o(e, t) || Object.defineProperty(e, t, {
                    enumerable: !0,
                    get: r
                })
            }, s.r = function(e) {
                "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                    value: "Module"
                }), Object.defineProperty(e, "__esModule", {
                    value: !0
                })
            }, s.t = function(t, e) {
                if (1 & e && (t = s(t)), 8 & e) return t;
                if (4 & e && "object" == typeof t && t && t.__esModule) return t;
                var r = Object.create(null);
                if (s.r(r), Object.defineProperty(r, "default", {
                        enumerable: !0,
                        value: t
                    }), 2 & e && "string" != typeof t)
                    for (var n in t) s.d(r, n, function(e) {
                        return t[e]
                    }.bind(null, n));
                return r
            }, s.n = function(e) {
                var t = e && e.__esModule ? function() {
                    return e.default
                } : function() {
                    return e
                };
                return s.d(t, "a", t), t
            }, s.o = function(e, t) {
                return Object.prototype.hasOwnProperty.call(e, t)
            }, s.p = "/", s.oe = function(e) {
                throw console.error(e), e
            };
            var t = window.webpackJsonp = window.webpackJsonp || [],
                n = t.push.bind(t);
            t.push = e, t = t.slice();
            for (var o = 0; o < t.length; o++) e(t[o]);
            var d = n;
            l()
        }([])
    </script>
    <script src="{{ asset('') }}static/js/2.6c895675.chunk.js"></script>
    <script src="{{ asset('') }}static/js/main.dec8df7d.chunk.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JXS56GGJV4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-JXS56GGJV4');
    </script>
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '500427508152117');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=500427508152117&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

</body>

</html>
