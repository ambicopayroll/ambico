<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "t_jdw_krj_peggridcls.php" ?>
<?php include_once "t_rumus2_peggridcls.php" ?>
<?php include_once "t_rumus_peggridcls.php" ?>
<?php include_once "t_pengecualian_peggridcls.php" ?>
<?php include_once "t_lemburgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pegawai_edit = NULL; // Initialize page object first

class cpegawai_edit extends cpegawai {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 'pegawai';

	// Page object name
	var $PageObjName = 'pegawai_edit';

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

		// Table object (pegawai)
		if (!isset($GLOBALS["pegawai"]) || get_class($GLOBALS["pegawai"]) == "cpegawai") {
			$GLOBALS["pegawai"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pegawai"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pegawai', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("pegawailist.php"));
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
		$this->pegawai_pin->SetVisibility();
		$this->pegawai_nip->SetVisibility();
		$this->pegawai_nama->SetVisibility();
		$this->pembagian1_id->SetVisibility();
		$this->pembagian2_id->SetVisibility();

		// Set up detail page object
		$this->SetupDetailPages();

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

			// Process auto fill for detail table 't_jdw_krj_peg'
			if (@$_POST["grid"] == "ft_jdw_krj_peggrid") {
				if (!isset($GLOBALS["t_jdw_krj_peg_grid"])) $GLOBALS["t_jdw_krj_peg_grid"] = new ct_jdw_krj_peg_grid;
				$GLOBALS["t_jdw_krj_peg_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_rumus2_peg'
			if (@$_POST["grid"] == "ft_rumus2_peggrid") {
				if (!isset($GLOBALS["t_rumus2_peg_grid"])) $GLOBALS["t_rumus2_peg_grid"] = new ct_rumus2_peg_grid;
				$GLOBALS["t_rumus2_peg_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_rumus_peg'
			if (@$_POST["grid"] == "ft_rumus_peggrid") {
				if (!isset($GLOBALS["t_rumus_peg_grid"])) $GLOBALS["t_rumus_peg_grid"] = new ct_rumus_peg_grid;
				$GLOBALS["t_rumus_peg_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_pengecualian_peg'
			if (@$_POST["grid"] == "ft_pengecualian_peggrid") {
				if (!isset($GLOBALS["t_pengecualian_peg_grid"])) $GLOBALS["t_pengecualian_peg_grid"] = new ct_pengecualian_peg_grid;
				$GLOBALS["t_pengecualian_peg_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_lembur'
			if (@$_POST["grid"] == "ft_lemburgrid") {
				if (!isset($GLOBALS["t_lembur_grid"])) $GLOBALS["t_lembur_grid"] = new ct_lembur_grid;
				$GLOBALS["t_lembur_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $pegawai;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pegawai);
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
	var $DetailPages; // Detail pages object

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
		if (@$_GET["pegawai_id"] <> "") {
			$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
			$this->RecKey["pegawai_id"] = $this->pegawai_id->QueryStringValue;
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
			$this->Page_Terminate("pegawailist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->pegawai_id->CurrentValue) == strval($this->Recordset->fields('pegawai_id'))) {
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

			// Set up detail parameters
			$this->SetUpDetailParms();
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
					$this->Page_Terminate("pegawailist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pegawailist.php")
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

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->pegawai_pin->FldIsDetailKey) {
			$this->pegawai_pin->setFormValue($objForm->GetValue("x_pegawai_pin"));
		}
		if (!$this->pegawai_nip->FldIsDetailKey) {
			$this->pegawai_nip->setFormValue($objForm->GetValue("x_pegawai_nip"));
		}
		if (!$this->pegawai_nama->FldIsDetailKey) {
			$this->pegawai_nama->setFormValue($objForm->GetValue("x_pegawai_nama"));
		}
		if (!$this->pembagian1_id->FldIsDetailKey) {
			$this->pembagian1_id->setFormValue($objForm->GetValue("x_pembagian1_id"));
		}
		if (!$this->pembagian2_id->FldIsDetailKey) {
			$this->pembagian2_id->setFormValue($objForm->GetValue("x_pembagian2_id"));
		}
		if (!$this->pegawai_id->FldIsDetailKey)
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->pegawai_pin->CurrentValue = $this->pegawai_pin->FormValue;
		$this->pegawai_nip->CurrentValue = $this->pegawai_nip->FormValue;
		$this->pegawai_nama->CurrentValue = $this->pegawai_nama->FormValue;
		$this->pembagian1_id->CurrentValue = $this->pembagian1_id->FormValue;
		$this->pembagian2_id->CurrentValue = $this->pembagian2_id->FormValue;
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->pegawai_pin->setDbValue($rs->fields('pegawai_pin'));
		$this->pegawai_nip->setDbValue($rs->fields('pegawai_nip'));
		$this->pegawai_nama->setDbValue($rs->fields('pegawai_nama'));
		$this->pegawai_pwd->setDbValue($rs->fields('pegawai_pwd'));
		$this->pegawai_rfid->setDbValue($rs->fields('pegawai_rfid'));
		$this->pegawai_privilege->setDbValue($rs->fields('pegawai_privilege'));
		$this->pegawai_telp->setDbValue($rs->fields('pegawai_telp'));
		$this->pegawai_status->setDbValue($rs->fields('pegawai_status'));
		$this->tempat_lahir->setDbValue($rs->fields('tempat_lahir'));
		$this->tgl_lahir->setDbValue($rs->fields('tgl_lahir'));
		$this->pembagian1_id->setDbValue($rs->fields('pembagian1_id'));
		if (array_key_exists('EV__pembagian1_id', $rs->fields)) {
			$this->pembagian1_id->VirtualValue = $rs->fields('EV__pembagian1_id'); // Set up virtual field value
		} else {
			$this->pembagian1_id->VirtualValue = ""; // Clear value
		}
		$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
		if (array_key_exists('EV__pembagian2_id', $rs->fields)) {
			$this->pembagian2_id->VirtualValue = $rs->fields('EV__pembagian2_id'); // Set up virtual field value
		} else {
			$this->pembagian2_id->VirtualValue = ""; // Clear value
		}
		$this->pembagian3_id->setDbValue($rs->fields('pembagian3_id'));
		if (array_key_exists('EV__pembagian3_id', $rs->fields)) {
			$this->pembagian3_id->VirtualValue = $rs->fields('EV__pembagian3_id'); // Set up virtual field value
		} else {
			$this->pembagian3_id->VirtualValue = ""; // Clear value
		}
		$this->tgl_mulai_kerja->setDbValue($rs->fields('tgl_mulai_kerja'));
		$this->tgl_resign->setDbValue($rs->fields('tgl_resign'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->tgl_masuk_pertama->setDbValue($rs->fields('tgl_masuk_pertama'));
		$this->photo_path->setDbValue($rs->fields('photo_path'));
		$this->tmp_img->setDbValue($rs->fields('tmp_img'));
		$this->nama_bank->setDbValue($rs->fields('nama_bank'));
		$this->nama_rek->setDbValue($rs->fields('nama_rek'));
		$this->no_rek->setDbValue($rs->fields('no_rek'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->pegawai_pin->DbValue = $row['pegawai_pin'];
		$this->pegawai_nip->DbValue = $row['pegawai_nip'];
		$this->pegawai_nama->DbValue = $row['pegawai_nama'];
		$this->pegawai_pwd->DbValue = $row['pegawai_pwd'];
		$this->pegawai_rfid->DbValue = $row['pegawai_rfid'];
		$this->pegawai_privilege->DbValue = $row['pegawai_privilege'];
		$this->pegawai_telp->DbValue = $row['pegawai_telp'];
		$this->pegawai_status->DbValue = $row['pegawai_status'];
		$this->tempat_lahir->DbValue = $row['tempat_lahir'];
		$this->tgl_lahir->DbValue = $row['tgl_lahir'];
		$this->pembagian1_id->DbValue = $row['pembagian1_id'];
		$this->pembagian2_id->DbValue = $row['pembagian2_id'];
		$this->pembagian3_id->DbValue = $row['pembagian3_id'];
		$this->tgl_mulai_kerja->DbValue = $row['tgl_mulai_kerja'];
		$this->tgl_resign->DbValue = $row['tgl_resign'];
		$this->gender->DbValue = $row['gender'];
		$this->tgl_masuk_pertama->DbValue = $row['tgl_masuk_pertama'];
		$this->photo_path->DbValue = $row['photo_path'];
		$this->tmp_img->DbValue = $row['tmp_img'];
		$this->nama_bank->DbValue = $row['nama_bank'];
		$this->nama_rek->DbValue = $row['nama_rek'];
		$this->no_rek->DbValue = $row['no_rek'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// pegawai_pin
		// pegawai_nip
		// pegawai_nama
		// pegawai_pwd
		// pegawai_rfid
		// pegawai_privilege
		// pegawai_telp
		// pegawai_status
		// tempat_lahir
		// tgl_lahir
		// pembagian1_id
		// pembagian2_id
		// pembagian3_id
		// tgl_mulai_kerja
		// tgl_resign
		// gender
		// tgl_masuk_pertama
		// photo_path
		// tmp_img
		// nama_bank
		// nama_rek
		// no_rek

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// pegawai_pin
		$this->pegawai_pin->ViewValue = $this->pegawai_pin->CurrentValue;
		$this->pegawai_pin->ViewCustomAttributes = "";

		// pegawai_nip
		$this->pegawai_nip->ViewValue = $this->pegawai_nip->CurrentValue;
		$this->pegawai_nip->ViewCustomAttributes = "";

		// pegawai_nama
		$this->pegawai_nama->ViewValue = $this->pegawai_nama->CurrentValue;
		$this->pegawai_nama->ViewCustomAttributes = "";

		// pembagian1_id
		if ($this->pembagian1_id->VirtualValue <> "") {
			$this->pembagian1_id->ViewValue = $this->pembagian1_id->VirtualValue;
		} else {
		if (strval($this->pembagian1_id->CurrentValue) <> "") {
			$sFilterWrk = "`pembagian1_id`" . ew_SearchString("=", $this->pembagian1_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pembagian1_id`, `pembagian1_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian1`";
		$sWhereWrk = "";
		$this->pembagian1_id->LookupFilters = array("dx1" => '`pembagian1_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pembagian1_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pembagian1_id->ViewValue = $this->pembagian1_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pembagian1_id->ViewValue = $this->pembagian1_id->CurrentValue;
			}
		} else {
			$this->pembagian1_id->ViewValue = NULL;
		}
		}
		$this->pembagian1_id->ViewCustomAttributes = "";

		// pembagian2_id
		if ($this->pembagian2_id->VirtualValue <> "") {
			$this->pembagian2_id->ViewValue = $this->pembagian2_id->VirtualValue;
		} else {
		if (strval($this->pembagian2_id->CurrentValue) <> "") {
			$sFilterWrk = "`pembagian2_id`" . ew_SearchString("=", $this->pembagian2_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pembagian2_id`, `pembagian2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian2`";
		$sWhereWrk = "";
		$this->pembagian2_id->LookupFilters = array("dx1" => '`pembagian2_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pembagian2_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pembagian2_id->ViewValue = $this->pembagian2_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pembagian2_id->ViewValue = $this->pembagian2_id->CurrentValue;
			}
		} else {
			$this->pembagian2_id->ViewValue = NULL;
		}
		}
		$this->pembagian2_id->ViewCustomAttributes = "";

			// pegawai_pin
			$this->pegawai_pin->LinkCustomAttributes = "";
			$this->pegawai_pin->HrefValue = "";
			$this->pegawai_pin->TooltipValue = "";

			// pegawai_nip
			$this->pegawai_nip->LinkCustomAttributes = "";
			$this->pegawai_nip->HrefValue = "";
			$this->pegawai_nip->TooltipValue = "";

			// pegawai_nama
			$this->pegawai_nama->LinkCustomAttributes = "";
			$this->pegawai_nama->HrefValue = "";
			$this->pegawai_nama->TooltipValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";
			$this->pembagian1_id->TooltipValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";
			$this->pembagian2_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pegawai_pin
			$this->pegawai_pin->EditAttrs["class"] = "form-control";
			$this->pegawai_pin->EditCustomAttributes = "";
			$this->pegawai_pin->EditValue = ew_HtmlEncode($this->pegawai_pin->CurrentValue);
			$this->pegawai_pin->PlaceHolder = ew_RemoveHtml($this->pegawai_pin->FldCaption());

			// pegawai_nip
			$this->pegawai_nip->EditAttrs["class"] = "form-control";
			$this->pegawai_nip->EditCustomAttributes = "";
			$this->pegawai_nip->EditValue = ew_HtmlEncode($this->pegawai_nip->CurrentValue);
			$this->pegawai_nip->PlaceHolder = ew_RemoveHtml($this->pegawai_nip->FldCaption());

			// pegawai_nama
			$this->pegawai_nama->EditAttrs["class"] = "form-control";
			$this->pegawai_nama->EditCustomAttributes = "";
			$this->pegawai_nama->EditValue = ew_HtmlEncode($this->pegawai_nama->CurrentValue);
			$this->pegawai_nama->PlaceHolder = ew_RemoveHtml($this->pegawai_nama->FldCaption());

			// pembagian1_id
			$this->pembagian1_id->EditCustomAttributes = "";
			if (trim(strval($this->pembagian1_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`pembagian1_id`" . ew_SearchString("=", $this->pembagian1_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `pembagian1_id`, `pembagian1_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pembagian1`";
			$sWhereWrk = "";
			$this->pembagian1_id->LookupFilters = array("dx1" => '`pembagian1_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pembagian1_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->pembagian1_id->ViewValue = $this->pembagian1_id->DisplayValue($arwrk);
			} else {
				$this->pembagian1_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->pembagian1_id->EditValue = $arwrk;

			// pembagian2_id
			$this->pembagian2_id->EditCustomAttributes = "";
			if (trim(strval($this->pembagian2_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`pembagian2_id`" . ew_SearchString("=", $this->pembagian2_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `pembagian2_id`, `pembagian2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pembagian2`";
			$sWhereWrk = "";
			$this->pembagian2_id->LookupFilters = array("dx1" => '`pembagian2_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pembagian2_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->pembagian2_id->ViewValue = $this->pembagian2_id->DisplayValue($arwrk);
			} else {
				$this->pembagian2_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->pembagian2_id->EditValue = $arwrk;

			// Edit refer script
			// pegawai_pin

			$this->pegawai_pin->LinkCustomAttributes = "";
			$this->pegawai_pin->HrefValue = "";

			// pegawai_nip
			$this->pegawai_nip->LinkCustomAttributes = "";
			$this->pegawai_nip->HrefValue = "";

			// pegawai_nama
			$this->pegawai_nama->LinkCustomAttributes = "";
			$this->pegawai_nama->HrefValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";
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
		if (!$this->pegawai_pin->FldIsDetailKey && !is_null($this->pegawai_pin->FormValue) && $this->pegawai_pin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_pin->FldCaption(), $this->pegawai_pin->ReqErrMsg));
		}
		if (!$this->pegawai_nama->FldIsDetailKey && !is_null($this->pegawai_nama->FormValue) && $this->pegawai_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_nama->FldCaption(), $this->pegawai_nama->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_jdw_krj_peg", $DetailTblVar) && $GLOBALS["t_jdw_krj_peg"]->DetailEdit) {
			if (!isset($GLOBALS["t_jdw_krj_peg_grid"])) $GLOBALS["t_jdw_krj_peg_grid"] = new ct_jdw_krj_peg_grid(); // get detail page object
			$GLOBALS["t_jdw_krj_peg_grid"]->ValidateGridForm();
		}
		if (in_array("t_rumus2_peg", $DetailTblVar) && $GLOBALS["t_rumus2_peg"]->DetailEdit) {
			if (!isset($GLOBALS["t_rumus2_peg_grid"])) $GLOBALS["t_rumus2_peg_grid"] = new ct_rumus2_peg_grid(); // get detail page object
			$GLOBALS["t_rumus2_peg_grid"]->ValidateGridForm();
		}
		if (in_array("t_rumus_peg", $DetailTblVar) && $GLOBALS["t_rumus_peg"]->DetailEdit) {
			if (!isset($GLOBALS["t_rumus_peg_grid"])) $GLOBALS["t_rumus_peg_grid"] = new ct_rumus_peg_grid(); // get detail page object
			$GLOBALS["t_rumus_peg_grid"]->ValidateGridForm();
		}
		if (in_array("t_pengecualian_peg", $DetailTblVar) && $GLOBALS["t_pengecualian_peg"]->DetailEdit) {
			if (!isset($GLOBALS["t_pengecualian_peg_grid"])) $GLOBALS["t_pengecualian_peg_grid"] = new ct_pengecualian_peg_grid(); // get detail page object
			$GLOBALS["t_pengecualian_peg_grid"]->ValidateGridForm();
		}
		if (in_array("t_lembur", $DetailTblVar) && $GLOBALS["t_lembur"]->DetailEdit) {
			if (!isset($GLOBALS["t_lembur_grid"])) $GLOBALS["t_lembur_grid"] = new ct_lembur_grid(); // get detail page object
			$GLOBALS["t_lembur_grid"]->ValidateGridForm();
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
		if ($this->pegawai_pin->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`pegawai_pin` = '" . ew_AdjustSql($this->pegawai_pin->CurrentValue, $this->DBID) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->pegawai_pin->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->pegawai_pin->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// pegawai_pin
			$this->pegawai_pin->SetDbValueDef($rsnew, $this->pegawai_pin->CurrentValue, "", $this->pegawai_pin->ReadOnly);

			// pegawai_nip
			$this->pegawai_nip->SetDbValueDef($rsnew, $this->pegawai_nip->CurrentValue, NULL, $this->pegawai_nip->ReadOnly);

			// pegawai_nama
			$this->pegawai_nama->SetDbValueDef($rsnew, $this->pegawai_nama->CurrentValue, "", $this->pegawai_nama->ReadOnly);

			// pembagian1_id
			$this->pembagian1_id->SetDbValueDef($rsnew, $this->pembagian1_id->CurrentValue, NULL, $this->pembagian1_id->ReadOnly);

			// pembagian2_id
			$this->pembagian2_id->SetDbValueDef($rsnew, $this->pembagian2_id->CurrentValue, NULL, $this->pembagian2_id->ReadOnly);

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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("t_jdw_krj_peg", $DetailTblVar) && $GLOBALS["t_jdw_krj_peg"]->DetailEdit) {
						if (!isset($GLOBALS["t_jdw_krj_peg_grid"])) $GLOBALS["t_jdw_krj_peg_grid"] = new ct_jdw_krj_peg_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_jdw_krj_peg"); // Load user level of detail table
						$EditRow = $GLOBALS["t_jdw_krj_peg_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_rumus2_peg", $DetailTblVar) && $GLOBALS["t_rumus2_peg"]->DetailEdit) {
						if (!isset($GLOBALS["t_rumus2_peg_grid"])) $GLOBALS["t_rumus2_peg_grid"] = new ct_rumus2_peg_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_rumus2_peg"); // Load user level of detail table
						$EditRow = $GLOBALS["t_rumus2_peg_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_rumus_peg", $DetailTblVar) && $GLOBALS["t_rumus_peg"]->DetailEdit) {
						if (!isset($GLOBALS["t_rumus_peg_grid"])) $GLOBALS["t_rumus_peg_grid"] = new ct_rumus_peg_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_rumus_peg"); // Load user level of detail table
						$EditRow = $GLOBALS["t_rumus_peg_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_pengecualian_peg", $DetailTblVar) && $GLOBALS["t_pengecualian_peg"]->DetailEdit) {
						if (!isset($GLOBALS["t_pengecualian_peg_grid"])) $GLOBALS["t_pengecualian_peg_grid"] = new ct_pengecualian_peg_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_pengecualian_peg"); // Load user level of detail table
						$EditRow = $GLOBALS["t_pengecualian_peg_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_lembur", $DetailTblVar) && $GLOBALS["t_lembur"]->DetailEdit) {
						if (!isset($GLOBALS["t_lembur_grid"])) $GLOBALS["t_lembur_grid"] = new ct_lembur_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_lembur"); // Load user level of detail table
						$EditRow = $GLOBALS["t_lembur_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("t_jdw_krj_peg", $DetailTblVar)) {
				if (!isset($GLOBALS["t_jdw_krj_peg_grid"]))
					$GLOBALS["t_jdw_krj_peg_grid"] = new ct_jdw_krj_peg_grid;
				if ($GLOBALS["t_jdw_krj_peg_grid"]->DetailEdit) {
					$GLOBALS["t_jdw_krj_peg_grid"]->CurrentMode = "edit";
					$GLOBALS["t_jdw_krj_peg_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_jdw_krj_peg_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_jdw_krj_peg_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_jdw_krj_peg_grid"]->pegawai_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_jdw_krj_peg_grid"]->pegawai_id->CurrentValue = $this->pegawai_id->CurrentValue;
					$GLOBALS["t_jdw_krj_peg_grid"]->pegawai_id->setSessionValue($GLOBALS["t_jdw_krj_peg_grid"]->pegawai_id->CurrentValue);
				}
			}
			if (in_array("t_rumus2_peg", $DetailTblVar)) {
				if (!isset($GLOBALS["t_rumus2_peg_grid"]))
					$GLOBALS["t_rumus2_peg_grid"] = new ct_rumus2_peg_grid;
				if ($GLOBALS["t_rumus2_peg_grid"]->DetailEdit) {
					$GLOBALS["t_rumus2_peg_grid"]->CurrentMode = "edit";
					$GLOBALS["t_rumus2_peg_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_rumus2_peg_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_rumus2_peg_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_rumus2_peg_grid"]->pegawai_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_rumus2_peg_grid"]->pegawai_id->CurrentValue = $this->pegawai_id->CurrentValue;
					$GLOBALS["t_rumus2_peg_grid"]->pegawai_id->setSessionValue($GLOBALS["t_rumus2_peg_grid"]->pegawai_id->CurrentValue);
				}
			}
			if (in_array("t_rumus_peg", $DetailTblVar)) {
				if (!isset($GLOBALS["t_rumus_peg_grid"]))
					$GLOBALS["t_rumus_peg_grid"] = new ct_rumus_peg_grid;
				if ($GLOBALS["t_rumus_peg_grid"]->DetailEdit) {
					$GLOBALS["t_rumus_peg_grid"]->CurrentMode = "edit";
					$GLOBALS["t_rumus_peg_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_rumus_peg_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_rumus_peg_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_rumus_peg_grid"]->pegawai_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_rumus_peg_grid"]->pegawai_id->CurrentValue = $this->pegawai_id->CurrentValue;
					$GLOBALS["t_rumus_peg_grid"]->pegawai_id->setSessionValue($GLOBALS["t_rumus_peg_grid"]->pegawai_id->CurrentValue);
				}
			}
			if (in_array("t_pengecualian_peg", $DetailTblVar)) {
				if (!isset($GLOBALS["t_pengecualian_peg_grid"]))
					$GLOBALS["t_pengecualian_peg_grid"] = new ct_pengecualian_peg_grid;
				if ($GLOBALS["t_pengecualian_peg_grid"]->DetailEdit) {
					$GLOBALS["t_pengecualian_peg_grid"]->CurrentMode = "edit";
					$GLOBALS["t_pengecualian_peg_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_pengecualian_peg_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_pengecualian_peg_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_pengecualian_peg_grid"]->pegawai_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_pengecualian_peg_grid"]->pegawai_id->CurrentValue = $this->pegawai_id->CurrentValue;
					$GLOBALS["t_pengecualian_peg_grid"]->pegawai_id->setSessionValue($GLOBALS["t_pengecualian_peg_grid"]->pegawai_id->CurrentValue);
				}
			}
			if (in_array("t_lembur", $DetailTblVar)) {
				if (!isset($GLOBALS["t_lembur_grid"]))
					$GLOBALS["t_lembur_grid"] = new ct_lembur_grid;
				if ($GLOBALS["t_lembur_grid"]->DetailEdit) {
					$GLOBALS["t_lembur_grid"]->CurrentMode = "edit";
					$GLOBALS["t_lembur_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_lembur_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_lembur_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_lembur_grid"]->pegawai_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_lembur_grid"]->pegawai_id->CurrentValue = $this->pegawai_id->CurrentValue;
					$GLOBALS["t_lembur_grid"]->pegawai_id->setSessionValue($GLOBALS["t_lembur_grid"]->pegawai_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pegawailist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add('t_jdw_krj_peg');
		$pages->Add('t_rumus2_peg');
		$pages->Add('t_rumus_peg');
		$pages->Add('t_pengecualian_peg');
		$pages->Add('t_lembur');
		$this->DetailPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_pembagian1_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `pembagian1_id` AS `LinkFld`, `pembagian1_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian1`";
			$sWhereWrk = "{filter}";
			$this->pembagian1_id->LookupFilters = array("dx1" => '`pembagian1_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`pembagian1_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->pembagian1_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_pembagian2_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `pembagian2_id` AS `LinkFld`, `pembagian2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian2`";
			$sWhereWrk = "{filter}";
			$this->pembagian2_id->LookupFilters = array("dx1" => '`pembagian2_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`pembagian2_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->pembagian2_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($pegawai_edit)) $pegawai_edit = new cpegawai_edit();

// Page init
$pegawai_edit->Page_Init();

// Page main
$pegawai_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpegawaiedit = new ew_Form("fpegawaiedit", "edit");

// Validate form
fpegawaiedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_pin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_pin->FldCaption(), $pegawai->pegawai_pin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_nama->FldCaption(), $pegawai->pegawai_nama->ReqErrMsg)) ?>");

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
fpegawaiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawaiedit.ValidateRequired = true;
<?php } else { ?>
fpegawaiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpegawaiedit.Lists["x_pembagian1_id"] = {"LinkField":"x_pembagian1_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian1_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian1"};
fpegawaiedit.Lists["x_pembagian2_id"] = {"LinkField":"x_pembagian2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian2"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pegawai_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pegawai_edit->ShowPageHeader(); ?>
<?php
$pegawai_edit->ShowMessage();
?>
<?php if (!$pegawai_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pegawai_edit->Pager)) $pegawai_edit->Pager = new cPrevNextPager($pegawai_edit->StartRec, $pegawai_edit->DisplayRecs, $pegawai_edit->TotalRecs) ?>
<?php if ($pegawai_edit->Pager->RecordCount > 0 && $pegawai_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fpegawaiedit" id="fpegawaiedit" class="<?php echo $pegawai_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($pegawai_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
	<div id="r_pegawai_pin" class="form-group">
		<label id="elh_pegawai_pegawai_pin" for="x_pegawai_pin" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_pin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pin">
<input type="text" data-table="pegawai" data-field="x_pegawai_pin" name="x_pegawai_pin" id="x_pegawai_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pin->EditValue ?>"<?php echo $pegawai->pegawai_pin->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_pin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
	<div id="r_pegawai_nip" class="form-group">
		<label id="elh_pegawai_pegawai_nip" for="x_pegawai_nip" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_nip->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nip">
<input type="text" data-table="pegawai" data-field="x_pegawai_nip" name="x_pegawai_nip" id="x_pegawai_nip" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nip->EditValue ?>"<?php echo $pegawai->pegawai_nip->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_nip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
	<div id="r_pegawai_nama" class="form-group">
		<label id="elh_pegawai_pegawai_nama" for="x_pegawai_nama" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nama">
<input type="text" data-table="pegawai" data-field="x_pegawai_nama" name="x_pegawai_nama" id="x_pegawai_nama" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nama->EditValue ?>"<?php echo $pegawai->pegawai_nama->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
	<div id="r_pembagian1_id" class="form-group">
		<label id="elh_pegawai_pembagian1_id" for="x_pembagian1_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pembagian1_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian1_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_pembagian1_id"><?php echo (strval($pegawai->pembagian1_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian1_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian1_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_pembagian1_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian1_id->DisplayValueSeparatorAttribute() ?>" name="x_pembagian1_id" id="x_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->CurrentValue ?>"<?php echo $pegawai->pembagian1_id->EditAttributes() ?>>
<input type="hidden" name="s_x_pembagian1_id" id="s_x_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->LookupFilterQuery() ?>">
</span>
<?php echo $pegawai->pembagian1_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
	<div id="r_pembagian2_id" class="form-group">
		<label id="elh_pegawai_pembagian2_id" for="x_pembagian2_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pembagian2_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_pembagian2_id"><?php echo (strval($pegawai->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x_pembagian2_id" id="x_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->CurrentValue ?>"<?php echo $pegawai->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x_pembagian2_id" id="s_x_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->LookupFilterQuery() ?>">
</span>
<?php echo $pegawai->pembagian2_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai->pegawai_id->CurrentValue) ?>">
<?php if ($pegawai->getCurrentDetailTable() <> "") { ?>
<?php
	$pegawai_edit->DetailPages->ValidKeys = explode(",", $pegawai->getCurrentDetailTable());
	$FirstActiveDetailTable = $pegawai_edit->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="pegawai_edit_details">
	<ul class="nav<?php echo $pegawai_edit->DetailPages->NavStyle() ?>">
<?php
	if (in_array("t_jdw_krj_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_jdw_krj_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_jdw_krj_peg") {
			$FirstActiveDetailTable = "t_jdw_krj_peg";
		}
?>
		<li<?php echo $pegawai_edit->DetailPages->TabStyle("t_jdw_krj_peg") ?>><a href="#tab_t_jdw_krj_peg" data-toggle="tab"><?php echo $Language->TablePhrase("t_jdw_krj_peg", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("t_rumus2_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_rumus2_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_rumus2_peg") {
			$FirstActiveDetailTable = "t_rumus2_peg";
		}
?>
		<li<?php echo $pegawai_edit->DetailPages->TabStyle("t_rumus2_peg") ?>><a href="#tab_t_rumus2_peg" data-toggle="tab"><?php echo $Language->TablePhrase("t_rumus2_peg", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("t_rumus_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_rumus_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_rumus_peg") {
			$FirstActiveDetailTable = "t_rumus_peg";
		}
?>
		<li<?php echo $pegawai_edit->DetailPages->TabStyle("t_rumus_peg") ?>><a href="#tab_t_rumus_peg" data-toggle="tab"><?php echo $Language->TablePhrase("t_rumus_peg", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("t_pengecualian_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_pengecualian_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_pengecualian_peg") {
			$FirstActiveDetailTable = "t_pengecualian_peg";
		}
?>
		<li<?php echo $pegawai_edit->DetailPages->TabStyle("t_pengecualian_peg") ?>><a href="#tab_t_pengecualian_peg" data-toggle="tab"><?php echo $Language->TablePhrase("t_pengecualian_peg", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("t_lembur", explode(",", $pegawai->getCurrentDetailTable())) && $t_lembur->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_lembur") {
			$FirstActiveDetailTable = "t_lembur";
		}
?>
		<li<?php echo $pegawai_edit->DetailPages->TabStyle("t_lembur") ?>><a href="#tab_t_lembur" data-toggle="tab"><?php echo $Language->TablePhrase("t_lembur", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("t_jdw_krj_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_jdw_krj_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_jdw_krj_peg") {
			$FirstActiveDetailTable = "t_jdw_krj_peg";
		}
?>
		<div class="tab-pane<?php echo $pegawai_edit->DetailPages->PageStyle("t_jdw_krj_peg") ?>" id="tab_t_jdw_krj_peg">
<?php include_once "t_jdw_krj_peggrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("t_rumus2_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_rumus2_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_rumus2_peg") {
			$FirstActiveDetailTable = "t_rumus2_peg";
		}
?>
		<div class="tab-pane<?php echo $pegawai_edit->DetailPages->PageStyle("t_rumus2_peg") ?>" id="tab_t_rumus2_peg">
<?php include_once "t_rumus2_peggrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("t_rumus_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_rumus_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_rumus_peg") {
			$FirstActiveDetailTable = "t_rumus_peg";
		}
?>
		<div class="tab-pane<?php echo $pegawai_edit->DetailPages->PageStyle("t_rumus_peg") ?>" id="tab_t_rumus_peg">
<?php include_once "t_rumus_peggrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("t_pengecualian_peg", explode(",", $pegawai->getCurrentDetailTable())) && $t_pengecualian_peg->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_pengecualian_peg") {
			$FirstActiveDetailTable = "t_pengecualian_peg";
		}
?>
		<div class="tab-pane<?php echo $pegawai_edit->DetailPages->PageStyle("t_pengecualian_peg") ?>" id="tab_t_pengecualian_peg">
<?php include_once "t_pengecualian_peggrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("t_lembur", explode(",", $pegawai->getCurrentDetailTable())) && $t_lembur->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_lembur") {
			$FirstActiveDetailTable = "t_lembur";
		}
?>
		<div class="tab-pane<?php echo $pegawai_edit->DetailPages->PageStyle("t_lembur") ?>" id="tab_t_lembur">
<?php include_once "t_lemburgrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
<?php if (!$pegawai_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pegawai_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($pegawai_edit->Pager)) $pegawai_edit->Pager = new cPrevNextPager($pegawai_edit->StartRec, $pegawai_edit->DisplayRecs, $pegawai_edit->TotalRecs) ?>
<?php if ($pegawai_edit->Pager->RecordCount > 0 && $pegawai_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_edit->PageUrl() ?>start=<?php echo $pegawai_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fpegawaiedit.Init();
</script>
<?php
$pegawai_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pegawai_edit->Page_Terminate();
?>
