jQuery(document).ready(function (a) {
    var b = lottie.loadAnimation({
        container: document.getElementById("icon1"),
        renderer: "svg",
        loop: !0,
        autoplay: !1,
        path: "/wp-content/themes/tehnokrat/js/icon_1.json"
    });
    a(".hov1").on("mouseover", function () {
        b.play(), a("#icon1 svg path").css({fill: "#8ebb2b", stroke: "#8ebb2b"})
    }), a(".hov1").on("mouseout", function () {
        setTimeout(function () {
            b.stop()
        }, 1700), a("#icon1 svg path").css({stroke: "rgb(145,135,128)", fill: "rgb(145,135,128)"})
    });
    var c = lottie.loadAnimation({
        container: document.getElementById("icon2"),
        renderer: "svg",
        loop: !0,
        autoplay: !1,
        path: "/wp-content/themes/tehnokrat/js/icon_2.json"
    });
    a(".hov2").on("mouseover", function () {
        c.play(), a("#icon2 svg path").css({stroke: "#8ebb2b", fill: "#8ebb2b"})
    }), a(".hov2").on("mouseout", function () {
        setTimeout(function () {
            c.stop()
        }, 1700), a("#icon2 svg path").css({stroke: "rgb(145,135,128)", fill: "rgb(145,135,128)"})
    });
    var d = lottie.loadAnimation({
        container: document.getElementById("icon3"),
        renderer: "svg",
        loop: !0,
        autoplay: !1,
        path: "/wp-content/themes/tehnokrat/js/icon_3.json"
    });
    a(".hov3").on("mouseover", function () {
        d.play(), a("#icon3 svg path").css({stroke: "#8ebb2b", fill: "#8ebb2b"})
    }), a(".hov3").on("mouseout", function () {
        setTimeout(function () {
            d.stop()
        }, 1300), a("#icon3 svg path").css({stroke: "rgb(145,135,128)", fill: "rgb(145,135,128)"})
    });
    var e = lottie.loadAnimation({
        container: document.getElementById("icon4"),
        renderer: "svg",
        loop: !0,
        autoplay: !1,
        path: "/wp-content/themes/tehnokrat/js/icon_4.json"
    });
    a(".hov4").on("mouseover", function () {
        e.play(), a("#icon4 svg path").css({stroke: "#8ebb2b", fill: "#8ebb2b"})
    }), a(".hov4").on("mouseout", function () {
        setTimeout(function () {
            e.stop()
        }, 1200), a("#icon4 svg path").css({stroke: "rgb(145,135,128)", fill: "rgb(145,135,128)"})
    });
    var f = lottie.loadAnimation({
        container: document.getElementById("icon5"),
        renderer: "svg",
        loop: !0,
        autoplay: !1,
        path: "/wp-content/themes/tehnokrat/js/icon_5.json"
    });
    a(".hov5").on("mouseover", function () {
        f.play(), a("#icon5 svg path").css({stroke: "#8ebb2b", fill: "#8ebb2b"})
    }), a(".hov5").on("mouseout", function () {
        setTimeout(function () {
            f.stop()
        }, 1400), a("#icon5 svg path").css({stroke: "rgb(145,135,128)", fill: "rgb(145,135,128)"})
    });
    var g = lottie.loadAnimation({
        container: document.getElementById("icon6"),
        renderer: "svg",
        loop: !0,
        autoplay: !1,
        path: "/wp-content/themes/tehnokrat/js/icon_6.json"
    });
    a(".hov6").on("mouseover", function () {
        g.play(), a("#icon6 svg path").css({stroke: "#8ebb2b", fill: "#8ebb2b"})
    }), a(".hov6").on("mouseout", function () {
        setTimeout(function () {
            g.stop()
        }, 1300), a("#icon6 svg path").css({stroke: "rgb(145,135,128)", fill: "rgb(145,135,128)"})
    }),  a(".icon-item .for-icon").matchHeight({byRow: !1})
});