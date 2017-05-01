<?php

// lapgroup_nama
// lapgroup_index

?>
<?php if ($t_lapgroup->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_lapgroup->TableCaption() ?></h4> -->
<table id="tbl_t_lapgroupmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_lapgroup->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_lapgroup->lapgroup_nama->Visible) { // lapgroup_nama ?>
		<tr id="r_lapgroup_nama">
			<td><?php echo $t_lapgroup->lapgroup_nama->FldCaption() ?></td>
			<td<?php echo $t_lapgroup->lapgroup_nama->CellAttributes() ?>>
<span id="el_t_lapgroup_lapgroup_nama">
<span<?php echo $t_lapgroup->lapgroup_nama->ViewAttributes() ?>>
<?php echo $t_lapgroup->lapgroup_nama->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_lapgroup->lapgroup_index->Visible) { // lapgroup_index ?>
		<tr id="r_lapgroup_index">
			<td><?php echo $t_lapgroup->lapgroup_index->FldCaption() ?></td>
			<td<?php echo $t_lapgroup->lapgroup_index->CellAttributes() ?>>
<span id="el_t_lapgroup_lapgroup_index">
<span<?php echo $t_lapgroup->lapgroup_index->ViewAttributes() ?>>
<?php echo $t_lapgroup->lapgroup_index->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
