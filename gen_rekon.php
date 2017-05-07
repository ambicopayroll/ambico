<?php
if ($_SERVER["HTTP_HOST"] == "localhost" or $_SERVER["HTTP_HOST"] == "36.80.56.64") {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}
elseif ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	include "adodb5/adodb.inc.php";
	$conn = ADONewConnection('mysql');
	$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
}

if ($_POST["radio_proses"]) {
	$conn->Execute("CALL p_gen_rekon ('".$_POST["start"]."', '".$_POST["end"]."')");
}

header("location: ./r_rekonctb.php?cmd=search&so_pegawai_id=%3D&sv_pegawai_id=&so_tgl=BETWEEN&sv_tgl=".$_POST["start"]."&sv2_tgl=".$_POST["end"]."");
?>