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
?>