<?php

// pegawai_pin
// pegawai_nip
// pegawai_nama
// pembagian1_id
// pembagian2_id

?>
<?php if ($pegawai->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $pegawai->TableCaption() ?></h4> -->
<table id="tbl_pegawaimaster" class="table table-bordered table-striped ewViewTable">
<?php echo $pegawai->TableCustomInnerHtml ?>
	<tbody>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<tr id="r_pegawai_pin">
			<td><?php echo $pegawai->pegawai_pin->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pin">
<span<?php echo $pegawai->pegawai_pin->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_pin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<tr id="r_pegawai_nip">
			<td><?php echo $pegawai->pegawai_nip->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nip">
<span<?php echo $pegawai->pegawai_nip->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nip->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<tr id="r_pegawai_nama">
			<td><?php echo $pegawai->pegawai_nama->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nama">
<span<?php echo $pegawai->pegawai_nama->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nama->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<tr id="r_pembagian1_id">
			<td><?php echo $pegawai->pembagian1_id->FldCaption() ?></td>
			<td<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian1_id">
<span<?php echo $pegawai->pembagian1_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian1_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<tr id="r_pembagian2_id">
			<td><?php echo $pegawai->pembagian2_id->FldCaption() ?></td>
			<td<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian2_id">
<span<?php echo $pegawai->pembagian2_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian2_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
