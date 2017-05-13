<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_keg_detai_grid)) $t_keg_detai_grid = new ct_keg_detai_grid();

// Page init
$t_keg_detai_grid->Page_Init();

// Page main
$t_keg_detai_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_keg_detai_grid->Page_Render();
?>
<?php if ($t_keg_detai->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_keg_detaigrid = new ew_Form("ft_keg_detaigrid", "grid");
ft_keg_detaigrid.FormKeyCountName = '<?php echo $t_keg_detai_grid->FormKeyCountName ?>';

// Validate form
ft_keg_detaigrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_keg_detai->pegawai_id->FldCaption(), $t_keg_detai->pegawai_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_keg_detaigrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_keg_detaigrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_keg_detaigrid.ValidateRequired = true;
<?php } else { ?>
ft_keg_detaigrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_keg_detaigrid.Lists["x_pegawai_id"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_keg_detai->CurrentAction == "gridadd") {
	if ($t_keg_detai->CurrentMode == "copy") {
		$bSelectLimit = $t_keg_detai_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_keg_detai_grid->TotalRecs = $t_keg_detai->SelectRecordCount();
			$t_keg_detai_grid->Recordset = $t_keg_detai_grid->LoadRecordset($t_keg_detai_grid->StartRec-1, $t_keg_detai_grid->DisplayRecs);
		} else {
			if ($t_keg_detai_grid->Recordset = $t_keg_detai_grid->LoadRecordset())
				$t_keg_detai_grid->TotalRecs = $t_keg_detai_grid->Recordset->RecordCount();
		}
		$t_keg_detai_grid->StartRec = 1;
		$t_keg_detai_grid->DisplayRecs = $t_keg_detai_grid->TotalRecs;
	} else {
		$t_keg_detai->CurrentFilter = "0=1";
		$t_keg_detai_grid->StartRec = 1;
		$t_keg_detai_grid->DisplayRecs = $t_keg_detai->GridAddRowCount;
	}
	$t_keg_detai_grid->TotalRecs = $t_keg_detai_grid->DisplayRecs;
	$t_keg_detai_grid->StopRec = $t_keg_detai_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_keg_detai_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_keg_detai_grid->TotalRecs <= 0)
			$t_keg_detai_grid->TotalRecs = $t_keg_detai->SelectRecordCount();
	} else {
		if (!$t_keg_detai_grid->Recordset && ($t_keg_detai_grid->Recordset = $t_keg_detai_grid->LoadRecordset()))
			$t_keg_detai_grid->TotalRecs = $t_keg_detai_grid->Recordset->RecordCount();
	}
	$t_keg_detai_grid->StartRec = 1;
	$t_keg_detai_grid->DisplayRecs = $t_keg_detai_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_keg_detai_grid->Recordset = $t_keg_detai_grid->LoadRecordset($t_keg_detai_grid->StartRec-1, $t_keg_detai_grid->DisplayRecs);

	// Set no record found message
	if ($t_keg_detai->CurrentAction == "" && $t_keg_detai_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_keg_detai_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_keg_detai_grid->SearchWhere == "0=101")
			$t_keg_detai_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_keg_detai_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_keg_detai_grid->RenderOtherOptions();
