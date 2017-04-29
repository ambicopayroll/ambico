<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_rumusinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_rumus_delete = NULL; // Initialize page object first

class ct_rumus_delete extends ct_rumus {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_rumus';

	// Page object name
	var $PageObjName = 't_rumus_delete';

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

		// Table object (t_rumus)
		if (!isset($GLOBALS["t_rumus"]) || get_class($GLOBALS["t_rumus"]) == "ct_rumus") {
			$GLOBALS["t_rumus"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_rumus"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_rumus', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_rumuslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->rumus_nama->SetVisibility();
		$this->hk_gol->SetVisibility();
		$this->umr->SetVisibility();
		$this->hk_jml->SetVisibility();
		$this->upah->SetVisibility();
		$this->premi_hadir->SetVisibility();
		$this->premi_malam->SetVisibility();
		$this->pot_absen->SetVisibility();
		$this->lembur->SetVisibility();

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
		global $EW_EXPORT, $t_rumus;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_rumus);
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
			$this->Page_Terminate("t_rumuslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_rumus class, t_rumusinfo.php

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
				$this->Page_Terminate("t_rumuslist.php"); // Return to list
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
		$this->rumus_id->setDbValue($rs->fields('rumus_id'));
		$this->rumus_nama->setDbValue($rs->fields('rumus_nama'));
		$this->hk_gol->setDbValue($rs->fields('hk_gol'));
		$this->umr->setDbValue($rs->fields('umr'));
		$this->hk_jml->setDbValue($rs->fields('hk_jml'));
		$this->upah->setDbValue($rs->fields('upah'));
		$this->premi_hadir->setDbValue($rs->fields('premi_hadir'));
		$this->premi_malam->setDbValue($rs->fields('premi_malam'));
		$this->pot_absen->setDbValue($rs->fields('pot_absen'));
		$this->lembur->setDbValue($rs->fields('lembur'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->rumus_id->DbValue = $row['rumus_id'];
		$this->rumus_nama->DbValue = $row['rumus_nama'];
		$this->hk_gol->DbValue = $row['hk_gol'];
		$this->umr->DbValue = $row['umr'];
		$this->hk_jml->DbValue = $row['hk_jml'];
		$this->upah->DbValue = $row['upah'];
		$this->premi_hadir->DbValue = $row['premi_hadir'];
		$this->premi_malam->DbValue = $row['premi_malam'];
		$this->pot_absen->DbValue = $row['pot_absen'];
		$this->lembur->DbValue = $row['lembur'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->umr->FormValue == $this->umr->CurrentValue && is_numeric(ew_StrToFloat($this->umr->CurrentValue)))
			$this->umr->CurrentValue = ew_StrToFloat($this->umr->CurrentValue);

		// Convert decimal values if posted back
		if ($this->upah->FormValue == $this->upah->CurrentValue && is_numeric(ew_StrToFloat($this->upah->CurrentValue)))
			$this->upah->CurrentValue = ew_StrToFloat($this->upah->CurrentValue);

		// Convert decimal values if posted back
		if ($this->premi_hadir->FormValue == $this->premi_hadir->CurrentValue && is_numeric(ew_StrToFloat($this->premi_hadir->CurrentValue)))
			$this->premi_hadir->CurrentValue = ew_StrToFloat($this->premi_hadir->CurrentValue);

		// Convert decimal values if posted back
		if ($this->premi_malam->FormValue == $this->premi_malam->CurrentValue && is_numeric(ew_StrToFloat($this->premi_malam->CurrentValue)))
			$this->premi_malam->CurrentValue = ew_StrToFloat($this->premi_malam->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pot_absen->FormValue == $this->pot_absen->CurrentValue && is_numeric(ew_StrToFloat($this->pot_absen->CurrentValue)))
			$this->pot_absen->CurrentValue = ew_StrToFloat($this->pot_absen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->lembur->FormValue == $this->lembur->CurrentValue && is_numeric(ew_StrToFloat($this->lembur->CurrentValue)))
			$this->lembur->CurrentValue = ew_StrToFloat($this->lembur->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// rumus_id
		// rumus_nama
		// hk_gol
		// umr
		// hk_jml
		// upah
		// premi_hadir
		// premi_malam
		// pot_absen
		// lembur

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// rumus_id
		$this->rumus_id->ViewValue = $this->rumus_id->CurrentValue;
		$this->rumus_id->ViewCustomAttributes = "";

		// rumus_nama
		$this->rumus_nama->ViewValue = $this->rumus_nama->CurrentValue;
		$this->rumus_nama->ViewCustomAttributes = "";

		// hk_gol
		if (strval($this->hk_gol->CurrentValue) <> "") {
			$this->hk_gol->ViewValue = $this->hk_gol->OptionCaption($this->hk_gol->CurrentValue);
		} else {
			$this->hk_gol->ViewValue = NULL;
		}
		$this->hk_gol->ViewCustomAttributes = "";

		// umr
		$this->umr->ViewValue = $this->umr->CurrentValue;
		$this->umr->ViewValue = ew_FormatNumber($this->umr->ViewValue, 0, -2, -2, -2);
		$this->umr->CellCssStyle .= "text-align: right;";
		$this->umr->ViewCustomAttributes = "";

		// hk_jml
		$this->hk_jml->ViewValue = $this->hk_jml->CurrentValue;
		$this->hk_jml->ViewValue = ew_FormatNumber($this->hk_jml->ViewValue, 0, -2, -2, -2);
		$this->hk_jml->CellCssStyle .= "text-align: right;";
		$this->hk_jml->ViewCustomAttributes = "";

		// upah
		$this->upah->ViewValue = $this->upah->CurrentValue;
		$this->upah->ViewValue = ew_FormatNumber($this->upah->ViewValue, 0, -2, -2, -2);
		$this->upah->CellCssStyle .= "text-align: right;";
		$this->upah->ViewCustomAttributes = "";

		// premi_hadir
		$this->premi_hadir->ViewValue = $this->premi_hadir->CurrentValue;
		$this->premi_hadir->ViewValue = ew_FormatNumber($this->premi_hadir->ViewValue, 0, -2, -2, -2);
		$this->premi_hadir->CellCssStyle .= "text-align: right;";
		$this->premi_hadir->ViewCustomAttributes = "";

		// premi_malam
		$this->premi_malam->ViewValue = $this->premi_malam->CurrentValue;
		$this->premi_malam->ViewValue = ew_FormatNumber($this->premi_malam->ViewValue, 0, -2, -2, -2);
		$this->premi_malam->CellCssStyle .= "text-align: right;";
		$this->premi_malam->ViewCustomAttributes = "";

		// pot_absen
		$this->pot_absen->ViewValue = $this->pot_absen->CurrentValue;
		$this->pot_absen->ViewValue = ew_FormatNumber($this->pot_absen->ViewValue, 0, -2, -2, -2);
		$this->pot_absen->CellCssStyle .= "text-align: right;";
		$this->pot_absen->ViewCustomAttributes = "";

		// lembur
		$this->lembur->ViewValue = $this->lembur->CurrentValue;
		$this->lembur->ViewValue = ew_FormatNumber($this->lembur->ViewValue, 0, -2, -2, -2);
		$this->lembur->CellCssStyle .= "text-align: right;";
		$this->lembur->ViewCustomAttributes = "";

			// rumus_nama
			$this->rumus_nama->LinkCustomAttributes = "";
			$this->rumus_nama->HrefValue = "";
			$this->rumus_nama->TooltipValue = "";

			// hk_gol
			$this->hk_gol->LinkCustomAttributes = "";
			$this->hk_gol->HrefValue = "";
			$this->hk_gol->TooltipValue = "";

			// umr
			$this->umr->LinkCustomAttributes = "";
			$this->umr->HrefValue = "";
			$this->umr->TooltipValue = "";

			// hk_jml
			$this->hk_jml->LinkCustomAttributes = "";
			$this->hk_jml->HrefValue = "";
			$this->hk_jml->TooltipValue = "";

			// upah
			$this->upah->LinkCustomAttributes = "";
			$this->upah->HrefValue = "";
			$this->upah->TooltipValue = "";

			// premi_hadir
			$this->premi_hadir->LinkCustomAttributes = "";
			$this->premi_hadir->HrefValue = "";
			$this->premi_hadir->TooltipValue = "";

			// premi_malam
			$this->premi_malam->LinkCustomAttributes = "";
			$this->premi_malam->HrefValue = "";
			$this->premi_malam->TooltipValue = "";

			// pot_absen
			$this->pot_absen->LinkCustomAttributes = "";
			$this->pot_absen->HrefValue = "";
			$this->pot_absen->TooltipValue = "";

			// lembur
			$this->lembur->LinkCustomAttributes = "";
			$this->lembur->HrefValue = "";
			$this->lembur->TooltipValue = "";
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
				$sThisKey .= $row['rumus_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_rumuslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($t_rumus_delete)) $t_rumus_delete = new ct_rumus_delete();

// Page init
$t_rumus_delete->Page_Init();

// Page main
$t_rumus_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_rumusdelete = new ew_Form("ft_rumusdelete", "delete");

// Form_CustomValidate event
ft_rumusdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumusdelete.ValidateRequired = true;
<?php } else { ?>
ft_rumusdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumusdelete.Lists["x_hk_gol"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_rumusdelete.Lists["x_hk_gol"].Options = <?php echo json_encode($t_rumus->hk_gol->Options()) ?>;

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
<?php $t_rumus_delete->ShowPageHeader(); ?>
<?php
$t_rumus_delete->ShowMessage();
?>
<form name="ft_rumusdelete" id="ft_rumusdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_rumus_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_rumus_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_rumus">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_rumus_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_rumus->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
		<th><span id="elh_t_rumus_rumus_nama" class="t_rumus_rumus_nama"><?php echo $t_rumus->rumus_nama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
		<th><span id="elh_t_rumus_hk_gol" class="t_rumus_hk_gol"><?php echo $t_rumus->hk_gol->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->umr->Visible) { // umr ?>
		<th><span id="elh_t_rumus_umr" class="t_rumus_umr"><?php echo $t_rumus->umr->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
		<th><span id="elh_t_rumus_hk_jml" class="t_rumus_hk_jml"><?php echo $t_rumus->hk_jml->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->upah->Visible) { // upah ?>
		<th><span id="elh_t_rumus_upah" class="t_rumus_upah"><?php echo $t_rumus->upah->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
		<th><span id="elh_t_rumus_premi_hadir" class="t_rumus_premi_hadir"><?php echo $t_rumus->premi_hadir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
		<th><span id="elh_t_rumus_premi_malam" class="t_rumus_premi_malam"><?php echo $t_rumus->premi_malam->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
		<th><span id="elh_t_rumus_pot_absen" class="t_rumus_pot_absen"><?php echo $t_rumus->pot_absen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_rumus->lembur->Visible) { // lembur ?>
		<th><span id="elh_t_rumus_lembur" class="t_rumus_lembur"><?php echo $t_rumus->lembur->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_rumus_delete->RecCnt = 0;
$i = 0;
while (!$t_rumus_delete->Recordset->EOF) {
	$t_rumus_delete->RecCnt++;
	$t_rumus_delete->RowCnt++;

	// Set row properties
	$t_rumus->ResetAttrs();
	$t_rumus->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_rumus_delete->LoadRowValues($t_rumus_delete->Recordset);

	// Render row
	$t_rumus_delete->RenderRow();
?>
	<tr<?php echo $t_rumus->RowAttributes() ?>>
<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
		<td<?php echo $t_rumus->rumus_nama->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_rumus_nama" class="t_rumus_rumus_nama">
<span<?php echo $t_rumus->rumus_nama->ViewAttributes() ?>>
<?php echo $t_rumus->rumus_nama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
		<td<?php echo $t_rumus->hk_gol->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_hk_gol" class="t_rumus_hk_gol">
<span<?php echo $t_rumus->hk_gol->ViewAttributes() ?>>
<?php echo $t_rumus->hk_gol->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->umr->Visible) { // umr ?>
		<td<?php echo $t_rumus->umr->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_umr" class="t_rumus_umr">
<span<?php echo $t_rumus->umr->ViewAttributes() ?>>
<?php echo $t_rumus->umr->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
		<td<?php echo $t_rumus->hk_jml->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_hk_jml" class="t_rumus_hk_jml">
<span<?php echo $t_rumus->hk_jml->ViewAttributes() ?>>
<?php echo $t_rumus->hk_jml->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->upah->Visible) { // upah ?>
		<td<?php echo $t_rumus->upah->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_upah" class="t_rumus_upah">
<span<?php echo $t_rumus->upah->ViewAttributes() ?>>
<?php echo $t_rumus->upah->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
		<td<?php echo $t_rumus->premi_hadir->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_premi_hadir" class="t_rumus_premi_hadir">
<span<?php echo $t_rumus->premi_hadir->ViewAttributes() ?>>
<?php echo $t_rumus->premi_hadir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
		<td<?php echo $t_rumus->premi_malam->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_premi_malam" class="t_rumus_premi_malam">
<span<?php echo $t_rumus->premi_malam->ViewAttributes() ?>>
<?php echo $t_rumus->premi_malam->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
		<td<?php echo $t_rumus->pot_absen->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_pot_absen" class="t_rumus_pot_absen">
<span<?php echo $t_rumus->pot_absen->ViewAttributes() ?>>
<?php echo $t_rumus->pot_absen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_rumus->lembur->Visible) { // lembur ?>
		<td<?php echo $t_rumus->lembur->CellAttributes() ?>>
<span id="el<?php echo $t_rumus_delete->RowCnt ?>_t_rumus_lembur" class="t_rumus_lembur">
<span<?php echo $t_rumus->lembur->ViewAttributes() ?>>
<?php echo $t_rumus->lembur->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_rumus_delete->Recordset->MoveNext();
}
$t_rumus_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_rumus_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_rumusdelete.Init();
</script>
<?php
$t_rumus_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_rumus_delete->Page_Terminate();
?>
