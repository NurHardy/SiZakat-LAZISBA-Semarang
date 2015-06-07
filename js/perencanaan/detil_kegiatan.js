/**
 * detil_kegiatan.js
 * -----------------
 * Digunakan pada:
 * o AM_SIZ_RA_DTLKGT | Detil Kegiatan
 */

	function edit_rincian(idRincian) {
		var divNamaRincian	= $("#siz_rincian_"+idRincian+" .siz-n-rinc");
		var divNilaiRincian = $("#siz_rincian_"+idRincian+" .siz-v-rinc");
		var divControl		= $("#siz_rincian_"+idRincian+" .siz-rinc-control");
		
		var namaRincian = $(divNamaRincian).html();
		var jumlahRincian = $(divNilaiRincian).data('nilai');

		var tagPrefix = "siz-rinc-"+idRincian+"-";
		$(divNamaRincian).hide().after(
				"<input type='text' name='"+tagPrefix+"n-edit' id='"+tagPrefix+
				"n-edit' value=\""+namaRincian+"\" class=\"form-control input-sm\"/>");
		$(divNilaiRincian).hide().after(
				"<input type='text' name='"+tagPrefix+"v-edit' id='"+tagPrefix+
				"v-edit' value=\""+jumlahRincian+"\" class=\"form-control input-sm\"/>");
		$(divControl).hide().after("<div class=\"siz-rinc-editcontrol\">"+
				"<a href=\"#\" onclick=\"return submit_rincian_edit("+idRincian+");\">"+
				"<span class=\"glyphicon glyphicon-ok\"></span> Simpan</a> "+
				"<a href=\"#\" onclick=\"return cancel_edit_rincian("+idRincian+");\">"+
				"<span class=\"glyphicon glyphicon-remove\"></span> Batal</a>"+
				"</div>");
		return false;
	}
	function cancel_edit_rincian(idRincian) {
		var rowIdPrefix		= "#siz_rincian_"+idRincian;
		var tagPrefix		= "#siz-rinc-"+idRincian+"-";
		
		$(rowIdPrefix+" .siz-n-rinc").show();
		$(rowIdPrefix+" .siz-v-rinc").show();
		$(rowIdPrefix+" "+tagPrefix+"n-edit").remove();
		$(rowIdPrefix+" "+tagPrefix+"v-edit").remove();
		
		$(rowIdPrefix+" .siz-rinc-control").show();
		$(rowIdPrefix+" .siz-rinc-editcontrol").remove();
		
		return false;
	}
	function submit_rincian_tambahan(idAgenda) {
		var rowIdPrefix		= "#siz_tambah_rinc_ag_"+idAgenda;
		var nRincian		= $(rowIdPrefix+" [name=txt_rinc_n_ag_"+idAgenda+"]").val();
		var vRincian		= $(rowIdPrefix+" [name=txt_rinc_v_ag_"+idAgenda+"]").val();
		
		_ajax_send({
			act: 'agenda.rincian.add',
			ida: idAgenda,
			namaRinc: nRincian,
			nilaiRinc: vRincian
		}, function(response){
			if (response.status == 'ok') {
				appendRincian(response.id_a, response.new_row);
				$("#siz_agenda_"+response.id_a+" .siz-total-anggaran")
					.html(response.t_agenda);
				$.gritter.add({
					title: "Rincian berhasil ditambahkan",
					text: "Rincian '"+response.new_row.n+"' berhasil ditambahkan"
				});
				$("input[name=txt_rinc_n_ag_"+response.id_a+"]").val('');
				$("input[name=txt_rinc_v_ag_"+response.id_a+"]").val('');
			} else {
				$.gritter.add({
					title: 'Rincian gagal ditambahkan',
					text: response.error
				});
			}
			
		}, "Menyimpan...", AJAX_URL);
		return false;
	}
	function submit_rincian_edit(idRincian) {
		var rowIdPrefix		= "#siz_rincian_"+idRincian;
		var tagPrefix		= "#siz-rinc-"+idRincian+"-";
		
		var nRincian		= $(rowIdPrefix+" "+tagPrefix+"n-edit").val();
		var vRincian		= $(rowIdPrefix+" "+tagPrefix+"v-edit").val();
		
		_ajax_send({
			act: 'agenda.rincian.edit',
			idr: idRincian,
			namaRinc: nRincian,
			nilaiRinc: vRincian
		}, function(response){
			if (response.status == 'ok') {
				
				updateRincian(response.new_row);
				$("#siz_agenda_"+response.id_a+" .siz-total-anggaran")
					.html(response.t_agenda);
				$.gritter.add({
					title: "Rincian berhasil diperbaharui",
					text: "Rincian '"+response.old_name+"' berhasil diperbaharui"
				});
				cancel_edit_rincian(response.new_row.id);
			} else {
				$.gritter.add({
					title: 'Rincian gagal diperaharui',
					text: response.error
				});
			}
			
		}, "Menyimpan...", AJAX_URL);
		return false;
	}
	function hapus_rincian(idRincian) {
		var rowIdPrefix		= "#siz_rincian_"+idRincian;
		var divNamaRincian	= $(rowIdPrefix+" .siz-n-rinc");
		var namaRincian		= $(divNamaRincian).html();
		$(rowIdPrefix).addClass("siz-row-highlight-fordelete");
		var uResp = confirm("Hapus rincian '"+namaRincian+"'?");
		$(rowIdPrefix).removeClass("siz-row-highlight-fordelete");
		if (uResp == false) {
			$(rowIdPrefix).removeClass("siz-row-highlight-fordelete");
			return false;
		}
		_ajax_send({
			act: 'agenda.rincian.delete',
			idr: idRincian
		}, function(response){
			if (response.status == 'ok') {
				var idRincian = response.old_row_id;
				$("#siz_agenda_"+response.id_a+" .siz-total-anggaran")
					.html(response.t_agenda);
				$.gritter.add({
					title: 'Berhasil Dihapus',
					text: "Rincian agenda kegiatan terpilih berhasil dihapus."
				});
				$("#siz_rincian_"+idRincian).fadeOut(250,function(){
					$(this).remove();
				});
			} else {
				$.gritter.add({
					title: 'Rincian gagal dihapus',
					text: response.error
				});
			}
			
		}, "Menyimpan...", AJAX_URL);
		return false;
	}
	function updateRincian(dataRincian) {
		var idRincian = dataRincian.id;
		$("#siz_rincian_"+idRincian+" .siz-n-rinc").html(dataRincian.n);
		$("#siz_rincian_"+idRincian+" .siz-v-rinc")
			.data('nilai',dataRincian.vn)
			.html(dataRincian.v);
	}
	function appendRincian(idAgenda, dataRincian) {
		try {
			var controlHtml = "";
			var newRowElement = "";
			var idRincian = dataRincian.id;
			var namaRincian = dataRincian.n;
			var nilaiRincian = dataRincian.v;
			
			// Sesuai AM_SIZ_RA_DTLKGT
			controlHtml  = "<div class=\"siz-rinc-control\"><a href=\"#\" onclick=\"return edit_rincian("+idRincian+");\">";
			controlHtml += "<span class=\"glyphicon glyphicon-pencil\"></span> Edit</a> ";
			controlHtml += "<a href=\"#\" onclick=\"return hapus_rincian("+idRincian+");\" class=\"red_link\">";
			controlHtml += "<span class=\"glyphicon glyphicon-trash\"></span> Hapus</a></div>";
			
			newRowElement += "<tr id=\"siz_rincian_"+idRincian+"\" style=\"display:none;\">";
			newRowElement += "<td><span class=\"siz-n-rinc\">"+namaRincian+"</span></td>";
			newRowElement += "<td><span class=\"siz-v-rinc\" data-nilai=\""+dataRincian.vn+"\">"+nilaiRincian+"</span></td>";
			newRowElement += "<td>"+controlHtml+"</td></tr>";
			
			$("#siz_agenda_"+idAgenda+" table tbody").append(newRowElement);
			$("#siz_rincian_"+idRincian).fadeIn(250);
		} catch (e) {
			alert("Terjadi kesalahan internal...");
		}
	}