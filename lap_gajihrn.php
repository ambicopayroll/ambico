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

$msql = "delete from t_gjhrn";
$conn->Execute($msql);

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
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		$mtotal2 = 0;
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
				$data_valid = false;
				if (!is_null($rs->fields["scan_masuk"]) and !is_null($rs->fields["scan_keluar"])) {
					$data_valid = true;
					// upah
					$mupah += $rs->fields["upah"];
					// premi hadir
					$mpremi_hadir = $rs->fields["premi_hadir"];
				}
				
				// hitung premi malam
				if (!$data_valid and substr($rs->fields["jk_kd"], 0, 2) == "S3") {
					$mpremi_malam += $rs->fields["premi_malam"];
				}
				
				// hitung premi hadir & pot. absen
				if (!$data_valid and substr($rs->fields["jk_kd"], -1) != "L") {
					$msql = "select f_cari_pengecualian(".$mpegawai_id.", '".$rs->fields["tgl"]."') as ada";
					$rs3 = $conn->Execute($msql); // echo $msql; exit;
					if ($rs3->fields["ada"]) {
					}
					else {
						$mpremi_hadir = 0;
						$mpot_absen += $rs->fields["pot_absen"];
					}
				}
				
				$rs->MoveNext();
			}
			$mtotal = $mupah + $mpremi_malam + $mpremi_hadir - $mpot_absen;
			$msql = "
				insert into t_gjhrn values (null, 
				'".$mlapgroup_nama."'
				, '".$mpembagian2_nama."'
				, '".$mpegawai_nama."'
				, '".$mpegawai_nip."'
				, ".$mupah."
				, ".$mpremi_malam."
				, ".$mpremi_hadir."
				, ".$mpot_absen."
				, ".$mtotal."
				)
				"; //echo $msql; exit;
			$conn->Execute($msql);
			$mtotal2 += $mupah;
			$mno++;			
		}
		$mtotal1 += $mtotal2;
	}
	$mgrand_total += $mtotal1;
}
$rs->Close();
// header("location: ./payroll_.php?ok=1");
header("location: r_lapgjhrnsmry.php");
?>