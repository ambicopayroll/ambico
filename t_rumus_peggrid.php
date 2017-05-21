<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_rumus_peg_grid)) $t_rumus_peg_grid = new ct_rumus_peg_grid();

// Page init
$t_rumus_peg_grid->Page_Init();

// Page main
$t_rumus_peg_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus_peg_grid->Page_Render();
?>
<?php if ($t_rumus_peg->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_rumus_peggrid = new ew_Form("ft_rumus_peggrid", "grid");
ft_rumus_peggrid.FormKeyCountName = '<?php echo $t_rumus_peg_grid->FormKeyCountName ?>';

// Validate form
ft_rumus_peggrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus_peg->pegawai_id->FldCaption(), $t_rumus_peg->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus_peg->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rumus_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus_peg->rumus_id->FldCaption(), $t_rumus_peg->rumus_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_t_jabatan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus_peg->t_jabatan->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_rumus_peggrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "rumus_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "t_jabatan", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_rumus_peggrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumus_peggrid.ValidateRequired = true;
<?php } else { ?>
ft_rumus_peggrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumus_peggrid.Lists["x_rumus_id"] = {"LinkField":"x_rumus_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rumus_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_rumus"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_rumus_peg->CurrentAction == "gridadd") {
	if ($t_rumus_peg->CurrentMode == "copy") {
		$bSelectLimit = $t_rumus_peg_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_rumus_peg_grid->TotalRecs = $t_rumus_peg->SelectRecordCount();
			$t_rumus_peg_grid->Recordset = $t_rumus_peg_grid->LoadRecordset($t_rumus_peg_grid->StartRec-1, $t_rumus_peg_grid->DisplayRecs);
		} else {
			if ($t_rumus_peg_grid->Recordset = $t_rumus_peg_grid->LoadRecordset())
				$t_rumus_peg_grid->TotalRecs = $t_rumus_peg_grid->Recordset->RecordCount();
		}
		$t_rumus_peg_grid->StartRec = 1;
		$t_rumus_peg_grid->DisplayRecs = $t_rumus_peg_grid->TotalRecs;
	} else {
		$t_rumus_peg->CurrentFilter = "0=1";
		$t_rumus_peg_grid->StartRec = 1;
		$t_rumus_peg_grid->DisplayRecs = $t_rumus_peg->GridAddRowCount;
	}
	$t_rumus_peg_grid->TotalRecs = $t_rumus_peg_grid->DisplayRecs;
	$t_rumus_peg_grid->StopRec = $t_rumus_peg_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_rumus_peg_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_rumus_peg_grid->TotalRecs <= 0)
			$t_rumus_peg_grid->TotalRecs = $t_rumus_peg->SelectRecordCount();
	} else {
		if (!$t_rumus_peg_grid->Recordset && ($t_rumus_peg_grid->Recordset = $t_rumus_peg_grid->LoadRecordset()))
			$t_rumus_peg_grid->TotalRecs = $t_rumus_peg_grid->Recordset->RecordCount();
	}
	$t_rumus_peg_grid->StartRec = 1;
	$t_rumus_peg_grid->DisplayRecs = $t_rumus_peg_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_rumus_peg_grid->Recordset = $t_rumus_peg_grid->LoadRecordset($t_rumus_peg_grid->StartRec-1, $t_rumus_peg_grid->DisplayRecs);

	// Set no record found message
	if ($t_rumus_peg->CurrentAction == "" && $t_rumus_peg_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_rumus_peg_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_rumus_peg_grid->SearchWhere == "0=101")
			$t_rumus_peg_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_rumus_peg_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_rumus_peg_grid->RenderOtherOptions();
