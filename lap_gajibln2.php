<?php

function f_data_valid($jam_masuk, $jam_keluar) {
	if (!is_null($jam_masuk) and !is_null($jam_keluar)) { // jam_masuk ada dan jam_keluar ada
		return 0;
	}
	if (!is_null($jam_masuk) and is_null($jam_keluar)) { // jam_masuk ada dan jam_keluar tidak ada
		return 1;
	}
	if (is_null($jam_masuk) and !is_null($jam_keluar)) { // jam_masuk tidak ada dan jam_keluar ada
		return 2;
	}
	if (is_null($jam_masuk) and is_null($jam_keluar)) { // jam_masuk tidak ada dan jam_keluar tidak ada
		return 3;
	}
}

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

$msql = "delete from t_gjbln";
$conn->Execute($msql);

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

$mgrand_total = 0;

while (!$rs->EOF) {
	$mlapgroup_id = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	$mtotal1 = 0;
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		$mtotal2 = 0;
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			
			// prepare data
			$mgp       = $rs->fields["gp"]; // gaji pokok
			$mt_jbtn   = $rs->fields["tj"]; // tunjangan jabatan
			$mp_absen  = 0; // potongan absen
			$mt_malam  = $rs->fields["premi_malam"]; // tunjangan malam
			$mt_lembur = 0; // tunjangan lembur
			$mt_hadir  = $rs->fields["premi_hadir"]; // tunjangan hadir
			$mt_um     = 0; // tunjangan uang makan
			$mt_fork   = 0; // tunjangan forklift
			
			$msql = "
				select * from v_jdw_krj_def
				where
					pegawai_id = ".$mpegawai_id."
					and tgl between '".$_POST['start']."' and '".$_POST['end']."'
				order by
					tgl
				"; //echo $msql; exit;
			$rs2 = $conn->Execute($msql);
			
			$mbagian       = $rs2->fields["pembagian2_nama"];
			$mpegawai_nama = $rs2->fields["pegawai_nama"];
			$mpegawai_nip  = $rs2->fields["pegawai_nip"];
			$mpegawai_pin  = $rs2->fields["pegawai_pin"];
			
			while (!$rs2->EOF) {

				// check data valid (jam masuk ada dan jam keluar ada)
				$mdata_valid = f_data_valid($rs2->fields["scan_masuk"], $rs2->fields["scan_keluar"]);
				if ($mdata_valid == 0) {
					// data valid
					
				}
				else {
					// data tidak valid
				}
				
			}
		}
	}
}
?>