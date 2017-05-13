<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_lembur_grid)) $t_lembur_grid = new ct_lembur_grid();

// Page init
$t_lembur_grid->Page_Init();

// Page main
$t_lembur_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_lembur_grid->Page_Render();
?>
<?php if ($t_lembur->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_lemburgrid = new ew_Form("ft_lemburgrid", "grid");
ft_lemburgrid.FormKeyCountName = '<?php echo $t_lembur_grid->FormKeyCountName ?>';

// Validate form
ft_lemburgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur->pegawai_id->FldCaption(), $t_lembur->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_mulai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur->tgl_mulai->FldCaption(), $t_lembur->tgl_mulai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_mulai");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur->tgl_mulai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_selesai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur->tgl_selesai->FldCaption(), $t_lembur->tgl_selesai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_selesai");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur->tgl_selesai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_mulai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur->jam_mulai->FldCaption(), $t_lembur->jam_mulai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jam_mulai");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur->jam_mulai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_selesai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lembur->jam_selesai->FldCaption(), $t_lembur->jam_selesai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jam_selesai");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lembur->jam_selesai->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_lemburgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_mulai", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_selesai", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jam_mulai", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jam_selesai", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_lemburgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_lemburgrid.ValidateRequired = true;
<?php } else { ?>
ft_lemburgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_lemburgrid.Lists["x_pegawai_id"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_lembur->CurrentAction == "gridadd") {
	if ($t_lembur->CurrentMode == "copy") {
		$bSelectLimit = $t_lembur_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_lembur_grid->TotalRecs = $t_lembur->SelectRecordCount();
			$t_lembur_grid->Recordset = $t_lembur_grid->LoadRecordset($t_lembur_grid->StartRec-1, $t_lembur_grid->DisplayRecs);
		} else {
			if ($t_lembur_grid->Recordset = $t_lembur_grid->LoadRecordset())
				$t_lembur_grid->TotalRecs = $t_lembur_grid->Recordset->RecordCount();
		}
		$t_lembur_grid->StartRec = 1;
		$t_lembur_grid->DisplayRecs = $t_lembur_grid->TotalRecs;
	} else {
		$t_lembur->CurrentFilter = "0=1";
		$t_lembur_grid->StartRec = 1;
		$t_lembur_grid->DisplayRecs = $t_lembur->GridAddRowCount;
	}
	$t_lembur_grid->TotalRecs = $t_lembur_grid->DisplayRecs;
	$t_lembur_grid->StopRec = $t_lembur_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_lembur_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_lembur_grid->TotalRecs <= 0)
			$t_lembur_grid->TotalRecs = $t_lembur->SelectRecordCount();
	} else {
		if (!$t_lembur_grid->Recordset && ($t_lembur_grid->Recordset = $t_lembur_grid->LoadRecordset()))
			$t_lembur_grid->TotalRecs = $t_lembur_grid->Recordset->RecordCount();
	}
	$t_lembur_grid->StartRec = 1;
	$t_lembur_grid->DisplayRecs = $t_lembur_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_lembur_grid->Recordset = $t_lembur_grid->LoadRecordset($t_lembur_grid->StartRec-1, $t_lembur_grid->DisplayRecs);

	// Set no record found message
	if ($t_lembur->CurrentAction == "" && $t_lembur_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_lembur_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_lembur_grid->SearchWhere == "0=101")
			$t_lembur_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_lembur_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_lembur_grid->RenderOtherOptions();
