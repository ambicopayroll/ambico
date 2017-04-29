<?php

// Global variable for table object
$t_rumus2 = NULL;

//
// Table class for t_rumus2
//
class ct_rumus2 extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $rumus2_id;
	var $rumus2_nama;
	var $gol_hk;
	var $premi_hadir;
	var $premi_malam;
	var $lp;
	var $forklift;
	var $pot_absen;
	var $pot_aspen;
	var $pot_bpjs;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_rumus2';
		$this->TableName = 't_rumus2';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_rumus2`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// rumus2_id
		$this->rumus2_id = new cField('t_rumus2', 't_rumus2', 'x_rumus2_id', 'rumus2_id', '`rumus2_id`', '`rumus2_id`', 3, -1, FALSE, '`rumus2_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->rumus2_id->Sortable = TRUE; // Allow sort
		$this->rumus2_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['rumus2_id'] = &$this->rumus2_id;

		// rumus2_nama
		$this->rumus2_nama = new cField('t_rumus2', 't_rumus2', 'x_rumus2_nama', 'rumus2_nama', '`rumus2_nama`', '`rumus2_nama`', 200, -1, FALSE, '`rumus2_nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rumus2_nama->Sortable = TRUE; // Allow sort
		$this->fields['rumus2_nama'] = &$this->rumus2_nama;

		// gol_hk
		$this->gol_hk = new cField('t_rumus2', 't_rumus2', 'x_gol_hk', 'gol_hk', '`gol_hk`', '`gol_hk`', 16, -1, FALSE, '`gol_hk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->gol_hk->Sortable = TRUE; // Allow sort
		$this->gol_hk->OptionCount = 2;
		$this->gol_hk->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['gol_hk'] = &$this->gol_hk;

		// premi_hadir
		$this->premi_hadir = new cField('t_rumus2', 't_rumus2', 'x_premi_hadir', 'premi_hadir', '`premi_hadir`', '`premi_hadir`', 4, -1, FALSE, '`premi_hadir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->premi_hadir->Sortable = TRUE; // Allow sort
		$this->premi_hadir->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['premi_hadir'] = &$this->premi_hadir;

		// premi_malam
		$this->premi_malam = new cField('t_rumus2', 't_rumus2', 'x_premi_malam', 'premi_malam', '`premi_malam`', '`premi_malam`', 4, -1, FALSE, '`premi_malam`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->premi_malam->Sortable = TRUE; // Allow sort
		$this->premi_malam->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['premi_malam'] = &$this->premi_malam;

		// lp
		$this->lp = new cField('t_rumus2', 't_rumus2', 'x_lp', 'lp', '`lp`', '`lp`', 4, -1, FALSE, '`lp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lp->Sortable = TRUE; // Allow sort
		$this->lp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['lp'] = &$this->lp;

		// forklift
		$this->forklift = new cField('t_rumus2', 't_rumus2', 'x_forklift', 'forklift', '`forklift`', '`forklift`', 4, -1, FALSE, '`forklift`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->forklift->Sortable = TRUE; // Allow sort
		$this->forklift->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['forklift'] = &$this->forklift;

		// pot_absen
		$this->pot_absen = new cField('t_rumus2', 't_rumus2', 'x_pot_absen', 'pot_absen', '`pot_absen`', '`pot_absen`', 4, -1, FALSE, '`pot_absen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pot_absen->Sortable = TRUE; // Allow sort
		$this->pot_absen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pot_absen'] = &$this->pot_absen;

		// pot_aspen
		$this->pot_aspen = new cField('t_rumus2', 't_rumus2', 'x_pot_aspen', 'pot_aspen', '`pot_aspen`', '`pot_aspen`', 4, -1, FALSE, '`pot_aspen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pot_aspen->Sortable = TRUE; // Allow sort
		$this->pot_aspen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pot_aspen'] = &$this->pot_aspen;

		// pot_bpjs
		$this->pot_bpjs = new cField('t_rumus2', 't_rumus2', 'x_pot_bpjs', 'pot_bpjs', '`pot_bpjs`', '`pot_bpjs`', 4, -1, FALSE, '`pot_bpjs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pot_bpjs->Sortable = TRUE; // Allow sort
		$this->pot_bpjs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pot_bpjs'] = &$this->pot_bpjs;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_rumus2`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->rumus2_id->setDbValue($conn->Insert_ID());
			$rs['rumus2_id'] = $this->rumus2_id->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'rumus2_id';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsaudit, $rsold);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('rumus2_id', $rs))
				ew_AddFilter($where, ew_QuotedName('rumus2_id', $this->DBID) . '=' . ew_QuotedValue($rs['rumus2_id'], $this->rumus2_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`rumus2_id` = @rumus2_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->rumus2_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@rumus2_id@", ew_AdjustSql($this->rumus2_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "t_rumus2list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_rumus2list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_rumus2view.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_rumus2view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_rumus2add.php?" . $this->UrlParm($parm);
		else
			$url = "t_rumus2add.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("t_rumus2edit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("t_rumus2add.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_rumus2delete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "rumus2_id:" . ew_VarToJson($this->rumus2_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->rumus2_id->CurrentValue)) {
			$sUrl .= "rumus2_id=" . urlencode($this->rumus2_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["rumus2_id"]))
				$arKeys[] = ew_StripSlashes($_POST["rumus2_id"]);
			elseif (isset($_GET["rumus2_id"]))
				$arKeys[] = ew_StripSlashes($_GET["rumus2_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->rumus2_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->rumus2_id->setDbValue($rs->fields('rumus2_id'));
		$this->rumus2_nama->setDbValue($rs->fields('rumus2_nama'));
		$this->gol_hk->setDbValue($rs->fields('gol_hk'));
		$this->premi_hadir->setDbValue($rs->fields('premi_hadir'));
		$this->premi_malam->setDbValue($rs->fields('premi_malam'));
		$this->lp->setDbValue($rs->fields('lp'));
		$this->forklift->setDbValue($rs->fields('forklift'));
		$this->pot_absen->setDbValue($rs->fields('pot_absen'));
		$this->pot_aspen->setDbValue($rs->fields('pot_aspen'));
		$this->pot_bpjs->setDbValue($rs->fields('pot_bpjs'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// rumus2_id
		// rumus2_nama
		// gol_hk
		// premi_hadir
		// premi_malam
		// lp
		// forklift
		// pot_absen
		// pot_aspen
		// pot_bpjs
		// rumus2_id

		$this->rumus2_id->ViewValue = $this->rumus2_id->CurrentValue;
		$this->rumus2_id->ViewCustomAttributes = "";

		// rumus2_nama
		$this->rumus2_nama->ViewValue = $this->rumus2_nama->CurrentValue;
		$this->rumus2_nama->ViewCustomAttributes = "";

		// gol_hk
		if (strval($this->gol_hk->CurrentValue) <> "") {
			$this->gol_hk->ViewValue = $this->gol_hk->OptionCaption($this->gol_hk->CurrentValue);
		} else {
			$this->gol_hk->ViewValue = NULL;
		}
		$this->gol_hk->ViewCustomAttributes = "";

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

		// lp
		$this->lp->ViewValue = $this->lp->CurrentValue;
		$this->lp->ViewValue = ew_FormatNumber($this->lp->ViewValue, 0, -2, -2, -2);
		$this->lp->CellCssStyle .= "text-align: right;";
		$this->lp->ViewCustomAttributes = "";

		// forklift
		$this->forklift->ViewValue = $this->forklift->CurrentValue;
		$this->forklift->ViewValue = ew_FormatNumber($this->forklift->ViewValue, 0, -2, -2, -2);
		$this->forklift->CellCssStyle .= "text-align: right;";
		$this->forklift->ViewCustomAttributes = "";

		// pot_absen
		$this->pot_absen->ViewValue = $this->pot_absen->CurrentValue;
		$this->pot_absen->ViewValue = ew_FormatNumber($this->pot_absen->ViewValue, 0, -2, -2, -2);
		$this->pot_absen->CellCssStyle .= "text-align: right;";
		$this->pot_absen->ViewCustomAttributes = "";

		// pot_aspen
		$this->pot_aspen->ViewValue = $this->pot_aspen->CurrentValue;
		$this->pot_aspen->ViewValue = ew_FormatNumber($this->pot_aspen->ViewValue, 2, -2, -2, -2);
		$this->pot_aspen->CellCssStyle .= "text-align: right;";
		$this->pot_aspen->ViewCustomAttributes = "";

		// pot_bpjs
		$this->pot_bpjs->ViewValue = $this->pot_bpjs->CurrentValue;
		$this->pot_bpjs->ViewValue = ew_FormatNumber($this->pot_bpjs->ViewValue, 2, -2, -2, -2);
		$this->pot_bpjs->CellCssStyle .= "text-align: right;";
		$this->pot_bpjs->ViewCustomAttributes = "";

		// rumus2_id
		$this->rumus2_id->LinkCustomAttributes = "";
		$this->rumus2_id->HrefValue = "";
		$this->rumus2_id->TooltipValue = "";

		// rumus2_nama
		$this->rumus2_nama->LinkCustomAttributes = "";
		$this->rumus2_nama->HrefValue = "";
		$this->rumus2_nama->TooltipValue = "";

		// gol_hk
		$this->gol_hk->LinkCustomAttributes = "";
		$this->gol_hk->HrefValue = "";
		$this->gol_hk->TooltipValue = "";

		// premi_hadir
		$this->premi_hadir->LinkCustomAttributes = "";
		$this->premi_hadir->HrefValue = "";
		$this->premi_hadir->TooltipValue = "";

		// premi_malam
		$this->premi_malam->LinkCustomAttributes = "";
		$this->premi_malam->HrefValue = "";
		$this->premi_malam->TooltipValue = "";

		// lp
		$this->lp->LinkCustomAttributes = "";
		$this->lp->HrefValue = "";
		$this->lp->TooltipValue = "";

		// forklift
		$this->forklift->LinkCustomAttributes = "";
		$this->forklift->HrefValue = "";
		$this->forklift->TooltipValue = "";

		// pot_absen
		$this->pot_absen->LinkCustomAttributes = "";
		$this->pot_absen->HrefValue = "";
		$this->pot_absen->TooltipValue = "";

		// pot_aspen
		$this->pot_aspen->LinkCustomAttributes = "";
		$this->pot_aspen->HrefValue = "";
		$this->pot_aspen->TooltipValue = "";

		// pot_bpjs
		$this->pot_bpjs->LinkCustomAttributes = "";
		$this->pot_bpjs->HrefValue = "";
		$this->pot_bpjs->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// rumus2_id
		$this->rumus2_id->EditAttrs["class"] = "form-control";
		$this->rumus2_id->EditCustomAttributes = "";
		$this->rumus2_id->EditValue = $this->rumus2_id->CurrentValue;
		$this->rumus2_id->ViewCustomAttributes = "";

		// rumus2_nama
		$this->rumus2_nama->EditAttrs["class"] = "form-control";
		$this->rumus2_nama->EditCustomAttributes = "";
		$this->rumus2_nama->EditValue = $this->rumus2_nama->CurrentValue;
		$this->rumus2_nama->PlaceHolder = ew_RemoveHtml($this->rumus2_nama->FldCaption());

		// gol_hk
		$this->gol_hk->EditCustomAttributes = "";
		$this->gol_hk->EditValue = $this->gol_hk->Options(FALSE);

		// premi_hadir
		$this->premi_hadir->EditAttrs["class"] = "form-control";
		$this->premi_hadir->EditCustomAttributes = "";
		$this->premi_hadir->EditValue = $this->premi_hadir->CurrentValue;
		$this->premi_hadir->PlaceHolder = ew_RemoveHtml($this->premi_hadir->FldCaption());
		if (strval($this->premi_hadir->EditValue) <> "" && is_numeric($this->premi_hadir->EditValue)) $this->premi_hadir->EditValue = ew_FormatNumber($this->premi_hadir->EditValue, -2, -2, -2, -2);

		// premi_malam
		$this->premi_malam->EditAttrs["class"] = "form-control";
		$this->premi_malam->EditCustomAttributes = "";
		$this->premi_malam->EditValue = $this->premi_malam->CurrentValue;
		$this->premi_malam->PlaceHolder = ew_RemoveHtml($this->premi_malam->FldCaption());
		if (strval($this->premi_malam->EditValue) <> "" && is_numeric($this->premi_malam->EditValue)) $this->premi_malam->EditValue = ew_FormatNumber($this->premi_malam->EditValue, -2, -2, -2, -2);

		// lp
		$this->lp->EditAttrs["class"] = "form-control";
		$this->lp->EditCustomAttributes = "";
		$this->lp->EditValue = $this->lp->CurrentValue;
		$this->lp->PlaceHolder = ew_RemoveHtml($this->lp->FldCaption());
		if (strval($this->lp->EditValue) <> "" && is_numeric($this->lp->EditValue)) $this->lp->EditValue = ew_FormatNumber($this->lp->EditValue, -2, -2, -2, -2);

		// forklift
		$this->forklift->EditAttrs["class"] = "form-control";
		$this->forklift->EditCustomAttributes = "";
		$this->forklift->EditValue = $this->forklift->CurrentValue;
		$this->forklift->PlaceHolder = ew_RemoveHtml($this->forklift->FldCaption());
		if (strval($this->forklift->EditValue) <> "" && is_numeric($this->forklift->EditValue)) $this->forklift->EditValue = ew_FormatNumber($this->forklift->EditValue, -2, -2, -2, -2);

		// pot_absen
		$this->pot_absen->EditAttrs["class"] = "form-control";
		$this->pot_absen->EditCustomAttributes = "";
		$this->pot_absen->EditValue = $this->pot_absen->CurrentValue;
		$this->pot_absen->PlaceHolder = ew_RemoveHtml($this->pot_absen->FldCaption());
		if (strval($this->pot_absen->EditValue) <> "" && is_numeric($this->pot_absen->EditValue)) $this->pot_absen->EditValue = ew_FormatNumber($this->pot_absen->EditValue, -2, -2, -2, -2);

		// pot_aspen
		$this->pot_aspen->EditAttrs["class"] = "form-control";
		$this->pot_aspen->EditCustomAttributes = "";
		$this->pot_aspen->EditValue = $this->pot_aspen->CurrentValue;
		$this->pot_aspen->PlaceHolder = ew_RemoveHtml($this->pot_aspen->FldCaption());
		if (strval($this->pot_aspen->EditValue) <> "" && is_numeric($this->pot_aspen->EditValue)) $this->pot_aspen->EditValue = ew_FormatNumber($this->pot_aspen->EditValue, -2, -2, -2, -2);

		// pot_bpjs
		$this->pot_bpjs->EditAttrs["class"] = "form-control";
		$this->pot_bpjs->EditCustomAttributes = "";
		$this->pot_bpjs->EditValue = $this->pot_bpjs->CurrentValue;
		$this->pot_bpjs->PlaceHolder = ew_RemoveHtml($this->pot_bpjs->FldCaption());
		if (strval($this->pot_bpjs->EditValue) <> "" && is_numeric($this->pot_bpjs->EditValue)) $this->pot_bpjs->EditValue = ew_FormatNumber($this->pot_bpjs->EditValue, -2, -2, -2, -2);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->rumus2_nama->Exportable) $Doc->ExportCaption($this->rumus2_nama);
					if ($this->gol_hk->Exportable) $Doc->ExportCaption($this->gol_hk);
					if ($this->premi_hadir->Exportable) $Doc->ExportCaption($this->premi_hadir);
					if ($this->premi_malam->Exportable) $Doc->ExportCaption($this->premi_malam);
					if ($this->lp->Exportable) $Doc->ExportCaption($this->lp);
					if ($this->forklift->Exportable) $Doc->ExportCaption($this->forklift);
					if ($this->pot_absen->Exportable) $Doc->ExportCaption($this->pot_absen);
					if ($this->pot_aspen->Exportable) $Doc->ExportCaption($this->pot_aspen);
					if ($this->pot_bpjs->Exportable) $Doc->ExportCaption($this->pot_bpjs);
				} else {
					if ($this->rumus2_id->Exportable) $Doc->ExportCaption($this->rumus2_id);
					if ($this->rumus2_nama->Exportable) $Doc->ExportCaption($this->rumus2_nama);
					if ($this->gol_hk->Exportable) $Doc->ExportCaption($this->gol_hk);
					if ($this->premi_hadir->Exportable) $Doc->ExportCaption($this->premi_hadir);
					if ($this->premi_malam->Exportable) $Doc->ExportCaption($this->premi_malam);
					if ($this->lp->Exportable) $Doc->ExportCaption($this->lp);
					if ($this->forklift->Exportable) $Doc->ExportCaption($this->forklift);
					if ($this->pot_absen->Exportable) $Doc->ExportCaption($this->pot_absen);
					if ($this->pot_aspen->Exportable) $Doc->ExportCaption($this->pot_aspen);
					if ($this->pot_bpjs->Exportable) $Doc->ExportCaption($this->pot_bpjs);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->rumus2_nama->Exportable) $Doc->ExportField($this->rumus2_nama);
						if ($this->gol_hk->Exportable) $Doc->ExportField($this->gol_hk);
						if ($this->premi_hadir->Exportable) $Doc->ExportField($this->premi_hadir);
						if ($this->premi_malam->Exportable) $Doc->ExportField($this->premi_malam);
						if ($this->lp->Exportable) $Doc->ExportField($this->lp);
						if ($this->forklift->Exportable) $Doc->ExportField($this->forklift);
						if ($this->pot_absen->Exportable) $Doc->ExportField($this->pot_absen);
						if ($this->pot_aspen->Exportable) $Doc->ExportField($this->pot_aspen);
						if ($this->pot_bpjs->Exportable) $Doc->ExportField($this->pot_bpjs);
					} else {
						if ($this->rumus2_id->Exportable) $Doc->ExportField($this->rumus2_id);
						if ($this->rumus2_nama->Exportable) $Doc->ExportField($this->rumus2_nama);
						if ($this->gol_hk->Exportable) $Doc->ExportField($this->gol_hk);
						if ($this->premi_hadir->Exportable) $Doc->ExportField($this->premi_hadir);
						if ($this->premi_malam->Exportable) $Doc->ExportField($this->premi_malam);
						if ($this->lp->Exportable) $Doc->ExportField($this->lp);
						if ($this->forklift->Exportable) $Doc->ExportField($this->forklift);
						if ($this->pot_absen->Exportable) $Doc->ExportField($this->pot_absen);
						if ($this->pot_aspen->Exportable) $Doc->ExportField($this->pot_aspen);
						if ($this->pot_bpjs->Exportable) $Doc->ExportField($this->pot_bpjs);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_rumus2';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 't_rumus2';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['rumus2_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 't_rumus2';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['rumus2_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 't_rumus2';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['rumus2_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
