api = {};
fingerprint = {};

//Registering name and tests of attributes
api.register = function(name,code){
    fingerprint[name] = code();
};


(function() {
    api.register("buildID", function () {
        return window.navigator.buildID;
    });
})();

(function() {
    api.register("cookies", function () {
        return window.navigator.cookieEnabled ? "yes" : "no";
    });
})();

(function() {
    var doNotTrack = "";
    if (window.navigator.doNotTrack != null && window.navigator.doNotTrack != "unspecified") {
        if (window.navigator.doNotTrack == "1" || window.navigator.doNotTrack == "yes") {
            doNotTrack = "yes";
        } else {
            doNotTrack = "no";
        }
    } else {
        doNotTrack = "NC";
    }
    api.register("dnt", function () {
        return doNotTrack;
    });
})();

(function() {
    api.register("platform", function () {
        return window.navigator.platform;
    });
})();

(function() {
    api.register("plugins", function () {
        var np = window.navigator.plugins;
        var plist = [];
        for (var i = 0; i < np.length; i++) {
            plist[i] = np[i].name + "; ";
            plist[i] += np[i].description + "; ";
            plist[i] += np[i].filename;
            plist[i] += ". ";
        }
        var plugins = "";
        for (i = 0; i < np.length; i++)
            plugins += "Plugin " + i + ": " + plist[i];
        return plugins;
    });
})();

(function() {
    api.register("screen", function () {
        return {
            "width": window.screen.width,
            "height": window.screen.height,
            "depth": window.screen.colorDepth,
            "availTop": window.screen.availTop,
            "availLeft": window.screen.availLeft,
            "availHeight": window.screen.availHeight,
            "availWidth": window.screen.availWidth,
            "left": window.screen.left,
            "top": window.screen.top
        };
    });
})();

(function() {
    try {
        localStorage.fp = "test";
        sessionStorage.fp = "test";
    } catch (ex) {
    }

    try {
        domLocalStorage = "";
        if (localStorage.fp == "test") {
            domLocalStorage = "yes";
        } else {
            domLocalStorage = "no";
        }
    } catch (ex) {
        domLocalStorage = "no";
    }

    try {
        domSessionStorage = "";
        if (sessionStorage.fp == "test") {
            domSessionStorage = "yes";
        } else {
            domSessionStorage = "no";
        }
    } catch (ex) {
        domSessionStorage = "no";
    }
    api.register("storage", function() {
        return {"local":domLocalStorage, "session":domSessionStorage};
    });
})();

(function() {
    api.register("timezone", function () {
        return new Date().getTimezoneOffset();
    });
})();

