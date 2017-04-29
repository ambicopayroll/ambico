<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_jdw_krj_peg_grid)) $t_jdw_krj_peg_grid = new ct_jdw_krj_peg_grid();

// Page init
$t_jdw_krj_peg_grid->Page_Init();

// Page main
$t_jdw_krj_peg_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_jdw_krj_peg_grid->Page_Render();
?>
<?php if ($t_jdw_krj_peg->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_jdw_krj_peggrid = new ew_Form("ft_jdw_krj_peggrid", "grid");
ft_jdw_krj_peggrid.FormKeyCountName = '<?php echo $t_jdw_krj_peg_grid->FormKeyCountName ?>';

// Validate form
ft_jdw_krj_peggrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_jdw_krj_peg->pegawai_id->FldCaption(), $t_jdw_krj_peg->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_jdw_krj_peg->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_jdw_krj_peg->tgl1->FldCaption(), $t_jdw_krj_peg->tgl1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl1");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_jdw_krj_peg->tgl1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_jdw_krj_peg->tgl2->FldCaption(), $t_jdw_krj_peg->tgl2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl2");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_jdw_krj_peg->tgl2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_jdw_krj_peg->jk_id->FldCaption(), $t_jdw_krj_peg->jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hk");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_jdw_krj_peg->hk->FldCaption(), $t_jdw_krj_peg->hk->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_jdw_krj_peggrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl1", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jk_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hk", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_jdw_krj_peggrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_jdw_krj_peggrid.ValidateRequired = true;
<?php } else { ?>
ft_jdw_krj_peggrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_jdw_krj_peggrid.Lists["x_jk_id"] = {"LinkField":"x_jk_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_jk_nm","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_jk"};
ft_jdw_krj_peggrid.Lists["x_hk"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_jdw_krj_peggrid.Lists["x_hk"].Options = <?php echo json_encode($t_jdw_krj_peg->hk->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($t_jdw_krj_peg->CurrentAction == "gridadd") {
	if ($t_jdw_krj_peg->CurrentMode == "copy") {
		$bSelectLimit = $t_jdw_krj_peg_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_jdw_krj_peg_grid->TotalRecs = $t_jdw_krj_peg->SelectRecordCount();
			$t_jdw_krj_peg_grid->Recordset = $t_jdw_krj_peg_grid->LoadRecordset($t_jdw_krj_peg_grid->StartRec-1, $t_jdw_krj_peg_grid->DisplayRecs);
		} else {
			if ($t_jdw_krj_peg_grid->Recordset = $t_jdw_krj_peg_grid->LoadRecordset())
				$t_jdw_krj_peg_grid->TotalRecs = $t_jdw_krj_peg_grid->Recordset->RecordCount();
		}
		$t_jdw_krj_peg_grid->StartRec = 1;
		$t_jdw_krj_peg_grid->DisplayRecs = $t_jdw_krj_peg_grid->TotalRecs;
	} else {
		$t_jdw_krj_peg->CurrentFilter = "0=1";
		$t_jdw_krj_peg_grid->StartRec = 1;
		$t_jdw_krj_peg_grid->DisplayRecs = $t_jdw_krj_peg->GridAddRowCount;
	}
	$t_jdw_krj_peg_grid->TotalRecs = $t_jdw_krj_peg_grid->DisplayRecs;
	$t_jdw_krj_peg_grid->StopRec = $t_jdw_krj_peg_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_jdw_krj_peg_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_jdw_krj_peg_grid->TotalRecs <= 0)
			$t_jdw_krj_peg_grid->TotalRecs = $t_jdw_krj_peg->SelectRecordCount();
	} else {
		if (!$t_jdw_krj_peg_grid->Recordset && ($t_jdw_krj_peg_grid->Recordset = $t_jdw_krj_peg_grid->LoadRecordset()))
			$t_jdw_krj_peg_grid->TotalRecs = $t_jdw_krj_peg_grid->Recordset->RecordCount();
	}
	$t_jdw_krj_peg_grid->StartRec = 1;
	$t_jdw_krj_peg_grid->DisplayRecs = $t_jdw_krj_peg_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_jdw_krj_peg_grid->Recordset = $t_jdw_krj_peg_grid->LoadRecordset($t_jdw_krj_peg_grid->StartRec-1, $t_jdw_krj_peg_grid->DisplayRecs);

	// Set no record found message
	if ($t_jdw_krj_peg->CurrentAction == "" && $t_jdw_krj_peg_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_jdw_krj_peg_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_jdw_krj_peg_grid->SearchWhere == "0=101")
			$t_jdw_krj_peg_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_jdw_krj_peg_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_jdw_krj_peg_grid->RenderOtherOptions();
?>
<?php $t_jdw_krj_peg_grid->ShowPageHeader(); ?>
<?php
$t_jdw_krj_peg_grid->ShowMessage();
?>
<?php if ($t_jdw_krj_peg_grid->TotalRecs > 0 || $t_jdw_krj_peg->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_jdw_krj_peg">
<div id="ft_jdw_krj_peggrid" class="ewForm form-inline">
<?php if ($t_jdw_krj_peg_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_jdw_krj_peg_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_jdw_krj_peg" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_jdw_krj_peggrid" class="table ewTable">
<?php echo $t_jdw_krj_peg->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_jdw_krj_peg_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_jdw_krj_peg_grid->RenderListOptions();

// Render list options (header, left)
$t_jdw_krj_peg_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_jdw_krj_peg->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_jdw_krj_peg->SortUrl($t_jdw_krj_peg->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_jdw_krj_peg_pegawai_id" class="t_jdw_krj_peg_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_jdw_krj_peg_pegawai_id" class="t_jdw_krj_peg_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_jdw_krj_peg->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_jdw_krj_peg->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_jdw_krj_peg->tgl1->Visible) { // tgl1 ?>
	<?php if ($t_jdw_krj_peg->SortUrl($t_jdw_krj_peg->tgl1) == "") { ?>
		<th data-name="tgl1"><div id="elh_t_jdw_krj_peg_tgl1" class="t_jdw_krj_peg_tgl1"><div class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->tgl1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl1"><div><div id="elh_t_jdw_krj_peg_tgl1" class="t_jdw_krj_peg_tgl1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->tgl1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_jdw_krj_peg->tgl1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_jdw_krj_peg->tgl1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_jdw_krj_peg->tgl2->Visible) { // tgl2 ?>
	<?php if ($t_jdw_krj_peg->SortUrl($t_jdw_krj_peg->tgl2) == "") { ?>
		<th data-name="tgl2"><div id="elh_t_jdw_krj_peg_tgl2" class="t_jdw_krj_peg_tgl2"><div class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->tgl2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl2"><div><div id="elh_t_jdw_krj_peg_tgl2" class="t_jdw_krj_peg_tgl2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->tgl2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_jdw_krj_peg->tgl2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_jdw_krj_peg->tgl2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_jdw_krj_peg->jk_id->Visible) { // jk_id ?>
	<?php if ($t_jdw_krj_peg->SortUrl($t_jdw_krj_peg->jk_id) == "") { ?>
		<th data-name="jk_id"><div id="elh_t_jdw_krj_peg_jk_id" class="t_jdw_krj_peg_jk_id"><div class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->jk_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_id"><div><div id="elh_t_jdw_krj_peg_jk_id" class="t_jdw_krj_peg_jk_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->jk_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_jdw_krj_peg->jk_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_jdw_krj_peg->jk_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_jdw_krj_peg->hk->Visible) { // hk ?>
	<?php if ($t_jdw_krj_peg->SortUrl($t_jdw_krj_peg->hk) == "") { ?>
		<th data-name="hk"><div id="elh_t_jdw_krj_peg_hk" class="t_jdw_krj_peg_hk"><div class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->hk->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hk"><div><div id="elh_t_jdw_krj_peg_hk" class="t_jdw_krj_peg_hk">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_jdw_krj_peg->hk->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_jdw_krj_peg->hk->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_jdw_krj_peg->hk->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_jdw_krj_peg_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_jdw_krj_peg_grid->StartRec = 1;
$t_jdw_krj_peg_grid->StopRec = $t_jdw_krj_peg_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_jdw_krj_peg_grid->FormKeyCountName) && ($t_jdw_krj_peg->CurrentAction == "gridadd" || $t_jdw_krj_peg->CurrentAction == "gridedit" || $t_jdw_krj_peg->CurrentAction == "F")) {
		$t_jdw_krj_peg_grid->KeyCount = $objForm->GetValue($t_jdw_krj_peg_grid->FormKeyCountName);
		$t_jdw_krj_peg_grid->StopRec = $t_jdw_krj_peg_grid->StartRec + $t_jdw_krj_peg_grid->KeyCount - 1;
	}
}
$t_jdw_krj_peg_grid->RecCnt = $t_jdw_krj_peg_grid->StartRec - 1;
if ($t_jdw_krj_peg_grid->Recordset && !$t_jdw_krj_peg_grid->Recordset->EOF) {
	$t_jdw_krj_peg_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_jdw_krj_peg_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_jdw_krj_peg_grid->StartRec > 1)
		$t_jdw_krj_peg_grid->Recordset->Move($t_jdw_krj_peg_grid->StartRec - 1);
} elseif (!$t_jdw_krj_peg->AllowAddDeleteRow && $t_jdw_krj_peg_grid->StopRec == 0) {
	$t_jdw_krj_peg_grid->StopRec = $t_jdw_krj_peg->GridAddRowCount;
}

// Initialize aggregate
$t_jdw_krj_peg->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_jdw_krj_peg->ResetAttrs();
$t_jdw_krj_peg_grid->RenderRow();
if ($t_jdw_krj_peg->CurrentAction == "gridadd")
	$t_jdw_krj_peg_grid->RowIndex = 0;
if ($t_jdw_krj_peg->CurrentAction == "gridedit")
	$t_jdw_krj_peg_grid->RowIndex = 0;
while ($t_jdw_krj_peg_grid->RecCnt < $t_jdw_krj_peg_grid->StopRec) {
	$t_jdw_krj_peg_grid->RecCnt++;
	if (intval($t_jdw_krj_peg_grid->RecCnt) >= intval($t_jdw_krj_peg_grid->StartRec)) {
		$t_jdw_krj_peg_grid->RowCnt++;
		if ($t_jdw_krj_peg->CurrentAction == "gridadd" || $t_jdw_krj_peg->CurrentAction == "gridedit" || $t_jdw_krj_peg->CurrentAction == "F") {
			$t_jdw_krj_peg_grid->RowIndex++;
			$objForm->Index = $t_jdw_krj_peg_grid->RowIndex;
			if ($objForm->HasValue($t_jdw_krj_peg_grid->FormActionName))
				$t_jdw_krj_peg_grid->RowAction = strval($objForm->GetValue($t_jdw_krj_peg_grid->FormActionName));
			elseif ($t_jdw_krj_peg->CurrentAction == "gridadd")
				$t_jdw_krj_peg_grid->RowAction = "insert";
			else
				$t_jdw_krj_peg_grid->RowAction = "";
		}

		// Set up key count
		$t_jdw_krj_peg_grid->KeyCount = $t_jdw_krj_peg_grid->RowIndex;

		// Init row class and style
		$t_jdw_krj_peg->ResetAttrs();
		$t_jdw_krj_peg->CssClass = "";
		if ($t_jdw_krj_peg->CurrentAction == "gridadd") {
			if ($t_jdw_krj_peg->CurrentMode == "copy") {
				$t_jdw_krj_peg_grid->LoadRowValues($t_jdw_krj_peg_grid->Recordset); // Load row values
				$t_jdw_krj_peg_grid->SetRecordKey($t_jdw_krj_peg_grid->RowOldKey, $t_jdw_krj_peg_grid->Recordset); // Set old record key
			} else {
				$t_jdw_krj_peg_grid->LoadDefaultValues(); // Load default values
				$t_jdw_krj_peg_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_jdw_krj_peg_grid->LoadRowValues($t_jdw_krj_peg_grid->Recordset); // Load row values
		}
		$t_jdw_krj_peg->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_jdw_krj_peg->CurrentAction == "gridadd") // Grid add
			$t_jdw_krj_peg->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_jdw_krj_peg->CurrentAction == "gridadd" && $t_jdw_krj_peg->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_jdw_krj_peg_grid->RestoreCurrentRowFormValues($t_jdw_krj_peg_grid->RowIndex); // Restore form values
		if ($t_jdw_krj_peg->CurrentAction == "gridedit") { // Grid edit
			if ($t_jdw_krj_peg->EventCancelled) {
				$t_jdw_krj_peg_grid->RestoreCurrentRowFormValues($t_jdw_krj_peg_grid->RowIndex); // Restore form values
			}
			if ($t_jdw_krj_peg_grid->RowAction == "insert")
				$t_jdw_krj_peg->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_jdw_krj_peg->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_jdw_krj_peg->CurrentAction == "gridedit" && ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT || $t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) && $t_jdw_krj_peg->EventCancelled) // Update failed
			$t_jdw_krj_peg_grid->RestoreCurrentRowFormValues($t_jdw_krj_peg_grid->RowIndex); // Restore form values
		if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_jdw_krj_peg_grid->EditRowCnt++;
		if ($t_jdw_krj_peg->CurrentAction == "F") // Confirm row
			$t_jdw_krj_peg_grid->RestoreCurrentRowFormValues($t_jdw_krj_peg_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_jdw_krj_peg->RowAttrs = array_merge($t_jdw_krj_peg->RowAttrs, array('data-rowindex'=>$t_jdw_krj_peg_grid->RowCnt, 'id'=>'r' . $t_jdw_krj_peg_grid->RowCnt . '_t_jdw_krj_peg', 'data-rowtype'=>$t_jdw_krj_peg->RowType));

		// Render row
		$t_jdw_krj_peg_grid->RenderRow();

		// Render list options
		$t_jdw_krj_peg_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_jdw_krj_peg_grid->RowAction <> "delete" && $t_jdw_krj_peg_grid->RowAction <> "insertdelete" && !($t_jdw_krj_peg_grid->RowAction == "insert" && $t_jdw_krj_peg->CurrentAction == "F" && $t_jdw_krj_peg_grid->EmptyRow())) {
?>
	<tr<?php echo $t_jdw_krj_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_jdw_krj_peg_grid->ListOptions->Render("body", "left", $t_jdw_krj_peg_grid->RowCnt);
?>
	<?php if ($t_jdw_krj_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_jdw_krj_peg->pegawai_id->CellAttributes() ?>>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_jdw_krj_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<span<?php echo $t_jdw_krj_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->pegawai_id->EditValue ?>"<?php echo $t_jdw_krj_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_jdw_krj_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<span<?php echo $t_jdw_krj_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->pegawai_id->EditValue ?>"<?php echo $t_jdw_krj_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_pegawai_id" class="t_jdw_krj_peg_pegawai_id">
<span<?php echo $t_jdw_krj_peg->pegawai_id->ViewAttributes() ?>>
<?php echo $t_jdw_krj_peg->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_jdw_krj_peg_grid->PageObjName . "_row_" . $t_jdw_krj_peg_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jdw_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jdw_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jdw_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jdw_id->CurrentValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jdw_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jdw_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jdw_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jdw_id->OldValue) ?>">
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT || $t_jdw_krj_peg->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jdw_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jdw_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jdw_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jdw_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_jdw_krj_peg->tgl1->Visible) { // tgl1 ?>
		<td data-name="tgl1"<?php echo $t_jdw_krj_peg->tgl1->CellAttributes() ?>>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_tgl1" class="form-group t_jdw_krj_peg_tgl1">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->tgl1->EditValue ?>"<?php echo $t_jdw_krj_peg->tgl1->EditAttributes() ?>>
<?php if (!$t_jdw_krj_peg->tgl1->ReadOnly && !$t_jdw_krj_peg->tgl1->Disabled && !isset($t_jdw_krj_peg->tgl1->EditAttrs["readonly"]) && !isset($t_jdw_krj_peg->tgl1->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_jdw_krj_peggrid", "x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->OldValue) ?>">
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_tgl1" class="form-group t_jdw_krj_peg_tgl1">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->tgl1->EditValue ?>"<?php echo $t_jdw_krj_peg->tgl1->EditAttributes() ?>>
<?php if (!$t_jdw_krj_peg->tgl1->ReadOnly && !$t_jdw_krj_peg->tgl1->Disabled && !isset($t_jdw_krj_peg->tgl1->EditAttrs["readonly"]) && !isset($t_jdw_krj_peg->tgl1->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_jdw_krj_peggrid", "x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_tgl1" class="t_jdw_krj_peg_tgl1">
<span<?php echo $t_jdw_krj_peg->tgl1->ViewAttributes() ?>>
<?php echo $t_jdw_krj_peg->tgl1->ListViewValue() ?></span>
</span>
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->tgl2->Visible) { // tgl2 ?>
		<td data-name="tgl2"<?php echo $t_jdw_krj_peg->tgl2->CellAttributes() ?>>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_tgl2" class="form-group t_jdw_krj_peg_tgl2">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->tgl2->EditValue ?>"<?php echo $t_jdw_krj_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_jdw_krj_peg->tgl2->ReadOnly && !$t_jdw_krj_peg->tgl2->Disabled && !isset($t_jdw_krj_peg->tgl2->EditAttrs["readonly"]) && !isset($t_jdw_krj_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_jdw_krj_peggrid", "x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->OldValue) ?>">
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_tgl2" class="form-group t_jdw_krj_peg_tgl2">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->tgl2->EditValue ?>"<?php echo $t_jdw_krj_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_jdw_krj_peg->tgl2->ReadOnly && !$t_jdw_krj_peg->tgl2->Disabled && !isset($t_jdw_krj_peg->tgl2->EditAttrs["readonly"]) && !isset($t_jdw_krj_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_jdw_krj_peggrid", "x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_tgl2" class="t_jdw_krj_peg_tgl2">
<span<?php echo $t_jdw_krj_peg->tgl2->ViewAttributes() ?>>
<?php echo $t_jdw_krj_peg->tgl2->ListViewValue() ?></span>
</span>
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->jk_id->Visible) { // jk_id ?>
		<td data-name="jk_id"<?php echo $t_jdw_krj_peg->jk_id->CellAttributes() ?>>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_jk_id" class="form-group t_jdw_krj_peg_jk_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id"><?php echo (strval($t_jdw_krj_peg->jk_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_jdw_krj_peg->jk_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_jdw_krj_peg->jk_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_jdw_krj_peg->jk_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo $t_jdw_krj_peg->jk_id->CurrentValue ?>"<?php echo $t_jdw_krj_peg->jk_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="s_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo $t_jdw_krj_peg->jk_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->OldValue) ?>">
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_jk_id" class="form-group t_jdw_krj_peg_jk_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id"><?php echo (strval($t_jdw_krj_peg->jk_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_jdw_krj_peg->jk_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_jdw_krj_peg->jk_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_jdw_krj_peg->jk_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo $t_jdw_krj_peg->jk_id->CurrentValue ?>"<?php echo $t_jdw_krj_peg->jk_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="s_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo $t_jdw_krj_peg->jk_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_jk_id" class="t_jdw_krj_peg_jk_id">
<span<?php echo $t_jdw_krj_peg->jk_id->ViewAttributes() ?>>
<?php echo $t_jdw_krj_peg->jk_id->ListViewValue() ?></span>
</span>
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->hk->Visible) { // hk ?>
		<td data-name="hk"<?php echo $t_jdw_krj_peg->hk->CellAttributes() ?>>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_hk" class="form-group t_jdw_krj_peg_hk">
<div id="tp_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" class="ewTemplate"><input type="radio" data-table="t_jdw_krj_peg" data-field="x_hk" data-value-separator="<?php echo $t_jdw_krj_peg->hk->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="{value}"<?php echo $t_jdw_krj_peg->hk->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_jdw_krj_peg->hk->RadioButtonListHtml(FALSE, "x{$t_jdw_krj_peg_grid->RowIndex}_hk") ?>
</div></div>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->OldValue) ?>">
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_hk" class="form-group t_jdw_krj_peg_hk">
<div id="tp_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" class="ewTemplate"><input type="radio" data-table="t_jdw_krj_peg" data-field="x_hk" data-value-separator="<?php echo $t_jdw_krj_peg->hk->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="{value}"<?php echo $t_jdw_krj_peg->hk->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_jdw_krj_peg->hk->RadioButtonListHtml(FALSE, "x{$t_jdw_krj_peg_grid->RowIndex}_hk") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_jdw_krj_peg_grid->RowCnt ?>_t_jdw_krj_peg_hk" class="t_jdw_krj_peg_hk">
<span<?php echo $t_jdw_krj_peg->hk->ViewAttributes() ?>>
<?php echo $t_jdw_krj_peg->hk->ListViewValue() ?></span>
</span>
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="ft_jdw_krj_peggrid$x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->FormValue) ?>">
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="ft_jdw_krj_peggrid$o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_jdw_krj_peg_grid->ListOptions->Render("body", "right", $t_jdw_krj_peg_grid->RowCnt);
?>
	</tr>
<?php if ($t_jdw_krj_peg->RowType == EW_ROWTYPE_ADD || $t_jdw_krj_peg->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_jdw_krj_peggrid.UpdateOpts(<?php echo $t_jdw_krj_peg_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_jdw_krj_peg->CurrentAction <> "gridadd" || $t_jdw_krj_peg->CurrentMode == "copy")
		if (!$t_jdw_krj_peg_grid->Recordset->EOF) $t_jdw_krj_peg_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_jdw_krj_peg->CurrentMode == "add" || $t_jdw_krj_peg->CurrentMode == "copy" || $t_jdw_krj_peg->CurrentMode == "edit") {
		$t_jdw_krj_peg_grid->RowIndex = '$rowindex$';
		$t_jdw_krj_peg_grid->LoadDefaultValues();

		// Set row properties
		$t_jdw_krj_peg->ResetAttrs();
		$t_jdw_krj_peg->RowAttrs = array_merge($t_jdw_krj_peg->RowAttrs, array('data-rowindex'=>$t_jdw_krj_peg_grid->RowIndex, 'id'=>'r0_t_jdw_krj_peg', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_jdw_krj_peg->RowAttrs["class"], "ewTemplate");
		$t_jdw_krj_peg->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_jdw_krj_peg_grid->RenderRow();

		// Render list options
		$t_jdw_krj_peg_grid->RenderListOptions();
		$t_jdw_krj_peg_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_jdw_krj_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_jdw_krj_peg_grid->ListOptions->Render("body", "left", $t_jdw_krj_peg_grid->RowIndex);
?>
	<?php if ($t_jdw_krj_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<?php if ($t_jdw_krj_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<span<?php echo $t_jdw_krj_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->pegawai_id->EditValue ?>"<?php echo $t_jdw_krj_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_jdw_krj_peg_pegawai_id" class="form-group t_jdw_krj_peg_pegawai_id">
<span<?php echo $t_jdw_krj_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_pegawai_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->tgl1->Visible) { // tgl1 ?>
		<td data-name="tgl1">
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_jdw_krj_peg_tgl1" class="form-group t_jdw_krj_peg_tgl1">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->tgl1->EditValue ?>"<?php echo $t_jdw_krj_peg->tgl1->EditAttributes() ?>>
<?php if (!$t_jdw_krj_peg->tgl1->ReadOnly && !$t_jdw_krj_peg->tgl1->Disabled && !isset($t_jdw_krj_peg->tgl1->EditAttrs["readonly"]) && !isset($t_jdw_krj_peg->tgl1->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_jdw_krj_peggrid", "x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_jdw_krj_peg_tgl1" class="form-group t_jdw_krj_peg_tgl1">
<span<?php echo $t_jdw_krj_peg->tgl1->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->tgl1->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl1" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl1" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl1->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->tgl2->Visible) { // tgl2 ?>
		<td data-name="tgl2">
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_jdw_krj_peg_tgl2" class="form-group t_jdw_krj_peg_tgl2">
<input type="text" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" placeholder="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_jdw_krj_peg->tgl2->EditValue ?>"<?php echo $t_jdw_krj_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_jdw_krj_peg->tgl2->ReadOnly && !$t_jdw_krj_peg->tgl2->Disabled && !isset($t_jdw_krj_peg->tgl2->EditAttrs["readonly"]) && !isset($t_jdw_krj_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_jdw_krj_peggrid", "x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_jdw_krj_peg_tgl2" class="form-group t_jdw_krj_peg_tgl2">
<span<?php echo $t_jdw_krj_peg->tgl2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->tgl2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_tgl2" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->tgl2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->jk_id->Visible) { // jk_id ?>
		<td data-name="jk_id">
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_jdw_krj_peg_jk_id" class="form-group t_jdw_krj_peg_jk_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id"><?php echo (strval($t_jdw_krj_peg->jk_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_jdw_krj_peg->jk_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_jdw_krj_peg->jk_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_jdw_krj_peg->jk_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo $t_jdw_krj_peg->jk_id->CurrentValue ?>"<?php echo $t_jdw_krj_peg->jk_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="s_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo $t_jdw_krj_peg->jk_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_jdw_krj_peg_jk_id" class="form-group t_jdw_krj_peg_jk_id">
<span<?php echo $t_jdw_krj_peg->jk_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->jk_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_jk_id" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_jk_id" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->jk_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_jdw_krj_peg->hk->Visible) { // hk ?>
		<td data-name="hk">
<?php if ($t_jdw_krj_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_jdw_krj_peg_hk" class="form-group t_jdw_krj_peg_hk">
<div id="tp_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" class="ewTemplate"><input type="radio" data-table="t_jdw_krj_peg" data-field="x_hk" data-value-separator="<?php echo $t_jdw_krj_peg->hk->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="{value}"<?php echo $t_jdw_krj_peg->hk->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_jdw_krj_peg->hk->RadioButtonListHtml(FALSE, "x{$t_jdw_krj_peg_grid->RowIndex}_hk") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_jdw_krj_peg_hk" class="form-group t_jdw_krj_peg_hk">
<span<?php echo $t_jdw_krj_peg->hk->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_jdw_krj_peg->hk->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="x<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_jdw_krj_peg" data-field="x_hk" name="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" id="o<?php echo $t_jdw_krj_peg_grid->RowIndex ?>_hk" value="<?php echo ew_HtmlEncode($t_jdw_krj_peg->hk->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_jdw_krj_peg_grid->ListOptions->Render("body", "right", $t_jdw_krj_peg_grid->RowCnt);
?>
<script type="text/javascript">
ft_jdw_krj_peggrid.UpdateOpts(<?php echo $t_jdw_krj_peg_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_jdw_krj_peg->CurrentMode == "add" || $t_jdw_krj_peg->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_jdw_krj_peg_grid->FormKeyCountName ?>" id="<?php echo $t_jdw_krj_peg_grid->FormKeyCountName ?>" value="<?php echo $t_jdw_krj_peg_grid->KeyCount ?>">
<?php echo $t_jdw_krj_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_jdw_krj_peg->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_jdw_krj_peg_grid->FormKeyCountName ?>" id="<?php echo $t_jdw_krj_peg_grid->FormKeyCountName ?>" value="<?php echo $t_jdw_krj_peg_grid->KeyCount ?>">
<?php echo $t_jdw_krj_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_jdw_krj_peg->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_jdw_krj_peggrid">
</div>
<?php

// Close recordset
if ($t_jdw_krj_peg_grid->Recordset)
	$t_jdw_krj_peg_grid->Recordset->Close();
?>
<?php if ($t_jdw_krj_peg_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_jdw_krj_peg_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_jdw_krj_peg_grid->TotalRecs == 0 && $t_jdw_krj_peg->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_jdw_krj_peg_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_jdw_krj_peg->Export == "") { ?>
<script type="text/javascript">
ft_jdw_krj_peggrid.Init();
</script>
<?php } ?>
<?php
$t_jdw_krj_peg_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_jdw_krj_peg_grid->Page_Terminate();
?>
