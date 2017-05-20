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

function f_carilamakerja($p_pegawai_id, $p_tgl, $p_conn) {
	$query = "select * from t_pengecualian_peg where pegawai_id = ".$p_pegawai_id." and tgl = '".$p_tgl."'";
	$rs = $p_conn->Execute($query);
	if (!$rs->EOF) {
		$lama_kerja = strtotime($rs->fields["jam_keluar"]) - strtotime($rs->fields["jam_masuk"]);
		$lama_kerja = floor($lama_kerja / (60 * 60));
		return $lama_kerja;
		/*$awal  = strtotime('2017-08-10 10:05:25');
		$akhir = strtotime('2017-08-11 11:07:33');
		$diff  = $akhir - $awal;

		$jam   = floor($diff / (60 * 60));
		$menit = $diff - $jam * (60 * 60);
		echo 'Waktu tinggal: ' . $jam .  ' jam, ' . floor( $menit / 60 ) . ' menit';*/
		
	}
}

function f_hitungjamlembur($p_conn, $p_pegawai_id) {
	$query = "select * from t_lembur where pegawai_id = ".$p_pegawai_id." order by tgl_mulai";
	$rs = $p_conn->Execute($query);
	$mlama_lembur = 0;
	while (!$rs->EOF) {
		$mtgl_mulai = $rs->fields["tgl_mulai"];
		$mtgl_selesai = $rs->fields["tgl_selesai"];
		
		// cek apakah hanya lembur 1 hari
		if ($mtgl_mulai == $mtgl_selesai) {
			
			// cek apakah hari lembur masuk dalam range input laporan gaji
			if ($mtgl_mulai >= $_POST["start"] and $mtgl_mulai <= $_POST["end"]) {
				// hitung jam lembur
				$lama_lembur = strtotime($rs->fields["jam_selesai"]) - strtotime($rs->fields["jam_mulai"]);
				$mlama_lembur += floor($lama_lembur / (60 * 60));
			}
		}
		// hari lembur lebih dari 1 hari
		else {
			while (strtotime($mtgl_mulai) <= strtotime($mtgl_selesai)) {
				if ($mtgl_mulai >= $_POST["start"] and $mtgl_mulai <= $_POST["end"]) {
					// hitung jam lembur
					$lama_lembur = strtotime($rs->fields["jam_selesai"]) - strtotime($rs->fields["jam_mulai"]);
					$mlama_lembur += floor($lama_lembur / (60 * 60));
				}
				$mtgl_mulai = date("Y-m-d", strtotime("+1 day", strtotime($mtgl_mulai)));
			}
		}
		$rs->MoveNext();
	}
	return $mlama_lembur;
}

$msql = "delete from t_laplembur";
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
		, c.pegawai_nip
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

$mno = 1;

while (!$rs->EOF) {
	$mlapgroup_id = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			
			// prepare data
			$pegawai_id   = $rs->fields["pegawai_id"];
			$gp           = $rs->fields["gp"]; // gaji pokok
			$t_jbtn       = $rs->fields["tj"]; // tunjangan jabatan
			$t_hadir      = $rs->fields["premi_hadir"]; // tunjangan hadir
			$t_malam      = $rs->fields["premi_malam"]; // tunjangan malam
			$t_um         = $rs->fields["lp"]; // tunjangan uang makan
			$t_fork       = $rs->fields["forklift"]; // tunjangan forklift
			//$t_lembur     = $rs->fields["lembur"]; // tunjangan lembur
			$t_lembur     = ($rs->fields["lembur"] < 500 ? $gp / $rs->fields["lembur"] : $rs->fields["lembur"]); // tunjangan lembur
			$p_absen5     = $gp / 25; // potongan absen 5 hk
			$p_absen6     = $gp / 30; // potongan absen 6 hk
			$p_aspen      = $gp * $rs->fields["pot_aspen"]; // potongan astek & pensiun
			$p_bpjs       = ($rs->fields["pot_bpjs"] < 1 ? $gp * $rs->fields["pot_bpjs"] : $rs->fields["pot_bpjs"]); // potongan bpjs
			$pegawai_nama = $rs->fields["pegawai_nama"];
			$pegawai_nip  = $rs->fields["pegawai_nip"];
			
			/*$msql = "
				select * from v_jdw_krj_def
				where
					pegawai_id = ".$pegawai_id."
					and tgl between '".$_POST['start']."' and '".$_POST['end']."'
				order by
					tgl
				"; //echo $msql; exit;*/
			$query = "
				select
					*
				from
					t_lembur
				where
					pegawai_id = ".$pegawai_id."
					and tgl_mulai between '' and ''
				order by
					tgl_mulai
				";
			$rs2 = $conn->Execute($query);
			
			//$bagian       = $rs2->fields["pembagian2_nama"];
			//$pegawai_nama = $rs2->fields["pegawai_nama"];
			//$pegawai_nip  = $rs2->fields["pegawai_nip"];
			//$pegawai_pin  = $rs2->fields["pegawai_pin"];
			
			$mp_absen   = 0;
			$mt_malam   = 0;
			$mt_lembur  = 0;
			$mt_um      = 0;
			$mt_fork    = 0;
			$mabsen     = 0;
			$mterlambat = 0;			
			
			// hitung lembur
			$mjml_jam = f_hitungjamlembur($conn, $pegawai_id);
			if ($mjml_jam > 1) {
				$mjml_lembur = (1.5 * $t_lembur) + (($mjml_jam - 1) * 2 * $t_lembur);
			}
			else {
				$mjml_lembur = (1.5 * $t_lembur);
			}
			//$mt_lembur += f_hitungjamlembur($conn, $pegawai_id) * $t_lembur;
			
			//if ($mabsen == 1 or $mterlambat == 1) $t_hadir = 0;
			//$bruto = $gp + $t_jbtn - $mp_absen + $mt_malam + $mt_lembur + $t_hadir + $mt_um; //+ $mt_fork;
			//$netto = $bruto - $p_aspen - $p_bpjs;
			
			/*$msql = "
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
				, '".$_POST["start"]."'
				, '".$_POST["end"]."'
				)
				"; //echo $msql; exit;*/
			
			if ($mjml_jam <> 0) {
				$query = "
					insert into t_laplembur values (null
					, ".$mno."
					, '".$mlapgroup_nama."'
					, '".$mpembagian2_nama."'
					, '".$pegawai_nama."'
					, '".$pegawai_nip."'
					, ".$mjml_jam."
					, ".$t_lembur."
					, ".$mjml_lembur."
					, '".$_POST["start"]."'
					, '".$_POST["end"]."'
					)
					";
				$conn->Execute($query);
				
				$mno++;
			}
			
			$rs->MoveNext();
		}
	}
}
$rs->Close();
header("location: r_laplembursmry.php");
?>