/*
CryptoJS v3.1.2
code.google.com/p/crypto-js
(c) 2009-2013 by Jeff Mott. All rights reserved.
code.google.com/p/crypto-js/wiki/License
*/
var CryptoJS=CryptoJS||function(e,m){var p={},j=p.lib={},l=function(){},f=j.Base={extend:function(a){l.prototype=this;var c=new l;a&&c.mixIn(a);c.hasOwnProperty("init")||(c.init=function(){c.$super.init.apply(this,arguments)});c.init.prototype=c;c.$super=this;return c},create:function(){var a=this.extend();a.init.apply(a,arguments);return a},init:function(){},mixIn:function(a){for(var c in a)a.hasOwnProperty(c)&&(this[c]=a[c]);a.hasOwnProperty("toString")&&(this.toString=a.toString)},clone:function(){return this.init.prototype.extend(this)}},
n=j.WordArray=f.extend({init:function(a,c){a=this.words=a||[];this.sigBytes=c!=m?c:4*a.length},toString:function(a){return(a||h).stringify(this)},concat:function(a){var c=this.words,q=a.words,d=this.sigBytes;a=a.sigBytes;this.clamp();if(d%4)for(var b=0;b<a;b++)c[d+b>>>2]|=(q[b>>>2]>>>24-8*(b%4)&255)<<24-8*((d+b)%4);else if(65535<q.length)for(b=0;b<a;b+=4)c[d+b>>>2]=q[b>>>2];else c.push.apply(c,q);this.sigBytes+=a;return this},clamp:function(){var a=this.words,c=this.sigBytes;a[c>>>2]&=4294967295<<
32-8*(c%4);a.length=e.ceil(c/4)},clone:function(){var a=f.clone.call(this);a.words=this.words.slice(0);return a},random:function(a){for(var c=[],b=0;b<a;b+=4)c.push(4294967296*e.random()|0);return new n.init(c,a)}}),b=p.enc={},h=b.Hex={stringify:function(a){var c=a.words;a=a.sigBytes;for(var b=[],d=0;d<a;d++){var f=c[d>>>2]>>>24-8*(d%4)&255;b.push((f>>>4).toString(16));b.push((f&15).toString(16))}return b.join("")},parse:function(a){for(var c=a.length,b=[],d=0;d<c;d+=2)b[d>>>3]|=parseInt(a.substr(d,
2),16)<<24-4*(d%8);return new n.init(b,c/2)}},g=b.Latin1={stringify:function(a){var c=a.words;a=a.sigBytes;for(var b=[],d=0;d<a;d++)b.push(String.fromCharCode(c[d>>>2]>>>24-8*(d%4)&255));return b.join("")},parse:function(a){for(var c=a.length,b=[],d=0;d<c;d++)b[d>>>2]|=(a.charCodeAt(d)&255)<<24-8*(d%4);return new n.init(b,c)}},r=b.Utf8={stringify:function(a){try{return decodeURIComponent(escape(g.stringify(a)))}catch(c){throw Error("Malformed UTF-8 data");}},parse:function(a){return g.parse(unescape(encodeURIComponent(a)))}},
k=j.BufferedBlockAlgorithm=f.extend({reset:function(){this._data=new n.init;this._nDataBytes=0},_append:function(a){"string"==typeof a&&(a=r.parse(a));this._data.concat(a);this._nDataBytes+=a.sigBytes},_process:function(a){var c=this._data,b=c.words,d=c.sigBytes,f=this.blockSize,h=d/(4*f),h=a?e.ceil(h):e.max((h|0)-this._minBufferSize,0);a=h*f;d=e.min(4*a,d);if(a){for(var g=0;g<a;g+=f)this._doProcessBlock(b,g);g=b.splice(0,a);c.sigBytes-=d}return new n.init(g,d)},clone:function(){var a=f.clone.call(this);
a._data=this._data.clone();return a},_minBufferSize:0});j.Hasher=k.extend({cfg:f.extend(),init:function(a){this.cfg=this.cfg.extend(a);this.reset()},reset:function(){k.reset.call(this);this._doReset()},update:function(a){this._append(a);this._process();return this},finalize:function(a){a&&this._append(a);return this._doFinalize()},blockSize:16,_createHelper:function(a){return function(c,b){return(new a.init(b)).finalize(c)}},_createHmacHelper:function(a){return function(b,f){return(new s.HMAC.init(a,
f)).finalize(b)}}});var s=p.algo={};return p}(Math);
(function(){var e=CryptoJS,m=e.lib,p=m.WordArray,j=m.Hasher,l=[],m=e.algo.SHA1=j.extend({_doReset:function(){this._hash=new p.init([1732584193,4023233417,2562383102,271733878,3285377520])},_doProcessBlock:function(f,n){for(var b=this._hash.words,h=b[0],g=b[1],e=b[2],k=b[3],j=b[4],a=0;80>a;a++){if(16>a)l[a]=f[n+a]|0;else{var c=l[a-3]^l[a-8]^l[a-14]^l[a-16];l[a]=c<<1|c>>>31}c=(h<<5|h>>>27)+j+l[a];c=20>a?c+((g&e|~g&k)+1518500249):40>a?c+((g^e^k)+1859775393):60>a?c+((g&e|g&k|e&k)-1894007588):c+((g^e^
k)-899497514);j=k;k=e;e=g<<30|g>>>2;g=h;h=c}b[0]=b[0]+h|0;b[1]=b[1]+g|0;b[2]=b[2]+e|0;b[3]=b[3]+k|0;b[4]=b[4]+j|0},_doFinalize:function(){var f=this._data,e=f.words,b=8*this._nDataBytes,h=8*f.sigBytes;e[h>>>5]|=128<<24-h%32;e[(h+64>>>9<<4)+14]=Math.floor(b/4294967296);e[(h+64>>>9<<4)+15]=b;f.sigBytes=4*e.length;this._process();return this._hash},clone:function(){var e=j.clone.call(this);e._hash=this._hash.clone();return e}});e.SHA1=j._createHelper(m);e.HmacSHA1=j._createHmacHelper(m)})();


