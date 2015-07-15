/**
 * persamaan_akun.js
 * -----------------
 * Digunakan pada:
 * o AM_SIZ_PERSMN_AKUN | Pengaturan persamaan akun
 */
	
	//---------- Tambah sumberdana
	// Menampilkan form tambah sumber dana
	function tampilFormTambah(idPengeluaran) {
		$.ajax({
			type: 'post',
			url: AJAX_URL,
			data: {
				act: 'get.form.tambahsumber',
				id: idPengeluaran
			},
			beforeSend: function( xhr ) {
				$("#siz_akunkeluar_"+idPengeluaran+" .loading").show();
				$("#siz_akunkeluar_"+idPengeluaran+" .siz_item_control").hide();
			},
			success: function(data){
				var formContainer = $("#siz_akunkeluar_"+idPengeluaran+" .siz_form_tambahsumber");
				$(formContainer).html(data);
				$("#siz_akunkeluar_"+idPengeluaran+" .siz_select_akun").select2({
					minimumResultsForSearch: 10
				});
				$(formContainer).fadeIn(250);
			},
			error: function() {
				$("#siz_akunkeluar_"+idPengeluaran+" .loading").hide();
				$("#siz_akunkeluar_"+idPengeluaran+" .siz_item_control").show();
			}
		}).always(function(){
			$("#siz_akunkeluar_"+idPengeluaran+" .loading").hide();
		});
		return false;
	}
	// Sembunyikan form tambah sumberdana
	function cancelFormTambah(idPengeluaran) {
		var formContainer = $("#siz_akunkeluar_"+idPengeluaran+" .siz_form_tambahsumber");
		$(formContainer).hide();
		$("#siz_akunkeluar_"+idPengeluaran+" .siz_item_control").show();
		return false;
	}
	// Submit form tambah sumberdana
	function submitTambahSumberDana(formElmt, idPengeluaran) {
		var formArgs = $(formElmt).serialize();
		_ajax_send(
			formArgs+"&act=sumberdana.simpan&siz_id_pengeluaran="+idPengeluaran,
		function(response){
			if (response.status == 'ok') {
				$("#siz_akunkeluar_"+idPengeluaran+" .item_container")
					.html(response.html);
				$.gritter.add({
					title: 'Operasi berhasil',
					text: "Sumber dana berhasil ditambahkan."
				});
				// Sembunyikan form
				var formContainer = $("#siz_akunkeluar_"+idPengeluaran+" .siz_form_tambahsumber");
				$(formContainer).hide();
				$("#siz_akunkeluar_"+idPengeluaran+" .siz_item_control").show();
			} else {
				$.gritter.add({
					title: 'Operasi gagal',
					text: response.error
				});
			}
		}, "Menyimpan...", AJAX_URL);
		return false;
	}
	
	//---------- Edit sumberdana
	// Menampilkan form edit sumberdana
	function editSumberDana(idPersamaan) {
		var rowIdPrefix		= "#siz_pers_"+idPersamaan;
		var valPercentage	= $(rowIdPrefix).data("percentage");
		var valPriority		= $(rowIdPrefix).data("priority");
		$(rowIdPrefix+" .control_akun").hide();
		$(rowIdPrefix).addClass("editing").append(
			"<form action='#' onsubmit='return submitEditSumberDana(this, "+idPersamaan+");' id='siz_form_pers_"+idPersamaan+"'>" +
			"<input type='text' value='"+valPercentage+"' name='siz_persentase' "+
				"class='perc' size='6' maxlength='6' "+
				"pattern='([1-9]|[1-9][0-9]|100)(.([0-9]|[0-9][0-9]))?' />% &nbsp; " +
			"<label for='prio_"+idPersamaan+"'>Prioritas:</label>" +
			"<input type='text' value='"+valPriority+"' id='prio_"+idPersamaan+"' name='siz_prioritas' "+
				"class='prio' size='2' maxlength='1' pattern='[0-9]'/>" +
			"<div style='text-align:right;'>"+
				"<a href='#' onclick='return cancelEditForm("+idPersamaan+");' class='btn btn-sm btn-danger'>Batal</a> "+
				"<input type='submit' value='Submit' class='btn btn-sm btn-primary'/>"+
			"</div>"+
			"</form>"
		);
		return false;
	}
	function cancelEditForm(idPersamaan) {
		var rowIdPrefix		= "#siz_pers_"+idPersamaan;
		$(rowIdPrefix).removeClass("editing");
		$(rowIdPrefix+" .control_akun").show();
		$("#siz_form_pers_"+idPersamaan).remove();
		return false;
	}
	// Submit form edit sumberdana
	function submitEditSumberDana(elmt, idPers) {
		var rowIdPrefix		= "#siz_pers_"+idPers;
		var formArgs = $(elmt).serialize();
		_ajax_send(
			"act=sumberdana.simpan&siz_id_pers="+idPers+"&"+formArgs
		, function(response){
			if (response.status == 'ok') {
				$(rowIdPrefix).parent('.item_container').html(response.html);
				$.gritter.add({
					title: 'Operasi berhasil',
					text: "Sumber dana berhasil diperbaharui."
				});
				cancelEditForm(idPers);
			} else {
				$.gritter.add({
					title: 'Operasi gagal',
					text: response.error
				});
			}
		}, "Memproses...", AJAX_URL);
		return false;
	}
	
	// Hapus sumberdana
	function hapusSumberDana(idPersamaan) {
		var rowIdPrefix		= "#siz_pers_"+idPersamaan;
		$(rowIdPrefix).addClass("highlight");
		
		var conf = confirm("Hapus sumber dana yang Anda pilih?");
	
		$(rowIdPrefix).removeClass("highlight");
		if (conf == false) {
			return false;
		}
	
		_ajax_send({
			act: 'sumberdana.hapus',
			id_pers: idPersamaan
		},
		function(response){
			if (response.status == 'ok') {
				$(rowIdPrefix).remove();
				$.gritter.add({
					title: 'Operasi berhasil',
					text: "Sumber dana berhasil dihapus."
				});
			} else {
				$.gritter.add({
					title: 'Operasi gagal',
					text: response.error
				});
			}
		}, "Memproses...", AJAX_URL);
		return false;
	}