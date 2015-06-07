<?php
	/**
	 * Library Name :	Form Filter Injection
	 * Description 	:  	Fungsi Untuk Melakukan Validasi terhadap  form
	 *					Fungsi ini cocok untuk validasi form melalui AJAX method.
	 * 					validasi ini meliputi "Required, Email, dan Number"
	 * Created By 	: 	Aliyyil Musthofa
	 * URL 			:	http://aliipp.com
	 * Version		:	1.0
	**/
	
	function clear_injection($data){
		//hapus tag
		$data = strip_tags($data);
		//hapus space, and konvert all html	l tag jika masih ada
		$data = htmlspecialchars(trim(htmlentities($data)));
		$data = addslashes($data);
		
		return $data;
	}
	
	//sample
	//$str = clear_injection("Is your name O'reilly? <a>hahaha</a>");
	//echo $str;
	
	/**
	 * Library Name :	Rupiah currency formatter
	 * Description 	:  	Pemformat angka menjadi format rupiah
	 * Created By 	: 	Muhammad Nur Hardyanto
	 * URL 			:	http://nurhardyanto.web.id
	 * Version		:	1.0
	**/
	function to_rupiah($nilai) {
		return "Rp. ".number_format($nilai, 0, ',', '.');
	}
	
	// Menampilkan halaman error (harus disertakan dengan template)
	function show_error_page($errDesc) {
		$errorDescription = $errDesc;
		include COMPONENT_PATH."/file/pages/error.php";
	}
	
	// Validasi format tanggal
	function validate_date($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	// Format : date('D, d M Y', strtotime('06/04/1993'));
	function tanggal_indonesia($tanggal) {
		$format = array(
				'Sun' => 'Minggu',
				'Mon' => 'Senin',
				'Tue' => 'Selasa',
				'Wed' => 'Rabu',
				'Thu' => 'Kamis',
				'Fri' => 'Jumat',
				'Sat' => 'Sabtu',
				'Jan' => 'Januari',
				'Feb' => 'Februari',
				'Mar' => 'Maret',
				'Apr' => 'April',
				'May' => 'Mei',
				'Jun' => 'Juni',
				'Jul' => 'Juli',
				'Aug' => 'Agustus',
				'Sep' => 'September',
				'Oct' => 'Oktober',
				'Nov' => 'November',
				'Dec' => 'Desember'
		);
	
		return strtr($tanggal, $format);
	}
