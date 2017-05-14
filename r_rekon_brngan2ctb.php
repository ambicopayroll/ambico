<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "r_rekon_brngan2ctbinfo.php" ?>
<?php

//
// Page class
//

$r_rekon_brngan2_crosstab = NULL; // Initialize page object first

class crr_rekon_brngan2_crosstab extends crr_rekon_brngan2 {

	// Page ID
	var $PageID = 'crosstab';

	// Project ID
	var $ProjectID = "{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}";

	// Page object name
	var $PageObjName = 'r_rekon_brngan2_crosstab';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog ewDisplayTable\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
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
		global $conn, $ReportLanguage;
		global $UserTable, $UserTableConn;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (r_rekon_brngan2)
		if (!isset($GLOBALS["r_rekon_brngan2"])) {
			$GLOBALS["r_rekon_brngan2"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_rekon_brngan2"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'crosstab', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'r_rekon_brngan2', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new crt_user();
			$UserTableConn = ReportConn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fr_rekon_brngan2crosstab";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security;
		global $gsCustomExport;

		// Security
		$Security = new crAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . 'r_rekon_brngan2');
		$Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($ReportLanguage->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$Security->SaveLastUrl();
			$this->setFailureMessage($ReportLanguage->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("login.php"));
		}

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		$this->tgl->PlaceHolder = $this->tgl->FldCaption();

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";

		//$item->Visible = TRUE;
		$item->Visible = TRUE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_r_rekon_brngan2\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_r_rekon_brngan2',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fr_rekon_brngan2crosstab\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fr_rekon_brngan2crosstab\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fr_rekon_brngan2crosstab\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_ConvertFullUrl($saveToFile) : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		 // Close connection
		ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options
	var $GenerateOptions; // Generate report options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $DisplayGrps = 20; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $ColSpan;
	var $GrpIdx;

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $gsFormError;
		global $gbDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Popup values and selections
		$this->tgl->SelectionList = "";
		$this->tgl->DefaultSelectionList = "";
		$this->tgl->ValueList = "";

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Restore filter list
		$this->RestoreFilterList();

		// Extended filter
		$sExtendedFilter = "";

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Load columns to array
		$this->GetColumns();

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Requires search criteria
		if (($this->Filter == $this->UserIDFilter || $gsFormError != "") && !$this->DrillDown)
			$this->Filter = "0=101";

		// Search options
		$this->SetupSearchOptions();

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), "", $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = TRUE;

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
			if ($Security->CanList()) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
			} else {
				$this->setWarningMessage($ReportLanguage->Phrase("NoPermission"));
			}
		}

		// Hide export options if export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown
		if ($this->Export <> "" || $this->DrillDown) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get total groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;

		// Set up column attributes
		$this->tgl->ViewAttrs["style"] = "";
		$this->tgl->CellAttrs["style"] = "vertical-align: top;";
		$this->SetupFieldCount();
	}

	// Get column values
	function GetColumns() {
		global $ReportLanguage;
		$this->LoadColumnValues($this->Filter);

		// Reset summary values
		$this->ResetLevelSummary(0);

		// Set up column search values
		for ($i = 1; $i <= $this->ColCount; $i++) {
			$wrkValue = $this->Col[$i]->Value;
			$wrkCaption = $this->Col[$i]->Caption;
			$this->tgl->ValueList[$wrkValue] = $wrkCaption;
		}

		// Get active columns
		if (!is_array($this->tgl->SelectionList)) {
			$this->ColSpan = $this->ColCount;
		} else {
			$this->ColSpan = 0;
			for ($i = 1; $i <= $this->ColCount; $i++) {
				$bSelected = FALSE;
				$cntsel = count($this->tgl->SelectionList);
				for ($j = 0; $j < $cntsel; $j++) {
					if (ewr_CompareValue($this->tgl->SelectionList[$j], $this->Col[$i]->Value, $this->tgl->FldType)) {
						$this->ColSpan++;
						$bSelected = TRUE;
						break;
					}
				}
				$this->Col[$i]->Visible = $bSelected;
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		$conn = &$this->Connection();
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group recordset
	function GetGrpRs($wrksql, $start = -1, $grps = -1) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

	//		$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->pegawai_nama->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF) {
			$this->pegawai_nama->setDbValue($rsgrp->fields[0]);
		} else {
			$this->pegawai_nama->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			if ($opt <> 1)
				$this->pegawai_nama->setDbValue($rs->fields('pegawai_nama'));
			$cntbase = 1;
			$cnt = count($this->SummaryFields);
			for ($is = 0; $is < $cnt; $is++) {
				$smry = &$this->SummaryFields[$is];
				$cntval = count($smry->SummaryVal);
				for ($ix = 1; $ix < $cntval; $ix++) {
					if ($smry->SummaryType == "AVG") {
						$smry->SummaryVal[$ix] = $rs->fields[$ix*2+$cntbase-2];
						$smry->SummaryValCnt[$ix] = $rs->fields[$ix*2+$cntbase-1];
					} else {
						$smry->SummaryVal[$ix] = $rs->fields[$ix+$cntbase-1];
					}
				}
				$cntbase += ($smry->SummaryType == "AVG") ? 2*($cntval-1) : ($cntval-1);
			}
		} else {
			$this->pegawai_nama->setDbValue("");
		}
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->pegawai_nama->CurrentValue) && !is_null($this->pegawai_nama->OldValue)) ||
					(!is_null($this->pegawai_nama->CurrentValue) && is_null($this->pegawai_nama->OldValue)) ||
					($this->pegawai_nama->GroupValue() <> $this->pegawai_nama->GroupOldValue());
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cnt = count($this->SummaryFields);
		for ($is = 0; $is < $cnt; $is++) {
			$smry = &$this->SummaryFields[$is];
			$cntx = count($smry->SummarySmry);
			for ($ix = 1; $ix < $cntx; $ix++) {
				$cnty = count($smry->SummarySmry[$ix]);
				for ($iy = 0; $iy < $cnty; $iy++) {
					$valwrk = $smry->SummaryVal[$ix];
					$smry->SummaryCnt[$ix][$iy]++;
					$smry->SummarySmry[$ix][$iy] = ewr_SummaryValue($smry->SummarySmry[$ix][$iy], $valwrk, $smry->SummaryType);
					if ($smry->SummaryType == "AVG") {
						$cntwrk = $smry->SummaryValCnt[$ix];
						$smry->SummarySmryCnt[$ix][$iy] += $cntwrk;
					}
				}
			}
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cnt = count($this->SummaryFields);
		for ($is = 0; $is < $cnt; $is++) {
			$smry = &$this->SummaryFields[$is];
			$cntx = count($smry->SummarySmry);
			for ($ix = 1; $ix < $cntx; $ix++) {
				$cnty = count($smry->SummarySmry[$ix]);
				for ($iy = $lvl; $iy < $cnty; $iy++) {
					$smry->SummaryCnt[$ix][$iy] = 0;
					$smry->SummarySmry[$ix][$iy] = $smry->SummaryInitValue;
					if ($smry->SummaryType == "AVG") {
						$smry->SummarySmryCnt[$ix][$iy] = 0;
					}
				}
			}
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Output data as Json

			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$_SESSION["sel_r_rekon_brngan2_tgl"] = "";
				$_SESSION["rf_r_rekon_brngan2_tgl"] = "";
				$_SESSION["rt_r_rekon_brngan2_tgl"] = "";
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		if (is_array(@$_SESSION["sel_r_rekon_brngan2_tgl"])) {
			$this->tgl->SelectionList = @$_SESSION["sel_r_rekon_brngan2_tgl"];
			$this->tgl->RangeFrom = @$_SESSION["rf_r_rekon_brngan2_tgl"];
			$this->tgl->RangeTo = @$_SESSION["rt_r_rekon_brngan2_tgl"];
		} elseif (@$_SESSION["sel_r_rekon_brngan2_tgl"] == EWR_INIT_VALUE) { // Select all
			$this->tgl->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 20; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 20; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $Security, $ReportLanguage;
		$conn = &$this->Connection();

		// Set up summary values
		$colcnt = $this->ColCount;
		$this->SummaryCellAttrs = &ewr_InitArray($colcnt, NULL);
		$this->SummaryViewAttrs = &ewr_InitArray($colcnt, NULL);
		$this->SummaryLinkAttrs = &ewr_InitArray($colcnt, NULL);
		$this->SummaryCurrentValue = &ewr_InitArray($colcnt, NULL);
		$this->SummaryViewValue = &ewr_InitArray($colcnt, NULL);
		$cnt = count($this->SummaryFields);
		for ($is = 0; $is < $cnt; $is++) {
			$smry = &$this->SummaryFields[$is];
			$smry->SummaryViewAttrs = &ewr_InitArray($colcnt, NULL);
			$smry->SummaryLinkAttrs = &ewr_InitArray($colcnt, NULL);
			$smry->SummaryCurrentValue = &ewr_InitArray($colcnt, NULL);
			$smry->SummaryViewValue = &ewr_InitArray($colcnt, NULL);
		}
		if ($this->RowTotalType == EWR_ROWTOTAL_GRAND) { // Grand total

			// Aggregate SQL
			$sSql = ewr_BuildReportSql(str_replace("<DistinctColumnFields>", $this->DistinctColumnFields, $this->getSqlSelectAgg()), $this->getSqlWhere(), $this->getSqlGroupByAgg(), "", "", $this->Filter, "");
			$rsagg = $conn->Execute($sSql);
			if ($rsagg && !$rsagg->EOF) $rsagg->MoveFirst();
		}
		for ($i = 1; $i <= $this->ColCount; $i++) {
			if ($this->Col[$i]->Visible) {
				$cntbaseagg = 0;
				$cnt = count($this->SummaryFields);
				for ($is = 0; $is < $cnt; $is++) {
					$smry = &$this->SummaryFields[$is];
					if ($this->RowType == EWR_ROWTYPE_DETAIL) { // Detail row
						$thisval = $smry->SummaryVal[$i];
						if ($smry->SummaryType == "AVG")
							$thiscnt = $smry->SummaryValCnt[$i];
					} elseif ($this->RowTotalType == EWR_ROWTOTAL_GROUP) { // Group total
						$thisval = $smry->SummarySmry[$i][$this->RowGroupLevel];
						if ($smry->SummaryType == "AVG")
							$thiscnt = $smry->SummarySmryCnt[$i][$this->RowGroupLevel];
					} elseif ($this->RowTotalType == EWR_ROWTOTAL_PAGE) { // Page total
						$thisval = $smry->SummarySmry[$i][0];
						if ($smry->SummaryType == "AVG")
							$thiscnt = $smry->SummarySmryCnt[$i][0];
					} elseif ($this->RowTotalType == EWR_ROWTOTAL_GRAND) { // Grand total
						if ($smry->SummaryType == "AVG") {
							$thisval = ($rsagg && !$rsagg->EOF) ? $rsagg->fields[$i*2+$cntbaseagg-2] : 0;
							$thiscnt = ($rsagg && !$rsagg->EOF) ? $rsagg->fields[$i*2+$cntbaseagg-1] : 0;
							$cntbaseagg += $this->ColCount * 2;
						} else {
							$thisval = ($rsagg && !$rsagg->EOF) ? $rsagg->fields[$i+$cntbaseagg-1] : 0;
							$cntbaseagg += $this->ColCount;
						}
					}
					if ($smry->SummaryType == "AVG")
						$smry->SummaryCurrentValue[$i-1] = ($thiscnt > 0) ? $thisval / $thiscnt : 0;
					else
						$smry->SummaryCurrentValue[$i-1] = $thisval;
				}
			}
		}
		if ($this->RowTotalType == EWR_ROWTOTAL_GRAND) { // Grand total
			if ($rsagg) $rsagg->Close();
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// pegawai_nama
			$this->pegawai_nama->GroupViewValue = $this->pegawai_nama->GroupOldValue();
			$this->pegawai_nama->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";

			// Set up summary values
			$smry = &$this->SummaryFields[0];
			$scvcnt = count($smry->SummaryCurrentValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$smry->SummaryViewValue[$i] = ewr_FormatDateTime($smry->SummaryCurrentValue[$i], 17);
				$smry->SummaryViewAttrs[$i]["style"] = "";
				$this->SummaryCellAttrs[$i]["class"] = ($this->RowTotalType == EWR_ROWTOTAL_GROUP) ? "ewRptGrpSummary" . $this->RowGroupLevel : "";
			}

			// Set up summary values
			$smry = &$this->SummaryFields[1];
			$scvcnt = count($smry->SummaryCurrentValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$smry->SummaryViewValue[$i] = ewr_FormatDateTime($smry->SummaryCurrentValue[$i], 17);
				$smry->SummaryViewAttrs[$i]["style"] = "";
				$this->SummaryCellAttrs[$i]["class"] = ($this->RowTotalType == EWR_ROWTOTAL_GROUP) ? "ewRptGrpSummary" . $this->RowGroupLevel : "";
			}

			// pegawai_nama
			$this->pegawai_nama->HrefValue = "";
		} else {

			// pegawai_nama
			$this->pegawai_nama->GroupViewValue = $this->pegawai_nama->GroupValue();
			$this->pegawai_nama->CellAttrs["class"] = "ewRptGrpField1";
			if ($this->pegawai_nama->GroupValue() == $this->pegawai_nama->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->pegawai_nama->GroupViewValue = "&nbsp;";

			// Set up summary values
			$smry = &$this->SummaryFields[0];
			$scvcnt = count($smry->SummaryCurrentValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$smry->SummaryViewValue[$i] = ewr_FormatDateTime($smry->SummaryCurrentValue[$i], 17);
				$smry->SummaryViewAttrs[$i]["style"] = "";
				$this->SummaryCellAttrs[$i]["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			}

			// Set up summary values
			$smry = &$this->SummaryFields[1];
			$scvcnt = count($smry->SummaryCurrentValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$smry->SummaryViewValue[$i] = ewr_FormatDateTime($smry->SummaryCurrentValue[$i], 17);
				$smry->SummaryViewAttrs[$i]["style"] = "";
				$this->SummaryCellAttrs[$i]["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			}

			// pegawai_nama
			$this->pegawai_nama->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// pegawai_nama
			$this->CurrentIndex = 0; // Current index
			$CurrentValue = $this->pegawai_nama->GroupOldValue();
			$ViewValue = &$this->pegawai_nama->GroupViewValue;
			$ViewAttrs = &$this->pegawai_nama->ViewAttrs;
			$CellAttrs = &$this->pegawai_nama->CellAttrs;
			$HrefValue = &$this->pegawai_nama->HrefValue;
			$LinkAttrs = &$this->pegawai_nama->LinkAttrs;
			$this->Cell_Rendered($this->pegawai_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
			for ($i = 0; $i < $scvcnt; $i++) {
				$this->CurrentIndex = $i;
				$cnt = count($this->SummaryFields);
				for ($is = 0; $is < $cnt; $is++) {
					$smry = &$this->SummaryFields[$is];
					$CurrentValue = $smry->SummaryCurrentValue[$i];
					$ViewValue = &$smry->SummaryViewValue[$i];
					$ViewAttrs = &$smry->SummaryViewAttrs[$i];
					$CellAttrs = &$this->SummaryCellAttrs[$i];
					$HrefValue = "";
					$LinkAttrs = &$smry->SummaryLinkAttrs[$i];
					$this->Cell_Rendered($smry, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
				}
			}
		} else {

			// pegawai_nama
			$this->CurrentIndex = 0; // Group index
			$CurrentValue = $this->pegawai_nama->GroupValue();
			$ViewValue = &$this->pegawai_nama->GroupViewValue;
			$ViewAttrs = &$this->pegawai_nama->ViewAttrs;
			$CellAttrs = &$this->pegawai_nama->CellAttrs;
			$HrefValue = &$this->pegawai_nama->HrefValue;
			$LinkAttrs = &$this->pegawai_nama->LinkAttrs;
			$this->Cell_Rendered($this->pegawai_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
			for ($i = 0; $i < $scvcnt; $i++) {
				$this->CurrentIndex = $i;
				$cnt = count($this->SummaryFields);
				for ($is = 0; $is < $cnt; $is++) {
					$smry = &$this->SummaryFields[$is];
					$CurrentValue = $smry->SummaryCurrentValue[$i];
					$ViewValue = &$smry->SummaryViewValue[$i];
					$ViewAttrs = &$smry->SummaryViewAttrs[$i];
					$CellAttrs = &$this->SummaryCellAttrs[$i];
					$HrefValue = "";
					$LinkAttrs = &$smry->SummaryLinkAttrs[$i];
					$this->Cell_Rendered($smry, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
				}
			}
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		if ($this->pegawai_nama->Visible) $this->GrpColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("crosstab", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $gsFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

			// Clear extended filter for field tgl
			if ($this->ClearExtFilter == 'r_rekon_brngan2_tgl')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'tgl');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->pegawai_nama->DropDownValue, $this->pegawai_nama->SearchOperator, 'pegawai_nama'); // Field pegawai_nama
			$this->SetSessionFilterValues($this->tgl->SearchValue, $this->tgl->SearchOperator, $this->tgl->SearchCondition, $this->tgl->SearchValue2, $this->tgl->SearchOperator2, 'tgl'); // Field tgl

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field pegawai_nama
			if ($this->GetDropDownValue($this->pegawai_nama)) {
				$bSetupFilter = TRUE;
			} elseif ($this->pegawai_nama->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_r_rekon_brngan2_pegawai_nama'])) {
				$bSetupFilter = TRUE;
			}

			// Field tgl
			if ($this->GetFilterValues($this->tgl)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->pegawai_nama); // Field pegawai_nama
			$this->GetSessionFilterValues($this->tgl); // Field tgl
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->pegawai_nama, $sFilter, $this->pegawai_nama->SearchOperator, FALSE, TRUE); // Field pegawai_nama
		$this->BuildExtendedFilter($this->tgl, $sFilter, FALSE, TRUE); // Field tgl

		// Save parms to session
		$this->SetSessionDropDownValue($this->pegawai_nama->DropDownValue, $this->pegawai_nama->SearchOperator, 'pegawai_nama'); // Field pegawai_nama
		$this->SetSessionFilterValues($this->tgl->SearchValue, $this->tgl->SearchOperator, $this->tgl->SearchCondition, $this->tgl->SearchValue2, $this->tgl->SearchOperator2, 'tgl'); // Field tgl

		// Setup filter
		if ($bSetupFilter) {

			// Field tgl
			$sWrk = "";
			$this->BuildExtendedFilter($this->tgl, $sWrk);
			ewr_LoadSelectionFromFilter($this->tgl, $sWrk, $this->tgl->SelectionList);
			$_SESSION['sel_r_rekon_brngan2_tgl'] = ($this->tgl->SelectionList == "") ? EWR_INIT_VALUE : $this->tgl->SelectionList;
		}

		// Field pegawai_nama
		ewr_LoadDropDownList($this->pegawai_nama->DropDownList, $this->pegawai_nama->DropDownValue);
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@") $this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@") $this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		if ($FldOpr == "") $FldOpr = "=";
		$sWrk = "";
		if (ewr_SameStr($FldVal, EWR_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal, EWR_NOT_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif (ewr_SameStr($FldVal, EWR_EMPTY_VALUE)) {
			$sWrk = $FldExpression . " = ''";
		} elseif (ewr_SameStr($FldVal, EWR_ALL_VALUE)) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal, $this->DBID);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "" && ($FldDataType == EWR_DATATYPE_STRING || $FldDataType == EWR_DATATYPE_MEMO)) {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal), $this->DBID);
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType, $this->DBID);
					} else {
						$sWrk = ewr_FilterString($FldOpr, $FldVal, $FldDataType, $this->DBID);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal, $dbid = 0) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID,0,2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld, $dbid);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default, $this->DBID);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["so_$parm"]))
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
		if (isset($_GET["sv_$parm"])) {
			$fld->DropDownValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewr_StripSlashes(@$_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewr_StripSlashes(@$_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewr_StripSlashes($_GET["so2_$parm"]);
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_r_rekon_brngan2_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_rekon_brngan2_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_r_rekon_brngan2_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_rekon_brngan2_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_r_rekon_brngan2_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_r_rekon_brngan2_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_r_rekon_brngan2_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_r_rekon_brngan2_' . $parm] = $sv;
		$_SESSION['so_r_rekon_brngan2_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_r_rekon_brngan2_' . $parm] = $sv1;
		$_SESSION['so_r_rekon_brngan2_' . $parm] = $so1;
		$_SESSION['sc_r_rekon_brngan2_' . $parm] = $sc;
		$_SESSION['sv2_r_rekon_brngan2_' . $parm] = $sv2;
		$_SESSION['so2_r_rekon_brngan2_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ewr_CheckDateDef($this->tgl->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br>";
			$gsFormError .= $this->tgl->FldErrMsg();
		}
		if (!ewr_CheckDateDef($this->tgl->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br>";
			$gsFormError .= $this->tgl->FldErrMsg();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<p>&nbsp;</p>" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_r_rekon_brngan2_$parm"] = "";
		$_SESSION["rf_r_rekon_brngan2_$parm"] = "";
		$_SESSION["rt_r_rekon_brngan2_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_r_rekon_brngan2_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_r_rekon_brngan2_$parm"];
		$fld->RangeTo = @$_SESSION["rt_r_rekon_brngan2_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field pegawai_nama
		$this->pegawai_nama->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->pegawai_nama->DropDownValue = $this->pegawai_nama->DefaultDropDownValue;
		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		// Field tgl
		$this->SetDefaultExtFilter($this->tgl, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->tgl);
		$sWrk = "";
		$this->BuildExtendedFilter($this->tgl, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->tgl, $sWrk, $this->tgl->DefaultSelectionList);
		if (!$this->SearchCommand) $this->tgl->SelectionList = $this->tgl->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field tgl
		// $this->tgl->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check pegawai_nama extended filter
		if ($this->NonTextFilterApplied($this->pegawai_nama))
			return TRUE;

		// Check tgl text filter
		if ($this->TextFilterApplied($this->tgl))
			return TRUE;

		// Check tgl popup filter
		if (!ewr_MatchedArray($this->tgl->DefaultSelectionList, $this->tgl->SelectionList))
			return TRUE;

		// Check tgl popup filter
		if (!ewr_MatchedArray($this->tgl->DefaultSelectionList, $this->tgl->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field pegawai_nama
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->pegawai_nama, $sExtWrk, $this->pegawai_nama->SearchOperator);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->pegawai_nama->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tgl
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->tgl, $sExtWrk);
		if (is_array($this->tgl->SelectionList))
			$sWrk = ewr_JoinArray($this->tgl->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tgl->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "" || $showDate) {
			$sMessage = "<div" . $divstyle . $divdataclass . "><div id=\"ewrFilterList\" class=\"alert alert-info ewDisplayTable\">";
			if ($showDate)
				$sMessage .= "<div id=\"ewrCurrentDate\">" . $ReportLanguage->Phrase("ReportGeneratedDate") . ewr_FormatDateTime(date("Y-m-d H:i:s"), 1) . "</div>";
			if ($sFilterList <> "")
				$sMessage .= "<div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$sMessage .= "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field pegawai_nama
		$sWrk = "";
		$sWrk = ($this->pegawai_nama->DropDownValue <> EWR_INIT_VALUE) ? $this->pegawai_nama->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_pegawai_nama\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tgl
		$sWrk = "";
		if ($this->tgl->SearchValue <> "" || $this->tgl->SearchValue2 <> "") {
			$sWrk = "\"sv_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchValue) . "\"," .
				"\"so_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchOperator) . "\"," .
				"\"sc_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchCondition) . "\"," .
				"\"sv2_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchValue2) . "\"," .
				"\"so2_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->tgl->SelectionList <> EWR_INIT_VALUE) ? $this->tgl->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tgl\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ewr_StripSlashes(@$_POST["filter"]), TRUE);
		return $this->SetupFilterList($filter);
	}

	// Setup list of filters
	function SetupFilterList($filter) {
		if (!is_array($filter))
			return FALSE;

		// Field pegawai_nama
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_pegawai_nama", $filter)) {
			$sWrk = $filter["sv_pegawai_nama"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_pegawai_nama"], "pegawai_nama");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "pegawai_nama");
		}

		// Field tgl
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tgl", $filter) || array_key_exists("so_tgl", $filter) ||
			array_key_exists("sc_tgl", $filter) ||
			array_key_exists("sv2_tgl", $filter) || array_key_exists("so2_tgl", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_tgl"], @$filter["so_tgl"], @$filter["sc_tgl"], @$filter["sv2_tgl"], @$filter["so2_tgl"], "tgl");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_tgl", $filter)) {
			$sWrk = $filter["sel_tgl"];
			$sWrk = explode("||", $sWrk);
			$this->tgl->SelectionList = $sWrk;
			$_SESSION["sel_r_rekon_brngan2_tgl"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tgl"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tgl");
			$this->tgl->SelectionList = "";
			$_SESSION["sel_r_rekon_brngan2_tgl"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->ExtendedFilterExist($this->tgl)) {
			if (is_array($this->tgl->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->tgl, "`tgl`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tgl, $sFilter, "popup");
				$this->tgl->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : ewr_StripSlashes(@$_GET["order"]);
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : ewr_StripSlashes(@$_GET["ordertype"]);

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->pegawai_nama->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->pegawai_nama, $bCtrl); // pegawai_nama
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent, $options = array()) {
		global $gTmpImages, $ReportLanguage;
		$bGenRequest = @$options["reporttype"] == "email";
		$sFailRespPfx = $bGenRequest ? "" : "<p class=\"text-error\">";
		$sSuccessRespPfx = $bGenRequest ? "" : "<p class=\"text-success\">";
		$sRespPfx = $bGenRequest ? "" : "</p>";
		$sContentType = (@$options["contenttype"] <> "") ? $options["contenttype"] : @$_POST["contenttype"];
		$sSender = (@$options["sender"] <> "") ? $options["sender"] : @$_POST["sender"];
		$sRecipient = (@$options["recipient"] <> "") ? $options["recipient"] : @$_POST["recipient"];
		$sCc = (@$options["cc"] <> "") ? $options["cc"] : @$_POST["cc"];
		$sBcc = (@$options["bcc"] <> "") ? $options["bcc"] : @$_POST["bcc"];

		// Subject
		$sEmailSubject = (@$options["subject"] <> "") ? $options["subject"] : ewr_StripSlashes(@$_POST["subject"]);

		// Message
		$sEmailMessage = (@$options["message"] <> "") ? $options["message"] : ewr_StripSlashes(@$_POST["message"]);

		// Check sender
		if ($sSender == "")
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterSenderEmail") . $sRespPfx;
		if (!ewr_CheckEmail($sSender))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperSenderEmail") . $sRespPfx;

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperRecipientEmail") . $sRespPfx;

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperCcEmail") . $sRespPfx;

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperBccEmail") . $sRespPfx;

		// Check email sent count
		$emailcount = $bGenRequest ? 0 : ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT)
			return $sFailRespPfx . $ReportLanguage->Phrase("ExceedMaxEmailExport") . $sRespPfx;
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = ewr_AdjustEmailContent($EmailContent);
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // Send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
			$sEmailMessage .= $sAttachmentContent;
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		if ($sAttachmentFile <> "")
			$Email->AddAttachment($sAttachmentFile, $sAttachmentContent);
		if ($sContentType <> "url") {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
		ewr_DeleteTmpImages($EmailContent);

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			return $sSuccessRespPfx . $ReportLanguage->Phrase("SendEmailSuccess") . $sRespPfx; // Set up success message
		} else {

			// Sent email failure
			return $sFailRespPfx . $Email->SendErrDescription . $sRespPfx;
		}
	}

	// Export to HTML
	function ExportHtml($html, $options = array()) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');

		$folder = @$this->GenOptions["folder"];
		$fileName = @$this->GenOptions["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";

		// Save generate file for print
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			$baseTag = "<base href=\"" . ewr_BaseUrl() . "\">";
			$html = preg_replace('/<head>/', '<head>' . $baseTag, $html);
			ewr_SaveFile($folder, $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file")
			echo $html;
		return $saveToFile;
	}

	// Export to WORD
	function ExportWord($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
			echo $html;
		}
		return $saveToFile;
	}

	// Export to EXCEL
	function ExportExcel($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
			echo $html;
		}
		return $saveToFile;
	}

	// Export PDF
	function ExportPdf($html, $options = array()) {
		global $gsExportFile;
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		if (EWR_DEBUG_ENABLED) // Add debug message
			$html = str_replace("</body>", ewr_DebugMsg() . "</body>", $html);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="uft-8">' . ewr_ConvertToUtf8($html)); // Convert to utf-8
		$spans = $doc->getElementsByTagName("span");
		foreach ($spans as $span) {
			if ($span->getAttribute("class") == "ewFilterCaption")
				$span->parentNode->insertBefore($doc->createElement("span", ":&nbsp;"), $span->nextSibling);
		}
		$html = $doc->saveHTML();
		$html = ewr_ConvertFromUtf8($html);
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $dompdf->output());
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			$sExportFile = strtolower(substr($gsExportFile, -4)) == ".pdf" ? $gsExportFile : $gsExportFile . ".pdf";
			$dompdf->stream($sExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		}
		ewr_DeleteTmpImages($html);
		return $saveToFile;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
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
<?php ewr_Header(FALSE) ?>
<?php

// Create page object
if (!isset($r_rekon_brngan2_crosstab)) $r_rekon_brngan2_crosstab = new crr_rekon_brngan2_crosstab();
if (isset($Page)) $OldPage = $Page;
$Page = &$r_rekon_brngan2_crosstab;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var r_rekon_brngan2_crosstab = new ewr_Page("r_rekon_brngan2_crosstab");

// Page properties
r_rekon_brngan2_crosstab.PageID = "crosstab"; // Page ID
var EWR_PAGE_ID = r_rekon_brngan2_crosstab.PageID;

// Extend page with Chart_Rendering function
r_rekon_brngan2_crosstab.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
r_rekon_brngan2_crosstab.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fr_rekon_brngan2crosstab = new ewr_Form("fr_rekon_brngan2crosstab");

// Validate method
fr_rekon_brngan2crosstab.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_tgl;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->tgl->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_tgl;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->tgl->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fr_rekon_brngan2crosstab.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fr_rekon_brngan2crosstab.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fr_rekon_brngan2crosstab.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fr_rekon_brngan2crosstab.Lists["sv_pegawai_nama"] = {"LinkField":"sv_pegawai_nama","Ajax":true,"DisplayFields":["sv_pegawai_nama","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">
<?php $jsdb = ewr_GetJsDb($Page->tgl, $Page->tgl->FldType); ?>
ewr_CreatePopup("r_rekon_brngan2_tgl", <?php echo $jsdb ?>); // Popup filters
</script>
<?php } ?>
<?php if ($Page->Export == "") { ?>
<!-- container (begin) -->
<div id="ewContainer" class="ewContainer">
<!-- top container (begin) -->
<div id="ewTop" class="ewTop">
<a id="top"></a>
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<!-- top slot -->
<div class="ewToolbar">
<?php if ($Page->Export == "" && (!$Page->DrillDown || !$Page->DrillDownInPanel)) { ?>
<?php if ($ReportBreadcrumb) $ReportBreadcrumb->Render(); ?>
<?php } ?>
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<?php echo $ReportLanguage->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "") { ?>
</div>
<!-- Top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
<?php } ?>
	<!-- left slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- left container (end) -->
	<!-- center container (report) (begin) -->
	<div id="ewCenter" class="ewCenter">
<?php } ?>
	<!-- center slot -->
<!-- crosstab report starts -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_crosstab">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<!-- Search form (begin) -->
<form name="fr_rekon_brngan2crosstab" id="fr_rekon_brngan2crosstab" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fr_rekon_brngan2crosstab_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_pegawai_nama" class="ewCell form-group">
	<label for="sv_pegawai_nama" class="ewSearchCaption ewLabel"><?php echo $Page->pegawai_nama->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->pegawai_nama->EditAttrs["class"], "form-control"); ?>
<select data-table="r_rekon_brngan2" data-field="x_pegawai_nama" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->pegawai_nama->DisplayValueSeparator) ? json_encode($Page->pegawai_nama->DisplayValueSeparator) : $Page->pegawai_nama->DisplayValueSeparator) ?>" id="sv_pegawai_nama" name="sv_pegawai_nama"<?php echo $Page->pegawai_nama->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->pegawai_nama->AdvancedFilters) ? count($Page->pegawai_nama->AdvancedFilters) : 0;
	$cntd = is_array($Page->pegawai_nama->DropDownList) ? count($Page->pegawai_nama->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->pegawai_nama->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->pegawai_nama->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->pegawai_nama->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->pegawai_nama->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_pegawai_nama" id="s_sv_pegawai_nama" value="<?php echo $Page->pegawai_nama->LookupFilterQuery() ?>"></span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_tgl" class="ewCell form-group">
	<label for="sv_tgl" class="ewSearchCaption ewLabel"><?php echo $Page->tgl->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_tgl" id="so_tgl" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->tgl->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="r_rekon_brngan2" data-field="x_tgl" id="sv_tgl" name="sv_tgl" placeholder="<?php echo $Page->tgl->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tgl->SearchValue) ?>" data-calendar="true" data-formatid="0"<?php echo $Page->tgl->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_tgl"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_tgl">
<?php ewr_PrependClass($Page->tgl->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="r_rekon_brngan2" data-field="x_tgl" id="sv2_tgl" name="sv2_tgl" placeholder="<?php echo $Page->tgl->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tgl->SearchValue2) ?>" data-calendar="true" data-formatid="0"<?php echo $Page->tgl->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fr_rekon_brngan2crosstab.Init();
fr_rekon_brngan2crosstab.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps)) {
	$Page->StopGrp = $Page->TotalGrps;
}

// Navigate
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCount = 1;
}
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show header
	if ($Page->ShowHeader) {
?>
<?php if ($Page->GrpCount > 1) { ?>
</tbody>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_rekon_brngan2ctbpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php echo $Page->PageBreakContent ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "r_rekon_brngan2ctbpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td class="ewRptColSummary" colspan="<?php echo $Page->GrpColumnCount ?>"><div><?php echo $Page->RenderSummaryCaptions() ?></div></td>
<?php } ?>
		<td class="ewRptColHeader" colspan="<?php echo @$Page->ColSpan ?>">
			<div class="ewTableHeaderBtn">
				<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
				<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'r_rekon_brngan2_tgl', false, '<?php echo $Page->tgl->RangeFrom ?>', '<?php echo $Page->tgl->RangeTo ?>');" name="x_tgl" id="x_tgl"><span class="icon-filter"></span></a>
<?php } ?>
			</div>
		</td>
	</tr>
	<tr class="ewTableHeader">
<?php if ($Page->pegawai_nama->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="pegawai_nama">
		<div class="r_rekon_brngan2_pegawai_nama"><span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span></div>
	</td>
<?php } else { ?>
	<td data-field="pegawai_nama">
<?php if ($Page->SortUrl($Page->pegawai_nama) == "") { ?>
		<div class="ewTableHeaderBtn r_rekon_brngan2_pegawai_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span>			
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekon_brngan2_pegawai_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pegawai_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pegawai_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pegawai_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<!-- Dynamic columns begin -->
<?php
	$cntval = count($Page->Col);
	for ($iy = 1; $iy < $cntval; $iy++) {
		if ($Page->Col[$iy]->Visible) {
			$Page->SummaryCurrentValue[$iy-1] = $Page->Col[$iy]->Caption;
			$Page->SummaryViewValue[$iy-1] = tgl_indo($Page->SummaryCurrentValue[$iy-1]);
?>
		<td class="ewTableHeader"<?php echo $Page->tgl->CellAttributes() ?>><div<?php echo $Page->tgl->ViewAttributes() ?>><?php echo $Page->SummaryViewValue[$iy-1]; ?></div></td>
<?php
		}
	}
?>
<!-- Dynamic columns end -->
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewr_DetailFilterSQL($Page->pegawai_nama, $Page->getSqlFirstGroupField(), $Page->pegawai_nama->GroupValue(), $Page->DBID);
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql(str_replace("<DistinctColumnFields>", $Page->DistinctColumnFields, $Page->getSqlSelect()), $Page->getSqlWhere(), $Page->getSqlGroupBy(), "", $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	while ($rs && !$rs->EOF) {
		$Page->RecCount++;
		$Page->RecIndex++;

		// Render row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->pegawai_nama->Visible) { ?>
		<!-- pegawai_nama -->
		<td data-field="pegawai_nama"<?php echo $Page->pegawai_nama->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_rekon_brngan2_pegawai_nama"<?php echo $Page->pegawai_nama->ViewAttributes() ?>><?php echo $Page->pegawai_nama->GroupViewValue ?></span></td>
<?php } ?>
<!-- Dynamic columns begin -->
<?php
		$cntcol = count($Page->SummaryViewValue);
		for ($iy = 1; $iy <= $cntcol; $iy++) {
			$bColShow = ($iy <= $Page->ColCount) ? $Page->Col[$iy]->Visible : TRUE;
			$sColDesc = ($iy <= $Page->ColCount) ? $Page->Col[$iy]->Caption : $ReportLanguage->Phrase("Summary");
			if ($bColShow) {
?>
		<!-- <?php echo $sColDesc; ?> -->
		<td<?php echo $Page->SummaryCellAttributes($iy-1) ?>><?php echo $Page->RenderSummaryFields($iy-1) ?></td>
<?php
			}
		}
?>
<!-- Dynamic columns end -->
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
?>
<?php
	} // End detail records loop
?>
<?php
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
}
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
</tfoot>
<?php } elseif (!$Page->ShowHeader && TRUE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "r_rekon_brngan2ctbpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_rekon_brngan2ctbpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Crosstab report ends -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- center container (report) (end) -->
	<!-- right container (begin) -->
	<div id="ewRight" class="ewRight">
<?php } ?>
	<!-- Right slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- right container (end) -->
<div class="clearfix"></div>
<!-- bottom container (begin) -->
<div id="ewBottom" class="ewBottom">
<?php } ?>
	<!-- Bottom slot -->
<?php if ($Page->Export == "") { ?>
	</div>
<!-- Bottom container (end) -->
</div>
<!-- container (end) -->
<?php } ?>
<?php $Page->ShowPageFooter(); ?>
<?php if (EWR_DEBUG_ENABLED) echo ewr_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
