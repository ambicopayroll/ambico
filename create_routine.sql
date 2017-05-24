CREATE FUNCTION `f_carikodepengecualian`(p_pegawai_id int, p_tgl date) RETURNS varchar(10) CHARSET latin1
BEGIN
declare r_kode varchar(10);
select b.kode into r_kode from t_pengecualian_peg a left join t_jns_pengecualian b on a.jns_id = b.jns_id
where pegawai_id = p_pegawai_id and p_tgl between tgl and tgl2;
RETURN r_kode;
END//

CREATE FUNCTION `f_cari_pengecualian`(p_pegawai_id int, p_tgl date) RETURNS varchar(10) CHARSET latin1
BEGIN
declare r_kode varchar(10);
-- select jns_id into ada from t_pengecualian_peg where pegawai_id = p_pegawai_id and tgl = p_tgl;
select b.kode into r_kode from t_pengecualian_peg a left join t_jns_pengecualian b on a.jns_id = b.jns_id where pegawai_id = p_pegawai_id and tgl = p_tgl;
RETURN r_kode;
END//

CREATE PROCEDURE `p_gen_rekon`(in mstart date, in mend date)
BEGIN
update
	t_jdw_krj_def a
	left join pegawai b on a.pegawai_id = b.pegawai_id
	left join t_jk c on a.jk_id = c.jk_id
	left join att_log d on
		b.pegawai_pin = d.pin
		and cast(d.scan_date as date) = cast(a.tgl as date)
        and cast(d.scan_date as time) between cast((concat('1974-12-24 ', c.jk_m) - interval '60' minute) as time) and cast(c.jk_m as time)
set
	scan_masuk = d.scan_date
where
    a.tgl between mstart and mend;
update
	t_jdw_krj_def a
	left join pegawai b on a.pegawai_id = b.pegawai_id
	left join t_jk c on a.jk_id = c.jk_id
	left join att_log e on
		b.pegawai_pin = e.pin
        and cast(e.scan_date as time) between cast(c.jk_k as time) and cast((concat('1974-12-24 ', c.jk_k) + interval '60' minute) as time)
        and cast(e.scan_date as date) = 
			(case when left(c.jk_kd, 2) = 'S3' then
				cast(a.tgl + interval 1 day as date)
			else
				cast(a.tgl as date)
			end)
set
	scan_keluar = e.scan_date
where
    a.tgl between mstart and mend;
END//

CREATE PROCEDURE `p_gen_rekon_brngan`(in mstart date, in mend date)
BEGIN
update
	t_keg_detail a
    left join
		(
        select
			pegawai_id,
			min(scan_date) as min_scan_date
		from
			(
			select
				a.pegawai_id
				, d.scan_date
			from
				t_keg_detail a
				left join t_keg_master b on a.kegm_id = b.kegm_id
				left join pegawai c on a.pegawai_id = c.pegawai_id
				left join att_log d on
					c.pegawai_pin = d.pin
					and cast(d.scan_date as date) = cast(b.tgl as date)
			where
				b.tgl between mstart and mend
			) a
		group by
			pegawai_id
		) b on a.pegawai_id = b.pegawai_id
set
	a.scan_masuk = b.min_scan_date;
update
	t_keg_detail a
    left join
		(
        select
			pegawai_id,
			max(scan_date) as max_scan_date
		from
			(
			select
				a.pegawai_id
				, d.scan_date
			from
				t_keg_detail a
				left join t_keg_master b on a.kegm_id = b.kegm_id
				left join pegawai c on a.pegawai_id = c.pegawai_id
				left join att_log d on
					c.pegawai_pin = d.pin
					and cast(d.scan_date as date) = cast(b.tgl as date)
			where
				b.tgl between mstart and mend
			) a
		group by
			pegawai_id
		) b on a.pegawai_id = b.pegawai_id
set
	a.scan_keluar = b.max_scan_date;
END//

CREATE FUNCTION `f_harilibur`(p_tgl date) RETURNS int(11)
BEGIN
declare ada integer;
select count(*) into ada from t_harilibur where libur_tgl = p_tgl;
RETURN ada;
END//