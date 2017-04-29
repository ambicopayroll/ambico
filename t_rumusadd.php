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

$t_rumus_add = NULL; // Initialize page object first

class ct_rumus_add extends ct_rumus {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_rumus';

	// Page object name
	var $PageObjName = 't_rumus_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
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
			if (@$_GET["rumus_id"] != "") {
				$this->rumus_id->setQueryStringValue($_GET["rumus_id"]);
				$this->setKey("rumus_id", $this->rumus_id->CurrentValue); // Set up key
			} else {
				$this->setKey("rumus_id", ""); // Clear key
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
					$this->Page_Terminate("t_rumuslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_rumuslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_rumusview.php")
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
		$this->rumus_nama->CurrentValue = NULL;
		$this->rumus_nama->OldValue = $this->rumus_nama->CurrentValue;
		$this->hk_gol->CurrentValue = 0;
		$this->umr->CurrentValue = 0.00;
		$this->hk_jml->CurrentValue = 0;
		$this->upah->CurrentValue = 0.00;
		$this->premi_hadir->CurrentValue = 0.00;
		$this->premi_malam->CurrentValue = 0.00;
		$this->pot_absen->CurrentValue = 0.00;
		$this->lembur->CurrentValue = 0.00;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->rumus_nama->FldIsDetailKey) {
			$this->rumus_nama->setFormValue($objForm->GetValue("x_rumus_nama"));
		}
		if (!$this->hk_gol->FldIsDetailKey) {
			$this->hk_gol->setFormValue($objForm->GetValue("x_hk_gol"));
		}
		if (!$this->umr->FldIsDetailKey) {
			$this->umr->setFormValue($objForm->GetValue("x_umr"));
		}
		if (!$this->hk_jml->FldIsDetailKey) {
			$this->hk_jml->setFormValue($objForm->GetValue("x_hk_jml"));
		}
		if (!$this->upah->FldIsDetailKey) {
			$this->upah->setFormValue($objForm->GetValue("x_upah"));
		}
		if (!$this->premi_hadir->FldIsDetailKey) {
			$this->premi_hadir->setFormValue($objForm->GetValue("x_premi_hadir"));
		}
		if (!$this->premi_malam->FldIsDetailKey) {
			$this->premi_malam->setFormValue($objForm->GetValue("x_premi_malam"));
		}
		if (!$this->pot_absen->FldIsDetailKey) {
			$this->pot_absen->setFormValue($objForm->GetValue("x_pot_absen"));
		}
		if (!$this->lembur->FldIsDetailKey) {
			$this->lembur->setFormValue($objForm->GetValue("x_lembur"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->rumus_nama->CurrentValue = $this->rumus_nama->FormValue;
		$this->hk_gol->CurrentValue = $this->hk_gol->FormValue;
		$this->umr->CurrentValue = $this->umr->FormValue;
		$this->hk_jml->CurrentValue = $this->hk_jml->FormValue;
		$this->upah->CurrentValue = $this->upah->FormValue;
		$this->premi_hadir->CurrentValue = $this->premi_hadir->FormValue;
		$this->premi_malam->CurrentValue = $this->premi_malam->FormValue;
		$this->pot_absen->CurrentValue = $this->pot_absen->FormValue;
		$this->lembur->CurrentValue = $this->lembur->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("rumus_id")) <> "")
			$this->rumus_id->CurrentValue = $this->getKey("rumus_id"); // rumus_id
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// rumus_nama
			$this->rumus_nama->EditAttrs["class"] = "form-control";
			$this->rumus_nama->EditCustomAttributes = "";
			$this->rumus_nama->EditValue = ew_HtmlEncode($this->rumus_nama->CurrentValue);
			$this->rumus_nama->PlaceHolder = ew_RemoveHtml($this->rumus_nama->FldCaption());

			// hk_gol
			$this->hk_gol->EditCustomAttributes = "";
			$this->hk_gol->EditValue = $this->hk_gol->Options(FALSE);

			// umr
			$this->umr->EditAttrs["class"] = "form-control";
			$this->umr->EditCustomAttributes = "";
			$this->umr->EditValue = ew_HtmlEncode($this->umr->CurrentValue);
			$this->umr->PlaceHolder = ew_RemoveHtml($this->umr->FldCaption());
			if (strval($this->umr->EditValue) <> "" && is_numeric($this->umr->EditValue)) $this->umr->EditValue = ew_FormatNumber($this->umr->EditValue, -2, -2, -2, -2);

			// hk_jml
			$this->hk_jml->EditAttrs["class"] = "form-control";
			$this->hk_jml->EditCustomAttributes = "";
			$this->hk_jml->EditValue = ew_HtmlEncode($this->hk_jml->CurrentValue);
			$this->hk_jml->PlaceHolder = ew_RemoveHtml($this->hk_jml->FldCaption());

			// upah
			$this->upah->EditAttrs["class"] = "form-control";
			$this->upah->EditCustomAttributes = "";
			$this->upah->EditValue = ew_HtmlEncode($this->upah->CurrentValue);
			$this->upah->PlaceHolder = ew_RemoveHtml($this->upah->FldCaption());
			if (strval($this->upah->EditValue) <> "" && is_numeric($this->upah->EditValue)) $this->upah->EditValue = ew_FormatNumber($this->upah->EditValue, -2, -2, -2, -2);

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

			// pot_absen
			$this->pot_absen->EditAttrs["class"] = "form-control";
			$this->pot_absen->EditCustomAttributes = "";
			$this->pot_absen->EditValue = ew_HtmlEncode($this->pot_absen->CurrentValue);
			$this->pot_absen->PlaceHolder = ew_RemoveHtml($this->pot_absen->FldCaption());
			if (strval($this->pot_absen->EditValue) <> "" && is_numeric($this->pot_absen->EditValue)) $this->pot_absen->EditValue = ew_FormatNumber($this->pot_absen->EditValue, -2, -2, -2, -2);

			// lembur
			$this->lembur->EditAttrs["class"] = "form-control";
			$this->lembur->EditCustomAttributes = "";
			$this->lembur->EditValue = ew_HtmlEncode($this->lembur->CurrentValue);
			$this->lembur->PlaceHolder = ew_RemoveHtml($this->lembur->FldCaption());
			if (strval($this->lembur->EditValue) <> "" && is_numeric($this->lembur->EditValue)) $this->lembur->EditValue = ew_FormatNumber($this->lembur->EditValue, -2, -2, -2, -2);

			// Add refer script
			// rumus_nama

			$this->rumus_nama->LinkCustomAttributes = "";
			$this->rumus_nama->HrefValue = "";

			// hk_gol
			$this->hk_gol->LinkCustomAttributes = "";
			$this->hk_gol->HrefValue = "";

			// umr
			$this->umr->LinkCustomAttributes = "";
			$this->umr->HrefValue = "";

			// hk_jml
			$this->hk_jml->LinkCustomAttributes = "";
			$this->hk_jml->HrefValue = "";

			// upah
			$this->upah->LinkCustomAttributes = "";
			$this->upah->HrefValue = "";

			// premi_hadir
			$this->premi_hadir->LinkCustomAttributes = "";
			$this->premi_hadir->HrefValue = "";

			// premi_malam
			$this->premi_malam->LinkCustomAttributes = "";
			$this->premi_malam->HrefValue = "";

			// pot_absen
			$this->pot_absen->LinkCustomAttributes = "";
			$this->pot_absen->HrefValue = "";

			// lembur
			$this->lembur->LinkCustomAttributes = "";
			$this->lembur->HrefValue = "";
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
		if (!$this->rumus_nama->FldIsDetailKey && !is_null($this->rumus_nama->FormValue) && $this->rumus_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rumus_nama->FldCaption(), $this->rumus_nama->ReqErrMsg));
		}
		if ($this->hk_gol->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hk_gol->FldCaption(), $this->hk_gol->ReqErrMsg));
		}
		if (!$this->umr->FldIsDetailKey && !is_null($this->umr->FormValue) && $this->umr->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->umr->FldCaption(), $this->umr->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->umr->FormValue)) {
			ew_AddMessage($gsFormError, $this->umr->FldErrMsg());
		}
		if (!$this->hk_jml->FldIsDetailKey && !is_null($this->hk_jml->FormValue) && $this->hk_jml->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hk_jml->FldCaption(), $this->hk_jml->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hk_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->hk_jml->FldErrMsg());
		}
		if (!$this->upah->FldIsDetailKey && !is_null($this->upah->FormValue) && $this->upah->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->upah->FldCaption(), $this->upah->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->upah->FormValue)) {
			ew_AddMessage($gsFormError, $this->upah->FldErrMsg());
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
		if (!$this->pot_absen->FldIsDetailKey && !is_null($this->pot_absen->FormValue) && $this->pot_absen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pot_absen->FldCaption(), $this->pot_absen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pot_absen->FormValue)) {
			ew_AddMessage($gsFormError, $this->pot_absen->FldErrMsg());
		}
		if (!$this->lembur->FldIsDetailKey && !is_null($this->lembur->FormValue) && $this->lembur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lembur->FldCaption(), $this->lembur->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->lembur->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur->FldErrMsg());
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

		// rumus_nama
		$this->rumus_nama->SetDbValueDef($rsnew, $this->rumus_nama->CurrentValue, "", FALSE);

		// hk_gol
		$this->hk_gol->SetDbValueDef($rsnew, $this->hk_gol->CurrentValue, 0, strval($this->hk_gol->CurrentValue) == "");

		// umr
		$this->umr->SetDbValueDef($rsnew, $this->umr->CurrentValue, 0, strval($this->umr->CurrentValue) == "");

		// hk_jml
		$this->hk_jml->SetDbValueDef($rsnew, $this->hk_jml->CurrentValue, 0, strval($this->hk_jml->CurrentValue) == "");

		// upah
		$this->upah->SetDbValueDef($rsnew, $this->upah->CurrentValue, 0, strval($this->upah->CurrentValue) == "");

		// premi_hadir
		$this->premi_hadir->SetDbValueDef($rsnew, $this->premi_hadir->CurrentValue, 0, strval($this->premi_hadir->CurrentValue) == "");

		// premi_malam
		$this->premi_malam->SetDbValueDef($rsnew, $this->premi_malam->CurrentValue, 0, strval($this->premi_malam->CurrentValue) == "");

		// pot_absen
		$this->pot_absen->SetDbValueDef($rsnew, $this->pot_absen->CurrentValue, 0, strval($this->pot_absen->CurrentValue) == "");

		// lembur
		$this->lembur->SetDbValueDef($rsnew, $this->lembur->CurrentValue, 0, strval($this->lembur->CurrentValue) == "");

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_rumuslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($t_rumus_add)) $t_rumus_add = new ct_rumus_add();

