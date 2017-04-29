create view v_rekon3 as
select
	a.*
    , b.scan_date as scan_masuk
from
	v_jdw_krj_def_pegawai a
    left join att_log b on a.pegawai_pin = b.pin
where
	Cast(b.scan_date As date) = Cast(a.tgl As date)
    And Cast(b.scan_date As time) Between Cast((Concat('1974-12-24 ', a.jk_m) - Interval '60' Minute) As time) And Cast(a.jk_m As time)