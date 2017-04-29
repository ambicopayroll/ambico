<?php

// Global variable for table object
$t_rumus = NULL;

//
// Table class for t_rumus
//
class ct_rumus extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $rumus_id;
	var $rumus_nama;
	var $hk_gol;
	var $umr;
	var $hk_jml;
	var $upah;
	var $premi_hadir;
	var $premi_malam;
	var $pot_absen;
	var $lembur;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_rumus';
		$this->TableName = 't_rumus';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_rumus`";
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

		// rumus_id
		$this->rumus_id = new cField('t_rumus', 't_rumus', 'x_rumus_id', 'rumus_id', '`rumus_id`', '`rumus_id`', 3, -1, FALSE, '`rumus_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->rumus_id->Sortable = TRUE; // Allow sort
		$this->rumus_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['rumus_id'] = &$this->rumus_id;

		// rumus_nama
		$this->rumus_nama = new cField('t_rumus', 't_rumus', 'x_rumus_nama', 'rumus_nama', '`rumus_nama`', '`rumus_nama`', 200, -1, FALSE, '`rumus_nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rumus_nama->Sortable = TRUE; // Allow sort
		$this->fields['rumus_nama'] = &$this->rumus_nama;

		// hk_gol
		$this->hk_gol = new cField('t_rumus', 't_rumus', 'x_hk_gol', 'hk_gol', '`hk_gol`', '`hk_gol`', 16, -1, FALSE, '`hk_gol`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->hk_gol->Sortable = TRUE; // Allow sort
		$this->hk_gol->OptionCount = 2;
		$this->hk_gol->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hk_gol'] = &$this->hk_gol;

		// umr
		$this->umr = new cField('t_rumus', 't_rumus', 'x_umr', 'umr', '`umr`', '`umr`', 4, -1, FALSE, '`umr`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->umr->Sortable = TRUE; // Allow sort
		$this->umr->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['umr'] = &$this->umr;

		// hk_jml
		$this->hk_jml = new cField('t_rumus', 't_rumus', 'x_hk_jml', 'hk_jml', '`hk_jml`', '`hk_jml`', 2, -1, FALSE, '`hk_jml`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hk_jml->Sortable = TRUE; // Allow sort
		$this->hk_jml->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hk_jml'] = &$this->hk_jml;

		// upah
		$this->upah = new cField('t_rumus', 't_rumus', 'x_upah', 'upah', '`upah`', '`upah`', 4, -1, FALSE, '`upah`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->upah->Sortable = TRUE; // Allow sort
		$this->upah->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['upah'] = &$this->upah;

		// premi_hadir
		$this->premi_hadir = new cField('t_rumus', 't_rumus', 'x_premi_hadir', 'premi_hadir', '`premi_hadir`', '`premi_hadir`', 4, -1, FALSE, '`premi_hadir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->premi_hadir->Sortable = TRUE; // Allow sort
		$this->premi_hadir->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['premi_hadir'] = &$this->premi_hadir;

		// premi_malam
		$this->premi_malam = new cField('t_rumus', 't_rumus', 'x_premi_malam', 'premi_malam', '`premi_malam`', '`premi_malam`', 4, -1, FALSE, '`premi_malam`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->premi_malam->Sortable = TRUE; // Allow sort
		$this->premi_malam->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['premi_malam'] = &$this->premi_malam;

		// pot_absen
		$this->pot_absen = new cField('t_rumus', 't_rumus', 'x_pot_absen', 'pot_absen', '`pot_absen`', '`pot_absen`', 4, -1, FALSE, '`pot_absen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pot_absen->Sortable = TRUE; // Allow sort
		$this->pot_absen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pot_absen'] = &$this->pot_absen;

		// lembur
		$this->lembur = new cField('t_rumus', 't_rumus', 'x_lembur', 'lembur', '`lembur`', '`lembur`', 4, -1, FALSE, '`lembur`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur->Sortable = TRUE; // Allow sort
		$this->lembur->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['lembur'] = &$this->lembur;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_rumus`";
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
			$this->rumus_id->setDbValue($conn->Insert_ID());
			$rs['rumus_id'] = $this->rumus_id->DbValue;
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
			$fldname = 'rumus_id';
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
			if (array_key_exists('rumus_id', $rs))
				ew_AddFilter($where, ew_QuotedName('rumus_id', $this->DBID) . '=' . ew_QuotedValue($rs['rumus_id'], $this->rumus_id->FldDataType, $this->DBID));
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
		return "`rumus_id` = @rumus_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->rumus_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@rumus_id@", ew_AdjustSql($this->rumus_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "t_rumuslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_rumuslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_rumusview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_rumusview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_rumusadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_rumusadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("t_rumusedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("t_rumusadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_rumusdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "rumus_id:" . ew_VarToJson($this->rumus_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->rumus_id->CurrentValue)) {
			$sUrl .= "rumus_id=" . urlencode($this->rumus_id->CurrentValue);
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
			if ($isPost && isset($_POST["rumus_id"]))
				$arKeys[] = ew_StripSlashes($_POST["rumus_id"]);
			elseif (isset($_GET["rumus_id"]))
				$arKeys[] = ew_StripSlashes($_GET["rumus_id"]);
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
			$this->rumus_id->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// rumus_id
		$this->rumus_id->LinkCustomAttributes = "";
		$this->rumus_id->HrefValue = "";
		$this->rumus_id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// rumus_id
		$this->rumus_id->EditAttrs["class"] = "form-control";
		$this->rumus_id->EditCustomAttributes = "";
		$this->rumus_id->EditValue = $this->rumus_id->CurrentValue;
		$this->rumus_id->ViewCustomAttributes = "";

		// rumus_nama
		$this->rumus_nama->EditAttrs["class"] = "form-control";
		$this->rumus_nama->EditCustomAttributes = "";
		$this->rumus_nama->EditValue = $this->rumus_nama->CurrentValue;
		$this->rumus_nama->PlaceHolder = ew_RemoveHtml($this->rumus_nama->FldCaption());

		// hk_gol
		$this->hk_gol->EditCustomAttributes = "";
		$this->hk_gol->EditValue = $this->hk_gol->Options(FALSE);

		// umr
		$this->umr->EditAttrs["class"] = "form-control";
		$this->umr->EditCustomAttributes = "";
		$this->umr->EditValue = $this->umr->CurrentValue;
		$this->umr->PlaceHolder = ew_RemoveHtml($this->umr->FldCaption());
		if (strval($this->umr->EditValue) <> "" && is_numeric($this->umr->EditValue)) $this->umr->EditValue = ew_FormatNumber($this->umr->EditValue, -2, -2, -2, -2);

		// hk_jml
		$this->hk_jml->EditAttrs["class"] = "form-control";
		$this->hk_jml->EditCustomAttributes = "";
		$this->hk_jml->EditValue = $this->hk_jml->CurrentValue;
		$this->hk_jml->PlaceHolder = ew_RemoveHtml($this->hk_jml->FldCaption());

		// upah
		$this->upah->EditAttrs["class"] = "form-control";
		$this->upah->EditCustomAttributes = "";
		$this->upah->EditValue = $this->upah->CurrentValue;
		$this->upah->PlaceHolder = ew_RemoveHtml($this->upah->FldCaption());
		if (strval($this->upah->EditValue) <> "" && is_numeric($this->upah->EditValue)) $this->upah->EditValue = ew_FormatNumber($this->upah->EditValue, -2, -2, -2, -2);

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

		// pot_absen
		$this->pot_absen->EditAttrs["class"] = "form-control";
		$this->pot_absen->EditCustomAttributes = "";
		$this->pot_absen->EditValue = $this->pot_absen->CurrentValue;
		$this->pot_absen->PlaceHolder = ew_RemoveHtml($this->pot_absen->FldCaption());
		if (strval($this->pot_absen->EditValue) <> "" && is_numeric($this->pot_absen->EditValue)) $this->pot_absen->EditValue = ew_FormatNumber($this->pot_absen->EditValue, -2, -2, -2, -2);

		// lembur
		$this->lembur->EditAttrs["class"] = "form-control";
		$this->lembur->EditCustomAttributes = "";
		$this->lembur->EditValue = $this->lembur->CurrentValue;
		$this->lembur->PlaceHolder = ew_RemoveHtml($this->lembur->FldCaption());
		if (strval($this->lembur->EditValue) <> "" && is_numeric($this->lembur->EditValue)) $this->lembur->EditValue = ew_FormatNumber($this->lembur->EditValue, -2, -2, -2, -2);

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
					if ($this->rumus_nama->Exportable) $Doc->ExportCaption($this->rumus_nama);
					if ($this->hk_gol->Exportable) $Doc->ExportCaption($this->hk_gol);
					if ($this->umr->Exportable) $Doc->ExportCaption($this->umr);
					if ($this->hk_jml->Exportable) $Doc->ExportCaption($this->hk_jml);
					if ($this->upah->Exportable) $Doc->ExportCaption($this->upah);
					if ($this->premi_hadir->Exportable) $Doc->ExportCaption($this->premi_hadir);
					if ($this->premi_malam->Exportable) $Doc->ExportCaption($this->premi_malam);
					if ($this->pot_absen->Exportable) $Doc->ExportCaption($this->pot_absen);
					if ($this->lembur->Exportable) $Doc->ExportCaption($this->lembur);
				} else {
					if ($this->rumus_id->Exportable) $Doc->ExportCaption($this->rumus_id);
					if ($this->rumus_nama->Exportable) $Doc->ExportCaption($this->rumus_nama);
					if ($this->hk_gol->Exportable) $Doc->ExportCaption($this->hk_gol);
					if ($this->umr->Exportable) $Doc->ExportCaption($this->umr);
					if ($this->hk_jml->Exportable) $Doc->ExportCaption($this->hk_jml);
					if ($this->upah->Exportable) $Doc->ExportCaption($this->upah);
					if ($this->premi_hadir->Exportable) $Doc->ExportCaption($this->premi_hadir);
					if ($this->premi_malam->Exportable) $Doc->ExportCaption($this->premi_malam);
					if ($this->pot_absen->Exportable) $Doc->ExportCaption($this->pot_absen);
					if ($this->lembur->Exportable) $Doc->ExportCaption($this->lembur);
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
						if ($this->rumus_nama->Exportable) $Doc->ExportField($this->rumus_nama);
						if ($this->hk_gol->Exportable) $Doc->ExportField($this->hk_gol);
						if ($this->umr->Exportable) $Doc->ExportField($this->umr);
						if ($this->hk_jml->Exportable) $Doc->ExportField($this->hk_jml);
						if ($this->upah->Exportable) $Doc->ExportField($this->upah);
						if ($this->premi_hadir->Exportable) $Doc->ExportField($this->premi_hadir);
						if ($this->premi_malam->Exportable) $Doc->ExportField($this->premi_malam);
						if ($this->pot_absen->Exportable) $Doc->ExportField($this->pot_absen);
						if ($this->lembur->Exportable) $Doc->ExportField($this->lembur);
					} else {
						if ($this->rumus_id->Exportable) $Doc->ExportField($this->rumus_id);
						if ($this->rumus_nama->Exportable) $Doc->ExportField($this->rumus_nama);
						if ($this->hk_gol->Exportable) $Doc->ExportField($this->hk_gol);
						if ($this->umr->Exportable) $Doc->ExportField($this->umr);
						if ($this->hk_jml->Exportable) $Doc->ExportField($this->hk_jml);
						if ($this->upah->Exportable) $Doc->ExportField($this->upah);
						if ($this->premi_hadir->Exportable) $Doc->ExportField($this->premi_hadir);
						if ($this->premi_malam->Exportable) $Doc->ExportField($this->premi_malam);
						if ($this->pot_absen->Exportable) $Doc->ExportField($this->pot_absen);
						if ($this->lembur->Exportable) $Doc->ExportField($this->lembur);
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
		$table = 't_rumus';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 't_rumus';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['rumus_id'];

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
		$table = 't_rumus';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['rumus_id'];

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
		$table = 't_rumus';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['rumus_id'];

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
