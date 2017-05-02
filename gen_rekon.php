<?php
include "conn.php";

mysql_connect($hostname_conn, $username_conn, $password_conn) or die ("Tidak bisa terkoneksi ke Database server");
mysql_select_db($database_conn) or die ("Database tidak ditemukan");

if ($_POST["radio_proses"]) {
	$msql = "call p_gen_rekon ('".$_POST["start"]."', '".$_POST["end"]."')"; //echo $msql; exit;
	mysql_query($msql);
}

header("location: ./r_rekonctb.php?cmd=search&so_pegawai_id=%3D&sv_pegawai_id=&so_tgl=BETWEEN&sv_tgl=".$_POST["start"]."&sv2_tgl=".$_POST["end"]."");
?>