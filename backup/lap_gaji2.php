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
$pdf->SetTitle('Bulanan');
$pdf->SetSubject('Bulanan');
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
$pdf->AddPage("L", "A4");
//$pdf->AddPage();
$pdf->SetFont('times', '', 10);

$html  = '<table border="0" width="300">';
$html .= '<tr><td>DAFTAR GAJI BULANAN</td></tr>';
$html .= '<tr><td>PT AMBICO - CARAT</td></tr>';
$html .= '<tr><td>Periode '.$_POST["start"].' s.d. '.$_POST["end"].'</td></tr>';
$html .= '</table>';

$html .= '<table border="1" width="100%">';
$html .= '
	<tr>
	  <td>No.</td>
	  <td>Bagian</td>
	  <td>Divisi</td>
	  <td>NIP</td>
	  <td>Nama</td>
	  <td>Gaji</td>
	  <td>Tunj. Jabatan</td>
	  <td>Pot. Absen</td>
	  <td>Tunj. Malam</td>
	  <td>Lembur</td>
	  <td>Premi Hadir</td>
	  <td>Uang Makan</td>
	  <td>Jumlah Bruto</td>
	  <td>Pot. Astek + Pensiun</td>
	  <td>Pot. BPJS</td>
	  <td>Total Terima</td>
	</tr>
	';

$mno = 1;

$msql = "
	select
		f.lapgroup_id
		, f.lapgroup_nama
		, f.lapgroup_index
		, d.pembagian2_id
		, d.pembagian2_nama
		, e.lapsubgroup_index
		, c.pegawai_nama
		, a.*
		, b.*
	from
		t_rumus2_peg a
		left join t_rumus2 b on a.rumus2_id = b.rumus2_id
		left join pegawai c on a.pegawai_id = c.pegawai_id
		left join pembagian2 d on c.pembagian2_id = d.pembagian2_id
		left join t_lapsubgroup e on c.pembagian2_id = e.pembagian2_id
		left join t_lapgroup f on e.lapgroup_id = f.lapgroup_id
	order by
		f.lapgroup_index,
		e.lapsubgroup_index
	";
$rs = $conn->Execute($msql);

