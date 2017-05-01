<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_lapsubgroup_grid)) $t_lapsubgroup_grid = new ct_lapsubgroup_grid();

// Page init
$t_lapsubgroup_grid->Page_Init();

// Page main
$t_lapsubgroup_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_lapsubgroup_grid->Page_Render();
?>
<?php if ($t_lapsubgroup->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_lapsubgroupgrid = new ew_Form("ft_lapsubgroupgrid", "grid");
ft_lapsubgroupgrid.FormKeyCountName = '<?php echo $t_lapsubgroup_grid->FormKeyCountName ?>';

// Validate form
ft_lapsubgroupgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pembagian2_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lapsubgroup->pembagian2_id->FldCaption(), $t_lapsubgroup->pembagian2_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lapsubgroup_index");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lapsubgroup->lapsubgroup_index->FldCaption(), $t_lapsubgroup->lapsubgroup_index->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lapsubgroup_index");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lapsubgroup->lapsubgroup_index->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lapgroup_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_lapsubgroup->lapgroup_id->FldCaption(), $t_lapsubgroup->lapgroup_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lapgroup_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_lapsubgroup->lapgroup_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_lapsubgroupgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pembagian2_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "lapsubgroup_index", false)) return false;
	if (ew_ValueChanged(fobj, infix, "lapgroup_id", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_lapsubgroupgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_lapsubgroupgrid.ValidateRequired = true;
<?php } else { ?>
ft_lapsubgroupgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_lapsubgroupgrid.Lists["x_pembagian2_id"] = {"LinkField":"x_pembagian2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian2"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_lapsubgroup->CurrentAction == "gridadd") {
	if ($t_lapsubgroup->CurrentMode == "copy") {
		$bSelectLimit = $t_lapsubgroup_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_lapsubgroup_grid->TotalRecs = $t_lapsubgroup->SelectRecordCount();
			$t_lapsubgroup_grid->Recordset = $t_lapsubgroup_grid->LoadRecordset($t_lapsubgroup_grid->StartRec-1, $t_lapsubgroup_grid->DisplayRecs);
		} else {
			if ($t_lapsubgroup_grid->Recordset = $t_lapsubgroup_grid->LoadRecordset())
				$t_lapsubgroup_grid->TotalRecs = $t_lapsubgroup_grid->Recordset->RecordCount();
		}
		$t_lapsubgroup_grid->StartRec = 1;
		$t_lapsubgroup_grid->DisplayRecs = $t_lapsubgroup_grid->TotalRecs;
	} else {
		$t_lapsubgroup->CurrentFilter = "0=1";
		$t_lapsubgroup_grid->StartRec = 1;
		$t_lapsubgroup_grid->DisplayRecs = $t_lapsubgroup->GridAddRowCount;
	}
	$t_lapsubgroup_grid->TotalRecs = $t_lapsubgroup_grid->DisplayRecs;
	$t_lapsubgroup_grid->StopRec = $t_lapsubgroup_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_lapsubgroup_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_lapsubgroup_grid->TotalRecs <= 0)
			$t_lapsubgroup_grid->TotalRecs = $t_lapsubgroup->SelectRecordCount();
	} else {
		if (!$t_lapsubgroup_grid->Recordset && ($t_lapsubgroup_grid->Recordset = $t_lapsubgroup_grid->LoadRecordset()))
			$t_lapsubgroup_grid->TotalRecs = $t_lapsubgroup_grid->Recordset->RecordCount();
	}
	$t_lapsubgroup_grid->StartRec = 1;
	$t_lapsubgroup_grid->DisplayRecs = $t_lapsubgroup_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_lapsubgroup_grid->Recordset = $t_lapsubgroup_grid->LoadRecordset($t_lapsubgroup_grid->StartRec-1, $t_lapsubgroup_grid->DisplayRecs);

	// Set no record found message
	if ($t_lapsubgroup->CurrentAction == "" && $t_lapsubgroup_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_lapsubgroup_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_lapsubgroup_grid->SearchWhere == "0=101")
			$t_lapsubgroup_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_lapsubgroup_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_lapsubgroup_grid->RenderOtherOptions();
