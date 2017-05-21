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

?>
<table border="1">
	<tr>
		<th>Nama</th>
	</tr>
<?php

$query = "
	select
		*
	from
		t_gjbln
	";
$rs = $conn->Execute($query);

while (!$rs->EOF) {
	echo
	"
	<tr>
		<td>".$rs->fields["nama"]."</td>
	</tr>
	";
	$rs->MoveNext();
}
$rs->Close();

?>

</table>