?>
<?php $t_lembur_grid->ShowPageHeader(); ?>
<?php
$t_lembur_grid->ShowMessage();
?>
<?php if ($t_lembur_grid->TotalRecs > 0 || $t_lembur->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_lembur">
<div id="ft_lemburgrid" class="ewForm form-inline">
<?php if ($t_lembur_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_lembur_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_lembur" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_lemburgrid" class="table ewTable">
<?php echo $t_lembur->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_lembur_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_lembur_grid->RenderListOptions();

// Render list options (header, left)
$t_lembur_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_lembur->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_lembur->SortUrl($t_lembur->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_lembur_pegawai_id" class="t_lembur_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_lembur->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_lembur_pegawai_id" class="t_lembur_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lembur->tgl_mulai->Visible) { // tgl_mulai ?>
	<?php if ($t_lembur->SortUrl($t_lembur->tgl_mulai) == "") { ?>
		<th data-name="tgl_mulai"><div id="elh_t_lembur_tgl_mulai" class="t_lembur_tgl_mulai"><div class="ewTableHeaderCaption"><?php echo $t_lembur->tgl_mulai->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_mulai"><div><div id="elh_t_lembur_tgl_mulai" class="t_lembur_tgl_mulai">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur->tgl_mulai->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur->tgl_mulai->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur->tgl_mulai->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lembur->tgl_selesai->Visible) { // tgl_selesai ?>
	<?php if ($t_lembur->SortUrl($t_lembur->tgl_selesai) == "") { ?>
		<th data-name="tgl_selesai"><div id="elh_t_lembur_tgl_selesai" class="t_lembur_tgl_selesai"><div class="ewTableHeaderCaption"><?php echo $t_lembur->tgl_selesai->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_selesai"><div><div id="elh_t_lembur_tgl_selesai" class="t_lembur_tgl_selesai">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur->tgl_selesai->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur->tgl_selesai->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur->tgl_selesai->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lembur->jam_mulai->Visible) { // jam_mulai ?>
	<?php if ($t_lembur->SortUrl($t_lembur->jam_mulai) == "") { ?>
		<th data-name="jam_mulai"><div id="elh_t_lembur_jam_mulai" class="t_lembur_jam_mulai"><div class="ewTableHeaderCaption"><?php echo $t_lembur->jam_mulai->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jam_mulai"><div><div id="elh_t_lembur_jam_mulai" class="t_lembur_jam_mulai">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur->jam_mulai->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur->jam_mulai->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur->jam_mulai->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lembur->jam_selesai->Visible) { // jam_selesai ?>
	<?php if ($t_lembur->SortUrl($t_lembur->jam_selesai) == "") { ?>
		<th data-name="jam_selesai"><div id="elh_t_lembur_jam_selesai" class="t_lembur_jam_selesai"><div class="ewTableHeaderCaption"><?php echo $t_lembur->jam_selesai->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jam_selesai"><div><div id="elh_t_lembur_jam_selesai" class="t_lembur_jam_selesai">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lembur->jam_selesai->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lembur->jam_selesai->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lembur->jam_selesai->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_lembur_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_lembur_grid->StartRec = 1;
$t_lembur_grid->StopRec = $t_lembur_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_lembur_grid->FormKeyCountName) && ($t_lembur->CurrentAction == "gridadd" || $t_lembur->CurrentAction == "gridedit" || $t_lembur->CurrentAction == "F")) {
		$t_lembur_grid->KeyCount = $objForm->GetValue($t_lembur_grid->FormKeyCountName);
		$t_lembur_grid->StopRec = $t_lembur_grid->StartRec + $t_lembur_grid->KeyCount - 1;
	}
}
$t_lembur_grid->RecCnt = $t_lembur_grid->StartRec - 1;
if ($t_lembur_grid->Recordset && !$t_lembur_grid->Recordset->EOF) {
	$t_lembur_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_lembur_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_lembur_grid->StartRec > 1)
		$t_lembur_grid->Recordset->Move($t_lembur_grid->StartRec - 1);
} elseif (!$t_lembur->AllowAddDeleteRow && $t_lembur_grid->StopRec == 0) {
	$t_lembur_grid->StopRec = $t_lembur->GridAddRowCount;
}

// Initialize aggregate
$t_lembur->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_lembur->ResetAttrs();
$t_lembur_grid->RenderRow();
if ($t_lembur->CurrentAction == "gridadd")
	$t_lembur_grid->RowIndex = 0;
if ($t_lembur->CurrentAction == "gridedit")
	$t_lembur_grid->RowIndex = 0;
while ($t_lembur_grid->RecCnt < $t_lembur_grid->StopRec) {
	$t_lembur_grid->RecCnt++;
	if (intval($t_lembur_grid->RecCnt) >= intval($t_lembur_grid->StartRec)) {
		$t_lembur_grid->RowCnt++;
		if ($t_lembur->CurrentAction == "gridadd" || $t_lembur->CurrentAction == "gridedit" || $t_lembur->CurrentAction == "F") {
			$t_lembur_grid->RowIndex++;
			$objForm->Index = $t_lembur_grid->RowIndex;
			if ($objForm->HasValue($t_lembur_grid->FormActionName))
				$t_lembur_grid->RowAction = strval($objForm->GetValue($t_lembur_grid->FormActionName));
			elseif ($t_lembur->CurrentAction == "gridadd")
				$t_lembur_grid->RowAction = "insert";
			else
				$t_lembur_grid->RowAction = "";
		}

		// Set up key count
		$t_lembur_grid->KeyCount = $t_lembur_grid->RowIndex;

		// Init row class and style
		$t_lembur->ResetAttrs();
		$t_lembur->CssClass = "";
		if ($t_lembur->CurrentAction == "gridadd") {
			if ($t_lembur->CurrentMode == "copy") {
				$t_lembur_grid->LoadRowValues($t_lembur_grid->Recordset); // Load row values
				$t_lembur_grid->SetRecordKey($t_lembur_grid->RowOldKey, $t_lembur_grid->Recordset); // Set old record key
			} else {
				$t_lembur_grid->LoadDefaultValues(); // Load default values
				$t_lembur_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_lembur_grid->LoadRowValues($t_lembur_grid->Recordset); // Load row values
		}
		$t_lembur->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_lembur->CurrentAction == "gridadd") // Grid add
			$t_lembur->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_lembur->CurrentAction == "gridadd" && $t_lembur->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_lembur_grid->RestoreCurrentRowFormValues($t_lembur_grid->RowIndex); // Restore form values
		if ($t_lembur->CurrentAction == "gridedit") { // Grid edit
			if ($t_lembur->EventCancelled) {
				$t_lembur_grid->RestoreCurrentRowFormValues($t_lembur_grid->RowIndex); // Restore form values
			}
			if ($t_lembur_grid->RowAction == "insert")
				$t_lembur->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_lembur->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_lembur->CurrentAction == "gridedit" && ($t_lembur->RowType == EW_ROWTYPE_EDIT || $t_lembur->RowType == EW_ROWTYPE_ADD) && $t_lembur->EventCancelled) // Update failed
			$t_lembur_grid->RestoreCurrentRowFormValues($t_lembur_grid->RowIndex); // Restore form values
		if ($t_lembur->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_lembur_grid->EditRowCnt++;
		if ($t_lembur->CurrentAction == "F") // Confirm row
			$t_lembur_grid->RestoreCurrentRowFormValues($t_lembur_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_lembur->RowAttrs = array_merge($t_lembur->RowAttrs, array('data-rowindex'=>$t_lembur_grid->RowCnt, 'id'=>'r' . $t_lembur_grid->RowCnt . '_t_lembur', 'data-rowtype'=>$t_lembur->RowType));

		// Render row
		$t_lembur_grid->RenderRow();

		// Render list options
		$t_lembur_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_lembur_grid->RowAction <> "delete" && $t_lembur_grid->RowAction <> "insertdelete" && !($t_lembur_grid->RowAction == "insert" && $t_lembur->CurrentAction == "F" && $t_lembur_grid->EmptyRow())) {
?>
	<tr<?php echo $t_lembur->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_lembur_grid->ListOptions->Render("body", "left", $t_lembur_grid->RowCnt);
?>
	<?php if ($t_lembur->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_lembur->pegawai_id->CellAttributes() ?>>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_lembur->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span<?php echo $t_lembur->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_lembur->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_lembur->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_lembur->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_lembur->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_lembur->pegawai_id->CurrentValue ?>"<?php echo $t_lembur->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_lembur->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_lembur->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span<?php echo $t_lembur->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_lembur->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_lembur->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_lembur->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_lembur->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_lembur->pegawai_id->CurrentValue ?>"<?php echo $t_lembur->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_lembur->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_pegawai_id" class="t_lembur_pegawai_id">
<span<?php echo $t_lembur->pegawai_id->ViewAttributes() ?>>
<?php echo $t_lembur->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_lembur_grid->PageObjName . "_row_" . $t_lembur_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_lembur" data-field="x_lembur_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_lembur_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_lembur_id" value="<?php echo ew_HtmlEncode($t_lembur->lembur_id->CurrentValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_lembur_id" name="o<?php echo $t_lembur_grid->RowIndex ?>_lembur_id" id="o<?php echo $t_lembur_grid->RowIndex ?>_lembur_id" value="<?php echo ew_HtmlEncode($t_lembur->lembur_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_EDIT || $t_lembur->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_lembur" data-field="x_lembur_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_lembur_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_lembur_id" value="<?php echo ew_HtmlEncode($t_lembur->lembur_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_lembur->tgl_mulai->Visible) { // tgl_mulai ?>
		<td data-name="tgl_mulai"<?php echo $t_lembur->tgl_mulai->CellAttributes() ?>>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_tgl_mulai" class="form-group t_lembur_tgl_mulai">
<input type="text" data-table="t_lembur" data-field="x_tgl_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" placeholder="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->tgl_mulai->EditValue ?>"<?php echo $t_lembur->tgl_mulai->EditAttributes() ?>>
<?php if (!$t_lembur->tgl_mulai->ReadOnly && !$t_lembur->tgl_mulai->Disabled && !isset($t_lembur->tgl_mulai->EditAttrs["readonly"]) && !isset($t_lembur->tgl_mulai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lemburgrid", "x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_tgl_mulai" class="form-group t_lembur_tgl_mulai">
<input type="text" data-table="t_lembur" data-field="x_tgl_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" placeholder="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->tgl_mulai->EditValue ?>"<?php echo $t_lembur->tgl_mulai->EditAttributes() ?>>
<?php if (!$t_lembur->tgl_mulai->ReadOnly && !$t_lembur->tgl_mulai->Disabled && !isset($t_lembur->tgl_mulai->EditAttrs["readonly"]) && !isset($t_lembur->tgl_mulai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lemburgrid", "x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_tgl_mulai" class="t_lembur_tgl_mulai">
<span<?php echo $t_lembur->tgl_mulai->ViewAttributes() ?>>
<?php echo $t_lembur->tgl_mulai->ListViewValue() ?></span>
</span>
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_lembur->tgl_selesai->Visible) { // tgl_selesai ?>
		<td data-name="tgl_selesai"<?php echo $t_lembur->tgl_selesai->CellAttributes() ?>>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_tgl_selesai" class="form-group t_lembur_tgl_selesai">
<input type="text" data-table="t_lembur" data-field="x_tgl_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" placeholder="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->tgl_selesai->EditValue ?>"<?php echo $t_lembur->tgl_selesai->EditAttributes() ?>>
<?php if (!$t_lembur->tgl_selesai->ReadOnly && !$t_lembur->tgl_selesai->Disabled && !isset($t_lembur->tgl_selesai->EditAttrs["readonly"]) && !isset($t_lembur->tgl_selesai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lemburgrid", "x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_tgl_selesai" class="form-group t_lembur_tgl_selesai">
<input type="text" data-table="t_lembur" data-field="x_tgl_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" placeholder="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->tgl_selesai->EditValue ?>"<?php echo $t_lembur->tgl_selesai->EditAttributes() ?>>
<?php if (!$t_lembur->tgl_selesai->ReadOnly && !$t_lembur->tgl_selesai->Disabled && !isset($t_lembur->tgl_selesai->EditAttrs["readonly"]) && !isset($t_lembur->tgl_selesai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lemburgrid", "x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_tgl_selesai" class="t_lembur_tgl_selesai">
<span<?php echo $t_lembur->tgl_selesai->ViewAttributes() ?>>
<?php echo $t_lembur->tgl_selesai->ListViewValue() ?></span>
</span>
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_lembur->jam_mulai->Visible) { // jam_mulai ?>
		<td data-name="jam_mulai"<?php echo $t_lembur->jam_mulai->CellAttributes() ?>>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_jam_mulai" class="form-group t_lembur_jam_mulai">
<input type="text" data-table="t_lembur" data-field="x_jam_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" placeholder="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->jam_mulai->EditValue ?>"<?php echo $t_lembur->jam_mulai->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_jam_mulai" class="form-group t_lembur_jam_mulai">
<input type="text" data-table="t_lembur" data-field="x_jam_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" placeholder="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->jam_mulai->EditValue ?>"<?php echo $t_lembur->jam_mulai->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_jam_mulai" class="t_lembur_jam_mulai">
<span<?php echo $t_lembur->jam_mulai->ViewAttributes() ?>>
<?php echo $t_lembur->jam_mulai->ListViewValue() ?></span>
</span>
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_lembur->jam_selesai->Visible) { // jam_selesai ?>
		<td data-name="jam_selesai"<?php echo $t_lembur->jam_selesai->CellAttributes() ?>>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_jam_selesai" class="form-group t_lembur_jam_selesai">
<input type="text" data-table="t_lembur" data-field="x_jam_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" placeholder="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->jam_selesai->EditValue ?>"<?php echo $t_lembur->jam_selesai->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->OldValue) ?>">
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_jam_selesai" class="form-group t_lembur_jam_selesai">
<input type="text" data-table="t_lembur" data-field="x_jam_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" placeholder="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->jam_selesai->EditValue ?>"<?php echo $t_lembur->jam_selesai->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_lembur->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lembur_grid->RowCnt ?>_t_lembur_jam_selesai" class="t_lembur_jam_selesai">
<span<?php echo $t_lembur->jam_selesai->ViewAttributes() ?>>
<?php echo $t_lembur->jam_selesai->ListViewValue() ?></span>
</span>
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="ft_lemburgrid$x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->FormValue) ?>">
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="ft_lemburgrid$o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_lembur_grid->ListOptions->Render("body", "right", $t_lembur_grid->RowCnt);
?>
	</tr>
<?php if ($t_lembur->RowType == EW_ROWTYPE_ADD || $t_lembur->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_lemburgrid.UpdateOpts(<?php echo $t_lembur_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_lembur->CurrentAction <> "gridadd" || $t_lembur->CurrentMode == "copy")
		if (!$t_lembur_grid->Recordset->EOF) $t_lembur_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_lembur->CurrentMode == "add" || $t_lembur->CurrentMode == "copy" || $t_lembur->CurrentMode == "edit") {
		$t_lembur_grid->RowIndex = '$rowindex$';
		$t_lembur_grid->LoadDefaultValues();

		// Set row properties
		$t_lembur->ResetAttrs();
		$t_lembur->RowAttrs = array_merge($t_lembur->RowAttrs, array('data-rowindex'=>$t_lembur_grid->RowIndex, 'id'=>'r0_t_lembur', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_lembur->RowAttrs["class"], "ewTemplate");
		$t_lembur->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_lembur_grid->RenderRow();

		// Render list options
		$t_lembur_grid->RenderListOptions();
		$t_lembur_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_lembur->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_lembur_grid->ListOptions->Render("body", "left", $t_lembur_grid->RowIndex);
?>
	<?php if ($t_lembur->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<?php if ($t_lembur->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span<?php echo $t_lembur->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_lembur->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_lembur->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_lembur->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_lembur->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_lembur->pegawai_id->CurrentValue ?>"<?php echo $t_lembur->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_lembur->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_lembur_pegawai_id" class="form-group t_lembur_pegawai_id">
<span<?php echo $t_lembur->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur" data-field="x_pegawai_id" name="o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_lembur_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_lembur->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lembur->tgl_mulai->Visible) { // tgl_mulai ?>
		<td data-name="tgl_mulai">
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lembur_tgl_mulai" class="form-group t_lembur_tgl_mulai">
<input type="text" data-table="t_lembur" data-field="x_tgl_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" placeholder="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->tgl_mulai->EditValue ?>"<?php echo $t_lembur->tgl_mulai->EditAttributes() ?>>
<?php if (!$t_lembur->tgl_mulai->ReadOnly && !$t_lembur->tgl_mulai->Disabled && !isset($t_lembur->tgl_mulai->EditAttrs["readonly"]) && !isset($t_lembur->tgl_mulai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lemburgrid", "x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lembur_tgl_mulai" class="form-group t_lembur_tgl_mulai">
<span<?php echo $t_lembur->tgl_mulai->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->tgl_mulai->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_mulai" name="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" id="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_mulai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_mulai->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lembur->tgl_selesai->Visible) { // tgl_selesai ?>
		<td data-name="tgl_selesai">
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lembur_tgl_selesai" class="form-group t_lembur_tgl_selesai">
<input type="text" data-table="t_lembur" data-field="x_tgl_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" placeholder="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->tgl_selesai->EditValue ?>"<?php echo $t_lembur->tgl_selesai->EditAttributes() ?>>
<?php if (!$t_lembur->tgl_selesai->ReadOnly && !$t_lembur->tgl_selesai->Disabled && !isset($t_lembur->tgl_selesai->EditAttrs["readonly"]) && !isset($t_lembur->tgl_selesai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_lemburgrid", "x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lembur_tgl_selesai" class="form-group t_lembur_tgl_selesai">
<span<?php echo $t_lembur->tgl_selesai->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->tgl_selesai->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur" data-field="x_tgl_selesai" name="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" id="o<?php echo $t_lembur_grid->RowIndex ?>_tgl_selesai" value="<?php echo ew_HtmlEncode($t_lembur->tgl_selesai->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lembur->jam_mulai->Visible) { // jam_mulai ?>
		<td data-name="jam_mulai">
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lembur_jam_mulai" class="form-group t_lembur_jam_mulai">
<input type="text" data-table="t_lembur" data-field="x_jam_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" placeholder="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->jam_mulai->EditValue ?>"<?php echo $t_lembur->jam_mulai->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lembur_jam_mulai" class="form-group t_lembur_jam_mulai">
<span<?php echo $t_lembur->jam_mulai->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->jam_mulai->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur" data-field="x_jam_mulai" name="o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" id="o<?php echo $t_lembur_grid->RowIndex ?>_jam_mulai" value="<?php echo ew_HtmlEncode($t_lembur->jam_mulai->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lembur->jam_selesai->Visible) { // jam_selesai ?>
		<td data-name="jam_selesai">
<?php if ($t_lembur->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lembur_jam_selesai" class="form-group t_lembur_jam_selesai">
<input type="text" data-table="t_lembur" data-field="x_jam_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" placeholder="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->getPlaceHolder()) ?>" value="<?php echo $t_lembur->jam_selesai->EditValue ?>"<?php echo $t_lembur->jam_selesai->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lembur_jam_selesai" class="form-group t_lembur_jam_selesai">
<span<?php echo $t_lembur->jam_selesai->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lembur->jam_selesai->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="x<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lembur" data-field="x_jam_selesai" name="o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" id="o<?php echo $t_lembur_grid->RowIndex ?>_jam_selesai" value="<?php echo ew_HtmlEncode($t_lembur->jam_selesai->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_lembur_grid->ListOptions->Render("body", "right", $t_lembur_grid->RowCnt);
?>
<script type="text/javascript">
ft_lemburgrid.UpdateOpts(<?php echo $t_lembur_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_lembur->CurrentMode == "add" || $t_lembur->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_lembur_grid->FormKeyCountName ?>" id="<?php echo $t_lembur_grid->FormKeyCountName ?>" value="<?php echo $t_lembur_grid->KeyCount ?>">
<?php echo $t_lembur_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_lembur->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_lembur_grid->FormKeyCountName ?>" id="<?php echo $t_lembur_grid->FormKeyCountName ?>" value="<?php echo $t_lembur_grid->KeyCount ?>">
<?php echo $t_lembur_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_lembur->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_lemburgrid">
</div>
<?php

// Close recordset
if ($t_lembur_grid->Recordset)
	$t_lembur_grid->Recordset->Close();
?>
<?php if ($t_lembur_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_lembur_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_lembur_grid->TotalRecs == 0 && $t_lembur->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_lembur_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_lembur->Export == "") { ?>
<script type="text/javascript">
ft_lemburgrid.Init();
</script>
<?php } ?>
<?php
$t_lembur_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_lembur_grid->Page_Terminate();
?>