?>
<?php $t_keg_detai_grid->ShowPageHeader(); ?>
<?php
$t_keg_detai_grid->ShowMessage();
?>
<?php if ($t_keg_detai_grid->TotalRecs > 0 || $t_keg_detai->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_keg_detai">
<div id="ft_keg_detaigrid" class="ewForm form-inline">
<?php if ($t_keg_detai_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_keg_detai_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_keg_detai" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_keg_detaigrid" class="table ewTable">
<?php echo $t_keg_detai->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_keg_detai_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_keg_detai_grid->RenderListOptions();

// Render list options (header, left)
$t_keg_detai_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_keg_detai->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_keg_detai->SortUrl($t_keg_detai->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_keg_detai_pegawai_id" class="t_keg_detai_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_keg_detai->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_keg_detai_pegawai_id" class="t_keg_detai_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_keg_detai->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_keg_detai->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_keg_detai->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_keg_detai_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_keg_detai_grid->StartRec = 1;
$t_keg_detai_grid->StopRec = $t_keg_detai_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_keg_detai_grid->FormKeyCountName) && ($t_keg_detai->CurrentAction == "gridadd" || $t_keg_detai->CurrentAction == "gridedit" || $t_keg_detai->CurrentAction == "F")) {
		$t_keg_detai_grid->KeyCount = $objForm->GetValue($t_keg_detai_grid->FormKeyCountName);
		$t_keg_detai_grid->StopRec = $t_keg_detai_grid->StartRec + $t_keg_detai_grid->KeyCount - 1;
	}
}
$t_keg_detai_grid->RecCnt = $t_keg_detai_grid->StartRec - 1;
if ($t_keg_detai_grid->Recordset && !$t_keg_detai_grid->Recordset->EOF) {
	$t_keg_detai_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_keg_detai_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_keg_detai_grid->StartRec > 1)
		$t_keg_detai_grid->Recordset->Move($t_keg_detai_grid->StartRec - 1);
} elseif (!$t_keg_detai->AllowAddDeleteRow && $t_keg_detai_grid->StopRec == 0) {
	$t_keg_detai_grid->StopRec = $t_keg_detai->GridAddRowCount;
}

// Initialize aggregate
$t_keg_detai->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_keg_detai->ResetAttrs();
$t_keg_detai_grid->RenderRow();
if ($t_keg_detai->CurrentAction == "gridadd")
	$t_keg_detai_grid->RowIndex = 0;
if ($t_keg_detai->CurrentAction == "gridedit")
	$t_keg_detai_grid->RowIndex = 0;
