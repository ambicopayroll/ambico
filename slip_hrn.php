<?php

if ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	//include "adodb5/adodb.inc.php";
	//$conn = ADONewConnection('mysql');
	//$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
	include "conn_adodb.php";
}
else {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}

function tgl_indo_header($tgl) {
	$a_namabln = array(
		1 => "JAN",
		"FEB",
		"MAR",
		"APR",
		"MEI",
		"JUN",
		"JUL",
		"AGS",
		"SEP",
		"OKT",
		"NOV",
		"DES");
	$a_hari = array(
		"Min",
		"Sen",
		"Sel",
		"Rab",
		"Kam",
		"Jum",
		"Sab");
	$tgl_data = strtotime($tgl);
	//$tgl_data = $tgl;
	$tanggal = date("d", $tgl_data);
	$bulan = $a_namabln[intval(date("m", $tgl_data))];
	$tahun = date("Y", $tgl_data);
	//$hari = date("w", $tgl);
	//return $a_hari[date("w", $tgl_data)].", ".$tanggal." ".$bulan." ".$tahun;
	return $tanggal." ".$bulan." ".$tahun;
}

include "../Classes/PHPExcel.php";

$excelku = new PHPExcel();
$excelku->getDefaultStyle()->getFont()->setName('Times New Roman');
$excelku->getDefaultStyle()->getFont()->setSize(10);
$excelku->getActiveSheet()->setShowGridlines(false);
$excelku->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth( 2);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth( 2);

$excelku->getActiveSheet()->getColumnDimension('H')->setWidth( 2);
$excelku->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('J')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('K')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('L')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('M')->setWidth(18);
$excelku->getActiveSheet()->getColumnDimension('N')->setWidth( 2);

$excelku->getActiveSheet()->getColumnDimension('O')->setWidth( 2);
$excelku->getActiveSheet()->getColumnDimension('P')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('Q')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('R')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('S')->setWidth( 5);
$excelku->getActiveSheet()->getColumnDimension('T')->setWidth(18);
$excelku->getActiveSheet()->getColumnDimension('U')->setWidth( 2);

$SI = $excelku->setActiveSheetIndex(0);

$styleArray = array(
	'borders' => array(
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			//'color' => array('argb' => 'FFFF0000'),
		),
	),
);

$outline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_DOTTED,
			//'color' => array('argb' => 'FFFF0000'),
		),
	),
);

$baris  = 1; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$i      = 0;

$query = "select * from t_gjhrn";
$rs = $conn->Execute($query);

