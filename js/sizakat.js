/**
 * Sizakat.js
 * ---------------------------
 * Javascript global untuk SiZakat LAZISBA
 */

var isLoading = false;
var _ov_msg;

function show_overlay(_msg) {
	if (_msg === '') {$("#siz_loading_msg").html('Sedang memproses... Mohon tunggu...');}
	else $("#siz_loading_msg").html(_msg);
	$("#siz_loading_overlay").show();
	//$("body").css("overflow-y","hidden");
	isLoading = true;
}
function hide_overlay() {
	isLoading = false;
	$("#siz_loading_overlay").fadeOut(100,function(){
		//$("body").css("overflow-y","scroll");
	});
}
function ov_change_msg(_msg) {
	$("#siz_loading_msg").html(_msg);
}
function _ajax_send(_postdata, _finishcallback, _msg, _requesturl) {
	_ov_msg = _msg || 'Menyimpan...';
	var _reqURL = _requesturl || "main.php?s=ajax";
	$.ajax({
		type: "POST",
		url: _reqURL,
		data: _postdata,
		dataType: 'json',
		beforeSend: function( xhr ) {
			isLoading = true;
			show_overlay(_ov_msg);
		},
		success: function(response){
			if (typeof(_finishcallback)=='function') {
				_finishcallback(response);
			} else {
				try {
					if (response.status != 'ok') {
						alert("Terjadi kesalahan: "+response.error);
					}
				} catch (e) {
					
				}
			}
		},
		error: function(xhr){
			alert("Maaf, terjadi kesalahan: "+xhr.status + " " + xhr.statusText);
		}
	}).always(function() {
		isLoading = false;
		hide_overlay();
	});
}