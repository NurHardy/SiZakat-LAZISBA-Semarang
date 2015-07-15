<?php

/**
 * Helper untuk mengunggah file
 * UploadSetting Array:
 *  path => URL tempat file diletakkan (Ex: assets/uploads/stuffs)
 *  exts => Ekstensi yang diperbolehkan
 *  size => Ukuran diperbolehkan (dalam byte)
 *  name => Nama field file yang akan diproses
 * Return array:
 *  name => nama file asli
 *  url => Path lengkap file
 *  ext => ekstensi file (lowercase)
 *  size => ukuran file
 *  
 * @param array $uploadSetting
 * @param string $errorDesc Jika ada error, output deskripsi errornya di sini
 * @return array Informasi file terupload.
 * @author Muhammad Nur Hardyanto
 */
function do_upload($uploadSetting, &$errorDesc) {
	//================== UPLOAD HANDLER
	// Put settings here
	$uploadPath = (isset($uploadSetting['path'])?$uploadSetting['path']:"");
	$validExts	= $uploadSetting['exts'];
	$maxSize	= $uploadSetting['size']; // Dalam byte
	$fieldName	= $uploadSetting['name'];
	
	// Init variables
	$fileName	= null;
	$fileSize	= 0;
	$urlGambar	= null;
	$ekstensi	= null;
		
	//=== UPLOAD FILE
	if (!empty($_FILES[$fieldName]['name'])) {
		$fileSize = $_FILES[$fieldName]['size'];
		$fileName = $_FILES[$fieldName]['name'];
		$urlElements = explode(".", $_FILES[$fieldName]['name']);
		$ekstensi = end($urlElements);
		$ekstensi = strtolower($ekstensi);
	
		if ((in_array($ekstensi, $validExts)) && ($fileSize <= $maxSize))
		{
			$dateChunk = date("Ymd-His");
			$saltChunk = substr(md5(uniqid(rand(), true)), 0, 5);
			$uploadedFile = sprintf("%s/%s-%s.%s", $uploadPath, $dateChunk, $saltChunk, $ekstensi);
			if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], FCPATH."\\".$uploadedFile))  {
				// Berhasil
				$urlGambar = $uploadedFile;
			} else {
				$errorDesc = "Error mengupload berkas. Silakan coba lagi atau hubungi administrator.";
				return null;
			}
		} else { // jika tidak valid / terlalu besar
			$errorDesc = "Ukuran dan jenis file harus sesuai.";
			return null;
		}
	} else {
		return null;
	}
	
	return array(
		'name'	=> $fileName,
		'url'	=> $urlGambar,
		'ext'	=> $ekstensi,
		'size'	=> $fileSize
	);
}