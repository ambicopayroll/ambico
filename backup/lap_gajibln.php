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
			$mgp = $rs->fields["gp"];
			$mtj = $rs->fields["tj"];
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
			$mpegawai_pin = $rs2->fields["pegawai_pin"];
			//$mjml_absen = 0;
			
			while (!$rs2->EOF) {

				// check data valid
				$data_valid = false;
				if (!is_null($rs2->fields["scan_masuk"]) and !is_null($rs2->fields["scan_keluar"])) {
					$data_valid = true;
				}
				
				// hitung premi hadir & pot. absen
				if (!$data_valid and substr($rs2->fields["jk_kd"], -1) != "L") {
					$mpremi_hadir = 0;
					$msql = "select f_cari_pengecualian(".$mpegawai_id.", '".$rs2->fields["tgl"]."') as r_kode"; //echo $msql; exit;
					$rs3 = $conn->Execute($msql);
					if (!$rs3->EOF) {
						if ($rs3->fields["r_kode"] == "HD") {
							// cek jam masuk dulu
							$mjam_masuk_valid = false; $mjam_keluar_valid = false;
							if (!is_null($rs2->fields["scan_masuk"])) {
								$mjam_masuk_valid = true;
								$mjam_masuk = $rs2->fields["scan_masuk"];
								// cari data jam keluar di att_log
								$msql = "select f_cari_jamkeluar(".$mpegawai_pin.", '".$rs2->fields["tgl"]."') as r_jam";
								$rs4 = $conn->Execute();
								if (!$rs4->EOF) {
									$mjam_keluar_valid = true;
									$mjam_keluar = $rs4->fields["r_jam"];
									
								}
							}
						}
						else {
							if ($rs2->fields["hk_def"] == 5) {
								$mpot_absen += $rs->fields["gp"] / 25; //echo $rs2->fields["tgl"]."-".$mpot_absen.";";
							}
							else {
								$mpot_absen += $rs->fields["gp"] / 30; //echo $rs2->fields["tgl"]."-".$mpot_absen.";";
							}
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
			$mtotal2 += $rs->fields["gp"];
			$mno++;
			
			$msql = "
				insert into t_gjbln values (null, 
				'".$mlapgroup_nama."'
				, '".$mpembagian2_nama."'
				, '".$mpegawai_nama."'
				, '".$mpegawai_nip."'
				, ".$mgp."
				, ".$mtj."
				, ".$mpot_absen."
				, ".$mpremi_malam."
				, ".$mlembur."
				, ".$mpremi_hadir."
				, ".$mlp."
				, ".$mbruto."
				, ".$mpot_aspen."
				, ".$mpot_bpjs."
				, ".$mnetto."
				)
				"; //echo $msql; exit;
			$conn->Execute($msql);
			
			$rs->MoveNext();
		}
		$mtotal1 += $mtotal2;
	}
	$mgrand_total += $mtotal1;
}
$rs->Close();
header("location: r_lapgjblnsmry.php");
?>