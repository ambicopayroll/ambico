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

function f_3kolom($p1, $p2, $p3) {
echo "
	<tr>
		<td>".$p1."</td>
		<td>:</td>
		<td>".$p2[$p3-3]."</td>
		
		<td>&nbsp;</td>
		
		<td>".$p1."</td>
		<td>:</td>
		<td>".$p2[$p3-2]."</td>
		
		<td>&nbsp;</td>
		
		<td>".$p1."</td>
		<td>:</td>
		<td>".$p2[$p3-1]."</td>
	</tr>";
}

function f_2kolom($p1, $p2, $p3) {
echo "
	<tr>
		<td>".$p1."</td>
		<td>:</td>
		<td>".$p2[$p3-2]."</td>
		
		<td>&nbsp;</td>
		
		<td>".$p1."</td>
		<td>:</td>
		<td>".$p2[$p3-1]."</td>
		
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>";
}

function f_1kolom($p1, $p2, $p3) {
echo "
	<tr>
		<td>".$p1."</td>
		<td>:</td>
		<td>".$p2[$p3-1]."</td>
		
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>";
}

function f_kosong() {
echo "
	<tr>
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
	</tr>";
}

function f_3kolom_judul($p1) {
echo "
	<tr>
		<td align='center' colspan='3'>".$p1."</td>
		
		<td>&nbsp;</td>
		
		<td align='center' colspan='3'>".$p1."</td>
		
		<td>&nbsp;</td>
		
		<td align='center' colspan='3'>".$p1."</td>
	</tr>";
}

function f_2kolom_judul($p1) {
echo "
	<tr>
		<td align='center' colspan='3'>".$p1."</td>
		
		<td>&nbsp;</td>
		
		<td align='center' colspan='3'>".$p1."</td>
		
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>";
}

function f_1kolom_judul($p1) {
echo "
	<tr>
		<td align='center' colspan='3'>".$p1."</td>
		
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>";
}

echo "<table border='0'>";

$query = "
	select
		*
	from
		t_gjbln
	";
$rs = $conn->Execute($query);

$mno = 0;
$i = 0;

while (!$rs->EOF) {
	
	$a[ 0][$i] = $rs->fields["nama"];
	$a[ 1][$i] = $rs->fields["nip"];
	$a[ 2][$i] = $rs->fields["bagian"];
	$a[ 3][$i] = "Bulanan";
	$a[ 4][$i] = date("F - Y", strtotime($rs->fields["end"]));
	$a[ 5][$i] = number_format($rs->fields["gp"]);
	$a[ 6][$i] = number_format($rs->fields["t_jbtn"]);
	$a[ 7][$i] = number_format($rs->fields["t_hadir"]);
	$a[ 8][$i] = number_format($rs->fields["t_malam"]);
	$a[ 9][$i] = number_format($rs->fields["p_absen"]);
	$a[10][$i] = number_format($rs->fields["p_aspen"]);
	$a[11][$i] = number_format($rs->fields["p_bpjs"]);
	$a[12][$i] = number_format($rs->fields["p_absen"] + $rs->fields["p_aspen"] + $rs->fields["p_bpjs"]);
	$a[13][$i] = number_format($rs->fields["j_netto"]);
	
	
	$mno++;
	$i++;
	if ($i % 3 == 0) {
		
		echo f_kosong();
		echo f_3kolom_judul("<b>PT. AMBICO</b>");
		echo f_3kolom("Nama"         , $a[ 0], $i);
		echo f_3kolom("NIP"          , $a[ 1], $i);
		echo f_3kolom("Bagian"       , $a[ 2], $i);
		echo f_3kolom("Status"       , $a[ 3], $i);
		echo f_3kolom("Periode"      , $a[ 4], $i);
		echo f_3kolom("Gaji"         , $a[ 5], $i);
		echo f_3kolom("Tunjangan"    , $a[ 6], $i);
		echo f_3kolom("Premi Hadir"  , $a[ 7], $i);
		echo f_3kolom("Premi Malam"  , $a[ 8], $i);
		echo f_kosong();
		echo f_3kolom_judul("<b>Potongan</b>");
		echo f_3kolom("Absensi"      , $a[ 9], $i);
		echo f_3kolom("ASTEK"        , $a[10], $i);
		echo f_3kolom("BPJS"         , $a[11], $i);
		echo f_3kolom("Jml. Potongan", $a[12], $i);
		echo f_3kolom("Jml. Terima"  , $a[13], $i);
		echo f_kosong();
		
	}
	$rs->MoveNext();
	
}
	if ($i % 3 == 1) {
		
		echo f_kosong();
		echo f_1kolom_judul("<b>PT. AMBICO</b>");
		echo f_1kolom("Nama"         , $a[ 0], $i);
		echo f_1kolom("NIP"          , $a[ 1], $i);
		echo f_1kolom("Bagian"       , $a[ 2], $i);
		echo f_1kolom("Status"       , $a[ 3], $i);
		echo f_1kolom("Periode"      , $a[ 4], $i);
		echo f_1kolom("Gaji"         , $a[ 5], $i);
		echo f_1kolom("Tunjangan"    , $a[ 6], $i);
		echo f_1kolom("Premi Hadir"  , $a[ 7], $i);
		echo f_1kolom("Premi Malam"  , $a[ 8], $i);
		echo f_kosong();
		echo f_1kolom_judul("<b>Potongan<b>");
		echo f_1kolom("Absensi"      , $a[ 9], $i);
		echo f_1kolom("ASTEK"        , $a[10], $i);
		echo f_1kolom("BPJS"         , $a[11], $i);
		echo f_1kolom("Jml. Potongan", $a[12], $i);
		echo f_1kolom("Jml. Terima"  , $a[13], $i);
		
	}
	
	if ($i % 3 == 2) {
		
		echo f_kosong();
		echo f_2kolom_judul("<b>PT. AMBICO</b>");
		echo f_2kolom("Nama"         , $a[ 0], $i);
		echo f_2kolom("NIP"          , $a[ 1], $i);
		echo f_2kolom("Bagian"       , $a[ 2], $i);
		echo f_2kolom("Status"       , $a[ 3], $i);
		echo f_2kolom("Periode"      , $a[ 4], $i);
		echo f_2kolom("Gaji"         , $a[ 5], $i);
		echo f_2kolom("Tunjangan"    , $a[ 6], $i);
		echo f_2kolom("Premi Hadir"  , $a[ 7], $i);
		echo f_2kolom("Premi Malam"  , $a[ 8], $i);
		echo f_kosong();
		echo f_2kolom_judul("<b>Potongan</b>");
		echo f_2kolom("Absensi"      , $a[ 9], $i);
		echo f_2kolom("ASTEK"        , $a[10], $i);
		echo f_2kolom("BPJS"         , $a[11], $i);
		echo f_2kolom("Jml. Potongan", $a[12], $i);
		echo f_2kolom("Jml. Terima"  , $a[13], $i);
		
	}

$rs->Close();
echo "</table>";
?>