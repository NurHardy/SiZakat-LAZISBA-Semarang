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
		global $mysqli, $queryCount;
		
		$queryJumlahRinc = sprintf(
				"SELECT SUM(jumlah_anggaran) AS ja FROM ra_rincian_agenda WHERE id_agenda=%d",
				$idAgenda
		);
		$resultJumlah = mysqli_query($mysqli, $queryJumlahRinc);
		$queryCount++;
		
		$dataJumlahRinc = mysqli_fetch_array($resultJumlah);
		$jumlahRincAgenda = $dataJumlahRinc['ja'];
		$queryUpdateRinc = sprintf(
				"UPDATE ra_agenda SET jumlah_anggaran=%d WHERE id_agenda=%d",
				$jumlahRincAgenda, $idAgenda
		);
		$resultUpdate = mysqli_query($mysqli, $queryUpdateRinc);
		$queryCount++;
		
		if ($resultUpdate) return $jumlahRincAgenda;
		else return -1;
	}
	
	/**
	 * Ambil record catatan dokumen tahunan
	 * @param integer $tahunDokumen Tahun dokumen yang ingin diambil
	 * @param boolean $printErrorMsg TRUE akan menampilkan pesan error jika record tidak ditemukan
	 * @return array|null Kembali NULL jika record tidak ditemukan, atau array record jika ditemukan
	 */
	function ra_cek_dokumen($tahunDokumen, $printErrorMsg = true) {
		global $mysqli, $queryCount;
		
		$queryCekDokumen = sprintf("SELECT * FROM ra_dokumen WHERE tahun_dokumen=%d",
				intval($tahunDokumen));
		$resultCekDokumen = mysqli_query($mysqli, $queryCekDokumen);
		$queryCount++;
		
		$dataDokumen = mysqli_fetch_assoc($resultCekDokumen);
		if (($dataDokumen==null)&&($printErrorMsg)) {
			show_error_page("Dokumen perencanaan tidak ditemukan atau belum dibuat.");
		}
		return $dataDokumen;
	}
	
	/**
	 * Ambil record catatan kegiatan untuk tahun tertentu
	 * @param integer $idKegiatan ID Kegiatan yang akan diperiksa
	 * @param integer $tahunDokumen Tahun Pelaksanaan kegiatan
	 * @return array|NULL Kembali NULL jika record tidak ditemukan, atau array record jika ditemukan
	 */
	function ra_cek_catatan_kegiatan($idKegiatan, $tahunDokumen) {
		global $mysqli, $queryCount;
		
		$queryCekKegiatan = sprintf("SELECT * FROM ra_catatan_kegiatan ".
				"WHERE id_kegiatan=%d AND tahun=%d",
				intval($idKegiatan),
				intval($tahunDokumen));
		$resultCekKegiatan = mysqli_query($mysqli, $queryCekKegiatan);
		$queryCount++;
		
		$dataCatatanKegiatan = mysqli_fetch_assoc($resultCekKegiatan);
		return $dataCatatanKegiatan;
	}