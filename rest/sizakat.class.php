<?php
require_once 'API.class.php';

class SiZakat extends API
{
    protected $User;
	protected $activeUserId = -1;
	protected $activeUserToken = null;

    public function __construct($request, $origin) {
        parent::__construct($request);

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('Tidak ada Kunci API');
        } else if (!$this->verifyKey($this->request['apiKey'], $origin)) {
			throw new Exception('Kunci API salah');
        }

		if (array_key_exists('siz_userid', $this->request))
			$this->activeUserId = intval($this->request['siz_userid']);
		
		if (array_key_exists('siz_usertoken', $this->request))
			$this->activeUserToken = ($this->request['siz_usertoken']);
		
        $this->User = "MyName!";
    }

    /**
     * Example of an Endpoint
     */
	protected function example() {
		if ($this->method == 'GET') {
			return (array('result' => "Request diterima!"));
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	}
	
	private function _verify_token($strToken) {
		global $mysqli;
		if (empty($strToken)) return -1;
		$query = "SELECT id_user FROM user WHERE token = '".$mysqli->real_escape_string($strToken)."'";
		$result = $mysqli->query($query);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if ($row) {
			return ($row['id_user']);
		} else return -1;
	}
	//================= ADMINISTRATION ===
	protected function check_token() {
		global $mysqli;
		$chkResult = "invalid";
		if ($this->method == 'GET') {
			if (($this->_verify_token($this->activeUserToken) == $this->activeUserId) && ($this->activeUserId != -1)) {
				$chkResult = 'ok';
			}
			$reportToLog = "[".date("d-m-Y H:i:s", strtotime("now"))."]\t: Checktoken token=|{$this->activeUserToken}| id=|{$this->activeUserId}|.\r\n";
			file_put_contents("tokenrequests.log", $reportToLog, FILE_APPEND);
			return array('result' => $chkResult);
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	}
	protected function request_token() {
		global $mysqli;
		$errorStr = "";
		$uName = $uPassCipher = null;
		
		if ($this->method == 'GET') {
			if (array_key_exists('siz_username', $this->request))
				$uName = ($this->request['siz_username']);
		
			if (array_key_exists('siz_passkey', $this->request))
				$uPassCipher = ($this->request['siz_passkey']);
			
			$uPass = $uPassCipher; //base64_decode($uPassCipher);
			$uPass = sha1(sha1(md5($uPass)));
			
			if ($uPass && $uName) {
				$reportToLog = "[".date("D-m-Y H:i:s", strtotime("now"))."]\t: Request u=|$uName| p=|$uPassCipher| pass_md5=|$uPass|.\r\n";
				file_put_contents("tokenrequests.log", $reportToLog, FILE_APPEND);
				$query =  "SELECT * FROM user WHERE username = '".$mysqli->real_escape_string($uName)."' AND ";
				$query .= "password = '".$mysqli->real_escape_string($uPass)."'";
				
				$result = $mysqli->query($query);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				if ($row) {
					$newToken = md5(uniqid(rand(), true));
					$insertResult = $mysqli->query("UPDATE user SET token='".$newToken."' WHERE id_user=".intval($row['id_user']));
					if ($insertResult) {
						return array(
							'result'		=> "ok",
							'data'			=> array(
								'user_id'		=> intval($row['id_user']),
								'user_token'	=> $newToken,
								'user_fullname'	=> $row['nama'],
								'user_email'	=> $row['email']
							)
						);
					} else {
						$errorStr = "Request failed! Please try again later...";
					}
				} else {
					$errorStr = "Username atau password salah! Pastikan telah diketikkan dengan benar...";
				}
			} else {
				$errorStr = "Field yang kosong harap diisi...";
			}
			return array('error' => $errorStr);
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	}
	
	//================= LAPORAN ==========
	protected function laporan_bulan() {
		$inMonth = $inYear = null;
		if ($this->method == 'GET') {
			if (array_key_exists('report_month', $this->request))
				$inMonth = ($this->request['report_month']);
		
			if (array_key_exists('report_year', $this->request))
				$inYear = ($this->request['report_year']);
			
			$error		= 0;
			$hasilAkhir = array();
			
			//if ($inMonth && $inYear) {
			require('rest_laporan_keuangan.php');
			return $hasilAkhir;
			//} else {
			//	return array('error' => 'Incomplete parameter...');
			//}
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	}
	protected function donasiku() {
		global $mysqli;
		if ($this->method == 'GET') {
			$query = "SELECT * FROM penerimaan p LEFT JOIN akun a ON p.id_akun=a.kode WHERE p.id_donatur = ".intval($this->activeUserId);
			$result = $mysqli->query($query);

			$index = 0;
			$dataResult = array();
			
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$dataResult[$index] = array(
					'tanggal'	=> $row['tanggal'],
					'no'		=> $row['id_penerimaan'],
					'kode'		=> $row['kode'],
					'jenis'		=> $row['namaakun'],
					'nominal'	=> "Rp. ".number_format($row['jumlah'], 0, ',', '.')
				);
				$index++;
			}
			return (array(
				'result'	=> "ok",
				'count'		=> $index,
				'data'		=> $dataResult
			));
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	}
	
	//================= BUS ==============
	protected function wilayah_bus() {
		global $mysqli;
		if ($this->method == 'GET') {
			
			$query = "SELECT DISTINCT wilayah FROM penerima_bus";
			$result = $mysqli->query($query);

			$index = 0;
			$dataResult = array();

			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$dataResult[$index] = $row['wilayah'];
				$index++;
			}
			return (array(
				'result' => "ok",
				'count' => $index,
				'data' => $dataResult
			));
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	}
	protected function peserta_bus() {
		global $mysqli;
		if ($this->method == 'GET') {
			//if ($this->verb == 'list')
				$query = "SELECT * FROM penerima_bus";
			//else
			//	$query = "SELECT * FROM penerima_bus WHERE f_id_donatur=".intval($this->activeUserId);
				
			$result = $mysqli->query($query);

			$index = 0;
			$dataResult = array();
			
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$dataResult[$index] = array(
					'nama'		=> $row['nama'],
					'sekolah'	=> $row['wilayah']
				);
				$index++;
			}
			return (array(
				'result' => "ok",
				'count' => $index,
				'data' => $dataResult
			));
		} else {
			return (array('error' => "Only accepts GET requests"));
		}
	} // end function
	
 }