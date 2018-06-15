$.ajaxSetup({
	beforeSend: function (a) {
		// 将cookie中的X-CSRF-TOKEN加入ajax请求头
		var b = window.document.cookie.match(/(?:^|\s|;)X-XSRF-TOKEN\s*=\s*([^;]+)(?:;|$)/);
		a.setRequestHeader("X-XSRF-TOKEN", b && b[1]);
	}, error: function (a, b, http_msg) {
		try {
			a = JSON.parse(a.responseText);
			void 0 !== a.msg ? alert(a.msg) : void 0 !== a.error.message && alert(a.error.message);
		} catch (e) {
			alert(http_msg);
		}
	}
});
// jquery 方法
jQuery.extend({
	urls: [],
	getScriptWithCache: function (url, callback) {
		url = $.inpath + url;
		if ($.urls.indexOf(url) === -1) {
			$.urls.push(url);
			$.ajax({
				url: url,
				type: "GET",
				cache: true,
				async: false,
				success: function () {
					callback();
				},
				dataType: "script"
			});
		} else
			callback();
	},
	getCssWithCache: function (css) {
		$("head").append("<link>");
		var dom = $("head").children(":last");
		dom.attr({
			rel: "stylesheet",
			type: "text/css",
			href: $.inpath + css
		});
	},
	getinfo: function () {
		$.getScriptWithCache("submitData.js", function () {
			$.getinfo_base();
		});
	},
	set_language: function (key) {
		$.getScriptWithCache("language.js", function () {
			$.language = key;
		});
	},
	set_language_json: function (obj) {
		$.language_json = Object.assign(obj, $.language_json);
		return true;
	},
	lw: function (key) {
		$.getScriptWithCache("language.js", function () {
			document.write($.language_base(key));
		});
	},
	lr: function (key) {
		var temp;
		$.getScriptWithCache("language.js", function () {
			temp = $.language_base(key);
		});
		return temp;
	},
	url: function (pathinfo, param) {
		var temp;
		$.getScriptWithCache("url.js", function () {
			temp = $.url_base(pathinfo, param);
		});
		return temp;
	},
	ajaxComet: function (obj,callback) {
		$.getScriptWithCache("ajaxComet.js", function () {
			$.ajaxComet_base(obj,callback);
		});
	}
});
// jquery dom 对象方法
$.fn.extend({
	submitData: function (method, callback, httpmethod) {
		var $this = $(this);
		$.getScriptWithCache("submitData.js", function () {
			$this.submitData_base(method, callback, httpmethod);
		});
	},
	copy: function (obj, callback) {
		var $this = $(this);
		$.getScriptWithCache("ZeroClipboard.min.js", function () {
			$this.copy_base(obj, callback);
		});
	}
});