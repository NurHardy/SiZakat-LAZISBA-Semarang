/**
 * select2_formatter.js
 * -----------------
 * Digunakan pada:
 * o AM_SIZ_MAP_PENERIMAAN | Halaman mapping penerimaan
 * o AM_SIZ_FRMTRXPENERIMAAN | Halaman form penerimaan
 */

//Fungsi templating untuk select2 akun
function formatItemAkun (elmtAkun) {
	if (!elmtAkun.id) { return elmtAkun.text; }
	var elmtOutput = (
	 '<div>' + elmtAkun.text + '</div>' +
	 '<div style="font-size:0.8em;"><span class="glyphicon glyphicon-triangle-right"></span> Kode: ' +
	 	elmtAkun.id + '</div>'
	);
	return $(elmtOutput);
};

// Fungsi templating untuk select2 donatur
function formatItemDonatur (elmtDonatur) {
	if (!elmtDonatur.id) { return elmtDonatur.text; }
	var userPrefix = "";
	if (elmtDonatur.type == 2) {
		  userPrefix = "[UKM]";
	} else if (elmtDonatur.type == 99) {
		  userPrefix = "[Amilin]";
	}
	var elmtOutput = (
	 '<div>' + userPrefix + " " + elmtDonatur.text + '</div>' +
	 '<div style="font-size:0.8em;"><span class="glyphicon glyphicon-envelope"></span> ' + 
	 	elmtDonatur.alamat + '</div>'
	);
	return $(elmtOutput);
};

//Fungsi templating untuk select2 bank
function formatItemBank (elmtAkun) {
	if (!elmtAkun.id) { return elmtAkun.text; }
	var elmtOutput = (
	 '<div>' + elmtAkun.text + '</div>' +
	 '<div style="font-size:0.8em;"><span class="glyphicon glyphicon-triangle-right"></span> Rek: ' + 
	 	(elmtAkun.rek?elmtAkun.rek:'-') + '</div>'
	);
	return $(elmtOutput);
};
