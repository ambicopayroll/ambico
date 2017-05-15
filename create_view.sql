create view v_att_log as
Select att_log.sn As sn,
  att_log.scan_date As scan_date,
  att_log.pin As pin,
  att_log.att_id As att_id,
  Cast(Date_Format(att_log.scan_date, '%Y-%m-%d') As date) As scan_date_tgl,
  Date_Format(att_log.scan_date, '%d-%m-%Y %H:%i:%s') As scan_date_tgl_jam,
  pegawai.pegawai_nip As pegawai_nip,
  pegawai.pegawai_nama As pegawai_nama
From att_log
  Left Join pegawai On att_log.pin = pegawai.pegawai_pin;

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
Select e.lapgroup_id As lapgroup_id,
  e.lapgroup_nama As lapgroup_nama,
  e.lapgroup_index As lapgroup_index,
  d.lapsubgroup_index As lapsubgroup_index,
  a.pegawai_id As pegawai_id,
  a.tgl As tgl,
  a.jk_id As jk_id,
  a.scan_masuk As scan_masuk,
  a.scan_keluar As scan_keluar,
  a.hk_def As hk_def,
  a.pegawai_nip As pegawai_nip,
  a.pegawai_nama As pegawai_nama,
  a.jk_kd As jk_kd,
  a.pembagian2_nama As pembagian2_nama,
  a.pembagian2_id As pembagian2_id,
  c.rumus_id As rumus_id,
  c.rumus_nama As rumus_nama,
  c.hk_gol As hk_gol,
  c.umr As umr,
  c.hk_jml As hk_jml,
  c.upah As upah,
  c.premi_hadir As premi_hadir,
  c.premi_malam As premi_malam,
  c.pot_absen As pot_absen,
  c.lembur As lembur,
  (Case When (isnull(a.scan_masuk) And isnull(a.scan_keluar)) Then 0 Else c.upah
  End) As upah2,
  (Case When (Right(a.jk_kd, 2) = 'S3') Then c.premi_malam Else 0
  End) As premi_malam2,
  (Case
    When (Count((isnull(a.scan_masuk) And isnull(a.scan_keluar) And
    (Right(a.jk_kd, 1) <> 'L'))) > 1) Then 0 Else c.premi_hadir
  End) As premi_hadir2
From (((v_jdw_krj_def a
  Left Join t_rumus_peg b On a.pegawai_id = b.pegawai_id)
  Left Join t_rumus c On b.rumus_id = c.rumus_id)
  Left Join t_lapsubgroup d On a.pembagian2_id = d.pembagian2_id)
  Left Join t_lapgroup e On d.lapgroup_id = e.lapgroup_id
Where (c.hk_gol = a.hk_def) And
  Not (a.pegawai_id In (Select t_rumus2_peg.pegawai_id As pegawai_id
  From t_rumus2_peg))
Order By lapgroup_index,
  lapsubgroup_index,
  pegawai_id,
  tgl;

create view v_jdw_krj_brngan as
Select a.pegawai_id As pegawai_id,
  b.tgl As tgl,
  a.scan_masuk As scan_masuk,
  a.scan_keluar As scan_keluar,
  c.pegawai_nip As pegawai_nip,
  c.pegawai_nama As pegawai_nama,
  c.pegawai_pin As pegawai_pin
From (t_keg_detail a
  Left Join t_keg_master b On a.kegm_id = b.kegm_id)
  Left Join pegawai c On a.pegawai_id = c.pegawai_id;

create view v_rekon_brngan as
Select b.pegawai_nama As pegawai_nama,
  Cast(a.scan_date As date) As tgl,
  Min(a.scan_date) As scan_masuk,
  Max(a.scan_date) As scan_keluar,
  b.pegawai_id As pegawai_id
From att_log a
  Left Join pegawai b On a.pin = b.pegawai_pin
Where b.pegawai_id In (Select t_keg_detail.pegawai_id As pegawai_id
  From t_keg_detail)
Group By Concat(Cast(a.scan_date As date), b.pegawai_id);