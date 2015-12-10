/**
 * detil_kegiatan_master.js
 * -----------------
 * Digunakan pada:
 * o AM_SIZ_RA_DTLMASTKGT | Detil Master Kegiatan
 */

	function edit_rincian(idRincian) {
		var divNamaRincian	= $("#siz_mast_rincian_"+idRincian+" .siz-n-rinc");
		var divNilaiRincian = $("#siz_mast_rincian_"+idRincian+" .siz-v-rinc");
		var divControl		= $("#siz_mast_rincian_"+idRincian+" .siz-rinc-control");
		
		var namaRincian = $(divNamaRincian).html();
		var jumlahRincian = $(divNilaiRincian).data('nilai');

		var tagPrefix = "siz-rinc-"+idRincian+"-";
		$(divNamaRincian).hide().after(
				"<input type='text' name='"+tagPrefix+"n-edit' id='"+tagPrefix+
				"n-edit' value=\""+namaRincian+"\" class=\"form-control input-sm\"/>");
		$(divNilaiRincian).hide().after(
				"<div class=\"input-group siz-input-anggaran\" id=\""+tagPrefix+"v-container\">"+
				"<div class=\"input-group-addon\">Rp.</div>"+
				 "<input type='text' name='"+tagPrefix+"v-edit' id='"+tagPrefix+
				  "v-edit' value=\""+jumlahRincian+"\" class=\"form-control input-sm\"/></div>");
		$(divControl).hide().after("<div class=\"siz-rinc-editcontrol\">"+
				"<a href=\"#\" onclick=\"return submit_rincian_edit("+idRincian+");\">"+
				"<span class=\"glyphicon glyphicon-ok\"></span> Simpan</a> "+
				"<a href=\"#\" onclick=\"return cancel_edit_rincian("+idRincian+");\">"+
				"<span class=\"glyphicon glyphicon-remove\"></span> Batal</a>"+
				"</div>");
		return false;
	}
	function cancel_edit_rincian(idRincian) {
		var rowIdPrefix		= "#siz_mast_rincian_"+idRincian;
		var tagPrefix		= "#siz-rinc-"+idRincian+"-";
		
		$(rowIdPrefix+" .siz-n-rinc").show();
		$(rowIdPrefix+" .siz-v-rinc").show();
		$(rowIdPrefix+" "+tagPrefix+"n-edit").remove();
		$(rowIdPrefix+" "+tagPrefix+"v-container").remove();
		//$(rowIdPrefix+" "+tagPrefix+"v-edit").remove();
		
		$(rowIdPrefix+" .siz-rinc-control").show();
		$(rowIdPrefix+" .siz-rinc-editcontrol").remove();
		
		return false;
	}
	function submit_rincian_tambahan(idKegiatan) {
		var rowIdPrefix		= "#siz_row_tambah_rincian";
		var nRincian		= $(rowIdPrefix+" [name=txt_nama_rincian]").val();
		var vRincian		= $(rowIdPrefix+" [name=txt_anggaran_rincian]").val();
		
		_ajax_send({
			act: 'mastkegiatan.rincian.add',
			idk: idKegiatan,
			namaRinc: nRincian,
			nilaiRinc: vRincian
		}, function(response){
			if (response.status == 'ok') {
				appendRincian(response.id_k, response.new_row);
				$(".siz-total-rinc-awal")
					.html(response.t_kegiatan);
				$.gritter.add({
					title: "Rincian awal berhasil ditambahkan",
					text: "Rincian awal '"+response.new_row.n+"' berhasil ditambahkan"
				});
				$("input[name=txt_nama_rincian]").val('');
				$("input[name=txt_anggaran_rincian]").val('');
			} else {
				$.gritter.add({
					title: 'Rincian awal gagal ditambahkan',
					text: response.error
				});
			}
			
		}, "Menyimpan...", AJAX_URL);
		return false;
	}
	function submit_rincian_edit(idRincian) {
		var rowIdPrefix		= "#siz_mast_rincian_"+idRincian;
		var tagPrefix		= "#siz-rinc-"+idRincian+"-";
		
		var nRincian		= $(rowIdPrefix+" "+tagPrefix+"n-edit").val();
		var vRincian		= $(rowIdPrefix+" "+tagPrefix+"v-edit").val();
		
		_ajax_send({
			act: 'mastkegiatan.rincian.edit',
			idr: idRincian,
			namaRinc: nRincian,
			nilaiRinc: vRincian
		}, function(response){
			if (response.status == 'ok') {
				
				updateRincian(response.new_row);
				$(".siz-total-rinc-awal").html(response.t_kegiatan);
				$.gritter.add({
					title: "Rincian awal berhasil diperbaharui",
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
		var rowIdPrefix		= "#siz_mast_rincian_"+idRincian;
		var divNamaRincian	= $(rowIdPrefix+" .siz-n-rinc");
		var namaRincian		= $(divNamaRincian).html();
		$(rowIdPrefix).addClass("siz-row-highlight-fordelete");
		var uResp = confirm("Hapus rincian '"+namaRincian+"'?");
		$(rowIdPrefix).removeClass("siz-row-highlight-fordelete");
		if (uResp == false) {
			return false;
		}
		_ajax_send({
			act: 'mastkegiatan.rincian.delete',
			idr: idRincian
		}, function(response){
			if (response.status == 'ok') {
				var idRincian = response.old_row_id;
				$(".siz-total-rinc-awal").html(response.t_kegiatan);
				$.gritter.add({
					title: 'Berhasil Dihapus',
					text: "Rincian awal kegiatan terpilih berhasil dihapus."
				});
				$("#siz_mast_rincian_"+idRincian).fadeOut(250,function(){
					$(this).remove();
				});
			} else {
				$.gritter.add({
					title: 'Rincian awal gagal dihapus',
					text: response.error
				});
			}
			
		}, "Menyimpan...", AJAX_URL);
		return false;
	}
	function updateRincian(dataRincian) {
		var idRincian = dataRincian.id;
		$("#siz_mast_rincian_"+idRincian+" .siz-n-rinc").html(dataRincian.n);
		$("#siz_mast_rincian_"+idRincian+" .siz-v-rinc")
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
			
			// Sesuai AM_SIZ_RA_DTLMASTKGT	
			controlHtml  = "<div class=\"siz-rinc-control\"><a href=\"#\" class=\"btn btn-xs btn-primary\" ";
			controlHtml += "onclick=\"return edit_rincian("+idRincian+");\" title=\"Edit\">";
			controlHtml += "<i class=\"glyphicon glyphicon-pencil\"></i> </a>&nbsp;";
			controlHtml += "<a href=\"#\" class=\"btn btn-xs btn-danger\" ";
			controlHtml += "onclick=\"return hapus_rincian("+idRincian+");\" title=\"Hapus\">";
			controlHtml += "<i class=\"glyphicon glyphicon-trash\"></i> </a></div>";
			
			newRowElement += "<tr id=\"siz_mast_rincian_"+idRincian+"\" style=\"display:none;\">";
			newRowElement += "<td><span class=\"siz-n-rinc\">"+namaRincian+"</span></td>";
			newRowElement += "<td><span class=\"siz-v-rinc\" data-nilai=\""+dataRincian.vn+"\">"+nilaiRincian+"</span></td>";
			newRowElement += "<td>"+controlHtml+"</td></tr>";
			
			$("#siz-tabel-rinc-awal tbody").append(newRowElement);
			$("#siz_mast_rincian_"+idRincian).fadeIn(250);
		} catch (e) {
			alert("Terjadi kesalahan internal...");
		}
	}