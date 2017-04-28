<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_rumus2_peg_grid)) $t_rumus2_peg_grid = new ct_rumus2_peg_grid();

// Page init
$t_rumus2_peg_grid->Page_Init();

// Page main
$t_rumus2_peg_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus2_peg_grid->Page_Render();
?>
<?php if ($t_rumus2_peg->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_rumus2_peggrid = new ew_Form("ft_rumus2_peggrid", "grid");
ft_rumus2_peggrid.FormKeyCountName = '<?php echo $t_rumus2_peg_grid->FormKeyCountName ?>';

// Validate form
ft_rumus2_peggrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->pegawai_id->FldCaption(), $t_rumus2_peg->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2_peg->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rumus2_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->rumus2_id->FldCaption(), $t_rumus2_peg->rumus2_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->gp->FldCaption(), $t_rumus2_peg->gp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2_peg->gp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tj");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->tj->FldCaption(), $t_rumus2_peg->tj->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tj");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2_peg->tj->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_rumus2_peggrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "rumus2_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "gp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tj", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_rumus2_peggrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumus2_peggrid.ValidateRequired = true;
<?php } else { ?>
ft_rumus2_peggrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumus2_peggrid.Lists["x_rumus2_id"] = {"LinkField":"x_rumus2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rumus2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_rumus2"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_rumus2_peg->CurrentAction == "gridadd") {
	if ($t_rumus2_peg->CurrentMode == "copy") {
		$bSelectLimit = $t_rumus2_peg_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_rumus2_peg_grid->TotalRecs = $t_rumus2_peg->SelectRecordCount();
			$t_rumus2_peg_grid->Recordset = $t_rumus2_peg_grid->LoadRecordset($t_rumus2_peg_grid->StartRec-1, $t_rumus2_peg_grid->DisplayRecs);
		} else {
			if ($t_rumus2_peg_grid->Recordset = $t_rumus2_peg_grid->LoadRecordset())
				$t_rumus2_peg_grid->TotalRecs = $t_rumus2_peg_grid->Recordset->RecordCount();
		}
		$t_rumus2_peg_grid->StartRec = 1;
		$t_rumus2_peg_grid->DisplayRecs = $t_rumus2_peg_grid->TotalRecs;
	} else {
		$t_rumus2_peg->CurrentFilter = "0=1";
		$t_rumus2_peg_grid->StartRec = 1;
		$t_rumus2_peg_grid->DisplayRecs = $t_rumus2_peg->GridAddRowCount;
	}
	$t_rumus2_peg_grid->TotalRecs = $t_rumus2_peg_grid->DisplayRecs;
	$t_rumus2_peg_grid->StopRec = $t_rumus2_peg_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_rumus2_peg_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_rumus2_peg_grid->TotalRecs <= 0)
			$t_rumus2_peg_grid->TotalRecs = $t_rumus2_peg->SelectRecordCount();
	} else {
		if (!$t_rumus2_peg_grid->Recordset && ($t_rumus2_peg_grid->Recordset = $t_rumus2_peg_grid->LoadRecordset()))
			$t_rumus2_peg_grid->TotalRecs = $t_rumus2_peg_grid->Recordset->RecordCount();
	}
	$t_rumus2_peg_grid->StartRec = 1;
	$t_rumus2_peg_grid->DisplayRecs = $t_rumus2_peg_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_rumus2_peg_grid->Recordset = $t_rumus2_peg_grid->LoadRecordset($t_rumus2_peg_grid->StartRec-1, $t_rumus2_peg_grid->DisplayRecs);

	// Set no record found message
	if ($t_rumus2_peg->CurrentAction == "" && $t_rumus2_peg_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_rumus2_peg_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_rumus2_peg_grid->SearchWhere == "0=101")
			$t_rumus2_peg_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_rumus2_peg_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_rumus2_peg_grid->RenderOtherOptions();
