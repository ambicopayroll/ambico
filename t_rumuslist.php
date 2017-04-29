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

$t_rumus_list = NULL; // Initialize page object first

class ct_rumus_list extends ct_rumus {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_rumus';

	// Page object name
	var $PageObjName = 't_rumus_list';

	// Grid form hidden field names
	var $FormName = 'ft_rumuslist';
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

		// Table object (t_rumus)
		if (!isset($GLOBALS["t_rumus"]) || get_class($GLOBALS["t_rumus"]) == "ct_rumus") {
			$GLOBALS["t_rumus"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_rumus"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_rumusadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_rumusdelete.php";
		$this->MultiUpdateUrl = "t_rumusupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ft_rumuslistsrch";

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
		$this->setKey("rumus_id", ""); // Clear inline edit key
		$this->umr->FormValue = ""; // Clear form value
		$this->upah->FormValue = ""; // Clear form value
		$this->premi_hadir->FormValue = ""; // Clear form value
		$this->premi_malam->FormValue = ""; // Clear form value
		$this->pot_absen->FormValue = ""; // Clear form value
		$this->lembur->FormValue = ""; // Clear form value
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
		if (@$_GET["rumus_id"] <> "") {
			$this->rumus_id->setQueryStringValue($_GET["rumus_id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("rumus_id", $this->rumus_id->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("rumus_id")) <> strval($this->rumus_id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["rumus_id"] <> "") {
				$this->rumus_id->setQueryStringValue($_GET["rumus_id"]);
				$this->setKey("rumus_id", $this->rumus_id->CurrentValue); // Set up key
			} else {
				$this->setKey("rumus_id", ""); // Clear key
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
			$this->rumus_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->rumus_id->FormValue))
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
					$sKey .= $this->rumus_id->CurrentValue;

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
		if ($objForm->HasValue("x_rumus_nama") && $objForm->HasValue("o_rumus_nama") && $this->rumus_nama->CurrentValue <> $this->rumus_nama->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_hk_gol") && $objForm->HasValue("o_hk_gol") && $this->hk_gol->CurrentValue <> $this->hk_gol->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_umr") && $objForm->HasValue("o_umr") && $this->umr->CurrentValue <> $this->umr->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_hk_jml") && $objForm->HasValue("o_hk_jml") && $this->hk_jml->CurrentValue <> $this->hk_jml->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_upah") && $objForm->HasValue("o_upah") && $this->upah->CurrentValue <> $this->upah->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_premi_hadir") && $objForm->HasValue("o_premi_hadir") && $this->premi_hadir->CurrentValue <> $this->premi_hadir->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_premi_malam") && $objForm->HasValue("o_premi_malam") && $this->premi_malam->CurrentValue <> $this->premi_malam->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pot_absen") && $objForm->HasValue("o_pot_absen") && $this->pot_absen->CurrentValue <> $this->pot_absen->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_lembur") && $objForm->HasValue("o_lembur") && $this->lembur->CurrentValue <> $this->lembur->OldValue)
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "ft_rumuslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->rumus_id->AdvancedSearch->ToJSON(), ","); // Field rumus_id
		$sFilterList = ew_Concat($sFilterList, $this->rumus_nama->AdvancedSearch->ToJSON(), ","); // Field rumus_nama
		$sFilterList = ew_Concat($sFilterList, $this->hk_gol->AdvancedSearch->ToJSON(), ","); // Field hk_gol
		$sFilterList = ew_Concat($sFilterList, $this->umr->AdvancedSearch->ToJSON(), ","); // Field umr
		$sFilterList = ew_Concat($sFilterList, $this->hk_jml->AdvancedSearch->ToJSON(), ","); // Field hk_jml
		$sFilterList = ew_Concat($sFilterList, $this->upah->AdvancedSearch->ToJSON(), ","); // Field upah
		$sFilterList = ew_Concat($sFilterList, $this->premi_hadir->AdvancedSearch->ToJSON(), ","); // Field premi_hadir
		$sFilterList = ew_Concat($sFilterList, $this->premi_malam->AdvancedSearch->ToJSON(), ","); // Field premi_malam
		$sFilterList = ew_Concat($sFilterList, $this->pot_absen->AdvancedSearch->ToJSON(), ","); // Field pot_absen
		$sFilterList = ew_Concat($sFilterList, $this->lembur->AdvancedSearch->ToJSON(), ","); // Field lembur
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ft_rumuslistsrch", $filters);

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

		// Field rumus_id
		$this->rumus_id->AdvancedSearch->SearchValue = @$filter["x_rumus_id"];
		$this->rumus_id->AdvancedSearch->SearchOperator = @$filter["z_rumus_id"];
		$this->rumus_id->AdvancedSearch->SearchCondition = @$filter["v_rumus_id"];
		$this->rumus_id->AdvancedSearch->SearchValue2 = @$filter["y_rumus_id"];
		$this->rumus_id->AdvancedSearch->SearchOperator2 = @$filter["w_rumus_id"];
		$this->rumus_id->AdvancedSearch->Save();

		// Field rumus_nama
		$this->rumus_nama->AdvancedSearch->SearchValue = @$filter["x_rumus_nama"];
		$this->rumus_nama->AdvancedSearch->SearchOperator = @$filter["z_rumus_nama"];
		$this->rumus_nama->AdvancedSearch->SearchCondition = @$filter["v_rumus_nama"];
		$this->rumus_nama->AdvancedSearch->SearchValue2 = @$filter["y_rumus_nama"];
		$this->rumus_nama->AdvancedSearch->SearchOperator2 = @$filter["w_rumus_nama"];
		$this->rumus_nama->AdvancedSearch->Save();

		// Field hk_gol
		$this->hk_gol->AdvancedSearch->SearchValue = @$filter["x_hk_gol"];
		$this->hk_gol->AdvancedSearch->SearchOperator = @$filter["z_hk_gol"];
		$this->hk_gol->AdvancedSearch->SearchCondition = @$filter["v_hk_gol"];
		$this->hk_gol->AdvancedSearch->SearchValue2 = @$filter["y_hk_gol"];
		$this->hk_gol->AdvancedSearch->SearchOperator2 = @$filter["w_hk_gol"];
		$this->hk_gol->AdvancedSearch->Save();

		// Field umr
		$this->umr->AdvancedSearch->SearchValue = @$filter["x_umr"];
		$this->umr->AdvancedSearch->SearchOperator = @$filter["z_umr"];
		$this->umr->AdvancedSearch->SearchCondition = @$filter["v_umr"];
		$this->umr->AdvancedSearch->SearchValue2 = @$filter["y_umr"];
		$this->umr->AdvancedSearch->SearchOperator2 = @$filter["w_umr"];
		$this->umr->AdvancedSearch->Save();

		// Field hk_jml
		$this->hk_jml->AdvancedSearch->SearchValue = @$filter["x_hk_jml"];
		$this->hk_jml->AdvancedSearch->SearchOperator = @$filter["z_hk_jml"];
		$this->hk_jml->AdvancedSearch->SearchCondition = @$filter["v_hk_jml"];
		$this->hk_jml->AdvancedSearch->SearchValue2 = @$filter["y_hk_jml"];
		$this->hk_jml->AdvancedSearch->SearchOperator2 = @$filter["w_hk_jml"];
		$this->hk_jml->AdvancedSearch->Save();

		// Field upah
		$this->upah->AdvancedSearch->SearchValue = @$filter["x_upah"];
		$this->upah->AdvancedSearch->SearchOperator = @$filter["z_upah"];
		$this->upah->AdvancedSearch->SearchCondition = @$filter["v_upah"];
		$this->upah->AdvancedSearch->SearchValue2 = @$filter["y_upah"];
		$this->upah->AdvancedSearch->SearchOperator2 = @$filter["w_upah"];
		$this->upah->AdvancedSearch->Save();

		// Field premi_hadir
		$this->premi_hadir->AdvancedSearch->SearchValue = @$filter["x_premi_hadir"];
		$this->premi_hadir->AdvancedSearch->SearchOperator = @$filter["z_premi_hadir"];
		$this->premi_hadir->AdvancedSearch->SearchCondition = @$filter["v_premi_hadir"];
		$this->premi_hadir->AdvancedSearch->SearchValue2 = @$filter["y_premi_hadir"];
		$this->premi_hadir->AdvancedSearch->SearchOperator2 = @$filter["w_premi_hadir"];
		$this->premi_hadir->AdvancedSearch->Save();

		// Field premi_malam
		$this->premi_malam->AdvancedSearch->SearchValue = @$filter["x_premi_malam"];
		$this->premi_malam->AdvancedSearch->SearchOperator = @$filter["z_premi_malam"];
		$this->premi_malam->AdvancedSearch->SearchCondition = @$filter["v_premi_malam"];
		$this->premi_malam->AdvancedSearch->SearchValue2 = @$filter["y_premi_malam"];
		$this->premi_malam->AdvancedSearch->SearchOperator2 = @$filter["w_premi_malam"];
		$this->premi_malam->AdvancedSearch->Save();

		// Field pot_absen
		$this->pot_absen->AdvancedSearch->SearchValue = @$filter["x_pot_absen"];
		$this->pot_absen->AdvancedSearch->SearchOperator = @$filter["z_pot_absen"];
		$this->pot_absen->AdvancedSearch->SearchCondition = @$filter["v_pot_absen"];
		$this->pot_absen->AdvancedSearch->SearchValue2 = @$filter["y_pot_absen"];
		$this->pot_absen->AdvancedSearch->SearchOperator2 = @$filter["w_pot_absen"];
		$this->pot_absen->AdvancedSearch->Save();

		// Field lembur
		$this->lembur->AdvancedSearch->SearchValue = @$filter["x_lembur"];
		$this->lembur->AdvancedSearch->SearchOperator = @$filter["z_lembur"];
		$this->lembur->AdvancedSearch->SearchCondition = @$filter["v_lembur"];
		$this->lembur->AdvancedSearch->SearchValue2 = @$filter["y_lembur"];
		$this->lembur->AdvancedSearch->SearchOperator2 = @$filter["w_lembur"];
		$this->lembur->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->rumus_nama, $arKeywords, $type);
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
			$this->UpdateSort($this->rumus_nama, $bCtrl); // rumus_nama
			$this->UpdateSort($this->hk_gol, $bCtrl); // hk_gol
			$this->UpdateSort($this->umr, $bCtrl); // umr
			$this->UpdateSort($this->hk_jml, $bCtrl); // hk_jml
			$this->UpdateSort($this->upah, $bCtrl); // upah
			$this->UpdateSort($this->premi_hadir, $bCtrl); // premi_hadir
			$this->UpdateSort($this->premi_malam, $bCtrl); // premi_malam
			$this->UpdateSort($this->pot_absen, $bCtrl); // pot_absen
			$this->UpdateSort($this->lembur, $bCtrl); // lembur
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
				$this->rumus_nama->setSort("");
				$this->hk_gol->setSort("");
				$this->umr->setSort("");
				$this->hk_jml->setSort("");
				$this->upah->setSort("");
				$this->premi_hadir->setSort("");
				$this->premi_malam->setSort("");
				$this->pot_absen->setSort("");
				$this->lembur->setSort("");
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->rumus_id->CurrentValue) . "\">";
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->rumus_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->rumus_id->CurrentValue . "\">";
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

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.ft_rumuslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ft_rumuslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ft_rumuslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ft_rumuslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ft_rumuslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->rumus_nama->CurrentValue = NULL;
		$this->rumus_nama->OldValue = $this->rumus_nama->CurrentValue;
		$this->hk_gol->CurrentValue = 0;
		$this->hk_gol->OldValue = $this->hk_gol->CurrentValue;
		$this->umr->CurrentValue = 0.00;
		$this->umr->OldValue = $this->umr->CurrentValue;
		$this->hk_jml->CurrentValue = 0;
		$this->hk_jml->OldValue = $this->hk_jml->CurrentValue;
		$this->upah->CurrentValue = 0.00;
		$this->upah->OldValue = $this->upah->CurrentValue;
		$this->premi_hadir->CurrentValue = 0.00;
		$this->premi_hadir->OldValue = $this->premi_hadir->CurrentValue;
		$this->premi_malam->CurrentValue = 0.00;
		$this->premi_malam->OldValue = $this->premi_malam->CurrentValue;
		$this->pot_absen->CurrentValue = 0.00;
		$this->pot_absen->OldValue = $this->pot_absen->CurrentValue;
		$this->lembur->CurrentValue = 0.00;
		$this->lembur->OldValue = $this->lembur->CurrentValue;
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
		if (!$this->rumus_nama->FldIsDetailKey) {
			$this->rumus_nama->setFormValue($objForm->GetValue("x_rumus_nama"));
		}
		$this->rumus_nama->setOldValue($objForm->GetValue("o_rumus_nama"));
		if (!$this->hk_gol->FldIsDetailKey) {
			$this->hk_gol->setFormValue($objForm->GetValue("x_hk_gol"));
		}
		$this->hk_gol->setOldValue($objForm->GetValue("o_hk_gol"));
		if (!$this->umr->FldIsDetailKey) {
			$this->umr->setFormValue($objForm->GetValue("x_umr"));
		}
		$this->umr->setOldValue($objForm->GetValue("o_umr"));
		if (!$this->hk_jml->FldIsDetailKey) {
			$this->hk_jml->setFormValue($objForm->GetValue("x_hk_jml"));
		}
		$this->hk_jml->setOldValue($objForm->GetValue("o_hk_jml"));
		if (!$this->upah->FldIsDetailKey) {
			$this->upah->setFormValue($objForm->GetValue("x_upah"));
		}
		$this->upah->setOldValue($objForm->GetValue("o_upah"));
		if (!$this->premi_hadir->FldIsDetailKey) {
			$this->premi_hadir->setFormValue($objForm->GetValue("x_premi_hadir"));
		}
		$this->premi_hadir->setOldValue($objForm->GetValue("o_premi_hadir"));
		if (!$this->premi_malam->FldIsDetailKey) {
			$this->premi_malam->setFormValue($objForm->GetValue("x_premi_malam"));
		}
		$this->premi_malam->setOldValue($objForm->GetValue("o_premi_malam"));
		if (!$this->pot_absen->FldIsDetailKey) {
			$this->pot_absen->setFormValue($objForm->GetValue("x_pot_absen"));
		}
		$this->pot_absen->setOldValue($objForm->GetValue("o_pot_absen"));
		if (!$this->lembur->FldIsDetailKey) {
			$this->lembur->setFormValue($objForm->GetValue("x_lembur"));
		}
		$this->lembur->setOldValue($objForm->GetValue("o_lembur"));
		if (!$this->rumus_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->rumus_id->setFormValue($objForm->GetValue("x_rumus_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->rumus_id->CurrentValue = $this->rumus_id->FormValue;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
			if (strval($this->umr->EditValue) <> "" && is_numeric($this->umr->EditValue)) {
			$this->umr->EditValue = ew_FormatNumber($this->umr->EditValue, -2, -2, -2, -2);
			$this->umr->OldValue = $this->umr->EditValue;
			}

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
			if (strval($this->upah->EditValue) <> "" && is_numeric($this->upah->EditValue)) {
			$this->upah->EditValue = ew_FormatNumber($this->upah->EditValue, -2, -2, -2, -2);
			$this->upah->OldValue = $this->upah->EditValue;
			}

			// premi_hadir
			$this->premi_hadir->EditAttrs["class"] = "form-control";
			$this->premi_hadir->EditCustomAttributes = "";
			$this->premi_hadir->EditValue = ew_HtmlEncode($this->premi_hadir->CurrentValue);
			$this->premi_hadir->PlaceHolder = ew_RemoveHtml($this->premi_hadir->FldCaption());
			if (strval($this->premi_hadir->EditValue) <> "" && is_numeric($this->premi_hadir->EditValue)) {
			$this->premi_hadir->EditValue = ew_FormatNumber($this->premi_hadir->EditValue, -2, -2, -2, -2);
			$this->premi_hadir->OldValue = $this->premi_hadir->EditValue;
			}

			// premi_malam
			$this->premi_malam->EditAttrs["class"] = "form-control";
			$this->premi_malam->EditCustomAttributes = "";
			$this->premi_malam->EditValue = ew_HtmlEncode($this->premi_malam->CurrentValue);
			$this->premi_malam->PlaceHolder = ew_RemoveHtml($this->premi_malam->FldCaption());
			if (strval($this->premi_malam->EditValue) <> "" && is_numeric($this->premi_malam->EditValue)) {
			$this->premi_malam->EditValue = ew_FormatNumber($this->premi_malam->EditValue, -2, -2, -2, -2);
			$this->premi_malam->OldValue = $this->premi_malam->EditValue;
			}

			// pot_absen
			$this->pot_absen->EditAttrs["class"] = "form-control";
			$this->pot_absen->EditCustomAttributes = "";
			$this->pot_absen->EditValue = ew_HtmlEncode($this->pot_absen->CurrentValue);
			$this->pot_absen->PlaceHolder = ew_RemoveHtml($this->pot_absen->FldCaption());
			if (strval($this->pot_absen->EditValue) <> "" && is_numeric($this->pot_absen->EditValue)) {
			$this->pot_absen->EditValue = ew_FormatNumber($this->pot_absen->EditValue, -2, -2, -2, -2);
			$this->pot_absen->OldValue = $this->pot_absen->EditValue;
			}

			// lembur
			$this->lembur->EditAttrs["class"] = "form-control";
			$this->lembur->EditCustomAttributes = "";
			$this->lembur->EditValue = ew_HtmlEncode($this->lembur->CurrentValue);
			$this->lembur->PlaceHolder = ew_RemoveHtml($this->lembur->FldCaption());
			if (strval($this->lembur->EditValue) <> "" && is_numeric($this->lembur->EditValue)) {
			$this->lembur->EditValue = ew_FormatNumber($this->lembur->EditValue, -2, -2, -2, -2);
			$this->lembur->OldValue = $this->lembur->EditValue;
			}

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			if (strval($this->umr->EditValue) <> "" && is_numeric($this->umr->EditValue)) {
			$this->umr->EditValue = ew_FormatNumber($this->umr->EditValue, -2, -2, -2, -2);
			$this->umr->OldValue = $this->umr->EditValue;
			}

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
			if (strval($this->upah->EditValue) <> "" && is_numeric($this->upah->EditValue)) {
			$this->upah->EditValue = ew_FormatNumber($this->upah->EditValue, -2, -2, -2, -2);
			$this->upah->OldValue = $this->upah->EditValue;
			}

			// premi_hadir
			$this->premi_hadir->EditAttrs["class"] = "form-control";
			$this->premi_hadir->EditCustomAttributes = "";
			$this->premi_hadir->EditValue = ew_HtmlEncode($this->premi_hadir->CurrentValue);
			$this->premi_hadir->PlaceHolder = ew_RemoveHtml($this->premi_hadir->FldCaption());
			if (strval($this->premi_hadir->EditValue) <> "" && is_numeric($this->premi_hadir->EditValue)) {
			$this->premi_hadir->EditValue = ew_FormatNumber($this->premi_hadir->EditValue, -2, -2, -2, -2);
			$this->premi_hadir->OldValue = $this->premi_hadir->EditValue;
			}

			// premi_malam
			$this->premi_malam->EditAttrs["class"] = "form-control";
			$this->premi_malam->EditCustomAttributes = "";
			$this->premi_malam->EditValue = ew_HtmlEncode($this->premi_malam->CurrentValue);
			$this->premi_malam->PlaceHolder = ew_RemoveHtml($this->premi_malam->FldCaption());
			if (strval($this->premi_malam->EditValue) <> "" && is_numeric($this->premi_malam->EditValue)) {
			$this->premi_malam->EditValue = ew_FormatNumber($this->premi_malam->EditValue, -2, -2, -2, -2);
			$this->premi_malam->OldValue = $this->premi_malam->EditValue;
			}

			// pot_absen
			$this->pot_absen->EditAttrs["class"] = "form-control";
			$this->pot_absen->EditCustomAttributes = "";
			$this->pot_absen->EditValue = ew_HtmlEncode($this->pot_absen->CurrentValue);
			$this->pot_absen->PlaceHolder = ew_RemoveHtml($this->pot_absen->FldCaption());
			if (strval($this->pot_absen->EditValue) <> "" && is_numeric($this->pot_absen->EditValue)) {
			$this->pot_absen->EditValue = ew_FormatNumber($this->pot_absen->EditValue, -2, -2, -2, -2);
			$this->pot_absen->OldValue = $this->pot_absen->EditValue;
			}

			// lembur
			$this->lembur->EditAttrs["class"] = "form-control";
			$this->lembur->EditCustomAttributes = "";
			$this->lembur->EditValue = ew_HtmlEncode($this->lembur->CurrentValue);
			$this->lembur->PlaceHolder = ew_RemoveHtml($this->lembur->FldCaption());
			if (strval($this->lembur->EditValue) <> "" && is_numeric($this->lembur->EditValue)) {
			$this->lembur->EditValue = ew_FormatNumber($this->lembur->EditValue, -2, -2, -2, -2);
			$this->lembur->OldValue = $this->lembur->EditValue;
			}

			// Edit refer script
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

			// rumus_nama
			$this->rumus_nama->SetDbValueDef($rsnew, $this->rumus_nama->CurrentValue, "", $this->rumus_nama->ReadOnly);

			// hk_gol
			$this->hk_gol->SetDbValueDef($rsnew, $this->hk_gol->CurrentValue, 0, $this->hk_gol->ReadOnly);

			// umr
			$this->umr->SetDbValueDef($rsnew, $this->umr->CurrentValue, 0, $this->umr->ReadOnly);

			// hk_jml
			$this->hk_jml->SetDbValueDef($rsnew, $this->hk_jml->CurrentValue, 0, $this->hk_jml->ReadOnly);

			// upah
			$this->upah->SetDbValueDef($rsnew, $this->upah->CurrentValue, 0, $this->upah->ReadOnly);

			// premi_hadir
			$this->premi_hadir->SetDbValueDef($rsnew, $this->premi_hadir->CurrentValue, 0, $this->premi_hadir->ReadOnly);

			// premi_malam
			$this->premi_malam->SetDbValueDef($rsnew, $this->premi_malam->CurrentValue, 0, $this->premi_malam->ReadOnly);

			// pot_absen
			$this->pot_absen->SetDbValueDef($rsnew, $this->pot_absen->CurrentValue, 0, $this->pot_absen->ReadOnly);

			// lembur
			$this->lembur->SetDbValueDef($rsnew, $this->lembur->CurrentValue, 0, $this->lembur->ReadOnly);

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
		$item->Body = "<button id=\"emf_t_rumus\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_rumus',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ft_rumuslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($t_rumus_list)) $t_rumus_list = new ct_rumus_list();

// Page init
$t_rumus_list->Page_Init();

// Page main
$t_rumus_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_rumus_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_rumus->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ft_rumuslist = new ew_Form("ft_rumuslist", "list");
ft_rumuslist.FormKeyCountName = '<?php echo $t_rumus_list->FormKeyCountName ?>';

// Validate form
ft_rumuslist.Validate = function() {
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
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
ft_rumuslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "rumus_nama", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hk_gol", false)) return false;
	if (ew_ValueChanged(fobj, infix, "umr", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hk_jml", false)) return false;
	if (ew_ValueChanged(fobj, infix, "upah", false)) return false;
	if (ew_ValueChanged(fobj, infix, "premi_hadir", false)) return false;
	if (ew_ValueChanged(fobj, infix, "premi_malam", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pot_absen", false)) return false;
	if (ew_ValueChanged(fobj, infix, "lembur", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_rumuslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_rumuslist.ValidateRequired = true;
<?php } else { ?>
ft_rumuslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_rumuslist.Lists["x_hk_gol"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_rumuslist.Lists["x_hk_gol"].Options = <?php echo json_encode($t_rumus->hk_gol->Options()) ?>;

// Form object for search
var CurrentSearchForm = ft_rumuslistsrch = new ew_Form("ft_rumuslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_rumus->Export == "") { ?>
<div class="ewToolbar">
<?php if ($t_rumus->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_rumus_list->TotalRecs > 0 && $t_rumus_list->ExportOptions->Visible()) { ?>
<?php $t_rumus_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($t_rumus_list->SearchOptions->Visible()) { ?>
<?php $t_rumus_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($t_rumus_list->FilterOptions->Visible()) { ?>
<?php $t_rumus_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($t_rumus->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($t_rumus->CurrentAction == "gridadd") {
	$t_rumus->CurrentFilter = "0=1";
	$t_rumus_list->StartRec = 1;
	$t_rumus_list->DisplayRecs = $t_rumus->GridAddRowCount;
	$t_rumus_list->TotalRecs = $t_rumus_list->DisplayRecs;
	$t_rumus_list->StopRec = $t_rumus_list->DisplayRecs;
} else {
	$bSelectLimit = $t_rumus_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_rumus_list->TotalRecs <= 0)
			$t_rumus_list->TotalRecs = $t_rumus->SelectRecordCount();
	} else {
		if (!$t_rumus_list->Recordset && ($t_rumus_list->Recordset = $t_rumus_list->LoadRecordset()))
			$t_rumus_list->TotalRecs = $t_rumus_list->Recordset->RecordCount();
	}
	$t_rumus_list->StartRec = 1;
	if ($t_rumus_list->DisplayRecs <= 0 || ($t_rumus->Export <> "" && $t_rumus->ExportAll)) // Display all records
		$t_rumus_list->DisplayRecs = $t_rumus_list->TotalRecs;
	if (!($t_rumus->Export <> "" && $t_rumus->ExportAll))
		$t_rumus_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_rumus_list->Recordset = $t_rumus_list->LoadRecordset($t_rumus_list->StartRec-1, $t_rumus_list->DisplayRecs);

	// Set no record found message
	if ($t_rumus->CurrentAction == "" && $t_rumus_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_rumus_list->setWarningMessage(ew_DeniedMsg());
		if ($t_rumus_list->SearchWhere == "0=101")
			$t_rumus_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_rumus_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($t_rumus_list->AuditTrailOnSearch && $t_rumus_list->Command == "search" && !$t_rumus_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $t_rumus_list->getSessionWhere();
		$t_rumus_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$t_rumus_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($t_rumus->Export == "" && $t_rumus->CurrentAction == "") { ?>
<form name="ft_rumuslistsrch" id="ft_rumuslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($t_rumus_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ft_rumuslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="t_rumus">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($t_rumus_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($t_rumus_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $t_rumus_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($t_rumus_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($t_rumus_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($t_rumus_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($t_rumus_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $t_rumus_list->ShowPageHeader(); ?>
<?php
$t_rumus_list->ShowMessage();
?>
<?php if ($t_rumus_list->TotalRecs > 0 || $t_rumus->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_rumus">
<?php if ($t_rumus->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($t_rumus->CurrentAction <> "gridadd" && $t_rumus->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_rumus_list->Pager)) $t_rumus_list->Pager = new cPrevNextPager($t_rumus_list->StartRec, $t_rumus_list->DisplayRecs, $t_rumus_list->TotalRecs) ?>
<?php if ($t_rumus_list->Pager->RecordCount > 0 && $t_rumus_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_rumus_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_rumus_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_rumus_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_rumus_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_rumus_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_rumus_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_rumus_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_rumus_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_rumus_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($t_rumus_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $t_rumus_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="t_rumus">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($t_rumus_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($t_rumus_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($t_rumus_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($t_rumus_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($t_rumus_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($t_rumus->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_rumus_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="ft_rumuslist" id="ft_rumuslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_rumus_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_rumus_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_rumus">
<div id="gmp_t_rumus" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($t_rumus_list->TotalRecs > 0 || $t_rumus->CurrentAction == "add" || $t_rumus->CurrentAction == "copy" || $t_rumus->CurrentAction == "gridedit") { ?>
<table id="tbl_t_rumuslist" class="table ewTable">
<?php echo $t_rumus->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_rumus_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_rumus_list->RenderListOptions();

// Render list options (header, left)
$t_rumus_list->ListOptions->Render("header", "left");
?>
<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
	<?php if ($t_rumus->SortUrl($t_rumus->rumus_nama) == "") { ?>
		<th data-name="rumus_nama"><div id="elh_t_rumus_rumus_nama" class="t_rumus_rumus_nama"><div class="ewTableHeaderCaption"><?php echo $t_rumus->rumus_nama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rumus_nama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->rumus_nama) ?>',2);"><div id="elh_t_rumus_rumus_nama" class="t_rumus_rumus_nama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->rumus_nama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->rumus_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->rumus_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
	<?php if ($t_rumus->SortUrl($t_rumus->hk_gol) == "") { ?>
		<th data-name="hk_gol"><div id="elh_t_rumus_hk_gol" class="t_rumus_hk_gol"><div class="ewTableHeaderCaption"><?php echo $t_rumus->hk_gol->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hk_gol"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->hk_gol) ?>',2);"><div id="elh_t_rumus_hk_gol" class="t_rumus_hk_gol">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->hk_gol->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->hk_gol->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->hk_gol->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->umr->Visible) { // umr ?>
	<?php if ($t_rumus->SortUrl($t_rumus->umr) == "") { ?>
		<th data-name="umr"><div id="elh_t_rumus_umr" class="t_rumus_umr"><div class="ewTableHeaderCaption"><?php echo $t_rumus->umr->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="umr"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->umr) ?>',2);"><div id="elh_t_rumus_umr" class="t_rumus_umr">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->umr->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->umr->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->umr->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
	<?php if ($t_rumus->SortUrl($t_rumus->hk_jml) == "") { ?>
		<th data-name="hk_jml"><div id="elh_t_rumus_hk_jml" class="t_rumus_hk_jml"><div class="ewTableHeaderCaption"><?php echo $t_rumus->hk_jml->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hk_jml"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->hk_jml) ?>',2);"><div id="elh_t_rumus_hk_jml" class="t_rumus_hk_jml">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->hk_jml->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->hk_jml->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->hk_jml->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->upah->Visible) { // upah ?>
	<?php if ($t_rumus->SortUrl($t_rumus->upah) == "") { ?>
		<th data-name="upah"><div id="elh_t_rumus_upah" class="t_rumus_upah"><div class="ewTableHeaderCaption"><?php echo $t_rumus->upah->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="upah"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->upah) ?>',2);"><div id="elh_t_rumus_upah" class="t_rumus_upah">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->upah->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->upah->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->upah->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
	<?php if ($t_rumus->SortUrl($t_rumus->premi_hadir) == "") { ?>
		<th data-name="premi_hadir"><div id="elh_t_rumus_premi_hadir" class="t_rumus_premi_hadir"><div class="ewTableHeaderCaption"><?php echo $t_rumus->premi_hadir->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="premi_hadir"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->premi_hadir) ?>',2);"><div id="elh_t_rumus_premi_hadir" class="t_rumus_premi_hadir">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->premi_hadir->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->premi_hadir->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->premi_hadir->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
	<?php if ($t_rumus->SortUrl($t_rumus->premi_malam) == "") { ?>
		<th data-name="premi_malam"><div id="elh_t_rumus_premi_malam" class="t_rumus_premi_malam"><div class="ewTableHeaderCaption"><?php echo $t_rumus->premi_malam->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="premi_malam"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->premi_malam) ?>',2);"><div id="elh_t_rumus_premi_malam" class="t_rumus_premi_malam">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->premi_malam->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->premi_malam->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->premi_malam->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
	<?php if ($t_rumus->SortUrl($t_rumus->pot_absen) == "") { ?>
		<th data-name="pot_absen"><div id="elh_t_rumus_pot_absen" class="t_rumus_pot_absen"><div class="ewTableHeaderCaption"><?php echo $t_rumus->pot_absen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pot_absen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->pot_absen) ?>',2);"><div id="elh_t_rumus_pot_absen" class="t_rumus_pot_absen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->pot_absen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->pot_absen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->pot_absen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_rumus->lembur->Visible) { // lembur ?>
	<?php if ($t_rumus->SortUrl($t_rumus->lembur) == "") { ?>
		<th data-name="lembur"><div id="elh_t_rumus_lembur" class="t_rumus_lembur"><div class="ewTableHeaderCaption"><?php echo $t_rumus->lembur->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lembur"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_rumus->SortUrl($t_rumus->lembur) ?>',2);"><div id="elh_t_rumus_lembur" class="t_rumus_lembur">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_rumus->lembur->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_rumus->lembur->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_rumus->lembur->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_rumus_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($t_rumus->CurrentAction == "add" || $t_rumus->CurrentAction == "copy") {
		$t_rumus_list->RowIndex = 0;
		$t_rumus_list->KeyCount = $t_rumus_list->RowIndex;
		if ($t_rumus->CurrentAction == "copy" && !$t_rumus_list->LoadRow())
				$t_rumus->CurrentAction = "add";
		if ($t_rumus->CurrentAction == "add")
			$t_rumus_list->LoadDefaultValues();
		if ($t_rumus->EventCancelled) // Insert failed
			$t_rumus_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_rumus->ResetAttrs();
		$t_rumus->RowAttrs = array_merge($t_rumus->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_rumus', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_rumus->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_rumus_list->RenderRow();

		// Render list options
		$t_rumus_list->RenderListOptions();
		$t_rumus_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_rumus->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus_list->ListOptions->Render("body", "left", $t_rumus_list->RowCnt);
?>
	<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
		<td data-name="rumus_nama">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_rumus_nama" class="form-group t_rumus_rumus_nama">
<input type="text" data-table="t_rumus" data-field="x_rumus_nama" name="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->getPlaceHolder()) ?>" value="<?php echo $t_rumus->rumus_nama->EditValue ?>"<?php echo $t_rumus->rumus_nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_rumus_nama" name="o<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="o<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" value="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
		<td data-name="hk_gol">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_gol" class="form-group t_rumus_hk_gol">
<div id="tp_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" class="ewTemplate"><input type="radio" data-table="t_rumus" data-field="x_hk_gol" data-value-separator="<?php echo $t_rumus->hk_gol->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="{value}"<?php echo $t_rumus->hk_gol->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_rumus->hk_gol->RadioButtonListHtml(FALSE, "x{$t_rumus_list->RowIndex}_hk_gol") ?>
</div></div>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_hk_gol" name="o<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="o<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="<?php echo ew_HtmlEncode($t_rumus->hk_gol->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->umr->Visible) { // umr ?>
		<td data-name="umr">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_umr" class="form-group t_rumus_umr">
<input type="text" data-table="t_rumus" data-field="x_umr" name="x<?php echo $t_rumus_list->RowIndex ?>_umr" id="x<?php echo $t_rumus_list->RowIndex ?>_umr" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->umr->getPlaceHolder()) ?>" value="<?php echo $t_rumus->umr->EditValue ?>"<?php echo $t_rumus->umr->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_umr" name="o<?php echo $t_rumus_list->RowIndex ?>_umr" id="o<?php echo $t_rumus_list->RowIndex ?>_umr" value="<?php echo ew_HtmlEncode($t_rumus->umr->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
		<td data-name="hk_jml">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_jml" class="form-group t_rumus_hk_jml">
<input type="text" data-table="t_rumus" data-field="x_hk_jml" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->hk_jml->getPlaceHolder()) ?>" value="<?php echo $t_rumus->hk_jml->EditValue ?>"<?php echo $t_rumus->hk_jml->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_hk_jml" name="o<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="o<?php echo $t_rumus_list->RowIndex ?>_hk_jml" value="<?php echo ew_HtmlEncode($t_rumus->hk_jml->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->upah->Visible) { // upah ?>
		<td data-name="upah">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_upah" class="form-group t_rumus_upah">
<input type="text" data-table="t_rumus" data-field="x_upah" name="x<?php echo $t_rumus_list->RowIndex ?>_upah" id="x<?php echo $t_rumus_list->RowIndex ?>_upah" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->upah->getPlaceHolder()) ?>" value="<?php echo $t_rumus->upah->EditValue ?>"<?php echo $t_rumus->upah->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_upah" name="o<?php echo $t_rumus_list->RowIndex ?>_upah" id="o<?php echo $t_rumus_list->RowIndex ?>_upah" value="<?php echo ew_HtmlEncode($t_rumus->upah->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
		<td data-name="premi_hadir">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_hadir" class="form-group t_rumus_premi_hadir">
<input type="text" data-table="t_rumus" data-field="x_premi_hadir" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_hadir->EditValue ?>"<?php echo $t_rumus->premi_hadir->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_premi_hadir" name="o<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="o<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" value="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
		<td data-name="premi_malam">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_malam" class="form-group t_rumus_premi_malam">
<input type="text" data-table="t_rumus" data-field="x_premi_malam" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_malam->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_malam->EditValue ?>"<?php echo $t_rumus->premi_malam->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_premi_malam" name="o<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="o<?php echo $t_rumus_list->RowIndex ?>_premi_malam" value="<?php echo ew_HtmlEncode($t_rumus->premi_malam->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
		<td data-name="pot_absen">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_pot_absen" class="form-group t_rumus_pot_absen">
<input type="text" data-table="t_rumus" data-field="x_pot_absen" name="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->pot_absen->getPlaceHolder()) ?>" value="<?php echo $t_rumus->pot_absen->EditValue ?>"<?php echo $t_rumus->pot_absen->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_pot_absen" name="o<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="o<?php echo $t_rumus_list->RowIndex ?>_pot_absen" value="<?php echo ew_HtmlEncode($t_rumus->pot_absen->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->lembur->Visible) { // lembur ?>
		<td data-name="lembur">
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_lembur" class="form-group t_rumus_lembur">
<input type="text" data-table="t_rumus" data-field="x_lembur" name="x<?php echo $t_rumus_list->RowIndex ?>_lembur" id="x<?php echo $t_rumus_list->RowIndex ?>_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->lembur->getPlaceHolder()) ?>" value="<?php echo $t_rumus->lembur->EditValue ?>"<?php echo $t_rumus->lembur->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_lembur" name="o<?php echo $t_rumus_list->RowIndex ?>_lembur" id="o<?php echo $t_rumus_list->RowIndex ?>_lembur" value="<?php echo ew_HtmlEncode($t_rumus->lembur->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus_list->ListOptions->Render("body", "right", $t_rumus_list->RowCnt);
?>
<script type="text/javascript">
ft_rumuslist.UpdateOpts(<?php echo $t_rumus_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($t_rumus->ExportAll && $t_rumus->Export <> "") {
	$t_rumus_list->StopRec = $t_rumus_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_rumus_list->TotalRecs > $t_rumus_list->StartRec + $t_rumus_list->DisplayRecs - 1)
		$t_rumus_list->StopRec = $t_rumus_list->StartRec + $t_rumus_list->DisplayRecs - 1;
	else
		$t_rumus_list->StopRec = $t_rumus_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_rumus_list->FormKeyCountName) && ($t_rumus->CurrentAction == "gridadd" || $t_rumus->CurrentAction == "gridedit" || $t_rumus->CurrentAction == "F")) {
		$t_rumus_list->KeyCount = $objForm->GetValue($t_rumus_list->FormKeyCountName);
		$t_rumus_list->StopRec = $t_rumus_list->StartRec + $t_rumus_list->KeyCount - 1;
	}
}
$t_rumus_list->RecCnt = $t_rumus_list->StartRec - 1;
if ($t_rumus_list->Recordset && !$t_rumus_list->Recordset->EOF) {
	$t_rumus_list->Recordset->MoveFirst();
	$bSelectLimit = $t_rumus_list->UseSelectLimit;
	if (!$bSelectLimit && $t_rumus_list->StartRec > 1)
		$t_rumus_list->Recordset->Move($t_rumus_list->StartRec - 1);
} elseif (!$t_rumus->AllowAddDeleteRow && $t_rumus_list->StopRec == 0) {
	$t_rumus_list->StopRec = $t_rumus->GridAddRowCount;
}

// Initialize aggregate
$t_rumus->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_rumus->ResetAttrs();
$t_rumus_list->RenderRow();
$t_rumus_list->EditRowCnt = 0;
if ($t_rumus->CurrentAction == "edit")
	$t_rumus_list->RowIndex = 1;
if ($t_rumus->CurrentAction == "gridadd")
	$t_rumus_list->RowIndex = 0;
if ($t_rumus->CurrentAction == "gridedit")
	$t_rumus_list->RowIndex = 0;
while ($t_rumus_list->RecCnt < $t_rumus_list->StopRec) {
	$t_rumus_list->RecCnt++;
	if (intval($t_rumus_list->RecCnt) >= intval($t_rumus_list->StartRec)) {
		$t_rumus_list->RowCnt++;
		if ($t_rumus->CurrentAction == "gridadd" || $t_rumus->CurrentAction == "gridedit" || $t_rumus->CurrentAction == "F") {
			$t_rumus_list->RowIndex++;
			$objForm->Index = $t_rumus_list->RowIndex;
			if ($objForm->HasValue($t_rumus_list->FormActionName))
				$t_rumus_list->RowAction = strval($objForm->GetValue($t_rumus_list->FormActionName));
			elseif ($t_rumus->CurrentAction == "gridadd")
				$t_rumus_list->RowAction = "insert";
			else
				$t_rumus_list->RowAction = "";
		}

		// Set up key count
		$t_rumus_list->KeyCount = $t_rumus_list->RowIndex;

		// Init row class and style
		$t_rumus->ResetAttrs();
		$t_rumus->CssClass = "";
		if ($t_rumus->CurrentAction == "gridadd") {
			$t_rumus_list->LoadDefaultValues(); // Load default values
		} else {
			$t_rumus_list->LoadRowValues($t_rumus_list->Recordset); // Load row values
		}
		$t_rumus->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_rumus->CurrentAction == "gridadd") // Grid add
			$t_rumus->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_rumus->CurrentAction == "gridadd" && $t_rumus->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_rumus_list->RestoreCurrentRowFormValues($t_rumus_list->RowIndex); // Restore form values
		if ($t_rumus->CurrentAction == "edit") {
			if ($t_rumus_list->CheckInlineEditKey() && $t_rumus_list->EditRowCnt == 0) { // Inline edit
				$t_rumus->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_rumus->CurrentAction == "gridedit") { // Grid edit
			if ($t_rumus->EventCancelled) {
				$t_rumus_list->RestoreCurrentRowFormValues($t_rumus_list->RowIndex); // Restore form values
			}
			if ($t_rumus_list->RowAction == "insert")
				$t_rumus->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_rumus->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_rumus->CurrentAction == "edit" && $t_rumus->RowType == EW_ROWTYPE_EDIT && $t_rumus->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_rumus_list->RestoreFormValues(); // Restore form values
		}
		if ($t_rumus->CurrentAction == "gridedit" && ($t_rumus->RowType == EW_ROWTYPE_EDIT || $t_rumus->RowType == EW_ROWTYPE_ADD) && $t_rumus->EventCancelled) // Update failed
			$t_rumus_list->RestoreCurrentRowFormValues($t_rumus_list->RowIndex); // Restore form values
		if ($t_rumus->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_rumus_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_rumus->RowAttrs = array_merge($t_rumus->RowAttrs, array('data-rowindex'=>$t_rumus_list->RowCnt, 'id'=>'r' . $t_rumus_list->RowCnt . '_t_rumus', 'data-rowtype'=>$t_rumus->RowType));

		// Render row
		$t_rumus_list->RenderRow();

		// Render list options
		$t_rumus_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_rumus_list->RowAction <> "delete" && $t_rumus_list->RowAction <> "insertdelete" && !($t_rumus_list->RowAction == "insert" && $t_rumus->CurrentAction == "F" && $t_rumus_list->EmptyRow())) {
?>
	<tr<?php echo $t_rumus->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus_list->ListOptions->Render("body", "left", $t_rumus_list->RowCnt);
?>
	<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
		<td data-name="rumus_nama"<?php echo $t_rumus->rumus_nama->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_rumus_nama" class="form-group t_rumus_rumus_nama">
<input type="text" data-table="t_rumus" data-field="x_rumus_nama" name="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->getPlaceHolder()) ?>" value="<?php echo $t_rumus->rumus_nama->EditValue ?>"<?php echo $t_rumus->rumus_nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_rumus_nama" name="o<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="o<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" value="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_rumus_nama" class="form-group t_rumus_rumus_nama">
<input type="text" data-table="t_rumus" data-field="x_rumus_nama" name="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->getPlaceHolder()) ?>" value="<?php echo $t_rumus->rumus_nama->EditValue ?>"<?php echo $t_rumus->rumus_nama->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_rumus_nama" class="t_rumus_rumus_nama">
<span<?php echo $t_rumus->rumus_nama->ViewAttributes() ?>>
<?php echo $t_rumus->rumus_nama->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $t_rumus_list->PageObjName . "_row_" . $t_rumus_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_rumus" data-field="x_rumus_id" name="x<?php echo $t_rumus_list->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_list->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus->rumus_id->CurrentValue) ?>">
<input type="hidden" data-table="t_rumus" data-field="x_rumus_id" name="o<?php echo $t_rumus_list->RowIndex ?>_rumus_id" id="o<?php echo $t_rumus_list->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus->rumus_id->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT || $t_rumus->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_rumus" data-field="x_rumus_id" name="x<?php echo $t_rumus_list->RowIndex ?>_rumus_id" id="x<?php echo $t_rumus_list->RowIndex ?>_rumus_id" value="<?php echo ew_HtmlEncode($t_rumus->rumus_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
		<td data-name="hk_gol"<?php echo $t_rumus->hk_gol->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_gol" class="form-group t_rumus_hk_gol">
<div id="tp_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" class="ewTemplate"><input type="radio" data-table="t_rumus" data-field="x_hk_gol" data-value-separator="<?php echo $t_rumus->hk_gol->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="{value}"<?php echo $t_rumus->hk_gol->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_rumus->hk_gol->RadioButtonListHtml(FALSE, "x{$t_rumus_list->RowIndex}_hk_gol") ?>
</div></div>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_hk_gol" name="o<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="o<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="<?php echo ew_HtmlEncode($t_rumus->hk_gol->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_gol" class="form-group t_rumus_hk_gol">
<div id="tp_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" class="ewTemplate"><input type="radio" data-table="t_rumus" data-field="x_hk_gol" data-value-separator="<?php echo $t_rumus->hk_gol->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="{value}"<?php echo $t_rumus->hk_gol->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_rumus->hk_gol->RadioButtonListHtml(FALSE, "x{$t_rumus_list->RowIndex}_hk_gol") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_gol" class="t_rumus_hk_gol">
<span<?php echo $t_rumus->hk_gol->ViewAttributes() ?>>
<?php echo $t_rumus->hk_gol->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->umr->Visible) { // umr ?>
		<td data-name="umr"<?php echo $t_rumus->umr->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_umr" class="form-group t_rumus_umr">
<input type="text" data-table="t_rumus" data-field="x_umr" name="x<?php echo $t_rumus_list->RowIndex ?>_umr" id="x<?php echo $t_rumus_list->RowIndex ?>_umr" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->umr->getPlaceHolder()) ?>" value="<?php echo $t_rumus->umr->EditValue ?>"<?php echo $t_rumus->umr->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_umr" name="o<?php echo $t_rumus_list->RowIndex ?>_umr" id="o<?php echo $t_rumus_list->RowIndex ?>_umr" value="<?php echo ew_HtmlEncode($t_rumus->umr->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_umr" class="form-group t_rumus_umr">
<input type="text" data-table="t_rumus" data-field="x_umr" name="x<?php echo $t_rumus_list->RowIndex ?>_umr" id="x<?php echo $t_rumus_list->RowIndex ?>_umr" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->umr->getPlaceHolder()) ?>" value="<?php echo $t_rumus->umr->EditValue ?>"<?php echo $t_rumus->umr->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_umr" class="t_rumus_umr">
<span<?php echo $t_rumus->umr->ViewAttributes() ?>>
<?php echo $t_rumus->umr->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
		<td data-name="hk_jml"<?php echo $t_rumus->hk_jml->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_jml" class="form-group t_rumus_hk_jml">
<input type="text" data-table="t_rumus" data-field="x_hk_jml" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->hk_jml->getPlaceHolder()) ?>" value="<?php echo $t_rumus->hk_jml->EditValue ?>"<?php echo $t_rumus->hk_jml->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_hk_jml" name="o<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="o<?php echo $t_rumus_list->RowIndex ?>_hk_jml" value="<?php echo ew_HtmlEncode($t_rumus->hk_jml->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_jml" class="form-group t_rumus_hk_jml">
<input type="text" data-table="t_rumus" data-field="x_hk_jml" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->hk_jml->getPlaceHolder()) ?>" value="<?php echo $t_rumus->hk_jml->EditValue ?>"<?php echo $t_rumus->hk_jml->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_hk_jml" class="t_rumus_hk_jml">
<span<?php echo $t_rumus->hk_jml->ViewAttributes() ?>>
<?php echo $t_rumus->hk_jml->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->upah->Visible) { // upah ?>
		<td data-name="upah"<?php echo $t_rumus->upah->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_upah" class="form-group t_rumus_upah">
<input type="text" data-table="t_rumus" data-field="x_upah" name="x<?php echo $t_rumus_list->RowIndex ?>_upah" id="x<?php echo $t_rumus_list->RowIndex ?>_upah" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->upah->getPlaceHolder()) ?>" value="<?php echo $t_rumus->upah->EditValue ?>"<?php echo $t_rumus->upah->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_upah" name="o<?php echo $t_rumus_list->RowIndex ?>_upah" id="o<?php echo $t_rumus_list->RowIndex ?>_upah" value="<?php echo ew_HtmlEncode($t_rumus->upah->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_upah" class="form-group t_rumus_upah">
<input type="text" data-table="t_rumus" data-field="x_upah" name="x<?php echo $t_rumus_list->RowIndex ?>_upah" id="x<?php echo $t_rumus_list->RowIndex ?>_upah" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->upah->getPlaceHolder()) ?>" value="<?php echo $t_rumus->upah->EditValue ?>"<?php echo $t_rumus->upah->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_upah" class="t_rumus_upah">
<span<?php echo $t_rumus->upah->ViewAttributes() ?>>
<?php echo $t_rumus->upah->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
		<td data-name="premi_hadir"<?php echo $t_rumus->premi_hadir->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_hadir" class="form-group t_rumus_premi_hadir">
<input type="text" data-table="t_rumus" data-field="x_premi_hadir" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_hadir->EditValue ?>"<?php echo $t_rumus->premi_hadir->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_premi_hadir" name="o<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="o<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" value="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_hadir" class="form-group t_rumus_premi_hadir">
<input type="text" data-table="t_rumus" data-field="x_premi_hadir" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_hadir->EditValue ?>"<?php echo $t_rumus->premi_hadir->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_hadir" class="t_rumus_premi_hadir">
<span<?php echo $t_rumus->premi_hadir->ViewAttributes() ?>>
<?php echo $t_rumus->premi_hadir->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
		<td data-name="premi_malam"<?php echo $t_rumus->premi_malam->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_malam" class="form-group t_rumus_premi_malam">
<input type="text" data-table="t_rumus" data-field="x_premi_malam" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_malam->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_malam->EditValue ?>"<?php echo $t_rumus->premi_malam->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_premi_malam" name="o<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="o<?php echo $t_rumus_list->RowIndex ?>_premi_malam" value="<?php echo ew_HtmlEncode($t_rumus->premi_malam->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_malam" class="form-group t_rumus_premi_malam">
<input type="text" data-table="t_rumus" data-field="x_premi_malam" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_malam->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_malam->EditValue ?>"<?php echo $t_rumus->premi_malam->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_premi_malam" class="t_rumus_premi_malam">
<span<?php echo $t_rumus->premi_malam->ViewAttributes() ?>>
<?php echo $t_rumus->premi_malam->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
		<td data-name="pot_absen"<?php echo $t_rumus->pot_absen->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_pot_absen" class="form-group t_rumus_pot_absen">
<input type="text" data-table="t_rumus" data-field="x_pot_absen" name="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->pot_absen->getPlaceHolder()) ?>" value="<?php echo $t_rumus->pot_absen->EditValue ?>"<?php echo $t_rumus->pot_absen->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_pot_absen" name="o<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="o<?php echo $t_rumus_list->RowIndex ?>_pot_absen" value="<?php echo ew_HtmlEncode($t_rumus->pot_absen->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_pot_absen" class="form-group t_rumus_pot_absen">
<input type="text" data-table="t_rumus" data-field="x_pot_absen" name="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->pot_absen->getPlaceHolder()) ?>" value="<?php echo $t_rumus->pot_absen->EditValue ?>"<?php echo $t_rumus->pot_absen->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_pot_absen" class="t_rumus_pot_absen">
<span<?php echo $t_rumus->pot_absen->ViewAttributes() ?>>
<?php echo $t_rumus->pot_absen->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_rumus->lembur->Visible) { // lembur ?>
		<td data-name="lembur"<?php echo $t_rumus->lembur->CellAttributes() ?>>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_lembur" class="form-group t_rumus_lembur">
<input type="text" data-table="t_rumus" data-field="x_lembur" name="x<?php echo $t_rumus_list->RowIndex ?>_lembur" id="x<?php echo $t_rumus_list->RowIndex ?>_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->lembur->getPlaceHolder()) ?>" value="<?php echo $t_rumus->lembur->EditValue ?>"<?php echo $t_rumus->lembur->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_lembur" name="o<?php echo $t_rumus_list->RowIndex ?>_lembur" id="o<?php echo $t_rumus_list->RowIndex ?>_lembur" value="<?php echo ew_HtmlEncode($t_rumus->lembur->OldValue) ?>">
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_lembur" class="form-group t_rumus_lembur">
<input type="text" data-table="t_rumus" data-field="x_lembur" name="x<?php echo $t_rumus_list->RowIndex ?>_lembur" id="x<?php echo $t_rumus_list->RowIndex ?>_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->lembur->getPlaceHolder()) ?>" value="<?php echo $t_rumus->lembur->EditValue ?>"<?php echo $t_rumus->lembur->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_rumus->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_rumus_list->RowCnt ?>_t_rumus_lembur" class="t_rumus_lembur">
<span<?php echo $t_rumus->lembur->ViewAttributes() ?>>
<?php echo $t_rumus->lembur->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus_list->ListOptions->Render("body", "right", $t_rumus_list->RowCnt);
?>
	</tr>
<?php if ($t_rumus->RowType == EW_ROWTYPE_ADD || $t_rumus->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_rumuslist.UpdateOpts(<?php echo $t_rumus_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_rumus->CurrentAction <> "gridadd")
		if (!$t_rumus_list->Recordset->EOF) $t_rumus_list->Recordset->MoveNext();
}
?>
<?php
	if ($t_rumus->CurrentAction == "gridadd" || $t_rumus->CurrentAction == "gridedit") {
		$t_rumus_list->RowIndex = '$rowindex$';
		$t_rumus_list->LoadDefaultValues();

		// Set row properties
		$t_rumus->ResetAttrs();
		$t_rumus->RowAttrs = array_merge($t_rumus->RowAttrs, array('data-rowindex'=>$t_rumus_list->RowIndex, 'id'=>'r0_t_rumus', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_rumus->RowAttrs["class"], "ewTemplate");
		$t_rumus->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_rumus_list->RenderRow();

		// Render list options
		$t_rumus_list->RenderListOptions();
		$t_rumus_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_rumus->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_rumus_list->ListOptions->Render("body", "left", $t_rumus_list->RowIndex);
?>
	<?php if ($t_rumus->rumus_nama->Visible) { // rumus_nama ?>
		<td data-name="rumus_nama">
<span id="el$rowindex$_t_rumus_rumus_nama" class="form-group t_rumus_rumus_nama">
<input type="text" data-table="t_rumus" data-field="x_rumus_nama" name="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="x<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->getPlaceHolder()) ?>" value="<?php echo $t_rumus->rumus_nama->EditValue ?>"<?php echo $t_rumus->rumus_nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_rumus_nama" name="o<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" id="o<?php echo $t_rumus_list->RowIndex ?>_rumus_nama" value="<?php echo ew_HtmlEncode($t_rumus->rumus_nama->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->hk_gol->Visible) { // hk_gol ?>
		<td data-name="hk_gol">
<span id="el$rowindex$_t_rumus_hk_gol" class="form-group t_rumus_hk_gol">
<div id="tp_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" class="ewTemplate"><input type="radio" data-table="t_rumus" data-field="x_hk_gol" data-value-separator="<?php echo $t_rumus->hk_gol->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="{value}"<?php echo $t_rumus->hk_gol->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_rumus_list->RowIndex ?>_hk_gol" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_rumus->hk_gol->RadioButtonListHtml(FALSE, "x{$t_rumus_list->RowIndex}_hk_gol") ?>
</div></div>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_hk_gol" name="o<?php echo $t_rumus_list->RowIndex ?>_hk_gol" id="o<?php echo $t_rumus_list->RowIndex ?>_hk_gol" value="<?php echo ew_HtmlEncode($t_rumus->hk_gol->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->umr->Visible) { // umr ?>
		<td data-name="umr">
<span id="el$rowindex$_t_rumus_umr" class="form-group t_rumus_umr">
<input type="text" data-table="t_rumus" data-field="x_umr" name="x<?php echo $t_rumus_list->RowIndex ?>_umr" id="x<?php echo $t_rumus_list->RowIndex ?>_umr" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->umr->getPlaceHolder()) ?>" value="<?php echo $t_rumus->umr->EditValue ?>"<?php echo $t_rumus->umr->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_umr" name="o<?php echo $t_rumus_list->RowIndex ?>_umr" id="o<?php echo $t_rumus_list->RowIndex ?>_umr" value="<?php echo ew_HtmlEncode($t_rumus->umr->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->hk_jml->Visible) { // hk_jml ?>
		<td data-name="hk_jml">
<span id="el$rowindex$_t_rumus_hk_jml" class="form-group t_rumus_hk_jml">
<input type="text" data-table="t_rumus" data-field="x_hk_jml" name="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="x<?php echo $t_rumus_list->RowIndex ?>_hk_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->hk_jml->getPlaceHolder()) ?>" value="<?php echo $t_rumus->hk_jml->EditValue ?>"<?php echo $t_rumus->hk_jml->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_hk_jml" name="o<?php echo $t_rumus_list->RowIndex ?>_hk_jml" id="o<?php echo $t_rumus_list->RowIndex ?>_hk_jml" value="<?php echo ew_HtmlEncode($t_rumus->hk_jml->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->upah->Visible) { // upah ?>
		<td data-name="upah">
<span id="el$rowindex$_t_rumus_upah" class="form-group t_rumus_upah">
<input type="text" data-table="t_rumus" data-field="x_upah" name="x<?php echo $t_rumus_list->RowIndex ?>_upah" id="x<?php echo $t_rumus_list->RowIndex ?>_upah" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->upah->getPlaceHolder()) ?>" value="<?php echo $t_rumus->upah->EditValue ?>"<?php echo $t_rumus->upah->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_upah" name="o<?php echo $t_rumus_list->RowIndex ?>_upah" id="o<?php echo $t_rumus_list->RowIndex ?>_upah" value="<?php echo ew_HtmlEncode($t_rumus->upah->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->premi_hadir->Visible) { // premi_hadir ?>
		<td data-name="premi_hadir">
<span id="el$rowindex$_t_rumus_premi_hadir" class="form-group t_rumus_premi_hadir">
<input type="text" data-table="t_rumus" data-field="x_premi_hadir" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_hadir->EditValue ?>"<?php echo $t_rumus->premi_hadir->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_premi_hadir" name="o<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" id="o<?php echo $t_rumus_list->RowIndex ?>_premi_hadir" value="<?php echo ew_HtmlEncode($t_rumus->premi_hadir->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->premi_malam->Visible) { // premi_malam ?>
		<td data-name="premi_malam">
<span id="el$rowindex$_t_rumus_premi_malam" class="form-group t_rumus_premi_malam">
<input type="text" data-table="t_rumus" data-field="x_premi_malam" name="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="x<?php echo $t_rumus_list->RowIndex ?>_premi_malam" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->premi_malam->getPlaceHolder()) ?>" value="<?php echo $t_rumus->premi_malam->EditValue ?>"<?php echo $t_rumus->premi_malam->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_premi_malam" name="o<?php echo $t_rumus_list->RowIndex ?>_premi_malam" id="o<?php echo $t_rumus_list->RowIndex ?>_premi_malam" value="<?php echo ew_HtmlEncode($t_rumus->premi_malam->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->pot_absen->Visible) { // pot_absen ?>
		<td data-name="pot_absen">
<span id="el$rowindex$_t_rumus_pot_absen" class="form-group t_rumus_pot_absen">
<input type="text" data-table="t_rumus" data-field="x_pot_absen" name="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="x<?php echo $t_rumus_list->RowIndex ?>_pot_absen" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->pot_absen->getPlaceHolder()) ?>" value="<?php echo $t_rumus->pot_absen->EditValue ?>"<?php echo $t_rumus->pot_absen->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_pot_absen" name="o<?php echo $t_rumus_list->RowIndex ?>_pot_absen" id="o<?php echo $t_rumus_list->RowIndex ?>_pot_absen" value="<?php echo ew_HtmlEncode($t_rumus->pot_absen->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_rumus->lembur->Visible) { // lembur ?>
		<td data-name="lembur">
<span id="el$rowindex$_t_rumus_lembur" class="form-group t_rumus_lembur">
<input type="text" data-table="t_rumus" data-field="x_lembur" name="x<?php echo $t_rumus_list->RowIndex ?>_lembur" id="x<?php echo $t_rumus_list->RowIndex ?>_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($t_rumus->lembur->getPlaceHolder()) ?>" value="<?php echo $t_rumus->lembur->EditValue ?>"<?php echo $t_rumus->lembur->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_rumus" data-field="x_lembur" name="o<?php echo $t_rumus_list->RowIndex ?>_lembur" id="o<?php echo $t_rumus_list->RowIndex ?>_lembur" value="<?php echo ew_HtmlEncode($t_rumus->lembur->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_rumus_list->ListOptions->Render("body", "right", $t_rumus_list->RowCnt);
?>
<script type="text/javascript">
ft_rumuslist.UpdateOpts(<?php echo $t_rumus_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_rumus->CurrentAction == "add" || $t_rumus->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_rumus_list->FormKeyCountName ?>" id="<?php echo $t_rumus_list->FormKeyCountName ?>" value="<?php echo $t_rumus_list->KeyCount ?>">
<?php } ?>
<?php if ($t_rumus->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_rumus_list->FormKeyCountName ?>" id="<?php echo $t_rumus_list->FormKeyCountName ?>" value="<?php echo $t_rumus_list->KeyCount ?>">
<?php echo $t_rumus_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_rumus->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_rumus_list->FormKeyCountName ?>" id="<?php echo $t_rumus_list->FormKeyCountName ?>" value="<?php echo $t_rumus_list->KeyCount ?>">
<?php } ?>
<?php if ($t_rumus->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_rumus_list->FormKeyCountName ?>" id="<?php echo $t_rumus_list->FormKeyCountName ?>" value="<?php echo $t_rumus_list->KeyCount ?>">
<?php echo $t_rumus_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_rumus->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_rumus_list->Recordset)
	$t_rumus_list->Recordset->Close();
?>
<?php if ($t_rumus->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($t_rumus->CurrentAction <> "gridadd" && $t_rumus->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_rumus_list->Pager)) $t_rumus_list->Pager = new cPrevNextPager($t_rumus_list->StartRec, $t_rumus_list->DisplayRecs, $t_rumus_list->TotalRecs) ?>
<?php if ($t_rumus_list->Pager->RecordCount > 0 && $t_rumus_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_rumus_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_rumus_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_rumus_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_rumus_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_rumus_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_rumus_list->PageUrl() ?>start=<?php echo $t_rumus_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_rumus_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_rumus_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_rumus_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_rumus_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($t_rumus_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $t_rumus_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="t_rumus">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($t_rumus_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($t_rumus_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($t_rumus_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($t_rumus_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($t_rumus_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($t_rumus->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_rumus_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($t_rumus_list->TotalRecs == 0 && $t_rumus->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_rumus_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_rumus->Export == "") { ?>
<script type="text/javascript">
ft_rumuslistsrch.FilterList = <?php echo $t_rumus_list->GetFilterList() ?>;
ft_rumuslistsrch.Init();
ft_rumuslist.Init();
</script>
<?php } ?>
<?php
$t_rumus_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_rumus->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_rumus_list->Page_Terminate();
?>
