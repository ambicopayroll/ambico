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
Select t_jdw_krj_def.pegawai_id As pegawai_id,
  t_jdw_krj_def.tgl As tgl,
  t_jdw_krj_def.jk_id As jk_id,
  t_jdw_krj_def.scan_masuk As scan_masuk,
  t_jdw_krj_def.scan_keluar As scan_keluar,
  t_jdw_krj_def.hk_def As hk_def,
  pegawai.pegawai_nip As pegawai_nip,
  pegawai.pegawai_nama As pegawai_nama,
  t_jk.jk_kd As jk_kd,
  pembagian2.pembagian2_nama As pembagian2_nama,
  pegawai.pembagian2_id As pembagian2_id,
  pegawai.pegawai_pin As pegawai_pin,
  t_lapgroup.lapgroup_nama As lapgroup_nama
From ((((t_jdw_krj_def
  Join pegawai On t_jdw_krj_def.pegawai_id = pegawai.pegawai_id)
  Join t_jk On t_jdw_krj_def.jk_id = t_jk.jk_id)
  Join pembagian2 On pegawai.pembagian2_id = pembagian2.pembagian2_id)
  Left Join t_lapsubgroup
    On pegawai.pembagian2_id = t_lapsubgroup.pembagian2_id)
  Join t_lapgroup On t_lapsubgroup.lapgroup_id = t_lapgroup.lapgroup_id;

create view v_lapgjhrn as  
SELECT e.lapgroup_id AS lapgroup_id,
  e.lapgroup_nama AS lapgroup_nama,
  e.lapgroup_index AS lapgroup_index,
  d.lapsubgroup_index AS lapsubgroup_index,
  a.pegawai_id AS pegawai_id,
  a.tgl AS tgl,
  a.jk_id AS jk_id,
  a.scan_masuk AS scan_masuk,
  a.scan_keluar AS scan_keluar,
  a.hk_def AS hk_def,
  a.pegawai_nip AS pegawai_nip,
  a.pegawai_nama AS pegawai_nama,
  a.jk_kd AS jk_kd,
  a.pembagian2_nama AS pembagian2_nama,
  a.pembagian2_id AS pembagian2_id,
  c.rumus_id AS rumus_id,
  c.rumus_nama AS rumus_nama,
  c.hk_gol AS hk_gol,
  c.umr AS umr,
  c.hk_jml AS hk_jml,
  c.upah AS upah,
  c.premi_hadir AS premi_hadir,
  c.premi_malam AS premi_malam,
  c.pot_absen AS pot_absen,
  c.lembur AS lembur,
  (CASE WHEN (isnull(a.scan_masuk) AND isnull(a.scan_keluar)) THEN 0 ELSE c.upah
  END) AS upah2,
  (CASE WHEN (Right(a.jk_kd, 2) = 'S3') THEN c.premi_malam ELSE 0
  END) AS premi_malam2,
  (CASE
    WHEN (Count((isnull(a.scan_masuk) AND isnull(a.scan_keluar) AND
    (Right(a.jk_kd, 1) <> 'L'))) > 1) THEN 0 ELSE c.premi_hadir
  END) AS premi_hadir2
FROM (((v_jdw_krj_def a
  LEFT JOIN t_rumus_peg b ON a.pegawai_id = b.pegawai_id)
  LEFT JOIN t_rumus c ON b.rumus_id = c.rumus_id)
  LEFT JOIN t_lapsubgroup d ON a.pembagian2_id = d.pembagian2_id)
  LEFT JOIN t_lapgroup e ON d.lapgroup_id = e.lapgroup_id
WHERE (c.hk_gol = a.hk_def) AND
  NOT (a.pegawai_id IN (SELECT t_rumus2_peg.pegawai_id AS pegawai_id
  FROM t_rumus2_peg))
ORDER BY lapgroup_index,
  lapsubgroup_index,
  pegawai_id,
  tgl;