?>
<?php $t_rumus2_peg_grid->ShowPageHeader(); ?>
<?php
$t_rumus2_peg_grid->ShowMessage();
?>
<?php if ($t_rumus2_peg_grid->TotalRecs > 0 || $t_rumus2_peg->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_rumus2_peg">
<div id="ft_rumus2_peggrid" class="ewForm form-inline">
<?php if ($t_rumus2_peg_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_rumus2_peg_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_rumus2_peg" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_rumus2_peggrid" class="table ewTable">
<?php echo $t_rumus2_peg->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_rumus2_peg_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_rumus2_peg_grid->RenderListOptions();

// Render list options (header, left)
$t_rumus2_peg_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_rumus2_peg->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_rumus2_peg->SortUrl($t_rumus2_peg->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_rumus2_peg_pegawai_id" class="t_rumus2_peg_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_rumus2_peg_pegawai_id" class="t_rumus2_peg_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus2_peg->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus2_peg->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus2_peg->rumus2_id->Visible) { // rumus2_id ?>
	<?php if ($t_rumus2_peg->SortUrl($t_rumus2_peg->rumus2_id) == "") { ?>
		<th data-name="rumus2_id"><div id="elh_t_rumus2_peg_rumus2_id" class="t_rumus2_peg_rumus2_id"><div class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->rumus2_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rumus2_id"><div><div id="elh_t_rumus2_peg_rumus2_id" class="t_rumus2_peg_rumus2_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->rumus2_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus2_peg->rumus2_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus2_peg->rumus2_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus2_peg->gp->Visible) { // gp ?>
	<?php if ($t_rumus2_peg->SortUrl($t_rumus2_peg->gp) == "") { ?>
		<th data-name="gp"><div id="elh_t_rumus2_peg_gp" class="t_rumus2_peg_gp"><div class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->gp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gp"><div><div id="elh_t_rumus2_peg_gp" class="t_rumus2_peg_gp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->gp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus2_peg->gp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus2_peg->gp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus2_peg->tj->Visible) { // tj ?>
	<?php if ($t_rumus2_peg->SortUrl($t_rumus2_peg->tj) == "") { ?>
		<th data-name="tj"><div id="elh_t_rumus2_peg_tj" class="t_rumus2_peg_tj"><div class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->tj->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tj"><div><div id="elh_t_rumus2_peg_tj" class="t_rumus2_peg_tj">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus2_peg->tj->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus2_peg->tj->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus2_peg->tj->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_rumus2_peg_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_rumus2_peg_grid->StartRec = 1;
$t_rumus2_peg_grid->StopRec = $t_rumus2_peg_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_rumus2_peg_grid->FormKeyCountName) && ($t_rumus2_peg->CurrentAction == "gridadd" || $t_rumus2_peg->CurrentAction == "gridedit" || $t_rumus2_peg->CurrentAction == "F")) {
		$t_rumus2_peg_grid->KeyCount = $objForm->GetValue($t_rumus2_peg_grid->FormKeyCountName);
		$t_rumus2_peg_grid->StopRec = $t_rumus2_peg_grid->StartRec + $t_rumus2_peg_grid->KeyCount - 1;
	}
}
$t_rumus2_peg_grid->RecCnt = $t_rumus2_peg_grid->StartRec - 1;
if ($t_rumus2_peg_grid->Recordset && !$t_rumus2_peg_grid->Recordset->EOF) {
	$t_rumus2_peg_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_rumus2_peg_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_rumus2_peg_grid->StartRec > 1)
		$t_rumus2_peg_grid->Recordset->Move($t_rumus2_peg_grid->StartRec - 1);
} elseif (!$t_rumus2_peg->AllowAddDeleteRow && $t_rumus2_peg_grid->StopRec == 0) {
	$t_rumus2_peg_grid->StopRec = $t_rumus2_peg->GridAddRowCount;
}

// Initialize aggregate
$t_rumus2_peg->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_rumus2_peg->ResetAttrs();
$t_rumus2_peg_grid->RenderRow();
if ($t_rumus2_peg->CurrentAction == "gridadd")
	$t_rumus2_peg_grid->RowIndex = 0;
if ($t_rumus2_peg->CurrentAction == "gridedit")
	$t_rumus2_peg_grid->RowIndex = 0;
while ($t_rumus2_peg_grid->RecCnt < $t_rumus2_peg_grid->StopRec) {
	$t_rumus2_peg_grid->RecCnt++;
	if (intval($t_rumus2_peg_grid->RecCnt) >= intval($t_rumus2_peg_grid->StartRec)) {
		$t_rumus2_peg_grid->RowCnt++;
		if ($t_rumus2_peg->CurrentAction == "gridadd" || $t_rumus2_peg->CurrentAction == "gridedit" || $t_rumus2_peg->CurrentAction == "F") {
			$t_rumus2_peg_grid->RowIndex++;
			$objForm->Index = $t_rumus2_peg_grid->RowIndex;
			if ($objForm->HasValue($t_rumus2_peg_grid->FormActionName))
				$t_rumus2_peg_grid->RowAction = strval($objForm->GetValue($t_rumus2_peg_grid->FormActionName));
			elseif ($t_rumus2_peg->CurrentAction == "gridadd")
				$t_rumus2_peg_grid->RowAction = "insert";
			else
				$t_rumus2_peg_grid->RowAction = "";
		}

		// Set up key count
		$t_rumus2_peg_grid->KeyCount = $t_rumus2_peg_grid->RowIndex;

		// Init row class and style
		$t_rumus2_peg->ResetAttrs();
		$t_rumus2_peg->CssClass = "";
		if ($t_rumus2_peg->CurrentAction == "gridadd") {
			if ($t_rumus2_peg->CurrentMode == "copy") {
				$t_rumus2_peg_grid->LoadRowValues($t_rumus2_peg_grid->Recordset); // Load row values
				$t_rumus2_peg_grid->SetRecordKey($t_rumus2_peg_grid->RowOldKey, $t_rumus2_peg_grid->Recordset); // Set old record key
			} else {
				$t_rumus2_peg_grid->LoadDefaultValues(); // Load default values
				$t_rumus2_peg_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_rumus2_peg_grid->LoadRowValues($t_rumus2_peg_grid->Recordset); // Load row values
		}
		$t_rumus2_peg->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_rumus2_peg->CurrentAction == "gridadd") // Grid add
			$t_rumus2_peg->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_rumus2_peg->CurrentAction == "gridadd" && $t_rumus2_peg->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_rumus2_peg_grid->RestoreCurrentRowFormValues($t_rumus2_peg_grid->RowIndex); // Restore form values
		if ($t_rumus2_peg->CurrentAction == "gridedit") { // Grid edit
			if ($t_rumus2_peg->EventCancelled) {
				$t_rumus2_peg_grid->RestoreCurrentRowFormValues($t_rumus2_peg_grid->RowIndex); // Restore form values
			}
			if ($t_rumus2_peg_grid->RowAction == "insert")
				$t_rumus2_peg->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_rumus2_peg->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_rumus2_peg->CurrentAction == "gridedit" && ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT || $t_rumus2_peg->RowType == EW_ROWTYPE_ADD) && $t_rumus2_peg->EventCancelled) // Update failed
			$t_rumus2_peg_grid->RestoreCurrentRowFormValues($t_rumus2_peg_grid->RowIndex); // Restore form values
		if ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_rumus2_peg_grid->EditRowCnt++;
		if ($t_rumus2_peg->CurrentAction == "F") // Confirm row
			$t_rumus2_peg_grid->RestoreCurrentRowFormValues($t_rumus2_peg_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_rumus2_peg->RowAttrs = array_merge($t_rumus2_peg->RowAttrs, array('data-rowindex'=>$t_rumus2_peg_grid->RowCnt, 'id'=>'r' . $t_rumus2_peg_grid->RowCnt . '_t_rumus2_peg', 'data-rowtype'=>$t_rumus2_peg->RowType));

		// Render row
		$t_rumus2_peg_grid->RenderRow();

		// Render list options
		$t_rumus2_peg_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_rumus2_peg_grid->RowAction <> "delete" && $t_rumus2_peg_grid->RowAction <> "insertdelete" && !($t_rumus2_peg_grid->RowAction == "insert" && $t_rumus2_peg->CurrentAction == "F" && $t_rumus2_peg_grid->EmptyRow())) {
?>
	<tr<?php echo $t_rumus2_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus2_peg_grid->ListOptions->Render("body", "left", $t_rumus2_peg_grid->RowCnt);
?>
	<?php if ($t_rumus2_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_rumus2_peg->pegawai_id->CellAttributes() ?>>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_rumus2_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<span<?php echo $t_rumus2_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<input type="text" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus2_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_rumus2_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<span<?php echo $t_rumus2_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<input type="text" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus2_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_pegawai_id" class="t_rumus2_peg_pegawai_id">
<span<?php echo $t_rumus2_peg->pegawai_id->ViewAttributes() ?>>
<?php echo $t_rumus2_peg->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_rumus2_peg_grid->PageObjName . "_row_" . $t_rumus2_peg_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_peg_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_peg_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_peg_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_peg_id->CurrentValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_peg_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_peg_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_peg_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_peg_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT || $t_rumus2_peg->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_peg_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_peg_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_peg_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_peg_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_rumus2_peg->rumus2_id->Visible) { // rumus2_id ?>
		<td data-name="rumus2_id"<?php echo $t_rumus2_peg->rumus2_id->CellAttributes() ?>>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_rumus2_id" class="form-group t_rumus2_peg_rumus2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id"><?php echo (strval($t_rumus2_peg->rumus2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus2_peg->rumus2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus2_peg->rumus2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus2_peg->rumus2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->CurrentValue ?>"<?php echo $t_rumus2_peg->rumus2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="s_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_rumus2_id" class="form-group t_rumus2_peg_rumus2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id"><?php echo (strval($t_rumus2_peg->rumus2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus2_peg->rumus2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus2_peg->rumus2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus2_peg->rumus2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->CurrentValue ?>"<?php echo $t_rumus2_peg->rumus2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="s_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_rumus2_id" class="t_rumus2_peg_rumus2_id">
<span<?php echo $t_rumus2_peg->rumus2_id->ViewAttributes() ?>>
<?php echo $t_rumus2_peg->rumus2_id->ListViewValue() ?></span>
</span>
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus2_peg->gp->Visible) { // gp ?>
		<td data-name="gp"<?php echo $t_rumus2_peg->gp->CellAttributes() ?>>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_gp" class="form-group t_rumus2_peg_gp">
<input type="text" data-table="t_rumus2_peg" data-field="x_gp" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->gp->EditValue ?>"<?php echo $t_rumus2_peg->gp->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_gp" class="form-group t_rumus2_peg_gp">
<input type="text" data-table="t_rumus2_peg" data-field="x_gp" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->gp->EditValue ?>"<?php echo $t_rumus2_peg->gp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_gp" class="t_rumus2_peg_gp">
<span<?php echo $t_rumus2_peg->gp->ViewAttributes() ?>>
<?php echo $t_rumus2_peg->gp->ListViewValue() ?></span>
</span>
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus2_peg->tj->Visible) { // tj ?>
		<td data-name="tj"<?php echo $t_rumus2_peg->tj->CellAttributes() ?>>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_tj" class="form-group t_rumus2_peg_tj">
<input type="text" data-table="t_rumus2_peg" data-field="x_tj" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->tj->EditValue ?>"<?php echo $t_rumus2_peg->tj->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_tj" class="form-group t_rumus2_peg_tj">
<input type="text" data-table="t_rumus2_peg" data-field="x_tj" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->tj->EditValue ?>"<?php echo $t_rumus2_peg->tj->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus2_peg_grid->RowCnt ?>_t_rumus2_peg_tj" class="t_rumus2_peg_tj">
<span<?php echo $t_rumus2_peg->tj->ViewAttributes() ?>>
<?php echo $t_rumus2_peg->tj->ListViewValue() ?></span>
</span>
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="ft_rumus2_peggrid$x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->FormValue) ?>">
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="ft_rumus2_peggrid$o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus2_peg_grid->ListOptions->Render("body", "right", $t_rumus2_peg_grid->RowCnt);
?>
	</tr>
<?php if ($t_rumus2_peg->RowType == EW_ROWTYPE_ADD || $t_rumus2_peg->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_rumus2_peggrid.UpdateOpts(<?php echo $t_rumus2_peg_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_rumus2_peg->CurrentAction <> "gridadd" || $t_rumus2_peg->CurrentMode == "copy")
		if (!$t_rumus2_peg_grid->Recordset->EOF) $t_rumus2_peg_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_rumus2_peg->CurrentMode == "add" || $t_rumus2_peg->CurrentMode == "copy" || $t_rumus2_peg->CurrentMode == "edit") {
		$t_rumus2_peg_grid->RowIndex = '$rowindex$';
		$t_rumus2_peg_grid->LoadDefaultValues();

		// Set row properties
		$t_rumus2_peg->ResetAttrs();
		$t_rumus2_peg->RowAttrs = array_merge($t_rumus2_peg->RowAttrs, array('data-rowindex'=>$t_rumus2_peg_grid->RowIndex, 'id'=>'r0_t_rumus2_peg', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_rumus2_peg->RowAttrs["class"], "ewTemplate");
		$t_rumus2_peg->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_rumus2_peg_grid->RenderRow();

		// Render list options
		$t_rumus2_peg_grid->RenderListOptions();
		$t_rumus2_peg_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_rumus2_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus2_peg_grid->ListOptions->Render("body", "left", $t_rumus2_peg_grid->RowIndex);
?>
	<?php if ($t_rumus2_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<?php if ($t_rumus2_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<span<?php echo $t_rumus2_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<input type="text" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus2_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_rumus2_peg_pegawai_id" class="form-group t_rumus2_peg_pegawai_id">
<span<?php echo $t_rumus2_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus2_peg->rumus2_id->Visible) { // rumus2_id ?>
		<td data-name="rumus2_id">
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_rumus2_peg_rumus2_id" class="form-group t_rumus2_peg_rumus2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id"><?php echo (strval($t_rumus2_peg->rumus2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus2_peg->rumus2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus2_peg->rumus2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus2_peg->rumus2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->CurrentValue ?>"<?php echo $t_rumus2_peg->rumus2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="s_x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_rumus2_peg_rumus2_id" class="form-group t_rumus2_peg_rumus2_id">
<span<?php echo $t_rumus2_peg->rumus2_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->rumus2_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->rumus2_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus2_peg->gp->Visible) { // gp ?>
		<td data-name="gp">
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_rumus2_peg_gp" class="form-group t_rumus2_peg_gp">
<input type="text" data-table="t_rumus2_peg" data-field="x_gp" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->gp->EditValue ?>"<?php echo $t_rumus2_peg->gp->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_rumus2_peg_gp" class="form-group t_rumus2_peg_gp">
<span<?php echo $t_rumus2_peg->gp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->gp->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_gp" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_gp" value="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus2_peg->tj->Visible) { // tj ?>
		<td data-name="tj">
<?php if ($t_rumus2_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_rumus2_peg_tj" class="form-group t_rumus2_peg_tj">
<input type="text" data-table="t_rumus2_peg" data-field="x_tj" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->tj->EditValue ?>"<?php echo $t_rumus2_peg->tj->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_rumus2_peg_tj" class="form-group t_rumus2_peg_tj">
<span<?php echo $t_rumus2_peg->tj->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->tj->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="x<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_tj" name="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" id="o<?php echo $t_rumus2_peg_grid->RowIndex ?>_tj" value="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus2_peg_grid->ListOptions->Render("body", "right", $t_rumus2_peg_grid->RowCnt);
?>
<script type="text/javascript">
ft_rumus2_peggrid.UpdateOpts(<?php echo $t_rumus2_peg_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_rumus2_peg->CurrentMode == "add" || $t_rumus2_peg->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_rumus2_peg_grid->FormKeyCountName ?>" id="<?php echo $t_rumus2_peg_grid->FormKeyCountName ?>" value="<?php echo $t_rumus2_peg_grid->KeyCount ?>">
<?php echo $t_rumus2_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_rumus2_peg->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_rumus2_peg_grid->FormKeyCountName ?>" id="<?php echo $t_rumus2_peg_grid->FormKeyCountName ?>" value="<?php echo $t_rumus2_peg_grid->KeyCount ?>">
<?php echo $t_rumus2_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_rumus2_peg->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_rumus2_peggrid">
</div>
<?php

// Close recordset
if ($t_rumus2_peg_grid->Recordset)
	$t_rumus2_peg_grid->Recordset->Close();
?>
<?php if ($t_rumus2_peg_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_rumus2_peg_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_rumus2_peg_grid->TotalRecs == 0 && $t_rumus2_peg->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_rumus2_peg_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_rumus2_peg->Export == "") { ?>
<script type="text/javascript">
ft_rumus2_peggrid.Init();
</script>
<?php } ?>
<?php
$t_rumus2_peg_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_rumus2_peg_grid->Page_Terminate();
?>
