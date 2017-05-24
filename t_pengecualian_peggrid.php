<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_pengecualian_peg_grid)) $t_pengecualian_peg_grid = new ct_pengecualian_peg_grid();

// Page init
$t_pengecualian_peg_grid->Page_Init();

// Page main
$t_pengecualian_peg_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pengecualian_peg_grid->Page_Render();
?>
<?php if ($t_pengecualian_peg->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_pengecualian_peggrid = new ew_Form("ft_pengecualian_peggrid", "grid");
ft_pengecualian_peggrid.FormKeyCountName = '<?php echo $t_pengecualian_peg_grid->FormKeyCountName ?>';

// Validate form
ft_pengecualian_peggrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pengecualian_peg->pegawai_id->FldCaption(), $t_pengecualian_peg->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jns_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pengecualian_peg->jns_id->FldCaption(), $t_pengecualian_peg->jns_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pengecualian_peg->tgl->FldCaption(), $t_pengecualian_peg->tgl->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pengecualian_peg->tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pengecualian_peg->tgl2->FldCaption(), $t_pengecualian_peg->tgl2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl2");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pengecualian_peg->tgl2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_masuk");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pengecualian_peg->jam_masuk->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_keluar");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pengecualian_peg->jam_keluar->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_pengecualian_peggrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jns_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jam_masuk", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jam_keluar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pegawai_id2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pegawai_id3", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_pengecualian_peggrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pengecualian_peggrid.ValidateRequired = true;
<?php } else { ?>
ft_pengecualian_peggrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pengecualian_peggrid.Lists["x_pegawai_id"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};
ft_pengecualian_peggrid.Lists["x_jns_id"] = {"LinkField":"x_jns_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_kode","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_jns_pengecualian"};
ft_pengecualian_peggrid.Lists["x_pegawai_id2"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};
ft_pengecualian_peggrid.Lists["x_pegawai_id3"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_pengecualian_peg->CurrentAction == "gridadd") {
	if ($t_pengecualian_peg->CurrentMode == "copy") {
		$bSelectLimit = $t_pengecualian_peg_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_pengecualian_peg_grid->TotalRecs = $t_pengecualian_peg->SelectRecordCount();
			$t_pengecualian_peg_grid->Recordset = $t_pengecualian_peg_grid->LoadRecordset($t_pengecualian_peg_grid->StartRec-1, $t_pengecualian_peg_grid->DisplayRecs);
		} else {
			if ($t_pengecualian_peg_grid->Recordset = $t_pengecualian_peg_grid->LoadRecordset())
				$t_pengecualian_peg_grid->TotalRecs = $t_pengecualian_peg_grid->Recordset->RecordCount();
		}
		$t_pengecualian_peg_grid->StartRec = 1;
		$t_pengecualian_peg_grid->DisplayRecs = $t_pengecualian_peg_grid->TotalRecs;
	} else {
		$t_pengecualian_peg->CurrentFilter = "0=1";
		$t_pengecualian_peg_grid->StartRec = 1;
		$t_pengecualian_peg_grid->DisplayRecs = $t_pengecualian_peg->GridAddRowCount;
	}
	$t_pengecualian_peg_grid->TotalRecs = $t_pengecualian_peg_grid->DisplayRecs;
	$t_pengecualian_peg_grid->StopRec = $t_pengecualian_peg_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_pengecualian_peg_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_pengecualian_peg_grid->TotalRecs <= 0)
			$t_pengecualian_peg_grid->TotalRecs = $t_pengecualian_peg->SelectRecordCount();
	} else {
		if (!$t_pengecualian_peg_grid->Recordset && ($t_pengecualian_peg_grid->Recordset = $t_pengecualian_peg_grid->LoadRecordset()))
			$t_pengecualian_peg_grid->TotalRecs = $t_pengecualian_peg_grid->Recordset->RecordCount();
	}
	$t_pengecualian_peg_grid->StartRec = 1;
	$t_pengecualian_peg_grid->DisplayRecs = $t_pengecualian_peg_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_pengecualian_peg_grid->Recordset = $t_pengecualian_peg_grid->LoadRecordset($t_pengecualian_peg_grid->StartRec-1, $t_pengecualian_peg_grid->DisplayRecs);

	// Set no record found message
	if ($t_pengecualian_peg->CurrentAction == "" && $t_pengecualian_peg_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_pengecualian_peg_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_pengecualian_peg_grid->SearchWhere == "0=101")
			$t_pengecualian_peg_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_pengecualian_peg_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_pengecualian_peg_grid->RenderOtherOptions();
