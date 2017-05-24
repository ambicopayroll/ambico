<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_pengecualian_peginfo.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_pengecualian_peg_edit = NULL; // Initialize page object first

class ct_pengecualian_peg_edit extends ct_pengecualian_peg {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_pengecualian_peg';

	// Page object name
	var $PageObjName = 't_pengecualian_peg_edit';

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

		// Table object (t_pengecualian_peg)
		if (!isset($GLOBALS["t_pengecualian_peg"]) || get_class($GLOBALS["t_pengecualian_peg"]) == "ct_pengecualian_peg") {
			$GLOBALS["t_pengecualian_peg"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_pengecualian_peg"];
		}

		// Table object (pegawai)
		if (!isset($GLOBALS['pegawai'])) $GLOBALS['pegawai'] = new cpegawai();

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_pengecualian_peg', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_pengecualian_peglist.php"));
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
		$this->pegawai_id->SetVisibility();
		$this->jns_id->SetVisibility();
		$this->tgl->SetVisibility();
		$this->tgl2->SetVisibility();
		$this->jam_masuk->SetVisibility();
		$this->jam_keluar->SetVisibility();
		$this->pegawai_id2->SetVisibility();
		$this->pegawai_id3->SetVisibility();

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
		global $EW_EXPORT, $t_pengecualian_peg;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_pengecualian_peg);
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
		if (@$_GET["pengecualian_id"] <> "") {
			$this->pengecualian_id->setQueryStringValue($_GET["pengecualian_id"]);
			$this->RecKey["pengecualian_id"] = $this->pengecualian_id->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("t_pengecualian_peglist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->pengecualian_id->CurrentValue) == strval($this->Recordset->fields('pengecualian_id'))) {
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
					$this->Page_Terminate("t_pengecualian_peglist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_pengecualian_peglist.php")
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
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->jns_id->FldIsDetailKey) {
			$this->jns_id->setFormValue($objForm->GetValue("x_jns_id"));
		}
		if (!$this->tgl->FldIsDetailKey) {
			$this->tgl->setFormValue($objForm->GetValue("x_tgl"));
			$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		}
		if (!$this->tgl2->FldIsDetailKey) {
			$this->tgl2->setFormValue($objForm->GetValue("x_tgl2"));
			$this->tgl2->CurrentValue = ew_UnFormatDateTime($this->tgl2->CurrentValue, 0);
		}
		if (!$this->jam_masuk->FldIsDetailKey) {
			$this->jam_masuk->setFormValue($objForm->GetValue("x_jam_masuk"));
			$this->jam_masuk->CurrentValue = ew_UnFormatDateTime($this->jam_masuk->CurrentValue, 9);
		}
		if (!$this->jam_keluar->FldIsDetailKey) {
			$this->jam_keluar->setFormValue($objForm->GetValue("x_jam_keluar"));
			$this->jam_keluar->CurrentValue = ew_UnFormatDateTime($this->jam_keluar->CurrentValue, 9);
		}
		if (!$this->pegawai_id2->FldIsDetailKey) {
			$this->pegawai_id2->setFormValue($objForm->GetValue("x_pegawai_id2"));
		}
		if (!$this->pegawai_id3->FldIsDetailKey) {
			$this->pegawai_id3->setFormValue($objForm->GetValue("x_pegawai_id3"));
		}
		if (!$this->pengecualian_id->FldIsDetailKey)
			$this->pengecualian_id->setFormValue($objForm->GetValue("x_pengecualian_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->pengecualian_id->CurrentValue = $this->pengecualian_id->FormValue;
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->jns_id->CurrentValue = $this->jns_id->FormValue;
		$this->tgl->CurrentValue = $this->tgl->FormValue;
		$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		$this->tgl2->CurrentValue = $this->tgl2->FormValue;
		$this->tgl2->CurrentValue = ew_UnFormatDateTime($this->tgl2->CurrentValue, 0);
		$this->jam_masuk->CurrentValue = $this->jam_masuk->FormValue;
		$this->jam_masuk->CurrentValue = ew_UnFormatDateTime($this->jam_masuk->CurrentValue, 9);
		$this->jam_keluar->CurrentValue = $this->jam_keluar->FormValue;
		$this->jam_keluar->CurrentValue = ew_UnFormatDateTime($this->jam_keluar->CurrentValue, 9);
		$this->pegawai_id2->CurrentValue = $this->pegawai_id2->FormValue;
		$this->pegawai_id3->CurrentValue = $this->pegawai_id3->FormValue;
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
		$this->pengecualian_id->setDbValue($rs->fields('pengecualian_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		if (array_key_exists('EV__pegawai_id', $rs->fields)) {
			$this->pegawai_id->VirtualValue = $rs->fields('EV__pegawai_id'); // Set up virtual field value
		} else {
			$this->pegawai_id->VirtualValue = ""; // Clear value
		}
		$this->jns_id->setDbValue($rs->fields('jns_id'));
		if (array_key_exists('EV__jns_id', $rs->fields)) {
			$this->jns_id->VirtualValue = $rs->fields('EV__jns_id'); // Set up virtual field value
		} else {
			$this->jns_id->VirtualValue = ""; // Clear value
		}
		$this->tgl->setDbValue($rs->fields('tgl'));
		$this->tgl2->setDbValue($rs->fields('tgl2'));
		$this->jam_masuk->setDbValue($rs->fields('jam_masuk'));
		$this->jam_keluar->setDbValue($rs->fields('jam_keluar'));
		$this->pegawai_id2->setDbValue($rs->fields('pegawai_id2'));
		if (array_key_exists('EV__pegawai_id2', $rs->fields)) {
			$this->pegawai_id2->VirtualValue = $rs->fields('EV__pegawai_id2'); // Set up virtual field value
		} else {
			$this->pegawai_id2->VirtualValue = ""; // Clear value
		}
		$this->pegawai_id3->setDbValue($rs->fields('pegawai_id3'));
		if (array_key_exists('EV__pegawai_id3', $rs->fields)) {
			$this->pegawai_id3->VirtualValue = $rs->fields('EV__pegawai_id3'); // Set up virtual field value
		} else {
			$this->pegawai_id3->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pengecualian_id->DbValue = $row['pengecualian_id'];
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->jns_id->DbValue = $row['jns_id'];
		$this->tgl->DbValue = $row['tgl'];
		$this->tgl2->DbValue = $row['tgl2'];
		$this->jam_masuk->DbValue = $row['jam_masuk'];
		$this->jam_keluar->DbValue = $row['jam_keluar'];
		$this->pegawai_id2->DbValue = $row['pegawai_id2'];
		$this->pegawai_id3->DbValue = $row['pegawai_id3'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pengecualian_id
		// pegawai_id
		// jns_id
		// tgl
		// tgl2
		// jam_masuk
		// jam_keluar
		// pegawai_id2
		// pegawai_id3

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pengecualian_id
		$this->pengecualian_id->ViewValue = $this->pengecualian_id->CurrentValue;
		$this->pengecualian_id->ViewCustomAttributes = "";

		// pegawai_id
		if ($this->pegawai_id->VirtualValue <> "") {
			$this->pegawai_id->ViewValue = $this->pegawai_id->VirtualValue;
		} else {
		if (strval($this->pegawai_id->CurrentValue) <> "") {
			$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
		$sWhereWrk = "";
		$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pegawai_id->ViewValue = $this->pegawai_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
			}
		} else {
			$this->pegawai_id->ViewValue = NULL;
		}
		}
		$this->pegawai_id->ViewCustomAttributes = "";

		// jns_id
		if ($this->jns_id->VirtualValue <> "") {
			$this->jns_id->ViewValue = $this->jns_id->VirtualValue;
		} else {
		if (strval($this->jns_id->CurrentValue) <> "") {
			$sFilterWrk = "`jns_id`" . ew_SearchString("=", $this->jns_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `jns_id`, `kode` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_jns_pengecualian`";
		$sWhereWrk = "";
		$this->jns_id->LookupFilters = array("dx1" => '`kode`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jns_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jns_id->ViewValue = $this->jns_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jns_id->ViewValue = $this->jns_id->CurrentValue;
			}
		} else {
			$this->jns_id->ViewValue = NULL;
		}
		}
		$this->jns_id->ViewCustomAttributes = "";

		// tgl
		$this->tgl->ViewValue = $this->tgl->CurrentValue;
		$this->tgl->ViewValue = tgl_indo($this->tgl->ViewValue);
		$this->tgl->ViewCustomAttributes = "";

		// tgl2
		$this->tgl2->ViewValue = $this->tgl2->CurrentValue;
		$this->tgl2->ViewValue = tgl_indo($this->tgl2->ViewValue);
		$this->tgl2->ViewCustomAttributes = "";

		// jam_masuk
		$this->jam_masuk->ViewValue = $this->jam_masuk->CurrentValue;
		$this->jam_masuk->ViewValue = ew_FormatDateTime($this->jam_masuk->ViewValue, 9);
		$this->jam_masuk->ViewCustomAttributes = "";

		// jam_keluar
		$this->jam_keluar->ViewValue = $this->jam_keluar->CurrentValue;
		$this->jam_keluar->ViewValue = ew_FormatDateTime($this->jam_keluar->ViewValue, 9);
		$this->jam_keluar->ViewCustomAttributes = "";

		// pegawai_id2
		if ($this->pegawai_id2->VirtualValue <> "") {
			$this->pegawai_id2->ViewValue = $this->pegawai_id2->VirtualValue;
		} else {
		if (strval($this->pegawai_id2->CurrentValue) <> "") {
			$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id2->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
		$sWhereWrk = "";
		$this->pegawai_id2->LookupFilters = array("dx1" => '`pegawai_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pegawai_id2, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pegawai_id2->ViewValue = $this->pegawai_id2->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pegawai_id2->ViewValue = $this->pegawai_id2->CurrentValue;
			}
		} else {
			$this->pegawai_id2->ViewValue = NULL;
		}
		}
		$this->pegawai_id2->ViewCustomAttributes = "";

		// pegawai_id3
		if ($this->pegawai_id3->VirtualValue <> "") {
			$this->pegawai_id3->ViewValue = $this->pegawai_id3->VirtualValue;
		} else {
		if (strval($this->pegawai_id3->CurrentValue) <> "") {
			$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id3->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
		$sWhereWrk = "";
		$this->pegawai_id3->LookupFilters = array("dx1" => '`pegawai_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pegawai_id3, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pegawai_id3->ViewValue = $this->pegawai_id3->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pegawai_id3->ViewValue = $this->pegawai_id3->CurrentValue;
			}
		} else {
			$this->pegawai_id3->ViewValue = NULL;
		}
		}
		$this->pegawai_id3->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// jns_id
			$this->jns_id->LinkCustomAttributes = "";
			$this->jns_id->HrefValue = "";
			$this->jns_id->TooltipValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";
			$this->tgl->TooltipValue = "";

			// tgl2
			$this->tgl2->LinkCustomAttributes = "";
			$this->tgl2->HrefValue = "";
			$this->tgl2->TooltipValue = "";

			// jam_masuk
			$this->jam_masuk->LinkCustomAttributes = "";
			$this->jam_masuk->HrefValue = "";
			$this->jam_masuk->TooltipValue = "";

			// jam_keluar
			$this->jam_keluar->LinkCustomAttributes = "";
			$this->jam_keluar->HrefValue = "";
			$this->jam_keluar->TooltipValue = "";

			// pegawai_id2
			$this->pegawai_id2->LinkCustomAttributes = "";
			$this->pegawai_id2->HrefValue = "";
			$this->pegawai_id2->TooltipValue = "";

			// pegawai_id3
			$this->pegawai_id3->LinkCustomAttributes = "";
			$this->pegawai_id3->HrefValue = "";
			$this->pegawai_id3->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pegawai_id
			$this->pegawai_id->EditCustomAttributes = "";
			if ($this->pegawai_id->getSessionValue() <> "") {
				$this->pegawai_id->CurrentValue = $this->pegawai_id->getSessionValue();
			if ($this->pegawai_id->VirtualValue <> "") {
				$this->pegawai_id->ViewValue = $this->pegawai_id->VirtualValue;
			} else {
			if (strval($this->pegawai_id->CurrentValue) <> "") {
				$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
			$sWhereWrk = "";
			$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->pegawai_id->ViewValue = $this->pegawai_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
				}
			} else {
				$this->pegawai_id->ViewValue = NULL;
			}
			}
			$this->pegawai_id->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->pegawai_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pegawai`";
			$sWhereWrk = "";
			$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->pegawai_id->ViewValue = $this->pegawai_id->DisplayValue($arwrk);
			} else {
				$this->pegawai_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->pegawai_id->EditValue = $arwrk;
			}

			// jns_id
			$this->jns_id->EditCustomAttributes = "";
			if (trim(strval($this->jns_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`jns_id`" . ew_SearchString("=", $this->jns_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `jns_id`, `kode` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_jns_pengecualian`";
			$sWhereWrk = "";
			$this->jns_id->LookupFilters = array("dx1" => '`kode`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->jns_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->jns_id->ViewValue = $this->jns_id->DisplayValue($arwrk);
			} else {
				$this->jns_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->jns_id->EditValue = $arwrk;

			// tgl
			$this->tgl->EditAttrs["class"] = "form-control";
			$this->tgl->EditCustomAttributes = "";
			$this->tgl->EditValue = ew_HtmlEncode($this->tgl->CurrentValue);
			$this->tgl->PlaceHolder = ew_RemoveHtml($this->tgl->FldCaption());

			// tgl2
			$this->tgl2->EditAttrs["class"] = "form-control";
			$this->tgl2->EditCustomAttributes = "";
			$this->tgl2->EditValue = ew_HtmlEncode($this->tgl2->CurrentValue);
			$this->tgl2->PlaceHolder = ew_RemoveHtml($this->tgl2->FldCaption());

			// jam_masuk
			$this->jam_masuk->EditAttrs["class"] = "form-control";
			$this->jam_masuk->EditCustomAttributes = "";
			$this->jam_masuk->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->jam_masuk->CurrentValue, 9));
			$this->jam_masuk->PlaceHolder = ew_RemoveHtml($this->jam_masuk->FldCaption());

			// jam_keluar
			$this->jam_keluar->EditAttrs["class"] = "form-control";
			$this->jam_keluar->EditCustomAttributes = "";
			$this->jam_keluar->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->jam_keluar->CurrentValue, 9));
			$this->jam_keluar->PlaceHolder = ew_RemoveHtml($this->jam_keluar->FldCaption());

			// pegawai_id2
			$this->pegawai_id2->EditCustomAttributes = "";
			if (trim(strval($this->pegawai_id2->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id2->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pegawai`";
			$sWhereWrk = "";
			$this->pegawai_id2->LookupFilters = array("dx1" => '`pegawai_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pegawai_id2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->pegawai_id2->ViewValue = $this->pegawai_id2->DisplayValue($arwrk);
			} else {
				$this->pegawai_id2->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->pegawai_id2->EditValue = $arwrk;

			// pegawai_id3
			$this->pegawai_id3->EditCustomAttributes = "";
			if (trim(strval($this->pegawai_id3->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`pegawai_id`" . ew_SearchString("=", $this->pegawai_id3->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `pegawai_id`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pegawai`";
			$sWhereWrk = "";
			$this->pegawai_id3->LookupFilters = array("dx1" => '`pegawai_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pegawai_id3, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->pegawai_id3->ViewValue = $this->pegawai_id3->DisplayValue($arwrk);
			} else {
				$this->pegawai_id3->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->pegawai_id3->EditValue = $arwrk;

			// Edit refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// jns_id
			$this->jns_id->LinkCustomAttributes = "";
			$this->jns_id->HrefValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";

			// tgl2
			$this->tgl2->LinkCustomAttributes = "";
			$this->tgl2->HrefValue = "";

			// jam_masuk
			$this->jam_masuk->LinkCustomAttributes = "";
			$this->jam_masuk->HrefValue = "";

			// jam_keluar
			$this->jam_keluar->LinkCustomAttributes = "";
			$this->jam_keluar->HrefValue = "";

			// pegawai_id2
			$this->pegawai_id2->LinkCustomAttributes = "";
			$this->pegawai_id2->HrefValue = "";

			// pegawai_id3
			$this->pegawai_id3->LinkCustomAttributes = "";
			$this->pegawai_id3->HrefValue = "";
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
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!$this->jns_id->FldIsDetailKey && !is_null($this->jns_id->FormValue) && $this->jns_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jns_id->FldCaption(), $this->jns_id->ReqErrMsg));
		}
		if (!$this->tgl->FldIsDetailKey && !is_null($this->tgl->FormValue) && $this->tgl->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl->FldCaption(), $this->tgl->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl->FldErrMsg());
		}
		if (!$this->tgl2->FldIsDetailKey && !is_null($this->tgl2->FormValue) && $this->tgl2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl2->FldCaption(), $this->tgl2->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl2->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl2->FldErrMsg());
		}
		if (!ew_CheckDate($this->jam_masuk->FormValue)) {
			ew_AddMessage($gsFormError, $this->jam_masuk->FldErrMsg());
		}
		if (!ew_CheckDate($this->jam_keluar->FormValue)) {
			ew_AddMessage($gsFormError, $this->jam_keluar->FldErrMsg());
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

			// pegawai_id
			$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, $this->pegawai_id->ReadOnly);

			// jns_id
			$this->jns_id->SetDbValueDef($rsnew, $this->jns_id->CurrentValue, 0, $this->jns_id->ReadOnly);

			// tgl
			$this->tgl->SetDbValueDef($rsnew, $this->tgl->CurrentValue, ew_CurrentDate(), $this->tgl->ReadOnly);

			// tgl2
			$this->tgl2->SetDbValueDef($rsnew, $this->tgl2->CurrentValue, ew_CurrentDate(), $this->tgl2->ReadOnly);

			// jam_masuk
			$this->jam_masuk->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jam_masuk->CurrentValue, 9), NULL, $this->jam_masuk->ReadOnly);

			// jam_keluar
			$this->jam_keluar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jam_keluar->CurrentValue, 9), NULL, $this->jam_keluar->ReadOnly);

