drop view v_jdw_krj_def_pegawai;
create view v_jdw_krj_def_pegawai as
select
	a.pegawai_id
    , a.tgl
    , a.jk_id
    , a.hk_def
    , b.pegawai_pin
    , b.pegawai_nama
    , c.pembagian2_nama
    , d.jk_kd
    , d.jk_m
    , d.jk_k
from
	t_jdw_krj_def a
    left join pegawai b on a.pegawai_id = b.pegawai_id
    left join pembagian2 c on b.pembagian2_id = c.pembagian2_id
    left join t_jk d on a.jk_id = d.jk_id