?>
<?php $t_lapsubgroup_grid->ShowPageHeader(); ?>
<?php
$t_lapsubgroup_grid->ShowMessage();
?>
<?php if ($t_lapsubgroup_grid->TotalRecs > 0 || $t_lapsubgroup->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_lapsubgroup">
<div id="ft_lapsubgroupgrid" class="ewForm form-inline">
<?php if ($t_lapsubgroup_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_lapsubgroup_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_lapsubgroup" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_lapsubgroupgrid" class="table ewTable">
<?php echo $t_lapsubgroup->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_lapsubgroup_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_lapsubgroup_grid->RenderListOptions();

// Render list options (header, left)
$t_lapsubgroup_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_lapsubgroup->pembagian2_id->Visible) { // pembagian2_id ?>
	<?php if ($t_lapsubgroup->SortUrl($t_lapsubgroup->pembagian2_id) == "") { ?>
		<th data-name="pembagian2_id"><div id="elh_t_lapsubgroup_pembagian2_id" class="t_lapsubgroup_pembagian2_id"><div class="ewTableHeaderCaption"><?php echo $t_lapsubgroup->pembagian2_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembagian2_id"><div><div id="elh_t_lapsubgroup_pembagian2_id" class="t_lapsubgroup_pembagian2_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lapsubgroup->pembagian2_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lapsubgroup->pembagian2_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lapsubgroup->pembagian2_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lapsubgroup->lapsubgroup_index->Visible) { // lapsubgroup_index ?>
	<?php if ($t_lapsubgroup->SortUrl($t_lapsubgroup->lapsubgroup_index) == "") { ?>
		<th data-name="lapsubgroup_index"><div id="elh_t_lapsubgroup_lapsubgroup_index" class="t_lapsubgroup_lapsubgroup_index"><div class="ewTableHeaderCaption"><?php echo $t_lapsubgroup->lapsubgroup_index->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lapsubgroup_index"><div><div id="elh_t_lapsubgroup_lapsubgroup_index" class="t_lapsubgroup_lapsubgroup_index">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lapsubgroup->lapsubgroup_index->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lapsubgroup->lapsubgroup_index->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lapsubgroup->lapsubgroup_index->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_lapsubgroup->lapgroup_id->Visible) { // lapgroup_id ?>
	<?php if ($t_lapsubgroup->SortUrl($t_lapsubgroup->lapgroup_id) == "") { ?>
		<th data-name="lapgroup_id"><div id="elh_t_lapsubgroup_lapgroup_id" class="t_lapsubgroup_lapgroup_id"><div class="ewTableHeaderCaption"><?php echo $t_lapsubgroup->lapgroup_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lapgroup_id"><div><div id="elh_t_lapsubgroup_lapgroup_id" class="t_lapsubgroup_lapgroup_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_lapsubgroup->lapgroup_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_lapsubgroup->lapgroup_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_lapsubgroup->lapgroup_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_lapsubgroup_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_lapsubgroup_grid->StartRec = 1;
$t_lapsubgroup_grid->StopRec = $t_lapsubgroup_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_lapsubgroup_grid->FormKeyCountName) && ($t_lapsubgroup->CurrentAction == "gridadd" || $t_lapsubgroup->CurrentAction == "gridedit" || $t_lapsubgroup->CurrentAction == "F")) {
		$t_lapsubgroup_grid->KeyCount = $objForm->GetValue($t_lapsubgroup_grid->FormKeyCountName);
		$t_lapsubgroup_grid->StopRec = $t_lapsubgroup_grid->StartRec + $t_lapsubgroup_grid->KeyCount - 1;
	}
}
$t_lapsubgroup_grid->RecCnt = $t_lapsubgroup_grid->StartRec - 1;
if ($t_lapsubgroup_grid->Recordset && !$t_lapsubgroup_grid->Recordset->EOF) {
	$t_lapsubgroup_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_lapsubgroup_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_lapsubgroup_grid->StartRec > 1)
		$t_lapsubgroup_grid->Recordset->Move($t_lapsubgroup_grid->StartRec - 1);
} elseif (!$t_lapsubgroup->AllowAddDeleteRow && $t_lapsubgroup_grid->StopRec == 0) {
	$t_lapsubgroup_grid->StopRec = $t_lapsubgroup->GridAddRowCount;
}

// Initialize aggregate
$t_lapsubgroup->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_lapsubgroup->ResetAttrs();
$t_lapsubgroup_grid->RenderRow();
if ($t_lapsubgroup->CurrentAction == "gridadd")
	$t_lapsubgroup_grid->RowIndex = 0;
if ($t_lapsubgroup->CurrentAction == "gridedit")
	$t_lapsubgroup_grid->RowIndex = 0;
while ($t_lapsubgroup_grid->RecCnt < $t_lapsubgroup_grid->StopRec) {
	$t_lapsubgroup_grid->RecCnt++;
	if (intval($t_lapsubgroup_grid->RecCnt) >= intval($t_lapsubgroup_grid->StartRec)) {
		$t_lapsubgroup_grid->RowCnt++;
		if ($t_lapsubgroup->CurrentAction == "gridadd" || $t_lapsubgroup->CurrentAction == "gridedit" || $t_lapsubgroup->CurrentAction == "F") {
			$t_lapsubgroup_grid->RowIndex++;
			$objForm->Index = $t_lapsubgroup_grid->RowIndex;
			if ($objForm->HasValue($t_lapsubgroup_grid->FormActionName))
				$t_lapsubgroup_grid->RowAction = strval($objForm->GetValue($t_lapsubgroup_grid->FormActionName));
			elseif ($t_lapsubgroup->CurrentAction == "gridadd")
				$t_lapsubgroup_grid->RowAction = "insert";
			else
				$t_lapsubgroup_grid->RowAction = "";
		}

		// Set up key count
		$t_lapsubgroup_grid->KeyCount = $t_lapsubgroup_grid->RowIndex;

		// Init row class and style
		$t_lapsubgroup->ResetAttrs();
		$t_lapsubgroup->CssClass = "";
		if ($t_lapsubgroup->CurrentAction == "gridadd") {
			if ($t_lapsubgroup->CurrentMode == "copy") {
				$t_lapsubgroup_grid->LoadRowValues($t_lapsubgroup_grid->Recordset); // Load row values
				$t_lapsubgroup_grid->SetRecordKey($t_lapsubgroup_grid->RowOldKey, $t_lapsubgroup_grid->Recordset); // Set old record key
			} else {
				$t_lapsubgroup_grid->LoadDefaultValues(); // Load default values
				$t_lapsubgroup_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_lapsubgroup_grid->LoadRowValues($t_lapsubgroup_grid->Recordset); // Load row values
		}
		$t_lapsubgroup->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_lapsubgroup->CurrentAction == "gridadd") // Grid add
			$t_lapsubgroup->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_lapsubgroup->CurrentAction == "gridadd" && $t_lapsubgroup->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_lapsubgroup_grid->RestoreCurrentRowFormValues($t_lapsubgroup_grid->RowIndex); // Restore form values
		if ($t_lapsubgroup->CurrentAction == "gridedit") { // Grid edit
			if ($t_lapsubgroup->EventCancelled) {
				$t_lapsubgroup_grid->RestoreCurrentRowFormValues($t_lapsubgroup_grid->RowIndex); // Restore form values
			}
			if ($t_lapsubgroup_grid->RowAction == "insert")
				$t_lapsubgroup->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_lapsubgroup->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_lapsubgroup->CurrentAction == "gridedit" && ($t_lapsubgroup->RowType == EW_ROWTYPE_EDIT || $t_lapsubgroup->RowType == EW_ROWTYPE_ADD) && $t_lapsubgroup->EventCancelled) // Update failed
			$t_lapsubgroup_grid->RestoreCurrentRowFormValues($t_lapsubgroup_grid->RowIndex); // Restore form values
		if ($t_lapsubgroup->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_lapsubgroup_grid->EditRowCnt++;
		if ($t_lapsubgroup->CurrentAction == "F") // Confirm row
			$t_lapsubgroup_grid->RestoreCurrentRowFormValues($t_lapsubgroup_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_lapsubgroup->RowAttrs = array_merge($t_lapsubgroup->RowAttrs, array('data-rowindex'=>$t_lapsubgroup_grid->RowCnt, 'id'=>'r' . $t_lapsubgroup_grid->RowCnt . '_t_lapsubgroup', 'data-rowtype'=>$t_lapsubgroup->RowType));

		// Render row
		$t_lapsubgroup_grid->RenderRow();

		// Render list options
		$t_lapsubgroup_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_lapsubgroup_grid->RowAction <> "delete" && $t_lapsubgroup_grid->RowAction <> "insertdelete" && !($t_lapsubgroup_grid->RowAction == "insert" && $t_lapsubgroup->CurrentAction == "F" && $t_lapsubgroup_grid->EmptyRow())) {
?>
	<tr<?php echo $t_lapsubgroup->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_lapsubgroup_grid->ListOptions->Render("body", "left", $t_lapsubgroup_grid->RowCnt);
?>
	<?php if ($t_lapsubgroup->pembagian2_id->Visible) { // pembagian2_id ?>
		<td data-name="pembagian2_id"<?php echo $t_lapsubgroup->pembagian2_id->CellAttributes() ?>>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_pembagian2_id" class="form-group t_lapsubgroup_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id"><?php echo (strval($t_lapsubgroup->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_lapsubgroup->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_lapsubgroup->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_lapsubgroup->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo $t_lapsubgroup->pembagian2_id->CurrentValue ?>"<?php echo $t_lapsubgroup->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="s_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo $t_lapsubgroup->pembagian2_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_pembagian2_id" class="form-group t_lapsubgroup_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id"><?php echo (strval($t_lapsubgroup->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_lapsubgroup->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_lapsubgroup->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_lapsubgroup->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo $t_lapsubgroup->pembagian2_id->CurrentValue ?>"<?php echo $t_lapsubgroup->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="s_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo $t_lapsubgroup->pembagian2_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_pembagian2_id" class="t_lapsubgroup_pembagian2_id">
<span<?php echo $t_lapsubgroup->pembagian2_id->ViewAttributes() ?>>
<?php echo $t_lapsubgroup->pembagian2_id->ListViewValue() ?></span>
</span>
<?php if ($t_lapsubgroup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->FormValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="ft_lapsubgroupgrid$x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="ft_lapsubgroupgrid$x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->FormValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="ft_lapsubgroupgrid$o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="ft_lapsubgroupgrid$o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_lapsubgroup_grid->PageObjName . "_row_" . $t_lapsubgroup_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_id->CurrentValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_EDIT || $t_lapsubgroup->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_lapsubgroup->lapsubgroup_index->Visible) { // lapsubgroup_index ?>
		<td data-name="lapsubgroup_index"<?php echo $t_lapsubgroup->lapsubgroup_index->CellAttributes() ?>>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapsubgroup_index" class="form-group t_lapsubgroup_lapsubgroup_index">
<input type="text" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" size="30" placeholder="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->getPlaceHolder()) ?>" value="<?php echo $t_lapsubgroup->lapsubgroup_index->EditValue ?>"<?php echo $t_lapsubgroup->lapsubgroup_index->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->OldValue) ?>">
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapsubgroup_index" class="form-group t_lapsubgroup_lapsubgroup_index">
<input type="text" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" size="30" placeholder="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->getPlaceHolder()) ?>" value="<?php echo $t_lapsubgroup->lapsubgroup_index->EditValue ?>"<?php echo $t_lapsubgroup->lapsubgroup_index->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapsubgroup_index" class="t_lapsubgroup_lapsubgroup_index">
<span<?php echo $t_lapsubgroup->lapsubgroup_index->ViewAttributes() ?>>
<?php echo $t_lapsubgroup->lapsubgroup_index->ListViewValue() ?></span>
</span>
<?php if ($t_lapsubgroup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->FormValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="ft_lapsubgroupgrid$x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="ft_lapsubgroupgrid$x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->FormValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="ft_lapsubgroupgrid$o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="ft_lapsubgroupgrid$o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_lapsubgroup->lapgroup_id->Visible) { // lapgroup_id ?>
		<td data-name="lapgroup_id"<?php echo $t_lapsubgroup->lapgroup_id->CellAttributes() ?>>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_lapsubgroup->lapgroup_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<span<?php echo $t_lapsubgroup->lapgroup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lapsubgroup->lapgroup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<input type="text" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->getPlaceHolder()) ?>" value="<?php echo $t_lapsubgroup->lapgroup_id->EditValue ?>"<?php echo $t_lapsubgroup->lapgroup_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->OldValue) ?>">
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_lapsubgroup->lapgroup_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<span<?php echo $t_lapsubgroup->lapgroup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lapsubgroup->lapgroup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<input type="text" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->getPlaceHolder()) ?>" value="<?php echo $t_lapsubgroup->lapgroup_id->EditValue ?>"<?php echo $t_lapsubgroup->lapgroup_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_lapsubgroup_grid->RowCnt ?>_t_lapsubgroup_lapgroup_id" class="t_lapsubgroup_lapgroup_id">
<span<?php echo $t_lapsubgroup->lapgroup_id->ViewAttributes() ?>>
<?php echo $t_lapsubgroup->lapgroup_id->ListViewValue() ?></span>
</span>
<?php if ($t_lapsubgroup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->FormValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="ft_lapsubgroupgrid$x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="ft_lapsubgroupgrid$x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->FormValue) ?>">
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="ft_lapsubgroupgrid$o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="ft_lapsubgroupgrid$o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_lapsubgroup_grid->ListOptions->Render("body", "right", $t_lapsubgroup_grid->RowCnt);
?>
	</tr>
<?php if ($t_lapsubgroup->RowType == EW_ROWTYPE_ADD || $t_lapsubgroup->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_lapsubgroupgrid.UpdateOpts(<?php echo $t_lapsubgroup_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_lapsubgroup->CurrentAction <> "gridadd" || $t_lapsubgroup->CurrentMode == "copy")
		if (!$t_lapsubgroup_grid->Recordset->EOF) $t_lapsubgroup_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_lapsubgroup->CurrentMode == "add" || $t_lapsubgroup->CurrentMode == "copy" || $t_lapsubgroup->CurrentMode == "edit") {
		$t_lapsubgroup_grid->RowIndex = '$rowindex$';
		$t_lapsubgroup_grid->LoadDefaultValues();

		// Set row properties
		$t_lapsubgroup->ResetAttrs();
		$t_lapsubgroup->RowAttrs = array_merge($t_lapsubgroup->RowAttrs, array('data-rowindex'=>$t_lapsubgroup_grid->RowIndex, 'id'=>'r0_t_lapsubgroup', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_lapsubgroup->RowAttrs["class"], "ewTemplate");
		$t_lapsubgroup->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_lapsubgroup_grid->RenderRow();

		// Render list options
		$t_lapsubgroup_grid->RenderListOptions();
		$t_lapsubgroup_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_lapsubgroup->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_lapsubgroup_grid->ListOptions->Render("body", "left", $t_lapsubgroup_grid->RowIndex);
?>
	<?php if ($t_lapsubgroup->pembagian2_id->Visible) { // pembagian2_id ?>
		<td data-name="pembagian2_id">
<?php if ($t_lapsubgroup->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lapsubgroup_pembagian2_id" class="form-group t_lapsubgroup_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id"><?php echo (strval($t_lapsubgroup->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_lapsubgroup->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_lapsubgroup->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_lapsubgroup->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo $t_lapsubgroup->pembagian2_id->CurrentValue ?>"<?php echo $t_lapsubgroup->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="s_x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo $t_lapsubgroup->pembagian2_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lapsubgroup_pembagian2_id" class="form-group t_lapsubgroup_pembagian2_id">
<span<?php echo $t_lapsubgroup->pembagian2_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lapsubgroup->pembagian2_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_pembagian2_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->pembagian2_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lapsubgroup->lapsubgroup_index->Visible) { // lapsubgroup_index ?>
		<td data-name="lapsubgroup_index">
<?php if ($t_lapsubgroup->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_lapsubgroup_lapsubgroup_index" class="form-group t_lapsubgroup_lapsubgroup_index">
<input type="text" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" size="30" placeholder="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->getPlaceHolder()) ?>" value="<?php echo $t_lapsubgroup->lapsubgroup_index->EditValue ?>"<?php echo $t_lapsubgroup->lapsubgroup_index->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_lapsubgroup_lapsubgroup_index" class="form-group t_lapsubgroup_lapsubgroup_index">
<span<?php echo $t_lapsubgroup->lapsubgroup_index->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lapsubgroup->lapsubgroup_index->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapsubgroup_index" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapsubgroup_index" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapsubgroup_index->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_lapsubgroup->lapgroup_id->Visible) { // lapgroup_id ?>
		<td data-name="lapgroup_id">
<?php if ($t_lapsubgroup->CurrentAction <> "F") { ?>
<?php if ($t_lapsubgroup->lapgroup_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<span<?php echo $t_lapsubgroup->lapgroup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lapsubgroup->lapgroup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<input type="text" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->getPlaceHolder()) ?>" value="<?php echo $t_lapsubgroup->lapgroup_id->EditValue ?>"<?php echo $t_lapsubgroup->lapgroup_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_lapsubgroup_lapgroup_id" class="form-group t_lapsubgroup_lapgroup_id">
<span<?php echo $t_lapsubgroup->lapgroup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_lapsubgroup->lapgroup_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="x<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_lapsubgroup" data-field="x_lapgroup_id" name="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" id="o<?php echo $t_lapsubgroup_grid->RowIndex ?>_lapgroup_id" value="<?php echo ew_HtmlEncode($t_lapsubgroup->lapgroup_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_lapsubgroup_grid->ListOptions->Render("body", "right", $t_lapsubgroup_grid->RowCnt);
?>
<script type="text/javascript">
ft_lapsubgroupgrid.UpdateOpts(<?php echo $t_lapsubgroup_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_lapsubgroup->CurrentMode == "add" || $t_lapsubgroup->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_lapsubgroup_grid->FormKeyCountName ?>" id="<?php echo $t_lapsubgroup_grid->FormKeyCountName ?>" value="<?php echo $t_lapsubgroup_grid->KeyCount ?>">
<?php echo $t_lapsubgroup_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_lapsubgroup->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_lapsubgroup_grid->FormKeyCountName ?>" id="<?php echo $t_lapsubgroup_grid->FormKeyCountName ?>" value="<?php echo $t_lapsubgroup_grid->KeyCount ?>">
<?php echo $t_lapsubgroup_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_lapsubgroup->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_lapsubgroupgrid">
</div>
<?php

// Close recordset
if ($t_lapsubgroup_grid->Recordset)
	$t_lapsubgroup_grid->Recordset->Close();
?>
<?php if ($t_lapsubgroup_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_lapsubgroup_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_lapsubgroup_grid->TotalRecs == 0 && $t_lapsubgroup->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_lapsubgroup_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_lapsubgroup->Export == "") { ?>
<script type="text/javascript">
ft_lapsubgroupgrid.Init();
</script>
<?php } ?>
<?php
$t_lapsubgroup_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_lapsubgroup_grid->Page_Terminate();
?>
