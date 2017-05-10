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

function f_carikodepengecualian($mpegawai_id, $mtgl, $mconn) {
	$msql = "select f_carikodepengecualian(".$mpegawai_id.", '".$mtgl."') as r_kode";
	$rsf = $mconn->Execute($msql);
	if (!$rsf->EOF) {
		return $rsf->fields["r_kode"];
	}
}

function f_carilamakerja($p_pegawai_pin, $p_tgl) {
	
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
	"; //echo $msql; exit;
$rs = $conn->Execute($msql);

while (!$rs->EOF) {
	$mlapgroup_id = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			
			// prepare data
			$pegawai_id = $rs->fields["pegawai_id"];
			$gp         = $rs->fields["gp"]; // gaji pokok
			$t_jbtn     = $rs->fields["tj"]; // tunjangan jabatan
			$t_hadir    = $rs->fields["premi_hadir"]; // tunjangan hadir
			$t_malam    = $rs->fields["premi_malam"]; // tunjangan malam
			$t_um       = $rs->fields["lp"]; // tunjangan uang makan
			$t_fork     = $rs->fields["forklift"]; // tunjangan forklift
			$t_lembur   = $rs->fields["lembur"]; // tunjangan lembur
			$p_absen5   = $gp / 25; // potongan absen 5 hk
			$p_absen6   = $gp / 30; // potongan absen 6 hk
			$p_aspen    = $gp * $rs->fields["pot_aspen"]; // potongan astek & pensiun
			$p_bpjs     = ($rs->fields["pot_bpjs"] < 1 ? $gp * $rs->fields["pot_bpjs"] : $rs->fields["pot_bpjs"]); // potongan bpjs
			
			$msql = "
				select * from v_jdw_krj_def
				where
					pegawai_id = ".$pegawai_id."
					and tgl between '".$_POST['start']."' and '".$_POST['end']."'
				order by
					tgl
				"; //echo $msql; exit;
			$rs2 = $conn->Execute($msql);
			
			$bagian       = $rs2->fields["pembagian2_nama"];
			$pegawai_nama = $rs2->fields["pegawai_nama"];
			$pegawai_nip  = $rs2->fields["pegawai_nip"];
			$pegawai_pin  = $rs2->fields["pegawai_pin"];
			
			$mp_absen   = 0;
			$mt_malam   = 0;
			$mt_lembur  = 0;
			$mt_um      = 0;
			$mt_fork    = 0;
			$mabsen     = 0;
			$mterlambat = 0;			
			
			while (!$rs2->EOF) {
				
				$tgl    = $rs2->fields["tgl"];
				$hk_def = $rs2->fields["hk_def"];
				$jk_kd  = $rs2->fields["jk_kd"];

				// check data valid (jam masuk ada dan jam keluar ada)
				$mdata_valid = f_data_valid($rs2->fields["scan_masuk"], $rs2->fields["scan_keluar"]);
				if ($mdata_valid != 0) {
					// data tidak valid
					
					// cari di tabel pengecualian
					$kode_pengecualian = f_carikodepengecualian($pegawai_id, $tgl, $conn);
					if ($kode_pengecualian == null) {
						// tidak ada data pengecualian
						
						// check hari libur
						if (substr($jk_kd, -1) == "L") {
						}
						else {
							$mabsen = 1; // untuk acuan perhitungan tunjangan hadir
							$mp_absen += ($hk_def == 5 ? $p_absen5 : $p_absen6);
						}
					}
					else {
						// ada data pengecualian
						if ($kode_pengecualian == "TL") {
							$mterlambat = 1; // untuk acuan perhitungan tunjangan hadir
							$mdata_valid = 0;
						}
						if ($kode_pengecualian == "HD") {
							$lama_kerja = f_carilamakerja($pegawai_pin, $tgl);
							$mp_absen += ($hk_def == 5 ? $p_absen5 : $p_absen6) / 2;
						}
					}
				}
				
				if ($mdata_valid == 0) {
					// data valid
					
					// hitung tunjangan malam
					if (substr($jk_kd, 0, 2) == "S3") {
						$mt_malam += $t_malam;
					}
					
					// hitung tunjangan uang makan
					$mt_um += $t_um;
					
					// hitung tunjangan forklift
					$mt_fork += $t_fork;
					
				}
				
				$rs2->MoveNext(); // go to next record on data rekonsiliasi
			}
			
			if ($mabsen == 1 or $mterlambat == 1) $t_hadir = 0;
			$bruto = $gp + $t_jbtn - $mp_absen + $mt_malam + $mt_lembur + $t_hadir + $mt_um; //+ $mt_fork;
			$netto = $bruto - $p_aspen - $p_bpjs;
			
			$msql = "
				insert into t_gjbln values (null, 
				'".$mlapgroup_nama."'
				, '".$mpembagian2_nama."'
				, '".$pegawai_nama."'
				, '".$pegawai_nip."'
				, ".$gp."
				, ".$t_jbtn."
				, ".$mp_absen."
				, ".$mt_malam."
				, ".$mt_lembur."
				, ".$t_hadir."
				, ".$mt_um."
				, ".$bruto."
				, ".$p_aspen."
				, ".$p_bpjs."
				, ".$netto."
				)
				"; //echo $msql; exit;
			$conn->Execute($msql);
			
			$rs->MoveNext();
		}
	}
}
$rs->Close();
header("location: r_lapgjblnsmry.php");
?>