?>
<?php $t_rumus_peg_grid->ShowPageHeader(); ?>
<?php
$t_rumus_peg_grid->ShowMessage();
?>
<?php if ($t_rumus_peg_grid->TotalRecs > 0 || $t_rumus_peg->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_rumus_peg">
<div id="ft_rumus_peggrid" class="ewForm form-inline">
<?php if ($t_rumus_peg_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_rumus_peg_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_rumus_peg" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_rumus_peggrid" class="table ewTable">
<?php echo $t_rumus_peg->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_rumus_peg_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_rumus_peg_grid->RenderListOptions();

// Render list options (header, left)
$t_rumus_peg_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_rumus_peg->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_rumus_peg->SortUrl($t_rumus_peg->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_rumus_peg_pegawai_id" class="t_rumus_peg_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_rumus_peg->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_rumus_peg_pegawai_id" class="t_rumus_peg_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus_peg->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus_peg->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus_peg->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus_peg->rumus_id->Visible) { // rumus_id ?>
	<?php if ($t_rumus_peg->SortUrl($t_rumus_peg->rumus_id) == "") { ?>
		<th data-name="rumus_id"><div id="elh_t_rumus_peg_rumus_id" class="t_rumus_peg_rumus_id"><div class="ewTableHeaderCaption"><?php echo $t_rumus_peg->rumus_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rumus_id"><div><div id="elh_t_rumus_peg_rumus_id" class="t_rumus_peg_rumus_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus_peg->rumus_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus_peg->rumus_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus_peg->rumus_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus_peg->t_jabatan->Visible) { // t_jabatan ?>
	<?php if ($t_rumus_peg->SortUrl($t_rumus_peg->t_jabatan) == "") { ?>
		<th data-name="t_jabatan"><div id="elh_t_rumus_peg_t_jabatan" class="t_rumus_peg_t_jabatan"><div class="ewTableHeaderCaption"><?php echo $t_rumus_peg->t_jabatan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="t_jabatan"><div><div id="elh_t_rumus_peg_t_jabatan" class="t_rumus_peg_t_jabatan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus_peg->t_jabatan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus_peg->t_jabatan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus_peg->t_jabatan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_rumus_peg_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_rumus_peg_grid->StartRec = 1;
$t_rumus_peg_grid->StopRec = $t_rumus_peg_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_rumus_peg_grid->FormKeyCountName) && ($t_rumus_peg->CurrentAction == "gridadd" || $t_rumus_peg->CurrentAction == "gridedit" || $t_rumus_peg->CurrentAction == "F")) {
		$t_rumus_peg_grid->KeyCount = $objForm->GetValue($t_rumus_peg_grid->FormKeyCountName);
		$t_rumus_peg_grid->StopRec = $t_rumus_peg_grid->StartRec + $t_rumus_peg_grid->KeyCount - 1;
	}
}
$t_rumus_peg_grid->RecCnt = $t_rumus_peg_grid->StartRec - 1;
if ($t_rumus_peg_grid->Recordset && !$t_rumus_peg_grid->Recordset->EOF) {
	$t_rumus_peg_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_rumus_peg_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_rumus_peg_grid->StartRec > 1)
		$t_rumus_peg_grid->Recordset->Move($t_rumus_peg_grid->StartRec - 1);
} elseif (!$t_rumus_peg->AllowAddDeleteRow && $t_rumus_peg_grid->StopRec == 0) {
	$t_rumus_peg_grid->StopRec = $t_rumus_peg->GridAddRowCount;
}

// Initialize aggregate
$t_rumus_peg->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_rumus_peg->ResetAttrs();
$t_rumus_peg_grid->RenderRow();
if ($t_rumus_peg->CurrentAction == "gridadd")
	$t_rumus_peg_grid->RowIndex = 0;
if ($t_rumus_peg->CurrentAction == "gridedit")
	$t_rumus_peg_grid->RowIndex = 0;
while ($t_rumus_peg_grid->RecCnt < $t_rumus_peg_grid->StopRec) {
	$t_rumus_peg_grid->RecCnt++;
	if (intval($t_rumus_peg_grid->RecCnt) >= intval($t_rumus_peg_grid->StartRec)) {
		$t_rumus_peg_grid->RowCnt++;
		if ($t_rumus_peg->CurrentAction == "gridadd" || $t_rumus_peg->CurrentAction == "gridedit" || $t_rumus_peg->CurrentAction == "F") {
			$t_rumus_peg_grid->RowIndex++;
			$objForm->Index = $t_rumus_peg_grid->RowIndex;
			if ($objForm->HasValue($t_rumus_peg_grid->FormActionName))
				$t_rumus_peg_grid->RowAction = strval($objForm->GetValue($t_rumus_peg_grid->FormActionName));
			elseif ($t_rumus_peg->CurrentAction == "gridadd")
				$t_rumus_peg_grid->RowAction = "insert";
			else
				$t_rumus_peg_grid->RowAction = "";
		}

		// Set up key count
		$t_rumus_peg_grid->KeyCount = $t_rumus_peg_grid->RowIndex;

		// Init row class and style
		$t_rumus_peg->ResetAttrs();
		$t_rumus_peg->CssClass = "";
		if ($t_rumus_peg->CurrentAction == "gridadd") {
			if ($t_rumus_peg->CurrentMode == "copy") {
				$t_rumus_peg_grid->LoadRowValues($t_rumus_peg_grid->Recordset); // Load row values
				$t_rumus_peg_grid->SetRecordKey($t_rumus_peg_grid->RowOldKey, $t_rumus_peg_grid->Recordset); // Set old record key
			} else {
				$t_rumus_peg_grid->LoadDefaultValues(); // Load default values
				$t_rumus_peg_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_rumus_peg_grid->LoadRowValues($t_rumus_peg_grid->Recordset); // Load row values
		}
		$t_rumus_peg->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_rumus_peg->CurrentAction == "gridadd") // Grid add
			$t_rumus_peg->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_rumus_peg->CurrentAction == "gridadd" && $t_rumus_peg->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_rumus_peg_grid->RestoreCurrentRowFormValues($t_rumus_peg_grid->RowIndex); // Restore form values
		if ($t_rumus_peg->CurrentAction == "gridedit") { // Grid edit
			if ($t_rumus_peg->EventCancelled) {
				$t_rumus_peg_grid->RestoreCurrentRowFormValues($t_rumus_peg_grid->RowIndex); // Restore form values
			}
			if ($t_rumus_peg_grid->RowAction == "insert")
				$t_rumus_peg->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_rumus_peg->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_rumus_peg->CurrentAction == "gridedit" && ($t_rumus_peg->RowType == EW_ROWTYPE_EDIT || $t_rumus_peg->RowType == EW_ROWTYPE_ADD) && $t_rumus_peg->EventCancelled) // Update failed
			$t_rumus_peg_grid->RestoreCurrentRowFormValues($t_rumus_peg_grid->RowIndex); // Restore form values
		if ($t_rumus_peg->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_rumus_peg_grid->EditRowCnt++;
		if ($t_rumus_peg->CurrentAction == "F") // Confirm row
			$t_rumus_peg_grid->RestoreCurrentRowFormValues($t_rumus_peg_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_rumus_peg->RowAttrs = array_merge($t_rumus_peg->RowAttrs, array('data-rowindex'=>$t_rumus_peg_grid->RowCnt, 'id'=>'r' . $t_rumus_peg_grid->RowCnt . '_t_rumus_peg', 'data-rowtype'=>$t_rumus_peg->RowType));

		// Render row
		$t_rumus_peg_grid->RenderRow();

		// Render list options
		$t_rumus_peg_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_rumus_peg_grid->RowAction <> "delete" && $t_rumus_peg_grid->RowAction <> "insertdelete" && !($t_rumus_peg_grid->RowAction == "insert" && $t_rumus_peg->CurrentAction == "F" && $t_rumus_peg_grid->EmptyRow())) {
?>
	<tr<?php echo $t_rumus_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus_peg_grid->ListOptions->Render("body", "left", $t_rumus_peg_grid->RowCnt);
?>
	<?php if ($t_rumus_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_rumus_peg->pegawai_id->CellAttributes() ?>>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_rumus_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<span<?php echo $t_rumus_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<input type="text" data-table="t_rumus_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_rumus_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<span<?php echo $t_rumus_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<input type="text" data-table="t_rumus_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_pegawai_id" class="t_rumus_peg_pegawai_id">
<span<?php echo $t_rumus_peg->pegawai_id->ViewAttributes() ?>>
<?php echo $t_rumus_peg->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_rumus_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="ft_rumus_peggrid$x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="ft_rumus_peggrid$x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="ft_rumus_peggrid$o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="ft_rumus_peggrid$o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_rumus_peg_grid->PageObjName . "_row_" . $t_rumus_peg_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_peg_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_peg_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_peg_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_peg_id->CurrentValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_peg_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_peg_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_peg_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_peg_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_EDIT || $t_rumus_peg->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_peg_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_peg_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_peg_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_peg_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_rumus_peg->rumus_id->Visible) { // rumus_id ?>
		<td data-name="rumus_id"<?php echo $t_rumus_peg->rumus_id->CellAttributes() ?>>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_rumus_id" class="form-group t_rumus_peg_rumus_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id"><?php echo (strval($t_rumus_peg->rumus_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus_peg->rumus_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus_peg->rumus_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus_peg->rumus_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo $t_rumus_peg->rumus_id->CurrentValue ?>"<?php echo $t_rumus_peg->rumus_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="s_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo $t_rumus_peg->rumus_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_rumus_id" class="form-group t_rumus_peg_rumus_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id"><?php echo (strval($t_rumus_peg->rumus_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus_peg->rumus_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus_peg->rumus_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus_peg->rumus_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo $t_rumus_peg->rumus_id->CurrentValue ?>"<?php echo $t_rumus_peg->rumus_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="s_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo $t_rumus_peg->rumus_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_rumus_id" class="t_rumus_peg_rumus_id">
<span<?php echo $t_rumus_peg->rumus_id->ViewAttributes() ?>>
<?php echo $t_rumus_peg->rumus_id->ListViewValue() ?></span>
</span>
<?php if ($t_rumus_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="ft_rumus_peggrid$x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="ft_rumus_peggrid$x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="ft_rumus_peggrid$o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="ft_rumus_peggrid$o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus_peg->t_jabatan->Visible) { // t_jabatan ?>
		<td data-name="t_jabatan"<?php echo $t_rumus_peg->t_jabatan->CellAttributes() ?>>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_t_jabatan" class="form-group t_rumus_peg_t_jabatan">
<input type="text" data-table="t_rumus_peg" data-field="x_t_jabatan" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->getPlaceHolder()) ?>" value="<?php echo $t_rumus_peg->t_jabatan->EditValue ?>"<?php echo $t_rumus_peg->t_jabatan->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_t_jabatan" class="form-group t_rumus_peg_t_jabatan">
<input type="text" data-table="t_rumus_peg" data-field="x_t_jabatan" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->getPlaceHolder()) ?>" value="<?php echo $t_rumus_peg->t_jabatan->EditValue ?>"<?php echo $t_rumus_peg->t_jabatan->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_peg_grid->RowCnt ?>_t_rumus_peg_t_jabatan" class="t_rumus_peg_t_jabatan">
<span<?php echo $t_rumus_peg->t_jabatan->ViewAttributes() ?>>
<?php echo $t_rumus_peg->t_jabatan->ListViewValue() ?></span>
</span>
<?php if ($t_rumus_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->FormValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="ft_rumus_peggrid$x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="ft_rumus_peggrid$x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->FormValue) ?>">
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="ft_rumus_peggrid$o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="ft_rumus_peggrid$o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus_peg_grid->ListOptions->Render("body", "right", $t_rumus_peg_grid->RowCnt);
?>
	</tr>
<?php if ($t_rumus_peg->RowType == EW_ROWTYPE_ADD || $t_rumus_peg->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_rumus_peggrid.UpdateOpts(<?php echo $t_rumus_peg_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_rumus_peg->CurrentAction <> "gridadd" || $t_rumus_peg->CurrentMode == "copy")
		if (!$t_rumus_peg_grid->Recordset->EOF) $t_rumus_peg_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_rumus_peg->CurrentMode == "add" || $t_rumus_peg->CurrentMode == "copy" || $t_rumus_peg->CurrentMode == "edit") {
		$t_rumus_peg_grid->RowIndex = '$rowindex$';
		$t_rumus_peg_grid->LoadDefaultValues();

		// Set row properties
		$t_rumus_peg->ResetAttrs();
		$t_rumus_peg->RowAttrs = array_merge($t_rumus_peg->RowAttrs, array('data-rowindex'=>$t_rumus_peg_grid->RowIndex, 'id'=>'r0_t_rumus_peg', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_rumus_peg->RowAttrs["class"], "ewTemplate");
		$t_rumus_peg->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_rumus_peg_grid->RenderRow();

		// Render list options
		$t_rumus_peg_grid->RenderListOptions();
		$t_rumus_peg_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_rumus_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus_peg_grid->ListOptions->Render("body", "left", $t_rumus_peg_grid->RowIndex);
?>
	<?php if ($t_rumus_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_rumus_peg->CurrentAction <> "F") { ?>
<?php if ($t_rumus_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<span<?php echo $t_rumus_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<input type="text" data-table="t_rumus_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_rumus_peg_pegawai_id" class="form-group t_rumus_peg_pegawai_id">
<span<?php echo $t_rumus_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_pegawai_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus_peg->rumus_id->Visible) { // rumus_id ?>
		<td data-name="rumus_id">
<?php if ($t_rumus_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_rumus_peg_rumus_id" class="form-group t_rumus_peg_rumus_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id"><?php echo (strval($t_rumus_peg->rumus_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus_peg->rumus_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus_peg->rumus_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus_peg->rumus_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo $t_rumus_peg->rumus_id->CurrentValue ?>"<?php echo $t_rumus_peg->rumus_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="s_x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo $t_rumus_peg->rumus_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_rumus_peg_rumus_id" class="form-group t_rumus_peg_rumus_id">
<span<?php echo $t_rumus_peg->rumus_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus_peg->rumus_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_rumus_id" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus_peg->rumus_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus_peg->t_jabatan->Visible) { // t_jabatan ?>
		<td data-name="t_jabatan">
<?php if ($t_rumus_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_rumus_peg_t_jabatan" class="form-group t_rumus_peg_t_jabatan">
<input type="text" data-table="t_rumus_peg" data-field="x_t_jabatan" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->getPlaceHolder()) ?>" value="<?php echo $t_rumus_peg->t_jabatan->EditValue ?>"<?php echo $t_rumus_peg->t_jabatan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_rumus_peg_t_jabatan" class="form-group t_rumus_peg_t_jabatan">
<span<?php echo $t_rumus_peg->t_jabatan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus_peg->t_jabatan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="x<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus_peg" data-field="x_t_jabatan" name="o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" id="o<?php echo $t_rumus_peg_grid->RowIndex ?>_t_jabatan" value="<?php echo ew_HtmlEncode($t_rumus_peg->t_jabatan->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus_peg_grid->ListOptions->Render("body", "right", $t_rumus_peg_grid->RowCnt);
?>
<script type="text/javascript">
ft_rumus_peggrid.UpdateOpts(<?php echo $t_rumus_peg_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_rumus_peg->CurrentMode == "add" || $t_rumus_peg->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_rumus_peg_grid->FormKeyCountName ?>" id="<?php echo $t_rumus_peg_grid->FormKeyCountName ?>" value="<?php echo $t_rumus_peg_grid->KeyCount ?>">
<?php echo $t_rumus_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_rumus_peg->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_rumus_peg_grid->FormKeyCountName ?>" id="<?php echo $t_rumus_peg_grid->FormKeyCountName ?>" value="<?php echo $t_rumus_peg_grid->KeyCount ?>">
<?php echo $t_rumus_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_rumus_peg->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_rumus_peggrid">
</div>
<?php

// Close recordset
if ($t_rumus_peg_grid->Recordset)
	$t_rumus_peg_grid->Recordset->Close();
?>
<?php if ($t_rumus_peg_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_rumus_peg_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_rumus_peg_grid->TotalRecs == 0 && $t_rumus_peg->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_rumus_peg_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_rumus_peg->Export == "") { ?>
<script type="text/javascript">
ft_rumus_peggrid.Init();
</script>
<?php } ?>
<?php
$t_rumus_peg_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_rumus_peg_grid->Page_Terminate();
?>
