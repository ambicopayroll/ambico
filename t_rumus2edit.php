<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_rumus2info.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_rumus2_edit = NULL; // Initialize page object first

class ct_rumus2_edit extends ct_rumus2 {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_rumus2';

	// Page object name
	var $PageObjName = 't_rumus2_edit';

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

		// Table object (t_rumus2)
		if (!isset($GLOBALS["t_rumus2"]) || get_class($GLOBALS["t_rumus2"]) == "ct_rumus2") {
			$GLOBALS["t_rumus2"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_rumus2"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_rumus2', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_rumus2list.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->rumus2_nama->SetVisibility();
		$this->gol_hk->SetVisibility();
		$this->premi_hadir->SetVisibility();
		$this->premi_malam->SetVisibility();
		$this->lp->SetVisibility();
		$this->forklift->SetVisibility();
		$this->lembur->SetVisibility();
		$this->pot_aspen->SetVisibility();
		$this->pot_absen->SetVisibility();
		$this->pot_bpjs->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $t_rumus2;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_rumus2);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["rumus2_id"] <> "") {
			$this->rumus2_id->setQueryStringValue($_GET["rumus2_id"]);
			$this->RecKey["rumus2_id"] = $this->rumus2_id->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("t_rumus2list.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->rumus2_id->CurrentValue) == strval($this->Recordset->fields('rumus2_id'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("t_rumus2list.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_rumus2list.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->rumus2_nama->FldIsDetailKey) {
			$this->rumus2_nama->setFormValue($objForm->GetValue("x_rumus2_nama"));
		}
		if (!$this->gol_hk->FldIsDetailKey) {
			$this->gol_hk->setFormValue($objForm->GetValue("x_gol_hk"));
		}
		if (!$this->premi_hadir->FldIsDetailKey) {
			$this->premi_hadir->setFormValue($objForm->GetValue("x_premi_hadir"));
		}
		if (!$this->premi_malam->FldIsDetailKey) {
			$this->premi_malam->setFormValue($objForm->GetValue("x_premi_malam"));
		}
		if (!$this->lp->FldIsDetailKey) {
			$this->lp->setFormValue($objForm->GetValue("x_lp"));
		}
		if (!$this->forklift->FldIsDetailKey) {
			$this->forklift->setFormValue($objForm->GetValue("x_forklift"));
		}
		if (!$this->lembur->FldIsDetailKey) {
			$this->lembur->setFormValue($objForm->GetValue("x_lembur"));
		}
		if (!$this->pot_aspen->FldIsDetailKey) {
			$this->pot_aspen->setFormValue($objForm->GetValue("x_pot_aspen"));
		}
		if (!$this->pot_absen->FldIsDetailKey) {
			$this->pot_absen->setFormValue($objForm->GetValue("x_pot_absen"));
		}
		if (!$this->pot_bpjs->FldIsDetailKey) {
			$this->pot_bpjs->setFormValue($objForm->GetValue("x_pot_bpjs"));
		}
		if (!$this->rumus2_id->FldIsDetailKey)
			$this->rumus2_id->setFormValue($objForm->GetValue("x_rumus2_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->rumus2_id->CurrentValue = $this->rumus2_id->FormValue;
		$this->rumus2_nama->CurrentValue = $this->rumus2_nama->FormValue;
		$this->gol_hk->CurrentValue = $this->gol_hk->FormValue;
		$this->premi_hadir->CurrentValue = $this->premi_hadir->FormValue;
		$this->premi_malam->CurrentValue = $this->premi_malam->FormValue;
		$this->lp->CurrentValue = $this->lp->FormValue;
		$this->forklift->CurrentValue = $this->forklift->FormValue;
		$this->lembur->CurrentValue = $this->lembur->FormValue;
		$this->pot_aspen->CurrentValue = $this->pot_aspen->FormValue;
		$this->pot_absen->CurrentValue = $this->pot_absen->FormValue;
		$this->pot_bpjs->CurrentValue = $this->pot_bpjs->FormValue;
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
		$this->rumus2_id->setDbValue($rs->fields('rumus2_id'));
		$this->rumus2_nama->setDbValue($rs->fields('rumus2_nama'));
		$this->gol_hk->setDbValue($rs->fields('gol_hk'));
		$this->premi_hadir->setDbValue($rs->fields('premi_hadir'));
		$this->premi_malam->setDbValue($rs->fields('premi_malam'));
		$this->lp->setDbValue($rs->fields('lp'));
		$this->forklift->setDbValue($rs->fields('forklift'));
		$this->lembur->setDbValue($rs->fields('lembur'));
		$this->pot_aspen->setDbValue($rs->fields('pot_aspen'));
		$this->pot_absen->setDbValue($rs->fields('pot_absen'));
		$this->pot_bpjs->setDbValue($rs->fields('pot_bpjs'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->rumus2_id->DbValue = $row['rumus2_id'];
		$this->rumus2_nama->DbValue = $row['rumus2_nama'];
		$this->gol_hk->DbValue = $row['gol_hk'];
		$this->premi_hadir->DbValue = $row['premi_hadir'];
		$this->premi_malam->DbValue = $row['premi_malam'];
		$this->lp->DbValue = $row['lp'];
		$this->forklift->DbValue = $row['forklift'];
		$this->lembur->DbValue = $row['lembur'];
		$this->pot_aspen->DbValue = $row['pot_aspen'];
		$this->pot_absen->DbValue = $row['pot_absen'];
		$this->pot_bpjs->DbValue = $row['pot_bpjs'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->premi_hadir->FormValue == $this->premi_hadir->CurrentValue && is_numeric(ew_StrToFloat($this->premi_hadir->CurrentValue)))
			$this->premi_hadir->CurrentValue = ew_StrToFloat($this->premi_hadir->CurrentValue);

		// Convert decimal values if posted back
		if ($this->premi_malam->FormValue == $this->premi_malam->CurrentValue && is_numeric(ew_StrToFloat($this->premi_malam->CurrentValue)))
			$this->premi_malam->CurrentValue = ew_StrToFloat($this->premi_malam->CurrentValue);

		// Convert decimal values if posted back
		if ($this->lp->FormValue == $this->lp->CurrentValue && is_numeric(ew_StrToFloat($this->lp->CurrentValue)))
			$this->lp->CurrentValue = ew_StrToFloat($this->lp->CurrentValue);

		// Convert decimal values if posted back
		if ($this->forklift->FormValue == $this->forklift->CurrentValue && is_numeric(ew_StrToFloat($this->forklift->CurrentValue)))
			$this->forklift->CurrentValue = ew_StrToFloat($this->forklift->CurrentValue);

		// Convert decimal values if posted back
		if ($this->lembur->FormValue == $this->lembur->CurrentValue && is_numeric(ew_StrToFloat($this->lembur->CurrentValue)))
			$this->lembur->CurrentValue = ew_StrToFloat($this->lembur->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pot_aspen->FormValue == $this->pot_aspen->CurrentValue && is_numeric(ew_StrToFloat($this->pot_aspen->CurrentValue)))
			$this->pot_aspen->CurrentValue = ew_StrToFloat($this->pot_aspen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pot_absen->FormValue == $this->pot_absen->CurrentValue && is_numeric(ew_StrToFloat($this->pot_absen->CurrentValue)))
			$this->pot_absen->CurrentValue = ew_StrToFloat($this->pot_absen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pot_bpjs->FormValue == $this->pot_bpjs->CurrentValue && is_numeric(ew_StrToFloat($this->pot_bpjs->CurrentValue)))
			$this->pot_bpjs->CurrentValue = ew_StrToFloat($this->pot_bpjs->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// rumus2_id
		// rumus2_nama
		// gol_hk
		// premi_hadir
		// premi_malam
		// lp
		// forklift
		// lembur
		// pot_aspen
		// pot_absen
		// pot_bpjs

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// rumus2_id
		$this->rumus2_id->ViewValue = $this->rumus2_id->CurrentValue;
		$this->rumus2_id->ViewCustomAttributes = "";

		// rumus2_nama
		$this->rumus2_nama->ViewValue = $this->rumus2_nama->CurrentValue;
		$this->rumus2_nama->ViewCustomAttributes = "";

		// gol_hk
		if (strval($this->gol_hk->CurrentValue) <> "") {
			$this->gol_hk->ViewValue = $this->gol_hk->OptionCaption($this->gol_hk->CurrentValue);
		} else {
			$this->gol_hk->ViewValue = NULL;
		}
		$this->gol_hk->ViewCustomAttributes = "";

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

		// lp
		$this->lp->ViewValue = $this->lp->CurrentValue;
		$this->lp->ViewValue = ew_FormatNumber($this->lp->ViewValue, 0, -2, -2, -2);
		$this->lp->CellCssStyle .= "text-align: right;";
		$this->lp->ViewCustomAttributes = "";

		// forklift
		$this->forklift->ViewValue = $this->forklift->CurrentValue;
		$this->forklift->ViewValue = ew_FormatNumber($this->forklift->ViewValue, 0, -2, -2, -2);
		$this->forklift->CellCssStyle .= "text-align: right;";
		$this->forklift->ViewCustomAttributes = "";

		// lembur
		$this->lembur->ViewValue = $this->lembur->CurrentValue;
		$this->lembur->ViewValue = ew_FormatNumber($this->lembur->ViewValue, 0, -2, -2, -2);
		$this->lembur->CellCssStyle .= "text-align: right;";
		$this->lembur->ViewCustomAttributes = "";

		// pot_aspen
		$this->pot_aspen->ViewValue = $this->pot_aspen->CurrentValue;
		$this->pot_aspen->ViewValue = ew_FormatNumber($this->pot_aspen->ViewValue, 2, -2, -2, -2);
		$this->pot_aspen->CellCssStyle .= "text-align: right;";
		$this->pot_aspen->ViewCustomAttributes = "";

		// pot_absen
		$this->pot_absen->ViewValue = $this->pot_absen->CurrentValue;
		$this->pot_absen->ViewValue = ew_FormatNumber($this->pot_absen->ViewValue, 0, -2, -2, -2);
		$this->pot_absen->CellCssStyle .= "text-align: right;";
		$this->pot_absen->ViewCustomAttributes = "";

		// pot_bpjs
		$this->pot_bpjs->ViewValue = $this->pot_bpjs->CurrentValue;
		$this->pot_bpjs->ViewValue = ew_FormatNumber($this->pot_bpjs->ViewValue, 2, -2, -2, -2);
		$this->pot_bpjs->CellCssStyle .= "text-align: right;";
		$this->pot_bpjs->ViewCustomAttributes = "";

			// rumus2_nama
			$this->rumus2_nama->LinkCustomAttributes = "";
			$this->rumus2_nama->HrefValue = "";
			$this->rumus2_nama->TooltipValue = "";

			// gol_hk
			$this->gol_hk->LinkCustomAttributes = "";
			$this->gol_hk->HrefValue = "";
			$this->gol_hk->TooltipValue = "";

			// premi_hadir
			$this->premi_hadir->LinkCustomAttributes = "";
			$this->premi_hadir->HrefValue = "";
			$this->premi_hadir->TooltipValue = "";

			// premi_malam
			$this->premi_malam->LinkCustomAttributes = "";
			$this->premi_malam->HrefValue = "";
			$this->premi_malam->TooltipValue = "";

			// lp
			$this->lp->LinkCustomAttributes = "";
			$this->lp->HrefValue = "";
			$this->lp->TooltipValue = "";

			// forklift
			$this->forklift->LinkCustomAttributes = "";
			$this->forklift->HrefValue = "";
			$this->forklift->TooltipValue = "";

			// lembur
			$this->lembur->LinkCustomAttributes = "";
			$this->lembur->HrefValue = "";
			$this->lembur->TooltipValue = "";

			// pot_aspen
			$this->pot_aspen->LinkCustomAttributes = "";
			$this->pot_aspen->HrefValue = "";
			$this->pot_aspen->TooltipValue = "";

			// pot_absen
			$this->pot_absen->LinkCustomAttributes = "";
			$this->pot_absen->HrefValue = "";
			$this->pot_absen->TooltipValue = "";

			// pot_bpjs
			$this->pot_bpjs->LinkCustomAttributes = "";
			$this->pot_bpjs->HrefValue = "";
			$this->pot_bpjs->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// rumus2_nama
			$this->rumus2_nama->EditAttrs["class"] = "form-control";
			$this->rumus2_nama->EditCustomAttributes = "";
			$this->rumus2_nama->EditValue = ew_HtmlEncode($this->rumus2_nama->CurrentValue);
			$this->rumus2_nama->PlaceHolder = ew_RemoveHtml($this->rumus2_nama->FldCaption());

			// gol_hk
			$this->gol_hk->EditCustomAttributes = "";
			$this->gol_hk->EditValue = $this->gol_hk->Options(FALSE);

			// premi_hadir
			$this->premi_hadir->EditAttrs["class"] = "form-control";
			$this->premi_hadir->EditCustomAttributes = "";
			$this->premi_hadir->EditValue = ew_HtmlEncode($this->premi_hadir->CurrentValue);
			$this->premi_hadir->PlaceHolder = ew_RemoveHtml($this->premi_hadir->FldCaption());
			if (strval($this->premi_hadir->EditValue) <> "" && is_numeric($this->premi_hadir->EditValue)) $this->premi_hadir->EditValue = ew_FormatNumber($this->premi_hadir->EditValue, -2, -2, -2, -2);

			// premi_malam
			$this->premi_malam->EditAttrs["class"] = "form-control";
			$this->premi_malam->EditCustomAttributes = "";
			$this->premi_malam->EditValue = ew_HtmlEncode($this->premi_malam->CurrentValue);
			$this->premi_malam->PlaceHolder = ew_RemoveHtml($this->premi_malam->FldCaption());
			if (strval($this->premi_malam->EditValue) <> "" && is_numeric($this->premi_malam->EditValue)) $this->premi_malam->EditValue = ew_FormatNumber($this->premi_malam->EditValue, -2, -2, -2, -2);

			// lp
			$this->lp->EditAttrs["class"] = "form-control";
			$this->lp->EditCustomAttributes = "";
			$this->lp->EditValue = ew_HtmlEncode($this->lp->CurrentValue);
			$this->lp->PlaceHolder = ew_RemoveHtml($this->lp->FldCaption());
			if (strval($this->lp->EditValue) <> "" && is_numeric($this->lp->EditValue)) $this->lp->EditValue = ew_FormatNumber($this->lp->EditValue, -2, -2, -2, -2);

			// forklift
			$this->forklift->EditAttrs["class"] = "form-control";
			$this->forklift->EditCustomAttributes = "";
			$this->forklift->EditValue = ew_HtmlEncode($this->forklift->CurrentValue);
			$this->forklift->PlaceHolder = ew_RemoveHtml($this->forklift->FldCaption());
			if (strval($this->forklift->EditValue) <> "" && is_numeric($this->forklift->EditValue)) $this->forklift->EditValue = ew_FormatNumber($this->forklift->EditValue, -2, -2, -2, -2);

			// lembur
			$this->lembur->EditAttrs["class"] = "form-control";
			$this->lembur->EditCustomAttributes = "";
			$this->lembur->EditValue = ew_HtmlEncode($this->lembur->CurrentValue);
			$this->lembur->PlaceHolder = ew_RemoveHtml($this->lembur->FldCaption());
			if (strval($this->lembur->EditValue) <> "" && is_numeric($this->lembur->EditValue)) $this->lembur->EditValue = ew_FormatNumber($this->lembur->EditValue, -2, -2, -2, -2);

			// pot_aspen
			$this->pot_aspen->EditAttrs["class"] = "form-control";
			$this->pot_aspen->EditCustomAttributes = "";
			$this->pot_aspen->EditValue = ew_HtmlEncode($this->pot_aspen->CurrentValue);
			$this->pot_aspen->PlaceHolder = ew_RemoveHtml($this->pot_aspen->FldCaption());
			if (strval($this->pot_aspen->EditValue) <> "" && is_numeric($this->pot_aspen->EditValue)) $this->pot_aspen->EditValue = ew_FormatNumber($this->pot_aspen->EditValue, -2, -2, -2, -2);

			// pot_absen
			$this->pot_absen->EditAttrs["class"] = "form-control";
			$this->pot_absen->EditCustomAttributes = "";
			$this->pot_absen->EditValue = ew_HtmlEncode($this->pot_absen->CurrentValue);
			$this->pot_absen->PlaceHolder = ew_RemoveHtml($this->pot_absen->FldCaption());
			if (strval($this->pot_absen->EditValue) <> "" && is_numeric($this->pot_absen->EditValue)) $this->pot_absen->EditValue = ew_FormatNumber($this->pot_absen->EditValue, -2, -2, -2, -2);

			// pot_bpjs
			$this->pot_bpjs->EditAttrs["class"] = "form-control";
			$this->pot_bpjs->EditCustomAttributes = "";
			$this->pot_bpjs->EditValue = ew_HtmlEncode($this->pot_bpjs->CurrentValue);
			$this->pot_bpjs->PlaceHolder = ew_RemoveHtml($this->pot_bpjs->FldCaption());
			if (strval($this->pot_bpjs->EditValue) <> "" && is_numeric($this->pot_bpjs->EditValue)) $this->pot_bpjs->EditValue = ew_FormatNumber($this->pot_bpjs->EditValue, -2, -2, -2, -2);

			// Edit refer script
			// rumus2_nama

			$this->rumus2_nama->LinkCustomAttributes = "";
			$this->rumus2_nama->HrefValue = "";

			// gol_hk
			$this->gol_hk->LinkCustomAttributes = "";
			$this->gol_hk->HrefValue = "";

			// premi_hadir
			$this->premi_hadir->LinkCustomAttributes = "";
			$this->premi_hadir->HrefValue = "";

			// premi_malam
			$this->premi_malam->LinkCustomAttributes = "";
			$this->premi_malam->HrefValue = "";

			// lp
			$this->lp->LinkCustomAttributes = "";
			$this->lp->HrefValue = "";

			// forklift
			$this->forklift->LinkCustomAttributes = "";
			$this->forklift->HrefValue = "";

			// lembur
			$this->lembur->LinkCustomAttributes = "";
			$this->lembur->HrefValue = "";

			// pot_aspen
			$this->pot_aspen->LinkCustomAttributes = "";
			$this->pot_aspen->HrefValue = "";

			// pot_absen
			$this->pot_absen->LinkCustomAttributes = "";
			$this->pot_absen->HrefValue = "";

			// pot_bpjs
			$this->pot_bpjs->LinkCustomAttributes = "";
			$this->pot_bpjs->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->rumus2_nama->FldIsDetailKey && !is_null($this->rumus2_nama->FormValue) && $this->rumus2_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rumus2_nama->FldCaption(), $this->rumus2_nama->ReqErrMsg));
		}
		if ($this->gol_hk->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gol_hk->FldCaption(), $this->gol_hk->ReqErrMsg));
		}
		if (!$this->premi_hadir->FldIsDetailKey && !is_null($this->premi_hadir->FormValue) && $this->premi_hadir->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->premi_hadir->FldCaption(), $this->premi_hadir->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->premi_hadir->FormValue)) {
			ew_AddMessage($gsFormError, $this->premi_hadir->FldErrMsg());
		}
		if (!$this->premi_malam->FldIsDetailKey && !is_null($this->premi_malam->FormValue) && $this->premi_malam->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->premi_malam->FldCaption(), $this->premi_malam->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->premi_malam->FormValue)) {
			ew_AddMessage($gsFormError, $this->premi_malam->FldErrMsg());
		}
		if (!$this->lp->FldIsDetailKey && !is_null($this->lp->FormValue) && $this->lp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lp->FldCaption(), $this->lp->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->lp->FormValue)) {
			ew_AddMessage($gsFormError, $this->lp->FldErrMsg());
		}
		if (!$this->forklift->FldIsDetailKey && !is_null($this->forklift->FormValue) && $this->forklift->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->forklift->FldCaption(), $this->forklift->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->forklift->FormValue)) {
			ew_AddMessage($gsFormError, $this->forklift->FldErrMsg());
		}
		if (!ew_CheckNumber($this->lembur->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur->FldErrMsg());
		}
		if (!$this->pot_aspen->FldIsDetailKey && !is_null($this->pot_aspen->FormValue) && $this->pot_aspen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pot_aspen->FldCaption(), $this->pot_aspen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pot_aspen->FormValue)) {
			ew_AddMessage($gsFormError, $this->pot_aspen->FldErrMsg());
		}
		if (!$this->pot_absen->FldIsDetailKey && !is_null($this->pot_absen->FormValue) && $this->pot_absen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pot_absen->FldCaption(), $this->pot_absen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pot_absen->FormValue)) {
			ew_AddMessage($gsFormError, $this->pot_absen->FldErrMsg());
		}
		if (!$this->pot_bpjs->FldIsDetailKey && !is_null($this->pot_bpjs->FormValue) && $this->pot_bpjs->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pot_bpjs->FldCaption(), $this->pot_bpjs->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pot_bpjs->FormValue)) {
			ew_AddMessage($gsFormError, $this->pot_bpjs->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// rumus2_nama
			$this->rumus2_nama->SetDbValueDef($rsnew, $this->rumus2_nama->CurrentValue, "", $this->rumus2_nama->ReadOnly);

			// gol_hk
			$this->gol_hk->SetDbValueDef($rsnew, $this->gol_hk->CurrentValue, 0, $this->gol_hk->ReadOnly);

			// premi_hadir
			$this->premi_hadir->SetDbValueDef($rsnew, $this->premi_hadir->CurrentValue, 0, $this->premi_hadir->ReadOnly);

			// premi_malam
			$this->premi_malam->SetDbValueDef($rsnew, $this->premi_malam->CurrentValue, 0, $this->premi_malam->ReadOnly);

			// lp
			$this->lp->SetDbValueDef($rsnew, $this->lp->CurrentValue, 0, $this->lp->ReadOnly);

			// forklift
			$this->forklift->SetDbValueDef($rsnew, $this->forklift->CurrentValue, 0, $this->forklift->ReadOnly);

			// lembur
			$this->lembur->SetDbValueDef($rsnew, $this->lembur->CurrentValue, 0, $this->lembur->ReadOnly);

			// pot_aspen
			$this->pot_aspen->SetDbValueDef($rsnew, $this->pot_aspen->CurrentValue, 0, $this->pot_aspen->ReadOnly);

			// pot_absen
			$this->pot_absen->SetDbValueDef($rsnew, $this->pot_absen->CurrentValue, 0, $this->pot_absen->ReadOnly);

			// pot_bpjs
			$this->pot_bpjs->SetDbValueDef($rsnew, $this->pot_bpjs->CurrentValue, 0, $this->pot_bpjs->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_rumus2list.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_rumus2_edit)) $t_rumus2_edit = new ct_rumus2_edit();

// Page init
$t_rumus2_edit->Page_Init();

// Page main
$t_rumus2_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus2_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_rumus2edit = new ew_Form("ft_rumus2edit", "edit");

// Validate form
ft_rumus2edit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_rumus2_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->rumus2_nama->FldCaption(), $t_rumus2->rumus2_nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gol_hk");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->gol_hk->FldCaption(), $t_rumus2->gol_hk->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_premi_hadir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->premi_hadir->FldCaption(), $t_rumus2->premi_hadir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_premi_hadir");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->premi_hadir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_premi_malam");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->premi_malam->FldCaption(), $t_rumus2->premi_malam->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_premi_malam");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->premi_malam->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->lp->FldCaption(), $t_rumus2->lp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->lp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_forklift");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->forklift->FldCaption(), $t_rumus2->forklift->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_forklift");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->forklift->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->lembur->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pot_aspen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->pot_aspen->FldCaption(), $t_rumus2->pot_aspen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pot_aspen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->pot_aspen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pot_absen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->pot_absen->FldCaption(), $t_rumus2->pot_absen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pot_absen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->pot_absen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pot_bpjs");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2->pot_bpjs->FldCaption(), $t_rumus2->pot_bpjs->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pot_bpjs");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2->pot_bpjs->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_rumus2edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumus2edit.ValidateRequired = true;
<?php } else { ?>
ft_rumus2edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumus2edit.Lists["x_gol_hk"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_rumus2edit.Lists["x_gol_hk"].Options = <?php echo json_encode($t_rumus2->gol_hk->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_rumus2_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_rumus2_edit->ShowPageHeader(); ?>
<?php
$t_rumus2_edit->ShowMessage();
?>
<?php if (!$t_rumus2_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_rumus2_edit->Pager)) $t_rumus2_edit->Pager = new cPrevNextPager($t_rumus2_edit->StartRec, $t_rumus2_edit->DisplayRecs, $t_rumus2_edit->TotalRecs) ?>
<?php if ($t_rumus2_edit->Pager->RecordCount > 0 && $t_rumus2_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_rumus2_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_rumus2_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_rumus2_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_rumus2_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_rumus2_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_rumus2_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_rumus2edit" id="ft_rumus2edit" class="<?php echo $t_rumus2_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_rumus2_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_rumus2_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_rumus2">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_rumus2_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_rumus2->rumus2_nama->Visible) { // rumus2_nama ?>
	<div id="r_rumus2_nama" class="form-group">
		<label id="elh_t_rumus2_rumus2_nama" for="x_rumus2_nama" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->rumus2_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->rumus2_nama->CellAttributes() ?>>
<span id="el_t_rumus2_rumus2_nama">
<input type="text" data-table="t_rumus2" data-field="x_rumus2_nama" name="x_rumus2_nama" id="x_rumus2_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_rumus2->rumus2_nama->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->rumus2_nama->EditValue ?>"<?php echo $t_rumus2->rumus2_nama->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->rumus2_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->gol_hk->Visible) { // gol_hk ?>
	<div id="r_gol_hk" class="form-group">
		<label id="elh_t_rumus2_gol_hk" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->gol_hk->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->gol_hk->CellAttributes() ?>>
<span id="el_t_rumus2_gol_hk">
<div id="tp_x_gol_hk" class="ewTemplate"><input type="radio" data-table="t_rumus2" data-field="x_gol_hk" data-value-separator="<?php echo $t_rumus2->gol_hk->DisplayValueSeparatorAttribute() ?>" name="x_gol_hk" id="x_gol_hk" value="{value}"<?php echo $t_rumus2->gol_hk->EditAttributes() ?>></div>
<div id="dsl_x_gol_hk" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_rumus2->gol_hk->RadioButtonListHtml(FALSE, "x_gol_hk") ?>
</div></div>
</span>
<?php echo $t_rumus2->gol_hk->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->premi_hadir->Visible) { // premi_hadir ?>
	<div id="r_premi_hadir" class="form-group">
		<label id="elh_t_rumus2_premi_hadir" for="x_premi_hadir" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->premi_hadir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->premi_hadir->CellAttributes() ?>>
<span id="el_t_rumus2_premi_hadir">
<input type="text" data-table="t_rumus2" data-field="x_premi_hadir" name="x_premi_hadir" id="x_premi_hadir" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->premi_hadir->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->premi_hadir->EditValue ?>"<?php echo $t_rumus2->premi_hadir->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->premi_hadir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->premi_malam->Visible) { // premi_malam ?>
	<div id="r_premi_malam" class="form-group">
		<label id="elh_t_rumus2_premi_malam" for="x_premi_malam" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->premi_malam->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->premi_malam->CellAttributes() ?>>
<span id="el_t_rumus2_premi_malam">
<input type="text" data-table="t_rumus2" data-field="x_premi_malam" name="x_premi_malam" id="x_premi_malam" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->premi_malam->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->premi_malam->EditValue ?>"<?php echo $t_rumus2->premi_malam->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->premi_malam->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->lp->Visible) { // lp ?>
	<div id="r_lp" class="form-group">
		<label id="elh_t_rumus2_lp" for="x_lp" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->lp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->lp->CellAttributes() ?>>
<span id="el_t_rumus2_lp">
<input type="text" data-table="t_rumus2" data-field="x_lp" name="x_lp" id="x_lp" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->lp->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->lp->EditValue ?>"<?php echo $t_rumus2->lp->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->lp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->forklift->Visible) { // forklift ?>
	<div id="r_forklift" class="form-group">
		<label id="elh_t_rumus2_forklift" for="x_forklift" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->forklift->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->forklift->CellAttributes() ?>>
<span id="el_t_rumus2_forklift">
<input type="text" data-table="t_rumus2" data-field="x_forklift" name="x_forklift" id="x_forklift" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->forklift->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->forklift->EditValue ?>"<?php echo $t_rumus2->forklift->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->forklift->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->lembur->Visible) { // lembur ?>
	<div id="r_lembur" class="form-group">
		<label id="elh_t_rumus2_lembur" for="x_lembur" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->lembur->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->lembur->CellAttributes() ?>>
<span id="el_t_rumus2_lembur">
<input type="text" data-table="t_rumus2" data-field="x_lembur" name="x_lembur" id="x_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->lembur->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->lembur->EditValue ?>"<?php echo $t_rumus2->lembur->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->lembur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->pot_aspen->Visible) { // pot_aspen ?>
	<div id="r_pot_aspen" class="form-group">
		<label id="elh_t_rumus2_pot_aspen" for="x_pot_aspen" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->pot_aspen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->pot_aspen->CellAttributes() ?>>
<span id="el_t_rumus2_pot_aspen">
<input type="text" data-table="t_rumus2" data-field="x_pot_aspen" name="x_pot_aspen" id="x_pot_aspen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->pot_aspen->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->pot_aspen->EditValue ?>"<?php echo $t_rumus2->pot_aspen->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->pot_aspen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->pot_absen->Visible) { // pot_absen ?>
	<div id="r_pot_absen" class="form-group">
		<label id="elh_t_rumus2_pot_absen" for="x_pot_absen" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->pot_absen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->pot_absen->CellAttributes() ?>>
<span id="el_t_rumus2_pot_absen">
<input type="text" data-table="t_rumus2" data-field="x_pot_absen" name="x_pot_absen" id="x_pot_absen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->pot_absen->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->pot_absen->EditValue ?>"<?php echo $t_rumus2->pot_absen->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->pot_absen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2->pot_bpjs->Visible) { // pot_bpjs ?>
	<div id="r_pot_bpjs" class="form-group">
		<label id="elh_t_rumus2_pot_bpjs" for="x_pot_bpjs" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2->pot_bpjs->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2->pot_bpjs->CellAttributes() ?>>
<span id="el_t_rumus2_pot_bpjs">
<input type="text" data-table="t_rumus2" data-field="x_pot_bpjs" name="x_pot_bpjs" id="x_pot_bpjs" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2->pot_bpjs->getPlaceHolder()) ?>" value="<?php echo $t_rumus2->pot_bpjs->EditValue ?>"<?php echo $t_rumus2->pot_bpjs->EditAttributes() ?>>
</span>
<?php echo $t_rumus2->pot_bpjs->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_rumus2" data-field="x_rumus2_id" name="x_rumus2_id" id="x_rumus2_id" value="<?php echo ew_HtmlEncode($t_rumus2->rumus2_id->CurrentValue) ?>">
<?php if (!$t_rumus2_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_rumus2_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($t_rumus2_edit->Pager)) $t_rumus2_edit->Pager = new cPrevNextPager($t_rumus2_edit->StartRec, $t_rumus2_edit->DisplayRecs, $t_rumus2_edit->TotalRecs) ?>
<?php if ($t_rumus2_edit->Pager->RecordCount > 0 && $t_rumus2_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_rumus2_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_rumus2_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_rumus2_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_rumus2_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_rumus2_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_rumus2_edit->PageUrl() ?>start=<?php echo $t_rumus2_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_rumus2_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_rumus2edit.Init();
</script>
<?php
$t_rumus2_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_rumus2_edit->Page_Terminate();
?>