(function() {
    var audioData = {};

// Performs fingerprint as found in https://client.a.pxi.pub/PXmssU3ZQ0/main.min.js
//Sum of buffer values
    var p1 = new Promise(function (resolve, reject) {
        try {
            if (context = new (window.OfflineAudioContext || window.webkitOfflineAudioContext)(1, 44100, 44100), !context) {
                audioData.pxi_output = 0;
            }

            // Create oscillator
            pxi_oscillator = context.createOscillator();
            pxi_oscillator.type = "triangle";
            pxi_oscillator.frequency.value = 1e4;

            // Create and configure compressor
            pxi_compressor = context.createDynamicsCompressor();
            pxi_compressor.threshold && (pxi_compressor.threshold.value = -50);
            pxi_compressor.knee && (pxi_compressor.knee.value = 40);
            pxi_compressor.ratio && (pxi_compressor.ratio.value = 12);
            pxi_compressor.reduction && (pxi_compressor.reduction.value = -20);
            pxi_compressor.attack && (pxi_compressor.attack.value = 0);
            pxi_compressor.release && (pxi_compressor.release.value = .25);

            // Connect nodes
            pxi_oscillator.connect(pxi_compressor);
            pxi_compressor.connect(context.destination);

            // Start audio processing
            pxi_oscillator.start(0);
            context.startRendering();
            context.oncomplete = function (evnt) {
                try {
                    audioData.pxi_output = 0;
                    var sha1 = CryptoJS.algo.SHA1.create();
                    for (var i = 0; i < evnt.renderedBuffer.length; i++) {
                        sha1.update(evnt.renderedBuffer.getChannelData(0)[i].toString());
                    }
                    hash = sha1.finalize();
                    audioData.pxi_full_buffer_hash = hash.toString(CryptoJS.enc.Hex);
                    for (var i = 4500; 5e3 > i; i++) {
                        audioData.pxi_output += Math.abs(evnt.renderedBuffer.getChannelData(0)[i]);
                    }
                    pxi_compressor.disconnect();
                    resolve();
                } catch(u){
                    audioData.pxi_output = 0;
                    resolve();
                }
            }
        } catch (u) {
            audioData.pxi_output = 0;
            resolve();
        }
    });

// End PXI fingerprint

// Performs fingerprint as found in some versions of http://metrics.nt.vc/metrics.js
    function a(a, b, c) {
        for (var d in b) "dopplerFactor" === d || "speedOfSound" === d || "currentTime" ===
        d || "number" !== typeof b[d] && "string" !== typeof b[d] || (a[(c ? c : "") + d] = b[d]);
        return a
    }

    var p2 = new Promise(function (resolve, reject) {
        try {
            var nt_vc_context = window.AudioContext || window.webkitAudioContext;
            if ("function" !== typeof nt_vc_context) audioData.nt_vc_output = "Not available";
            else {
                var f = new nt_vc_context,
                    d = f.createAnalyser();
                audioData.nt_vc_output = a({}, f, "ac-");
                audioData.nt_vc_output = a(audioData.nt_vc_output, f.destination, "ac-");
                audioData.nt_vc_output = a(audioData.nt_vc_output, f.listener, "ac-");
                audioData.nt_vc_output = a(audioData.nt_vc_output, d, "an-");
            }
        } catch (g) {
            audioData.nt_vc_output = 0
        }
        resolve();
    });

// Performs fingerprint as found in https://www.cdn-net.com/cc.js
    var cc_output = [];

    var p3 = new Promise(function (resolve, reject) {
        var audioCtx = new (window.AudioContext || window.webkitAudioContext),
            oscillator = audioCtx.createOscillator(),
            analyser = audioCtx.createAnalyser(),
            gain = audioCtx.createGain(),
            scriptProcessor = audioCtx.createScriptProcessor(4096, 1, 1);


        gain.gain.value = 0; // Disable volume
        oscillator.type = "triangle"; // Set oscillator to output triangle wave
        oscillator.connect(analyser); // Connect oscillator output to analyser input
        analyser.connect(scriptProcessor); // Connect analyser output to scriptProcessor input
        scriptProcessor.connect(gain); // Connect scriptProcessor output to gain input
        gain.connect(audioCtx.destination); // Connect gain output to audiocontext destination

        scriptProcessor.onaudioprocess = function (bins) {
            bins = new Float32Array(analyser.frequencyBinCount);
            analyser.getFloatFrequencyData(bins);
            for (var i = 0; i < bins.length; i = i + 1) {
                cc_output.push(bins[i]);
            }
            analyser.disconnect();
            scriptProcessor.disconnect();
            gain.disconnect();
            audioData.cc_output = cc_output.slice(0, 30);
            resolve();
        };

        oscillator.start(0);
    });

// Performs a hybrid of cc/pxi methods found above
    var hybrid_output = [];

    var p4 = new Promise(function (resolve, reject) {
        var audioCtx = new (window.AudioContext || window.webkitAudioContext),
            oscillator = audioCtx.createOscillator(),
            analyser = audioCtx.createAnalyser(),
            gain = audioCtx.createGain(),
            scriptProcessor = audioCtx.createScriptProcessor(4096, 1, 1);

        // Create and configure compressor
        compressor = audioCtx.createDynamicsCompressor();
        compressor.threshold && (compressor.threshold.value = -50);
        compressor.knee && (compressor.knee.value = 40);
        compressor.ratio && (compressor.ratio.value = 12);
        compressor.reduction && (compressor.reduction.value = -20);
        compressor.attack && (compressor.attack.value = 0);
        compressor.release && (compressor.release.value = .25);

        gain.gain.value = 0; // Disable volume
        oscillator.type = "triangle"; // Set oscillator to output triangle wave
        oscillator.connect(compressor); // Connect oscillator output to dynamic compressor
        compressor.connect(analyser); // Connect compressor to analyser
        analyser.connect(scriptProcessor); // Connect analyser output to scriptProcessor input
        scriptProcessor.connect(gain); // Connect scriptProcessor output to gain input
        gain.connect(audioCtx.destination); // Connect gain output to audiocontext destination

        scriptProcessor.onaudioprocess = function (bins) {
            bins = new Float32Array(analyser.frequencyBinCount);
            analyser.getFloatFrequencyData(bins);
            for (var i = 0; i < bins.length; i = i + 1) {
                hybrid_output.push(bins[i]);
            }
            analyser.disconnect();
            scriptProcessor.disconnect();
            gain.disconnect();

            audioData.hybrid_output = hybrid_output.slice(0, 30);
            resolve();
        };

        oscillator.start(0);
    });


    api.register("audio", function () {

        return Promise.all([p1, p2, p3, p4]).then(function () {
            return {name: "audio", data: audioData};
        });

    });
})();

