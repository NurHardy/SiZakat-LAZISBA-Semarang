/**
 * Sizakat.js
 * ---------------------------
 * Javascript global untuk SiZakat LAZISBA
 */

var isLoading = false;
var _ov_msg;

$(document).ready(function(){
	if (typeof(init_page) == "function") init_page();
	$(".siz-use-select2").select2({minimumResultsForSearch: 10});
	$('input[type=checkbox],input[type=radio]').iCheck({
    	checkboxClass: 'icheckbox_flat-blue',
    	radioClass: 'iradio_flat-blue'
	});
    //$('.colorpicker').colorpicker();
    $('.datepicker').datepicker({autoclose: true});
});
function scrollTo(elmt) {
	$('html, body').animate({
        scrollTop: $(elmt).offset().top
    }, 250);
}
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
function show_form_overlay(formTitle, formReqUrl, formReqArgs, onSubmitCallback, initCallback) {
	//get.stagepenerimaan.form
	var _reqURL = formReqUrl || "main.php?s=ajax";
	$.ajax({
		type: "POST",
		url: _reqURL,
		data: formReqArgs,
		beforeSend: function( xhr ) {
			$('#siz_overlaydlg_loading').show();
			$('#siz_overlaydlg_body').html("Loading content...").hide();
			$('#siz_overlaydlg_formctr').modal({
				backdrop: 'static'
			});
		},
		success: function(response){
			try {
				$('#siz_overlaydlg_body').html(response);
				$('#siz_overlaydlg_form').unbind().submit(onSubmitCallback);
				if (typeof(initCallback)=='function') {
					initCallback(response);
				}
			} catch (e) {
				$('#siz_overlaydlg_body').html(
						"Error happens. Try to refresh the page. Error: "+e);
			}
		},
		error: function(xhr){
			alert("Maaf, terjadi kesalahan: "+xhr.status + " " + xhr.statusText);
		}
	}).always(function() {
		$('#siz_overlaydlg_loading').hide();
		$('#siz_overlaydlg_body').show();
	});
}

function hide_form_overlay() {
	$("#siz_overlaydlg_formctr").modal('hide');
}
/*
tinyMCE.init({
				// General options
				//mode : "textareas",
				theme : "advanced",
				selector : "textarea.editme",
				plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,image,|,insertdate,inserttime,preview,|,forecolor",
				theme_advanced_buttons2 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,

				// Example content CSS (should be your site CSS)
				// using false to ensure that the default browser settings are used for best Accessibility
				// ACCESSIBILITY SETTINGS
				content_css : false,
				// Use browser preferred colors for dialogs.
				browser_preferred_colors : true,
				detect_highcontrast : true,

				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "lists/template_list.js",
				external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js",

				// Style formats
				style_formats : [
					{title : 'Bold text', inline : 'b'},
					{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
					{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
					{title : 'Example 1', inline : 'span', classes : 'example1'},
					{title : 'Example 2', inline : 'span', classes : 'example2'},
					{title : 'Table styles'},
					{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
				],

				// Replace values for the template plugin
				template_replace_values : {
					username : "Some User",
					staffid : "991234"
				}
			});
			
			$(selector).chosen(config[selector]);
*/