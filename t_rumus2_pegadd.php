<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_rumus2_peginfo.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_rumus2_peg_add = NULL; // Initialize page object first

class ct_rumus2_peg_add extends ct_rumus2_peg {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_rumus2_peg';

	// Page object name
	var $PageObjName = 't_rumus2_peg_add';

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

		// Table object (t_rumus2_peg)
		if (!isset($GLOBALS["t_rumus2_peg"]) || get_class($GLOBALS["t_rumus2_peg"]) == "ct_rumus2_peg") {
			$GLOBALS["t_rumus2_peg"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_rumus2_peg"];
		}

		// Table object (pegawai)
		if (!isset($GLOBALS['pegawai'])) $GLOBALS['pegawai'] = new cpegawai();

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_rumus2_peg', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_rumus2_peglist.php"));
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
		$this->rumus2_id->SetVisibility();
		$this->gp->SetVisibility();
		$this->tj->SetVisibility();

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
		global $EW_EXPORT, $t_rumus2_peg;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_rumus2_peg);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["rumus2_peg_id"] != "") {
				$this->rumus2_peg_id->setQueryStringValue($_GET["rumus2_peg_id"]);
				$this->setKey("rumus2_peg_id", $this->rumus2_peg_id->CurrentValue); // Set up key
			} else {
				$this->setKey("rumus2_peg_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_rumus2_peglist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_rumus2_peglist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_rumus2_pegview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->pegawai_id->CurrentValue = NULL;
		$this->pegawai_id->OldValue = $this->pegawai_id->CurrentValue;
		$this->rumus2_id->CurrentValue = NULL;
		$this->rumus2_id->OldValue = $this->rumus2_id->CurrentValue;
		$this->gp->CurrentValue = 0.00;
		$this->tj->CurrentValue = 0.00;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->rumus2_id->FldIsDetailKey) {
			$this->rumus2_id->setFormValue($objForm->GetValue("x_rumus2_id"));
		}
		if (!$this->gp->FldIsDetailKey) {
			$this->gp->setFormValue($objForm->GetValue("x_gp"));
		}
		if (!$this->tj->FldIsDetailKey) {
			$this->tj->setFormValue($objForm->GetValue("x_tj"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->rumus2_id->CurrentValue = $this->rumus2_id->FormValue;
		$this->gp->CurrentValue = $this->gp->FormValue;
		$this->tj->CurrentValue = $this->tj->FormValue;
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
		$this->rumus2_peg_id->setDbValue($rs->fields('rumus2_peg_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->rumus2_id->setDbValue($rs->fields('rumus2_id'));
		if (array_key_exists('EV__rumus2_id', $rs->fields)) {
			$this->rumus2_id->VirtualValue = $rs->fields('EV__rumus2_id'); // Set up virtual field value
		} else {
			$this->rumus2_id->VirtualValue = ""; // Clear value
		}
		$this->gp->setDbValue($rs->fields('gp'));
		$this->tj->setDbValue($rs->fields('tj'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->rumus2_peg_id->DbValue = $row['rumus2_peg_id'];
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->rumus2_id->DbValue = $row['rumus2_id'];
		$this->gp->DbValue = $row['gp'];
		$this->tj->DbValue = $row['tj'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("rumus2_peg_id")) <> "")
			$this->rumus2_peg_id->CurrentValue = $this->getKey("rumus2_peg_id"); // rumus2_peg_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->gp->FormValue == $this->gp->CurrentValue && is_numeric(ew_StrToFloat($this->gp->CurrentValue)))
			$this->gp->CurrentValue = ew_StrToFloat($this->gp->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tj->FormValue == $this->tj->CurrentValue && is_numeric(ew_StrToFloat($this->tj->CurrentValue)))
			$this->tj->CurrentValue = ew_StrToFloat($this->tj->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// rumus2_peg_id
		// pegawai_id
		// rumus2_id
		// gp
		// tj

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// rumus2_peg_id
		$this->rumus2_peg_id->ViewValue = $this->rumus2_peg_id->CurrentValue;
		$this->rumus2_peg_id->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// rumus2_id
		if ($this->rumus2_id->VirtualValue <> "") {
			$this->rumus2_id->ViewValue = $this->rumus2_id->VirtualValue;
		} else {
		if (strval($this->rumus2_id->CurrentValue) <> "") {
			$sFilterWrk = "`rumus2_id`" . ew_SearchString("=", $this->rumus2_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `rumus2_id`, `rumus2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_rumus2`";
		$sWhereWrk = "";
		$this->rumus2_id->LookupFilters = array("dx1" => '`rumus2_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rumus2_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->rumus2_id->ViewValue = $this->rumus2_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rumus2_id->ViewValue = $this->rumus2_id->CurrentValue;
			}
		} else {
			$this->rumus2_id->ViewValue = NULL;
		}
		}
		$this->rumus2_id->ViewCustomAttributes = "";

		// gp
		$this->gp->ViewValue = $this->gp->CurrentValue;
		$this->gp->ViewValue = ew_FormatNumber($this->gp->ViewValue, 0, -2, -2, -2);
		$this->gp->CellCssStyle .= "text-align: right;";
		$this->gp->ViewCustomAttributes = "";

		// tj
		$this->tj->ViewValue = $this->tj->CurrentValue;
		$this->tj->ViewValue = ew_FormatNumber($this->tj->ViewValue, 0, -2, -2, -2);
		$this->tj->CellCssStyle .= "text-align: right;";
		$this->tj->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// rumus2_id
			$this->rumus2_id->LinkCustomAttributes = "";
			$this->rumus2_id->HrefValue = "";
			$this->rumus2_id->TooltipValue = "";

			// gp
			$this->gp->LinkCustomAttributes = "";
			$this->gp->HrefValue = "";
			$this->gp->TooltipValue = "";

			// tj
			$this->tj->LinkCustomAttributes = "";
			$this->tj->HrefValue = "";
			$this->tj->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			if ($this->pegawai_id->getSessionValue() <> "") {
				$this->pegawai_id->CurrentValue = $this->pegawai_id->getSessionValue();
			$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
			$this->pegawai_id->ViewCustomAttributes = "";
			} else {
			$this->pegawai_id->EditValue = ew_HtmlEncode($this->pegawai_id->CurrentValue);
			$this->pegawai_id->PlaceHolder = ew_RemoveHtml($this->pegawai_id->FldCaption());
			}

			// rumus2_id
			$this->rumus2_id->EditCustomAttributes = "";
			if (trim(strval($this->rumus2_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`rumus2_id`" . ew_SearchString("=", $this->rumus2_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `rumus2_id`, `rumus2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_rumus2`";
			$sWhereWrk = "";
			$this->rumus2_id->LookupFilters = array("dx1" => '`rumus2_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->rumus2_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->rumus2_id->ViewValue = $this->rumus2_id->DisplayValue($arwrk);
			} else {
				$this->rumus2_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->rumus2_id->EditValue = $arwrk;

			// gp
			$this->gp->EditAttrs["class"] = "form-control";
			$this->gp->EditCustomAttributes = "";
			$this->gp->EditValue = ew_HtmlEncode($this->gp->CurrentValue);
			$this->gp->PlaceHolder = ew_RemoveHtml($this->gp->FldCaption());
			if (strval($this->gp->EditValue) <> "" && is_numeric($this->gp->EditValue)) $this->gp->EditValue = ew_FormatNumber($this->gp->EditValue, -2, -2, -2, -2);

			// tj
			$this->tj->EditAttrs["class"] = "form-control";
			$this->tj->EditCustomAttributes = "";
			$this->tj->EditValue = ew_HtmlEncode($this->tj->CurrentValue);
			$this->tj->PlaceHolder = ew_RemoveHtml($this->tj->FldCaption());
			if (strval($this->tj->EditValue) <> "" && is_numeric($this->tj->EditValue)) $this->tj->EditValue = ew_FormatNumber($this->tj->EditValue, -2, -2, -2, -2);

			// Add refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// rumus2_id
			$this->rumus2_id->LinkCustomAttributes = "";
			$this->rumus2_id->HrefValue = "";

			// gp
			$this->gp->LinkCustomAttributes = "";
			$this->gp->HrefValue = "";

			// tj
			$this->tj->LinkCustomAttributes = "";
			$this->tj->HrefValue = "";
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
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->rumus2_id->FldIsDetailKey && !is_null($this->rumus2_id->FormValue) && $this->rumus2_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rumus2_id->FldCaption(), $this->rumus2_id->ReqErrMsg));
		}
		if (!$this->gp->FldIsDetailKey && !is_null($this->gp->FormValue) && $this->gp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gp->FldCaption(), $this->gp->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->gp->FormValue)) {
			ew_AddMessage($gsFormError, $this->gp->FldErrMsg());
		}
		if (!$this->tj->FldIsDetailKey && !is_null($this->tj->FormValue) && $this->tj->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tj->FldCaption(), $this->tj->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tj->FormValue)) {
			ew_AddMessage($gsFormError, $this->tj->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// pegawai_id
		$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, FALSE);

		// rumus2_id
		$this->rumus2_id->SetDbValueDef($rsnew, $this->rumus2_id->CurrentValue, 0, FALSE);

		// gp
		$this->gp->SetDbValueDef($rsnew, $this->gp->CurrentValue, 0, strval($this->gp->CurrentValue) == "");

		// tj
		$this->tj->SetDbValueDef($rsnew, $this->tj->CurrentValue, 0, strval($this->tj->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_rumus2_peglist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_rumus2_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `rumus2_id` AS `LinkFld`, `rumus2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_rumus2`";
			$sWhereWrk = "{filter}";
			$this->rumus2_id->LookupFilters = array("dx1" => '`rumus2_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`rumus2_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rumus2_id, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_rumus2_peg_add)) $t_rumus2_peg_add = new ct_rumus2_peg_add();

// Page init
$t_rumus2_peg_add->Page_Init();

// Page main
$t_rumus2_peg_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus2_peg_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_rumus2_pegadd = new ew_Form("ft_rumus2_pegadd", "add");

// Validate form
ft_rumus2_pegadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->pegawai_id->FldCaption(), $t_rumus2_peg->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2_peg->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rumus2_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->rumus2_id->FldCaption(), $t_rumus2_peg->rumus2_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->gp->FldCaption(), $t_rumus2_peg->gp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2_peg->gp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tj");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus2_peg->tj->FldCaption(), $t_rumus2_peg->tj->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tj");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus2_peg->tj->FldErrMsg()) ?>");

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
ft_rumus2_pegadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumus2_pegadd.ValidateRequired = true;
<?php } else { ?>
ft_rumus2_pegadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumus2_pegadd.Lists["x_rumus2_id"] = {"LinkField":"x_rumus2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rumus2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_rumus2"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_rumus2_peg_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_rumus2_peg_add->ShowPageHeader(); ?>
<?php
$t_rumus2_peg_add->ShowMessage();
?>
<form name="ft_rumus2_pegadd" id="ft_rumus2_pegadd" class="<?php echo $t_rumus2_peg_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_rumus2_peg_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_rumus2_peg_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_rumus2_peg">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_rumus2_peg_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_rumus2_peg->getCurrentMasterTable() == "pegawai") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pegawai">
<input type="hidden" name="fk_pegawai_id" value="<?php echo $t_rumus2_peg->pegawai_id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_rumus2_peg->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_t_rumus2_peg_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2_peg->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2_peg->pegawai_id->CellAttributes() ?>>
<?php if ($t_rumus2_peg->pegawai_id->getSessionValue() <> "") { ?>
<span id="el_t_rumus2_peg_pegawai_id">
<span<?php echo $t_rumus2_peg->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_rumus2_peg->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_pegawai_id" name="x_pegawai_id" value="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_t_rumus2_peg_pegawai_id">
<input type="text" data-table="t_rumus2_peg" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->pegawai_id->EditValue ?>"<?php echo $t_rumus2_peg->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $t_rumus2_peg->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2_peg->rumus2_id->Visible) { // rumus2_id ?>
	<div id="r_rumus2_id" class="form-group">
		<label id="elh_t_rumus2_peg_rumus2_id" for="x_rumus2_id" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2_peg->rumus2_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2_peg->rumus2_id->CellAttributes() ?>>
<span id="el_t_rumus2_peg_rumus2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_rumus2_id"><?php echo (strval($t_rumus2_peg->rumus2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_rumus2_peg->rumus2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_rumus2_peg->rumus2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_rumus2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_rumus2_peg" data-field="x_rumus2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_rumus2_peg->rumus2_id->DisplayValueSeparatorAttribute() ?>" name="x_rumus2_id" id="x_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->CurrentValue ?>"<?php echo $t_rumus2_peg->rumus2_id->EditAttributes() ?>>
<input type="hidden" name="s_x_rumus2_id" id="s_x_rumus2_id" value="<?php echo $t_rumus2_peg->rumus2_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_rumus2_peg->rumus2_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2_peg->gp->Visible) { // gp ?>
	<div id="r_gp" class="form-group">
		<label id="elh_t_rumus2_peg_gp" for="x_gp" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2_peg->gp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2_peg->gp->CellAttributes() ?>>
<span id="el_t_rumus2_peg_gp">
<input type="text" data-table="t_rumus2_peg" data-field="x_gp" name="x_gp" id="x_gp" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->gp->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->gp->EditValue ?>"<?php echo $t_rumus2_peg->gp->EditAttributes() ?>>
</span>
<?php echo $t_rumus2_peg->gp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus2_peg->tj->Visible) { // tj ?>
	<div id="r_tj" class="form-group">
		<label id="elh_t_rumus2_peg_tj" for="x_tj" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus2_peg->tj->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus2_peg->tj->CellAttributes() ?>>
<span id="el_t_rumus2_peg_tj">
<input type="text" data-table="t_rumus2_peg" data-field="x_tj" name="x_tj" id="x_tj" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus2_peg->tj->getPlaceHolder()) ?>" value="<?php echo $t_rumus2_peg->tj->EditValue ?>"<?php echo $t_rumus2_peg->tj->EditAttributes() ?>>
</span>
<?php echo $t_rumus2_peg->tj->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_rumus2_peg_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_rumus2_peg_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_rumus2_pegadd.Init();
</script>
<?php
$t_rumus2_peg_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_rumus2_peg_add->Page_Terminate();
?>