?>
<?php $t_pengecualian_peg_grid->ShowPageHeader(); ?>
<?php
$t_pengecualian_peg_grid->ShowMessage();
?>
<?php if ($t_pengecualian_peg_grid->TotalRecs > 0 || $t_pengecualian_peg->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_pengecualian_peg">
<div id="ft_pengecualian_peggrid" class="ewForm form-inline">
<?php if ($t_pengecualian_peg_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_pengecualian_peg_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_pengecualian_peg" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_pengecualian_peggrid" class="table ewTable">
<?php echo $t_pengecualian_peg->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_pengecualian_peg_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_pengecualian_peg_grid->RenderListOptions();

// Render list options (header, left)
$t_pengecualian_peg_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_pengecualian_peg->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_t_pengecualian_peg_pegawai_id" class="t_pengecualian_peg_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_t_pengecualian_peg_pegawai_id" class="t_pengecualian_peg_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->jns_id->Visible) { // jns_id ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->jns_id) == "") { ?>
		<th data-name="jns_id"><div id="elh_t_pengecualian_peg_jns_id" class="t_pengecualian_peg_jns_id"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->jns_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jns_id"><div><div id="elh_t_pengecualian_peg_jns_id" class="t_pengecualian_peg_jns_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->jns_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->jns_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->jns_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->tgl->Visible) { // tgl ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->tgl) == "") { ?>
		<th data-name="tgl"><div id="elh_t_pengecualian_peg_tgl" class="t_pengecualian_peg_tgl"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->tgl->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl"><div><div id="elh_t_pengecualian_peg_tgl" class="t_pengecualian_peg_tgl">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->tgl->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->tgl2->Visible) { // tgl2 ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->tgl2) == "") { ?>
		<th data-name="tgl2"><div id="elh_t_pengecualian_peg_tgl2" class="t_pengecualian_peg_tgl2"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->tgl2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl2"><div><div id="elh_t_pengecualian_peg_tgl2" class="t_pengecualian_peg_tgl2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->tgl2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->tgl2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->tgl2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->jam_masuk->Visible) { // jam_masuk ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->jam_masuk) == "") { ?>
		<th data-name="jam_masuk"><div id="elh_t_pengecualian_peg_jam_masuk" class="t_pengecualian_peg_jam_masuk"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->jam_masuk->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jam_masuk"><div><div id="elh_t_pengecualian_peg_jam_masuk" class="t_pengecualian_peg_jam_masuk">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->jam_masuk->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->jam_masuk->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->jam_masuk->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->jam_keluar->Visible) { // jam_keluar ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->jam_keluar) == "") { ?>
		<th data-name="jam_keluar"><div id="elh_t_pengecualian_peg_jam_keluar" class="t_pengecualian_peg_jam_keluar"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->jam_keluar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jam_keluar"><div><div id="elh_t_pengecualian_peg_jam_keluar" class="t_pengecualian_peg_jam_keluar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->jam_keluar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->jam_keluar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->jam_keluar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->pegawai_id2->Visible) { // pegawai_id2 ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->pegawai_id2) == "") { ?>
		<th data-name="pegawai_id2"><div id="elh_t_pengecualian_peg_pegawai_id2" class="t_pengecualian_peg_pegawai_id2"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->pegawai_id2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id2"><div><div id="elh_t_pengecualian_peg_pegawai_id2" class="t_pengecualian_peg_pegawai_id2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->pegawai_id2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->pegawai_id2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->pegawai_id2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pengecualian_peg->pegawai_id3->Visible) { // pegawai_id3 ?>
	<?php if ($t_pengecualian_peg->SortUrl($t_pengecualian_peg->pegawai_id3) == "") { ?>
		<th data-name="pegawai_id3"><div id="elh_t_pengecualian_peg_pegawai_id3" class="t_pengecualian_peg_pegawai_id3"><div class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->pegawai_id3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id3"><div><div id="elh_t_pengecualian_peg_pegawai_id3" class="t_pengecualian_peg_pegawai_id3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pengecualian_peg->pegawai_id3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pengecualian_peg->pegawai_id3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pengecualian_peg->pegawai_id3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_pengecualian_peg_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_pengecualian_peg_grid->StartRec = 1;
$t_pengecualian_peg_grid->StopRec = $t_pengecualian_peg_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_pengecualian_peg_grid->FormKeyCountName) && ($t_pengecualian_peg->CurrentAction == "gridadd" || $t_pengecualian_peg->CurrentAction == "gridedit" || $t_pengecualian_peg->CurrentAction == "F")) {
		$t_pengecualian_peg_grid->KeyCount = $objForm->GetValue($t_pengecualian_peg_grid->FormKeyCountName);
		$t_pengecualian_peg_grid->StopRec = $t_pengecualian_peg_grid->StartRec + $t_pengecualian_peg_grid->KeyCount - 1;
	}
}
$t_pengecualian_peg_grid->RecCnt = $t_pengecualian_peg_grid->StartRec - 1;
if ($t_pengecualian_peg_grid->Recordset && !$t_pengecualian_peg_grid->Recordset->EOF) {
	$t_pengecualian_peg_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_pengecualian_peg_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_pengecualian_peg_grid->StartRec > 1)
		$t_pengecualian_peg_grid->Recordset->Move($t_pengecualian_peg_grid->StartRec - 1);
} elseif (!$t_pengecualian_peg->AllowAddDeleteRow && $t_pengecualian_peg_grid->StopRec == 0) {
	$t_pengecualian_peg_grid->StopRec = $t_pengecualian_peg->GridAddRowCount;
}

