jQuery.extend({
	ajaxComet_base: function (old_ajax_obj, callback) {
		var timestamp = Date.parse(new Date());
		// 构造轮询ajax参数对象
		var comet_obj = old_ajax_obj;

		comet_obj.data = $.extend(old_ajax_obj.data, {'timestamp': timestamp});
		comet_obj.success = function (res) {
			callback(res);
			ajaxComet();
		};
		comet_obj.error = function (jqXHR, textStatus, errorThrown) {
			if (jqXHR.status === 423 || jqXHR.status === 429) {		// lock || Too Many Attempts
				alert(errorThrown);
			} else if (jqXHR.status === 500) {	// error
				try {
					void 0 !== jqXHR.msg ? alert(jqXHR.msg) : void 0 !== jqXHR.error.message && alert(jqXHR.error.message);
				} catch (e) {
					alert(errorThrown);
				}
			} else if (jqXHR.status !== 410) {	// gone
				ajaxComet();
			}
		};
		var ajaxComet = function () {
			$.ajax(comet_obj);
		};
		ajaxComet();
		window.onunload = function () {
			$.ajax({
				url: old_ajax_obj.url,
				data: {'action': 'leave'},
				async: false,
				timeout: 2000 // 设置超时时间
			});
		};
	}
});