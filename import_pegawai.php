<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "userfn13.php" ?>
<html>
<body>
	<form name="import" method="post" enctype="multipart/form-data">
    	<input type="file" name="file" /><br />
        <input type="submit" name="submit" value="Submit" />
    </form>
<?php
	// nik	tgl1	tgl2	shift	masuk	keluar	hk
	//include ("connection.php");
	
	if(isset($_POST["submit"]))
	{
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 0;
		while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		{
			//$name = $filesop[0];
			//$email = $filesop[1];
			$nik    = $filesop[0]; //echo "nik = ".$nik."</br>";
			$tgl1   = $filesop[1];
			$tgl2   = $filesop[2];
			$shift  = $filesop[3];
			$masuk  = $filesop[4];
			$keluar = $filesop[5];
			$libur  = $filesop[6];
			$hk     = $filesop[7];
			
			// cari pegawai_id
			$pegawai_id = "";
			$nik = substr("0000" . $nik, -4);
			$q = "select pegawai_id from pegawai where pegawai_nip = '".$nik."'";
			$rs = Conn()->Execute($q); //$r = mysql_query($q);
			//$recno = mysql_num_rows($r);
			if ($rs->RecordCount() > 0) { //if ($recno > 0) {
				//$rs = mysql_fetch_array($r);
				$pegawai_id = $rs->fields["pegawai_id"]; //$pegawai_id = $rs["pegawai_id"];
			}
			
			// cari jk_id
			$jk_id = 0;
			//$jk_nm = "Shift " . $shift . ", " . substr("00" . $masuk, -2) . "-" . substr("00" . $keluar, -2);
			$jk_nm = "Shift " . $shift . ", " . $masuk . "-" . $keluar;
			if ($libur == "L") {
				$jk_nm .= ", Libur";
			} //echo $jk_nm;
			$q = "select jk_id from t_jk where jk_nm = '".$jk_nm."'";
			$rs = Conn()->Execute($q); //$r = mysql_query($q);
			//$recno = mysql_num_rows($r);
			if ($rs->RecordCount() > 0) { //if ($recno > 0) {
				//$rs = mysql_fetch_array($r);
				$jk_id = $rs->fields["jk_id"]; //$jk_id = $rs["jk_id"];
			}
			
			if ($pegawai_id == "") {
				// tidak menemukan data pegawai
				$baris = $c + 1;
				echo "Tidak ditemukan Data Pegawai dengan NIK : <b>".$nik."</b> pada baris ke ".$baris.", data tidak terimpor</br>";
			}
			else {
				if ($jk_id == 0) {
					// tidak menemukan data jam kerja
					$baris = $c + 1;
					echo "Tidak ditemukan Data Jam Kerja dengan Nama Jam Kerja : <b>".$jk_nm."</b> pada baris ke ".$baris.", data tidak terimpor</br>";
				}
				else {
					//echo "Tgl_1 ".$tgl1."</br>";
					//echo "Tgl_2 ".$tgl2."</br>";
					$sql = "insert into t_jdw_krj_peg values (null, '".$pegawai_id."', '".$tgl1."', '".$tgl2."', ".$jk_id.", ".$hk.")";
					echo $sql."</br>";
					//$sql = mysql_query("INSERT INTO csv (name, email) VALUES ('$name','$email')");
					//$sql = mysql_query("INSERT INTO csv (name, email) VALUES ('$name','$email')");
					Conn()->Execute($sql); //mysql_query($sql);
				}
			}
			
			$c = $c + 1;
		}
		
			/*if($sql){
				echo "You database has imported successfully. You have inserted ". $c ." recoreds";
			}else{
				echo "Sorry! There is some problem.";
			}*/

	}
?>
    
    </div>
</body>
</html>