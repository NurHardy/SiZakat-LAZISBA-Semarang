<?php
	// Generate URL modul rincian anggaran
	// Peringatan: tidak mengonvert & menjadi &amp;
	function ra_gen_url($moduleAction, $docYear = null, $addition = null) {
		return (RA_MODULE_URL."&action=".$moduleAction.
				($docYear==null?"":"&th=".$docYear).
				($addition==null?"":"&".$addition)
				);
	}
	
	// Mengecek privilege
	function ra_check_privilege($idDivisi = -1, $printErrorMsg = true) {
		if (!empty($_SESSION['siz_divisi'])) {
			if ($idDivisi > 0) {
				if ($_SESSION['siz_divisi'] == $idDivisi) {
					return true;
				}
			} else {
				return true;
			}
		}
		if ($printErrorMsg)
			show_error_page("[Access Denied] Anda tidak berhak mengakses halaman ini.".
				"Mohon hubungi Administrator.");
		return false;
	}
	
	function ra_print_status($namaDivisiUser) {
		$tahunSekarang = date("Y");
		echo "<div class=\"well well-sm\" style=\"margin-top: 10px;\">\n";
		echo "  <a href=\"".htmlspecialchars(ra_gen_url("home"))."\"><span class=\"glyphicon glyphicon-home\"></span> ";
		echo "Perencanaan</a> |\n";
		echo "  <a href=\"".htmlspecialchars(ra_gen_url("list"))."\"><span class=\"glyphicon glyphicon-list\"></span> ";
		echo "Master Kegiatan</a> |\n";
		echo "  <a href=\"".htmlspecialchars(ra_gen_url("rekap",$tahunSekarang))."\">";
		echo "<span class=\"glyphicon glyphicon-file\"></span> Dokumen Perencanaan ".$tahunSekarang."</a> |\n";
		echo "  <a href=\"".htmlspecialchars(ra_gen_url("realisasi",$tahunSekarang))."\">";
		echo "<span class=\"glyphicon glyphicon-file\"></span> Laporan Realisasi</a> |\n";
		echo "  <span class=\"glyphicon glyphicon-user\"></span> Status/Divisi Anda : \n";
		echo "<strong>".$namaDivisiUser."</strong>\n\n";
		
		echo "</div>\n";
	}
	
	/**
	 * Mengupdate kolom jumlah anggaran kegiatan berdasarkan rincian kegiatan
	 * 
	 * @param int $idAgenda ID Agenda yang ingin diupdate rinciannya
	 * @return int Mengembalikan jumlah rincian agenda, jika query gagal, mengembalikan -1
	 */
	function update_anggaran_agenda($idAgenda) {
		global $mysqli;
		
		$queryJumlahRinc = sprintf(
				"SELECT SUM(jumlah_anggaran) AS ja FROM ra_rincian_agenda WHERE id_agenda=%d",
				$idAgenda
		);
		$resultJumlah = mysqli_query($mysqli, $queryJumlahRinc);
		$dataJumlahRinc = mysqli_fetch_array($resultJumlah);
		$jumlahRincAgenda = $dataJumlahRinc['ja'];
		$queryUpdateRinc = sprintf(
				"UPDATE ra_agenda SET jumlah_anggaran=%d WHERE id_agenda=%d",
				$jumlahRincAgenda, $idAgenda
		);
		$resultUpdate = mysqli_query($mysqli, $queryUpdateRinc);
		if ($resultUpdate) return $jumlahRincAgenda;
		else return -1;
	}