while ($t_keg_detai_grid->RecCnt < $t_keg_detai_grid->StopRec) {
	$t_keg_detai_grid->RecCnt++;
	if (intval($t_keg_detai_grid->RecCnt) >= intval($t_keg_detai_grid->StartRec)) {
		$t_keg_detai_grid->RowCnt++;
		if ($t_keg_detai->CurrentAction == "gridadd" || $t_keg_detai->CurrentAction == "gridedit" || $t_keg_detai->CurrentAction == "F") {
			$t_keg_detai_grid->RowIndex++;
			$objForm->Index = $t_keg_detai_grid->RowIndex;
			if ($objForm->HasValue($t_keg_detai_grid->FormActionName))
				$t_keg_detai_grid->RowAction = strval($objForm->GetValue($t_keg_detai_grid->FormActionName));
			elseif ($t_keg_detai->CurrentAction == "gridadd")
				$t_keg_detai_grid->RowAction = "insert";
			else
				$t_keg_detai_grid->RowAction = "";
		}

		// Set up key count
		$t_keg_detai_grid->KeyCount = $t_keg_detai_grid->RowIndex;

		// Init row class and style
		$t_keg_detai->ResetAttrs();
		$t_keg_detai->CssClass = "";
		if ($t_keg_detai->CurrentAction == "gridadd") {
			if ($t_keg_detai->CurrentMode == "copy") {
				$t_keg_detai_grid->LoadRowValues($t_keg_detai_grid->Recordset); // Load row values
				$t_keg_detai_grid->SetRecordKey($t_keg_detai_grid->RowOldKey, $t_keg_detai_grid->Recordset); // Set old record key
			} else {
				$t_keg_detai_grid->LoadDefaultValues(); // Load default values
				$t_keg_detai_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_keg_detai_grid->LoadRowValues($t_keg_detai_grid->Recordset); // Load row values
		}
		$t_keg_detai->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_keg_detai->CurrentAction == "gridadd") // Grid add
			$t_keg_detai->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_keg_detai->CurrentAction == "gridadd" && $t_keg_detai->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_keg_detai_grid->RestoreCurrentRowFormValues($t_keg_detai_grid->RowIndex); // Restore form values
		if ($t_keg_detai->CurrentAction == "gridedit") { // Grid edit
			if ($t_keg_detai->EventCancelled) {
				$t_keg_detai_grid->RestoreCurrentRowFormValues($t_keg_detai_grid->RowIndex); // Restore form values
			}
			if ($t_keg_detai_grid->RowAction == "insert")
				$t_keg_detai->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_keg_detai->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_keg_detai->CurrentAction == "gridedit" && ($t_keg_detai->RowType == EW_ROWTYPE_EDIT || $t_keg_detai->RowType == EW_ROWTYPE_ADD) && $t_keg_detai->EventCancelled) // Update failed
			$t_keg_detai_grid->RestoreCurrentRowFormValues($t_keg_detai_grid->RowIndex); // Restore form values
		if ($t_keg_detai->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_keg_detai_grid->EditRowCnt++;
		if ($t_keg_detai->CurrentAction == "F") // Confirm row
			$t_keg_detai_grid->RestoreCurrentRowFormValues($t_keg_detai_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_keg_detai->RowAttrs = array_merge($t_keg_detai->RowAttrs, array('data-rowindex'=>$t_keg_detai_grid->RowCnt, 'id'=>'r' . $t_keg_detai_grid->RowCnt . '_t_keg_detai', 'data-rowtype'=>$t_keg_detai->RowType));

		// Render row
		$t_keg_detai_grid->RenderRow();

		// Render list options
		$t_keg_detai_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_keg_detai_grid->RowAction <> "delete" && $t_keg_detai_grid->RowAction <> "insertdelete" && !($t_keg_detai_grid->RowAction == "insert" && $t_keg_detai->CurrentAction == "F" && $t_keg_detai_grid->EmptyRow())) {
?>
	<tr<?php echo $t_keg_detai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_keg_detai_grid->ListOptions->Render("body", "left", $t_keg_detai_grid->RowCnt);
?>
	<?php if ($t_keg_detai->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_keg_detai->pegawai_id->CellAttributes() ?>>
<?php if ($t_keg_detai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_keg_detai_grid->RowCnt ?>_t_keg_detai_pegawai_id" class="form-group t_keg_detai_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_keg_detai->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_keg_detai->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_keg_detai->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_keg_detai->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_keg_detai->pegawai_id->CurrentValue ?>"<?php echo $t_keg_detai->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_keg_detai->pegawai_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_keg_detai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_keg_detai_grid->RowCnt ?>_t_keg_detai_pegawai_id" class="form-group t_keg_detai_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_keg_detai->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_keg_detai->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_keg_detai->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_keg_detai->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_keg_detai->pegawai_id->CurrentValue ?>"<?php echo $t_keg_detai->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_keg_detai->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_keg_detai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_keg_detai_grid->RowCnt ?>_t_keg_detai_pegawai_id" class="t_keg_detai_pegawai_id">
<span<?php echo $t_keg_detai->pegawai_id->ViewAttributes() ?>>
<?php echo $t_keg_detai->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_keg_detai->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="ft_keg_detaigrid$x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="ft_keg_detaigrid$x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="ft_keg_detaigrid$o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="ft_keg_detaigrid$o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_keg_detai_grid->PageObjName . "_row_" . $t_keg_detai_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_keg_detai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_keg_detai" data-field="x_kegd_id" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_kegd_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_kegd_id" value="<?php echo ew_HtmlEncode($t_keg_detai->kegd_id->CurrentValue) ?>">
<input type="hidden" data-table="t_keg_detai" data-field="x_kegd_id" name="o<?php echo $t_keg_detai_grid->RowIndex ?>_kegd_id" id="o<?php echo $t_keg_detai_grid->RowIndex ?>_kegd_id" value="<?php echo ew_HtmlEncode($t_keg_detai->kegd_id->OldValue) ?>">
<?php } ?>
<?php if ($t_keg_detai->RowType == EW_ROWTYPE_EDIT || $t_keg_detai->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_keg_detai" data-field="x_kegd_id" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_kegd_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_kegd_id" value="<?php echo ew_HtmlEncode($t_keg_detai->kegd_id->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$t_keg_detai_grid->ListOptions->Render("body", "right", $t_keg_detai_grid->RowCnt);
?>
	</tr>
<?php if ($t_keg_detai->RowType == EW_ROWTYPE_ADD || $t_keg_detai->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_keg_detaigrid.UpdateOpts(<?php echo $t_keg_detai_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_keg_detai->CurrentAction <> "gridadd" || $t_keg_detai->CurrentMode == "copy")
		if (!$t_keg_detai_grid->Recordset->EOF) $t_keg_detai_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_keg_detai->CurrentMode == "add" || $t_keg_detai->CurrentMode == "copy" || $t_keg_detai->CurrentMode == "edit") {
		$t_keg_detai_grid->RowIndex = '$rowindex$';
		$t_keg_detai_grid->LoadDefaultValues();

		// Set row properties
		$t_keg_detai->ResetAttrs();
		$t_keg_detai->RowAttrs = array_merge($t_keg_detai->RowAttrs, array('data-rowindex'=>$t_keg_detai_grid->RowIndex, 'id'=>'r0_t_keg_detai', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_keg_detai->RowAttrs["class"], "ewTemplate");
		$t_keg_detai->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_keg_detai_grid->RenderRow();

		// Render list options
		$t_keg_detai_grid->RenderListOptions();
		$t_keg_detai_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_keg_detai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_keg_detai_grid->ListOptions->Render("body", "left", $t_keg_detai_grid->RowIndex);
?>
	<?php if ($t_keg_detai->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_keg_detai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_keg_detai_pegawai_id" class="form-group t_keg_detai_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_keg_detai->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_keg_detai->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_keg_detai->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_keg_detai->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_keg_detai->pegawai_id->CurrentValue ?>"<?php echo $t_keg_detai->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_keg_detai->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_keg_detai_pegawai_id" class="form-group t_keg_detai_pegawai_id">
<span<?php echo $t_keg_detai->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_keg_detai->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_keg_detai" data-field="x_pegawai_id" name="o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_keg_detai_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_keg_detai->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_keg_detai_grid->ListOptions->Render("body", "right", $t_keg_detai_grid->RowCnt);
?>
<script type="text/javascript">
ft_keg_detaigrid.UpdateOpts(<?php echo $t_keg_detai_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_keg_detai->CurrentMode == "add" || $t_keg_detai->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_keg_detai_grid->FormKeyCountName ?>" id="<?php echo $t_keg_detai_grid->FormKeyCountName ?>" value="<?php echo $t_keg_detai_grid->KeyCount ?>">
<?php echo $t_keg_detai_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_keg_detai->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_keg_detai_grid->FormKeyCountName ?>" id="<?php echo $t_keg_detai_grid->FormKeyCountName ?>" value="<?php echo $t_keg_detai_grid->KeyCount ?>">
<?php echo $t_keg_detai_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_keg_detai->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_keg_detaigrid">
</div>
<?php

// Close recordset
if ($t_keg_detai_grid->Recordset)
	$t_keg_detai_grid->Recordset->Close();
?>
<?php if ($t_keg_detai_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_keg_detai_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_keg_detai_grid->TotalRecs == 0 && $t_keg_detai->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_keg_detai_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_keg_detai->Export == "") { ?>
<script type="text/javascript">
ft_keg_detaigrid.Init();
</script>
<?php } ?>
<?php
$t_keg_detai_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_keg_detai_grid->Page_Terminate();
?>
