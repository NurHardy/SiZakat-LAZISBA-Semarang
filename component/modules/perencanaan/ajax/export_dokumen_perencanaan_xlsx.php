<?php

	$monthName = array(
			"Bulan","Januari","Februari","Maret","April","Mei","Juni",
			"Juli","Agustus","September","Oktober","November","Desember"
	);
	$listDivisi = array("Guest",
			"Keuangan",
			"Kantor",
			"Marketing",
			"Campaign",
			"Program",
			99 => "Administrator Perencanaan"
	);
	$listPrioritas = array(
			0 => "-",
			1 => "Fix",
			2 => "Penjelasan",
			3 => "Tentatif",
	);

	$tahunDokumen = $_POST['th'];
	
	$errorDesc = null;
	if (empty($tahunDokumen)) {
		$errorDesc = ("Argumen tidak lengkap.");
	}
	if (!is_numeric($tahunDokumen)) {
		$errorDesc = ("Tahun tidak valid.");
	}
	
	if ($errorDesc != null) {
		echo json_encode(array('status' => 'error','error'	=> $errorDesc));
		return;
	}
	
	//========== EXPORT EXCEL DENGAN PHPEXCEL
	require_once COMPONENT_PATH."\\libraries\\phpexcel\\PHPExcel.php";
	
	/** Create a new PHPExcel Object **/
	$objPHPExcel = new PHPExcel();
	
	define('IDX_COL_HOME', 2);	// Kolom dimulai pada kolom ke 3 (index 2)
	define('IDX_ROW_START', 1); // Dimulai dari baris 1
	define('TABLE_COLS', 8);	// Tabel ada 8 kolom
	
	$exportFileName = "perencanaan-".$tahunDokumen.".xlsx";
	$columnWidths = array(
			2, // [A]
			2, // [B]
			6,	// Kolom nomor
			15,	// Kolom bulan
			10,	// Kolom tanggal
			35,	// [F] Kolom kegiatan dan rincian
			13, // Kolom divisi
			20,	// Kolom besar rincian
			20,	// Kolom rincian per kegiatan
			20	// Kolom rincian per bulan
	);
	$styleHeader = array(
		'alignment' => array(
			'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER
		),
		'font'  => array(
		    'bold'  => true
		),
	);
	$styleBorderAll = array(
		'borders' => array(
			'allborders'	=> array(
				'style'		=> PHPExcel_Style_Border::BORDER_THIN
			)
		)
	);
	$styleAlignCenterTop = array(
		'alignment' => array(
			'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'			=> true
		),
	);
	$styleFormatRupiah = array(
		'numberformat' => array(
			'code'	=> "_(Rp* #,##0.00_)"
		)
	);
	// Mulai generate worksheet
	try {
		// Buat worksheet baru
		//$worksheetRAT = new PHPExcel_Worksheet($objPHPExcel, 'Agenda RAT '.$tahunDokumen);
		
		// Jika ingin menambah worksheet baru:
		//$objPHPExcel->addSheet($worksheetRAT, 0);
		//$objPHPExcel->setActiveSheetIndex(0);
		
		$worksheetRAT = $objPHPExcel->getActiveSheet();
		$worksheetRAT->setTitle('Agenda RAT '.$tahunDokumen);
		
		// Set up kolom
		foreach ($columnWidths as $colIdx => $colWidth) {
			$worksheetRAT->getColumnDimensionByColumn($colIdx)->setWidth($colWidth);
		}
		
		// Judul worksheet pada bagian atas
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME, IDX_ROW_START,
				IDX_COL_HOME+TABLE_COLS-1, IDX_ROW_START+1)
			->setCellValueByColumnAndRow(IDX_COL_HOME, IDX_ROW_START, "Agenda RAT ".$tahunDokumen);
		
		$worksheetRAT->getStyleByColumnAndRow(IDX_COL_HOME, IDX_ROW_START)
			->applyFromArray($styleHeader); // Set style
		$worksheetRAT->getStyleByColumnAndRow(IDX_COL_HOME, IDX_ROW_START)
			->getFont()->setSize(18);
		
		$rowBaseTable	= IDX_ROW_START + 3;
		
		// Baris judul divisi
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME, $rowBaseTable,
				IDX_COL_HOME+TABLE_COLS-1, $rowBaseTable)
			->setCellValueByColumnAndRow(IDX_COL_HOME, $rowBaseTable,
					'DIVISI | '.date("d m Y, H:i"));
		
		// Baris header tabel
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME, $rowBaseTable+1,
				IDX_COL_HOME, $rowBaseTable+2)
				->setCellValueByColumnAndRow(IDX_COL_HOME,$rowBaseTable+1,'No.');
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME+1, $rowBaseTable+1,
				IDX_COL_HOME+1, $rowBaseTable+2)
				->setCellValueByColumnAndRow(IDX_COL_HOME+1,$rowBaseTable+1,'Bulan');
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME+2, $rowBaseTable+1,
				IDX_COL_HOME+2, $rowBaseTable+2)
				->setCellValueByColumnAndRow(IDX_COL_HOME+2,$rowBaseTable+1,'Tanggal');
		
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME+3, $rowBaseTable+1,
				IDX_COL_HOME+6, $rowBaseTable+1)
				->setCellValueByColumnAndRow(IDX_COL_HOME+3,$rowBaseTable+1,'Agenda');
		$worksheetRAT->setCellValueByColumnAndRow(
				IDX_COL_HOME+3,$rowBaseTable+2, 'Kegiatan dan Rincian');
		$worksheetRAT->setCellValueByColumnAndRow(
				IDX_COL_HOME+4,$rowBaseTable+2, 'Divisi');
		$worksheetRAT->setCellValueByColumnAndRow(
				IDX_COL_HOME+5,$rowBaseTable+2, 'Rincian Anggaran');
		$worksheetRAT->setCellValueByColumnAndRow(
				IDX_COL_HOME+6,$rowBaseTable+2, 'Per Kegiatan');
		
		$worksheetRAT->mergeCellsByColumnAndRow(
				IDX_COL_HOME+7, $rowBaseTable+1,
				IDX_COL_HOME+7, $rowBaseTable+2)
				->setCellValueByColumnAndRow(IDX_COL_HOME+7,$rowBaseTable+1,'Anggaran Per Bulan');
		
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME,$rowBaseTable+1,
				IDX_COL_HOME+TABLE_COLS-1,$rowBaseTable+2)
			->applyFromArray($styleHeader)->applyFromArray($styleBorderAll);


		// Iterasi agenda
		$queryAgenda =	sprintf(
				"SELECT a.*, MONTH(a.tgl_mulai) AS bulan, DAY(a.tgl_mulai) AS tanggal, ".
				"k.nama_kegiatan AS kegiatan, k.divisi AS divisi ".
				"FROM ra_agenda AS a, ra_kegiatan AS k ".
				"WHERE YEAR(a.tgl_mulai)=%d AND k.id_kegiatan=a.id_kegiatan ORDER BY a.tgl_mulai",
				$tahunDokumen
			);
		$resultAgenda	= mysqli_query($mysqli, $queryAgenda);
		
		$currentRow		= $rowBaseTable + 3;
		$rowBaseMonth	= $currentRow;
		$rowBaseAgenda	= $currentRow;
		
		$currentMonth	= 0;
		$showMonthName	= true;
		$counterAgenda	= 0;
		
		$colLetterRincian		= PHPExcel_Cell::stringFromColumnIndex(IDX_COL_HOME+5);
		$colLetterTotalKegiatan	= PHPExcel_Cell::stringFromColumnIndex(IDX_COL_HOME+6);
		$colLetterTotalBulan	= PHPExcel_Cell::stringFromColumnIndex(IDX_COL_HOME+7);
		
		while ($rowAgenda = mysqli_fetch_array($resultAgenda)) {
			$counterAgenda++;
			if ($rowAgenda['bulan'] != $currentMonth) {
				if ($currentMonth != 0) {
					// Merge kolom bulan
					$worksheetRAT->mergeCellsByColumnAndRow(
							IDX_COL_HOME+1, $rowBaseMonth,
							IDX_COL_HOME+1, $currentRow-1);
					$worksheetRAT->mergeCellsByColumnAndRow(
							IDX_COL_HOME+7, $rowBaseMonth,
							IDX_COL_HOME+7, $currentRow-1);
				}
				if ($rowBaseMonth < $currentRow) {
					// Formula SUM
					$worksheetRAT->setCellValueExplicitByColumnAndRow(
							IDX_COL_HOME+7, $rowBaseMonth,
							"=SUM(".$colLetterTotalKegiatan.($rowBaseMonth).":".
							$colLetterTotalKegiatan.($currentRow-1).")",
							PHPExcel_Cell_DataType::TYPE_FORMULA);
				}
				$rowBaseMonth = $currentRow;
				$currentMonth = $rowAgenda['bulan'];
				$showMonthName	= true;
			}
			// ====== AGENDA ===========
			$rowBaseAgenda = $currentRow;
			
			if ($showMonthName) {
				$worksheetRAT->setCellValueByColumnAndRow(
						IDX_COL_HOME+1,$currentRow,$monthName[$rowAgenda['bulan']]);
				$showMonthName = false;
			}
			$labelPrioritas = (empty($rowAgenda['prioritas_agenda'])?"":
					"\r\n(".$listPrioritas[$rowAgenda['prioritas_agenda']].")");
			$worksheetRAT->setCellValueByColumnAndRow(
					IDX_COL_HOME, $currentRow, $counterAgenda);
			$worksheetRAT->setCellValueByColumnAndRow(
					IDX_COL_HOME+2, $currentRow, $rowAgenda['tanggal']);
			$worksheetRAT->setCellValueByColumnAndRow(
					IDX_COL_HOME+3, $currentRow, $rowAgenda['kegiatan']);
			$worksheetRAT->setCellValueByColumnAndRow(
					IDX_COL_HOME+4, $currentRow,
					$listDivisi[$rowAgenda['divisi']].
					$labelPrioritas);
			
			// Jika terdapat catatan/komentar
			if (!empty($rowAgenda['catatan'])) {
				$worksheetRAT->getCommentByColumnAndRow(
						IDX_COL_HOME+3, $currentRow)->setAuthor(
								"Divisi ".$listDivisi[$rowAgenda['divisi']]);
				$worksheetRAT->getCommentByColumnAndRow(
						IDX_COL_HOME+3, $currentRow)->getText()
						->createText($rowAgenda['catatan']);
			}
			
			$currentRow++;
			$queryRincian = sprintf(
					"SELECT * FROM ra_rincian_agenda WHERE id_agenda=%d",
					$rowAgenda['id_agenda']
				);
			$resultRincian = mysqli_query($mysqli, $queryRincian);
			while ($rowRincian = mysqli_fetch_array($resultRincian)) {
				$worksheetRAT->setCellValueByColumnAndRow(
						IDX_COL_HOME+3,$currentRow, ' - '.$rowRincian['nama_rincian']);
				$worksheetRAT->setCellValueByColumnAndRow(
						IDX_COL_HOME+5,$currentRow, $rowRincian['jumlah_anggaran']);
				
				$currentRow++;
			}
			
			// Merge per agenda
			$worksheetRAT->mergeCellsByColumnAndRow(
					IDX_COL_HOME, $rowBaseAgenda,
					IDX_COL_HOME, $currentRow-1);
			$worksheetRAT->mergeCellsByColumnAndRow(
					IDX_COL_HOME+2, $rowBaseAgenda,
					IDX_COL_HOME+2, $currentRow-1);
			$worksheetRAT->mergeCellsByColumnAndRow(
					IDX_COL_HOME+6, $rowBaseAgenda,
					IDX_COL_HOME+6, $currentRow-1);
			$worksheetRAT->mergeCellsByColumnAndRow(
					IDX_COL_HOME+4, $rowBaseAgenda,
					IDX_COL_HOME+4, $currentRow-1);
			
			if ($rowBaseAgenda < $currentRow) {
				// Formula SUM
				$worksheetRAT->setCellValueExplicitByColumnAndRow(
						IDX_COL_HOME+6, $rowBaseAgenda,
						"=SUM(".$colLetterRincian.($rowBaseAgenda+1).":".
						$colLetterRincian.($currentRow-1).")",
						PHPExcel_Cell_DataType::TYPE_FORMULA);
			} else {
				$worksheetRAT->setCellValueByColumnAndRow(
						IDX_COL_HOME+6, $rowBaseAgenda, 0);
			}
			$worksheetRAT
					->getStyleByColumnAndRow(IDX_COL_HOME+6,$currentRow)
					->applyFromArray($styleFormatRupiah);
			
			// ======== END OF AGENDA
		}
		if ($currentMonth != 0) {
			// Merge kolom bulan
			$worksheetRAT->mergeCellsByColumnAndRow(
					IDX_COL_HOME+1, $rowBaseMonth,
					IDX_COL_HOME+1, $currentRow-1);
			$worksheetRAT->mergeCellsByColumnAndRow(
					IDX_COL_HOME+7, $rowBaseMonth,
					IDX_COL_HOME+7, $currentRow-1);
			if ($rowBaseMonth < $currentRow) {
				// Formula SUM
				$worksheetRAT->setCellValueExplicitByColumnAndRow(
						IDX_COL_HOME+7, $rowBaseMonth,
						"=SUM(".$colLetterTotalKegiatan.($rowBaseMonth).":".
						$colLetterTotalKegiatan.($currentRow-1).")",
						PHPExcel_Cell_DataType::TYPE_FORMULA);
			}
		}
		
		// Set formatting rupiah untuk rincian dan jumlah
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME+5,$rowBaseTable+3,
				IDX_COL_HOME+TABLE_COLS-1,$currentRow-1)
			->applyFromArray($styleFormatRupiah)
			->applyFromArray($styleAlignCenterTop);
		
		// Atur alignment untuk merger cell
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME,$rowBaseTable+3,
				IDX_COL_HOME+2,$currentRow-1)
			->applyFromArray($styleAlignCenterTop);
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME+4,$rowBaseTable+3,
				IDX_COL_HOME+4,$currentRow-1)
			->applyFromArray($styleAlignCenterTop);
		
		// Set border untuk seluruh cell
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME,$rowBaseTable+1,
				IDX_COL_HOME+TABLE_COLS-1,$currentRow-1)
				->applyFromArray($styleBorderAll);
		
		// Baris grand total
		$worksheetRAT->setCellValueByColumnAndRow(
				IDX_COL_HOME+3, $currentRow+1,
				"Total jumlah anggaran");
		$worksheetRAT->setCellValueExplicitByColumnAndRow(
				IDX_COL_HOME+7, $currentRow+1,
				"=SUM(".$colLetterTotalBulan.($rowBaseTable+3).":".
				$colLetterTotalBulan.($currentRow-1).")",
				PHPExcel_Cell_DataType::TYPE_FORMULA);
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME+7, $currentRow+1)
				->applyFromArray($styleFormatRupiah);
		$worksheetRAT->getStyleByColumnAndRow(
				IDX_COL_HOME, $currentRow+1,
				IDX_COL_HOME+TABLE_COLS-1, $currentRow+1)
				->getFont()->setBold(true);
		
		// Sorot total jumlah anggaran
		$worksheetRAT->setSelectedCellByColumnAndRow(
				IDX_COL_HOME+TABLE_COLS-1, $currentRow+1);
		// Simpan file
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->setPreCalculateFormulas(true);
		$objWriter->save(FCPATH."\\downloads\\".$exportFileName);
		
		echo json_encode(array(
				'status'	=> 'ok',
				'data'		=> array(
						'filename'	=> $exportFileName,
						'fileurl'	=> 'downloads/'.$exportFileName,
						'icon'		=> 'ico-xlsx.png'
				),
				'title'		=> 'Dokumen Perencanaan tahun '.$tahunDokumen
		));
	} catch (Exception $e) {
		echo json_encode(array(
				'status'	=> 'error',
				'error'		=> '[PHPExcel error] '.$e->getMessage()." Trace:<br>".$e->getTraceAsString()
		));
	}
	
	
	