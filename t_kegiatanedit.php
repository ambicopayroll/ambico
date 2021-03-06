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

$t_kegiatan_edit = NULL; // Initialize page object first

class ct_kegiatan_edit extends ct_kegiatan {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_kegiatan';

	// Page object name
	var $PageObjName = 't_kegiatan_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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

		// Create form object
		$objForm = new cFormObj();
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
		if (@$_GET["keg_id"] <> "") {
			$this->keg_id->setQueryStringValue($_GET["keg_id"]);
			$this->RecKey["keg_id"] = $this->keg_id->QueryStringValue;
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
			$this->Page_Terminate("t_kegiatanlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->keg_id->CurrentValue) == strval($this->Recordset->fields('keg_id'))) {
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
					$this->Page_Terminate("t_kegiatanlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_kegiatanlist.php")
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
		if (!$this->keg_nama->FldIsDetailKey) {
			$this->keg_nama->setFormValue($objForm->GetValue("x_keg_nama"));
		}
		if (!$this->keg_ket->FldIsDetailKey) {
			$this->keg_ket->setFormValue($objForm->GetValue("x_keg_ket"));
		}
		if (!$this->tarif_acuan->FldIsDetailKey) {
			$this->tarif_acuan->setFormValue($objForm->GetValue("x_tarif_acuan"));
		}
		if (!$this->tarif1->FldIsDetailKey) {
			$this->tarif1->setFormValue($objForm->GetValue("x_tarif1"));
		}
		if (!$this->tarif2->FldIsDetailKey) {
			$this->tarif2->setFormValue($objForm->GetValue("x_tarif2"));
		}
		if (!$this->keg_id->FldIsDetailKey)
			$this->keg_id->setFormValue($objForm->GetValue("x_keg_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->keg_id->CurrentValue = $this->keg_id->FormValue;
		$this->keg_nama->CurrentValue = $this->keg_nama->FormValue;
		$this->keg_ket->CurrentValue = $this->keg_ket->FormValue;
		$this->tarif_acuan->CurrentValue = $this->tarif_acuan->FormValue;
		$this->tarif1->CurrentValue = $this->tarif1->FormValue;
		$this->tarif2->CurrentValue = $this->tarif2->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// keg_nama
			$this->keg_nama->EditAttrs["class"] = "form-control";
			$this->keg_nama->EditCustomAttributes = "";
			$this->keg_nama->EditValue = ew_HtmlEncode($this->keg_nama->CurrentValue);
			$this->keg_nama->PlaceHolder = ew_RemoveHtml($this->keg_nama->FldCaption());

			// keg_ket
			$this->keg_ket->EditAttrs["class"] = "form-control";
			$this->keg_ket->EditCustomAttributes = "";
			$this->keg_ket->EditValue = ew_HtmlEncode($this->keg_ket->CurrentValue);
			$this->keg_ket->PlaceHolder = ew_RemoveHtml($this->keg_ket->FldCaption());

			// tarif_acuan
			$this->tarif_acuan->EditAttrs["class"] = "form-control";
			$this->tarif_acuan->EditCustomAttributes = "";
			$this->tarif_acuan->EditValue = ew_HtmlEncode($this->tarif_acuan->CurrentValue);
			$this->tarif_acuan->PlaceHolder = ew_RemoveHtml($this->tarif_acuan->FldCaption());

			// tarif1
			$this->tarif1->EditAttrs["class"] = "form-control";
			$this->tarif1->EditCustomAttributes = "";
			$this->tarif1->EditValue = ew_HtmlEncode($this->tarif1->CurrentValue);
			$this->tarif1->PlaceHolder = ew_RemoveHtml($this->tarif1->FldCaption());
			if (strval($this->tarif1->EditValue) <> "" && is_numeric($this->tarif1->EditValue)) $this->tarif1->EditValue = ew_FormatNumber($this->tarif1->EditValue, -2, -2, -2, -2);

			// tarif2
			$this->tarif2->EditAttrs["class"] = "form-control";
			$this->tarif2->EditCustomAttributes = "";
			$this->tarif2->EditValue = ew_HtmlEncode($this->tarif2->CurrentValue);
			$this->tarif2->PlaceHolder = ew_RemoveHtml($this->tarif2->FldCaption());
			if (strval($this->tarif2->EditValue) <> "" && is_numeric($this->tarif2->EditValue)) $this->tarif2->EditValue = ew_FormatNumber($this->tarif2->EditValue, -2, -2, -2, -2);

			// Edit refer script
			// keg_nama

			$this->keg_nama->LinkCustomAttributes = "";
			$this->keg_nama->HrefValue = "";

			// keg_ket
			$this->keg_ket->LinkCustomAttributes = "";
			$this->keg_ket->HrefValue = "";

			// tarif_acuan
			$this->tarif_acuan->LinkCustomAttributes = "";
			$this->tarif_acuan->HrefValue = "";

			// tarif1
			$this->tarif1->LinkCustomAttributes = "";
			$this->tarif1->HrefValue = "";

			// tarif2
			$this->tarif2->LinkCustomAttributes = "";
			$this->tarif2->HrefValue = "";
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
		if (!$this->keg_nama->FldIsDetailKey && !is_null($this->keg_nama->FormValue) && $this->keg_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keg_nama->FldCaption(), $this->keg_nama->ReqErrMsg));
		}
		if (!$this->tarif_acuan->FldIsDetailKey && !is_null($this->tarif_acuan->FormValue) && $this->tarif_acuan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tarif_acuan->FldCaption(), $this->tarif_acuan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->tarif_acuan->FormValue)) {
			ew_AddMessage($gsFormError, $this->tarif_acuan->FldErrMsg());
		}
		if (!$this->tarif1->FldIsDetailKey && !is_null($this->tarif1->FormValue) && $this->tarif1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tarif1->FldCaption(), $this->tarif1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tarif1->FormValue)) {
			ew_AddMessage($gsFormError, $this->tarif1->FldErrMsg());
		}
		if (!$this->tarif2->FldIsDetailKey && !is_null($this->tarif2->FormValue) && $this->tarif2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tarif2->FldCaption(), $this->tarif2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tarif2->FormValue)) {
			ew_AddMessage($gsFormError, $this->tarif2->FldErrMsg());
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

			// keg_nama
			$this->keg_nama->SetDbValueDef($rsnew, $this->keg_nama->CurrentValue, "", $this->keg_nama->ReadOnly);

			// keg_ket
			$this->keg_ket->SetDbValueDef($rsnew, $this->keg_ket->CurrentValue, NULL, $this->keg_ket->ReadOnly);

			// tarif_acuan
			$this->tarif_acuan->SetDbValueDef($rsnew, $this->tarif_acuan->CurrentValue, 0, $this->tarif_acuan->ReadOnly);

			// tarif1
			$this->tarif1->SetDbValueDef($rsnew, $this->tarif1->CurrentValue, 0, $this->tarif1->ReadOnly);

			// tarif2
			$this->tarif2->SetDbValueDef($rsnew, $this->tarif2->CurrentValue, 0, $this->tarif2->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_kegiatanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($t_kegiatan_edit)) $t_kegiatan_edit = new ct_kegiatan_edit();

// Page init
$t_kegiatan_edit->Page_Init();

// Page main
$t_kegiatan_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_kegiatan_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_kegiatanedit = new ew_Form("ft_kegiatanedit", "edit");

// Validate form
ft_kegiatanedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_keg_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_kegiatan->keg_nama->FldCaption(), $t_kegiatan->keg_nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tarif_acuan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_kegiatan->tarif_acuan->FldCaption(), $t_kegiatan->tarif_acuan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tarif_acuan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_kegiatan->tarif_acuan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tarif1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_kegiatan->tarif1->FldCaption(), $t_kegiatan->tarif1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tarif1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_kegiatan->tarif1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tarif2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_kegiatan->tarif2->FldCaption(), $t_kegiatan->tarif2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tarif2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_kegiatan->tarif2->FldErrMsg()) ?>");

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
ft_kegiatanedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_kegiatanedit.ValidateRequired = true;
<?php } else { ?>
ft_kegiatanedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_kegiatan_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_kegiatan_edit->ShowPageHeader(); ?>
<?php
$t_kegiatan_edit->ShowMessage();
?>
<?php if (!$t_kegiatan_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_kegiatan_edit->Pager)) $t_kegiatan_edit->Pager = new cPrevNextPager($t_kegiatan_edit->StartRec, $t_kegiatan_edit->DisplayRecs, $t_kegiatan_edit->TotalRecs) ?>
<?php if ($t_kegiatan_edit->Pager->RecordCount > 0 && $t_kegiatan_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_kegiatan_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_kegiatan_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_kegiatan_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_kegiatan_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_kegiatan_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_kegiatan_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_kegiatanedit" id="ft_kegiatanedit" class="<?php echo $t_kegiatan_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_kegiatan_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_kegiatan_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_kegiatan">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_kegiatan_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_kegiatan->keg_nama->Visible) { // keg_nama ?>
	<div id="r_keg_nama" class="form-group">
		<label id="elh_t_kegiatan_keg_nama" for="x_keg_nama" class="col-sm-2 control-label ewLabel"><?php echo $t_kegiatan->keg_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_kegiatan->keg_nama->CellAttributes() ?>>
<span id="el_t_kegiatan_keg_nama">
<input type="text" data-table="t_kegiatan" data-field="x_keg_nama" name="x_keg_nama" id="x_keg_nama" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_kegiatan->keg_nama->getPlaceHolder()) ?>" value="<?php echo $t_kegiatan->keg_nama->EditValue ?>"<?php echo $t_kegiatan->keg_nama->EditAttributes() ?>>
</span>
<?php echo $t_kegiatan->keg_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_kegiatan->keg_ket->Visible) { // keg_ket ?>
	<div id="r_keg_ket" class="form-group">
		<label id="elh_t_kegiatan_keg_ket" for="x_keg_ket" class="col-sm-2 control-label ewLabel"><?php echo $t_kegiatan->keg_ket->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_kegiatan->keg_ket->CellAttributes() ?>>
<span id="el_t_kegiatan_keg_ket">
<input type="text" data-table="t_kegiatan" data-field="x_keg_ket" name="x_keg_ket" id="x_keg_ket" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_kegiatan->keg_ket->getPlaceHolder()) ?>" value="<?php echo $t_kegiatan->keg_ket->EditValue ?>"<?php echo $t_kegiatan->keg_ket->EditAttributes() ?>>
</span>
<?php echo $t_kegiatan->keg_ket->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_kegiatan->tarif_acuan->Visible) { // tarif_acuan ?>
	<div id="r_tarif_acuan" class="form-group">
		<label id="elh_t_kegiatan_tarif_acuan" for="x_tarif_acuan" class="col-sm-2 control-label ewLabel"><?php echo $t_kegiatan->tarif_acuan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_kegiatan->tarif_acuan->CellAttributes() ?>>
<span id="el_t_kegiatan_tarif_acuan">
<input type="text" data-table="t_kegiatan" data-field="x_tarif_acuan" name="x_tarif_acuan" id="x_tarif_acuan" size="30" placeholder="<?php echo ew_HtmlEncode($t_kegiatan->tarif_acuan->getPlaceHolder()) ?>" value="<?php echo $t_kegiatan->tarif_acuan->EditValue ?>"<?php echo $t_kegiatan->tarif_acuan->EditAttributes() ?>>
</span>
<?php echo $t_kegiatan->tarif_acuan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_kegiatan->tarif1->Visible) { // tarif1 ?>
	<div id="r_tarif1" class="form-group">
		<label id="elh_t_kegiatan_tarif1" for="x_tarif1" class="col-sm-2 control-label ewLabel"><?php echo $t_kegiatan->tarif1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_kegiatan->tarif1->CellAttributes() ?>>
<span id="el_t_kegiatan_tarif1">
<input type="text" data-table="t_kegiatan" data-field="x_tarif1" name="x_tarif1" id="x_tarif1" size="30" placeholder="<?php echo ew_HtmlEncode($t_kegiatan->tarif1->getPlaceHolder()) ?>" value="<?php echo $t_kegiatan->tarif1->EditValue ?>"<?php echo $t_kegiatan->tarif1->EditAttributes() ?>>
</span>
<?php echo $t_kegiatan->tarif1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_kegiatan->tarif2->Visible) { // tarif2 ?>
	<div id="r_tarif2" class="form-group">
		<label id="elh_t_kegiatan_tarif2" for="x_tarif2" class="col-sm-2 control-label ewLabel"><?php echo $t_kegiatan->tarif2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_kegiatan->tarif2->CellAttributes() ?>>
<span id="el_t_kegiatan_tarif2">
<input type="text" data-table="t_kegiatan" data-field="x_tarif2" name="x_tarif2" id="x_tarif2" size="30" placeholder="<?php echo ew_HtmlEncode($t_kegiatan->tarif2->getPlaceHolder()) ?>" value="<?php echo $t_kegiatan->tarif2->EditValue ?>"<?php echo $t_kegiatan->tarif2->EditAttributes() ?>>
</span>
<?php echo $t_kegiatan->tarif2->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_kegiatan" data-field="x_keg_id" name="x_keg_id" id="x_keg_id" value="<?php echo ew_HtmlEncode($t_kegiatan->keg_id->CurrentValue) ?>">
<?php if (!$t_kegiatan_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_kegiatan_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($t_kegiatan_edit->Pager)) $t_kegiatan_edit->Pager = new cPrevNextPager($t_kegiatan_edit->StartRec, $t_kegiatan_edit->DisplayRecs, $t_kegiatan_edit->TotalRecs) ?>
<?php if ($t_kegiatan_edit->Pager->RecordCount > 0 && $t_kegiatan_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_kegiatan_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_kegiatan_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_kegiatan_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_kegiatan_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_kegiatan_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_kegiatan_edit->PageUrl() ?>start=<?php echo $t_kegiatan_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_kegiatan_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_kegiatanedit.Init();
</script>
<?php
$t_kegiatan_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_kegiatan_edit->Page_Terminate();
?>
