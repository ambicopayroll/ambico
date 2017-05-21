<?php
if ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	include "adodb5/adodb.inc.php";
	$conn = ADONewConnection('mysql');
	$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
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

$msql = "delete from t_laplemburh";
$conn->Execute($msql);

$mno = 1;

$query = "
	select
		f.lapgroup_nama
		, g.pembagian2_nama
		, a.pegawai_id
		, b.pegawai_nama
		, b.pegawai_nip
		, d.lembur
	from
		t_lembur a
		left join pegawai b on a.pegawai_id = b.pegawai_id
		left join t_rumus_peg c on a.pegawai_id = c.pegawai_id
		left join t_rumus d on c.rumus_id = d.rumus_id
		left join t_lapsubgroup e on b.pembagian2_id = e.pembagian2_id
		left join t_lapgroup f on e.lapgroup_id = f.lapgroup_id
		left join pembagian2 g on b.pembagian2_id = g.pembagian2_id
	where
		a.pegawai_id in (select pegawai_id from t_rumus_peg)
		and a.tgl_mulai between '".$_POST["start"]."' and '".$_POST["end"]."'
	group by
		a.pegawai_id
	"; //echo $query;
$rs = $conn->Execute($query);

while (!$rs->EOF) {
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	while ($rs->fields["lapgroup_nama"] == $mlapgroup_nama and !$rs->EOF) {
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		while ($rs->fields["pembagian2_nama"] == $mpembagian2_nama and !$rs->EOF) {
			$mpegawai_id = $rs->fields["pegawai_id"];
			$mpegawai_nama = $rs->fields["pegawai_nama"];
			$mpegawai_nip = $rs->fields["pegawai_nip"];
			$mt_lembur = $rs->fields["lembur"];

			// hitung lembur
			$mjml_jam = f_hitungjamlembur($conn, $mpegawai_id);
			$mjml_lembur = $mjml_jam * $mt_lembur;
			
			if ($mjml_jam <> 0) {
				$query = "
					insert into t_laplemburh values (null
					, ".$mno."
					, '".$mlapgroup_nama."'
					, '".$mpembagian2_nama."'
					, '".$mpegawai_nama."'
					, '".$mpegawai_nip."'
					, ".$mjml_jam."
					, ".$mt_lembur."
					, ".$mjml_lembur."
					, '".$_POST["start"]."'
					, '".$_POST["end"]."'
					)
					"; //echo $query;
				$conn->Execute($query);
				
				$mno++;
			}

			$rs->MoveNext();
		}
	}
}
$rs->Close();
header("location: r_laplemburhsmry.php");
?>