while (!$rs->EOF) {
	
	$a[ 0][$i] = $rs->fields["nama"]; //
	$a[ 1][$i] = $rs->fields["nip"]; //
	$a[ 2][$i] = $rs->fields["bagian"]; //
	$a[ 3][$i] = "HARIAN";
	$a[ 4][$i] = tgl_indo_header($rs->fields["start"])." - ".tgl_indo_header($rs->fields["end"]); //tgl_indo_header($rs->fields["end"]); //date("F - Y", strtotime($rs->fields["end"]));
	$a[ 5][$i] = $rs->fields["upah"]; //$rs->fields["gp"];
	//$a[ 6][$i] = $rs->fields["t_jbtn"];
	$a[ 7][$i] = $rs->fields["premi_hadir"]; //
	$a[ 8][$i] = $rs->fields["premi_malam"]; //
	$a[ 9][$i] = $rs->fields["pot_absen"]; //
	//$a[10][$i] = $rs->fields["p_aspen"];
	//$a[11][$i] = $rs->fields["p_bpjs"];
	//$a[12][$i] = $rs->fields["p_absen"] + $rs->fields["p_aspen"] + $rs->fields["p_bpjs"];
	$a[13][$i] = $rs->fields["total"];
	
	$mnama_file = "KTR HRN ".tgl_indo_header($rs->fields["end"]);
	
	$i++;
	if ($i % 3 == 0) {

		$mawal_kotak = $baris;
		$baris++; // $baris = 2

		// $baris = 2
		$excelku->getActiveSheet()->mergeCells('b'.$baris.':f'.$baris); $excelku->getActiveSheet()->mergeCells('i'.$baris.':m'.$baris); $excelku->getActiveSheet()->mergeCells('p'.$baris.':t'.$baris);
		$excelku->getActiveSheet()->getStyle("B".$baris)->getFont()->setUnderline(true); $excelku->getActiveSheet()->getStyle("B".$baris)->getFont()->setBold(true); $SI->setCellValue("B".$baris, "PT. AMBICO"); $excelku->getActiveSheet()->getStyle('B'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle("I".$baris)->getFont()->setUnderline(true); $excelku->getActiveSheet()->getStyle("I".$baris)->getFont()->setBold(true); $SI->setCellValue("I".$baris, "PT. AMBICO"); $excelku->getActiveSheet()->getStyle('I'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle("P".$baris)->getFont()->setUnderline(true); $excelku->getActiveSheet()->getStyle("P".$baris)->getFont()->setBold(true); $SI->setCellValue("P".$baris, "PT. AMBICO"); $excelku->getActiveSheet()->getStyle('P'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 3
		$SI->setCellValue("B".$baris, "NAMA"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[0][$i-3]);
		$SI->setCellValue("I".$baris, "NAMA"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[0][$i-2]);
		$SI->setCellValue("P".$baris, "NAMA"); $SI->setCellValue("Q".$baris, ":"); $SI->setCellValue("R".$baris, $a[0][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('Q'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 4
		$SI->setCellValue("B".$baris, "NIP"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[1][$i-3]);
		$SI->setCellValue("I".$baris, "NIP"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[1][$i-2]);
		$SI->setCellValue("P".$baris, "NIP"); $SI->setCellValue("Q".$baris, ":"); $SI->setCellValue("R".$baris, $a[1][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('Q'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 5
		$SI->setCellValue("B".$baris, "BAGIAN"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[2][$i-3]);
		$SI->setCellValue("I".$baris, "BAGIAN"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[2][$i-2]);
		$SI->setCellValue("P".$baris, "BAGIAN"); $SI->setCellValue("Q".$baris, ":"); $SI->setCellValue("R".$baris, $a[2][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('Q'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 6
		$SI->setCellValue("B".$baris, "STATUS"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[3][$i-3]);
		$SI->setCellValue("I".$baris, "STATUS"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[3][$i-2]);
		$SI->setCellValue("P".$baris, "STATUS"); $SI->setCellValue("Q".$baris, ":"); $SI->setCellValue("R".$baris, $a[3][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('Q'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 7
		$SI->setCellValue("B".$baris, "PERIODE"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[4][$i-3]);
		$SI->setCellValue("I".$baris, "PERIODE"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[4][$i-2]);
		$SI->setCellValue("P".$baris, "PERIODE"); $SI->setCellValue("Q".$baris, ":"); $SI->setCellValue("R".$baris, $a[4][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('Q'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//garis
		$excelku->getActiveSheet()->getStyle("b".$baris.":f".$baris)->applyFromArray($styleArray);
		$excelku->getActiveSheet()->getStyle("i".$baris.":m".$baris)->applyFromArray($styleArray);
		$excelku->getActiveSheet()->getStyle("p".$baris.":t".$baris)->applyFromArray($styleArray);
		
		$baris++; // $baris = 8
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "UPAH"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[5][$i-3]);
		$SI->setCellValue("I".$baris, "UPAH"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[5][$i-2]);
		$SI->setCellValue("P".$baris, "UPAH"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[5][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		/*
		$baris++; // $baris = 9
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "TUNJANGAN"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[6][$i-3]);
		$SI->setCellValue("I".$baris, "TUNJANGAN"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[6][$i-2]);
		$SI->setCellValue("P".$baris, "TUNJANGAN"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[6][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		$baris++; // $baris = 10
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "PREMI HADIR"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[7][$i-3]);
		$SI->setCellValue("I".$baris, "PREMI HADIR"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[7][$i-2]);
		$SI->setCellValue("P".$baris, "PREMI HADIR"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[7][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 11
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "PREMI MALAM"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[8][$i-3]);
		$SI->setCellValue("I".$baris, "PREMI MALAM"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[8][$i-2]);
		$SI->setCellValue("P".$baris, "PREMI MALAM"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[8][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 12
		
		$baris++; // $baris = 13
		$excelku->getActiveSheet()->mergeCells('b'.$baris.':d'.$baris); $excelku->getActiveSheet()->mergeCells('i'.$baris.':k'.$baris); $excelku->getActiveSheet()->mergeCells('p'.$baris.':r'.$baris);
		$SI->setCellValue("B".$baris, "POTONGAN"); $excelku->getActiveSheet()->getStyle('B'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$SI->setCellValue("I".$baris, "POTONGAN"); $excelku->getActiveSheet()->getStyle('I'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$SI->setCellValue("P".$baris, "POTONGAN"); $excelku->getActiveSheet()->getStyle('P'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//$baris++; // $baris = 14
		
		$baris++; // $baris = 14
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "ABSENSI"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[9][$i-3]);
		$SI->setCellValue("I".$baris, "ABSENSI"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[9][$i-2]);
		$SI->setCellValue("P".$baris, "ABSENSI"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[9][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		/*
		$baris++; // $baris = 15
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "ASTEK"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[10][$i-3]);
		$SI->setCellValue("I".$baris, "ASTEK"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[10][$i-2]);
		$SI->setCellValue("P".$baris, "ASTEK"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[10][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 16
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "BPJS"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[11][$i-3]);
		$SI->setCellValue("I".$baris, "BPJS"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[11][$i-2]);
		$SI->setCellValue("P".$baris, "BPJS"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[11][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		//garis
		$excelku->getActiveSheet()->getStyle("b".$baris.":f".$baris)->applyFromArray($styleArray);
		$excelku->getActiveSheet()->getStyle("i".$baris.":m".$baris)->applyFromArray($styleArray);
		$excelku->getActiveSheet()->getStyle("p".$baris.":t".$baris)->applyFromArray($styleArray);
		
		/*
		$baris++; // $baris = 17
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "JML POTONGAN"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[12][$i-3]);
		$SI->setCellValue("I".$baris, "JML POTONGAN"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[12][$i-2]);
		$SI->setCellValue("P".$baris, "JML POTONGAN"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[12][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		$baris++; // $baris = 18
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('t'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "JML TERIMA"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[13][$i-3]);
		$SI->setCellValue("I".$baris, "JML TERIMA"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[13][$i-2]);
		$SI->setCellValue("P".$baris, "JML TERIMA"); $SI->setCellValue("s".$baris, ":"); $SI->setCellValue("t".$baris, $a[13][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('s'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 19
		
		$makhir_kotak = $baris;
		
		$excelku->getActiveSheet()->getStyle("a".$mawal_kotak.":g".$makhir_kotak)->applyFromArray($outline);
		$excelku->getActiveSheet()->getStyle("h".$mawal_kotak.":n".$makhir_kotak)->applyFromArray($outline);
		$excelku->getActiveSheet()->getStyle("o".$mawal_kotak.":u".$makhir_kotak)->applyFromArray($outline);
		
	}
	$rs->MoveNext();
	
}
	if ($i % 3 == 1) {
		
		$baris++;
		$mawal_kotak = $baris;
		
		$baris++;

		// $baris = 2
		$excelku->getActiveSheet()->mergeCells('b'.$baris.':f'.$baris);
		$excelku->getActiveSheet()->getStyle("B".$baris)->getFont()->setUnderline(true); $excelku->getActiveSheet()->getStyle("B".$baris)->getFont()->setBold(true); $SI->setCellValue("B".$baris, "PT. AMBICO"); $excelku->getActiveSheet()->getStyle('B'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 3
		$SI->setCellValue("B".$baris, "NAMA"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[0][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 4
		$SI->setCellValue("B".$baris, "NIP"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[1][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 5
		$SI->setCellValue("B".$baris, "BAGIAN"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[2][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 6
		$SI->setCellValue("B".$baris, "STATUS"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[3][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 7
		$SI->setCellValue("B".$baris, "PERIODE"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[4][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//garis
		$excelku->getActiveSheet()->getStyle("b".$baris.":f".$baris)->applyFromArray($styleArray);
		
		$baris++; // $baris = 8
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "GAJI"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[5][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		/*
		$baris++; // $baris = 9
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "TUNJANGAN"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[6][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		$baris++; // $baris = 10
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "PREMI HADIR"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[7][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 11
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "PREMI MALAM"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[8][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 12
		
		$baris++; // $baris = 13
		$excelku->getActiveSheet()->mergeCells('b'.$baris.':d'.$baris);
		$SI->setCellValue("B".$baris, "POTONGAN"); $excelku->getActiveSheet()->getStyle('B'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//$baris++; // $baris = 14
		
		$baris++; // $baris = 14
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "ABSENSI"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[9][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		/*
		$baris++; // $baris = 15
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "ASTEK"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[10][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 16
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "BPJS"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[11][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//garis
		$excelku->getActiveSheet()->getStyle("b".$baris.":f".$baris)->applyFromArray($styleArray);
		
		$baris++; // $baris = 17
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "JML POTONGAN"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[12][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		$baris++; // $baris = 18
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "JML TERIMA"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[13][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 19
		
		$makhir_kotak = $baris;
		
		$excelku->getActiveSheet()->getStyle("a".$mawal_kotak.":g".$makhir_kotak)->applyFromArray($outline);
		
	}
	
	if ($i % 3 == 2) {
		
		$baris++;
		$mawal_kotak = $baris;
		
		$baris++;
		
		// $baris = 2
		$excelku->getActiveSheet()->mergeCells('b'.$baris.':f'.$baris); $excelku->getActiveSheet()->mergeCells('i'.$baris.':m'.$baris);
		$excelku->getActiveSheet()->getStyle("B".$baris)->getFont()->setUnderline(true); $excelku->getActiveSheet()->getStyle("B".$baris)->getFont()->setBold(true); $SI->setCellValue("B".$baris, "PT. AMBICO"); $excelku->getActiveSheet()->getStyle('B'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle("I".$baris)->getFont()->setUnderline(true); $excelku->getActiveSheet()->getStyle("I".$baris)->getFont()->setBold(true); $SI->setCellValue("I".$baris, "PT. AMBICO"); $excelku->getActiveSheet()->getStyle('I'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 3
		$SI->setCellValue("B".$baris, "NAMA"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[0][$i-2]);
		$SI->setCellValue("I".$baris, "NAMA"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[0][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 4
		$SI->setCellValue("B".$baris, "NIP"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[1][$i-2]);
		$SI->setCellValue("I".$baris, "NIP"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[1][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$baris++; // $baris = 5
		$SI->setCellValue("B".$baris, "BAGIAN"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[2][$i-2]);
		$SI->setCellValue("I".$baris, "BAGIAN"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[2][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 6
		$SI->setCellValue("B".$baris, "STATUS"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[3][$i-2]);
		$SI->setCellValue("I".$baris, "STATUS"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[3][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 7
		$SI->setCellValue("B".$baris, "PERIODE"); $SI->setCellValue("C".$baris, ":"); $SI->setCellValue("D".$baris, $a[4][$i-2]);
		$SI->setCellValue("I".$baris, "PERIODE"); $SI->setCellValue("J".$baris, ":"); $SI->setCellValue("K".$baris, $a[4][$i-1]);
		$excelku->getActiveSheet()->getStyle('C'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('J'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//garis
		$excelku->getActiveSheet()->getStyle("b".$baris.":f".$baris)->applyFromArray($styleArray);
		$excelku->getActiveSheet()->getStyle("i".$baris.":m".$baris)->applyFromArray($styleArray);
		
		$baris++; // $baris = 8
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "GAJI"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[5][$i-2]);
		$SI->setCellValue("I".$baris, "GAJI"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[5][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		/*
		$baris++; // $baris = 9
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "TUNJANGAN"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[6][$i-2]);
		$SI->setCellValue("I".$baris, "TUNJANGAN"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[6][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		$baris++; // $baris = 10
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "PREMI HADIR"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[7][$i-2]);
		$SI->setCellValue("I".$baris, "PREMI HADIR"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[7][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 11
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "PREMI MALAM"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[8][$i-2]);
		$SI->setCellValue("I".$baris, "PREMI MALAM"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[8][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 12
		
		$baris++; // $baris = 13
		$excelku->getActiveSheet()->mergeCells('b'.$baris.':d'.$baris); $excelku->getActiveSheet()->mergeCells('i'.$baris.':k'.$baris);
		$SI->setCellValue("B".$baris, "POTONGAN"); $excelku->getActiveSheet()->getStyle('B'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$SI->setCellValue("I".$baris, "POTONGAN"); $excelku->getActiveSheet()->getStyle('I'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//$baris++; // $baris = 14
		
		$baris++; // $baris = 14
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "ABSENSI"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[9][$i-2]);
		$SI->setCellValue("I".$baris, "ABSENSI"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[9][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		/*
		$baris++; // $baris = 15
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "ASTEK"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[10][$i-2]);
		$SI->setCellValue("I".$baris, "ASTEK"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[10][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 16
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "BPJS"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[11][$i-2]);
		$SI->setCellValue("I".$baris, "BPJS"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[11][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//garis
		$excelku->getActiveSheet()->getStyle("b".$baris.":f".$baris)->applyFromArray($styleArray);
		$excelku->getActiveSheet()->getStyle("i".$baris.":m".$baris)->applyFromArray($styleArray);
		
		$baris++; // $baris = 17
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "JML POTONGAN"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[12][$i-2]);
		$SI->setCellValue("I".$baris, "JML POTONGAN"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[12][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		*/
		
		$baris++; // $baris = 18
		$excelku->getActiveSheet()->getStyle('f'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$excelku->getActiveSheet()->getStyle('m'.$baris)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
		$SI->setCellValue("B".$baris, "JML TERIMA"); $SI->setCellValue("e".$baris, ":"); $SI->setCellValue("f".$baris, $a[13][$i-2]);
		$SI->setCellValue("I".$baris, "JML TERIMA"); $SI->setCellValue("l".$baris, ":"); $SI->setCellValue("m".$baris, $a[13][$i-1]);
		$excelku->getActiveSheet()->getStyle('e'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excelku->getActiveSheet()->getStyle('l'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$baris++; // $baris = 19
		
		$makhir_kotak = $baris;
		
		$excelku->getActiveSheet()->getStyle("a".$mawal_kotak.":g".$makhir_kotak)->applyFromArray($outline);
		$excelku->getActiveSheet()->getStyle("h".$mawal_kotak.":n".$makhir_kotak)->applyFromArray($outline);
		
	}

$rs->Close();

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Slip Gaji Harian');

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$mnama_file.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
$objWriter->save('php://output');
exit;

?>