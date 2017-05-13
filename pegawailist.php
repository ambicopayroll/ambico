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

$pegawai_list = NULL; // Initialize page object first

class cpegawai_list extends cpegawai {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 'pegawai';

	// Page object name
	var $PageObjName = 'pegawai_list';

	// Grid form hidden field names
	var $FormName = 'fpegawailist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pegawaiadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pegawaidelete.php";
		$this->MultiUpdateUrl = "pegawaiupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fpegawailistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->pegawai_pin->SetVisibility();
		$this->pegawai_nip->SetVisibility();
		$this->pegawai_nama->SetVisibility();
		$this->pembagian1_id->SetVisibility();
		$this->pembagian2_id->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("pegawai_id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["pegawai_id"] <> "") {
			$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("pegawai_id", $this->pegawai_id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("pegawai_id")) <> strval($this->pegawai_id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["pegawai_id"] <> "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->setKey("pegawai_id", $this->pegawai_id->CurrentValue); // Set up key
			} else {
				$this->setKey("pegawai_id", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->pegawai_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->pegawai_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->pegawai_id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_pegawai_pin") && $objForm->HasValue("o_pegawai_pin") && $this->pegawai_pin->CurrentValue <> $this->pegawai_pin->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pegawai_nip") && $objForm->HasValue("o_pegawai_nip") && $this->pegawai_nip->CurrentValue <> $this->pegawai_nip->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pegawai_nama") && $objForm->HasValue("o_pegawai_nama") && $this->pegawai_nama->CurrentValue <> $this->pegawai_nama->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pembagian1_id") && $objForm->HasValue("o_pembagian1_id") && $this->pembagian1_id->CurrentValue <> $this->pembagian1_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pembagian2_id") && $objForm->HasValue("o_pembagian2_id") && $this->pembagian2_id->CurrentValue <> $this->pembagian2_id->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fpegawailistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_id->AdvancedSearch->ToJSON(), ","); // Field pegawai_id
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_pin->AdvancedSearch->ToJSON(), ","); // Field pegawai_pin
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_nip->AdvancedSearch->ToJSON(), ","); // Field pegawai_nip
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_nama->AdvancedSearch->ToJSON(), ","); // Field pegawai_nama
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_pwd->AdvancedSearch->ToJSON(), ","); // Field pegawai_pwd
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_rfid->AdvancedSearch->ToJSON(), ","); // Field pegawai_rfid
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_privilege->AdvancedSearch->ToJSON(), ","); // Field pegawai_privilege
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_telp->AdvancedSearch->ToJSON(), ","); // Field pegawai_telp
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_status->AdvancedSearch->ToJSON(), ","); // Field pegawai_status
		$sFilterList = ew_Concat($sFilterList, $this->tempat_lahir->AdvancedSearch->ToJSON(), ","); // Field tempat_lahir
		$sFilterList = ew_Concat($sFilterList, $this->tgl_lahir->AdvancedSearch->ToJSON(), ","); // Field tgl_lahir
		$sFilterList = ew_Concat($sFilterList, $this->pembagian1_id->AdvancedSearch->ToJSON(), ","); // Field pembagian1_id
		$sFilterList = ew_Concat($sFilterList, $this->pembagian2_id->AdvancedSearch->ToJSON(), ","); // Field pembagian2_id
		$sFilterList = ew_Concat($sFilterList, $this->tgl_mulai_kerja->AdvancedSearch->ToJSON(), ","); // Field tgl_mulai_kerja
		$sFilterList = ew_Concat($sFilterList, $this->tgl_resign->AdvancedSearch->ToJSON(), ","); // Field tgl_resign
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJSON(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->tgl_masuk_pertama->AdvancedSearch->ToJSON(), ","); // Field tgl_masuk_pertama
		$sFilterList = ew_Concat($sFilterList, $this->photo_path->AdvancedSearch->ToJSON(), ","); // Field photo_path
		$sFilterList = ew_Concat($sFilterList, $this->tmp_img->AdvancedSearch->ToJSON(), ","); // Field tmp_img
		$sFilterList = ew_Concat($sFilterList, $this->nama_bank->AdvancedSearch->ToJSON(), ","); // Field nama_bank
		$sFilterList = ew_Concat($sFilterList, $this->nama_rek->AdvancedSearch->ToJSON(), ","); // Field nama_rek
		$sFilterList = ew_Concat($sFilterList, $this->no_rek->AdvancedSearch->ToJSON(), ","); // Field no_rek
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fpegawailistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field pegawai_id
		$this->pegawai_id->AdvancedSearch->SearchValue = @$filter["x_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchOperator = @$filter["z_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchCondition = @$filter["v_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->Save();

		// Field pegawai_pin
		$this->pegawai_pin->AdvancedSearch->SearchValue = @$filter["x_pegawai_pin"];
		$this->pegawai_pin->AdvancedSearch->SearchOperator = @$filter["z_pegawai_pin"];
		$this->pegawai_pin->AdvancedSearch->SearchCondition = @$filter["v_pegawai_pin"];
		$this->pegawai_pin->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_pin"];
		$this->pegawai_pin->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_pin"];
		$this->pegawai_pin->AdvancedSearch->Save();

		// Field pegawai_nip
		$this->pegawai_nip->AdvancedSearch->SearchValue = @$filter["x_pegawai_nip"];
		$this->pegawai_nip->AdvancedSearch->SearchOperator = @$filter["z_pegawai_nip"];
		$this->pegawai_nip->AdvancedSearch->SearchCondition = @$filter["v_pegawai_nip"];
		$this->pegawai_nip->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_nip"];
		$this->pegawai_nip->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_nip"];
		$this->pegawai_nip->AdvancedSearch->Save();

		// Field pegawai_nama
		$this->pegawai_nama->AdvancedSearch->SearchValue = @$filter["x_pegawai_nama"];
		$this->pegawai_nama->AdvancedSearch->SearchOperator = @$filter["z_pegawai_nama"];
		$this->pegawai_nama->AdvancedSearch->SearchCondition = @$filter["v_pegawai_nama"];
		$this->pegawai_nama->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_nama"];
		$this->pegawai_nama->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_nama"];
		$this->pegawai_nama->AdvancedSearch->Save();

		// Field pegawai_pwd
		$this->pegawai_pwd->AdvancedSearch->SearchValue = @$filter["x_pegawai_pwd"];
		$this->pegawai_pwd->AdvancedSearch->SearchOperator = @$filter["z_pegawai_pwd"];
		$this->pegawai_pwd->AdvancedSearch->SearchCondition = @$filter["v_pegawai_pwd"];
		$this->pegawai_pwd->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_pwd"];
		$this->pegawai_pwd->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_pwd"];
		$this->pegawai_pwd->AdvancedSearch->Save();

		// Field pegawai_rfid
		$this->pegawai_rfid->AdvancedSearch->SearchValue = @$filter["x_pegawai_rfid"];
		$this->pegawai_rfid->AdvancedSearch->SearchOperator = @$filter["z_pegawai_rfid"];
		$this->pegawai_rfid->AdvancedSearch->SearchCondition = @$filter["v_pegawai_rfid"];
		$this->pegawai_rfid->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_rfid"];
		$this->pegawai_rfid->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_rfid"];
		$this->pegawai_rfid->AdvancedSearch->Save();

		// Field pegawai_privilege
		$this->pegawai_privilege->AdvancedSearch->SearchValue = @$filter["x_pegawai_privilege"];
		$this->pegawai_privilege->AdvancedSearch->SearchOperator = @$filter["z_pegawai_privilege"];
		$this->pegawai_privilege->AdvancedSearch->SearchCondition = @$filter["v_pegawai_privilege"];
		$this->pegawai_privilege->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_privilege"];
		$this->pegawai_privilege->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_privilege"];
		$this->pegawai_privilege->AdvancedSearch->Save();

		// Field pegawai_telp
		$this->pegawai_telp->AdvancedSearch->SearchValue = @$filter["x_pegawai_telp"];
		$this->pegawai_telp->AdvancedSearch->SearchOperator = @$filter["z_pegawai_telp"];
		$this->pegawai_telp->AdvancedSearch->SearchCondition = @$filter["v_pegawai_telp"];
		$this->pegawai_telp->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_telp"];
		$this->pegawai_telp->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_telp"];
		$this->pegawai_telp->AdvancedSearch->Save();

		// Field pegawai_status
		$this->pegawai_status->AdvancedSearch->SearchValue = @$filter["x_pegawai_status"];
		$this->pegawai_status->AdvancedSearch->SearchOperator = @$filter["z_pegawai_status"];
		$this->pegawai_status->AdvancedSearch->SearchCondition = @$filter["v_pegawai_status"];
		$this->pegawai_status->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_status"];
		$this->pegawai_status->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_status"];
		$this->pegawai_status->AdvancedSearch->Save();

		// Field tempat_lahir
		$this->tempat_lahir->AdvancedSearch->SearchValue = @$filter["x_tempat_lahir"];
		$this->tempat_lahir->AdvancedSearch->SearchOperator = @$filter["z_tempat_lahir"];
		$this->tempat_lahir->AdvancedSearch->SearchCondition = @$filter["v_tempat_lahir"];
		$this->tempat_lahir->AdvancedSearch->SearchValue2 = @$filter["y_tempat_lahir"];
		$this->tempat_lahir->AdvancedSearch->SearchOperator2 = @$filter["w_tempat_lahir"];
		$this->tempat_lahir->AdvancedSearch->Save();

		// Field tgl_lahir
		$this->tgl_lahir->AdvancedSearch->SearchValue = @$filter["x_tgl_lahir"];
		$this->tgl_lahir->AdvancedSearch->SearchOperator = @$filter["z_tgl_lahir"];
		$this->tgl_lahir->AdvancedSearch->SearchCondition = @$filter["v_tgl_lahir"];
		$this->tgl_lahir->AdvancedSearch->SearchValue2 = @$filter["y_tgl_lahir"];
		$this->tgl_lahir->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_lahir"];
		$this->tgl_lahir->AdvancedSearch->Save();

		// Field pembagian1_id
		$this->pembagian1_id->AdvancedSearch->SearchValue = @$filter["x_pembagian1_id"];
		$this->pembagian1_id->AdvancedSearch->SearchOperator = @$filter["z_pembagian1_id"];
		$this->pembagian1_id->AdvancedSearch->SearchCondition = @$filter["v_pembagian1_id"];
		$this->pembagian1_id->AdvancedSearch->SearchValue2 = @$filter["y_pembagian1_id"];
		$this->pembagian1_id->AdvancedSearch->SearchOperator2 = @$filter["w_pembagian1_id"];
		$this->pembagian1_id->AdvancedSearch->Save();

		// Field pembagian2_id
		$this->pembagian2_id->AdvancedSearch->SearchValue = @$filter["x_pembagian2_id"];
		$this->pembagian2_id->AdvancedSearch->SearchOperator = @$filter["z_pembagian2_id"];
		$this->pembagian2_id->AdvancedSearch->SearchCondition = @$filter["v_pembagian2_id"];
		$this->pembagian2_id->AdvancedSearch->SearchValue2 = @$filter["y_pembagian2_id"];
		$this->pembagian2_id->AdvancedSearch->SearchOperator2 = @$filter["w_pembagian2_id"];
		$this->pembagian2_id->AdvancedSearch->Save();

		// Field tgl_mulai_kerja
		$this->tgl_mulai_kerja->AdvancedSearch->SearchValue = @$filter["x_tgl_mulai_kerja"];
		$this->tgl_mulai_kerja->AdvancedSearch->SearchOperator = @$filter["z_tgl_mulai_kerja"];
		$this->tgl_mulai_kerja->AdvancedSearch->SearchCondition = @$filter["v_tgl_mulai_kerja"];
		$this->tgl_mulai_kerja->AdvancedSearch->SearchValue2 = @$filter["y_tgl_mulai_kerja"];
		$this->tgl_mulai_kerja->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_mulai_kerja"];
		$this->tgl_mulai_kerja->AdvancedSearch->Save();

		// Field tgl_resign
		$this->tgl_resign->AdvancedSearch->SearchValue = @$filter["x_tgl_resign"];
		$this->tgl_resign->AdvancedSearch->SearchOperator = @$filter["z_tgl_resign"];
		$this->tgl_resign->AdvancedSearch->SearchCondition = @$filter["v_tgl_resign"];
		$this->tgl_resign->AdvancedSearch->SearchValue2 = @$filter["y_tgl_resign"];
		$this->tgl_resign->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_resign"];
		$this->tgl_resign->AdvancedSearch->Save();

		// Field gender
		$this->gender->AdvancedSearch->SearchValue = @$filter["x_gender"];
		$this->gender->AdvancedSearch->SearchOperator = @$filter["z_gender"];
		$this->gender->AdvancedSearch->SearchCondition = @$filter["v_gender"];
		$this->gender->AdvancedSearch->SearchValue2 = @$filter["y_gender"];
		$this->gender->AdvancedSearch->SearchOperator2 = @$filter["w_gender"];
		$this->gender->AdvancedSearch->Save();

		// Field tgl_masuk_pertama
		$this->tgl_masuk_pertama->AdvancedSearch->SearchValue = @$filter["x_tgl_masuk_pertama"];
		$this->tgl_masuk_pertama->AdvancedSearch->SearchOperator = @$filter["z_tgl_masuk_pertama"];
		$this->tgl_masuk_pertama->AdvancedSearch->SearchCondition = @$filter["v_tgl_masuk_pertama"];
		$this->tgl_masuk_pertama->AdvancedSearch->SearchValue2 = @$filter["y_tgl_masuk_pertama"];
		$this->tgl_masuk_pertama->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_masuk_pertama"];
		$this->tgl_masuk_pertama->AdvancedSearch->Save();

		// Field photo_path
		$this->photo_path->AdvancedSearch->SearchValue = @$filter["x_photo_path"];
		$this->photo_path->AdvancedSearch->SearchOperator = @$filter["z_photo_path"];
		$this->photo_path->AdvancedSearch->SearchCondition = @$filter["v_photo_path"];
		$this->photo_path->AdvancedSearch->SearchValue2 = @$filter["y_photo_path"];
		$this->photo_path->AdvancedSearch->SearchOperator2 = @$filter["w_photo_path"];
		$this->photo_path->AdvancedSearch->Save();

		// Field tmp_img
		$this->tmp_img->AdvancedSearch->SearchValue = @$filter["x_tmp_img"];
		$this->tmp_img->AdvancedSearch->SearchOperator = @$filter["z_tmp_img"];
		$this->tmp_img->AdvancedSearch->SearchCondition = @$filter["v_tmp_img"];
		$this->tmp_img->AdvancedSearch->SearchValue2 = @$filter["y_tmp_img"];
		$this->tmp_img->AdvancedSearch->SearchOperator2 = @$filter["w_tmp_img"];
		$this->tmp_img->AdvancedSearch->Save();

		// Field nama_bank
		$this->nama_bank->AdvancedSearch->SearchValue = @$filter["x_nama_bank"];
		$this->nama_bank->AdvancedSearch->SearchOperator = @$filter["z_nama_bank"];
		$this->nama_bank->AdvancedSearch->SearchCondition = @$filter["v_nama_bank"];
		$this->nama_bank->AdvancedSearch->SearchValue2 = @$filter["y_nama_bank"];
		$this->nama_bank->AdvancedSearch->SearchOperator2 = @$filter["w_nama_bank"];
		$this->nama_bank->AdvancedSearch->Save();

		// Field nama_rek
		$this->nama_rek->AdvancedSearch->SearchValue = @$filter["x_nama_rek"];
		$this->nama_rek->AdvancedSearch->SearchOperator = @$filter["z_nama_rek"];
		$this->nama_rek->AdvancedSearch->SearchCondition = @$filter["v_nama_rek"];
		$this->nama_rek->AdvancedSearch->SearchValue2 = @$filter["y_nama_rek"];
		$this->nama_rek->AdvancedSearch->SearchOperator2 = @$filter["w_nama_rek"];
		$this->nama_rek->AdvancedSearch->Save();

		// Field no_rek
		$this->no_rek->AdvancedSearch->SearchValue = @$filter["x_no_rek"];
		$this->no_rek->AdvancedSearch->SearchOperator = @$filter["z_no_rek"];
		$this->no_rek->AdvancedSearch->SearchCondition = @$filter["v_no_rek"];
		$this->no_rek->AdvancedSearch->SearchValue2 = @$filter["y_no_rek"];
		$this->no_rek->AdvancedSearch->SearchOperator2 = @$filter["w_no_rek"];
		$this->no_rek->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_pin, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_nip, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_nama, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_pwd, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_rfid, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_privilege, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pegawai_telp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tempat_lahir, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->photo_path, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tmp_img, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nama_bank, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nama_rek, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->no_rek, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->pegawai_pin, $bCtrl); // pegawai_pin
			$this->UpdateSort($this->pegawai_nip, $bCtrl); // pegawai_nip
			$this->UpdateSort($this->pegawai_nama, $bCtrl); // pegawai_nama
			$this->UpdateSort($this->pembagian1_id, $bCtrl); // pembagian1_id
			$this->UpdateSort($this->pembagian2_id, $bCtrl); // pembagian2_id
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->pegawai_pin->setSort("");
				$this->pegawai_nip->setSort("");
				$this->pegawai_nama->setSort("");
				$this->pembagian1_id->setSort("");
				$this->pembagian2_id->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "detail_t_jdw_krj_peg"
		$item = &$this->ListOptions->Add("detail_t_jdw_krj_peg");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_jdw_krj_peg') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_jdw_krj_peg_grid"])) $GLOBALS["t_jdw_krj_peg_grid"] = new ct_jdw_krj_peg_grid;

		// "detail_t_rumus2_peg"
		$item = &$this->ListOptions->Add("detail_t_rumus2_peg");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_rumus2_peg') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_rumus2_peg_grid"])) $GLOBALS["t_rumus2_peg_grid"] = new ct_rumus2_peg_grid;

		// "detail_t_rumus_peg"
		$item = &$this->ListOptions->Add("detail_t_rumus_peg");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_rumus_peg') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_rumus_peg_grid"])) $GLOBALS["t_rumus_peg_grid"] = new ct_rumus_peg_grid;

		// "detail_t_pengecualian_peg"
		$item = &$this->ListOptions->Add("detail_t_pengecualian_peg");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_pengecualian_peg') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_pengecualian_peg_grid"])) $GLOBALS["t_pengecualian_peg_grid"] = new ct_pengecualian_peg_grid;

		// "detail_t_lembur"
		$item = &$this->ListOptions->Add("detail_t_lembur");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_lembur') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_lembur_grid"])) $GLOBALS["t_lembur_grid"] = new ct_lembur_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("t_jdw_krj_peg");
		$pages->Add("t_rumus2_peg");
		$pages->Add("t_rumus_peg");
		$pages->Add("t_pengecualian_peg");
		$pages->Add("t_lembur");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->pegawai_id->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_t_jdw_krj_peg"
		$oListOpt = &$this->ListOptions->Items["detail_t_jdw_krj_peg"];
		if ($Security->AllowList(CurrentProjectID() . 't_jdw_krj_peg')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_jdw_krj_peg", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_jdw_krj_peglist.php?" . EW_TABLE_SHOW_MASTER . "=pegawai&fk_pegawai_id=" . urlencode(strval($this->pegawai_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_jdw_krj_peg_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_jdw_krj_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_jdw_krj_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_jdw_krj_peg";
			}
			if ($GLOBALS["t_jdw_krj_peg_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_jdw_krj_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_jdw_krj_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_jdw_krj_peg";
			}
			if ($GLOBALS["t_jdw_krj_peg_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_jdw_krj_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_jdw_krj_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "t_jdw_krj_peg";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_rumus2_peg"
		$oListOpt = &$this->ListOptions->Items["detail_t_rumus2_peg"];
		if ($Security->AllowList(CurrentProjectID() . 't_rumus2_peg')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_rumus2_peg", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_rumus2_peglist.php?" . EW_TABLE_SHOW_MASTER . "=pegawai&fk_pegawai_id=" . urlencode(strval($this->pegawai_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_rumus2_peg_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_rumus2_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus2_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_rumus2_peg";
			}
			if ($GLOBALS["t_rumus2_peg_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_rumus2_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus2_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_rumus2_peg";
			}
			if ($GLOBALS["t_rumus2_peg_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_rumus2_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus2_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "t_rumus2_peg";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_rumus_peg"
		$oListOpt = &$this->ListOptions->Items["detail_t_rumus_peg"];
		if ($Security->AllowList(CurrentProjectID() . 't_rumus_peg')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_rumus_peg", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_rumus_peglist.php?" . EW_TABLE_SHOW_MASTER . "=pegawai&fk_pegawai_id=" . urlencode(strval($this->pegawai_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_rumus_peg_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_rumus_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_rumus_peg";
			}
			if ($GLOBALS["t_rumus_peg_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_rumus_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_rumus_peg";
			}
			if ($GLOBALS["t_rumus_peg_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_rumus_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "t_rumus_peg";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_pengecualian_peg"
		$oListOpt = &$this->ListOptions->Items["detail_t_pengecualian_peg"];
		if ($Security->AllowList(CurrentProjectID() . 't_pengecualian_peg')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_pengecualian_peg", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_pengecualian_peglist.php?" . EW_TABLE_SHOW_MASTER . "=pegawai&fk_pegawai_id=" . urlencode(strval($this->pegawai_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_pengecualian_peg_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_pengecualian_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_pengecualian_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_pengecualian_peg";
			}
			if ($GLOBALS["t_pengecualian_peg_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_pengecualian_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_pengecualian_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_pengecualian_peg";
			}
			if ($GLOBALS["t_pengecualian_peg_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_pengecualian_peg')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_pengecualian_peg")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "t_pengecualian_peg";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_lembur"
		$oListOpt = &$this->ListOptions->Items["detail_t_lembur"];
		if ($Security->AllowList(CurrentProjectID() . 't_lembur')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_lembur", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_lemburlist.php?" . EW_TABLE_SHOW_MASTER . "=pegawai&fk_pegawai_id=" . urlencode(strval($this->pegawai_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_lembur_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_lembur')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_lembur")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_lembur";
			}
			if ($GLOBALS["t_lembur_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_lembur')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_lembur")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_lembur";
			}
			if ($GLOBALS["t_lembur_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_lembur')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_lembur")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "t_lembur";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->pegawai_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->pegawai_id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_t_jdw_krj_peg");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=t_jdw_krj_peg");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["t_jdw_krj_peg"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["t_jdw_krj_peg"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 't_jdw_krj_peg') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_jdw_krj_peg";
		}
		$item = &$option->Add("detailadd_t_rumus2_peg");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus2_peg");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["t_rumus2_peg"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["t_rumus2_peg"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 't_rumus2_peg') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_rumus2_peg";
		}
		$item = &$option->Add("detailadd_t_rumus_peg");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=t_rumus_peg");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["t_rumus_peg"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["t_rumus_peg"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 't_rumus_peg') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_rumus_peg";
		}
		$item = &$option->Add("detailadd_t_pengecualian_peg");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=t_pengecualian_peg");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["t_pengecualian_peg"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["t_pengecualian_peg"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 't_pengecualian_peg') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_pengecualian_peg";
		}
		$item = &$option->Add("detailadd_t_lembur");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=t_lembur");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["t_lembur"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["t_lembur"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 't_lembur') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_lembur";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fpegawailist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpegawailistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpegawailistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpegawailist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpegawailistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->pegawai_pin->CurrentValue = NULL;
		$this->pegawai_pin->OldValue = $this->pegawai_pin->CurrentValue;
		$this->pegawai_nip->CurrentValue = NULL;
		$this->pegawai_nip->OldValue = $this->pegawai_nip->CurrentValue;
		$this->pegawai_nama->CurrentValue = NULL;
		$this->pegawai_nama->OldValue = $this->pegawai_nama->CurrentValue;
		$this->pembagian1_id->CurrentValue = 0;
		$this->pembagian1_id->OldValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian2_id->CurrentValue = 0;
		$this->pembagian2_id->OldValue = $this->pembagian2_id->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_pin->FldIsDetailKey) {
			$this->pegawai_pin->setFormValue($objForm->GetValue("x_pegawai_pin"));
		}
		$this->pegawai_pin->setOldValue($objForm->GetValue("o_pegawai_pin"));
		if (!$this->pegawai_nip->FldIsDetailKey) {
			$this->pegawai_nip->setFormValue($objForm->GetValue("x_pegawai_nip"));
		}
		$this->pegawai_nip->setOldValue($objForm->GetValue("o_pegawai_nip"));
		if (!$this->pegawai_nama->FldIsDetailKey) {
			$this->pegawai_nama->setFormValue($objForm->GetValue("x_pegawai_nama"));
		}
		$this->pegawai_nama->setOldValue($objForm->GetValue("o_pegawai_nama"));
		if (!$this->pembagian1_id->FldIsDetailKey) {
			$this->pembagian1_id->setFormValue($objForm->GetValue("x_pembagian1_id"));
		}
		$this->pembagian1_id->setOldValue($objForm->GetValue("o_pembagian1_id"));
		if (!$this->pembagian2_id->FldIsDetailKey) {
			$this->pembagian2_id->setFormValue($objForm->GetValue("x_pembagian2_id"));
		}
		$this->pembagian2_id->setOldValue($objForm->GetValue("o_pembagian2_id"));
		if (!$this->pegawai_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Add refer script
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
				$sThisKey .= $row['pegawai_id'];
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
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		if ($this->pegawai_pin->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(pegawai_pin = '" . ew_AdjustSql($this->pegawai_pin->CurrentValue, $this->DBID) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->pegawai_pin->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->pegawai_pin->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// pegawai_pin
		$this->pegawai_pin->SetDbValueDef($rsnew, $this->pegawai_pin->CurrentValue, "", FALSE);

		// pegawai_nip
		$this->pegawai_nip->SetDbValueDef($rsnew, $this->pegawai_nip->CurrentValue, NULL, FALSE);

		// pegawai_nama
		$this->pegawai_nama->SetDbValueDef($rsnew, $this->pegawai_nama->CurrentValue, "", FALSE);

		// pembagian1_id
		$this->pembagian1_id->SetDbValueDef($rsnew, $this->pembagian1_id->CurrentValue, NULL, strval($this->pembagian1_id->CurrentValue) == "");

		// pembagian2_id
		$this->pembagian2_id->SetDbValueDef($rsnew, $this->pembagian2_id->CurrentValue, NULL, strval($this->pembagian2_id->CurrentValue) == "");

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_pegawai\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pegawai',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpegawailist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pegawai_list)) $pegawai_list = new cpegawai_list();

// Page init
$pegawai_list->Page_Init();

// Page main
$pegawai_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pegawai->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpegawailist = new ew_Form("fpegawailist", "list");
fpegawailist.FormKeyCountName = '<?php echo $pegawai_list->FormKeyCountName ?>';

// Validate form
fpegawailist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_pin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_pin->FldCaption(), $pegawai->pegawai_pin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_nama->FldCaption(), $pegawai->pegawai_nama->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fpegawailist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_pin", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pegawai_nip", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pegawai_nama", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pembagian1_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pembagian2_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fpegawailist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawailist.ValidateRequired = true;
<?php } else { ?>
fpegawailist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpegawailist.Lists["x_pembagian1_id"] = {"LinkField":"x_pembagian1_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian1_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian1"};
fpegawailist.Lists["x_pembagian2_id"] = {"LinkField":"x_pembagian2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian2"};

// Form object for search
var CurrentSearchForm = fpegawailistsrch = new ew_Form("fpegawailistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pegawai->Export == "") { ?>
<div class="ewToolbar">
<?php if ($pegawai->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($pegawai_list->TotalRecs > 0 && $pegawai_list->ExportOptions->Visible()) { ?>
<?php $pegawai_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($pegawai_list->SearchOptions->Visible()) { ?>
<?php $pegawai_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($pegawai_list->FilterOptions->Visible()) { ?>
<?php $pegawai_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($pegawai->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($pegawai->CurrentAction == "gridadd") {
	$pegawai->CurrentFilter = "0=1";
	$pegawai_list->StartRec = 1;
	$pegawai_list->DisplayRecs = $pegawai->GridAddRowCount;
	$pegawai_list->TotalRecs = $pegawai_list->DisplayRecs;
	$pegawai_list->StopRec = $pegawai_list->DisplayRecs;
} else {
	$bSelectLimit = $pegawai_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pegawai_list->TotalRecs <= 0)
			$pegawai_list->TotalRecs = $pegawai->SelectRecordCount();
	} else {
		if (!$pegawai_list->Recordset && ($pegawai_list->Recordset = $pegawai_list->LoadRecordset()))
			$pegawai_list->TotalRecs = $pegawai_list->Recordset->RecordCount();
	}
	$pegawai_list->StartRec = 1;
	if ($pegawai_list->DisplayRecs <= 0 || ($pegawai->Export <> "" && $pegawai->ExportAll)) // Display all records
		$pegawai_list->DisplayRecs = $pegawai_list->TotalRecs;
	if (!($pegawai->Export <> "" && $pegawai->ExportAll))
		$pegawai_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pegawai_list->Recordset = $pegawai_list->LoadRecordset($pegawai_list->StartRec-1, $pegawai_list->DisplayRecs);

	// Set no record found message
	if ($pegawai->CurrentAction == "" && $pegawai_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$pegawai_list->setWarningMessage(ew_DeniedMsg());
		if ($pegawai_list->SearchWhere == "0=101")
			$pegawai_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pegawai_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($pegawai_list->AuditTrailOnSearch && $pegawai_list->Command == "search" && !$pegawai_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $pegawai_list->getSessionWhere();
		$pegawai_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$pegawai_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($pegawai->Export == "" && $pegawai->CurrentAction == "") { ?>
<form name="fpegawailistsrch" id="fpegawailistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($pegawai_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fpegawailistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pegawai">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($pegawai_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($pegawai_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $pegawai_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($pegawai_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($pegawai_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($pegawai_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($pegawai_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $pegawai_list->ShowPageHeader(); ?>
<?php
$pegawai_list->ShowMessage();
?>
<?php if ($pegawai_list->TotalRecs > 0 || $pegawai->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid pegawai">
<?php if ($pegawai->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($pegawai->CurrentAction <> "gridadd" && $pegawai->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pegawai_list->Pager)) $pegawai_list->Pager = new cPrevNextPager($pegawai_list->StartRec, $pegawai_list->DisplayRecs, $pegawai_list->TotalRecs) ?>
<?php if ($pegawai_list->Pager->RecordCount > 0 && $pegawai_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pegawai_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pegawai_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pegawai_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pegawai_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $pegawai_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pegawai">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($pegawai_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($pegawai_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($pegawai_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($pegawai_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($pegawai_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($pegawai->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pegawai_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fpegawailist" id="fpegawailist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai">
<div id="gmp_pegawai" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($pegawai_list->TotalRecs > 0 || $pegawai->CurrentAction == "add" || $pegawai->CurrentAction == "copy" || $pegawai->CurrentAction == "gridedit") { ?>
<table id="tbl_pegawailist" class="table ewTable">
<?php echo $pegawai->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$pegawai_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pegawai_list->RenderListOptions();

// Render list options (header, left)
$pegawai_list->ListOptions->Render("header", "left");
?>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
	<?php if ($pegawai->SortUrl($pegawai->pegawai_pin) == "") { ?>
		<th data-name="pegawai_pin"><div id="elh_pegawai_pegawai_pin" class="pegawai_pegawai_pin"><div class="ewTableHeaderCaption"><?php echo $pegawai->pegawai_pin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_pin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pegawai->SortUrl($pegawai->pegawai_pin) ?>',2);"><div id="elh_pegawai_pegawai_pin" class="pegawai_pegawai_pin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai->pegawai_pin->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pegawai->pegawai_pin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai->pegawai_pin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
	<?php if ($pegawai->SortUrl($pegawai->pegawai_nip) == "") { ?>
		<th data-name="pegawai_nip"><div id="elh_pegawai_pegawai_nip" class="pegawai_pegawai_nip"><div class="ewTableHeaderCaption"><?php echo $pegawai->pegawai_nip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_nip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pegawai->SortUrl($pegawai->pegawai_nip) ?>',2);"><div id="elh_pegawai_pegawai_nip" class="pegawai_pegawai_nip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai->pegawai_nip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pegawai->pegawai_nip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai->pegawai_nip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
	<?php if ($pegawai->SortUrl($pegawai->pegawai_nama) == "") { ?>
		<th data-name="pegawai_nama"><div id="elh_pegawai_pegawai_nama" class="pegawai_pegawai_nama"><div class="ewTableHeaderCaption"><?php echo $pegawai->pegawai_nama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_nama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pegawai->SortUrl($pegawai->pegawai_nama) ?>',2);"><div id="elh_pegawai_pegawai_nama" class="pegawai_pegawai_nama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai->pegawai_nama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pegawai->pegawai_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai->pegawai_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
	<?php if ($pegawai->SortUrl($pegawai->pembagian1_id) == "") { ?>
		<th data-name="pembagian1_id"><div id="elh_pegawai_pembagian1_id" class="pegawai_pembagian1_id"><div class="ewTableHeaderCaption"><?php echo $pegawai->pembagian1_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembagian1_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pegawai->SortUrl($pegawai->pembagian1_id) ?>',2);"><div id="elh_pegawai_pembagian1_id" class="pegawai_pembagian1_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai->pembagian1_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai->pembagian1_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai->pembagian1_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
	<?php if ($pegawai->SortUrl($pegawai->pembagian2_id) == "") { ?>
		<th data-name="pembagian2_id"><div id="elh_pegawai_pembagian2_id" class="pegawai_pembagian2_id"><div class="ewTableHeaderCaption"><?php echo $pegawai->pembagian2_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembagian2_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pegawai->SortUrl($pegawai->pembagian2_id) ?>',2);"><div id="elh_pegawai_pembagian2_id" class="pegawai_pembagian2_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai->pembagian2_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai->pembagian2_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai->pembagian2_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pegawai_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($pegawai->CurrentAction == "add" || $pegawai->CurrentAction == "copy") {
		$pegawai_list->RowIndex = 0;
		$pegawai_list->KeyCount = $pegawai_list->RowIndex;
		if ($pegawai->CurrentAction == "copy" && !$pegawai_list->LoadRow())
				$pegawai->CurrentAction = "add";
		if ($pegawai->CurrentAction == "add")
			$pegawai_list->LoadDefaultValues();
		if ($pegawai->EventCancelled) // Insert failed
			$pegawai_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$pegawai->ResetAttrs();
		$pegawai->RowAttrs = array_merge($pegawai->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_pegawai', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$pegawai->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pegawai_list->RenderRow();

		// Render list options
		$pegawai_list->RenderListOptions();
		$pegawai_list->StartRowCnt = 0;
?>
	<tr<?php echo $pegawai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pegawai_list->ListOptions->Render("body", "left", $pegawai_list->RowCnt);
?>
	<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<td data-name="pegawai_pin">
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_pin" class="form-group pegawai_pegawai_pin">
<input type="text" data-table="pegawai" data-field="x_pegawai_pin" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pin->EditValue ?>"<?php echo $pegawai->pegawai_pin->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_pin" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" value="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<td data-name="pegawai_nip">
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nip" class="form-group pegawai_pegawai_nip">
<input type="text" data-table="pegawai" data-field="x_pegawai_nip" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nip->EditValue ?>"<?php echo $pegawai->pegawai_nip->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_nip" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" value="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<td data-name="pegawai_nama">
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nama" class="form-group pegawai_pegawai_nama">
<input type="text" data-table="pegawai" data-field="x_pegawai_nama" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nama->EditValue ?>"<?php echo $pegawai->pegawai_nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_nama" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" value="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<td data-name="pembagian1_id">
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian1_id" class="form-group pegawai_pembagian1_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id"><?php echo (strval($pegawai->pembagian1_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian1_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian1_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian1_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->CurrentValue ?>"<?php echo $pegawai->pembagian1_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" name="o<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="o<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo ew_HtmlEncode($pegawai->pembagian1_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<td data-name="pembagian2_id">
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian2_id" class="form-group pegawai_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id"><?php echo (strval($pegawai->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->CurrentValue ?>"<?php echo $pegawai->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" name="o<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="o<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($pegawai->pembagian2_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pegawai_list->ListOptions->Render("body", "right", $pegawai_list->RowCnt);
?>
<script type="text/javascript">
fpegawailist.UpdateOpts(<?php echo $pegawai_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($pegawai->ExportAll && $pegawai->Export <> "") {
	$pegawai_list->StopRec = $pegawai_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pegawai_list->TotalRecs > $pegawai_list->StartRec + $pegawai_list->DisplayRecs - 1)
		$pegawai_list->StopRec = $pegawai_list->StartRec + $pegawai_list->DisplayRecs - 1;
	else
		$pegawai_list->StopRec = $pegawai_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pegawai_list->FormKeyCountName) && ($pegawai->CurrentAction == "gridadd" || $pegawai->CurrentAction == "gridedit" || $pegawai->CurrentAction == "F")) {
		$pegawai_list->KeyCount = $objForm->GetValue($pegawai_list->FormKeyCountName);
		$pegawai_list->StopRec = $pegawai_list->StartRec + $pegawai_list->KeyCount - 1;
	}
}
$pegawai_list->RecCnt = $pegawai_list->StartRec - 1;
if ($pegawai_list->Recordset && !$pegawai_list->Recordset->EOF) {
	$pegawai_list->Recordset->MoveFirst();
	$bSelectLimit = $pegawai_list->UseSelectLimit;
	if (!$bSelectLimit && $pegawai_list->StartRec > 1)
		$pegawai_list->Recordset->Move($pegawai_list->StartRec - 1);
} elseif (!$pegawai->AllowAddDeleteRow && $pegawai_list->StopRec == 0) {
	$pegawai_list->StopRec = $pegawai->GridAddRowCount;
}

// Initialize aggregate
$pegawai->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pegawai->ResetAttrs();
$pegawai_list->RenderRow();
$pegawai_list->EditRowCnt = 0;
if ($pegawai->CurrentAction == "edit")
	$pegawai_list->RowIndex = 1;
if ($pegawai->CurrentAction == "gridadd")
	$pegawai_list->RowIndex = 0;
if ($pegawai->CurrentAction == "gridedit")
	$pegawai_list->RowIndex = 0;
while ($pegawai_list->RecCnt < $pegawai_list->StopRec) {
	$pegawai_list->RecCnt++;
	if (intval($pegawai_list->RecCnt) >= intval($pegawai_list->StartRec)) {
		$pegawai_list->RowCnt++;
		if ($pegawai->CurrentAction == "gridadd" || $pegawai->CurrentAction == "gridedit" || $pegawai->CurrentAction == "F") {
			$pegawai_list->RowIndex++;
			$objForm->Index = $pegawai_list->RowIndex;
			if ($objForm->HasValue($pegawai_list->FormActionName))
				$pegawai_list->RowAction = strval($objForm->GetValue($pegawai_list->FormActionName));
			elseif ($pegawai->CurrentAction == "gridadd")
				$pegawai_list->RowAction = "insert";
			else
				$pegawai_list->RowAction = "";
		}

		// Set up key count
		$pegawai_list->KeyCount = $pegawai_list->RowIndex;

		// Init row class and style
		$pegawai->ResetAttrs();
		$pegawai->CssClass = "";
		if ($pegawai->CurrentAction == "gridadd") {
			$pegawai_list->LoadDefaultValues(); // Load default values
		} else {
			$pegawai_list->LoadRowValues($pegawai_list->Recordset); // Load row values
		}
		$pegawai->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pegawai->CurrentAction == "gridadd") // Grid add
			$pegawai->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pegawai->CurrentAction == "gridadd" && $pegawai->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pegawai_list->RestoreCurrentRowFormValues($pegawai_list->RowIndex); // Restore form values
		if ($pegawai->CurrentAction == "edit") {
			if ($pegawai_list->CheckInlineEditKey() && $pegawai_list->EditRowCnt == 0) { // Inline edit
				$pegawai->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($pegawai->CurrentAction == "gridedit") { // Grid edit
			if ($pegawai->EventCancelled) {
				$pegawai_list->RestoreCurrentRowFormValues($pegawai_list->RowIndex); // Restore form values
			}
			if ($pegawai_list->RowAction == "insert")
				$pegawai->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pegawai->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pegawai->CurrentAction == "edit" && $pegawai->RowType == EW_ROWTYPE_EDIT && $pegawai->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$pegawai_list->RestoreFormValues(); // Restore form values
		}
		if ($pegawai->CurrentAction == "gridedit" && ($pegawai->RowType == EW_ROWTYPE_EDIT || $pegawai->RowType == EW_ROWTYPE_ADD) && $pegawai->EventCancelled) // Update failed
			$pegawai_list->RestoreCurrentRowFormValues($pegawai_list->RowIndex); // Restore form values
		if ($pegawai->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pegawai_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$pegawai->RowAttrs = array_merge($pegawai->RowAttrs, array('data-rowindex'=>$pegawai_list->RowCnt, 'id'=>'r' . $pegawai_list->RowCnt . '_pegawai', 'data-rowtype'=>$pegawai->RowType));

		// Render row
		$pegawai_list->RenderRow();

		// Render list options
		$pegawai_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pegawai_list->RowAction <> "delete" && $pegawai_list->RowAction <> "insertdelete" && !($pegawai_list->RowAction == "insert" && $pegawai->CurrentAction == "F" && $pegawai_list->EmptyRow())) {
?>
	<tr<?php echo $pegawai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pegawai_list->ListOptions->Render("body", "left", $pegawai_list->RowCnt);
?>
	<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<td data-name="pegawai_pin"<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_pin" class="form-group pegawai_pegawai_pin">
<input type="text" data-table="pegawai" data-field="x_pegawai_pin" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pin->EditValue ?>"<?php echo $pegawai->pegawai_pin->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_pin" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" value="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->OldValue) ?>">
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_pin" class="form-group pegawai_pegawai_pin">
<input type="text" data-table="pegawai" data-field="x_pegawai_pin" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pin->EditValue ?>"<?php echo $pegawai->pegawai_pin->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_pin" class="pegawai_pegawai_pin">
<span<?php echo $pegawai->pegawai_pin->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_pin->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $pegawai_list->PageObjName . "_row_" . $pegawai_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_id" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai->pegawai_id->CurrentValue) ?>">
<input type="hidden" data-table="pegawai" data-field="x_pegawai_id" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_id" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_EDIT || $pegawai->CurrentMode == "edit") { ?>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_id" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai->pegawai_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<td data-name="pegawai_nip"<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nip" class="form-group pegawai_pegawai_nip">
<input type="text" data-table="pegawai" data-field="x_pegawai_nip" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nip->EditValue ?>"<?php echo $pegawai->pegawai_nip->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_nip" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" value="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->OldValue) ?>">
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nip" class="form-group pegawai_pegawai_nip">
<input type="text" data-table="pegawai" data-field="x_pegawai_nip" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nip->EditValue ?>"<?php echo $pegawai->pegawai_nip->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nip" class="pegawai_pegawai_nip">
<span<?php echo $pegawai->pegawai_nip->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nip->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<td data-name="pegawai_nama"<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nama" class="form-group pegawai_pegawai_nama">
<input type="text" data-table="pegawai" data-field="x_pegawai_nama" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nama->EditValue ?>"<?php echo $pegawai->pegawai_nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_nama" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" value="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->OldValue) ?>">
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nama" class="form-group pegawai_pegawai_nama">
<input type="text" data-table="pegawai" data-field="x_pegawai_nama" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nama->EditValue ?>"<?php echo $pegawai->pegawai_nama->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pegawai_nama" class="pegawai_pegawai_nama">
<span<?php echo $pegawai->pegawai_nama->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nama->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<td data-name="pembagian1_id"<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian1_id" class="form-group pegawai_pembagian1_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id"><?php echo (strval($pegawai->pembagian1_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian1_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian1_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian1_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->CurrentValue ?>"<?php echo $pegawai->pembagian1_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" name="o<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="o<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo ew_HtmlEncode($pegawai->pembagian1_id->OldValue) ?>">
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian1_id" class="form-group pegawai_pembagian1_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id"><?php echo (strval($pegawai->pembagian1_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian1_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian1_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian1_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->CurrentValue ?>"<?php echo $pegawai->pembagian1_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian1_id" class="pegawai_pembagian1_id">
<span<?php echo $pegawai->pembagian1_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian1_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<td data-name="pembagian2_id"<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian2_id" class="form-group pegawai_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id"><?php echo (strval($pegawai->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->CurrentValue ?>"<?php echo $pegawai->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" name="o<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="o<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($pegawai->pembagian2_id->OldValue) ?>">
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian2_id" class="form-group pegawai_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id"><?php echo (strval($pegawai->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->CurrentValue ?>"<?php echo $pegawai->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($pegawai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_list->RowCnt ?>_pegawai_pembagian2_id" class="pegawai_pembagian2_id">
<span<?php echo $pegawai->pembagian2_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian2_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pegawai_list->ListOptions->Render("body", "right", $pegawai_list->RowCnt);
?>
	</tr>
<?php if ($pegawai->RowType == EW_ROWTYPE_ADD || $pegawai->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpegawailist.UpdateOpts(<?php echo $pegawai_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pegawai->CurrentAction <> "gridadd")
		if (!$pegawai_list->Recordset->EOF) $pegawai_list->Recordset->MoveNext();
}
?>
<?php
	if ($pegawai->CurrentAction == "gridadd" || $pegawai->CurrentAction == "gridedit") {
		$pegawai_list->RowIndex = '$rowindex$';
		$pegawai_list->LoadDefaultValues();

		// Set row properties
		$pegawai->ResetAttrs();
		$pegawai->RowAttrs = array_merge($pegawai->RowAttrs, array('data-rowindex'=>$pegawai_list->RowIndex, 'id'=>'r0_pegawai', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pegawai->RowAttrs["class"], "ewTemplate");
		$pegawai->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pegawai_list->RenderRow();

		// Render list options
		$pegawai_list->RenderListOptions();
		$pegawai_list->StartRowCnt = 0;
?>
	<tr<?php echo $pegawai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pegawai_list->ListOptions->Render("body", "left", $pegawai_list->RowIndex);
?>
	<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<td data-name="pegawai_pin">
<span id="el$rowindex$_pegawai_pegawai_pin" class="form-group pegawai_pegawai_pin">
<input type="text" data-table="pegawai" data-field="x_pegawai_pin" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pin->EditValue ?>"<?php echo $pegawai->pegawai_pin->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_pin" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_pin" value="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<td data-name="pegawai_nip">
<span id="el$rowindex$_pegawai_pegawai_nip" class="form-group pegawai_pegawai_nip">
<input type="text" data-table="pegawai" data-field="x_pegawai_nip" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nip->EditValue ?>"<?php echo $pegawai->pegawai_nip->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_nip" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nip" value="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<td data-name="pegawai_nama">
<span id="el$rowindex$_pegawai_pegawai_nama" class="form-group pegawai_pegawai_nama">
<input type="text" data-table="pegawai" data-field="x_pegawai_nama" name="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="x<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nama->EditValue ?>"<?php echo $pegawai->pegawai_nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_nama" name="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" id="o<?php echo $pegawai_list->RowIndex ?>_pegawai_nama" value="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<td data-name="pembagian1_id">
<span id="el$rowindex$_pegawai_pembagian1_id" class="form-group pegawai_pembagian1_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id"><?php echo (strval($pegawai->pembagian1_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian1_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian1_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian1_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->CurrentValue ?>"<?php echo $pegawai->pembagian1_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo $pegawai->pembagian1_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pegawai" data-field="x_pembagian1_id" name="o<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" id="o<?php echo $pegawai_list->RowIndex ?>_pembagian1_id" value="<?php echo ew_HtmlEncode($pegawai->pembagian1_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<td data-name="pembagian2_id">
<span id="el$rowindex$_pegawai_pembagian2_id" class="form-group pegawai_pembagian2_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id"><?php echo (strval($pegawai->pembagian2_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pegawai->pembagian2_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pegawai->pembagian2_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pegawai->pembagian2_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->CurrentValue ?>"<?php echo $pegawai->pembagian2_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="s_x<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo $pegawai->pembagian2_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pegawai" data-field="x_pembagian2_id" name="o<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" id="o<?php echo $pegawai_list->RowIndex ?>_pembagian2_id" value="<?php echo ew_HtmlEncode($pegawai->pembagian2_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pegawai_list->ListOptions->Render("body", "right", $pegawai_list->RowCnt);
?>
<script type="text/javascript">
fpegawailist.UpdateOpts(<?php echo $pegawai_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pegawai->CurrentAction == "add" || $pegawai->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $pegawai_list->FormKeyCountName ?>" id="<?php echo $pegawai_list->FormKeyCountName ?>" value="<?php echo $pegawai_list->KeyCount ?>">
<?php } ?>
<?php if ($pegawai->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pegawai_list->FormKeyCountName ?>" id="<?php echo $pegawai_list->FormKeyCountName ?>" value="<?php echo $pegawai_list->KeyCount ?>">
<?php echo $pegawai_list->MultiSelectKey ?>
<?php } ?>
<?php if ($pegawai->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $pegawai_list->FormKeyCountName ?>" id="<?php echo $pegawai_list->FormKeyCountName ?>" value="<?php echo $pegawai_list->KeyCount ?>">
<?php } ?>
<?php if ($pegawai->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pegawai_list->FormKeyCountName ?>" id="<?php echo $pegawai_list->FormKeyCountName ?>" value="<?php echo $pegawai_list->KeyCount ?>">
<?php echo $pegawai_list->MultiSelectKey ?>
<?php } ?>
<?php if ($pegawai->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pegawai_list->Recordset)
	$pegawai_list->Recordset->Close();
?>
<?php if ($pegawai->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($pegawai->CurrentAction <> "gridadd" && $pegawai->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pegawai_list->Pager)) $pegawai_list->Pager = new cPrevNextPager($pegawai_list->StartRec, $pegawai_list->DisplayRecs, $pegawai_list->TotalRecs) ?>
<?php if ($pegawai_list->Pager->RecordCount > 0 && $pegawai_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_list->PageUrl() ?>start=<?php echo $pegawai_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pegawai_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pegawai_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pegawai_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pegawai_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $pegawai_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pegawai">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($pegawai_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($pegawai_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($pegawai_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($pegawai_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($pegawai_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($pegawai->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pegawai_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($pegawai_list->TotalRecs == 0 && $pegawai->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pegawai_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pegawai->Export == "") { ?>
<script type="text/javascript">
fpegawailistsrch.FilterList = <?php echo $pegawai_list->GetFilterList() ?>;
fpegawailistsrch.Init();
fpegawailist.Init();
</script>
<?php } ?>
<?php
$pegawai_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pegawai->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pegawai_list->Page_Terminate();
?>
