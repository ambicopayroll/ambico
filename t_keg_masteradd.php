<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_keg_masterinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "t_keg_detaigridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_keg_master_add = NULL; // Initialize page object first

class ct_keg_master_add extends ct_keg_master {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_keg_master';

	// Page object name
	var $PageObjName = 't_keg_master_add';

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

		// Table object (t_keg_master)
		if (!isset($GLOBALS["t_keg_master"]) || get_class($GLOBALS["t_keg_master"]) == "ct_keg_master") {
			$GLOBALS["t_keg_master"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_keg_master"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_keg_master', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_keg_masterlist.php"));
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
		$this->keg_id->SetVisibility();
		$this->tgl->SetVisibility();
		$this->shift->SetVisibility();
		$this->hasil->SetVisibility();

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

			// Process auto fill for detail table 't_keg_detai'
			if (@$_POST["grid"] == "ft_keg_detaigrid") {
				if (!isset($GLOBALS["t_keg_detai_grid"])) $GLOBALS["t_keg_detai_grid"] = new ct_keg_detai_grid;
				$GLOBALS["t_keg_detai_grid"]->Page_Init();
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
		global $EW_EXPORT, $t_keg_master;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_keg_master);
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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["kegm_id"] != "") {
				$this->kegm_id->setQueryStringValue($_GET["kegm_id"]);
				$this->setKey("kegm_id", $this->kegm_id->CurrentValue); // Set up key
			} else {
				$this->setKey("kegm_id", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("t_keg_masterlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_keg_masterlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_keg_masterview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->keg_id->CurrentValue = NULL;
		$this->keg_id->OldValue = $this->keg_id->CurrentValue;
		$this->tgl->CurrentValue = NULL;
		$this->tgl->OldValue = $this->tgl->CurrentValue;
		$this->shift->CurrentValue = NULL;
		$this->shift->OldValue = $this->shift->CurrentValue;
		$this->hasil->CurrentValue = NULL;
		$this->hasil->OldValue = $this->hasil->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->keg_id->FldIsDetailKey) {
			$this->keg_id->setFormValue($objForm->GetValue("x_keg_id"));
		}
		if (!$this->tgl->FldIsDetailKey) {
			$this->tgl->setFormValue($objForm->GetValue("x_tgl"));
			$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		}
		if (!$this->shift->FldIsDetailKey) {
			$this->shift->setFormValue($objForm->GetValue("x_shift"));
		}
		if (!$this->hasil->FldIsDetailKey) {
			$this->hasil->setFormValue($objForm->GetValue("x_hasil"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->keg_id->CurrentValue = $this->keg_id->FormValue;
		$this->tgl->CurrentValue = $this->tgl->FormValue;
		$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		$this->shift->CurrentValue = $this->shift->FormValue;
		$this->hasil->CurrentValue = $this->hasil->FormValue;
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
		$this->kegm_id->setDbValue($rs->fields('kegm_id'));
		$this->keg_id->setDbValue($rs->fields('keg_id'));
		if (array_key_exists('EV__keg_id', $rs->fields)) {
			$this->keg_id->VirtualValue = $rs->fields('EV__keg_id'); // Set up virtual field value
		} else {
			$this->keg_id->VirtualValue = ""; // Clear value
		}
		$this->tgl->setDbValue($rs->fields('tgl'));
		$this->shift->setDbValue($rs->fields('shift'));
		$this->hasil->setDbValue($rs->fields('hasil'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kegm_id->DbValue = $row['kegm_id'];
		$this->keg_id->DbValue = $row['keg_id'];
		$this->tgl->DbValue = $row['tgl'];
		$this->shift->DbValue = $row['shift'];
		$this->hasil->DbValue = $row['hasil'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("kegm_id")) <> "")
			$this->kegm_id->CurrentValue = $this->getKey("kegm_id"); // kegm_id
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// kegm_id
		// keg_id
		// tgl
		// shift
		// hasil

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// kegm_id
		$this->kegm_id->ViewValue = $this->kegm_id->CurrentValue;
		$this->kegm_id->ViewCustomAttributes = "";

		// keg_id
		if ($this->keg_id->VirtualValue <> "") {
			$this->keg_id->ViewValue = $this->keg_id->VirtualValue;
		} else {
		if (strval($this->keg_id->CurrentValue) <> "") {
			$sFilterWrk = "`keg_id`" . ew_SearchString("=", $this->keg_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `keg_id`, `keg_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_kegiatan`";
		$sWhereWrk = "";
		$this->keg_id->LookupFilters = array("dx1" => '`keg_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->keg_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->keg_id->ViewValue = $this->keg_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->keg_id->ViewValue = $this->keg_id->CurrentValue;
			}
		} else {
			$this->keg_id->ViewValue = NULL;
		}
		}
		$this->keg_id->ViewCustomAttributes = "";

		// tgl
		$this->tgl->ViewValue = $this->tgl->CurrentValue;
		$this->tgl->ViewValue = ew_FormatDateTime($this->tgl->ViewValue, 0);
		$this->tgl->ViewCustomAttributes = "";

		// shift
		if (strval($this->shift->CurrentValue) <> "") {
			$this->shift->ViewValue = $this->shift->OptionCaption($this->shift->CurrentValue);
		} else {
			$this->shift->ViewValue = NULL;
		}
		$this->shift->ViewCustomAttributes = "";

		// hasil
		$this->hasil->ViewValue = $this->hasil->CurrentValue;
		$this->hasil->ViewValue = ew_FormatNumber($this->hasil->ViewValue, 0, -2, -2, -2);
		$this->hasil->CellCssStyle .= "text-align: right;";
		$this->hasil->ViewCustomAttributes = "";

			// keg_id
			$this->keg_id->LinkCustomAttributes = "";
			$this->keg_id->HrefValue = "";
			$this->keg_id->TooltipValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";
			$this->tgl->TooltipValue = "";

			// shift
			$this->shift->LinkCustomAttributes = "";
			$this->shift->HrefValue = "";
			$this->shift->TooltipValue = "";

			// hasil
			$this->hasil->LinkCustomAttributes = "";
			$this->hasil->HrefValue = "";
			$this->hasil->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// keg_id
			$this->keg_id->EditCustomAttributes = "";
			if (trim(strval($this->keg_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`keg_id`" . ew_SearchString("=", $this->keg_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `keg_id`, `keg_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_kegiatan`";
			$sWhereWrk = "";
			$this->keg_id->LookupFilters = array("dx1" => '`keg_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->keg_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->keg_id->ViewValue = $this->keg_id->DisplayValue($arwrk);
			} else {
				$this->keg_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->keg_id->EditValue = $arwrk;

			// tgl
			$this->tgl->EditAttrs["class"] = "form-control";
			$this->tgl->EditCustomAttributes = "";
			$this->tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl->CurrentValue, 8));
			$this->tgl->PlaceHolder = ew_RemoveHtml($this->tgl->FldCaption());

			// shift
			$this->shift->EditCustomAttributes = "";
			$this->shift->EditValue = $this->shift->Options(FALSE);

			// hasil
			$this->hasil->EditAttrs["class"] = "form-control";
			$this->hasil->EditCustomAttributes = "";
			$this->hasil->EditValue = ew_HtmlEncode($this->hasil->CurrentValue);
			$this->hasil->PlaceHolder = ew_RemoveHtml($this->hasil->FldCaption());

			// Add refer script
			// keg_id

			$this->keg_id->LinkCustomAttributes = "";
			$this->keg_id->HrefValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";

			// shift
			$this->shift->LinkCustomAttributes = "";
			$this->shift->HrefValue = "";

			// hasil
			$this->hasil->LinkCustomAttributes = "";
			$this->hasil->HrefValue = "";
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
		if (!$this->keg_id->FldIsDetailKey && !is_null($this->keg_id->FormValue) && $this->keg_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keg_id->FldCaption(), $this->keg_id->ReqErrMsg));
		}
		if (!$this->tgl->FldIsDetailKey && !is_null($this->tgl->FormValue) && $this->tgl->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl->FldCaption(), $this->tgl->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl->FldErrMsg());
		}
		if ($this->shift->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->shift->FldCaption(), $this->shift->ReqErrMsg));
		}
		if (!$this->hasil->FldIsDetailKey && !is_null($this->hasil->FormValue) && $this->hasil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hasil->FldCaption(), $this->hasil->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hasil->FormValue)) {
			ew_AddMessage($gsFormError, $this->hasil->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_keg_detai", $DetailTblVar) && $GLOBALS["t_keg_detai"]->DetailAdd) {
			if (!isset($GLOBALS["t_keg_detai_grid"])) $GLOBALS["t_keg_detai_grid"] = new ct_keg_detai_grid(); // get detail page object
			$GLOBALS["t_keg_detai_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// keg_id
		$this->keg_id->SetDbValueDef($rsnew, $this->keg_id->CurrentValue, 0, FALSE);

		// tgl
		$this->tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// shift
		$this->shift->SetDbValueDef($rsnew, $this->shift->CurrentValue, 0, FALSE);

		// hasil
		$this->hasil->SetDbValueDef($rsnew, $this->hasil->CurrentValue, 0, FALSE);

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("t_keg_detai", $DetailTblVar) && $GLOBALS["t_keg_detai"]->DetailAdd) {
				$GLOBALS["t_keg_detai"]->kegm_id->setSessionValue($this->kegm_id->CurrentValue); // Set master key
				if (!isset($GLOBALS["t_keg_detai_grid"])) $GLOBALS["t_keg_detai_grid"] = new ct_keg_detai_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "t_keg_detai"); // Load user level of detail table
				$AddRow = $GLOBALS["t_keg_detai_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["t_keg_detai"]->kegm_id->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
			if (in_array("t_keg_detai", $DetailTblVar)) {
				if (!isset($GLOBALS["t_keg_detai_grid"]))
					$GLOBALS["t_keg_detai_grid"] = new ct_keg_detai_grid;
				if ($GLOBALS["t_keg_detai_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["t_keg_detai_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["t_keg_detai_grid"]->CurrentMode = "add";
					$GLOBALS["t_keg_detai_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["t_keg_detai_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_keg_detai_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_keg_detai_grid"]->kegm_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_keg_detai_grid"]->kegm_id->CurrentValue = $this->kegm_id->CurrentValue;
					$GLOBALS["t_keg_detai_grid"]->kegm_id->setSessionValue($GLOBALS["t_keg_detai_grid"]->kegm_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_keg_masterlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_keg_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `keg_id` AS `LinkFld`, `keg_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_kegiatan`";
			$sWhereWrk = "{filter}";
			$this->keg_id->LookupFilters = array("dx1" => '`keg_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`keg_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->keg_id, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_keg_master_add)) $t_keg_master_add = new ct_keg_master_add();

// Page init
$t_keg_master_add->Page_Init();

// Page main
$t_keg_master_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_keg_master_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_keg_masteradd = new ew_Form("ft_keg_masteradd", "add");

// Validate form
ft_keg_masteradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_keg_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_keg_master->keg_id->FldCaption(), $t_keg_master->keg_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_keg_master->tgl->FldCaption(), $t_keg_master->tgl->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_keg_master->tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_shift");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_keg_master->shift->FldCaption(), $t_keg_master->shift->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hasil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_keg_master->hasil->FldCaption(), $t_keg_master->hasil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hasil");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_keg_master->hasil->FldErrMsg()) ?>");

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
ft_keg_masteradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_keg_masteradd.ValidateRequired = true;
<?php } else { ?>
ft_keg_masteradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_keg_masteradd.Lists["x_keg_id"] = {"LinkField":"x_keg_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_keg_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_kegiatan"};
ft_keg_masteradd.Lists["x_shift"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_keg_masteradd.Lists["x_shift"].Options = <?php echo json_encode($t_keg_master->shift->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_keg_master_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_keg_master_add->ShowPageHeader(); ?>
<?php
$t_keg_master_add->ShowMessage();
?>
<form name="ft_keg_masteradd" id="ft_keg_masteradd" class="<?php echo $t_keg_master_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_keg_master_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_keg_master_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_keg_master">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_keg_master_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_keg_master->keg_id->Visible) { // keg_id ?>
	<div id="r_keg_id" class="form-group">
		<label id="elh_t_keg_master_keg_id" for="x_keg_id" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_master->keg_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_master->keg_id->CellAttributes() ?>>
<span id="el_t_keg_master_keg_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_keg_id"><?php echo (strval($t_keg_master->keg_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_keg_master->keg_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_keg_master->keg_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_keg_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_keg_master" data-field="x_keg_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_keg_master->keg_id->DisplayValueSeparatorAttribute() ?>" name="x_keg_id" id="x_keg_id" value="<?php echo $t_keg_master->keg_id->CurrentValue ?>"<?php echo $t_keg_master->keg_id->EditAttributes() ?>>
<input type="hidden" name="s_x_keg_id" id="s_x_keg_id" value="<?php echo $t_keg_master->keg_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_keg_master->keg_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_keg_master->tgl->Visible) { // tgl ?>
	<div id="r_tgl" class="form-group">
		<label id="elh_t_keg_master_tgl" for="x_tgl" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_master->tgl->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_master->tgl->CellAttributes() ?>>
<span id="el_t_keg_master_tgl">
<input type="text" data-table="t_keg_master" data-field="x_tgl" name="x_tgl" id="x_tgl" placeholder="<?php echo ew_HtmlEncode($t_keg_master->tgl->getPlaceHolder()) ?>" value="<?php echo $t_keg_master->tgl->EditValue ?>"<?php echo $t_keg_master->tgl->EditAttributes() ?>>
</span>
<?php echo $t_keg_master->tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_keg_master->shift->Visible) { // shift ?>
	<div id="r_shift" class="form-group">
		<label id="elh_t_keg_master_shift" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_master->shift->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_master->shift->CellAttributes() ?>>
<span id="el_t_keg_master_shift">
<div id="tp_x_shift" class="ewTemplate"><input type="radio" data-table="t_keg_master" data-field="x_shift" data-value-separator="<?php echo $t_keg_master->shift->DisplayValueSeparatorAttribute() ?>" name="x_shift" id="x_shift" value="{value}"<?php echo $t_keg_master->shift->EditAttributes() ?>></div>
<div id="dsl_x_shift" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_keg_master->shift->RadioButtonListHtml(FALSE, "x_shift") ?>
</div></div>
</span>
<?php echo $t_keg_master->shift->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_keg_master->hasil->Visible) { // hasil ?>
	<div id="r_hasil" class="form-group">
		<label id="elh_t_keg_master_hasil" for="x_hasil" class="col-sm-2 control-label ewLabel"><?php echo $t_keg_master->hasil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_keg_master->hasil->CellAttributes() ?>>
<span id="el_t_keg_master_hasil">
<input type="text" data-table="t_keg_master" data-field="x_hasil" name="x_hasil" id="x_hasil" size="30" placeholder="<?php echo ew_HtmlEncode($t_keg_master->hasil->getPlaceHolder()) ?>" value="<?php echo $t_keg_master->hasil->EditValue ?>"<?php echo $t_keg_master->hasil->EditAttributes() ?>>
</span>
<?php echo $t_keg_master->hasil->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("t_keg_detai", explode(",", $t_keg_master->getCurrentDetailTable())) && $t_keg_detai->DetailAdd) {
?>
<?php if ($t_keg_master->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("t_keg_detai", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "t_keg_detaigrid.php" ?>
<?php } ?>
<?php if (!$t_keg_master_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_keg_master_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_keg_masteradd.Init();
</script>
<?php
$t_keg_master_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_keg_master_add->Page_Terminate();
?>