// Page init
$t_rumus_add->Page_Init();

// Page main
$t_rumus_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_rumusadd = new ew_Form("ft_rumusadd", "add");

// Validate form
ft_rumusadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_rumus_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->rumus_nama->FldCaption(), $t_rumus->rumus_nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hk_gol");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->hk_gol->FldCaption(), $t_rumus->hk_gol->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_umr");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->umr->FldCaption(), $t_rumus->umr->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_umr");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->umr->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hk_jml");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->hk_jml->FldCaption(), $t_rumus->hk_jml->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hk_jml");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->hk_jml->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_upah");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->upah->FldCaption(), $t_rumus->upah->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_upah");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->upah->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_premi_hadir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->premi_hadir->FldCaption(), $t_rumus->premi_hadir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_premi_hadir");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->premi_hadir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_premi_malam");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->premi_malam->FldCaption(), $t_rumus->premi_malam->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_premi_malam");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->premi_malam->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pot_absen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->pot_absen->FldCaption(), $t_rumus->pot_absen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pot_absen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->pot_absen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_rumus->lembur->FldCaption(), $t_rumus->lembur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lembur");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_rumus->lembur->FldErrMsg()) ?>");

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
ft_rumusadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumusadd.ValidateRequired = true;
<?php } else { ?>
ft_rumusadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumusadd.Lists["x_hk_gol"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_rumusadd.Lists["x_hk_gol"].Options = <?php echo json_encode($t_rumus->hk_gol->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_rumus_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_rumus_add->ShowPageHeader(); ?>
<?php
$t_rumus_add->ShowMessage();
?>
<form name="ft_rumusadd" id="ft_rumusadd" class="<?php echo $t_rumus_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_rumus_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_rumus_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_rumus">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_rumus_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
	<div id="r_rumus_nama" class="form-group">
		<label id="elh_t_rumus_rumus_nama" for="x_rumus_nama" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->rumus_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->rumus_nama->CellAttributes() ?>>
<span id="el_t_rumus_rumus_nama">
<input type="text" data-table="t_rumus" data-field="x_rumus_nama" name="x_rumus_nama" id="x_rumus_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->getPlaceHolder()) ?>" value="<?php echo $t_rumus->rumus_nama->EditValue ?>"<?php echo $t_rumus->rumus_nama->EditAttributes() ?>>
</span>
<?php echo $t_rumus->rumus_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
	<div id="r_hk_gol" class="form-group">
		<label id="elh_t_rumus_hk_gol" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->hk_gol->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->hk_gol->CellAttributes() ?>>
<span id="el_t_rumus_hk_gol">
<div id="tp_x_hk_gol" class="ewTemplate"><input type="radio" data-table="t_rumus" data-field="x_hk_gol" data-value-separator="<?php echo $t_rumus->hk_gol->DisplayValueSeparatorAttribute() ?>" name="x_hk_gol" id="x_hk_gol" value="{value}"<?php echo $t_rumus->hk_gol->EditAttributes() ?>></div>
<div id="dsl_x_hk_gol" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_rumus->hk_gol->RadioButtonListHtml(FALSE, "x_hk_gol") ?>
</div></div>
</span>
<?php echo $t_rumus->hk_gol->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->umr->Visible) { // umr ?>
	<div id="r_umr" class="form-group">
		<label id="elh_t_rumus_umr" for="x_umr" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->umr->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->umr->CellAttributes() ?>>
<span id="el_t_rumus_umr">
<input type="text" data-table="t_rumus" data-field="x_umr" name="x_umr" id="x_umr" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->umr->getPlaceHolder()) ?>" value="<?php echo $t_rumus->umr->EditValue ?>"<?php echo $t_rumus->umr->EditAttributes() ?>>
</span>
<?php echo $t_rumus->umr->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
	<div id="r_hk_jml" class="form-group">
		<label id="elh_t_rumus_hk_jml" for="x_hk_jml" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->hk_jml->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->hk_jml->CellAttributes() ?>>
<span id="el_t_rumus_hk_jml">
<input type="text" data-table="t_rumus" data-field="x_hk_jml" name="x_hk_jml" id="x_hk_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->hk_jml->getPlaceHolder()) ?>" value="<?php echo $t_rumus->hk_jml->EditValue ?>"<?php echo $t_rumus->hk_jml->EditAttributes() ?>>
</span>
<?php echo $t_rumus->hk_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->upah->Visible) { // upah ?>
	<div id="r_upah" class="form-group">
		<label id="elh_t_rumus_upah" for="x_upah" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->upah->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->upah->CellAttributes() ?>>
<span id="el_t_rumus_upah">
<input type="text" data-table="t_rumus" data-field="x_upah" name="x_upah" id="x_upah" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->upah->getPlaceHolder()) ?>" value="<?php echo $t_rumus->upah->EditValue ?>"<?php echo $t_rumus->upah->EditAttributes() ?>>
</span>
<?php echo $t_rumus->upah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
	<div id="r_premi_hadir" class="form-group">
		<label id="elh_t_rumus_premi_hadir" for="x_premi_hadir" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->premi_hadir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->premi_hadir->CellAttributes() ?>>
<span id="el_t_rumus_premi_hadir">
<input type="text" data-table="t_rumus" data-field="x_premi_hadir" name="x_premi_hadir" id="x_premi_hadir" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_hadir->EditValue ?>"<?php echo $t_rumus->premi_hadir->EditAttributes() ?>>
</span>
<?php echo $t_rumus->premi_hadir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
	<div id="r_premi_malam" class="form-group">
		<label id="elh_t_rumus_premi_malam" for="x_premi_malam" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->premi_malam->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->premi_malam->CellAttributes() ?>>
<span id="el_t_rumus_premi_malam">
<input type="text" data-table="t_rumus" data-field="x_premi_malam" name="x_premi_malam" id="x_premi_malam" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_malam->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_malam->EditValue ?>"<?php echo $t_rumus->premi_malam->EditAttributes() ?>>
</span>
<?php echo $t_rumus->premi_malam->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
	<div id="r_pot_absen" class="form-group">
		<label id="elh_t_rumus_pot_absen" for="x_pot_absen" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->pot_absen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->pot_absen->CellAttributes() ?>>
<span id="el_t_rumus_pot_absen">
<input type="text" data-table="t_rumus" data-field="x_pot_absen" name="x_pot_absen" id="x_pot_absen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->pot_absen->getPlaceHolder()) ?>" value="<?php echo $t_rumus->pot_absen->EditValue ?>"<?php echo $t_rumus->pot_absen->EditAttributes() ?>>
</span>
<?php echo $t_rumus->pot_absen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_rumus->lembur->Visible) { // lembur ?>
	<div id="r_lembur" class="form-group">
		<label id="elh_t_rumus_lembur" for="x_lembur" class="col-sm-2 control-label ewLabel"><?php echo $t_rumus->lembur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_rumus->lembur->CellAttributes() ?>>
<span id="el_t_rumus_lembur">
<input type="text" data-table="t_rumus" data-field="x_lembur" name="x_lembur" id="x_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->lembur->getPlaceHolder()) ?>" value="<?php echo $t_rumus->lembur->EditValue ?>"<?php echo $t_rumus->lembur->EditAttributes() ?>>
</span>
<?php echo $t_rumus->lembur->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_rumus_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_rumus_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_rumusadd.Init();
</script>
<?php
$t_rumus_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_rumus_add->Page_Terminate();
?>
