!function (e, t) {
    "object" == typeof exports && "object" == typeof module ? module.exports = t(require("moment"), require("fullcalendar")) : "function" == typeof define && define.amd ? define(["moment", "fullcalendar"], t) : "object" == typeof exports ? t(require("moment"), require("fullcalendar")) : t(e.moment, e.FullCalendar)
}("undefined" != typeof self ? self : this, function (e, t) {
    return function (e) {
        function t(r) {
            if (a[r]) return a[r].exports;
            var n = a[r] = {i: r, l: !1, exports: {}};
            return e[r].call(n.exports, n, n.exports, t), n.l = !0, n.exports
        }

        var a = {};
        return t.m = e, t.c = a, t.d = function (e, a, r) {
            t.o(e, a) || Object.defineProperty(e, a, {configurable: !1, enumerable: !0, get: r})
        }, t.n = function (e) {
            var a = e && e.__esModule ? function () {
                return e.default
            } : function () {
                return e
            };
            return t.d(a, "a", a), a
        }, t.o = function (e, t) {
            return Object.prototype.hasOwnProperty.call(e, t)
        }, t.p = "", t(t.s = 107)
    }({
        0: function (t, a) {
            t.exports = e
        }, 1: function (e, a) {
            e.exports = t
        }, 107: function (e, t, a) {
            Object.defineProperty(t, "__azModule", {value: !0}), a(108);
            var r = a(1);
            r.datepickerLocale("az", "az-AZ", {
                closeText: "Bitdi",
                prevText: "Əvvəl",
                nextText: "Sonra",
                currentText: "Bu gün",
                monthNames: ['Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'İyun', 'İyul', 'Avqust', 'Sentyabr', 'Oktyabr', 'Noyabr', 'Dekabr'],
                monthNamesShort: ['Yav', 'Fev', 'Mar', 'Apr', 'May', 'İyn', 'İyl', 'Avq', 'Sen', 'Okt', 'Noy', 'Dek'],
                dayNames: ['Bazar', 'Bazar ertəsi', 'Çərşənbə axşamı', 'Çərşənbə', 'Cümə axşamı', 'Cümə', 'Şənbə'],
                dayNamesShort: ['B.', 'B.e.', 'Ç.a.', 'Ç.', 'C.a.', 'C.', 'Ş.'],
                dayNamesMin: ['B.', 'B.e.', 'Ç.a.', 'Ç.', 'C.a.', 'C.', 'Ş.'],
                weekHeader: "Wk",
                dateFormat: "dd/mm/yy",
                firstDay: 1,
                isRTL: !1,
                showMonthAfterYear: !1,
                yearSuffix: ""
            }), r.locale("az")
        }, 108: function (e, t, a) {
            !function (e, t) {
                t(a(0))
            }(0, function (e) {
                return e.defineLocale("az-Az", {
                    months: ['Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'İyun', 'İyul', 'Avqust', 'Sentyabr', 'Oktyabr', 'Noyabr', 'Dekabr'],
                    monthsShort: ['Yav', 'Fev', 'Mar', 'Apr', 'May', 'İyn', 'İyl', 'Avq', 'Sen', 'Okt', 'Noy', 'Dek'],
                    weekdays: ['Bazar', 'Bazar ertəsi', 'Çərşənbə axşamı', 'Çərşənbə', 'Cümə axşamı', 'Cümə', 'Şənbə'],
                    weekdaysShort: ['B.', 'B.e.', 'Ç.a.', 'Ç.', 'C.a.', 'C.', 'Ş.'],
                    weekdaysMin: ['B.', 'B.e.', 'Ç.a.', 'Ç.', 'C.a.', 'C.', 'Ş.'],
                    longDateFormat: {
                        LT: "h:mm A",
                        LTS: "h:mm:ss A",
                        L: "DD/MM/YYYY",
                        LL: "D MMMM YYYY",
                        LLL: "D MMMM YYYY h:mm A",
                        LLLL: "dddd, D MMMM YYYY h:mm A"
                    },
                    calendar: {
                        sameDay: "[Bu gün] LT",
                        nextDay: "[Sabah] LT",
                        nextWeek: "dddd [-da] LT",
                        lastDay: "[Dünən] LT",
                        lastWeek: "[Son] dddd [-da] LT",
                        sameElse: "L"
                    },
                    relativeTime: {
                        future: "%s -da",
                        past: "%s evvəl",
                        s: "bir necə saniyə",
                        ss: "%d saniyə",
                        m: "dəyqə",
                        mm: "%d dəyqə",
                        h: "saat",
                        hh: "%d saat",
                        d: "gün",
                        dd: "%d gün",
                        M: "ay",
                        MM: "%d ay",
                        y: "il",
                        yy: "%d il"
                    },
                    dayOfMonthOrdinalParse: /\d{1,2}(st|nd|rd|th)/,
                    ordinal: function (e) {
                        var t = e % 10;
                        return e + (1 == ~~(e % 100 / 10) ? "th" : 1 === t ? "st" : 2 === t ? "nd" : 3 === t ? "rd" : "th")
                    },
                    week: {dow: 1, doy: 4}
                })
            })
        }
    })
});