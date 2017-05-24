<?php include_once "t_pengecualian_peginfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php

//
// Page class
//

$t_pengecualian_peg_grid = NULL; // Initialize page object first

class ct_pengecualian_peg_grid extends ct_pengecualian_peg {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 't_pengecualian_peg';

	// Page object name
	var $PageObjName = 't_pengecualian_peg_grid';

	// Grid form hidden field names
	var $FormName = 'ft_pengecualian_peggrid';
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_pengecualian_peg)
		if (!isset($GLOBALS["t_pengecualian_peg"]) || get_class($GLOBALS["t_pengecualian_peg"]) == "ct_pengecualian_peg") {
			$GLOBALS["t_pengecualian_peg"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["t_pengecualian_peg"];

		}
		$this->AddUrl = "t_pengecualian_pegadd.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Setup other options
		$this->SetupOtherOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

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

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
		$this->Page_Redirecting($url);

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
	var $ShowOtherOptions = FALSE;
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

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "pegawai") {
			global $pegawai;
			$rsmaster = $pegawai->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("pegawailist.php"); // Return to master page
			} else {
				$pegawai->LoadListRowValues($rsmaster);
				$pegawai->RowType = EW_ROWTYPE_MASTER; // Master row
				$pegawai->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

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

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
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
			$this->pengecualian_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->pengecualian_id->FormValue))
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
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
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
					$sKey .= $this->pengecualian_id->CurrentValue;

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
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

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
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
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
		if ($objForm->HasValue("x_pegawai_id") && $objForm->HasValue("o_pegawai_id") && $this->pegawai_id->CurrentValue <> $this->pegawai_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jns_id") && $objForm->HasValue("o_jns_id") && $this->jns_id->CurrentValue <> $this->jns_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl") && $objForm->HasValue("o_tgl") && $this->tgl->CurrentValue <> $this->tgl->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl2") && $objForm->HasValue("o_tgl2") && $this->tgl2->CurrentValue <> $this->tgl2->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jam_masuk") && $objForm->HasValue("o_jam_masuk") && $this->jam_masuk->CurrentValue <> $this->jam_masuk->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jam_keluar") && $objForm->HasValue("o_jam_keluar") && $this->jam_keluar->CurrentValue <> $this->jam_keluar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pegawai_id2") && $objForm->HasValue("o_pegawai_id2") && $this->pegawai_id2->CurrentValue <> $this->pegawai_id2->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pegawai_id3") && $objForm->HasValue("o_pegawai_id3") && $this->pegawai_id3->CurrentValue <> $this->pegawai_id3->OldValue)
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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->pegawai_id->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
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
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
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
		if ($this->CurrentMode == "view") { // View mode

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
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}
		} // End View mode
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->pengecualian_id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('pengecualian_id');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Add
		if ($this->CurrentMode == "view") { // Check view mode
			$item = &$option->Add("add");
			$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
			$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		}
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = $Security->CanAdd();
				$this->ShowOtherOptions = $item->Visible;
			}
		}
		if ($this->CurrentMode == "view") { // Check view mode
			$option = &$options["addedit"];
			$item = &$option->GetItem("add");
			$this->ShowOtherOptions = $item && $item->Visible;
		}
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->pegawai_id->CurrentValue = NULL;
		$this->pegawai_id->OldValue = $this->pegawai_id->CurrentValue;
		$this->jns_id->CurrentValue = NULL;
		$this->jns_id->OldValue = $this->jns_id->CurrentValue;
		$this->tgl->CurrentValue = NULL;
		$this->tgl->OldValue = $this->tgl->CurrentValue;
		$this->tgl2->CurrentValue = NULL;
		$this->tgl2->OldValue = $this->tgl2->CurrentValue;
		$this->jam_masuk->CurrentValue = NULL;
		$this->jam_masuk->OldValue = $this->jam_masuk->CurrentValue;
		$this->jam_keluar->CurrentValue = NULL;
		$this->jam_keluar->OldValue = $this->jam_keluar->CurrentValue;
		$this->pegawai_id2->CurrentValue = NULL;
		$this->pegawai_id2->OldValue = $this->pegawai_id2->CurrentValue;
		$this->pegawai_id3->CurrentValue = NULL;
		$this->pegawai_id3->OldValue = $this->pegawai_id3->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		$this->pegawai_id->setOldValue($objForm->GetValue("o_pegawai_id"));
		if (!$this->jns_id->FldIsDetailKey) {
			$this->jns_id->setFormValue($objForm->GetValue("x_jns_id"));
		}
		$this->jns_id->setOldValue($objForm->GetValue("o_jns_id"));
		if (!$this->tgl->FldIsDetailKey) {
			$this->tgl->setFormValue($objForm->GetValue("x_tgl"));
			$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		}
		$this->tgl->setOldValue($objForm->GetValue("o_tgl"));
		if (!$this->tgl2->FldIsDetailKey) {
			$this->tgl2->setFormValue($objForm->GetValue("x_tgl2"));
			$this->tgl2->CurrentValue = ew_UnFormatDateTime($this->tgl2->CurrentValue, 0);
		}
		$this->tgl2->setOldValue($objForm->GetValue("o_tgl2"));
		if (!$this->jam_masuk->FldIsDetailKey) {
			$this->jam_masuk->setFormValue($objForm->GetValue("x_jam_masuk"));
			$this->jam_masuk->CurrentValue = ew_UnFormatDateTime($this->jam_masuk->CurrentValue, 9);
		}
		$this->jam_masuk->setOldValue($objForm->GetValue("o_jam_masuk"));
		if (!$this->jam_keluar->FldIsDetailKey) {
			$this->jam_keluar->setFormValue($objForm->GetValue("x_jam_keluar"));
			$this->jam_keluar->CurrentValue = ew_UnFormatDateTime($this->jam_keluar->CurrentValue, 9);
		}
		$this->jam_keluar->setOldValue($objForm->GetValue("o_jam_keluar"));
		if (!$this->pegawai_id2->FldIsDetailKey) {
			$this->pegawai_id2->setFormValue($objForm->GetValue("x_pegawai_id2"));
		}
		$this->pegawai_id2->setOldValue($objForm->GetValue("o_pegawai_id2"));
		if (!$this->pegawai_id3->FldIsDetailKey) {
			$this->pegawai_id3->setFormValue($objForm->GetValue("x_pegawai_id3"));
		}
		$this->pegawai_id3->setOldValue($objForm->GetValue("o_pegawai_id3"));
		if (!$this->pengecualian_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->pengecualian_id->setFormValue($objForm->GetValue("x_pengecualian_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->pengecualian_id->CurrentValue = strval($arKeys[0]); // pengecualian_id
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

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
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditCustomAttributes = "";
			if ($this->pegawai_id->getSessionValue() <> "") {
				$this->pegawai_id->CurrentValue = $this->pegawai_id->getSessionValue();
				$this->pegawai_id->OldValue = $this->pegawai_id->CurrentValue;
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

			// Add refer script
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pegawai_id
			$this->pegawai_id->EditCustomAttributes = "";
			if ($this->pegawai_id->getSessionValue() <> "") {
				$this->pegawai_id->CurrentValue = $this->pegawai_id->getSessionValue();
				$this->pegawai_id->OldValue = $this->pegawai_id->CurrentValue;
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
				$sThisKey .= $row['pengecualian_id'];
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "pegawai") {
				$this->pegawai_id->CurrentValue = $this->pegawai_id->getSessionValue();
			}

		// Check referential integrity for master table 'pegawai'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_pegawai();
		if (strval($this->pegawai_id->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, "DB"), $sMasterFilter);
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
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// pegawai_id
		$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, FALSE);

		// jns_id
		$this->jns_id->SetDbValueDef($rsnew, $this->jns_id->CurrentValue, 0, FALSE);

		// tgl
		$this->tgl->SetDbValueDef($rsnew, $this->tgl->CurrentValue, ew_CurrentDate(), FALSE);

		// tgl2
		$this->tgl2->SetDbValueDef($rsnew, $this->tgl2->CurrentValue, ew_CurrentDate(), FALSE);

		// jam_masuk
		$this->jam_masuk->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jam_masuk->CurrentValue, 9), NULL, FALSE);

		// jam_keluar
		$this->jam_keluar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jam_keluar->CurrentValue, 9), NULL, FALSE);

		// pegawai_id2
		$this->pegawai_id2->SetDbValueDef($rsnew, $this->pegawai_id2->CurrentValue, NULL, FALSE);

		// pegawai_id3
		$this->pegawai_id3->SetDbValueDef($rsnew, $this->pegawai_id3->CurrentValue, NULL, FALSE);

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

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "pegawai") {
			$this->pegawai_id->Visible = FALSE;
			if ($GLOBALS["pegawai"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