// Initialize aggregate
$t_pengecualian_peg->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_pengecualian_peg->ResetAttrs();
$t_pengecualian_peg_grid->RenderRow();
if ($t_pengecualian_peg->CurrentAction == "gridadd")
	$t_pengecualian_peg_grid->RowIndex = 0;
if ($t_pengecualian_peg->CurrentAction == "gridedit")
	$t_pengecualian_peg_grid->RowIndex = 0;
while ($t_pengecualian_peg_grid->RecCnt < $t_pengecualian_peg_grid->StopRec) {
	$t_pengecualian_peg_grid->RecCnt++;
	if (intval($t_pengecualian_peg_grid->RecCnt) >= intval($t_pengecualian_peg_grid->StartRec)) {
		$t_pengecualian_peg_grid->RowCnt++;
		if ($t_pengecualian_peg->CurrentAction == "gridadd" || $t_pengecualian_peg->CurrentAction == "gridedit" || $t_pengecualian_peg->CurrentAction == "F") {
			$t_pengecualian_peg_grid->RowIndex++;
			$objForm->Index = $t_pengecualian_peg_grid->RowIndex;
			if ($objForm->HasValue($t_pengecualian_peg_grid->FormActionName))
				$t_pengecualian_peg_grid->RowAction = strval($objForm->GetValue($t_pengecualian_peg_grid->FormActionName));
			elseif ($t_pengecualian_peg->CurrentAction == "gridadd")
				$t_pengecualian_peg_grid->RowAction = "insert";
			else
				$t_pengecualian_peg_grid->RowAction = "";
		}

		// Set up key count
		$t_pengecualian_peg_grid->KeyCount = $t_pengecualian_peg_grid->RowIndex;

		// Init row class and style
		$t_pengecualian_peg->ResetAttrs();
		$t_pengecualian_peg->CssClass = "";
		if ($t_pengecualian_peg->CurrentAction == "gridadd") {
			if ($t_pengecualian_peg->CurrentMode == "copy") {
				$t_pengecualian_peg_grid->LoadRowValues($t_pengecualian_peg_grid->Recordset); // Load row values
				$t_pengecualian_peg_grid->SetRecordKey($t_pengecualian_peg_grid->RowOldKey, $t_pengecualian_peg_grid->Recordset); // Set old record key
			} else {
				$t_pengecualian_peg_grid->LoadDefaultValues(); // Load default values
				$t_pengecualian_peg_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_pengecualian_peg_grid->LoadRowValues($t_pengecualian_peg_grid->Recordset); // Load row values
		}
		$t_pengecualian_peg->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_pengecualian_peg->CurrentAction == "gridadd") // Grid add
			$t_pengecualian_peg->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_pengecualian_peg->CurrentAction == "gridadd" && $t_pengecualian_peg->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_pengecualian_peg_grid->RestoreCurrentRowFormValues($t_pengecualian_peg_grid->RowIndex); // Restore form values
		if ($t_pengecualian_peg->CurrentAction == "gridedit") { // Grid edit
			if ($t_pengecualian_peg->EventCancelled) {
				$t_pengecualian_peg_grid->RestoreCurrentRowFormValues($t_pengecualian_peg_grid->RowIndex); // Restore form values
			}
			if ($t_pengecualian_peg_grid->RowAction == "insert")
				$t_pengecualian_peg->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_pengecualian_peg->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_pengecualian_peg->CurrentAction == "gridedit" && ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT || $t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) && $t_pengecualian_peg->EventCancelled) // Update failed
			$t_pengecualian_peg_grid->RestoreCurrentRowFormValues($t_pengecualian_peg_grid->RowIndex); // Restore form values
		if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_pengecualian_peg_grid->EditRowCnt++;
		if ($t_pengecualian_peg->CurrentAction == "F") // Confirm row
			$t_pengecualian_peg_grid->RestoreCurrentRowFormValues($t_pengecualian_peg_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_pengecualian_peg->RowAttrs = array_merge($t_pengecualian_peg->RowAttrs, array('data-rowindex'=>$t_pengecualian_peg_grid->RowCnt, 'id'=>'r' . $t_pengecualian_peg_grid->RowCnt . '_t_pengecualian_peg', 'data-rowtype'=>$t_pengecualian_peg->RowType));

		// Render row
		$t_pengecualian_peg_grid->RenderRow();

		// Render list options
		$t_pengecualian_peg_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_pengecualian_peg_grid->RowAction <> "delete" && $t_pengecualian_peg_grid->RowAction <> "insertdelete" && !($t_pengecualian_peg_grid->RowAction == "insert" && $t_pengecualian_peg->CurrentAction == "F" && $t_pengecualian_peg_grid->EmptyRow())) {
?>
	<tr<?php echo $t_pengecualian_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_pengecualian_peg_grid->ListOptions->Render("body", "left", $t_pengecualian_peg_grid->RowCnt);
?>
	<?php if ($t_pengecualian_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $t_pengecualian_peg->pegawai_id->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_pengecualian_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span<?php echo $t_pengecualian_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_pengecualian_peg->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_pengecualian_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span<?php echo $t_pengecualian_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_pengecualian_peg->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id" class="t_pengecualian_peg_pegawai_id">
<span<?php echo $t_pengecualian_peg->pegawai_id->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_pengecualian_peg_grid->PageObjName . "_row_" . $t_pengecualian_peg_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pengecualian_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pengecualian_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pengecualian_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pengecualian_id->CurrentValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pengecualian_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pengecualian_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pengecualian_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pengecualian_id->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT || $t_pengecualian_peg->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pengecualian_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pengecualian_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pengecualian_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pengecualian_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_pengecualian_peg->jns_id->Visible) { // jns_id ?>
		<td data-name="jns_id"<?php echo $t_pengecualian_peg->jns_id->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jns_id" class="form-group t_pengecualian_peg_jns_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id"><?php echo (strval($t_pengecualian_peg->jns_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->jns_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->jns_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->jns_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->jns_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jns_id" class="form-group t_pengecualian_peg_jns_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id"><?php echo (strval($t_pengecualian_peg->jns_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->jns_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->jns_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->jns_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->jns_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jns_id" class="t_pengecualian_peg_jns_id">
<span<?php echo $t_pengecualian_peg->jns_id->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->jns_id->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->tgl->Visible) { // tgl ?>
		<td data-name="tgl"<?php echo $t_pengecualian_peg->tgl->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_tgl" class="form-group t_pengecualian_peg_tgl">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl->EditValue ?>"<?php echo $t_pengecualian_peg->tgl->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl->ReadOnly && !$t_pengecualian_peg->tgl->Disabled && !isset($t_pengecualian_peg->tgl->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_peggrid", "x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_tgl" class="form-group t_pengecualian_peg_tgl">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl->EditValue ?>"<?php echo $t_pengecualian_peg->tgl->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl->ReadOnly && !$t_pengecualian_peg->tgl->Disabled && !isset($t_pengecualian_peg->tgl->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_peggrid", "x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_tgl" class="t_pengecualian_peg_tgl">
<span<?php echo $t_pengecualian_peg->tgl->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->tgl->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->tgl2->Visible) { // tgl2 ?>
		<td data-name="tgl2"<?php echo $t_pengecualian_peg->tgl2->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_tgl2" class="form-group t_pengecualian_peg_tgl2">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl2->EditValue ?>"<?php echo $t_pengecualian_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl2->ReadOnly && !$t_pengecualian_peg->tgl2->Disabled && !isset($t_pengecualian_peg->tgl2->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_peggrid", "x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_tgl2" class="form-group t_pengecualian_peg_tgl2">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl2->EditValue ?>"<?php echo $t_pengecualian_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl2->ReadOnly && !$t_pengecualian_peg->tgl2->Disabled && !isset($t_pengecualian_peg->tgl2->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_peggrid", "x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_tgl2" class="t_pengecualian_peg_tgl2">
<span<?php echo $t_pengecualian_peg->tgl2->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->tgl2->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->jam_masuk->Visible) { // jam_masuk ?>
		<td data-name="jam_masuk"<?php echo $t_pengecualian_peg->jam_masuk->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jam_masuk" class="form-group t_pengecualian_peg_jam_masuk">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_masuk" data-format="9" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_masuk->EditValue ?>"<?php echo $t_pengecualian_peg->jam_masuk->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jam_masuk" class="form-group t_pengecualian_peg_jam_masuk">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_masuk" data-format="9" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_masuk->EditValue ?>"<?php echo $t_pengecualian_peg->jam_masuk->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jam_masuk" class="t_pengecualian_peg_jam_masuk">
<span<?php echo $t_pengecualian_peg->jam_masuk->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->jam_masuk->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->jam_keluar->Visible) { // jam_keluar ?>
		<td data-name="jam_keluar"<?php echo $t_pengecualian_peg->jam_keluar->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jam_keluar" class="form-group t_pengecualian_peg_jam_keluar">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_keluar" data-format="9" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_keluar->EditValue ?>"<?php echo $t_pengecualian_peg->jam_keluar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jam_keluar" class="form-group t_pengecualian_peg_jam_keluar">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_keluar" data-format="9" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_keluar->EditValue ?>"<?php echo $t_pengecualian_peg->jam_keluar->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_jam_keluar" class="t_pengecualian_peg_jam_keluar">
<span<?php echo $t_pengecualian_peg->jam_keluar->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->jam_keluar->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->pegawai_id2->Visible) { // pegawai_id2 ?>
		<td data-name="pegawai_id2"<?php echo $t_pengecualian_peg->pegawai_id2->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id2" class="form-group t_pengecualian_peg_pegawai_id2">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2"><?php echo (strval($t_pengecualian_peg->pegawai_id2->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id2->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id2->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id2->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id2->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id2" class="form-group t_pengecualian_peg_pegawai_id2">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2"><?php echo (strval($t_pengecualian_peg->pegawai_id2->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id2->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id2->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id2->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id2->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id2" class="t_pengecualian_peg_pegawai_id2">
<span<?php echo $t_pengecualian_peg->pegawai_id2->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->pegawai_id2->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->pegawai_id3->Visible) { // pegawai_id3 ?>
		<td data-name="pegawai_id3"<?php echo $t_pengecualian_peg->pegawai_id3->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id3" class="form-group t_pengecualian_peg_pegawai_id3">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3"><?php echo (strval($t_pengecualian_peg->pegawai_id3->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id3->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id3->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id3->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id3->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->OldValue) ?>">
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id3" class="form-group t_pengecualian_peg_pegawai_id3">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3"><?php echo (strval($t_pengecualian_peg->pegawai_id3->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id3->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id3->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id3->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id3->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pengecualian_peg_grid->RowCnt ?>_t_pengecualian_peg_pegawai_id3" class="t_pengecualian_peg_pegawai_id3">
<span<?php echo $t_pengecualian_peg->pegawai_id3->ViewAttributes() ?>>
<?php echo $t_pengecualian_peg->pegawai_id3->ListViewValue() ?></span>
</span>
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="ft_pengecualian_peggrid$x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->FormValue) ?>">
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="ft_pengecualian_peggrid$o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_pengecualian_peg_grid->ListOptions->Render("body", "right", $t_pengecualian_peg_grid->RowCnt);
?>
	</tr>
<?php if ($t_pengecualian_peg->RowType == EW_ROWTYPE_ADD || $t_pengecualian_peg->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_pengecualian_peggrid.UpdateOpts(<?php echo $t_pengecualian_peg_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_pengecualian_peg->CurrentAction <> "gridadd" || $t_pengecualian_peg->CurrentMode == "copy")
		if (!$t_pengecualian_peg_grid->Recordset->EOF) $t_pengecualian_peg_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_pengecualian_peg->CurrentMode == "add" || $t_pengecualian_peg->CurrentMode == "copy" || $t_pengecualian_peg->CurrentMode == "edit") {
		$t_pengecualian_peg_grid->RowIndex = '$rowindex$';
		$t_pengecualian_peg_grid->LoadDefaultValues();

		// Set row properties
		$t_pengecualian_peg->ResetAttrs();
		$t_pengecualian_peg->RowAttrs = array_merge($t_pengecualian_peg->RowAttrs, array('data-rowindex'=>$t_pengecualian_peg_grid->RowIndex, 'id'=>'r0_t_pengecualian_peg', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_pengecualian_peg->RowAttrs["class"], "ewTemplate");
		$t_pengecualian_peg->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_pengecualian_peg_grid->RenderRow();

		// Render list options
		$t_pengecualian_peg_grid->RenderListOptions();
		$t_pengecualian_peg_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_pengecualian_peg->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_pengecualian_peg_grid->ListOptions->Render("body", "left", $t_pengecualian_peg_grid->RowIndex);
?>
	<?php if ($t_pengecualian_peg->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<?php if ($t_pengecualian_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span<?php echo $t_pengecualian_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id"><?php echo (strval($t_pengecualian_peg->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id" class="form-group t_pengecualian_peg_pegawai_id">
<span<?php echo $t_pengecualian_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->jns_id->Visible) { // jns_id ?>
		<td data-name="jns_id">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_jns_id" class="form-group t_pengecualian_peg_jns_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id"><?php echo (strval($t_pengecualian_peg->jns_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->jns_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->jns_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->jns_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->jns_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_jns_id" class="form-group t_pengecualian_peg_jns_id">
<span<?php echo $t_pengecualian_peg->jns_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->jns_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jns_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jns_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->tgl->Visible) { // tgl ?>
		<td data-name="tgl">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_tgl" class="form-group t_pengecualian_peg_tgl">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl->EditValue ?>"<?php echo $t_pengecualian_peg->tgl->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl->ReadOnly && !$t_pengecualian_peg->tgl->Disabled && !isset($t_pengecualian_peg->tgl->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_peggrid", "x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_tgl" class="form-group t_pengecualian_peg_tgl">
<span<?php echo $t_pengecualian_peg->tgl->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->tgl->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->tgl2->Visible) { // tgl2 ?>
		<td data-name="tgl2">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_tgl2" class="form-group t_pengecualian_peg_tgl2">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl2->EditValue ?>"<?php echo $t_pengecualian_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl2->ReadOnly && !$t_pengecualian_peg->tgl2->Disabled && !isset($t_pengecualian_peg->tgl2->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_peggrid", "x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_tgl2" class="form-group t_pengecualian_peg_tgl2">
<span<?php echo $t_pengecualian_peg->tgl2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->tgl2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_tgl2" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_tgl2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->jam_masuk->Visible) { // jam_masuk ?>
		<td data-name="jam_masuk">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_jam_masuk" class="form-group t_pengecualian_peg_jam_masuk">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_masuk" data-format="9" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_masuk->EditValue ?>"<?php echo $t_pengecualian_peg->jam_masuk->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_jam_masuk" class="form-group t_pengecualian_peg_jam_masuk">
<span<?php echo $t_pengecualian_peg->jam_masuk->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->jam_masuk->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_masuk" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_masuk" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->jam_keluar->Visible) { // jam_keluar ?>
		<td data-name="jam_keluar">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_jam_keluar" class="form-group t_pengecualian_peg_jam_keluar">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_keluar" data-format="9" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_keluar->EditValue ?>"<?php echo $t_pengecualian_peg->jam_keluar->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_jam_keluar" class="form-group t_pengecualian_peg_jam_keluar">
<span<?php echo $t_pengecualian_peg->jam_keluar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->jam_keluar->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jam_keluar" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_jam_keluar" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->pegawai_id2->Visible) { // pegawai_id2 ?>
		<td data-name="pegawai_id2">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id2" class="form-group t_pengecualian_peg_pegawai_id2">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2"><?php echo (strval($t_pengecualian_peg->pegawai_id2->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id2->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id2->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id2->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id2->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id2" class="form-group t_pengecualian_peg_pegawai_id2">
<span<?php echo $t_pengecualian_peg->pegawai_id2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id2" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pengecualian_peg->pegawai_id3->Visible) { // pegawai_id3 ?>
		<td data-name="pegawai_id3">
<?php if ($t_pengecualian_peg->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id3" class="form-group t_pengecualian_peg_pegawai_id3">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3"><?php echo (strval($t_pengecualian_peg->pegawai_id3->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id3->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id3->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id3->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id3->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="s_x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pengecualian_peg_pegawai_id3" class="form-group t_pengecualian_peg_pegawai_id3">
<span<?php echo $t_pengecualian_peg->pegawai_id3->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id3->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="x<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" name="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" id="o<?php echo $t_pengecualian_peg_grid->RowIndex ?>_pegawai_id3" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id3->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_pengecualian_peg_grid->ListOptions->Render("body", "right", $t_pengecualian_peg_grid->RowCnt);
?>
<script type="text/javascript">
ft_pengecualian_peggrid.UpdateOpts(<?php echo $t_pengecualian_peg_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_pengecualian_peg->CurrentMode == "add" || $t_pengecualian_peg->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_pengecualian_peg_grid->FormKeyCountName ?>" id="<?php echo $t_pengecualian_peg_grid->FormKeyCountName ?>" value="<?php echo $t_pengecualian_peg_grid->KeyCount ?>">
<?php echo $t_pengecualian_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pengecualian_peg->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_pengecualian_peg_grid->FormKeyCountName ?>" id="<?php echo $t_pengecualian_peg_grid->FormKeyCountName ?>" value="<?php echo $t_pengecualian_peg_grid->KeyCount ?>">
<?php echo $t_pengecualian_peg_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pengecualian_peg->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_pengecualian_peggrid">
</div>
<?php

// Close recordset
if ($t_pengecualian_peg_grid->Recordset)
	$t_pengecualian_peg_grid->Recordset->Close();
?>
<?php if ($t_pengecualian_peg_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_pengecualian_peg_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_pengecualian_peg_grid->TotalRecs == 0 && $t_pengecualian_peg->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_pengecualian_peg_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_pengecualian_peg->Export == "") { ?>
<script type="text/javascript">
ft_pengecualian_peggrid.Init();
</script>
<?php } ?>
<?php
$t_pengecualian_peg_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_pengecualian_peg_grid->Page_Terminate();
?>
