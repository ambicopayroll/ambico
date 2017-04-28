<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function tgl_indo($tgl) {
	$a_namabln = array(
		1 => "Jan",
		"Feb",
		"Mar",
		"Apr",
		"Mei",
		"Jun",
		"Jul",
		"Ags",
		"Sep",
		"Okt",
		"Nov",
		"Des");
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
	return $a_hari[date("w", $tgl_data)].", ".$tanggal." ".$bulan." ".$tahun;
}
?>
