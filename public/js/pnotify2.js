function pnAlert(t) {
    var i = "undefined" != typeof t && "undefined" != typeof t.type ? t.type : "error",
        e = "undefined" != typeof t && "undefined" != typeof t.title ? t.title : "Alert!",
        o = "undefined" != typeof t && "undefined" != typeof t.text ? t.text : "Alert!",
        n = "undefined" != typeof t && "undefined" != typeof t.addClass ? t.addClass : "",
        s = "undefined" != typeof t && "undefined" != typeof t.closeLabel ? t.closeLabel : "Close";
    new PNotify({
        type: i,
        title: e,
        text: o,
        addclass: n,
        hide: !1,
        buttons: {
            closer: !0,
            sticker: !1,
            labels: {
                close: s
            }
        },
        history: {
            history: !1
        }
    })
}

function pnConfirm(t) {
    var i = "undefined" != typeof t && "undefined" != typeof t.title ? t.title : "Confirmation Needed",
        e = "undefined" != typeof t && "undefined" != typeof t.text ? t.text : "Are you sure?",
        o = "undefined" != typeof t && "undefined" != typeof t.addClass ? t.addClass : "",
        n = "undefined" != typeof t && "undefined" != typeof t.icon ? t.icon : "glyphicon glyphicon-question-sign",
        s = "undefined" != typeof t && "undefined" != typeof t.closeLabel ? t.closeLabel : "Close",
        a = "undefined" != typeof t && "undefined" != typeof t.modal ? t.modal : !0,
        c = "undefined" != typeof t && "undefined" != typeof t.confirmButtonCaption ? t.confirmButtonCaption : "Ok",
        l = "undefined" != typeof t && "undefined" != typeof t.confirmButtonClass ? t.confirmButtonClass : "pn-confirm-button",
        r = "undefined" != typeof t && "undefined" != typeof t.confirmFunction ? t.confirmFunction : null,
        p = "undefined" != typeof t && "undefined" != typeof t.confirmData ? t.confirmData : null,
        u = "undefined" != typeof t && "undefined" != typeof t.cancelButtonCaption ? t.cancelButtonCaption : "Cancel",
        d = "undefined" != typeof t && "undefined" != typeof t.cancelButtonClass ? t.cancelButtonClass : "pn-cancel-button",
        f = "undefined" != typeof t && "undefined" != typeof t.cancelFunction ? t.cancelFunction : null,
        h = "undefined" != typeof t && "undefined" != typeof t.cancelData ? t.cancelData : null,
        m = {
            dir1: "down",
            dir2: "right",
            modal: a
        },
        y = new PNotify({
            title: i,
            text: e,
            // addclass: o,
            addclass: "stack-modal" + (o ? ' ' + o : ''),
            icon: n,
            hide: !1,
            confirm: {
                confirm: !0,
                buttons: [{
                    text: c,
                    addClass: l,
                    promptTrigger: !0,
                    click: function(t, i) {
                        t.remove(), t.get().trigger("pnotify.confirm", [t, i])
                    }
                }, {
                    text: u,
                    addClass: d,
                    click: function(t) {
                        t.remove(), t.get().trigger("pnotify.cancel", t)
                    }
                }]
            },
            buttons: {
                closer: !1,
                sticker: !1,
                labels: {
                    close: s
                }
            },
            history: {
                history: !1
            },
            stack: m
        }).get();
    "function" == typeof r && y.on("pnotify.confirm", p, r), "function" == typeof f && y.on("pnotify.cancel", h, f)
}! function(t, i) {
    "function" == typeof define && define.amd ? define("pnotify", ["jquery"], function(e) {
        return i(e, t)
    }) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), global || t) : t.PNotify = i(t.jQuery, t)
}(this, function(t, i) {
    var e = function(i) {
        var o, n, s = {
                dir1: "down",
                dir2: "left",
                push: "bottom",
                spacing1: 36,
                spacing2: 36,
                context: t("body"),
                modal: !1
            },
            a = t(i),
            c = function() {
                n = t("body"), r.prototype.options.stack.context = n, a = t(i), a.bind("resize", function() {
                    o && clearTimeout(o), o = setTimeout(function() {
                        r.positionAll(!0)
                    }, 10)
                })
            },
            l = function(i) {
                var e = t("<div />", {
                    "class": "ui-pnotify-modal-overlay"
                });
                return e.prependTo(i.context), i.overlay_close && e.click(function() {
                    r.removeStack(i)
                }), e
            },
            r = function(t) {
                this.parseOptions(t), this.init()
            };
        return t.extend(r.prototype, {
            version: "3.0.0",
            options: {
                title: !1,
                title_escape: !1,
                text: !1,
                text_escape: !1,
                styling: "brighttheme",
                addclass: "",
                cornerclass: "",
                auto_display: !0,
                width: "300px",
                min_height: "16px",
                type: "notice",
                icon: !0,
                animation: "fade",
                animate_speed: "normal",
                shadow: !0,
                hide: !0,
                delay: 8e3,
                mouse_reset: !0,
                remove: !0,
                insert_brs: !0,
                destroy: !0,
                stack: s
            },
            modules: {},
            runModules: function(t, i) {
                var e;
                for (var o in this.modules) e = "object" == typeof i && o in i ? i[o] : i, "function" == typeof this.modules[o][t] && (this.modules[o].notice = this, this.modules[o].options = "object" == typeof this.options[o] ? this.options[o] : {}, this.modules[o][t](this, "object" == typeof this.options[o] ? this.options[o] : {}, e))
            },
            state: "initializing",
            timer: null,
            animTimer: null,
            styles: null,
            elem: null,
            container: null,
            title_container: null,
            text_container: null,
            animating: !1,
            timerHide: !1,
            init: function() {
                var i = this;
                return this.modules = {}, t.extend(!0, this.modules, r.prototype.modules), "object" == typeof this.options.styling ? this.styles = this.options.styling : this.styles = r.styling[this.options.styling], this.elem = t("<div />", {
                    "class": "ui-pnotify " + this.options.addclass,
                    css: {
                        display: "none"
                    },
                    "aria-live": "assertive",
                    "aria-role": "alertdialog",
                    mouseenter: function(t) {
                        if (i.options.mouse_reset && "out" === i.animating) {
                            if (!i.timerHide) return;
                            i.cancelRemove()
                        }
                        i.options.hide && i.options.mouse_reset && i.cancelRemove()
                    },
                    mouseleave: function(t) {
                        i.options.hide && i.options.mouse_reset && "out" !== i.animating && i.queueRemove(), r.positionAll()
                    }
                }), "fade" === this.options.animation && this.elem.addClass("ui-pnotify-fade-" + this.options.animate_speed), this.container = t("<div />", {
                    "class": this.styles.container + " ui-pnotify-container " + ("error" === this.options.type ? this.styles.error : "info" === this.options.type ? this.styles.info : "success" === this.options.type ? this.styles.success : this.styles.notice),
                    role: "alert"
                }).appendTo(this.elem), "" !== this.options.cornerclass && this.container.removeClass("ui-corner-all").addClass(this.options.cornerclass), this.options.shadow && this.container.addClass("ui-pnotify-shadow"), this.options.icon !== !1 && t("<div />", {
                    "class": "ui-pnotify-icon"
                }).append(t("<span />", {
                    "class": this.options.icon === !0 ? "error" === this.options.type ? this.styles.error_icon : "info" === this.options.type ? this.styles.info_icon : "success" === this.options.type ? this.styles.success_icon : this.styles.notice_icon : this.options.icon
                })).prependTo(this.container), this.title_container = t("<h4 />", {
                    "class": "ui-pnotify-title"
                }).appendTo(this.container), this.options.title === !1 ? this.title_container.hide() : this.options.title_escape ? this.title_container.text(this.options.title) : this.title_container.html(this.options.title), this.text_container = t("<div />", {
                    "class": "ui-pnotify-text",
                    "aria-role": "alert"
                }).appendTo(this.container), this.options.text === !1 ? this.text_container.hide() : this.options.text_escape ? this.text_container.text(this.options.text) : this.text_container.html(this.options.insert_brs ? String(this.options.text).replace(/\n/g, "<br />") : this.options.text), "string" == typeof this.options.width && this.elem.css("width", this.options.width), "string" == typeof this.options.min_height && this.container.css("min-height", this.options.min_height), "top" === this.options.stack.push ? r.notices = t.merge([this], r.notices) : r.notices = t.merge(r.notices, [this]), "top" === this.options.stack.push && this.queuePosition(!1, 1), this.options.stack.animation = !1, this.runModules("init"), this.options.auto_display && this.open(), this
            },
            update: function(i) {
                var e = this.options;
                return this.parseOptions(e, i), this.elem.removeClass("ui-pnotify-fade-slow ui-pnotify-fade-normal ui-pnotify-fade-fast"), "fade" === this.options.animation && this.elem.addClass("ui-pnotify-fade-" + this.options.animate_speed), this.options.cornerclass !== e.cornerclass && this.container.removeClass("ui-corner-all " + e.cornerclass).addClass(this.options.cornerclass), this.options.shadow !== e.shadow && (this.options.shadow ? this.container.addClass("ui-pnotify-shadow") : this.container.removeClass("ui-pnotify-shadow")), this.options.addclass === !1 ? this.elem.removeClass(e.addclass) : this.options.addclass !== e.addclass && this.elem.removeClass(e.addclass).addClass(this.options.addclass), this.options.title === !1 ? this.title_container.slideUp("fast") : this.options.title !== e.title && (this.options.title_escape ? this.title_container.text(this.options.title) : this.title_container.html(this.options.title), e.title === !1 && this.title_container.slideDown(200)), this.options.text === !1 ? this.text_container.slideUp("fast") : this.options.text !== e.text && (this.options.text_escape ? this.text_container.text(this.options.text) : this.text_container.html(this.options.insert_brs ? String(this.options.text).replace(/\n/g, "<br />") : this.options.text), e.text === !1 && this.text_container.slideDown(200)), this.options.type !== e.type && this.container.removeClass(this.styles.error + " " + this.styles.notice + " " + this.styles.success + " " + this.styles.info).addClass("error" === this.options.type ? this.styles.error : "info" === this.options.type ? this.styles.info : "success" === this.options.type ? this.styles.success : this.styles.notice), (this.options.icon !== e.icon || this.options.icon === !0 && this.options.type !== e.type) && (this.container.find("div.ui-pnotify-icon").remove(), this.options.icon !== !1 && t("<div />", {
                    "class": "ui-pnotify-icon"
                }).append(t("<span />", {
                    "class": this.options.icon === !0 ? "error" === this.options.type ? this.styles.error_icon : "info" === this.options.type ? this.styles.info_icon : "success" === this.options.type ? this.styles.success_icon : this.styles.notice_icon : this.options.icon
                })).prependTo(this.container)), this.options.width !== e.width && this.elem.animate({
                    width: this.options.width
                }), this.options.min_height !== e.min_height && this.container.animate({
                    minHeight: this.options.min_height
                }), this.options.hide ? e.hide || this.queueRemove() : this.cancelRemove(), this.queuePosition(!0), this.runModules("update", e), this
            },
            open: function() {
                this.state = "opening", this.runModules("beforeOpen");
                var t = this;
                return this.elem.parent().length || this.elem.appendTo(this.options.stack.context ? this.options.stack.context : n), "top" !== this.options.stack.push && this.position(!0), this.animateIn(function() {
                    t.queuePosition(!0), t.options.hide && t.queueRemove(), t.state = "open", t.runModules("afterOpen")
                }), this
            },
            remove: function(e) {
                this.state = "closing", this.timerHide = !!e, this.runModules("beforeClose");
                var o = this;
                return this.timer && (i.clearTimeout(this.timer), this.timer = null), this.animateOut(function() {
                    if (o.state = "closed", o.runModules("afterClose"), o.queuePosition(!0), o.options.remove && o.elem.detach(), o.runModules("beforeDestroy"), o.options.destroy && null !== r.notices) {
                        var i = t.inArray(o, r.notices); - 1 !== i && r.notices.splice(i, 1)
                    }
                    o.runModules("afterDestroy")
                }), this
            },
            get: function() {
                return this.elem
            },
            parseOptions: function(i, e) {
                this.options = t.extend(!0, {}, r.prototype.options), this.options.stack = r.prototype.options.stack;
                for (var o, n = [i, e], s = 0; s < n.length && (o = n[s], "undefined" != typeof o); s++)
                    if ("object" != typeof o) this.options.text = o;
                    else
                        for (var a in o) this.modules[a] ? t.extend(!0, this.options[a], o[a]) : this.options[a] = o[a]
            },
            animateIn: function(t) {
                this.animating = "in";
                var i = this;
                t = function() {
                    i.animTimer && clearTimeout(i.animTimer), "in" === i.animating && (i.elem.is(":visible") ? (this && this.call(), i.animating = !1) : i.animTimer = setTimeout(t, 40))
                }.bind(t), "fade" === this.options.animation ? (this.elem.one("webkitTransitionEnd mozTransitionEnd MSTransitionEnd oTransitionEnd transitionend", t).addClass("ui-pnotify-in"), this.elem.css("opacity"), this.elem.addClass("ui-pnotify-fade-in"), this.animTimer = setTimeout(t, 650)) : (this.elem.addClass("ui-pnotify-in"), t())
            },
            animateOut: function(t) {
                this.animating = "out";
                var i = this;
                t = function() {
                    i.animTimer && clearTimeout(i.animTimer), "out" === i.animating && ("0" != i.elem.css("opacity") && i.elem.is(":visible") ? i.animTimer = setTimeout(t, 40) : (i.elem.removeClass("ui-pnotify-in"), this && this.call(), i.animating = !1))
                }.bind(t), "fade" === this.options.animation ? (this.elem.one("webkitTransitionEnd mozTransitionEnd MSTransitionEnd oTransitionEnd transitionend", t).removeClass("ui-pnotify-fade-in"), this.animTimer = setTimeout(t, 650)) : (this.elem.removeClass("ui-pnotify-in"), t())
            },
            position: function(t) {
                var i = this.options.stack,
                    e = this.elem;
                if ("undefined" == typeof i.context && (i.context = n), i) {
                    "number" != typeof i.nextpos1 && (i.nextpos1 = i.firstpos1), "number" != typeof i.nextpos2 && (i.nextpos2 = i.firstpos2), "number" != typeof i.addpos2 && (i.addpos2 = 0);
                    var o = !e.hasClass("ui-pnotify-in");
                    if (!o || t) {
                        i.modal && (i.overlay ? i.overlay.show() : i.overlay = l(i)), e.addClass("ui-pnotify-move");
                        var s, c, r;
                        switch (i.dir1) {
                            case "down":
                                r = "top";
                                break;
                            case "up":
                                r = "bottom";
                                break;
                            case "left":
                                r = "right";
                                break;
                            case "right":
                                r = "left"
                        }
                        s = parseInt(e.css(r).replace(/(?:\..*|[^0-9.])/g, "")), isNaN(s) && (s = 0), "undefined" != typeof i.firstpos1 || o || (i.firstpos1 = s, i.nextpos1 = i.firstpos1);
                        var p;
                        switch (i.dir2) {
                            case "down":
                                p = "top";
                                break;
                            case "up":
                                p = "bottom";
                                break;
                            case "left":
                                p = "right";
                                break;
                            case "right":
                                p = "left"
                        }
                        switch (c = parseInt(e.css(p).replace(/(?:\..*|[^0-9.])/g, "")), isNaN(c) && (c = 0), "undefined" != typeof i.firstpos2 || o || (i.firstpos2 = c, i.nextpos2 = i.firstpos2), ("down" === i.dir1 && i.nextpos1 + e.height() > (i.context.is(n) ? a.height() : i.context.prop("scrollHeight")) || "up" === i.dir1 && i.nextpos1 + e.height() > (i.context.is(n) ? a.height() : i.context.prop("scrollHeight")) || "left" === i.dir1 && i.nextpos1 + e.width() > (i.context.is(n) ? a.width() : i.context.prop("scrollWidth")) || "right" === i.dir1 && i.nextpos1 + e.width() > (i.context.is(n) ? a.width() : i.context.prop("scrollWidth"))) && (i.nextpos1 = i.firstpos1, i.nextpos2 += i.addpos2 + ("undefined" == typeof i.spacing2 ? 25 : i.spacing2), i.addpos2 = 0), "number" == typeof i.nextpos2 && (i.animation ? e.css(p, i.nextpos2 + "px") : (e.removeClass("ui-pnotify-move"), e.css(p, i.nextpos2 + "px"), e.css(p), e.addClass("ui-pnotify-move"))), i.dir2) {
                            case "down":
                            case "up":
                                e.outerHeight(!0) > i.addpos2 && (i.addpos2 = e.height());
                                break;
                            case "left":
                            case "right":
                                e.outerWidth(!0) > i.addpos2 && (i.addpos2 = e.width())
                        }
                        switch ("number" == typeof i.nextpos1 && (i.animation ? e.css(r, i.nextpos1 + "px") : (e.removeClass("ui-pnotify-move"), e.css(r, i.nextpos1 + "px"), e.css(r), e.addClass("ui-pnotify-move"))), i.dir1) {
                            case "down":
                            case "up":
                                i.nextpos1 += e.height() + ("undefined" == typeof i.spacing1 ? 25 : i.spacing1);
                                break;
                            case "left":
                            case "right":
                                i.nextpos1 += e.width() + ("undefined" == typeof i.spacing1 ? 25 : i.spacing1)
                        }
                    }
                    return this
                }
            },
            queuePosition: function(t, i) {
                return o && clearTimeout(o), i || (i = 10), o = setTimeout(function() {
                    r.positionAll(t)
                }, i), this
            },
            cancelRemove: function() {
                return this.timer && i.clearTimeout(this.timer), this.animTimer && i.clearTimeout(this.animTimer), "closing" === this.state && (this.state = "open", this.animating = !1, this.elem.addClass("ui-pnotify-in"), "fade" === this.options.animation && this.elem.addClass("ui-pnotify-fade-in")), this
            },
            queueRemove: function() {
                var t = this;
                return this.cancelRemove(), this.timer = i.setTimeout(function() {
                    t.remove(!0)
                }, isNaN(this.options.delay) ? 0 : this.options.delay), this
            }
        }), t.extend(r, {
            notices: [],
            reload: e,
            removeAll: function() {
                t.each(r.notices, function() {
                    this.remove && this.remove(!1)
                })
            },
            removeStack: function(i) {
                t.each(r.notices, function() {
                    this.remove && this.options.stack === i && this.remove(!1)
                })
            },
            positionAll: function(i) {
                if (o && clearTimeout(o), o = null, r.notices && r.notices.length) t.each(r.notices, function() {
                    var t = this.options.stack;
                    t && (t.overlay && t.overlay.hide(), t.nextpos1 = t.firstpos1, t.nextpos2 = t.firstpos2, t.addpos2 = 0, t.animation = i)
                }), t.each(r.notices, function() {
                    this.position()
                });
                else {
                    var e = r.prototype.options.stack;
                    e && (delete e.nextpos1, delete e.nextpos2)
                }
            },
            styling: {
                brighttheme: {
                    container: "brighttheme",
                    notice: "brighttheme-notice",
                    notice_icon: "brighttheme-icon-notice",
                    info: "brighttheme-info",
                    info_icon: "brighttheme-icon-info",
                    success: "brighttheme-success",
                    success_icon: "brighttheme-icon-success",
                    error: "brighttheme-error",
                    error_icon: "brighttheme-icon-error"
                },
                jqueryui: {
                    container: "ui-widget ui-widget-content ui-corner-all",
                    notice: "ui-state-highlight",
                    notice_icon: "ui-icon ui-icon-info",
                    info: "",
                    info_icon: "ui-icon ui-icon-info",
                    success: "ui-state-default",
                    success_icon: "ui-icon ui-icon-circle-check",
                    error: "ui-state-error",
                    error_icon: "ui-icon ui-icon-alert"
                },
                bootstrap3: {
                    container: "alert",
                    notice: "alert-warning",
                    notice_icon: "glyphicon glyphicon-exclamation-sign",
                    info: "alert-info",
                    info_icon: "glyphicon glyphicon-info-sign",
                    success: "alert-success",
                    success_icon: "glyphicon glyphicon-ok-sign",
                    error: "alert-danger",
                    error_icon: "glyphicon glyphicon-warning-sign"
                }
            }
        }), r.styling.fontawesome = t.extend({}, r.styling.bootstrap3), t.extend(r.styling.fontawesome, {
            notice_icon: "fa fa-exclamation-circle",
            info_icon: "fa fa-info",
            success_icon: "fa fa-check",
            error_icon: "fa fa-warning"
        }), i.document.body ? c() : t(c), r
    };
    return e(i)
}),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.animate", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        i.prototype.options.animate = {
            animate: !1,
            in_class: "",
            out_class: ""
        }, i.prototype.modules.animate = {
            init: function(t, i) {
                this.setUpAnimations(t, i), t.attention = function(i, e) {
                    t.elem.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
                        t.elem.removeClass(i), e && e.call(t)
                    }).addClass("animated " + i)
                }
            },
            update: function(t, i, e) {
                i.animate != e.animate && this.setUpAnimations(t, i)
            },
            setUpAnimations: function(t, i) {
                if (i.animate) {
                    t.options.animation = "none", t.elem.removeClass("ui-pnotify-fade-slow ui-pnotify-fade-normal ui-pnotify-fade-fast"), t._animateIn || (t._animateIn = t.animateIn), t._animateOut || (t._animateOut = t.animateOut), t.animateIn = this.animateIn.bind(this), t.animateOut = this.animateOut.bind(this);
                    var e = 400;
                    "slow" === t.options.animate_speed ? e = 600 : "fast" === t.options.animate_speed ? e = 200 : t.options.animate_speed > 0 && (e = t.options.animate_speed), e /= 1e3, t.elem.addClass("animated").css({
                        "-webkit-animation-duration": e + "s",
                        "-moz-animation-duration": e + "s",
                        "animation-duration": e + "s"
                    })
                } else t._animateIn && t._animateOut && (t.animateIn = t._animateIn, delete t._animateIn, t.animateOut = t._animateOut, delete t._animateOut, t.elem.addClass("animated"))
            },
            animateIn: function(t) {
                this.notice.animating = "in";
                var i = this;
                t = function() {
                    this && this.call(), i.notice.animating = !1
                }.bind(t), this.notice.elem.show().one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", t).removeClass(this.options.out_class).addClass("ui-pnotify-in").addClass(this.options.in_class)
            },
            animateOut: function(t) {
                this.notice.animating = "out";
                var i = this;
                t = function() {
                    i.notice.elem.removeClass("ui-pnotify-in"), this && this.call(), i.notice.animating = !1
                }.bind(t), this.notice.elem.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", t).removeClass(this.options.in_class).addClass(this.options.out_class)
            }
        }
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.buttons", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        i.prototype.options.buttons = {
            closer: !0,
            closer_hover: !0,
            sticker: !0,
            sticker_hover: !0,
            show_on_nonblock: !1,
            labels: {
                close: "Close",
                stick: "Stick",
                unstick: "Unstick"
            },
            classes: {
                closer: null,
                pin_up: null,
                pin_down: null
            }
        }, i.prototype.modules.buttons = {
            closer: null,
            sticker: null,
            init: function(i, e) {
                var o = this;
                i.elem.on({
                    mouseenter: function(t) {
                        !o.options.sticker || i.options.nonblock && i.options.nonblock.nonblock && !o.options.show_on_nonblock || o.sticker.trigger("pnotify:buttons:toggleStick").css("visibility", "visible"), !o.options.closer || i.options.nonblock && i.options.nonblock.nonblock && !o.options.show_on_nonblock || o.closer.css("visibility", "visible")
                    },
                    mouseleave: function(t) {
                        o.options.sticker_hover && o.sticker.css("visibility", "hidden"), o.options.closer_hover && o.closer.css("visibility", "hidden")
                    }
                }), this.sticker = t("<div />", {
                    "class": "ui-pnotify-sticker",
                    "aria-role": "button",
                    "aria-pressed": i.options.hide ? "false" : "true",
                    tabindex: "0",
                    title: i.options.hide ? e.labels.stick : e.labels.unstick,
                    css: {
                        cursor: "pointer",
                        visibility: e.sticker_hover ? "hidden" : "visible"
                    },
                    click: function() {
                        i.options.hide = !i.options.hide, i.options.hide ? i.queueRemove() : i.cancelRemove(), t(this).trigger("pnotify:buttons:toggleStick")
                    }
                }).bind("pnotify:buttons:toggleStick", function() {
                    var e = null === o.options.classes.pin_up ? i.styles.pin_up : o.options.classes.pin_up,
                        n = null === o.options.classes.pin_down ? i.styles.pin_down : o.options.classes.pin_down;
                    t(this).attr("title", i.options.hide ? o.options.labels.stick : o.options.labels.unstick).children().attr("class", "").addClass(i.options.hide ? e : n).attr("aria-pressed", i.options.hide ? "false" : "true")
                }).append("<span />").trigger("pnotify:buttons:toggleStick").prependTo(i.container), (!e.sticker || i.options.nonblock && i.options.nonblock.nonblock && !e.show_on_nonblock) && this.sticker.css("display", "none"), this.closer = t("<div />", {
                    "class": "ui-pnotify-closer",
                    "aria-role": "button",
                    tabindex: "0",
                    title: e.labels.close,
                    css: {
                        cursor: "pointer",
                        visibility: e.closer_hover ? "hidden" : "visible"
                    },
                    click: function() {
                        i.remove(!1), o.sticker.css("visibility", "hidden"), o.closer.css("visibility", "hidden")
                    }
                }).append(t("<span />", {
                    "class": null === e.classes.closer ? i.styles.closer : e.classes.closer
                })).prependTo(i.container), (!e.closer || i.options.nonblock && i.options.nonblock.nonblock && !e.show_on_nonblock) && this.closer.css("display", "none")
            },
            update: function(t, i) {
                !i.closer || t.options.nonblock && t.options.nonblock.nonblock && !i.show_on_nonblock ? this.closer.css("display", "none") : i.closer && this.closer.css("display", "block"), !i.sticker || t.options.nonblock && t.options.nonblock.nonblock && !i.show_on_nonblock ? this.sticker.css("display", "none") : i.sticker && this.sticker.css("display", "block"), this.sticker.trigger("pnotify:buttons:toggleStick"), this.closer.find("span").attr("class", "").addClass(null === i.classes.closer ? t.styles.closer : i.classes.closer), i.sticker_hover ? this.sticker.css("visibility", "hidden") : t.options.nonblock && t.options.nonblock.nonblock && !i.show_on_nonblock || this.sticker.css("visibility", "visible"), i.closer_hover ? this.closer.css("visibility", "hidden") : t.options.nonblock && t.options.nonblock.nonblock && !i.show_on_nonblock || this.closer.css("visibility", "visible")
            }
        }, t.extend(i.styling.brighttheme, {
            closer: "brighttheme-icon-closer",
            pin_up: "brighttheme-icon-sticker",
            pin_down: "brighttheme-icon-sticker brighttheme-icon-stuck"
        }), t.extend(i.styling.jqueryui, {
            closer: "ui-icon ui-icon-close",
            pin_up: "ui-icon ui-icon-pin-w",
            pin_down: "ui-icon ui-icon-pin-s"
        }), t.extend(i.styling.bootstrap2, {
            closer: "icon-remove",
            pin_up: "icon-pause",
            pin_down: "icon-play"
        }), t.extend(i.styling.bootstrap3, {
            closer: "glyphicon glyphicon-remove",
            pin_up: "glyphicon glyphicon-pause",
            pin_down: "glyphicon glyphicon-play"
        }), t.extend(i.styling.fontawesome, {
            closer: "fa fa-times",
            pin_up: "fa fa-pause",
            pin_down: "fa fa-play"
        })
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.confirm", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        i.prototype.options.confirm = {
            confirm: !1,
            prompt: !1,
            prompt_class: "",
            prompt_default: "",
            prompt_multi_line: !1,
            align: "right",
            buttons: [{
                text: "Ok",
                addClass: "",
                promptTrigger: !0,
                click: function(t, i) {
                    t.remove(), t.get().trigger("pnotify.confirm", [t, i])
                }
            }, {
                text: "Cancel",
                addClass: "",
                click: function(t) {
                    t.remove(), t.get().trigger("pnotify.cancel", t)
                }
            }]
        }, i.prototype.modules.confirm = {
            container: null,
            prompt: null,
            init: function(i, e) {
                this.container = t('<div class="ui-pnotify-action-bar" style="margin-top:5px;clear:both;" />').css("text-align", e.align).appendTo(i.container), e.confirm || e.prompt ? this.makeDialog(i, e) : this.container.hide()
            },
            update: function(t, i) {
                i.confirm ? (this.makeDialog(t, i), this.container.show()) : this.container.hide().empty()
            },
            afterOpen: function(t, i) {
                i.prompt && this.prompt.focus()
            },
            makeDialog: function(e, o) {
                var n, s, a = !1,
                    c = this;
                this.container.empty(), o.prompt && (this.prompt = t("<" + (o.prompt_multi_line ? 'textarea rows="5"' : 'input type="text"') + ' style="margin-bottom:5px;clear:both;" />').addClass(("undefined" == typeof e.styles.input ? "" : e.styles.input) + " " + ("undefined" == typeof o.prompt_class ? "" : o.prompt_class)).val(o.prompt_default).appendTo(this.container));
                for (var l = o.buttons[0] && o.buttons[0] !== i.prototype.options.confirm.buttons[0], r = 0; r < o.buttons.length; r++) null === o.buttons[r] || l && i.prototype.options.confirm.buttons[r] && i.prototype.options.confirm.buttons[r] === o.buttons[r] || (n = o.buttons[r], a ? this.container.append(" ") : a = !0, s = t('<button type="button" class="ui-pnotify-action-button" />').addClass(("undefined" == typeof e.styles.btn ? "" : e.styles.btn) + " " + ("undefined" == typeof n.addClass ? "" : n.addClass)).text(n.text).appendTo(this.container).on("click", function(t) {
                    return function() {
                        "function" == typeof t.click && t.click(e, o.prompt ? c.prompt.val() : null)
                    }
                }(n)), o.prompt && !o.prompt_multi_line && n.promptTrigger && this.prompt.keypress(function(t) {
                    return function(i) {
                        13 == i.keyCode && t.click()
                    }
                }(s)), e.styles.text && s.wrapInner('<span class="' + e.styles.text + '"></span>'), e.styles.btnhover && s.hover(function(t) {
                    return function() {
                        t.addClass(e.styles.btnhover)
                    }
                }(s), function(t) {
                    return function() {
                        t.removeClass(e.styles.btnhover)
                    }
                }(s)), e.styles.btnactive && s.on("mousedown", function(t) {
                    return function() {
                        t.addClass(e.styles.btnactive)
                    }
                }(s)).on("mouseup", function(t) {
                    return function() {
                        t.removeClass(e.styles.btnactive)
                    }
                }(s)), e.styles.btnfocus && s.on("focus", function(t) {
                    return function() {
                        t.addClass(e.styles.btnfocus)
                    }
                }(s)).on("blur", function(t) {
                    return function() {
                        t.removeClass(e.styles.btnfocus)
                    }
                }(s)))
            }
        }, t.extend(i.styling.jqueryui, {
            btn: "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only",
            btnhover: "ui-state-hover",
            btnactive: "ui-state-active",
            btnfocus: "ui-state-focus",
            input: "",
            text: "ui-button-text"
        }), t.extend(i.styling.bootstrap2, {
            btn: "btn",
            input: ""
        }), t.extend(i.styling.bootstrap3, {
            btn: "btn btn-default",
            input: "form-control"
        }), t.extend(i.styling.fontawesome, {
            btn: "btn btn-default",
            input: "form-control"
        })
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.nonblock", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        var e, o = /^on/,
            n = /^(dbl)?click$|^mouse(move|down|up|over|out|enter|leave)$|^contextmenu$/,
            s = /^(focus|blur|select|change|reset)$|^key(press|down|up)$/,
            a = /^(scroll|resize|(un)?load|abort|error)$/,
            c = function(i, e) {
                var c;
                if (i = i.toLowerCase(), document.createEvent && this.dispatchEvent) {
                    if (i = i.replace(o, ""), i.match(n) ? (t(this).offset(), c = document.createEvent("MouseEvents"), c.initMouseEvent(i, e.bubbles, e.cancelable, e.view, e.detail, e.screenX, e.screenY, e.clientX, e.clientY, e.ctrlKey, e.altKey, e.shiftKey, e.metaKey, e.button, e.relatedTarget)) : i.match(s) ? (c = document.createEvent("UIEvents"), c.initUIEvent(i, e.bubbles, e.cancelable, e.view, e.detail)) : i.match(a) && (c = document.createEvent("HTMLEvents"), c.initEvent(i, e.bubbles, e.cancelable)), !c) return;
                    this.dispatchEvent(c)
                } else i.match(o) || (i = "on" + i), c = document.createEventObject(e), this.fireEvent(i, c)
            },
            l = function(i, o, n) {
                i.elem.addClass("ui-pnotify-nonblock-hide");
                var s = document.elementFromPoint(o.clientX, o.clientY);
                i.elem.removeClass("ui-pnotify-nonblock-hide");
                var a = t(s),
                    l = a.css("cursor");
                "auto" === l && "A" === s.tagName && (l = "pointer"), i.elem.css("cursor", "auto" !== l ? l : "default"), e && e.get(0) == s || (e && (c.call(e.get(0), "mouseleave", o.originalEvent), c.call(e.get(0), "mouseout", o.originalEvent)), c.call(s, "mouseenter", o.originalEvent), c.call(s, "mouseover", o.originalEvent)), c.call(s, n, o.originalEvent), e = a
            };
        i.prototype.options.nonblock = {
            nonblock: !1
        }, i.prototype.modules.nonblock = {
            init: function(t, i) {
                var o = this;
                t.elem.on({
                    mouseenter: function(i) {
                        o.options.nonblock && i.stopPropagation(), o.options.nonblock && t.elem.addClass("ui-pnotify-nonblock-fade")
                    },
                    mouseleave: function(i) {
                        o.options.nonblock && i.stopPropagation(), e = null, t.elem.css("cursor", "auto"), o.options.nonblock && "out" !== t.animating && t.elem.removeClass("ui-pnotify-nonblock-fade")
                    },
                    mouseover: function(t) {
                        o.options.nonblock && t.stopPropagation()
                    },
                    mouseout: function(t) {
                        o.options.nonblock && t.stopPropagation()
                    },
                    mousemove: function(i) {
                        o.options.nonblock && (i.stopPropagation(), l(t, i, "onmousemove"))
                    },
                    mousedown: function(i) {
                        o.options.nonblock && (i.stopPropagation(), i.preventDefault(), l(t, i, "onmousedown"))
                    },
                    mouseup: function(i) {
                        o.options.nonblock && (i.stopPropagation(), i.preventDefault(), l(t, i, "onmouseup"))
                    },
                    click: function(i) {
                        o.options.nonblock && (i.stopPropagation(), l(t, i, "onclick"))
                    },
                    dblclick: function(i) {
                        o.options.nonblock && (i.stopPropagation(), l(t, i, "ondblclick"))
                    }
                })
            }
        }
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.mobile", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        i.prototype.options.mobile = {
            swipe_dismiss: !0,
            styling: !0
        }, i.prototype.modules.mobile = {
            swipe_dismiss: !0,
            init: function(t, i) {
                var e = this,
                    o = null,
                    n = null,
                    s = null;
                this.swipe_dismiss = i.swipe_dismiss, this.doMobileStyling(t, i), t.elem.on({
                    touchstart: function(i) {
                        e.swipe_dismiss && (o = i.originalEvent.touches[0].screenX, s = t.elem.width(), t.container.css("left", "0"))
                    },
                    touchmove: function(i) {
                        if (o && e.swipe_dismiss) {
                            var a = i.originalEvent.touches[0].screenX;
                            n = a - o;
                            var c = (1 - Math.abs(n) / s) * t.options.opacity;
                            t.elem.css("opacity", c), t.container.css("left", n)
                        }
                    },
                    touchend: function() {
                        if (o && e.swipe_dismiss) {
                            if (Math.abs(n) > 40) {
                                var i = 0 > n ? -2 * s : 2 * s;
                                t.elem.animate({
                                    opacity: 0
                                }, 100), t.container.animate({
                                    left: i
                                }, 100), t.remove()
                            } else t.elem.animate({
                                opacity: t.options.opacity
                            }, 100), t.container.animate({
                                left: 0
                            }, 100);
                            o = null, n = null, s = null
                        }
                    },
                    touchcancel: function() {
                        o && e.swipe_dismiss && (t.elem.animate({
                            opacity: t.options.opacity
                        }, 100), t.container.animate({
                            left: 0
                        }, 100), o = null, n = null, s = null)
                    }
                })
            },
            update: function(t, i) {
                this.swipe_dismiss = i.swipe_dismiss, this.doMobileStyling(t, i)
            },
            doMobileStyling: function(i, e) {
                e.styling ? (i.elem.addClass("ui-pnotify-mobile-able"), t(window).width() <= 480 ? (i.options.stack.mobileOrigSpacing1 || (i.options.stack.mobileOrigSpacing1 = i.options.stack.spacing1, i.options.stack.mobileOrigSpacing2 = i.options.stack.spacing2), i.options.stack.spacing1 = 0, i.options.stack.spacing2 = 0) : (i.options.stack.mobileOrigSpacing1 || i.options.stack.mobileOrigSpacing2) && (i.options.stack.spacing1 = i.options.stack.mobileOrigSpacing1, delete i.options.stack.mobileOrigSpacing1, i.options.stack.spacing2 = i.options.stack.mobileOrigSpacing2, delete i.options.stack.mobileOrigSpacing2)) : (i.elem.removeClass("ui-pnotify-mobile-able"), i.options.stack.mobileOrigSpacing1 && (i.options.stack.spacing1 = i.options.stack.mobileOrigSpacing1, delete i.options.stack.mobileOrigSpacing1), i.options.stack.mobileOrigSpacing2 && (i.options.stack.spacing2 = i.options.stack.mobileOrigSpacing2, delete i.options.stack.mobileOrigSpacing2))
            }
        }
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.desktop", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        var e, o = function(t, i) {
            return (o = "Notification" in window ? function(t, i) {
                return new Notification(t, i)
            } : "mozNotification" in navigator ? function(t, i) {
                return navigator.mozNotification.createNotification(t, i.body, i.icon).show()
            } : "webkitNotifications" in window ? function(t, i) {
                return window.webkitNotifications.createNotification(i.icon, t, i.body)
            } : function(t, i) {
                return null
            })(t, i)
        };
        i.prototype.options.desktop = {
            desktop: !1,
            fallback: !0,
            icon: null,
            tag: null
        }, i.prototype.modules.desktop = {
            tag: null,
            icon: null,
            genNotice: function(t, i) {
                null === i.icon ? this.icon = "http://sciactive.com/pnotify/includes/desktop/" + t.options.type + ".png" : i.icon === !1 ? this.icon = null : this.icon = i.icon, (null === this.tag || null !== i.tag) && (this.tag = null === i.tag ? "PNotify-" + Math.round(1e6 * Math.random()) : i.tag), t.desktop = o(t.options.title, {
                    icon: this.icon,
                    body: i.text || t.options.text,
                    tag: this.tag
                }), !("close" in t.desktop) && "cancel" in t.desktop && (t.desktop.close = function() {
                    t.desktop.cancel()
                }), t.desktop.onclick = function() {
                    t.elem.trigger("click")
                }, t.desktop.onclose = function() {
                    "closing" !== t.state && "closed" !== t.state && t.remove()
                }
            },
            init: function(t, o) {
                return o.desktop ? (e = i.desktop.checkPermission(), 0 !== e ? void(o.fallback || (t.options.auto_display = !1)) : void this.genNotice(t, o)) : void 0
            },
            update: function(t, i, o) {
                0 !== e && i.fallback || !i.desktop || this.genNotice(t, i)
            },
            beforeOpen: function(t, i) {
                0 !== e && i.fallback || !i.desktop || t.elem.css({
                    left: "-10000px"
                }).removeClass("ui-pnotify-in")
            },
            afterOpen: function(t, i) {
                0 !== e && i.fallback || !i.desktop || (t.elem.css({
                    left: "-10000px"
                }).removeClass("ui-pnotify-in"), "show" in t.desktop && t.desktop.show())
            },
            beforeClose: function(t, i) {
                0 !== e && i.fallback || !i.desktop || t.elem.css({
                    left: "-10000px"
                }).removeClass("ui-pnotify-in")
            },
            afterClose: function(t, i) {
                0 !== e && i.fallback || !i.desktop || (t.elem.css({
                    left: "-10000px"
                }).removeClass("ui-pnotify-in"), "close" in t.desktop && t.desktop.close())
            }
        }, i.desktop = {
            permission: function() {
                "undefined" != typeof Notification && "requestPermission" in Notification ? Notification.requestPermission() : "webkitNotifications" in window && window.webkitNotifications.requestPermission()
            },
            checkPermission: function() {
                return "undefined" != typeof Notification && "permission" in Notification ? "granted" === Notification.permission ? 0 : 1 : "webkitNotifications" in window && 0 == window.webkitNotifications.checkPermission() ? 0 : 1;
            }
        }, e = i.desktop.checkPermission()
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.history", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        var e, o;
        t(function() {
            t("body").on("pnotify.history-all", function() {
                t.each(i.notices, function() {
                    this.modules.history.inHistory && (this.elem.is(":visible") ? this.options.hide && this.queueRemove() : this.open && this.open())
                })
            }).on("pnotify.history-last", function() {
                var t, e = "top" === i.prototype.options.stack.push,
                    o = e ? 0 : -1;
                do {
                    if (t = -1 === o ? i.notices.slice(o) : i.notices.slice(o, o + 1), !t[0]) return !1;
                    o = e ? o + 1 : o - 1
                } while (!t[0].modules.history.inHistory || t[0].elem.is(":visible"));
                t[0].open && t[0].open()
            })
        }), i.prototype.options.history = {
            history: !0,
            menu: !1,
            fixed: !0,
            maxonscreen: 1 / 0,
            labels: {
                redisplay: "Redisplay",
                all: "All",
                last: "Last"
            }
        }, i.prototype.modules.history = {
            inHistory: !1,
            init: function(i, n) {
                if (i.options.destroy = !1, this.inHistory = n.history, n.menu && "undefined" == typeof e) {
                    e = t("<div />", {
                        "class": "ui-pnotify-history-container " + i.styles.hi_menu,
                        mouseleave: function() {
                            e.animate({
                                top: "-" + o + "px"
                            }, {
                                duration: 100,
                                queue: !1
                            })
                        }
                    }).append(t("<div />", {
                        "class": "ui-pnotify-history-header",
                        text: n.labels.redisplay
                    })).append(t("<button />", {
                        "class": "ui-pnotify-history-all " + i.styles.hi_btn,
                        text: n.labels.all,
                        mouseenter: function() {
                            t(this).addClass(i.styles.hi_btnhov)
                        },
                        mouseleave: function() {
                            t(this).removeClass(i.styles.hi_btnhov)
                        },
                        click: function() {
                            return t(this).trigger("pnotify.history-all"), !1
                        }
                    })).append(t("<button />", {
                        "class": "ui-pnotify-history-last " + i.styles.hi_btn,
                        text: n.labels.last,
                        mouseenter: function() {
                            t(this).addClass(i.styles.hi_btnhov)
                        },
                        mouseleave: function() {
                            t(this).removeClass(i.styles.hi_btnhov)
                        },
                        click: function() {
                            return t(this).trigger("pnotify.history-last"), !1
                        }
                    })).appendTo("body");
                    var s = t("<span />", {
                        "class": "ui-pnotify-history-pulldown " + i.styles.hi_hnd,
                        mouseenter: function() {
                            e.animate({
                                top: "0"
                            }, {
                                duration: 100,
                                queue: !1
                            })
                        }
                    }).appendTo(e);
                    o = s.offset().top + 2, e.css({
                        top: "-" + o + "px"
                    }), n.fixed && e.addClass("ui-pnotify-history-fixed")
                }
            },
            update: function(t, i) {
                this.inHistory = i.history, i.fixed && e ? e.addClass("ui-pnotify-history-fixed") : e && e.removeClass("ui-pnotify-history-fixed")
            },
            beforeOpen: function(e, o) {
                if (i.notices && i.notices.length > o.maxonscreen) {
                    var n;
                    n = "top" !== e.options.stack.push ? i.notices.slice(0, i.notices.length - o.maxonscreen) : i.notices.slice(o.maxonscreen, i.notices.length), t.each(n, function() {
                        this.remove && this.remove()
                    })
                }
            }
        }, t.extend(i.styling.jqueryui, {
            hi_menu: "ui-state-default ui-corner-bottom",
            hi_btn: "ui-state-default ui-corner-all",
            hi_btnhov: "ui-state-hover",
            hi_hnd: "ui-icon ui-icon-grip-dotted-horizontal"
        }), t.extend(i.styling.bootstrap2, {
            hi_menu: "well",
            hi_btn: "btn",
            hi_btnhov: "",
            hi_hnd: "icon-chevron-down"
        }), t.extend(i.styling.bootstrap3, {
            hi_menu: "well",
            hi_btn: "btn btn-default",
            hi_btnhov: "",
            hi_hnd: "glyphicon glyphicon-chevron-down"
        }), t.extend(i.styling.fontawesome, {
            hi_menu: "well",
            hi_btn: "btn btn-default",
            hi_btnhov: "",
            hi_hnd: "fa fa-chevron-down"
        })
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.callbacks", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        var e = i.prototype.init,
            o = i.prototype.open,
            n = i.prototype.remove;
        i.prototype.init = function() {
            this.options.before_init && this.options.before_init(this.options), e.apply(this, arguments), this.options.after_init && this.options.after_init(this)
        }, i.prototype.open = function() {
            var t;
            this.options.before_open && (t = this.options.before_open(this)), t !== !1 && (o.apply(this, arguments), this.options.after_open && this.options.after_open(this))
        }, i.prototype.remove = function(t) {
            var i;
            this.options.before_close && (i = this.options.before_close(this, t)), i !== !1 && (n.apply(this, arguments), this.options.after_close && this.options.after_close(this, t))
        }
    }),
    function(t, i) {
        "function" == typeof define && define.amd ? define("pnotify.reference", ["jquery", "pnotify"], i) : "object" == typeof exports && "undefined" != typeof module ? module.exports = i(require("jquery"), require("./pnotify")) : i(t.jQuery, t.PNotify)
    }(this, function(t, i) {
        i.prototype.options.reference = {
            put_thing: !1,
            labels: {
                text: "Spin Around"
            }
        }, i.prototype.modules.reference = {
            thingElem: null,
            init: function(i, e) {
                var o = this;
                this.notice, this.options, e.put_thing && (this.thingElem = t('<button style="float:right;" class="btn btn-default" type="button" disabled><i class="' + i.styles.athing + '" />&nbsp;' + e.labels.text + "</button>").appendTo(i.container), i.container.append('<div style="clear: right; line-height: 0;" />'), i.elem.on({
                    mouseenter: function(t) {
                        o.thingElem.prop("disabled", !1)
                    },
                    mouseleave: function(t) {
                        o.thingElem.prop("disabled", !0)
                    }
                }), this.thingElem.on("click", function() {
                    var t = 0,
                        e = setInterval(function() {
                            t += 10, 360 == t && (t = 0, clearInterval(e)), i.elem.css({
                                "-moz-transform": "rotate(" + t + "deg)",
                                "-webkit-transform": "rotate(" + t + "deg)",
                                "-o-transform": "rotate(" + t + "deg)",
                                "-ms-transform": "rotate(" + t + "deg)",
                                filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + t / 360 * 4 + ")"
                            })
                        }, 20)
                }))
            },
            update: function(t, i, e) {
                this.notice, this.options, i.put_thing && this.thingElem ? this.thingElem.show() : !i.put_thing && this.thingElem && this.thingElem.hide(), this.thingElem && this.thingElem.find("i").attr("class", t.styles.athing)
            },
            beforeOpen: function(t, i) {},
            afterOpen: function(t, i) {},
            beforeClose: function(t, i) {},
            afterClose: function(t, i) {},
            beforeDestroy: function(t, i) {},
            afterDestroy: function(t, i) {}
        }, t.extend(i.styling.jqueryui, {
            athing: "ui-icon ui-icon-refresh"
        }), t.extend(i.styling.bootstrap2, {
            athing: "icon-refresh"
        }), t.extend(i.styling.bootstrap3, {
            athing: "glyphicon glyphicon-refresh"
        }), t.extend(i.styling.fontawesome, {
            athing: "fa fa-refresh"
        })
    });