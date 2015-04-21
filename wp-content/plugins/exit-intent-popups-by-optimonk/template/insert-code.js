(function(d, a) {
    var h = d.getElementsByTagName("head")[0], p = d.location.protocol, s;
    s = d.createElement("script");
    s.type = "text/javascript";
    s.charset = "utf-8";
    s.async = true;
    s.defer = true;
    s.src = p + "//front.optimonk.com/public/" + a + "/js/preload.js";
    h.appendChild(s);
})(document, '{{accountId}}');
