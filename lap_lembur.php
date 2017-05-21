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

function f_hitungjamlembur_staf($p_conn, $p_pegawai_id) {
	$query = "select * from t_lembur where pegawai_id = ".$p_pegawai_id." order by tgl_mulai";
	$rs = $p_conn->Execute($query);
	//$mlama_lembur = 0;
	$mlama_lembur1_5 = 0;
	$mlama_lembur2_0 = 0;
	while (!$rs->EOF) {
		$mtgl_mulai = $rs->fields["tgl_mulai"];
		$mtgl_selesai = $rs->fields["tgl_selesai"];
		
		// cek apakah hanya lembur 1 hari
		if ($mtgl_mulai == $mtgl_selesai) {
			
			// cek apakah hari lembur masuk dalam range input laporan gaji
			if ($mtgl_mulai >= $_POST["start"] and $mtgl_mulai <= $_POST["end"]) {
				// hitung jam lembur
				$lama_lembur = strtotime($rs->fields["jam_selesai"]) - strtotime($rs->fields["jam_mulai"]);
				//$mlama_lembur += floor($lama_lembur / (60 * 60));
				$lama_lembur = floor($lama_lembur / (60 * 60));
				if ($lama_lembur == 1) {
					$mlama_lembur1_5 += 1;
					$mlama_lembur2_0 += 0;
				}
				elseif ($lama_lembur > 1) {
					$mlama_lembur1_5 += 1;
					$mlama_lembur2_0 += ($lama_lembur - 1);
				}
			}
			
		}
		// hari lembur lebih dari 1 hari
		else {
			while (strtotime($mtgl_mulai) <= strtotime($mtgl_selesai)) {
				if ($mtgl_mulai >= $_POST["start"] and $mtgl_mulai <= $_POST["end"]) {
					// hitung jam lembur
					$lama_lembur = strtotime($rs->fields["jam_selesai"]) - strtotime($rs->fields["jam_mulai"]);
					$lama_lembur = floor($lama_lembur / (60 * 60));
					if ($lama_lembur == 1) {
						$mlama_lembur1_5 += 1;
						$mlama_lembur2_0 += 0;
					}
					elseif ($lama_lembur > 1) {
						$mlama_lembur1_5 += 1;
						$mlama_lembur2_0 += ($lama_lembur - 1);
					}
				}
				$mtgl_mulai = date("Y-m-d", strtotime("+1 day", strtotime($mtgl_mulai)));
			}
		}
		$rs->MoveNext();
	}
	return array($mlama_lembur1_5, $mlama_lembur2_0);
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
		f.lapgroup_nama
		, d.pembagian2_nama
		, c.pegawai_nama
		, c.pegawai_nip
		, c.pembagian1_id
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
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	while ($rs->fields["lapgroup_nama"] == $mlapgroup_nama and !$rs->EOF) {
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		while ($rs->fields["pembagian2_nama"] == $mpembagian2_nama and !$rs->EOF) {
			
			// prepare data
			$pegawai_id   = $rs->fields["pegawai_id"];
			$gp           = $rs->fields["gp"]; // gaji pokok
			$t_lembur     = ($rs->fields["lembur"] < 500 ? $gp / $rs->fields["lembur"] : $rs->fields["lembur"]); // tunjangan lembur
			$pegawai_nama = $rs->fields["pegawai_nama"];
			$pegawai_nip  = $rs->fields["pegawai_nip"];
			$mjml_jam     = 0;
			$mjml_lembur  = 0;
			
			// hitung lembur
			if ($rs->fields["pembagian1_id"] >= 1 and $rs->fields["pembagian1_id"] <= 3) {
				$ajml_jam = f_hitungjamlembur_staf($conn, $pegawai_id);
				if ($ajml_jam[0] <> 0) {
					$mjml_jam += $ajml_jam[0];
					$mjml_lembur += (1.5 * $ajml_jam[0] * $t_lembur);
				}
				if ($ajml_jam[1] <> 0) {
					$mjml_jam += $ajml_jam[1];
					$mjml_lembur += (2 * $ajml_jam[1] * $t_lembur);
				}
			}
			else {
				$mjml_jam = f_hitungjamlembur($conn, $pegawai_id);
				$mjml_lembur = $mjml_jam * $t_lembur;
			}
			
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