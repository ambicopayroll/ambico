<?php

// keg_id
// tgl
// shift
// hasil

?>
<?php if ($t_keg_master->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_keg_master->TableCaption() ?></h4> -->
<table id="tbl_t_keg_mastermaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_keg_master->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_keg_master->keg_id->Visible) { // keg_id ?>
		<tr id="r_keg_id">
			<td><?php echo $t_keg_master->keg_id->FldCaption() ?></td>
			<td<?php echo $t_keg_master->keg_id->CellAttributes() ?>>
<span id="el_t_keg_master_keg_id">
<span<?php echo $t_keg_master->keg_id->ViewAttributes() ?>>
<?php echo $t_keg_master->keg_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_keg_master->tgl->Visible) { // tgl ?>
		<tr id="r_tgl">
			<td><?php echo $t_keg_master->tgl->FldCaption() ?></td>
			<td<?php echo $t_keg_master->tgl->CellAttributes() ?>>
<span id="el_t_keg_master_tgl">
<span<?php echo $t_keg_master->tgl->ViewAttributes() ?>>
<?php echo $t_keg_master->tgl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_keg_master->shift->Visible) { // shift ?>
		<tr id="r_shift">
			<td><?php echo $t_keg_master->shift->FldCaption() ?></td>
			<td<?php echo $t_keg_master->shift->CellAttributes() ?>>
<span id="el_t_keg_master_shift">
<span<?php echo $t_keg_master->shift->ViewAttributes() ?>>
<?php echo $t_keg_master->shift->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_keg_master->hasil->Visible) { // hasil ?>
		<tr id="r_hasil">
			<td><?php echo $t_keg_master->hasil->FldCaption() ?></td>
			<td<?php echo $t_keg_master->hasil->CellAttributes() ?>>
<span id="el_t_keg_master_hasil">
<span<?php echo $t_keg_master->hasil->ViewAttributes() ?>>
<?php echo $t_keg_master->hasil->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
