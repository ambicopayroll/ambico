<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "r_lapgjhrnsmryinfo.php" ?>
<?php

//
// Page class
//

$r_lapgjhrn_summary = NULL; // Initialize page object first

class crr_lapgjhrn_summary extends crr_lapgjhrn {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}";

	// Page object name
	var $PageObjName = 'r_lapgjhrn_summary';

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
	var $ExportPrintCustom = TRUE;
	var $ExportExcelCustom = TRUE;
	var $ExportWordCustom = TRUE;
	var $ExportPdfCustom = TRUE;
	var $ExportEmailCustom = TRUE;

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

		// Table object (r_lapgjhrn)
		if (!isset($GLOBALS["r_lapgjhrn"])) {
			$GLOBALS["r_lapgjhrn"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_lapgjhrn"];
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
			define("EWR_TABLE_NAME", 'r_lapgjhrn', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fr_lapgjhrnsummary";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'r_lapgjhrn');
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

		// Get custom export parameters
		if ($this->Export <> "" && @$_GET["custom"] <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;

		// Custom export (post back from ewr_ApplyTemplate), export and terminate page
		if (@$_POST["customexport"] <> "") {
			$this->CustomExport = $_POST["customexport"];
			$this->Export = $this->CustomExport;
			$this->Page_Terminate();
			exit();
		}
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

		// Update Export URLs
		if ($this->ExportPrintCustom)
			$this->ExportPrintUrl .= "&amp;custom=1";

		//if (defined("EWR_USE_PHPEXCEL"))
		//	$this->ExportExcelCustom = FALSE;

		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";

		//if (defined("EWR_USE_PHPWORD"))
		//	$this->ExportWordCustom = FALSE;

		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		if ($this->ExportPrintCustom)
			$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $this->ExportPrintUrl . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		else
			$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly"), TRUE) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		if ($this->ExportExcelCustom)
			$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $this->ExportExcelUrl . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		else
			$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		if ($this->ExportWordCustom)
			$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $this->ExportWordUrl . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		else
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
		if ($this->ExportEmailCustom)
			$url .= "&amp;custom=1";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_r_lapgjhrn\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_r_lapgjhrn',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fr_lapgjhrnsummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fr_lapgjhrnsummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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

		// Hide main table for custom layout
		if ($this->Export <> "" || $this->UseCustomTemplate)
			$this->ReportTableStyle = " style=\"display: none;\"";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fr_lapgjhrnsummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		if (@$_POST["customexport"] == "") {

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		}

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			if (@$_POST["data"] <> "") {
				$sContent = $_POST["data"];
				$gsExportFile = @$_POST["filename"];
				if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			} else {
				$sContent = ob_get_contents();
			}
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
		$this->tgl->SetVisibility();
		$this->pegawai_nip->SetVisibility();
		$this->upah->SetVisibility();
		$this->premi_hadir->SetVisibility();
		$this->premi_malam->SetVisibility();
		$this->pot_absen->SetVisibility();
		$this->upah2->SetVisibility();
		$this->premi_malam2->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 9;
		$nGrps = 4;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

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

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

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
			$wrklapgroup_nama = $row["lapgroup_nama"];
			$wrkpembagian2_nama = $row["pembagian2_nama"];
			$wrkpegawai_nama = $row["pegawai_nama"];
			if ($lvl >= 1) {
				$val = $curValue ? $this->lapgroup_nama->CurrentValue : $this->lapgroup_nama->OldValue;
				$grpval = $curValue ? $this->lapgroup_nama->GroupValue() : $this->lapgroup_nama->GroupOldValue();
				if (is_null($val) && !is_null($wrklapgroup_nama) || !is_null($val) && is_null($wrklapgroup_nama) ||
					$grpval <> $this->lapgroup_nama->getGroupValueBase($wrklapgroup_nama))
				continue;
			}
			if ($lvl >= 2) {
				$val = $curValue ? $this->pembagian2_nama->CurrentValue : $this->pembagian2_nama->OldValue;
				$grpval = $curValue ? $this->pembagian2_nama->GroupValue() : $this->pembagian2_nama->GroupOldValue();
				if (is_null($val) && !is_null($wrkpembagian2_nama) || !is_null($val) && is_null($wrkpembagian2_nama) ||
					$grpval <> $this->pembagian2_nama->getGroupValueBase($wrkpembagian2_nama))
				continue;
			}
			if ($lvl >= 3) {
				$val = $curValue ? $this->pegawai_nama->CurrentValue : $this->pegawai_nama->OldValue;
				$grpval = $curValue ? $this->pegawai_nama->GroupValue() : $this->pegawai_nama->GroupOldValue();
				if (is_null($val) && !is_null($wrkpegawai_nama) || !is_null($val) && is_null($wrkpegawai_nama) ||
					$grpval <> $this->pegawai_nama->getGroupValueBase($wrkpegawai_nama))
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
				return (is_null($this->lapgroup_nama->CurrentValue) && !is_null($this->lapgroup_nama->OldValue)) ||
					(!is_null($this->lapgroup_nama->CurrentValue) && is_null($this->lapgroup_nama->OldValue)) ||
					($this->lapgroup_nama->GroupValue() <> $this->lapgroup_nama->GroupOldValue());
			case 2:
				return (is_null($this->pembagian2_nama->CurrentValue) && !is_null($this->pembagian2_nama->OldValue)) ||
					(!is_null($this->pembagian2_nama->CurrentValue) && is_null($this->pembagian2_nama->OldValue)) ||
					($this->pembagian2_nama->GroupValue() <> $this->pembagian2_nama->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($this->pegawai_nama->CurrentValue) && !is_null($this->pegawai_nama->OldValue)) ||
					(!is_null($this->pegawai_nama->CurrentValue) && is_null($this->pegawai_nama->OldValue)) ||
					($this->pegawai_nama->GroupValue() <> $this->pegawai_nama->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
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
			$this->lapgroup_nama->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->lapgroup_nama->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->lapgroup_nama->setDbValue("");
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
				$this->FirstRowData['lapgroup_id'] = ewr_Conv($rs->fields('lapgroup_id'), 3);
				$this->FirstRowData['lapgroup_nama'] = ewr_Conv($rs->fields('lapgroup_nama'), 200);
				$this->FirstRowData['lapgroup_index'] = ewr_Conv($rs->fields('lapgroup_index'), 16);
				$this->FirstRowData['lapsubgroup_index'] = ewr_Conv($rs->fields('lapsubgroup_index'), 3);
				$this->FirstRowData['pegawai_id'] = ewr_Conv($rs->fields('pegawai_id'), 3);
				$this->FirstRowData['tgl'] = ewr_Conv($rs->fields('tgl'), 133);
				$this->FirstRowData['jk_id'] = ewr_Conv($rs->fields('jk_id'), 3);
				$this->FirstRowData['scan_masuk'] = ewr_Conv($rs->fields('scan_masuk'), 135);
				$this->FirstRowData['scan_keluar'] = ewr_Conv($rs->fields('scan_keluar'), 135);
				$this->FirstRowData['hk_def'] = ewr_Conv($rs->fields('hk_def'), 16);
				$this->FirstRowData['pegawai_nama'] = ewr_Conv($rs->fields('pegawai_nama'), 200);
				$this->FirstRowData['pegawai_nip'] = ewr_Conv($rs->fields('pegawai_nip'), 200);
				$this->FirstRowData['jk_kd'] = ewr_Conv($rs->fields('jk_kd'), 200);
				$this->FirstRowData['pembagian2_nama'] = ewr_Conv($rs->fields('pembagian2_nama'), 200);
				$this->FirstRowData['pembagian2_id'] = ewr_Conv($rs->fields('pembagian2_id'), 3);
				$this->FirstRowData['rumus_id'] = ewr_Conv($rs->fields('rumus_id'), 3);
				$this->FirstRowData['rumus_nama'] = ewr_Conv($rs->fields('rumus_nama'), 200);
				$this->FirstRowData['hk_gol'] = ewr_Conv($rs->fields('hk_gol'), 16);
				$this->FirstRowData['umr'] = ewr_Conv($rs->fields('umr'), 4);
				$this->FirstRowData['hk_jml'] = ewr_Conv($rs->fields('hk_jml'), 2);
				$this->FirstRowData['upah'] = ewr_Conv($rs->fields('upah'), 4);
				$this->FirstRowData['premi_hadir'] = ewr_Conv($rs->fields('premi_hadir'), 4);
				$this->FirstRowData['premi_malam'] = ewr_Conv($rs->fields('premi_malam'), 4);
				$this->FirstRowData['pot_absen'] = ewr_Conv($rs->fields('pot_absen'), 4);
				$this->FirstRowData['lembur'] = ewr_Conv($rs->fields('lembur'), 4);
				$this->FirstRowData['upah2'] = ewr_Conv($rs->fields('upah2'), 5);
				$this->FirstRowData['premi_malam2'] = ewr_Conv($rs->fields('premi_malam2'), 4);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->lapgroup_id->setDbValue($rs->fields('lapgroup_id'));
			if ($opt <> 1) {
				if (is_array($this->lapgroup_nama->GroupDbValues))
					$this->lapgroup_nama->setDbValue(@$this->lapgroup_nama->GroupDbValues[$rs->fields('lapgroup_nama')]);
				else
					$this->lapgroup_nama->setDbValue(ewr_GroupValue($this->lapgroup_nama, $rs->fields('lapgroup_nama')));
			}
			$this->lapgroup_index->setDbValue($rs->fields('lapgroup_index'));
			$this->lapsubgroup_index->setDbValue($rs->fields('lapsubgroup_index'));
			$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
			$this->tgl->setDbValue($rs->fields('tgl'));
			$this->jk_id->setDbValue($rs->fields('jk_id'));
			$this->scan_masuk->setDbValue($rs->fields('scan_masuk'));
			$this->scan_keluar->setDbValue($rs->fields('scan_keluar'));
			$this->hk_def->setDbValue($rs->fields('hk_def'));
			$this->pegawai_nama->setDbValue($rs->fields('pegawai_nama'));
			$this->pegawai_nip->setDbValue($rs->fields('pegawai_nip'));
			$this->jk_kd->setDbValue($rs->fields('jk_kd'));
			$this->pembagian2_nama->setDbValue($rs->fields('pembagian2_nama'));
			$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
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
			$this->upah2->setDbValue($rs->fields('upah2'));
			$this->premi_malam2->setDbValue($rs->fields('premi_malam2'));
			$this->Val[1] = $this->tgl->CurrentValue;
			$this->Val[2] = $this->pegawai_nip->CurrentValue;
			$this->Val[3] = $this->upah->CurrentValue;
			$this->Val[4] = $this->premi_hadir->CurrentValue;
			$this->Val[5] = $this->premi_malam->CurrentValue;
			$this->Val[6] = $this->pot_absen->CurrentValue;
			$this->Val[7] = $this->upah2->CurrentValue;
			$this->Val[8] = $this->premi_malam2->CurrentValue;
		} else {
			$this->lapgroup_id->setDbValue("");
			$this->lapgroup_nama->setDbValue("");
			$this->lapgroup_index->setDbValue("");
			$this->lapsubgroup_index->setDbValue("");
			$this->pegawai_id->setDbValue("");
			$this->tgl->setDbValue("");
			$this->jk_id->setDbValue("");
			$this->scan_masuk->setDbValue("");
			$this->scan_keluar->setDbValue("");
			$this->hk_def->setDbValue("");
			$this->pegawai_nama->setDbValue("");
			$this->pegawai_nip->setDbValue("");
			$this->jk_kd->setDbValue("");
			$this->pembagian2_nama->setDbValue("");
			$this->pembagian2_id->setDbValue("");
			$this->rumus_id->setDbValue("");
			$this->rumus_nama->setDbValue("");
			$this->hk_gol->setDbValue("");
			$this->umr->setDbValue("");
			$this->hk_jml->setDbValue("");
			$this->upah->setDbValue("");
			$this->premi_hadir->setDbValue("");
			$this->premi_malam->setDbValue("");
			$this->pot_absen->setDbValue("");
			$this->lembur->setDbValue("");
			$this->upah2->setDbValue("");
			$this->premi_malam2->setDbValue("");
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
				$this->GrandSmry[3] = $rsagg->fields("sum_upah");
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandSmry[4] = $rsagg->fields("sum_premi_hadir");
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandSmry[5] = $rsagg->fields("sum_premi_malam");
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandSmry[6] = $rsagg->fields("sum_pot_absen");
				$this->GrandCnt[7] = $this->TotCount;
				$this->GrandSmry[7] = $rsagg->fields("sum_upah2");
				$this->GrandCnt[8] = $this->TotCount;
				$this->GrandSmry[8] = $rsagg->fields("sum_premi_malam2");
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
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP) $this->RowAttrs["data-group"] = $this->lapgroup_nama->GroupOldValue(); // Set up group attribute
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->pembagian2_nama->GroupOldValue(); // Set up group attribute 2
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 3) $this->RowAttrs["data-group-3"] = $this->pegawai_nama->GroupOldValue(); // Set up group attribute 3

			// lapgroup_nama
			$this->lapgroup_nama->GroupViewValue = $this->lapgroup_nama->GroupOldValue();
			$this->lapgroup_nama->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->lapgroup_nama->GroupViewValue = ewr_DisplayGroupValue($this->lapgroup_nama, $this->lapgroup_nama->GroupViewValue);
			$this->lapgroup_nama->GroupSummaryOldValue = $this->lapgroup_nama->GroupSummaryValue;
			$this->lapgroup_nama->GroupSummaryValue = $this->lapgroup_nama->GroupViewValue;
			$this->lapgroup_nama->GroupSummaryViewValue = ($this->lapgroup_nama->GroupSummaryOldValue <> $this->lapgroup_nama->GroupSummaryValue) ? $this->lapgroup_nama->GroupSummaryValue : "&nbsp;";

			// pembagian2_nama
			$this->pembagian2_nama->GroupViewValue = $this->pembagian2_nama->GroupOldValue();
			$this->pembagian2_nama->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->pembagian2_nama->GroupViewValue = ewr_DisplayGroupValue($this->pembagian2_nama, $this->pembagian2_nama->GroupViewValue);
			$this->pembagian2_nama->GroupSummaryOldValue = $this->pembagian2_nama->GroupSummaryValue;
			$this->pembagian2_nama->GroupSummaryValue = $this->pembagian2_nama->GroupViewValue;
			$this->pembagian2_nama->GroupSummaryViewValue = ($this->pembagian2_nama->GroupSummaryOldValue <> $this->pembagian2_nama->GroupSummaryValue) ? $this->pembagian2_nama->GroupSummaryValue : "&nbsp;";

			// pegawai_nama
			$this->pegawai_nama->GroupViewValue = $this->pegawai_nama->GroupOldValue();
			$this->pegawai_nama->CellAttrs["class"] = ($this->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$this->pegawai_nama->GroupViewValue = ewr_DisplayGroupValue($this->pegawai_nama, $this->pegawai_nama->GroupViewValue);
			$this->pegawai_nama->GroupSummaryOldValue = $this->pegawai_nama->GroupSummaryValue;
			$this->pegawai_nama->GroupSummaryValue = $this->pegawai_nama->GroupViewValue;
			$this->pegawai_nama->GroupSummaryViewValue = ($this->pegawai_nama->GroupSummaryOldValue <> $this->pegawai_nama->GroupSummaryValue) ? $this->pegawai_nama->GroupSummaryValue : "&nbsp;";

			// upah
			$this->upah->SumViewValue = $this->upah->SumValue;
			$this->upah->SumViewValue = ewr_FormatNumber($this->upah->SumViewValue, $this->upah->DefaultDecimalPrecision, -1, 0, 0);
			$this->upah->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// premi_hadir
			$this->premi_hadir->SumViewValue = $this->premi_hadir->SumValue;
			$this->premi_hadir->SumViewValue = ewr_FormatNumber($this->premi_hadir->SumViewValue, $this->premi_hadir->DefaultDecimalPrecision, -1, 0, 0);
			$this->premi_hadir->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// premi_malam
			$this->premi_malam->SumViewValue = $this->premi_malam->SumValue;
			$this->premi_malam->SumViewValue = ewr_FormatNumber($this->premi_malam->SumViewValue, $this->premi_malam->DefaultDecimalPrecision, -1, 0, 0);
			$this->premi_malam->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// pot_absen
			$this->pot_absen->SumViewValue = $this->pot_absen->SumValue;
			$this->pot_absen->SumViewValue = ewr_FormatNumber($this->pot_absen->SumViewValue, $this->pot_absen->DefaultDecimalPrecision, -1, 0, 0);
			$this->pot_absen->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// upah2
			$this->upah2->SumViewValue = $this->upah2->SumValue;
			$this->upah2->SumViewValue = ewr_FormatNumber($this->upah2->SumViewValue, $this->upah2->DefaultDecimalPrecision, -1, 0, 0);
			$this->upah2->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// premi_malam2
			$this->premi_malam2->SumViewValue = $this->premi_malam2->SumValue;
			$this->premi_malam2->SumViewValue = ewr_FormatNumber($this->premi_malam2->SumViewValue, $this->premi_malam2->DefaultDecimalPrecision, -1, 0, 0);
			$this->premi_malam2->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// lapgroup_nama
			$this->lapgroup_nama->HrefValue = "";

			// pembagian2_nama
			$this->pembagian2_nama->HrefValue = "";

			// pegawai_nama
			$this->pegawai_nama->HrefValue = "";

			// tgl
			$this->tgl->HrefValue = "";

			// pegawai_nip
			$this->pegawai_nip->HrefValue = "";

			// upah
			$this->upah->HrefValue = "";

			// premi_hadir
			$this->premi_hadir->HrefValue = "";

			// premi_malam
			$this->premi_malam->HrefValue = "";

			// pot_absen
			$this->pot_absen->HrefValue = "";

			// upah2
			$this->upah2->HrefValue = "";

			// premi_malam2
			$this->premi_malam2->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			$this->RowAttrs["data-group"] = $this->lapgroup_nama->GroupValue(); // Set up group attribute
			if ($this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->pembagian2_nama->GroupValue(); // Set up group attribute 2
			if ($this->RowGroupLevel >= 3) $this->RowAttrs["data-group-3"] = $this->pegawai_nama->GroupValue(); // Set up group attribute 3
			} else {
			$this->RowAttrs["data-group"] = $this->lapgroup_nama->GroupValue(); // Set up group attribute
			$this->RowAttrs["data-group-2"] = $this->pembagian2_nama->GroupValue(); // Set up group attribute 2
			$this->RowAttrs["data-group-3"] = $this->pegawai_nama->GroupValue(); // Set up group attribute 3
			}

			// lapgroup_nama
			$this->lapgroup_nama->GroupViewValue = $this->lapgroup_nama->GroupValue();
			$this->lapgroup_nama->CellAttrs["class"] = "ewRptGrpField1";
			$this->lapgroup_nama->GroupViewValue = ewr_DisplayGroupValue($this->lapgroup_nama, $this->lapgroup_nama->GroupViewValue);
			if ($this->lapgroup_nama->GroupValue() == $this->lapgroup_nama->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->lapgroup_nama->GroupViewValue = "&nbsp;";

			// pembagian2_nama
			$this->pembagian2_nama->GroupViewValue = $this->pembagian2_nama->GroupValue();
			$this->pembagian2_nama->CellAttrs["class"] = "ewRptGrpField2";
			$this->pembagian2_nama->GroupViewValue = ewr_DisplayGroupValue($this->pembagian2_nama, $this->pembagian2_nama->GroupViewValue);
			if ($this->pembagian2_nama->GroupValue() == $this->pembagian2_nama->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->pembagian2_nama->GroupViewValue = "&nbsp;";

			// pegawai_nama
			$this->pegawai_nama->GroupViewValue = $this->pegawai_nama->GroupValue();
			$this->pegawai_nama->CellAttrs["class"] = "ewRptGrpField3";
			$this->pegawai_nama->GroupViewValue = ewr_DisplayGroupValue($this->pegawai_nama, $this->pegawai_nama->GroupViewValue);
			if ($this->pegawai_nama->GroupValue() == $this->pegawai_nama->GroupOldValue() && !$this->ChkLvlBreak(3))
				$this->pegawai_nama->GroupViewValue = "&nbsp;";

			// tgl
			$this->tgl->ViewValue = $this->tgl->CurrentValue;
			$this->tgl->ViewValue = ewr_FormatDateTime($this->tgl->ViewValue, 0);
			$this->tgl->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// pegawai_nip
			$this->pegawai_nip->ViewValue = $this->pegawai_nip->CurrentValue;
			$this->pegawai_nip->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// upah
			$this->upah->ViewValue = $this->upah->CurrentValue;
			$this->upah->ViewValue = ewr_FormatNumber($this->upah->ViewValue, $this->upah->DefaultDecimalPrecision, -1, 0, 0);
			$this->upah->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// premi_hadir
			$this->premi_hadir->ViewValue = $this->premi_hadir->CurrentValue;
			$this->premi_hadir->ViewValue = ewr_FormatNumber($this->premi_hadir->ViewValue, $this->premi_hadir->DefaultDecimalPrecision, -1, 0, 0);
			$this->premi_hadir->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// premi_malam
			$this->premi_malam->ViewValue = $this->premi_malam->CurrentValue;
			$this->premi_malam->ViewValue = ewr_FormatNumber($this->premi_malam->ViewValue, $this->premi_malam->DefaultDecimalPrecision, -1, 0, 0);
			$this->premi_malam->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// pot_absen
			$this->pot_absen->ViewValue = $this->pot_absen->CurrentValue;
			$this->pot_absen->ViewValue = ewr_FormatNumber($this->pot_absen->ViewValue, $this->pot_absen->DefaultDecimalPrecision, -1, 0, 0);
			$this->pot_absen->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// upah2
			$this->upah2->ViewValue = $this->upah2->CurrentValue;
			$this->upah2->ViewValue = ewr_FormatNumber($this->upah2->ViewValue, $this->upah2->DefaultDecimalPrecision, -1, 0, 0);
			$this->upah2->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// premi_malam2
			$this->premi_malam2->ViewValue = $this->premi_malam2->CurrentValue;
			$this->premi_malam2->ViewValue = ewr_FormatNumber($this->premi_malam2->ViewValue, $this->premi_malam2->DefaultDecimalPrecision, -1, 0, 0);
			$this->premi_malam2->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// lapgroup_nama
			$this->lapgroup_nama->HrefValue = "";

			// pembagian2_nama
			$this->pembagian2_nama->HrefValue = "";

			// pegawai_nama
			$this->pegawai_nama->HrefValue = "";

			// tgl
			$this->tgl->HrefValue = "";

			// pegawai_nip
			$this->pegawai_nip->HrefValue = "";

			// upah
			$this->upah->HrefValue = "";

			// premi_hadir
			$this->premi_hadir->HrefValue = "";

			// premi_malam
			$this->premi_malam->HrefValue = "";

			// pot_absen
			$this->pot_absen->HrefValue = "";

			// upah2
			$this->upah2->HrefValue = "";

			// premi_malam2
			$this->premi_malam2->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// lapgroup_nama
			$CurrentValue = $this->lapgroup_nama->GroupViewValue;
			$ViewValue = &$this->lapgroup_nama->GroupViewValue;
			$ViewAttrs = &$this->lapgroup_nama->ViewAttrs;
			$CellAttrs = &$this->lapgroup_nama->CellAttrs;
			$HrefValue = &$this->lapgroup_nama->HrefValue;
			$LinkAttrs = &$this->lapgroup_nama->LinkAttrs;
			$this->Cell_Rendered($this->lapgroup_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pembagian2_nama
			$CurrentValue = $this->pembagian2_nama->GroupViewValue;
			$ViewValue = &$this->pembagian2_nama->GroupViewValue;
			$ViewAttrs = &$this->pembagian2_nama->ViewAttrs;
			$CellAttrs = &$this->pembagian2_nama->CellAttrs;
			$HrefValue = &$this->pembagian2_nama->HrefValue;
			$LinkAttrs = &$this->pembagian2_nama->LinkAttrs;
			$this->Cell_Rendered($this->pembagian2_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pegawai_nama
			$CurrentValue = $this->pegawai_nama->GroupViewValue;
			$ViewValue = &$this->pegawai_nama->GroupViewValue;
			$ViewAttrs = &$this->pegawai_nama->ViewAttrs;
			$CellAttrs = &$this->pegawai_nama->CellAttrs;
			$HrefValue = &$this->pegawai_nama->HrefValue;
			$LinkAttrs = &$this->pegawai_nama->LinkAttrs;
			$this->Cell_Rendered($this->pegawai_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// upah
			$CurrentValue = $this->upah->SumValue;
			$ViewValue = &$this->upah->SumViewValue;
			$ViewAttrs = &$this->upah->ViewAttrs;
			$CellAttrs = &$this->upah->CellAttrs;
			$HrefValue = &$this->upah->HrefValue;
			$LinkAttrs = &$this->upah->LinkAttrs;
			$this->Cell_Rendered($this->upah, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// premi_hadir
			$CurrentValue = $this->premi_hadir->SumValue;
			$ViewValue = &$this->premi_hadir->SumViewValue;
			$ViewAttrs = &$this->premi_hadir->ViewAttrs;
			$CellAttrs = &$this->premi_hadir->CellAttrs;
			$HrefValue = &$this->premi_hadir->HrefValue;
			$LinkAttrs = &$this->premi_hadir->LinkAttrs;
			$this->Cell_Rendered($this->premi_hadir, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// premi_malam
			$CurrentValue = $this->premi_malam->SumValue;
			$ViewValue = &$this->premi_malam->SumViewValue;
			$ViewAttrs = &$this->premi_malam->ViewAttrs;
			$CellAttrs = &$this->premi_malam->CellAttrs;
			$HrefValue = &$this->premi_malam->HrefValue;
			$LinkAttrs = &$this->premi_malam->LinkAttrs;
			$this->Cell_Rendered($this->premi_malam, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pot_absen
			$CurrentValue = $this->pot_absen->SumValue;
			$ViewValue = &$this->pot_absen->SumViewValue;
			$ViewAttrs = &$this->pot_absen->ViewAttrs;
			$CellAttrs = &$this->pot_absen->CellAttrs;
			$HrefValue = &$this->pot_absen->HrefValue;
			$LinkAttrs = &$this->pot_absen->LinkAttrs;
			$this->Cell_Rendered($this->pot_absen, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// upah2
			$CurrentValue = $this->upah2->SumValue;
			$ViewValue = &$this->upah2->SumViewValue;
			$ViewAttrs = &$this->upah2->ViewAttrs;
			$CellAttrs = &$this->upah2->CellAttrs;
			$HrefValue = &$this->upah2->HrefValue;
			$LinkAttrs = &$this->upah2->LinkAttrs;
			$this->Cell_Rendered($this->upah2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// premi_malam2
			$CurrentValue = $this->premi_malam2->SumValue;
			$ViewValue = &$this->premi_malam2->SumViewValue;
			$ViewAttrs = &$this->premi_malam2->ViewAttrs;
			$CellAttrs = &$this->premi_malam2->CellAttrs;
			$HrefValue = &$this->premi_malam2->HrefValue;
			$LinkAttrs = &$this->premi_malam2->LinkAttrs;
			$this->Cell_Rendered($this->premi_malam2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// lapgroup_nama
			$CurrentValue = $this->lapgroup_nama->GroupValue();
			$ViewValue = &$this->lapgroup_nama->GroupViewValue;
			$ViewAttrs = &$this->lapgroup_nama->ViewAttrs;
			$CellAttrs = &$this->lapgroup_nama->CellAttrs;
			$HrefValue = &$this->lapgroup_nama->HrefValue;
			$LinkAttrs = &$this->lapgroup_nama->LinkAttrs;
			$this->Cell_Rendered($this->lapgroup_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pembagian2_nama
			$CurrentValue = $this->pembagian2_nama->GroupValue();
			$ViewValue = &$this->pembagian2_nama->GroupViewValue;
			$ViewAttrs = &$this->pembagian2_nama->ViewAttrs;
			$CellAttrs = &$this->pembagian2_nama->CellAttrs;
			$HrefValue = &$this->pembagian2_nama->HrefValue;
			$LinkAttrs = &$this->pembagian2_nama->LinkAttrs;
			$this->Cell_Rendered($this->pembagian2_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pegawai_nama
			$CurrentValue = $this->pegawai_nama->GroupValue();
			$ViewValue = &$this->pegawai_nama->GroupViewValue;
			$ViewAttrs = &$this->pegawai_nama->ViewAttrs;
			$CellAttrs = &$this->pegawai_nama->CellAttrs;
			$HrefValue = &$this->pegawai_nama->HrefValue;
			$LinkAttrs = &$this->pegawai_nama->LinkAttrs;
			$this->Cell_Rendered($this->pegawai_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tgl
			$CurrentValue = $this->tgl->CurrentValue;
			$ViewValue = &$this->tgl->ViewValue;
			$ViewAttrs = &$this->tgl->ViewAttrs;
			$CellAttrs = &$this->tgl->CellAttrs;
			$HrefValue = &$this->tgl->HrefValue;
			$LinkAttrs = &$this->tgl->LinkAttrs;
			$this->Cell_Rendered($this->tgl, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pegawai_nip
			$CurrentValue = $this->pegawai_nip->CurrentValue;
			$ViewValue = &$this->pegawai_nip->ViewValue;
			$ViewAttrs = &$this->pegawai_nip->ViewAttrs;
			$CellAttrs = &$this->pegawai_nip->CellAttrs;
			$HrefValue = &$this->pegawai_nip->HrefValue;
			$LinkAttrs = &$this->pegawai_nip->LinkAttrs;
			$this->Cell_Rendered($this->pegawai_nip, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// upah
			$CurrentValue = $this->upah->CurrentValue;
			$ViewValue = &$this->upah->ViewValue;
			$ViewAttrs = &$this->upah->ViewAttrs;
			$CellAttrs = &$this->upah->CellAttrs;
			$HrefValue = &$this->upah->HrefValue;
			$LinkAttrs = &$this->upah->LinkAttrs;
			$this->Cell_Rendered($this->upah, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// premi_hadir
			$CurrentValue = $this->premi_hadir->CurrentValue;
			$ViewValue = &$this->premi_hadir->ViewValue;
			$ViewAttrs = &$this->premi_hadir->ViewAttrs;
			$CellAttrs = &$this->premi_hadir->CellAttrs;
			$HrefValue = &$this->premi_hadir->HrefValue;
			$LinkAttrs = &$this->premi_hadir->LinkAttrs;
			$this->Cell_Rendered($this->premi_hadir, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// premi_malam
			$CurrentValue = $this->premi_malam->CurrentValue;
			$ViewValue = &$this->premi_malam->ViewValue;
			$ViewAttrs = &$this->premi_malam->ViewAttrs;
			$CellAttrs = &$this->premi_malam->CellAttrs;
			$HrefValue = &$this->premi_malam->HrefValue;
			$LinkAttrs = &$this->premi_malam->LinkAttrs;
			$this->Cell_Rendered($this->premi_malam, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pot_absen
			$CurrentValue = $this->pot_absen->CurrentValue;
			$ViewValue = &$this->pot_absen->ViewValue;
			$ViewAttrs = &$this->pot_absen->ViewAttrs;
			$CellAttrs = &$this->pot_absen->CellAttrs;
			$HrefValue = &$this->pot_absen->HrefValue;
			$LinkAttrs = &$this->pot_absen->LinkAttrs;
			$this->Cell_Rendered($this->pot_absen, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// upah2
			$CurrentValue = $this->upah2->CurrentValue;
			$ViewValue = &$this->upah2->ViewValue;
			$ViewAttrs = &$this->upah2->ViewAttrs;
			$CellAttrs = &$this->upah2->CellAttrs;
			$HrefValue = &$this->upah2->HrefValue;
			$LinkAttrs = &$this->upah2->LinkAttrs;
			$this->Cell_Rendered($this->upah2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// premi_malam2
			$CurrentValue = $this->premi_malam2->CurrentValue;
			$ViewValue = &$this->premi_malam2->ViewValue;
			$ViewAttrs = &$this->premi_malam2->ViewAttrs;
			$CellAttrs = &$this->premi_malam2->CellAttrs;
			$HrefValue = &$this->premi_malam2->HrefValue;
			$LinkAttrs = &$this->premi_malam2->LinkAttrs;
			$this->Cell_Rendered($this->premi_malam2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->lapgroup_nama->Visible) $this->GrpColumnCount += 1;
		if ($this->pembagian2_nama->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->pegawai_nama->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->tgl->Visible) $this->DtlColumnCount += 1;
		if ($this->pegawai_nip->Visible) $this->DtlColumnCount += 1;
		if ($this->upah->Visible) $this->DtlColumnCount += 1;
		if ($this->premi_hadir->Visible) $this->DtlColumnCount += 1;
		if ($this->premi_malam->Visible) $this->DtlColumnCount += 1;
		if ($this->pot_absen->Visible) $this->DtlColumnCount += 1;
		if ($this->upah2->Visible) $this->DtlColumnCount += 1;
		if ($this->premi_malam2->Visible) $this->DtlColumnCount += 1;
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

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->tgl->SearchValue, $this->tgl->SearchOperator, $this->tgl->SearchCondition, $this->tgl->SearchValue2, $this->tgl->SearchOperator2, 'tgl'); // Field tgl

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

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
			$this->GetSessionFilterValues($this->tgl); // Field tgl
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->tgl, $sFilter, FALSE, TRUE); // Field tgl

		// Save parms to session
		$this->SetSessionFilterValues($this->tgl->SearchValue, $this->tgl->SearchOperator, $this->tgl->SearchCondition, $this->tgl->SearchValue2, $this->tgl->SearchOperator2, 'tgl'); // Field tgl

		// Setup filter
		if ($bSetupFilter) {
		}
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_r_lapgjhrn_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_lapgjhrn_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_r_lapgjhrn_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_lapgjhrn_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_r_lapgjhrn_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_r_lapgjhrn_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_r_lapgjhrn_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_r_lapgjhrn_' . $parm] = $sv;
		$_SESSION['so_r_lapgjhrn_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_r_lapgjhrn_' . $parm] = $sv1;
		$_SESSION['so_r_lapgjhrn_' . $parm] = $so1;
		$_SESSION['sc_r_lapgjhrn_' . $parm] = $sc;
		$_SESSION['sv2_r_lapgjhrn_' . $parm] = $sv2;
		$_SESSION['so2_r_lapgjhrn_' . $parm] = $so2;
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
		$_SESSION["sel_r_lapgjhrn_$parm"] = "";
		$_SESSION["rf_r_lapgjhrn_$parm"] = "";
		$_SESSION["rt_r_lapgjhrn_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_r_lapgjhrn_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_r_lapgjhrn_$parm"];
		$fld->RangeTo = @$_SESSION["rt_r_lapgjhrn_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/
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
		/**
		* Set up default values for popup filters
		*/
	}

	// Check if filter applied
	function CheckFilter() {

		// Check tgl text filter
		if ($this->TextFilterApplied($this->tgl))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field tgl
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->tgl, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tgl->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = ($this->Export <> "" || $this->UseCustomTemplate) ? " style=\"display: none;\"" : "";
		$divdataclass = ($this->Export <> "" || $this->UseCustomTemplate) ? " data-class=\"tp_current_filters\"" : "";

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
		} else {
			echo "<span" . $divdataclass . "></span>"; // Show dummy span
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field tgl
		$sWrk = "";
		if ($this->tgl->SearchValue <> "" || $this->tgl->SearchValue2 <> "") {
			$sWrk = "\"sv_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchValue) . "\"," .
				"\"so_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchOperator) . "\"," .
				"\"sc_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchCondition) . "\"," .
				"\"sv2_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchValue2) . "\"," .
				"\"so2_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchOperator2) . "\"";
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

		// Field tgl
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tgl", $filter) || array_key_exists("so_tgl", $filter) ||
			array_key_exists("sc_tgl", $filter) ||
			array_key_exists("sv2_tgl", $filter) || array_key_exists("so2_tgl", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_tgl"], @$filter["so_tgl"], @$filter["sc_tgl"], @$filter["sv2_tgl"], @$filter["so2_tgl"], "tgl");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tgl");
		}
		return TRUE;
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
			$this->lapgroup_nama->setSort("");
			$this->pembagian2_nama->setSort("");
			$this->pegawai_nama->setSort("");
			$this->tgl->setSort("");
			$this->pegawai_nip->setSort("");
			$this->upah->setSort("");
			$this->premi_hadir->setSort("");
			$this->premi_malam->setSort("");
			$this->pot_absen->setSort("");
			$this->upah2->setSort("");
			$this->premi_malam2->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->lapgroup_nama, $bCtrl); // lapgroup_nama
			$this->UpdateSort($this->pembagian2_nama, $bCtrl); // pembagian2_nama
			$this->UpdateSort($this->pegawai_nama, $bCtrl); // pegawai_nama
			$this->UpdateSort($this->tgl, $bCtrl); // tgl
			$this->UpdateSort($this->pegawai_nip, $bCtrl); // pegawai_nip
			$this->UpdateSort($this->upah, $bCtrl); // upah
			$this->UpdateSort($this->premi_hadir, $bCtrl); // premi_hadir
			$this->UpdateSort($this->premi_malam, $bCtrl); // premi_malam
			$this->UpdateSort($this->pot_absen, $bCtrl); // pot_absen
			$this->UpdateSort($this->upah2, $bCtrl); // upah2
			$this->UpdateSort($this->premi_malam2, $bCtrl); // premi_malam2
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

			// Replace images in custom template
			if (preg_match_all('/<img([^>]*)>/i', $sEmailMessage, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					if (preg_match('/\s+src\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[1], $submatches)) { // Match src='src'
						$src = $submatches[1];

						// Add embedded temp image if not in gTmpImages
						if (substr($src,0,4) == "cid:") {
							$tmpimage = substr($src,4);
							if (substr($tmpimage,0,3) == "tmp") {

								// Add file extension
								$addimage = FALSE;
								if (file_exists(ewr_AppRoot() . EWR_UPLOAD_DEST_PATH . $tmpimage . ".gif")) {
									$tmpimage .= ".gif";
									$addimage = TRUE;
								} elseif (file_exists(ewr_AppRoot() . EWR_UPLOAD_DEST_PATH . $tmpimage . ".jpg")) {
									$tmpimage .= ".jpg";
									$addimage = TRUE;
								} elseif (file_exists(ewr_AppRoot() . EWR_UPLOAD_DEST_PATH . $tmpimage . ".png")) {
									$tmpimage .= ".png";
									$addimage = TRUE;
								}

								// Add to gTmpImages
								if ($addimage) {
									foreach ($gTmpImages as $tmpimage2)
										if ($tmpimage == $tmpimage2)
											$addimage = FALSE;
									if ($addimage)
										$gTmpImages[] = $tmpimage;
								}
							}

						// Not embedded image, create temp image
						} else {
							$data = @file_get_contents($src);
							if ($data <> "")
								$sEmailMessage = str_replace($match[0], "<img src=\"" . ewr_TmpImage($data) . "\">", $sEmailMessage);
						}
					}
				}
			}
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

		// Replace images in custom template to hyperlinks
		if (preg_match_all('/<img([^>]*)>/i', $html, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				if (preg_match('/\s+src\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[1], $submatches)) { // Match src='src'
					$src = $submatches[1];
					$html = str_replace($match[0], "<a class=\"ewExportLink\" href=\"" . ewr_ConvertFullUrl($src) . "\">" . $src . "</a>", $html);
				}
			}
		}
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

		// Replace images in custom template to hyperlinks
		if (preg_match_all('/<img([^>]*)>/i', $html, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				if (preg_match('/\s+src\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[1], $submatches)) { // Match src='src'
					$src = $submatches[1];
					$html = str_replace($match[0], "<a class=\"ewExportLink\" href=\"" . ewr_ConvertFullUrl($src) . "\">" . $src . "</a>", $html);
				}
			}
		}
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
if (!isset($r_lapgjhrn_summary)) $r_lapgjhrn_summary = new crr_lapgjhrn_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$r_lapgjhrn_summary;

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
var r_lapgjhrn_summary = new ewr_Page("r_lapgjhrn_summary");

// Page properties
r_lapgjhrn_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = r_lapgjhrn_summary.PageID;

// Extend page with Chart_Rendering function
r_lapgjhrn_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
r_lapgjhrn_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fr_lapgjhrnsummary = new ewr_Form("fr_lapgjhrnsummary");

// Validate method
fr_lapgjhrnsummary.Validate = function() {
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
fr_lapgjhrnsummary.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fr_lapgjhrnsummary.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fr_lapgjhrnsummary.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown || $Page->Export <> "" && $Page->CustomExport <> "") { ?>
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
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<!-- Search form (begin) -->
<form name="fr_lapgjhrnsummary" id="fr_lapgjhrnsummary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fr_lapgjhrnsummary_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_tgl" class="ewCell form-group">
	<label for="sv_tgl" class="ewSearchCaption ewLabel"><?php echo $Page->tgl->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_tgl" id="so_tgl" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->tgl->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="r_lapgjhrn" data-field="x_tgl" id="sv_tgl" name="sv_tgl" placeholder="<?php echo $Page->tgl->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tgl->SearchValue) ?>" data-calendar="true" data-formatid="0"<?php echo $Page->tgl->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_tgl"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_tgl">
<?php ewr_PrependClass($Page->tgl->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="r_lapgjhrn" data-field="x_tgl" id="sv2_tgl" name="sv2_tgl" placeholder="<?php echo $Page->tgl->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tgl->SearchValue2) ?>" data-calendar="true" data-formatid="0"<?php echo $Page->tgl->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fr_lapgjhrnsummary.Init();
fr_lapgjhrnsummary.FilterList = <?php echo $Page->GetFilterList() ?>;
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
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCounter[0] = 1;
	$Page->GrpCounter[1] = 1;
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
<?php include "r_lapgjhrnsmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_r_lapgjhrn"><?php echo $Page->PageBreakContent ?></span>
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
<?php include "r_lapgjhrnsmrypager.php" ?>
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
<?php if ($Page->lapgroup_nama->Visible) { ?>
	<?php if ($Page->lapgroup_nama->ShowGroupHeaderAsRow) { ?>
	<td data-field="lapgroup_nama">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="lapgroup_nama"><div class="r_lapgjhrn_lapgroup_nama"><span class="ewTableHeaderCaption"><?php echo $Page->lapgroup_nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="lapgroup_nama">
<?php if ($Page->SortUrl($Page->lapgroup_nama) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_lapgroup_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->lapgroup_nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_lapgroup_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->lapgroup_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->lapgroup_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->lapgroup_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->lapgroup_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
	<?php if ($Page->pembagian2_nama->ShowGroupHeaderAsRow) { ?>
	<td data-field="pembagian2_nama">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="pembagian2_nama"><div class="r_lapgjhrn_pembagian2_nama"><span class="ewTableHeaderCaption"><?php echo $Page->pembagian2_nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="pembagian2_nama">
<?php if ($Page->SortUrl($Page->pembagian2_nama) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_pembagian2_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->pembagian2_nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_pembagian2_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pembagian2_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pembagian2_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pembagian2_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pembagian2_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible) { ?>
	<?php if ($Page->pegawai_nama->ShowGroupHeaderAsRow) { ?>
	<td data-field="pegawai_nama">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="pegawai_nama"><div class="r_lapgjhrn_pegawai_nama"><span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="pegawai_nama">
<?php if ($Page->SortUrl($Page->pegawai_nama) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_pegawai_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_pegawai_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pegawai_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pegawai_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pegawai_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tgl"><div class="r_lapgjhrn_tgl"><span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tgl">
<?php if ($Page->SortUrl($Page->tgl) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_tgl">
			<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_tgl" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tgl) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="pegawai_nip"><div class="r_lapgjhrn_pegawai_nip"><span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nip->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="pegawai_nip">
<?php if ($Page->SortUrl($Page->pegawai_nip) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_pegawai_nip">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nip->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_pegawai_nip" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pegawai_nip) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nip->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pegawai_nip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pegawai_nip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="upah"><div class="r_lapgjhrn_upah"><span class="ewTableHeaderCaption"><?php echo $Page->upah->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="upah">
<?php if ($Page->SortUrl($Page->upah) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_upah">
			<span class="ewTableHeaderCaption"><?php echo $Page->upah->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_upah" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->upah) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->upah->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->upah->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->upah->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="premi_hadir"><div class="r_lapgjhrn_premi_hadir"><span class="ewTableHeaderCaption"><?php echo $Page->premi_hadir->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="premi_hadir">
<?php if ($Page->SortUrl($Page->premi_hadir) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_premi_hadir">
			<span class="ewTableHeaderCaption"><?php echo $Page->premi_hadir->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_premi_hadir" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->premi_hadir) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->premi_hadir->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->premi_hadir->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->premi_hadir->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="premi_malam"><div class="r_lapgjhrn_premi_malam"><span class="ewTableHeaderCaption"><?php echo $Page->premi_malam->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="premi_malam">
<?php if ($Page->SortUrl($Page->premi_malam) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_premi_malam">
			<span class="ewTableHeaderCaption"><?php echo $Page->premi_malam->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_premi_malam" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->premi_malam) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->premi_malam->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->premi_malam->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->premi_malam->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="pot_absen"><div class="r_lapgjhrn_pot_absen"><span class="ewTableHeaderCaption"><?php echo $Page->pot_absen->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="pot_absen">
<?php if ($Page->SortUrl($Page->pot_absen) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_pot_absen">
			<span class="ewTableHeaderCaption"><?php echo $Page->pot_absen->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_pot_absen" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pot_absen) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pot_absen->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pot_absen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pot_absen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="upah2"><div class="r_lapgjhrn_upah2"><span class="ewTableHeaderCaption"><?php echo $Page->upah2->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="upah2">
<?php if ($Page->SortUrl($Page->upah2) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_upah2">
			<span class="ewTableHeaderCaption"><?php echo $Page->upah2->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_upah2" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->upah2) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->upah2->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->upah2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->upah2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="premi_malam2"><div class="r_lapgjhrn_premi_malam2"><span class="ewTableHeaderCaption"><?php echo $Page->premi_malam2->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="premi_malam2">
<?php if ($Page->SortUrl($Page->premi_malam2) == "") { ?>
		<div class="ewTableHeaderBtn r_lapgjhrn_premi_malam2">
			<span class="ewTableHeaderCaption"><?php echo $Page->premi_malam2->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_lapgjhrn_premi_malam2" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->premi_malam2) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->premi_malam2->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->premi_malam2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->premi_malam2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
	$sWhere = ewr_DetailFilterSQL($Page->lapgroup_nama, $Page->getSqlFirstGroupField(), $Page->lapgroup_nama->GroupValue(), $Page->DBID);
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
	$Page->GrpIdx[$Page->GrpCount][] = array(-1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;
?>
<?php if ($Page->lapgroup_nama->Visible && $Page->ChkLvlBreak(1) && $Page->lapgroup_nama->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 1;
		$Page->lapgroup_nama->Count = $Page->GetSummaryCount(1);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="lapgroup_nama" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_lapgjhrn_lapgroup_nama"><span class="ewTableHeaderCaption"><?php echo $Page->lapgroup_nama->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->lapgroup_nama) == "") { ?>
		<span class="ewSummaryCaption r_lapgjhrn_lapgroup_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->lapgroup_nama->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_lapgjhrn_lapgroup_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->lapgroup_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->lapgroup_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->lapgroup_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->lapgroup_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_lapgjhrn_lapgroup_nama"<?php echo $Page->lapgroup_nama->ViewAttributes() ?>><?php echo $Page->lapgroup_nama->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->lapgroup_nama->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible && $Page->ChkLvlBreak(2) && $Page->pembagian2_nama->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 2;
		$Page->pembagian2_nama->Count = $Page->GetSummaryCount(2);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="pembagian2_nama" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 2) ?>"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_lapgjhrn_pembagian2_nama"><span class="ewTableHeaderCaption"><?php echo $Page->pembagian2_nama->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->pembagian2_nama) == "") { ?>
		<span class="ewSummaryCaption r_lapgjhrn_pembagian2_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->pembagian2_nama->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_lapgjhrn_pembagian2_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pembagian2_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pembagian2_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pembagian2_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pembagian2_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_pembagian2_nama"<?php echo $Page->pembagian2_nama->ViewAttributes() ?>><?php echo $Page->pembagian2_nama->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pembagian2_nama->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible && $Page->ChkLvlBreak(3) && $Page->pegawai_nama->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 3;
		$Page->pegawai_nama->Count = $Page->GetSummaryCount(3);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible) { ?>
		<td data-field="pegawai_nama"<?php echo $Page->pegawai_nama->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="pegawai_nama" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 3) ?>"<?php echo $Page->pegawai_nama->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_lapgjhrn_pegawai_nama"><span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->pegawai_nama) == "") { ?>
		<span class="ewSummaryCaption r_lapgjhrn_pegawai_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_lapgjhrn_pegawai_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->pegawai_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->pegawai_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->pegawai_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->pegawai_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_pegawai_nama"<?php echo $Page->pegawai_nama->ViewAttributes() ?>><?php echo $Page->pegawai_nama->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pegawai_nama->Count,0,-2,-2,-2) ?></span>)</span>
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
<?php if ($Page->lapgroup_nama->Visible) { ?>
	<?php if ($Page->lapgroup_nama->ShowGroupHeaderAsRow) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_lapgjhrn_lapgroup_nama"<?php echo $Page->lapgroup_nama->ViewAttributes() ?>><?php echo $Page->lapgroup_nama->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
	<?php if ($Page->pembagian2_nama->ShowGroupHeaderAsRow) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_pembagian2_nama"<?php echo $Page->pembagian2_nama->ViewAttributes() ?>><?php echo $Page->pembagian2_nama->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible) { ?>
	<?php if ($Page->pegawai_nama->ShowGroupHeaderAsRow) { ?>
		<td data-field="pegawai_nama"<?php echo $Page->pegawai_nama->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="pegawai_nama"<?php echo $Page->pegawai_nama->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_pegawai_nama"<?php echo $Page->pegawai_nama->ViewAttributes() ?>><?php echo $Page->pegawai_nama->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_tgl"<?php echo $Page->tgl->ViewAttributes() ?>><?php echo $Page->tgl->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nip->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_pegawai_nip"<?php echo $Page->pegawai_nip->ViewAttributes() ?>><?php echo $Page->pegawai_nip->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->upah->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_hadir->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pot_absen->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->upah2->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->ListViewValue() ?></span></td>
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
		if ($Page->ChkLvlBreak(3)) {
			$cnt = count(@$Page->GrpIdx[$Page->GrpCount][$Page->GrpCounter[0]]);
			$Page->GrpIdx[$Page->GrpCount][$Page->GrpCounter[0]][$cnt] = $Page->RecCount;
		}
		if ($Page->ChkLvlBreak(3) && $Page->pegawai_nama->Visible) {
?>
<?php
			$Page->lapgroup_nama->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->pembagian2_nama->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->pegawai_nama->Count = $Page->GetSummaryCount(3, FALSE);
			$Page->upah->Count = $Page->Cnt[3][3];
			$Page->upah->SumValue = $Page->Smry[3][3]; // Load SUM
			$Page->premi_hadir->Count = $Page->Cnt[3][4];
			$Page->premi_hadir->SumValue = $Page->Smry[3][4]; // Load SUM
			$Page->premi_malam->Count = $Page->Cnt[3][5];
			$Page->premi_malam->SumValue = $Page->Smry[3][5]; // Load SUM
			$Page->pot_absen->Count = $Page->Cnt[3][6];
			$Page->pot_absen->SumValue = $Page->Smry[3][6]; // Load SUM
			$Page->upah2->Count = $Page->Cnt[3][7];
			$Page->upah2->SumValue = $Page->Smry[3][7]; // Load SUM
			$Page->premi_malam2->Count = $Page->Cnt[3][8];
			$Page->premi_malam2->SumValue = $Page->Smry[3][8]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 3;
			$Page->RenderRow();
?>
<?php if ($Page->pegawai_nama->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>
	<?php if ($Page->lapgroup_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->lapgroup_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>
	<?php if ($Page->pembagian2_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pembagian2_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible) { ?>
		<td data-field="pegawai_nama"<?php echo $Page->pegawai_nama->CellAttributes() ?>>
	<?php if ($Page->pegawai_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 3) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pegawai_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->pegawai_nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->pegawai_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->pegawai_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->pegawai_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pegawai_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->pegawai_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->pegawai_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->SubGrpColumnCount + $Page->DtlColumnCount - 1 > 0) { ?>
		<td colspan="<?php echo ($Page->SubGrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->premi_malam2->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->pegawai_nama->GroupViewValue, $Page->pegawai_nama->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[3][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 2) ?>"<?php echo $Page->pegawai_nama->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->pegawai_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 3 summary
			$Page->ResetLevelSummary(3);
		} // End show footer check
		if ($Page->ChkLvlBreak(3)) {
			$Page->GrpCounter[1]++;
		}
?>
<?php
		if ($Page->ChkLvlBreak(2) && $Page->pembagian2_nama->Visible) {
?>
<?php
			$Page->lapgroup_nama->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->pembagian2_nama->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->pegawai_nama->Count = $Page->GetSummaryCount(3, FALSE);
			$Page->upah->Count = $Page->Cnt[2][3];
			$Page->upah->SumValue = $Page->Smry[2][3]; // Load SUM
			$Page->premi_hadir->Count = $Page->Cnt[2][4];
			$Page->premi_hadir->SumValue = $Page->Smry[2][4]; // Load SUM
			$Page->premi_malam->Count = $Page->Cnt[2][5];
			$Page->premi_malam->SumValue = $Page->Smry[2][5]; // Load SUM
			$Page->pot_absen->Count = $Page->Cnt[2][6];
			$Page->pot_absen->SumValue = $Page->Smry[2][6]; // Load SUM
			$Page->upah2->Count = $Page->Cnt[2][7];
			$Page->upah2->SumValue = $Page->Smry[2][7]; // Load SUM
			$Page->premi_malam2->Count = $Page->Cnt[2][8];
			$Page->premi_malam2->SumValue = $Page->Smry[2][8]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 2;
			$Page->RenderRow();
?>
<?php if ($Page->pembagian2_nama->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>
	<?php if ($Page->lapgroup_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->lapgroup_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>
	<?php if ($Page->pembagian2_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pembagian2_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible) { ?>
		<td data-field="pegawai_nama"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>
	<?php if ($Page->pegawai_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 3) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pegawai_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->pembagian2_nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pembagian2_nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->SubGrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->SubGrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->premi_malam2->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->pembagian2_nama->GroupViewValue, $Page->pembagian2_nama->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[2][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 1) ?>"<?php echo $Page->pembagian2_nama->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pembagian2_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 2 summary
			$Page->ResetLevelSummary(2);
		} // End show footer check
		if ($Page->ChkLvlBreak(2)) {
			$Page->GrpCounter[0]++;
			if (!$rs->EOF)
				$Page->GrpIdx[$Page->GrpCount][$Page->GrpCounter[0]] = array(-1);
			$Page->GrpCounter[1] = 1;
		}
?>
<?php
	} // End detail records loop
?>
<?php
		if ($Page->lapgroup_nama->Visible) {
?>
<?php
			$Page->lapgroup_nama->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->pembagian2_nama->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->pegawai_nama->Count = $Page->GetSummaryCount(3, FALSE);
			$Page->upah->Count = $Page->Cnt[1][3];
			$Page->upah->SumValue = $Page->Smry[1][3]; // Load SUM
			$Page->premi_hadir->Count = $Page->Cnt[1][4];
			$Page->premi_hadir->SumValue = $Page->Smry[1][4]; // Load SUM
			$Page->premi_malam->Count = $Page->Cnt[1][5];
			$Page->premi_malam->SumValue = $Page->Smry[1][5]; // Load SUM
			$Page->pot_absen->Count = $Page->Cnt[1][6];
			$Page->pot_absen->SumValue = $Page->Smry[1][6]; // Load SUM
			$Page->upah2->Count = $Page->Cnt[1][7];
			$Page->upah2->SumValue = $Page->Smry[1][7]; // Load SUM
			$Page->premi_malam2->Count = $Page->Cnt[1][8];
			$Page->premi_malam2->SumValue = $Page->Smry[1][8]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 1;
			$Page->RenderRow();
?>
<?php if ($Page->lapgroup_nama->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->lapgroup_nama->Visible) { ?>
		<td data-field="lapgroup_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>
	<?php if ($Page->lapgroup_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->lapgroup_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->pembagian2_nama->Visible) { ?>
		<td data-field="pembagian2_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>
	<?php if ($Page->pembagian2_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pembagian2_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->pegawai_nama->Visible) { ?>
		<td data-field="pegawai_nama"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>
	<?php if ($Page->pegawai_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 3) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->pegawai_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->lapgroup_nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->lapgroup_nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->premi_malam2->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->lapgroup_nama->GroupViewValue, $Page->lapgroup_nama->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[1][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 0) ?>"<?php echo $Page->lapgroup_nama->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->lapgroup_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
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
	$Page->GrpCounter[1] = 1;
	$Page->GrpCounter[0] = 1;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php if (($Page->StopGrp - $Page->StartGrp + 1) <> $Page->TotalGrps) { ?>
<?php
	$Page->upah->Count = $Page->Cnt[0][3];
	$Page->upah->SumValue = $Page->Smry[0][3]; // Load SUM
	$Page->premi_hadir->Count = $Page->Cnt[0][4];
	$Page->premi_hadir->SumValue = $Page->Smry[0][4]; // Load SUM
	$Page->premi_malam->Count = $Page->Cnt[0][5];
	$Page->premi_malam->SumValue = $Page->Smry[0][5]; // Load SUM
	$Page->pot_absen->Count = $Page->Cnt[0][6];
	$Page->pot_absen->SumValue = $Page->Smry[0][6]; // Load SUM
	$Page->upah2->Count = $Page->Cnt[0][7];
	$Page->upah2->SumValue = $Page->Smry[0][7]; // Load SUM
	$Page->premi_malam2->Count = $Page->Cnt[0][8];
	$Page->premi_malam2->SumValue = $Page->Smry[0][8]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_PAGE;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptPageSummary";
	$Page->RenderRow();
?>
<?php if ($Page->lapgroup_nama->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptPageSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->Cnt[0][0],0,-2,-2,-2) ?></span>)</td></tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nip->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->upah->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_hadir->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pot_absen->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->upah2->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptPageSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[0][0],0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nip->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->upah->CellAttributes() ?>>
<span data-class="tpps_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_hadir->CellAttributes() ?>>
<span data-class="tpps_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam->CellAttributes() ?>>
<span data-class="tpps_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pot_absen->CellAttributes() ?>>
<span data-class="tpps_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->upah2->CellAttributes() ?>>
<span data-class="tpps_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpps_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php } ?>
<?php
	$Page->upah->Count = $Page->GrandCnt[3];
	$Page->upah->SumValue = $Page->GrandSmry[3]; // Load SUM
	$Page->premi_hadir->Count = $Page->GrandCnt[4];
	$Page->premi_hadir->SumValue = $Page->GrandSmry[4]; // Load SUM
	$Page->premi_malam->Count = $Page->GrandCnt[5];
	$Page->premi_malam->SumValue = $Page->GrandSmry[5]; // Load SUM
	$Page->pot_absen->Count = $Page->GrandCnt[6];
	$Page->pot_absen->SumValue = $Page->GrandSmry[6]; // Load SUM
	$Page->upah2->Count = $Page->GrandCnt[7];
	$Page->upah2->SumValue = $Page->GrandSmry[7]; // Load SUM
	$Page->premi_malam2->Count = $Page->GrandCnt[8];
	$Page->premi_malam2->SumValue = $Page->GrandSmry[8]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->lapgroup_nama->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nip->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->upah->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_hadir->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pot_absen->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->upah2->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->pegawai_nip->Visible) { ?>
		<td data-field="pegawai_nip"<?php echo $Page->pegawai_nip->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->upah->Visible) { ?>
		<td data-field="upah"<?php echo $Page->upah->CellAttributes() ?>>
<span data-class="tpts_r_lapgjhrn_upah"<?php echo $Page->upah->ViewAttributes() ?>><?php echo $Page->upah->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_hadir->Visible) { ?>
		<td data-field="premi_hadir"<?php echo $Page->premi_hadir->CellAttributes() ?>>
<span data-class="tpts_r_lapgjhrn_premi_hadir"<?php echo $Page->premi_hadir->ViewAttributes() ?>><?php echo $Page->premi_hadir->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam->Visible) { ?>
		<td data-field="premi_malam"<?php echo $Page->premi_malam->CellAttributes() ?>>
<span data-class="tpts_r_lapgjhrn_premi_malam"<?php echo $Page->premi_malam->ViewAttributes() ?>><?php echo $Page->premi_malam->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->pot_absen->Visible) { ?>
		<td data-field="pot_absen"<?php echo $Page->pot_absen->CellAttributes() ?>>
<span data-class="tpts_r_lapgjhrn_pot_absen"<?php echo $Page->pot_absen->ViewAttributes() ?>><?php echo $Page->pot_absen->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->upah2->Visible) { ?>
		<td data-field="upah2"<?php echo $Page->upah2->CellAttributes() ?>>
<span data-class="tpts_r_lapgjhrn_upah2"<?php echo $Page->upah2->ViewAttributes() ?>><?php echo $Page->upah2->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->premi_malam2->Visible) { ?>
		<td data-field="premi_malam2"<?php echo $Page->premi_malam2->CellAttributes() ?>>
<span data-class="tpts_r_lapgjhrn_premi_malam2"<?php echo $Page->premi_malam2->ViewAttributes() ?>><?php echo $Page->premi_malam2->SumViewValue ?></span></td>
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
<?php include "r_lapgjhrnsmrypager.php" ?>
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
<?php include "r_lapgjhrnsmrypager.php" ?>
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
<?php if ($Page->Export <> "" || $Page->UseCustomTemplate) { ?>
<div id="tpd_r_lapgjhrnsummary"></div>
<script id="tpm_r_lapgjhrnsummary" type="text/html">
<div id="ct_r_lapgjhrn_summary"><table class="ewTable ewExportTable">
	<tr>
		<th align="center"><?php echo $r_lapgjhrn->lapgroup_nama->FldCaption() ?></th>
		<th align="center"><?php echo $r_lapgjhrn->pembagian2_nama->FldCaption() ?></th>
		<th align="center"><?php echo $r_lapgjhrn->pegawai_nama->FldCaption() ?></th>
		<th align="center"><?php echo $r_lapgjhrn->pegawai_nip->FldCaption() ?></th>
		<th align="center"><?php echo $r_lapgjhrn->upah2->FldCaption() ?></th>
		<th align="center"><?php echo $r_lapgjhrn->premi_malam2->FldCaption() ?></th>
	</tr>
<?php
$cnt = count($Page->GrpIdx) - 1;
for ($i = 1; $i <= $cnt; $i++) {
?>
<tr>
	<td>{{include tmpl="#tpx<?php echo $i ?>_r_lapgjhrn_lapgroup_nama"/}}</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<?php
$cnt1 = count($Page->GrpIdx[$i]) - 1;
for ($i1 = 1; $i1 <= $cnt1; $i1++) {
?>
<tr>
	<td>&nbsp;</td>
	<td>{{include tmpl="#tpx<?php echo $i ?>_<?php echo $i1 ?>_r_lapgjhrn_pembagian2_nama"/}}</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<?php
$cnt2 = count($Page->GrpIdx[$i][$i1]) - 1;
for ($i2 = 1; $i2 <= $cnt2; $i2++) {
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>{{include tmpl="#tpx<?php echo $i ?>_<?php echo $i1 ?>_<?php echo $i2 ?>_r_lapgjhrn_pegawai_nama"/}}</td>
	<td>{{:pegawai_nip}}</td>
	<td>{{include tmpl="#tpgs<?php echo $i ?>_<?php echo $i1 ?>_<?php echo $i2 ?>_r_lapgjhrn_upah2"/}}</td>
	<td>{{include tmpl="#tpgs<?php echo $i ?>_<?php echo $i1 ?>_<?php echo $i2 ?>_r_lapgjhrn_premi_malam2"/}}</td>
</tr>
<?php
}
?>
<?php
}
?>
<?php
if ($r_lapgjhrn->ExportPageBreakCount > 0) {
if ($i % $r_lapgjhrn->ExportPageBreakCount == 0 && $i < $cnt) {
?>
{{include tmpl="#tpb<?php echo $i ?>_r_lapgjhrn"/}}
<?php
}
}
}
?>
</table>
</div>
</script>
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
<?php if ($Page->Export == "" && !$Page->DrillDown || $Page->Export <> "" && $Page->CustomExport <> "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php if ($Page->Export <> "" || $Page->UseCustomTemplate) { ?>
<script type="text/javascript">
ewr_ApplyTemplate("tpd_r_lapgjhrnsummary", "tpm_r_lapgjhrnsummary", "r_lapgjhrnsummary", "<?php echo $Page->CustomExport ?>", <?php echo ewr_JsonEncode($Page->FirstRowData) ?>);
</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
