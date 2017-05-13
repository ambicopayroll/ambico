<?php
if ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	include "adodb5/adodb.inc.php";
	$conn = ADONewConnection('mysql');
	$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
}
else {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ambico');
$pdf->SetTitle('Upah');
$pdf->SetSubject('Upah');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('times', 'BI', 20);

// add a page
//$pdf->AddPage("L", "A4");
$pdf->AddPage();
$pdf->SetFont('times', '', 10);

$html  = '<table border="0" width="300">';
$html .= '<tr><td>DAFTAR UPAH HARIAN LEPAS</td></tr>';
$html .= '<tr><td>PT AMBICO - CARAT</td></tr>';
$html .= '<tr><td>Periode '.$_POST["start"].' s.d. '.$_POST["end"].'</td></tr>';
$html .= '</table>';

$html .= '<table border="1" width="100%">';
$html .= '
	<tr>
		<th rowspan="2" align="center"width="35">No.</th>
		<th rowspan="2" align="center" width="150">Nama / Bagian</th>
		<th rowspan="2" align="center" width="50">NP</th>
		<th rowspan="2" align="center">Total Upah</th>
		<th colspan="2" align="center">Premi</th>
		<th rowspan="2" align="center">Absen</th>
		<th rowspan="2" align="center">Jumlah Terima</th>
	</tr>';
$html .= '
	<tr>
		<th align="center">Malam</th>
		<th align="center">Hadir</th>
	</tr>';

$mno = 1;

/*$msql = "
	select * from
		v_jdw_krj_def a
		left join t_rumus_peg b on a.pegawai_id = b.pegawai_id
		left join t_rumus c on b.rumus_id = c.rumus_id
	where
		tgl between '".$_POST['start']."' and '".$_POST['end']."'
		and c.hk_gol = a.hk_def
		and a.pegawai_id not in (select pegawai_id from t_rumus2_peg)
	order by
		a.pegawai_id
		, tgl
	"; echo $msql; exit; */
$msql = "
	select
		e.lapgroup_id
		, e.lapgroup_nama
		, e.lapgroup_index
		, d.lapsubgroup_index
		, a.*
		, c.*
	from
		v_jdw_krj_def a
		left join t_rumus_peg b on a.pegawai_id = b.pegawai_id
		left join t_rumus c on b.rumus_id = c.rumus_id
		left join t_lapsubgroup d on a.pembagian2_id = d.pembagian2_id
		left join t_lapgroup e on d.lapgroup_id = e.lapgroup_id
	where
		tgl between '".$_POST['start']."' and '".$_POST['end']."'
		and c.hk_gol = a.hk_def
		and a.pegawai_id not in (select pegawai_id from t_rumus2_peg)
	order by
		e.lapgroup_index
		, d.lapsubgroup_index
		, a.pegawai_id
		, a.tgl
	"; //echo $msql; exit;
$rs = $conn->Execute($msql);
while (!$rs->EOF) {
	$mlapgroup_id = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	$mtotal1 = 0;
	$html .= '
		<tr>
			<td>&nbsp;</td>
			<td><b>'.$mlapgroup_nama.'</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>';
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		$mtotal2 = 0;
		$html .= '
			<tr>
				<td>&nbsp;</td>
				<td align="center"><b>'.$mpembagian2_nama.'</b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>';
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			$mpegawai_id = $rs->fields["pegawai_id"];
			$mpegawai_nama = $rs->fields["pegawai_nama"];
			$mpegawai_nip = $rs->fields["pegawai_nip"];
			$mupah = 0;
			$mpremi_malam = 0;
			$mpremi_hadir = 0;
			$mtidak_masuk = 0;
			$mpot_absen = 0;
			$mjml_premi_malam = 0;
			while ($mpegawai_id == $rs->fields["pegawai_id"] and !$rs->EOF) {
				
				// check data valid
				$data_valid = false; //echo "tgl. ".$rs->fields["tgl"].$rs->fields["scan_masuk"];
				if (!is_null($rs->fields["scan_masuk"]) and !is_null($rs->fields["scan_keluar"])) {
					$data_valid = true; //echo "valid";
					$mupah += $rs->fields["upah"];
				}
				
				// hitung premi hadir & pot. absen
				// echo substr($rs2->fields["jk_kd"], -1); exit;
				if (!$data_valid and substr($rs->fields["jk_kd"], -1) != "L") {
					$mpremi_hadir = 0;
					$msql = "select f_cari_pengecualian(".$mpegawai_id.", '".$rs->fields["tgl"]."') as ada";
					$rs3 = $conn->Execute($msql); // echo $msql; exit;
					if ($rs3->fields["ada"]) {
						
					}
					else {
						$mpot_absen += $rs->fields["pot_absen"];
					}
				}
				
				//$mupah += $rs->fields["upah"];
				if (substr($rs->fields["jk_kd"], 0, 2) == "S3") {
					//$mjml_premi_malam++;
					//if ($mjml_premi_malam <= 5) {
						$mpremi_malam += $rs->fields["premi_malam"];
					//}
				}
				$mpremi_hadir = $rs->fields["premi_hadir"];
				//if (is_null($rs->fields["gol_hk"])) {
				/*if (is_null($rs->fields["scan_masuk"]) or is_null($rs->fields["scan_keluar"])) {
					$mtidak_masuk = 1;
					$mpot_absen += $rs->fields["pot_absen"];
				}*/
				$rs->MoveNext();
			}
			$mtotal = $mupah + $mpremi_malam + $mpremi_hadir - $mpot_absen;
			$mtotal2 += $mupah;
			// echo $mupah." ".$mpremi_malam." ".($mtidak_masuk ? "0" : $mpremi_hadir)." ".$mtotal;
			$html .= '<tr><td align="right">'.$mno.'.&nbsp;</td><td>&nbsp;'.$mpegawai_nama.'</td><td align="center">'.$mpegawai_nip.'</td>'.'<td align="right">'.number_format($mupah).'</td>'.'<td align="right">'.number_format($mpremi_malam).'</td>'.'<td align="right">'.($mtidak_masuk ? "" : number_format($mpremi_hadir)).'</td>'.'<td align="right">'.number_format($mpot_absen).'</td>'.'<td align="right">'.number_format($mtotal).'</td></tr>';
			$mno++;			
		}
		$html .= '
			<tr>
				<td>&nbsp;</td>
				<td>Sub Total</td>
				<td>&nbsp;</td>
				<td align="right">'.number_format($mtotal2).'</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>';
		$mtotal1 += $mtotal2;
	}
	$html .= '
		<tr>
			<td>&nbsp;</td>
			<td>Total '.$mlapgroup_nama.'</td>
			<td>&nbsp;</td>
			<td align="right">'.number_format($mtotal1).'</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>';
	$mgrand_total += $mtotal1;
}
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>Total Seluruh Bagian</td>
		<td>&nbsp;</td>
		<td align="right">'.number_format($mgrand_total).'</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>';

$html .= '</table>';
//$html .= $msql;
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Upah.pdf', 'I');
//echo $html;

$rs->Close();
//$conn->Close();

// header("location: ./payroll_.php?ok=1");

?>