while (!$rs->EOF) {
	$mlapgroup_id = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	$mtotal1 = 0;
	$html .= '
		<tr>
		  <td>&nbsp;</td>
		  <td>'.$mlapgroup_nama.'</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		';
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		$mtotal2 = 0;
		$html .= '
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>'.$mpembagian2_nama.'</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			</tr>
			';
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			$mpegawai_id = $rs->fields["pegawai_id"];
			//$mgp = $rs->fields["gp"];
			//$mtj = $rs->fields["tj"];
			$mpremi_hadir = $rs->fields["premi_hadir"];
			$mpremi_malam = 0;
			$mlp = 0;
			$mtunj_forklift = 0;
			$mpot_absen = 0;
			$mlembur = 0;

			$msql = "
				select * from v_jdw_krj_def
				where
					pegawai_id = ".$mpegawai_id." 
					and tgl between '".$_POST['start']."' and '".$_POST['end']."'
				order by
					tgl
				"; //echo $msql; exit;
			$rs2 = $conn->Execute($msql);
			$mbagian = $rs2->fields["pembagian2_nama"];
			$mpegawai_nama = $rs2->fields["pegawai_nama"];
			$mpegawai_nip = $rs2->fields["pegawai_nip"];
			//$mjml_absen = 0;
			
			while (!$rs2->EOF) {

				// check data valid
				$data_valid = false;
				if (!is_null($rs2->fields["scan_masuk"]) and !is_null($rs2->fields["scan_keluar"])) {
					$data_valid = true;
				}
				
				// hitung premi hadir & pot. absen
				// echo substr($rs2->fields["jk_kd"], -1); exit;
				if (!$data_valid and substr($rs2->fields["jk_kd"], -1) != "L") {
					$mpremi_hadir = 0;
					$msql = "select f_cari_pengecualian(".$mpegawai_id.", '".$rs2->fields["tgl"]."') as ada";
					$rs3 = $conn->Execute($msql); // echo $msql; exit;
					if ($rs3->fields["ada"]) {
						
					}
					else {
						if ($rs2->fields["hk_def"] == 5) {
							$mpot_absen += $rs->fields["gp"] / 25;
						}
						else {
							$mpot_absen += $rs->fields["gp"] / 30;
						}
					}
				}
				
				// hitung premi malam
				if ($data_valid and substr($rs2->fields["jk_kd"], 0, 2) == "S3" and ($mbagian != "KEAMANAN" or $mbagian != "PPIC")) {
					$mpremi_malam += $rs->fields["premi_malam"];
				}

				// hitung lp
				if ($data_valid and ($mbagian == "KEAMANAN" or $mbagian == "PPIC")) {
					$mlp += $rs->fields["lp"];
				}
				
				// hitung tunj. forklift
				if ($data_valid and $mbagian == "GUDANG") {
					$mtunj_forklift += 1000;
				}
				
				$rs2->MoveNext();
				
			}

			if ($mbagian == "KEAMANAN") {
				$mpremi_hadir = 0;
			}
			
			$mpot_aspen = $rs->fields["gp"] * $rs->fields["pot_aspen"];
			$mpot_bpjs = $rs->fields["pot_bpjs"] < 1 ? $rs->fields["gp"] * $rs->fields["pot_bpjs"] : $rs->fields["pot_bpjs"];
			$mbruto = $rs->fields["gp"] + $rs->fields["tj"] - $mpot_absen + $mpremi_malam + $mlembur + $mpremi_hadir + $mlp;
			$mnetto = $mbruto - $mpot_aspen - $mpot_bpjs;
			$html .= 
			'<tr>
				<td align="right">'.$mno.'&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center">'.$mpegawai_nip.'</td>
				<td>&nbsp;'.$mpegawai_nama.'</td>
				<td align="right">'.number_format($rs->fields["gp"]).'</td>
				<td align="right">'.number_format($rs->fields["tj"]).'</td>
				<td align="right">'.number_format($mpot_absen).'</td>
				<td align="right">'.number_format($mpremi_malam).'</td>
				<td align="right">'.number_format($mlembur).'</td>
				<td align="right">'.number_format($mpremi_hadir).'</td>
				<td align="right">'.number_format($mlp).'</td>
				<td align="right">'.number_format($mbruto).'</td>
				<td align="right">'.number_format($mpot_aspen).'</td>
				<td align="right">'.number_format($mpot_bpjs).'</td>
				<td align="right">'.number_format($mnetto).'</td>
			</tr>';
			$mtotal2 += $rs->fields["gp"];
			$mno++;
			$rs->MoveNext();
		}
		$html .= 
		'<tr>
			<td align="right">'."".'&nbsp;</td>
			<td align="center">'."".'&nbsp;</td>
			<td colspan="3">Sub Total</td>
			<td align="right">'.number_format($mtotal2).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
			<td align="right">'.number_format(0).'</td>
		</tr>';
		$mtotal1 += $mtotal2;
	}
	$html .= 
	'<tr>
		<td align="right">'."".'&nbsp;</td>
		<td colspan="4">Total</td>
		<td align="right">'.number_format($mtotal1).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
		<td align="right">'.number_format(0).'</td>
	</tr>';
	$mgrand_total += $mtotal1;
}
$html .= 
'<tr>
	<td colspan="5">Grand Total</td>
	<td align="right">'.number_format($mgrand_total).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
	<td align="right">'.number_format(0).'</td>
</tr>';

$html .= '</table>';
//$html .= $msql;
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Bulanan.pdf', 'I');
//echo $html;

$rs->Close();
//$conn->Close();

// header("location: ./payroll_.php?ok=1");

?>