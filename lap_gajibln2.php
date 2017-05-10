<?php

function f_data_valid($jam_masuk, $jam_keluar) {
	if (!is_null($jam_masuk) and !is_null($jam_keluar)) { jam_masuk ada dan jam_keluar ada
		return 0;
	}
	if (!is_null($jam_masuk) and is_null($jam_keluar)) { jam_masuk ada dan jam_keluar tidak ada
		return 1;
	}
	if (is_null($jam_masuk) and !is_null($jam_keluar)) { jam_masuk tidak ada dan jam_keluar ada
		return 2;
	}
	if (is_null($jam_masuk) and is_null($jam_keluar)) { jam_masuk tidak ada dan jam_keluar tidak ada
		return 3;
	}
}

$gp       = $rs->fields["gp"]; // gaji pokok
$t_jbtn   = $rs->fields["tj"]; // tunjangan jabatan
$p_absen  = 0; // potongan absen
$t_malam  = 0; // tunjangan malam
$t_lembur = 0; // tunjangan lembur
$t_hadir  = 0; // tunjangan hadir
$t_um     = 0; // tunjangan uang makan
$t_fork   = 0; // tunjangan forklift

// check data valid
$data_valid = f_data_valid($rs2->fields["scan_masuk"], $rs2->fields["scan_keluar"]);
if ($data_valid == 0) {
	// data valid
}
else {
	// data tidak valid
}
?>