			// pegawai_id2
			$this->pegawai_id2->SetDbValueDef($rsnew, $this->pegawai_id2->CurrentValue, NULL, $this->pegawai_id2->ReadOnly);

			// pegawai_id3
			$this->pegawai_id3->SetDbValueDef($rsnew, $this->pegawai_id3->CurrentValue, NULL, $this->pegawai_id3->ReadOnly);

			// Check referential integrity for master table 'pegawai'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_pegawai();
			$KeyValue = isset($rsnew['pegawai_id']) ? $rsnew['pegawai_id'] : $rsold['pegawai_id'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@pegawai_id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["pegawai"])) $GLOBALS["pegawai"] = new cpegawai();
				$rsmaster = $GLOBALS["pegawai"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "pegawai", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pegawai") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_pegawai_id"] <> "") {
					$GLOBALS["pegawai"]->pegawai_id->setQueryStringValue($_GET["fk_pegawai_id"]);
					$this->pegawai_id->setQueryStringValue($GLOBALS["pegawai"]->pegawai_id->QueryStringValue);
					$this->pegawai_id->setSessionValue($this->pegawai_id->QueryStringValue);
					if (!is_numeric($GLOBALS["pegawai"]->pegawai_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pegawai") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_pegawai_id"] <> "") {
					$GLOBALS["pegawai"]->pegawai_id->setFormValue($_POST["fk_pegawai_id"]);
					$this->pegawai_id->setFormValue($GLOBALS["pegawai"]->pegawai_id->FormValue);
					$this->pegawai_id->setSessionValue($this->pegawai_id->FormValue);
					if (!is_numeric($GLOBALS["pegawai"]->pegawai_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "pegawai") {
				if ($this->pegawai_id->CurrentValue == "") $this->pegawai_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_pengecualian_peglist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_pegawai_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `pegawai_id` AS `LinkFld`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
			$sWhereWrk = "{filter}";
			$this->pegawai_id->LookupFilters = array("dx1" => '`pegawai_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`pegawai_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->pegawai_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_jns_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `jns_id` AS `LinkFld`, `kode` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_jns_pengecualian`";
			$sWhereWrk = "{filter}";
			$this->jns_id->LookupFilters = array("dx1" => '`kode`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`jns_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->jns_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_pegawai_id2":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `pegawai_id` AS `LinkFld`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
			$sWhereWrk = "{filter}";
			$this->pegawai_id2->LookupFilters = array("dx1" => '`pegawai_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`pegawai_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->pegawai_id2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_pegawai_id3":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `pegawai_id` AS `LinkFld`, `pegawai_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pegawai`";
			$sWhereWrk = "{filter}";
			$this->pegawai_id3->LookupFilters = array("dx1" => '`pegawai_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`pegawai_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->pegawai_id3, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_pengecualian_peg_edit)) $t_pengecualian_peg_edit = new ct_pengecualian_peg_edit();

// Page init
$t_pengecualian_peg_edit->Page_Init();

// Page main
$t_pengecualian_peg_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pengecualian_peg_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_pengecualian_pegedit = new ew_Form("ft_pengecualian_pegedit", "edit");

// Validate form
ft_pengecualian_pegedit.Validate = function() {
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
ft_pengecualian_pegedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pengecualian_pegedit.ValidateRequired = true;
<?php } else { ?>
ft_pengecualian_pegedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pengecualian_pegedit.Lists["x_pegawai_id"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};
ft_pengecualian_pegedit.Lists["x_jns_id"] = {"LinkField":"x_jns_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_kode","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_jns_pengecualian"};
ft_pengecualian_pegedit.Lists["x_pegawai_id2"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};
ft_pengecualian_pegedit.Lists["x_pegawai_id3"] = {"LinkField":"x_pegawai_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pegawai_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pegawai"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_pengecualian_peg_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_pengecualian_peg_edit->ShowPageHeader(); ?>
<?php
$t_pengecualian_peg_edit->ShowMessage();
?>
<?php if (!$t_pengecualian_peg_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_pengecualian_peg_edit->Pager)) $t_pengecualian_peg_edit->Pager = new cPrevNextPager($t_pengecualian_peg_edit->StartRec, $t_pengecualian_peg_edit->DisplayRecs, $t_pengecualian_peg_edit->TotalRecs) ?>
<?php if ($t_pengecualian_peg_edit->Pager->RecordCount > 0 && $t_pengecualian_peg_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_pengecualian_peg_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_pengecualian_peg_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_pengecualian_pegedit" id="ft_pengecualian_pegedit" class="<?php echo $t_pengecualian_peg_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_pengecualian_peg_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_pengecualian_peg_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_pengecualian_peg">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_pengecualian_peg_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_pengecualian_peg->getCurrentMasterTable() == "pegawai") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pegawai">
<input type="hidden" name="fk_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_pengecualian_peg->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_t_pengecualian_peg_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->pegawai_id->CellAttributes() ?>>
<?php if ($t_pengecualian_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el_t_pengecualian_peg_pegawai_id">
<span<?php echo $t_pengecualian_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pengecualian_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_pegawai_id" name="x_pegawai_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_t_pengecualian_peg_pegawai_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_pegawai_id"><?php echo (strval($t_pengecualian_peg->pegawai_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_pegawai_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id->DisplayValueSeparatorAttribute() ?>" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id->EditAttributes() ?>>
<input type="hidden" name="s_x_pegawai_id" id="s_x_pegawai_id" value="<?php echo $t_pengecualian_peg->pegawai_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $t_pengecualian_peg->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->jns_id->Visible) { // jns_id ?>
	<div id="r_jns_id" class="form-group">
		<label id="elh_t_pengecualian_peg_jns_id" for="x_jns_id" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->jns_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->jns_id->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_jns_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_jns_id"><?php echo (strval($t_pengecualian_peg->jns_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->jns_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->jns_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_jns_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_jns_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->jns_id->DisplayValueSeparatorAttribute() ?>" name="x_jns_id" id="x_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->CurrentValue ?>"<?php echo $t_pengecualian_peg->jns_id->EditAttributes() ?>>
<input type="hidden" name="s_x_jns_id" id="s_x_jns_id" value="<?php echo $t_pengecualian_peg->jns_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_pengecualian_peg->jns_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->tgl->Visible) { // tgl ?>
	<div id="r_tgl" class="form-group">
		<label id="elh_t_pengecualian_peg_tgl" for="x_tgl" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->tgl->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->tgl->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_tgl">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl" name="x_tgl" id="x_tgl" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl->EditValue ?>"<?php echo $t_pengecualian_peg->tgl->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl->ReadOnly && !$t_pengecualian_peg->tgl->Disabled && !isset($t_pengecualian_peg->tgl->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_pegedit", "x_tgl", 0);
</script>
<?php } ?>
</span>
<?php echo $t_pengecualian_peg->tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->tgl2->Visible) { // tgl2 ?>
	<div id="r_tgl2" class="form-group">
		<label id="elh_t_pengecualian_peg_tgl2" for="x_tgl2" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->tgl2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->tgl2->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_tgl2">
<input type="text" data-table="t_pengecualian_peg" data-field="x_tgl2" name="x_tgl2" id="x_tgl2" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->tgl2->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->tgl2->EditValue ?>"<?php echo $t_pengecualian_peg->tgl2->EditAttributes() ?>>
<?php if (!$t_pengecualian_peg->tgl2->ReadOnly && !$t_pengecualian_peg->tgl2->Disabled && !isset($t_pengecualian_peg->tgl2->EditAttrs["readonly"]) && !isset($t_pengecualian_peg->tgl2->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_pengecualian_pegedit", "x_tgl2", 0);
</script>
<?php } ?>
</span>
<?php echo $t_pengecualian_peg->tgl2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->jam_masuk->Visible) { // jam_masuk ?>
	<div id="r_jam_masuk" class="form-group">
		<label id="elh_t_pengecualian_peg_jam_masuk" for="x_jam_masuk" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->jam_masuk->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->jam_masuk->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_jam_masuk">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_masuk" data-format="9" name="x_jam_masuk" id="x_jam_masuk" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_masuk->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_masuk->EditValue ?>"<?php echo $t_pengecualian_peg->jam_masuk->EditAttributes() ?>>
</span>
<?php echo $t_pengecualian_peg->jam_masuk->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->jam_keluar->Visible) { // jam_keluar ?>
	<div id="r_jam_keluar" class="form-group">
		<label id="elh_t_pengecualian_peg_jam_keluar" for="x_jam_keluar" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->jam_keluar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->jam_keluar->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_jam_keluar">
<input type="text" data-table="t_pengecualian_peg" data-field="x_jam_keluar" data-format="9" name="x_jam_keluar" id="x_jam_keluar" placeholder="<?php echo ew_HtmlEncode($t_pengecualian_peg->jam_keluar->getPlaceHolder()) ?>" value="<?php echo $t_pengecualian_peg->jam_keluar->EditValue ?>"<?php echo $t_pengecualian_peg->jam_keluar->EditAttributes() ?>>
</span>
<?php echo $t_pengecualian_peg->jam_keluar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->pegawai_id2->Visible) { // pegawai_id2 ?>
	<div id="r_pegawai_id2" class="form-group">
		<label id="elh_t_pengecualian_peg_pegawai_id2" for="x_pegawai_id2" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->pegawai_id2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->pegawai_id2->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_pegawai_id2">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_pegawai_id2"><?php echo (strval($t_pengecualian_peg->pegawai_id2->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id2->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id2->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_pegawai_id2',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id2->DisplayValueSeparatorAttribute() ?>" name="x_pegawai_id2" id="x_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id2->EditAttributes() ?>>
<input type="hidden" name="s_x_pegawai_id2" id="s_x_pegawai_id2" value="<?php echo $t_pengecualian_peg->pegawai_id2->LookupFilterQuery() ?>">
</span>
<?php echo $t_pengecualian_peg->pegawai_id2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pengecualian_peg->pegawai_id3->Visible) { // pegawai_id3 ?>
	<div id="r_pegawai_id3" class="form-group">
		<label id="elh_t_pengecualian_peg_pegawai_id3" for="x_pegawai_id3" class="col-sm-2 control-label ewLabel"><?php echo $t_pengecualian_peg->pegawai_id3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pengecualian_peg->pegawai_id3->CellAttributes() ?>>
<span id="el_t_pengecualian_peg_pegawai_id3">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_pegawai_id3"><?php echo (strval($t_pengecualian_peg->pegawai_id3->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_pengecualian_peg->pegawai_id3->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_pengecualian_peg->pegawai_id3->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_pegawai_id3',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pegawai_id3" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_pengecualian_peg->pegawai_id3->DisplayValueSeparatorAttribute() ?>" name="x_pegawai_id3" id="x_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->CurrentValue ?>"<?php echo $t_pengecualian_peg->pegawai_id3->EditAttributes() ?>>
<input type="hidden" name="s_x_pegawai_id3" id="s_x_pegawai_id3" value="<?php echo $t_pengecualian_peg->pegawai_id3->LookupFilterQuery() ?>">
</span>
<?php echo $t_pengecualian_peg->pegawai_id3->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_pengecualian_peg" data-field="x_pengecualian_id" name="x_pengecualian_id" id="x_pengecualian_id" value="<?php echo ew_HtmlEncode($t_pengecualian_peg->pengecualian_id->CurrentValue) ?>">
<?php if (!$t_pengecualian_peg_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_pengecualian_peg_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($t_pengecualian_peg_edit->Pager)) $t_pengecualian_peg_edit->Pager = new cPrevNextPager($t_pengecualian_peg_edit->StartRec, $t_pengecualian_peg_edit->DisplayRecs, $t_pengecualian_peg_edit->TotalRecs) ?>
<?php if ($t_pengecualian_peg_edit->Pager->RecordCount > 0 && $t_pengecualian_peg_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_pengecualian_peg_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_pengecualian_peg_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_pengecualian_peg_edit->PageUrl() ?>start=<?php echo $t_pengecualian_peg_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_pengecualian_peg_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_pengecualian_pegedit.Init();
</script>
<?php
$t_pengecualian_peg_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_pengecualian_peg_edit->Page_Terminate();
?>
