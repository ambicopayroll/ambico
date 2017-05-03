create view v_att_log as
SELECT att_log.sn AS sn,
  att_log.scan_date AS scan_date,
  att_log.pin AS pin,
  att_log.att_id AS att_id,
  CAST(Date_Format(att_log.scan_date, '%Y-%m-%d') AS date) AS scan_date_tgl,
  Date_Format(att_log.scan_date, '%d-%m-%Y %H:%i:%s') AS scan_date_tgl_jam,
  pegawai.pegawai_nip AS pegawai_nip,
  pegawai.pegawai_nama AS pegawai_nama
FROM att_log
  LEFT JOIN pegawai ON att_log.pin = pegawai.pegawai_pin;

create view v_jdw_krj_def as
SELECT t_jdw_krj_def.pegawai_id AS pegawai_id,
  t_jdw_krj_def.tgl AS tgl,
  t_jdw_krj_def.jk_id AS jk_id,
  t_jdw_krj_def.scan_masuk AS scan_masuk,
  t_jdw_krj_def.scan_keluar AS scan_keluar,
  t_jdw_krj_def.hk_def AS hk_def,
  pegawai.pegawai_nip AS pegawai_nip,
  pegawai.pegawai_nama AS pegawai_nama,
  t_jk.jk_kd AS jk_kd,
  pembagian2.pembagian2_nama AS pembagian2_nama
FROM ((t_jdw_krj_def
  JOIN pegawai ON t_jdw_krj_def.pegawai_id = pegawai.pegawai_id)
  JOIN t_jk ON t_jdw_krj_def.jk_id = t_jk.jk_id)
  JOIN pembagian2 ON pegawai.pembagian2_id = pembagian2.pembagian2_id;