<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "r_laplembursmryinfo.php" ?>
<?php

//
// Page class
//

$r_laplembur_summary = NULL; // Initialize page object first

class crr_laplembur_summary extends crr_laplembur {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}";

	// Page object name
	var $PageObjName = 'r_laplembur_summary';

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

		// Table object (r_laplembur)
		if (!isset($GLOBALS["r_laplembur"])) {
			$GLOBALS["r_laplembur"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_laplembur"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'r_laplembur', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fr_laplembursummary";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'r_laplembur');
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
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_r_laplembur\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_r_laplembur',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fr_laplembursummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fr_laplembursummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fr_laplembursummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = FALSE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = FALSE && $this->FilterApplied;

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

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
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
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();

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

		// Set field visibility for detail fields
		$this->no->SetVisibility();
		$this->nama->SetVisibility();
		$this->nip->SetVisibility();
		$this->jml_jam->SetVisibility();
		$this->tarif->SetVisibility();
		$this->total_lembur->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 7;
		$nGrps = 3;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// No filter
		$this->FilterApplied = FALSE;
		$this->FilterOptions->GetItem("savecurrentfilter")->Visible = FALSE;
		$this->FilterOptions->GetItem("deletefilter")->Visible = FALSE;

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = ($this->TotalGrps > 0);

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

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Get summary count
	function GetSummaryCount($lvl, $curValue = TRUE) {
		$cnt = 0;
		foreach ($this->DetailRows as $row) {
			$wrkbagian = $row["bagian"];
			$wrkdivisi = $row["divisi"];
			if ($lvl >= 1) {
				$val = $curValue ? $this->bagian->CurrentValue : $this->bagian->OldValue;
				$grpval = $curValue ? $this->bagian->GroupValue() : $this->bagian->GroupOldValue();
				if (is_null($val) && !is_null($wrkbagian) || !is_null($val) && is_null($wrkbagian) ||
					$grpval <> $this->bagian->getGroupValueBase($wrkbagian))
				continue;
			}
			if ($lvl >= 2) {
				$val = $curValue ? $this->divisi->CurrentValue : $this->divisi->OldValue;
				$grpval = $curValue ? $this->divisi->GroupValue() : $this->divisi->GroupOldValue();
				if (is_null($val) && !is_null($wrkdivisi) || !is_null($val) && is_null($wrkdivisi) ||
					$grpval <> $this->divisi->getGroupValueBase($wrkdivisi))
				continue;
			}
			$cnt++;
		}
		return $cnt;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->bagian->CurrentValue) && !is_null($this->bagian->OldValue)) ||
					(!is_null($this->bagian->CurrentValue) && is_null($this->bagian->OldValue)) ||
					($this->bagian->GroupValue() <> $this->bagian->GroupOldValue());
			case 2:
				return (is_null($this->divisi->CurrentValue) && !is_null($this->divisi->OldValue)) ||
					(!is_null($this->divisi->CurrentValue) && is_null($this->divisi->OldValue)) ||
					($this->divisi->GroupValue() <> $this->divisi->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
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

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->bagian->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->bagian->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->bagian->setDbValue("");
		}
	}

	// Get detail recordset
	function GetDetailRs($wrksql) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->Execute($wrksql);
		$dbtype = ewr_GetConnectionType($this->DBID);
		if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL") {
			$this->DetailRows = ($rswrk) ? $rswrk->GetRows() : array();
		} else { // Cannot MoveFirst, use another recordset
			$rstmp = $conn->Execute($wrksql);
			$this->DetailRows = ($rstmp) ? $rstmp->GetRows() : array();
			$rstmp->Close();
		}
		$conn->raiseErrorFn = "";
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
			$rs->MoveFirst(); // Move first
			if ($this->GrpCount == 1) {
				$this->FirstRowData = array();
				$this->FirstRowData['laplembur_id'] = ewr_Conv($rs->fields('laplembur_id'), 3);
				$this->FirstRowData['no'] = ewr_Conv($rs->fields('no'), 3);
				$this->FirstRowData['bagian'] = ewr_Conv($rs->fields('bagian'), 200);
				$this->FirstRowData['divisi'] = ewr_Conv($rs->fields('divisi'), 200);
				$this->FirstRowData['nama'] = ewr_Conv($rs->fields('nama'), 200);
				$this->FirstRowData['nip'] = ewr_Conv($rs->fields('nip'), 200);
				$this->FirstRowData['jml_jam'] = ewr_Conv($rs->fields('jml_jam'), 4);
				$this->FirstRowData['tarif'] = ewr_Conv($rs->fields('tarif'), 4);
				$this->FirstRowData['total_lembur'] = ewr_Conv($rs->fields('total_lembur'), 4);
				$this->FirstRowData['start'] = ewr_Conv($rs->fields('start'), 133);
				$this->FirstRowData['end'] = ewr_Conv($rs->fields('end'), 133);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->laplembur_id->setDbValue($rs->fields('laplembur_id'));
			$this->no->setDbValue($rs->fields('no'));
			if ($opt <> 1) {
				if (is_array($this->bagian->GroupDbValues))
					$this->bagian->setDbValue(@$this->bagian->GroupDbValues[$rs->fields('bagian')]);
				else
					$this->bagian->setDbValue(ewr_GroupValue($this->bagian, $rs->fields('bagian')));
			}
			$this->divisi->setDbValue($rs->fields('divisi'));
			$this->nama->setDbValue($rs->fields('nama'));
			$this->nip->setDbValue($rs->fields('nip'));
			$this->jml_jam->setDbValue($rs->fields('jml_jam'));
			$this->tarif->setDbValue($rs->fields('tarif'));
			$this->total_lembur->setDbValue($rs->fields('total_lembur'));
			$this->start->setDbValue($rs->fields('start'));
			$this->end->setDbValue($rs->fields('end'));
			$this->Val[1] = $this->no->CurrentValue;
			$this->Val[2] = $this->nama->CurrentValue;
			$this->Val[3] = $this->nip->CurrentValue;
			$this->Val[4] = $this->jml_jam->CurrentValue;
			$this->Val[5] = $this->tarif->CurrentValue;
			$this->Val[6] = $this->total_lembur->CurrentValue;
		} else {
			$this->laplembur_id->setDbValue("");
			$this->no->setDbValue("");
			$this->bagian->setDbValue("");
			$this->divisi->setDbValue("");
			$this->nama->setDbValue("");
			$this->nip->setDbValue("");
			$this->jml_jam->setDbValue("");
			$this->tarif->setDbValue("");
			$this->total_lembur->setDbValue("");
			$this->start->setDbValue("");
			$this->end->setDbValue("");
		}
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

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
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
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
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
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectAgg(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$sSql = $this->getSqlAggPfx() . $sSql . $this->getSqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandSmry[6] = $rsagg->fields("sum_total_lembur");
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel); // Set up row class
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP) $this->RowAttrs["data-group"] = $this->bagian->GroupOldValue(); // Set up group attribute
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->divisi->GroupOldValue(); // Set up group attribute 2

			// bagian
			$this->bagian->GroupViewValue = $this->bagian->GroupOldValue();
			$this->bagian->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->bagian->GroupViewValue = ewr_DisplayGroupValue($this->bagian, $this->bagian->GroupViewValue);
			$this->bagian->GroupSummaryOldValue = $this->bagian->GroupSummaryValue;
			$this->bagian->GroupSummaryValue = $this->bagian->GroupViewValue;
			$this->bagian->GroupSummaryViewValue = ($this->bagian->GroupSummaryOldValue <> $this->bagian->GroupSummaryValue) ? $this->bagian->GroupSummaryValue : "&nbsp;";

			// divisi
			$this->divisi->GroupViewValue = $this->divisi->GroupOldValue();
			$this->divisi->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->divisi->GroupViewValue = ewr_DisplayGroupValue($this->divisi, $this->divisi->GroupViewValue);
			$this->divisi->GroupSummaryOldValue = $this->divisi->GroupSummaryValue;
			$this->divisi->GroupSummaryValue = $this->divisi->GroupViewValue;
			$this->divisi->GroupSummaryViewValue = ($this->divisi->GroupSummaryOldValue <> $this->divisi->GroupSummaryValue) ? $this->divisi->GroupSummaryValue : "&nbsp;";

			// total_lembur
			$this->total_lembur->SumViewValue = $this->total_lembur->SumValue;
			$this->total_lembur->SumViewValue = ewr_FormatNumber($this->total_lembur->SumViewValue, 0, -2, -2, -2);
			$this->total_lembur->CellAttrs["style"] = "text-align:right;";
			$this->total_lembur->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// bagian
			$this->bagian->HrefValue = "";

			// divisi
			$this->divisi->HrefValue = "";

			// no
			$this->no->HrefValue = "";

			// nama
			$this->nama->HrefValue = "";

			// nip
			$this->nip->HrefValue = "";

			// jml_jam
			$this->jml_jam->HrefValue = "";

			// tarif
			$this->tarif->HrefValue = "";

			// total_lembur
			$this->total_lembur->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			$this->RowAttrs["data-group"] = $this->bagian->GroupValue(); // Set up group attribute
			if ($this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->divisi->GroupValue(); // Set up group attribute 2
			} else {
			$this->RowAttrs["data-group"] = $this->bagian->GroupValue(); // Set up group attribute
			$this->RowAttrs["data-group-2"] = $this->divisi->GroupValue(); // Set up group attribute 2
			}

			// bagian
			$this->bagian->GroupViewValue = $this->bagian->GroupValue();
			$this->bagian->CellAttrs["class"] = "ewRptGrpField1";
			$this->bagian->GroupViewValue = ewr_DisplayGroupValue($this->bagian, $this->bagian->GroupViewValue);
			if ($this->bagian->GroupValue() == $this->bagian->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->bagian->GroupViewValue = "&nbsp;";

			// divisi
			$this->divisi->GroupViewValue = $this->divisi->GroupValue();
			$this->divisi->CellAttrs["class"] = "ewRptGrpField2";
			$this->divisi->GroupViewValue = ewr_DisplayGroupValue($this->divisi, $this->divisi->GroupViewValue);
			if ($this->divisi->GroupValue() == $this->divisi->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->divisi->GroupViewValue = "&nbsp;";

			// no
			$this->no->ViewValue = $this->no->CurrentValue;
			$this->no->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nama
			$this->nama->ViewValue = $this->nama->CurrentValue;
			$this->nama->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nip
			$this->nip->ViewValue = $this->nip->CurrentValue;
			$this->nip->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// jml_jam
			$this->jml_jam->ViewValue = $this->jml_jam->CurrentValue;
			$this->jml_jam->ViewValue = ewr_FormatNumber($this->jml_jam->ViewValue, 0, -2, -2, -2);
			$this->jml_jam->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->jml_jam->CellAttrs["style"] = "text-align:right;";

			// tarif
			$this->tarif->ViewValue = $this->tarif->CurrentValue;
			$this->tarif->ViewValue = ewr_FormatNumber($this->tarif->ViewValue, 0, -2, -2, -2);
			$this->tarif->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->tarif->CellAttrs["style"] = "text-align:right;";

			// total_lembur
			$this->total_lembur->ViewValue = $this->total_lembur->CurrentValue;
			$this->total_lembur->ViewValue = ewr_FormatNumber($this->total_lembur->ViewValue, 0, -2, -2, -2);
			$this->total_lembur->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->total_lembur->CellAttrs["style"] = "text-align:right;";

			// bagian
			$this->bagian->HrefValue = "";

			// divisi
			$this->divisi->HrefValue = "";

			// no
			$this->no->HrefValue = "";

			// nama
			$this->nama->HrefValue = "";

			// nip
			$this->nip->HrefValue = "";

			// jml_jam
			$this->jml_jam->HrefValue = "";

			// tarif
			$this->tarif->HrefValue = "";

			// total_lembur
			$this->total_lembur->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// bagian
			$CurrentValue = $this->bagian->GroupViewValue;
			$ViewValue = &$this->bagian->GroupViewValue;
			$ViewAttrs = &$this->bagian->ViewAttrs;
			$CellAttrs = &$this->bagian->CellAttrs;
			$HrefValue = &$this->bagian->HrefValue;
			$LinkAttrs = &$this->bagian->LinkAttrs;
			$this->Cell_Rendered($this->bagian, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// divisi
			$CurrentValue = $this->divisi->GroupViewValue;
			$ViewValue = &$this->divisi->GroupViewValue;
			$ViewAttrs = &$this->divisi->ViewAttrs;
			$CellAttrs = &$this->divisi->CellAttrs;
			$HrefValue = &$this->divisi->HrefValue;
			$LinkAttrs = &$this->divisi->LinkAttrs;
			$this->Cell_Rendered($this->divisi, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// total_lembur
			$CurrentValue = $this->total_lembur->SumValue;
			$ViewValue = &$this->total_lembur->SumViewValue;
			$ViewAttrs = &$this->total_lembur->ViewAttrs;
			$CellAttrs = &$this->total_lembur->CellAttrs;
			$HrefValue = &$this->total_lembur->HrefValue;
			$LinkAttrs = &$this->total_lembur->LinkAttrs;
			$this->Cell_Rendered($this->total_lembur, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// bagian
			$CurrentValue = $this->bagian->GroupValue();
			$ViewValue = &$this->bagian->GroupViewValue;
			$ViewAttrs = &$this->bagian->ViewAttrs;
			$CellAttrs = &$this->bagian->CellAttrs;
			$HrefValue = &$this->bagian->HrefValue;
			$LinkAttrs = &$this->bagian->LinkAttrs;
			$this->Cell_Rendered($this->bagian, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// divisi
			$CurrentValue = $this->divisi->GroupValue();
			$ViewValue = &$this->divisi->GroupViewValue;
			$ViewAttrs = &$this->divisi->ViewAttrs;
			$CellAttrs = &$this->divisi->CellAttrs;
			$HrefValue = &$this->divisi->HrefValue;
			$LinkAttrs = &$this->divisi->LinkAttrs;
			$this->Cell_Rendered($this->divisi, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// no
			$CurrentValue = $this->no->CurrentValue;
			$ViewValue = &$this->no->ViewValue;
			$ViewAttrs = &$this->no->ViewAttrs;
			$CellAttrs = &$this->no->CellAttrs;
			$HrefValue = &$this->no->HrefValue;
			$LinkAttrs = &$this->no->LinkAttrs;
			$this->Cell_Rendered($this->no, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nama
			$CurrentValue = $this->nama->CurrentValue;
			$ViewValue = &$this->nama->ViewValue;
			$ViewAttrs = &$this->nama->ViewAttrs;
			$CellAttrs = &$this->nama->CellAttrs;
			$HrefValue = &$this->nama->HrefValue;
			$LinkAttrs = &$this->nama->LinkAttrs;
			$this->Cell_Rendered($this->nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nip
			$CurrentValue = $this->nip->CurrentValue;
			$ViewValue = &$this->nip->ViewValue;
			$ViewAttrs = &$this->nip->ViewAttrs;
			$CellAttrs = &$this->nip->CellAttrs;
			$HrefValue = &$this->nip->HrefValue;
			$LinkAttrs = &$this->nip->LinkAttrs;
			$this->Cell_Rendered($this->nip, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// jml_jam
			$CurrentValue = $this->jml_jam->CurrentValue;
			$ViewValue = &$this->jml_jam->ViewValue;
			$ViewAttrs = &$this->jml_jam->ViewAttrs;
			$CellAttrs = &$this->jml_jam->CellAttrs;
			$HrefValue = &$this->jml_jam->HrefValue;
			$LinkAttrs = &$this->jml_jam->LinkAttrs;
			$this->Cell_Rendered($this->jml_jam, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tarif
			$CurrentValue = $this->tarif->CurrentValue;
			$ViewValue = &$this->tarif->ViewValue;
			$ViewAttrs = &$this->tarif->ViewAttrs;
			$CellAttrs = &$this->tarif->CellAttrs;
			$HrefValue = &$this->tarif->HrefValue;
			$LinkAttrs = &$this->tarif->LinkAttrs;
			$this->Cell_Rendered($this->tarif, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// total_lembur
			$CurrentValue = $this->total_lembur->CurrentValue;
			$ViewValue = &$this->total_lembur->ViewValue;
			$ViewAttrs = &$this->total_lembur->ViewAttrs;
			$CellAttrs = &$this->total_lembur->CellAttrs;
			$HrefValue = &$this->total_lembur->HrefValue;
			$LinkAttrs = &$this->total_lembur->LinkAttrs;
			$this->Cell_Rendered($this->total_lembur, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->bagian->Visible) $this->GrpColumnCount += 1;
		if ($this->divisi->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->no->Visible) $this->DtlColumnCount += 1;
		if ($this->nama->Visible) $this->DtlColumnCount += 1;
		if ($this->nip->Visible) $this->DtlColumnCount += 1;
		if ($this->jml_jam->Visible) $this->DtlColumnCount += 1;
		if ($this->tarif->Visible) $this->DtlColumnCount += 1;
		if ($this->total_lembur->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
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
			$this->bagian->setSort("");
			$this->divisi->setSort("");
			$this->no->setSort("");
			$this->nama->setSort("");
			$this->nip->setSort("");
			$this->jml_jam->setSort("");
			$this->tarif->setSort("");
			$this->total_lembur->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->bagian, $bCtrl); // bagian
			$this->UpdateSort($this->divisi, $bCtrl); // divisi
			$this->UpdateSort($this->no, $bCtrl); // no
			$this->UpdateSort($this->nama, $bCtrl); // nama
			$this->UpdateSort($this->nip, $bCtrl); // nip
			$this->UpdateSort($this->jml_jam, $bCtrl); // jml_jam
			$this->UpdateSort($this->tarif, $bCtrl); // tarif
			$this->UpdateSort($this->total_lembur, $bCtrl); // total_lembur
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

		$MyRow = ewr_ExecuteRow("SELECT start, end FROM t_laplembur limit 1");
		$header = "Laporan Lembur Bulanan<br>Periode ".tgl_indo_header($MyRow["start"])." s.d. ".tgl_indo_header($MyRow["end"])."<br>&nbsp;";
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
if (!isset($r_laplembur_summary)) $r_laplembur_summary = new crr_laplembur_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$r_laplembur_summary;

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
var r_laplembur_summary = new ewr_Page("r_laplembur_summary");

// Page properties
r_laplembur_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = r_laplembur_summary.PageID;

// Extend page with Chart_Rendering function
r_laplembur_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
r_laplembur_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
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
<!-- top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
<?php } ?>
	<!-- Left slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- left container (end) -->
	<!-- center container - report (begin) -->
	<div id="ewCenter" class="ewCenter">
<?php } ?>
	<!-- center slot -->
<!-- summary report starts -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCounter[0] = 1;
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray($Page->StopGrp - $Page->StartGrp + 1, -1);
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
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
<?php include "r_laplembursmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_r_laplembur"><?php echo $Page->PageBreakContent ?></span>
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
<?php include "r_laplembursmrypager.php" ?>
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
<?php if ($Page->bagian->Visible) { ?>
	<?php if ($Page->bagian->ShowGroupHeaderAsRow) { ?>
	<td data-field="bagian">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="bagian"><div class="r_laplembur_bagian"><span class="ewTableHeaderCaption"><?php echo $Page->bagian->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="bagian">
<?php if ($Page->SortUrl($Page->bagian) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_bagian">
			<span class="ewTableHeaderCaption"><?php echo $Page->bagian->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_bagian" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->bagian) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->bagian->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->bagian->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->bagian->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->divisi->Visible) { ?>
	<?php if ($Page->divisi->ShowGroupHeaderAsRow) { ?>
	<td data-field="divisi">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="divisi"><div class="r_laplembur_divisi"><span class="ewTableHeaderCaption"><?php echo $Page->divisi->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="divisi">
<?php if ($Page->SortUrl($Page->divisi) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_divisi">
			<span class="ewTableHeaderCaption"><?php echo $Page->divisi->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_divisi" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->divisi) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->divisi->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->divisi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->divisi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="no"><div class="r_laplembur_no"><span class="ewTableHeaderCaption"><?php echo $Page->no->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="no">
<?php if ($Page->SortUrl($Page->no) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_no">
			<span class="ewTableHeaderCaption"><?php echo $Page->no->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_no" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->no) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->no->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->no->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->no->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nama"><div class="r_laplembur_nama"><span class="ewTableHeaderCaption"><?php echo $Page->nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nama">
<?php if ($Page->SortUrl($Page->nama) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nip"><div class="r_laplembur_nip"><span class="ewTableHeaderCaption"><?php echo $Page->nip->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nip">
<?php if ($Page->SortUrl($Page->nip) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_nip">
			<span class="ewTableHeaderCaption"><?php echo $Page->nip->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_nip" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nip) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nip->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="jml_jam"><div class="r_laplembur_jml_jam" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->jml_jam->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="jml_jam">
<?php if ($Page->SortUrl($Page->jml_jam) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_jml_jam" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->jml_jam->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_jml_jam" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->jml_jam) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->jml_jam->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->jml_jam->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->jml_jam->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tarif"><div class="r_laplembur_tarif" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->tarif->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tarif">
<?php if ($Page->SortUrl($Page->tarif) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_tarif" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tarif->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_tarif" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tarif) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tarif->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tarif->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tarif->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="total_lembur"><div class="r_laplembur_total_lembur" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->total_lembur->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="total_lembur">
<?php if ($Page->SortUrl($Page->total_lembur) == "") { ?>
		<div class="ewTableHeaderBtn r_laplembur_total_lembur" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->total_lembur->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_laplembur_total_lembur" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->total_lembur) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->total_lembur->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->total_lembur->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->total_lembur->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewr_DetailFilterSQL($Page->bagian, $Page->getSqlFirstGroupField(), $Page->bagian->GroupValue(), $Page->DBID);
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql($Page->getSqlSelect(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $Page->GetDetailRs($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	$Page->GrpIdx[$Page->GrpCount] = array(-1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;
?>
<?php if ($Page->bagian->Visible && $Page->ChkLvlBreak(1) && $Page->bagian->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 1;
		$Page->bagian->Count = $Page->GetSummaryCount(1);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="bagian" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->bagian->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_laplembur_bagian"><span class="ewTableHeaderCaption"><?php echo $Page->bagian->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->bagian) == "") { ?>
		<span class="ewSummaryCaption r_laplembur_bagian">
			<span class="ewTableHeaderCaption"><?php echo $Page->bagian->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_laplembur_bagian" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->bagian) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->bagian->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->bagian->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->bagian->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_laplembur_bagian"<?php echo $Page->bagian->ViewAttributes() ?>><?php echo $Page->bagian->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->bagian->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->divisi->Visible && $Page->ChkLvlBreak(2) && $Page->divisi->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 2;
		$Page->divisi->Count = $Page->GetSummaryCount(2);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->divisi->Visible) { ?>
		<td data-field="divisi"<?php echo $Page->divisi->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="divisi" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 2) ?>"<?php echo $Page->divisi->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_laplembur_divisi"><span class="ewTableHeaderCaption"><?php echo $Page->divisi->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->divisi) == "") { ?>
		<span class="ewSummaryCaption r_laplembur_divisi">
			<span class="ewTableHeaderCaption"><?php echo $Page->divisi->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_laplembur_divisi" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->divisi) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->divisi->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->divisi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->divisi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_laplembur_divisi"<?php echo $Page->divisi->ViewAttributes() ?>><?php echo $Page->divisi->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->divisi->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
	<?php if ($Page->bagian->ShowGroupHeaderAsRow) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_laplembur_bagian"<?php echo $Page->bagian->ViewAttributes() ?>><?php echo $Page->bagian->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->divisi->Visible) { ?>
	<?php if ($Page->divisi->ShowGroupHeaderAsRow) { ?>
		<td data-field="divisi"<?php echo $Page->divisi->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="divisi"<?php echo $Page->divisi->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_laplembur_divisi"<?php echo $Page->divisi->ViewAttributes() ?>><?php echo $Page->divisi->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->no->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_laplembur_no"<?php echo $Page->no->ViewAttributes() ?>><?php echo $Page->no->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_laplembur_nama"<?php echo $Page->nama->ViewAttributes() ?>><?php echo $Page->nama->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->nip->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_laplembur_nip"<?php echo $Page->nip->ViewAttributes() ?>><?php echo $Page->nip->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->jml_jam->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_laplembur_jml_jam"<?php echo $Page->jml_jam->ViewAttributes() ?>><?php echo $Page->jml_jam->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->tarif->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_laplembur_tarif"<?php echo $Page->tarif->ViewAttributes() ?>><?php echo $Page->tarif->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->total_lembur->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);

		// Show Footers
?>
<?php
		if ($Page->ChkLvlBreak(2)) {
			$cnt = count(@$Page->GrpIdx[$Page->GrpCount]);
			$Page->GrpIdx[$Page->GrpCount][$cnt] = $Page->RecCount;
		}
		if ($Page->ChkLvlBreak(2) && $Page->divisi->Visible) {
?>
<?php
			$Page->bagian->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->divisi->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->total_lembur->Count = $Page->Cnt[2][6];
			$Page->total_lembur->SumValue = $Page->Smry[2][6]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 2;
			$Page->RenderRow();
?>
<?php if ($Page->divisi->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes() ?>>
	<?php if ($Page->bagian->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->bagian->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->divisi->Visible) { ?>
		<td data-field="divisi"<?php echo $Page->divisi->CellAttributes() ?>>
	<?php if ($Page->divisi->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->divisi->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->divisi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->divisi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->divisi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->divisi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->divisi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->divisi->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->SubGrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->SubGrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->total_lembur->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->divisi->GroupViewValue, $Page->divisi->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[2][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 1) ?>"<?php echo $Page->divisi->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->divisi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->divisi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->divisi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->divisi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->divisi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->total_lembur->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 2 summary
			$Page->ResetLevelSummary(2);
		} // End show footer check
		if ($Page->ChkLvlBreak(2)) {
			$Page->GrpCounter[0]++;
		}
?>
<?php
	} // End detail records loop
?>
<?php
		if ($Page->bagian->Visible) {
?>
<?php
			$Page->bagian->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->divisi->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->total_lembur->Count = $Page->Cnt[1][6];
			$Page->total_lembur->SumValue = $Page->Smry[1][6]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 1;
			$Page->RenderRow();
?>
<?php if ($Page->bagian->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->bagian->Visible) { ?>
		<td data-field="bagian"<?php echo $Page->bagian->CellAttributes() ?>>
	<?php if ($Page->bagian->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->bagian->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->divisi->Visible) { ?>
		<td data-field="divisi"<?php echo $Page->bagian->CellAttributes() ?>>
	<?php if ($Page->divisi->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->divisi->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->bagian->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->bagian->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->bagian->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->bagian->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->bagian->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->bagian->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->total_lembur->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->bagian->GroupViewValue, $Page->bagian->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[1][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 0) ?>"<?php echo $Page->bagian->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->bagian->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->total_lembur->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 1 summary
			$Page->ResetLevelSummary(1);
		} // End show footer check
?>
<?php

	// Next group
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;
	$Page->GrpCounter[0] = 1;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->total_lembur->Count = $Page->GrandCnt[6];
	$Page->total_lembur->SumValue = $Page->GrandSmry[6]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->bagian->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->no->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->nip->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->jml_jam->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->tarif->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->total_lembur->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->no->Visible) { ?>
		<td data-field="no"<?php echo $Page->no->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nip->Visible) { ?>
		<td data-field="nip"<?php echo $Page->nip->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->jml_jam->Visible) { ?>
		<td data-field="jml_jam"<?php echo $Page->jml_jam->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tarif->Visible) { ?>
		<td data-field="tarif"<?php echo $Page->tarif->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->total_lembur->Visible) { ?>
		<td data-field="total_lembur"<?php echo $Page->total_lembur->CellAttributes() ?>>
<span data-class="tpts_r_laplembur_total_lembur"<?php echo $Page->total_lembur->ViewAttributes() ?>><?php echo $Page->total_lembur->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "r_laplembursmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_laplembursmrypager.php" ?>
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
<!-- Summary Report Ends -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- center container - report (end) -->
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
<!-- Bottom Container (End) -->
</div>
<!-- Table Container (End) -->
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
