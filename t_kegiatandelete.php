<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_kegiataninfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_kegiatan_delete = NULL; // Initialize page object first

class ct_kegiatan_delete extends ct_kegiatan {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_kegiatan';

	// Page object name
	var $PageObjName = 't_kegiatan_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_kegiatan)
		if (!isset($GLOBALS["t_kegiatan"]) || get_class($GLOBALS["t_kegiatan"]) == "ct_kegiatan") {
			$GLOBALS["t_kegiatan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_kegiatan"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_kegiatan', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_kegiatanlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->keg_nama->SetVisibility();
		$this->keg_ket->SetVisibility();
		$this->tarif_acuan->SetVisibility();
		$this->tarif1->SetVisibility();
		$this->tarif2->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_kegiatan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_kegiatan);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_kegiatanlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_kegiatan class, t_kegiataninfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("t_kegiatanlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->keg_id->setDbValue($rs->fields('keg_id'));
		$this->keg_nama->setDbValue($rs->fields('keg_nama'));
		$this->keg_ket->setDbValue($rs->fields('keg_ket'));
		$this->tarif_acuan->setDbValue($rs->fields('tarif_acuan'));
		$this->tarif1->setDbValue($rs->fields('tarif1'));
		$this->tarif2->setDbValue($rs->fields('tarif2'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->keg_id->DbValue = $row['keg_id'];
		$this->keg_nama->DbValue = $row['keg_nama'];
		$this->keg_ket->DbValue = $row['keg_ket'];
		$this->tarif_acuan->DbValue = $row['tarif_acuan'];
		$this->tarif1->DbValue = $row['tarif1'];
		$this->tarif2->DbValue = $row['tarif2'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->tarif1->FormValue == $this->tarif1->CurrentValue && is_numeric(ew_StrToFloat($this->tarif1->CurrentValue)))
			$this->tarif1->CurrentValue = ew_StrToFloat($this->tarif1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tarif2->FormValue == $this->tarif2->CurrentValue && is_numeric(ew_StrToFloat($this->tarif2->CurrentValue)))
			$this->tarif2->CurrentValue = ew_StrToFloat($this->tarif2->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// keg_id
		// keg_nama
		// keg_ket
		// tarif_acuan
		// tarif1
		// tarif2

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// keg_id
		$this->keg_id->ViewValue = $this->keg_id->CurrentValue;
		$this->keg_id->ViewCustomAttributes = "";

		// keg_nama
		$this->keg_nama->ViewValue = $this->keg_nama->CurrentValue;
		$this->keg_nama->ViewCustomAttributes = "";

		// keg_ket
		$this->keg_ket->ViewValue = $this->keg_ket->CurrentValue;
		$this->keg_ket->ViewCustomAttributes = "";

		// tarif_acuan
		$this->tarif_acuan->ViewValue = $this->tarif_acuan->CurrentValue;
		$this->tarif_acuan->ViewValue = ew_FormatNumber($this->tarif_acuan->ViewValue, 0, -2, -2, -2);
		$this->tarif_acuan->CellCssStyle .= "text-align: right;";
		$this->tarif_acuan->ViewCustomAttributes = "";

		// tarif1
		$this->tarif1->ViewValue = $this->tarif1->CurrentValue;
		$this->tarif1->ViewValue = ew_FormatNumber($this->tarif1->ViewValue, 0, -2, -2, -2);
		$this->tarif1->CellCssStyle .= "text-align: right;";
		$this->tarif1->ViewCustomAttributes = "";

		// tarif2
		$this->tarif2->ViewValue = $this->tarif2->CurrentValue;
		$this->tarif2->ViewValue = ew_FormatNumber($this->tarif2->ViewValue, 0, -2, -2, -2);
		$this->tarif2->CellCssStyle .= "text-align: right;";
		$this->tarif2->ViewCustomAttributes = "";

			// keg_nama
			$this->keg_nama->LinkCustomAttributes = "";
			$this->keg_nama->HrefValue = "";
			$this->keg_nama->TooltipValue = "";

			// keg_ket
			$this->keg_ket->LinkCustomAttributes = "";
			$this->keg_ket->HrefValue = "";
			$this->keg_ket->TooltipValue = "";

			// tarif_acuan
			$this->tarif_acuan->LinkCustomAttributes = "";
			$this->tarif_acuan->HrefValue = "";
			$this->tarif_acuan->TooltipValue = "";

			// tarif1
			$this->tarif1->LinkCustomAttributes = "";
			$this->tarif1->HrefValue = "";
			$this->tarif1->TooltipValue = "";

			// tarif2
			$this->tarif2->LinkCustomAttributes = "";
			$this->tarif2->HrefValue = "";
			$this->tarif2->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['keg_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_kegiatanlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_kegiatan_delete)) $t_kegiatan_delete = new ct_kegiatan_delete();

// Page init
$t_kegiatan_delete->Page_Init();

// Page main
$t_kegiatan_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_kegiatan_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_kegiatandelete = new ew_Form("ft_kegiatandelete", "delete");

// Form_CustomValidate event
ft_kegiatandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_kegiatandelete.ValidateRequired = true;
<?php } else { ?>
ft_kegiatandelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $t_kegiatan_delete->ShowPageHeader(); ?>
<?php
$t_kegiatan_delete->ShowMessage();
?>
<form name="ft_kegiatandelete" id="ft_kegiatandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_kegiatan_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_kegiatan_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_kegiatan">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_kegiatan_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_kegiatan->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_kegiatan->keg_nama->Visible) { // keg_nama ?>
		<th><span id="elh_t_kegiatan_keg_nama" class="t_kegiatan_keg_nama"><?php echo $t_kegiatan->keg_nama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_kegiatan->keg_ket->Visible) { // keg_ket ?>
		<th><span id="elh_t_kegiatan_keg_ket" class="t_kegiatan_keg_ket"><?php echo $t_kegiatan->keg_ket->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_kegiatan->tarif_acuan->Visible) { // tarif_acuan ?>
		<th><span id="elh_t_kegiatan_tarif_acuan" class="t_kegiatan_tarif_acuan"><?php echo $t_kegiatan->tarif_acuan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_kegiatan->tarif1->Visible) { // tarif1 ?>
		<th><span id="elh_t_kegiatan_tarif1" class="t_kegiatan_tarif1"><?php echo $t_kegiatan->tarif1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_kegiatan->tarif2->Visible) { // tarif2 ?>
		<th><span id="elh_t_kegiatan_tarif2" class="t_kegiatan_tarif2"><?php echo $t_kegiatan->tarif2->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_kegiatan_delete->RecCnt = 0;
$i = 0;
while (!$t_kegiatan_delete->Recordset->EOF) {
	$t_kegiatan_delete->RecCnt++;
	$t_kegiatan_delete->RowCnt++;

	// Set row properties
	$t_kegiatan->ResetAttrs();
	$t_kegiatan->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_kegiatan_delete->LoadRowValues($t_kegiatan_delete->Recordset);

	// Render row
	$t_kegiatan_delete->RenderRow();
?>
	<tr<?php echo $t_kegiatan->RowAttributes() ?>>
<?php if ($t_kegiatan->keg_nama->Visible) { // keg_nama ?>
		<td<?php echo $t_kegiatan->keg_nama->CellAttributes() ?>>
<span id="el<?php echo $t_kegiatan_delete->RowCnt ?>_t_kegiatan_keg_nama" class="t_kegiatan_keg_nama">
<span<?php echo $t_kegiatan->keg_nama->ViewAttributes() ?>>
<?php echo $t_kegiatan->keg_nama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_kegiatan->keg_ket->Visible) { // keg_ket ?>
		<td<?php echo $t_kegiatan->keg_ket->CellAttributes() ?>>
<span id="el<?php echo $t_kegiatan_delete->RowCnt ?>_t_kegiatan_keg_ket" class="t_kegiatan_keg_ket">
<span<?php echo $t_kegiatan->keg_ket->ViewAttributes() ?>>
<?php echo $t_kegiatan->keg_ket->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_kegiatan->tarif_acuan->Visible) { // tarif_acuan ?>
		<td<?php echo $t_kegiatan->tarif_acuan->CellAttributes() ?>>
<span id="el<?php echo $t_kegiatan_delete->RowCnt ?>_t_kegiatan_tarif_acuan" class="t_kegiatan_tarif_acuan">
<span<?php echo $t_kegiatan->tarif_acuan->ViewAttributes() ?>>
<?php echo $t_kegiatan->tarif_acuan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_kegiatan->tarif1->Visible) { // tarif1 ?>
		<td<?php echo $t_kegiatan->tarif1->CellAttributes() ?>>
<span id="el<?php echo $t_kegiatan_delete->RowCnt ?>_t_kegiatan_tarif1" class="t_kegiatan_tarif1">
<span<?php echo $t_kegiatan->tarif1->ViewAttributes() ?>>
<?php echo $t_kegiatan->tarif1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_kegiatan->tarif2->Visible) { // tarif2 ?>
		<td<?php echo $t_kegiatan->tarif2->CellAttributes() ?>>
<span id="el<?php echo $t_kegiatan_delete->RowCnt ?>_t_kegiatan_tarif2" class="t_kegiatan_tarif2">
<span<?php echo $t_kegiatan->tarif2->ViewAttributes() ?>>
<?php echo $t_kegiatan->tarif2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_kegiatan_delete->Recordset->MoveNext();
}
$t_kegiatan_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_kegiatan_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_kegiatandelete.Init();
</script>
<?php
$t_kegiatan_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_kegiatan_delete->Page_Terminate();
?>
