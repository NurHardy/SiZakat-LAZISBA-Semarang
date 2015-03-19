<?php
	/**
	 * Library Name :	Form Validation
	 * Description 	:  	Fungsi Untuk Melakukan Validasi terhadap  form
	 *					Fungsi ini cocok untuk validasi form melalui AJAX method.
	 * 					validasi ini meliputi "Required, Email, dan Number"
	 * Created By 	: 	Aliyyil Musthofa
	 * URL 			:	http://aliipp.com
	 * Version		:	1.0
	**/
	 
	 /*
	  * Format untuk pengecekan
	    Array(
			Array(
				data1,nama field,required,email,number
			),
			array(
				data2,nama field,required,email,number
			)
	    )
		
		For Example : 
		$tes_validasi = array(
							array($_POST['field1'],'Field Satu','required','',''),
							array($_POST['field2'],'Email','required','email',''),
							array($_POST['field3'],'Number Order','required','','number'),
						)
						
		if(form_validation($tes_validasi)){
			//true validation
		}else{
			//false validation
		}
	  */
	  
	  
	  /*Fungsi Untuk Menyatakan valid atau tidak form yang diisi*/
	  function form_validation($data){
		$c_field = count($data);
		$total_error = 0;
		for($i=0;$i<$c_field;$i++){
			$field = $data[$i];
			
			$test = $field[0];
			//flag required or not;
			if($field[2] == 'required'){$req = 1;}else{$req = 0;}
			if($field[3]){$email = 1;}else{$email = 0;}
			if($field[4]){$numb = 1;}else{$numb = 0;}
			
			$err1 = $err2 = $err3 = 0;
			if(($req == 1) && ($test == "")){
				$err1 = 1;
			}
			
			if($email == 1){
				if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $test) ) {
					$err2 = 1;
				}
			}
			
			if($numb == 1){
				if(!is_numeric($test)) {
					$err3 = 1;
				}
			}
			
			if(($err1 == 1) || ($err2 == 1) || ($err3 == 1)){
				$total_error++;
			}			
		}
		
		if($total_error > 0){
			return false;
		}else{
			return true;
		}
	  }

	  /*Fungsi menampilkan Pesan Error yang diterima oleh tim validator*/
	  function validation_notification($data){
		$c_field = count($data);
		echo '<div class="alert alert-error">';
		for($i=0;$i<$c_field;$i++){
			$field = $data[$i];
			
			$test = $field[0];
			//flag required or not;
			if($field[2] == 'required'){$req = 1;}else{$req = 0;}
			if($field[3]){$email = 1;}else{$email = 0;}
			if($field[4]){$numb = 1;}else{$numb = 0;}
			
			$err1 = $err2 = $err3 = 0;
			if(($req == 1) && ($test == "")){
				$err1 = 1;
				echo $field[1].' Tidak Boleh Kosong<br />';
			}
			
			if($email == 1){
				if( !filter_var($test, FILTER_VALIDATE_EMAIL )) {
					$err2 = 1;
					echo $field[1].", Email Tidak Valid<br />";
				}
			}
			
			if($numb == 1){
				if(!is_numeric($test)) {
					$err3 = 1;
					echo $field[1].' Harus Berupa Angka<br />';
				}
			}
		}
		
		echo "</div>";
	  }
	  
?>