(function() {
    function asinh(x) {
        if (x === -Infinity) {
            return x;
        } else {
            return Math.log(x + Math.sqrt(x * x + 1));
        }
    }

    function acosh(x) {
        return Math.log(x + Math.sqrt(x * x - 1));
    }

    function atanh(x) {
        return Math.log((1 + x) / (1 - x)) / 2;
    }

    function cbrt(x) {
        var y = Math.pow(Math.abs(x), 1 / 3);
        return x < 0 ? -y : y;
    }

    function cosh(x) {
        var y = Math.exp(x);
        return (y + 1 / y) / 2;
    }

    function expm1(x) {
        return Math.exp(x) - 1;
    }

    function log1p(x) {
        return Math.log(1 + x);
    }

    function sinh(x) {
        var y = Math.exp(x);
        return (y - 1 / y) / 2;
    }

    function tanh(x) {
        if (x === Infinity) {
            return 1;
        } else if (x === -Infinity) {
            return -1;
        } else {
            var y = Math.exp(2 * x);
            return (y - 1) / (y + 1);
        }
    }

    api.register("math", function () {
        return {
            "asinh(1)": asinh(1),
            "acosh(1e300)": (acosh(1e300) == "Infinity") ? "Infinity" : acosh(1e300),
            "atanh(05)": atanh(0.5),
            "expm1(1)": expm1(1),
            "cbrt(100)": cbrt(100),
            "log1p(10)": log1p(10),
            "sinh(1)": sinh(1),
            "cosh(10)": cosh(10),
            "tanh(1)": tanh(1)
        }
    });
})();
