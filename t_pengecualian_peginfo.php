<?php

// Global variable for table object
$t_pengecualian_peg = NULL;

//
// Table class for t_pengecualian_peg
//
class ct_pengecualian_peg extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $pengecualian_id;
	var $pegawai_id;
	var $jns_id;
	var $tgl;
	var $tgl2;
	var $jam_masuk;
	var $jam_keluar;
	var $pegawai_id2;
	var $pegawai_id3;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_pengecualian_peg';
		$this->TableName = 't_pengecualian_peg';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_pengecualian_peg`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// pengecualian_id
		$this->pengecualian_id = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_pengecualian_id', 'pengecualian_id', '`pengecualian_id`', '`pengecualian_id`', 3, -1, FALSE, '`pengecualian_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->pengecualian_id->Sortable = TRUE; // Allow sort
		$this->pengecualian_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pengecualian_id'] = &$this->pengecualian_id;

		// pegawai_id
		$this->pegawai_id = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`EV__pegawai_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->pegawai_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// jns_id
		$this->jns_id = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_jns_id', 'jns_id', '`jns_id`', '`jns_id`', 3, -1, FALSE, '`EV__jns_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->jns_id->Sortable = TRUE; // Allow sort
		$this->jns_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->jns_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->jns_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jns_id'] = &$this->jns_id;

		// tgl
		$this->tgl = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_tgl', 'tgl', '`tgl`', ew_CastDateFieldForLike('`tgl`', 0, "DB"), 133, -1, FALSE, '`tgl`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl->Sortable = TRUE; // Allow sort
		$this->tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl'] = &$this->tgl;

		// tgl2
		$this->tgl2 = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_tgl2', 'tgl2', '`tgl2`', ew_CastDateFieldForLike('`tgl2`', 0, "DB"), 133, -1, FALSE, '`tgl2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl2->Sortable = TRUE; // Allow sort
		$this->tgl2->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl2'] = &$this->tgl2;

		// jam_masuk
		$this->jam_masuk = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_jam_masuk', 'jam_masuk', '`jam_masuk`', ew_CastDateFieldForLike('`jam_masuk`', 9, "DB"), 135, 9, FALSE, '`jam_masuk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jam_masuk->Sortable = TRUE; // Allow sort
		$this->jam_masuk->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateYMD"));
		$this->fields['jam_masuk'] = &$this->jam_masuk;

		// jam_keluar
		$this->jam_keluar = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_jam_keluar', 'jam_keluar', '`jam_keluar`', ew_CastDateFieldForLike('`jam_keluar`', 9, "DB"), 135, 9, FALSE, '`jam_keluar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jam_keluar->Sortable = TRUE; // Allow sort
		$this->jam_keluar->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateYMD"));
		$this->fields['jam_keluar'] = &$this->jam_keluar;

		// pegawai_id2
		$this->pegawai_id2 = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_pegawai_id2', 'pegawai_id2', '`pegawai_id2`', '`pegawai_id2`', 3, -1, FALSE, '`EV__pegawai_id2`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->pegawai_id2->Sortable = TRUE; // Allow sort
		$this->pegawai_id2->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->pegawai_id2->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->pegawai_id2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id2'] = &$this->pegawai_id2;

		// pegawai_id3
		$this->pegawai_id3 = new cField('t_pengecualian_peg', 't_pengecualian_peg', 'x_pegawai_id3', 'pegawai_id3', '`pegawai_id3`', '`pegawai_id3`', 3, -1, FALSE, '`EV__pegawai_id3`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->pegawai_id3->Sortable = TRUE; // Allow sort
		$this->pegawai_id3->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->pegawai_id3->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->pegawai_id3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id3'] = &$this->pegawai_id3;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "pegawai") {
			if ($this->pegawai_id->getSessionValue() <> "")
				$sMasterFilter .= "`pegawai_id`=" . ew_QuotedValue($this->pegawai_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "pegawai") {
			if ($this->pegawai_id->getSessionValue() <> "")
				$sDetailFilter .= "`pegawai_id`=" . ew_QuotedValue($this->pegawai_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_pegawai() {
		return "`pegawai_id`=@pegawai_id@";
	}

	// Detail filter
	function SqlDetailFilter_pegawai() {
		return "`pegawai_id`=@pegawai_id@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_pengecualian_peg`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `pegawai_nama` FROM `pegawai` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`pegawai_id` = `t_pengecualian_peg`.`pegawai_id` LIMIT 1) AS `EV__pegawai_id`, (SELECT `kode` FROM `t_jns_pengecualian` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`jns_id` = `t_pengecualian_peg`.`jns_id` LIMIT 1) AS `EV__jns_id`, (SELECT `pegawai_nama` FROM `pegawai` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`pegawai_id` = `t_pengecualian_peg`.`pegawai_id2` LIMIT 1) AS `EV__pegawai_id2`, (SELECT `pegawai_nama` FROM `pegawai` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`pegawai_id` = `t_pengecualian_peg`.`pegawai_id3` LIMIT 1) AS `EV__pegawai_id3` FROM `t_pengecualian_peg`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->pegawai_id->AdvancedSearch->SearchValue <> "" ||
			$this->pegawai_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->pegawai_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->pegawai_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->jns_id->AdvancedSearch->SearchValue <> "" ||
			$this->jns_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->jns_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->jns_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->pegawai_id2->AdvancedSearch->SearchValue <> "" ||
			$this->pegawai_id2->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->pegawai_id2->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->pegawai_id2->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->pegawai_id3->AdvancedSearch->SearchValue <> "" ||
			$this->pegawai_id3->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->pegawai_id3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->pegawai_id3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
			$this->pengecualian_id->setDbValue($conn->Insert_ID());
			$rs['pengecualian_id'] = $this->pengecualian_id->DbValue;
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
			$fldname = 'pengecualian_id';
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
			if (array_key_exists('pengecualian_id', $rs))
				ew_AddFilter($where, ew_QuotedName('pengecualian_id', $this->DBID) . '=' . ew_QuotedValue($rs['pengecualian_id'], $this->pengecualian_id->FldDataType, $this->DBID));
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
		return "`pengecualian_id` = @pengecualian_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->pengecualian_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pengecualian_id@", ew_AdjustSql($this->pengecualian_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "t_pengecualian_peglist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_pengecualian_peglist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_pengecualian_pegview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_pengecualian_pegview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_pengecualian_pegadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_pengecualian_pegadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("t_pengecualian_pegedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("t_pengecualian_pegadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_pengecualian_pegdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "pegawai" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_pegawai_id=" . urlencode($this->pegawai_id->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "pengecualian_id:" . ew_VarToJson($this->pengecualian_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->pengecualian_id->CurrentValue)) {
			$sUrl .= "pengecualian_id=" . urlencode($this->pengecualian_id->CurrentValue);
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
			if ($isPost && isset($_POST["pengecualian_id"]))
				$arKeys[] = ew_StripSlashes($_POST["pengecualian_id"]);
			elseif (isset($_GET["pengecualian_id"]))
				$arKeys[] = ew_StripSlashes($_GET["pengecualian_id"]);
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
			$this->pengecualian_id->CurrentValue = $key;
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
		$this->pengecualian_id->setDbValue($rs->fields('pengecualian_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->jns_id->setDbValue($rs->fields('jns_id'));
		$this->tgl->setDbValue($rs->fields('tgl'));
		$this->tgl2->setDbValue($rs->fields('tgl2'));
		$this->jam_masuk->setDbValue($rs->fields('jam_masuk'));
		$this->jam_keluar->setDbValue($rs->fields('jam_keluar'));
		$this->pegawai_id2->setDbValue($rs->fields('pegawai_id2'));
		$this->pegawai_id3->setDbValue($rs->fields('pegawai_id3'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// pengecualian_id
		// pegawai_id
		// jns_id
		// tgl
		// tgl2
		// jam_masuk
		// jam_keluar
		// pegawai_id2
		// pegawai_id3
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

		// pengecualian_id
		$this->pengecualian_id->LinkCustomAttributes = "";
		$this->pengecualian_id->HrefValue = "";
		$this->pengecualian_id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// pengecualian_id
		$this->pengecualian_id->EditAttrs["class"] = "form-control";
		$this->pengecualian_id->EditCustomAttributes = "";
		$this->pengecualian_id->EditValue = $this->pengecualian_id->CurrentValue;
		$this->pengecualian_id->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->EditAttrs["class"] = "form-control";
		$this->pegawai_id->EditCustomAttributes = "";
		if ($this->pegawai_id->getSessionValue() <> "") {
			$this->pegawai_id->CurrentValue = $this->pegawai_id->getSessionValue();
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
		}

		// jns_id
		$this->jns_id->EditAttrs["class"] = "form-control";
		$this->jns_id->EditCustomAttributes = "";

		// tgl
		$this->tgl->EditAttrs["class"] = "form-control";
		$this->tgl->EditCustomAttributes = "";
		$this->tgl->EditValue = $this->tgl->CurrentValue;
		$this->tgl->PlaceHolder = ew_RemoveHtml($this->tgl->FldCaption());

		// tgl2
		$this->tgl2->EditAttrs["class"] = "form-control";
		$this->tgl2->EditCustomAttributes = "";
		$this->tgl2->EditValue = $this->tgl2->CurrentValue;
		$this->tgl2->PlaceHolder = ew_RemoveHtml($this->tgl2->FldCaption());

		// jam_masuk
		$this->jam_masuk->EditAttrs["class"] = "form-control";
		$this->jam_masuk->EditCustomAttributes = "";
		$this->jam_masuk->EditValue = ew_FormatDateTime($this->jam_masuk->CurrentValue, 9);
		$this->jam_masuk->PlaceHolder = ew_RemoveHtml($this->jam_masuk->FldCaption());

		// jam_keluar
		$this->jam_keluar->EditAttrs["class"] = "form-control";
		$this->jam_keluar->EditCustomAttributes = "";
		$this->jam_keluar->EditValue = ew_FormatDateTime($this->jam_keluar->CurrentValue, 9);
		$this->jam_keluar->PlaceHolder = ew_RemoveHtml($this->jam_keluar->FldCaption());

		// pegawai_id2
		$this->pegawai_id2->EditAttrs["class"] = "form-control";
		$this->pegawai_id2->EditCustomAttributes = "";

		// pegawai_id3
		$this->pegawai_id3->EditAttrs["class"] = "form-control";
		$this->pegawai_id3->EditCustomAttributes = "";

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
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->jns_id->Exportable) $Doc->ExportCaption($this->jns_id);
					if ($this->tgl->Exportable) $Doc->ExportCaption($this->tgl);
					if ($this->tgl2->Exportable) $Doc->ExportCaption($this->tgl2);
					if ($this->jam_masuk->Exportable) $Doc->ExportCaption($this->jam_masuk);
					if ($this->jam_keluar->Exportable) $Doc->ExportCaption($this->jam_keluar);
					if ($this->pegawai_id2->Exportable) $Doc->ExportCaption($this->pegawai_id2);
					if ($this->pegawai_id3->Exportable) $Doc->ExportCaption($this->pegawai_id3);
				} else {
					if ($this->pengecualian_id->Exportable) $Doc->ExportCaption($this->pengecualian_id);
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->jns_id->Exportable) $Doc->ExportCaption($this->jns_id);
					if ($this->tgl->Exportable) $Doc->ExportCaption($this->tgl);
					if ($this->tgl2->Exportable) $Doc->ExportCaption($this->tgl2);
					if ($this->jam_masuk->Exportable) $Doc->ExportCaption($this->jam_masuk);
					if ($this->jam_keluar->Exportable) $Doc->ExportCaption($this->jam_keluar);
					if ($this->pegawai_id2->Exportable) $Doc->ExportCaption($this->pegawai_id2);
					if ($this->pegawai_id3->Exportable) $Doc->ExportCaption($this->pegawai_id3);
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
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->jns_id->Exportable) $Doc->ExportField($this->jns_id);
						if ($this->tgl->Exportable) $Doc->ExportField($this->tgl);
						if ($this->tgl2->Exportable) $Doc->ExportField($this->tgl2);
						if ($this->jam_masuk->Exportable) $Doc->ExportField($this->jam_masuk);
						if ($this->jam_keluar->Exportable) $Doc->ExportField($this->jam_keluar);
						if ($this->pegawai_id2->Exportable) $Doc->ExportField($this->pegawai_id2);
						if ($this->pegawai_id3->Exportable) $Doc->ExportField($this->pegawai_id3);
					} else {
						if ($this->pengecualian_id->Exportable) $Doc->ExportField($this->pengecualian_id);
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->jns_id->Exportable) $Doc->ExportField($this->jns_id);
						if ($this->tgl->Exportable) $Doc->ExportField($this->tgl);
						if ($this->tgl2->Exportable) $Doc->ExportField($this->tgl2);
						if ($this->jam_masuk->Exportable) $Doc->ExportField($this->jam_masuk);
						if ($this->jam_keluar->Exportable) $Doc->ExportField($this->jam_keluar);
						if ($this->pegawai_id2->Exportable) $Doc->ExportField($this->pegawai_id2);
						if ($this->pegawai_id3->Exportable) $Doc->ExportField($this->pegawai_id3);
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
		$table = 't_pengecualian_peg';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 't_pengecualian_peg';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['pengecualian_id'];

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
		$table = 't_pengecualian_peg';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['pengecualian_id'];

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
		$table = 't_pengecualian_peg';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['pengecualian_id'];

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
		//$tot_det = ew_ExecuteScalar("SELECT SUM(Value) FROM category_detail WHERE Cat_ID = ".$rsnew["Cat_ID"]."");
		//ew_Execute("UPDATE categories SET Cat_Total = ".$tot_det." WHERE Cat_ID = ".$rsnew["Cat_ID"]."");
		// cari jenis pengecualian

		$query = "select kode from t_jns_pengecualian where jns_id = ".$rsnew["jns_id"]."";
		$kode = ew_ExecuteScalar($query);

		// jika kode TS dan field pegawai_id3 (digantikan oleh == null)
		if ($kode == "TS" and $rsnew["pegawai_id3"] == null) {
			$query = "insert into t_pengecualian_peg values (
				null
				, ".$rsnew["pegawai_id2"]."
				, '".$rsnew["tgl"]."'
				, ".$rsnew["jns_id"]."
				, null
				, null
				, null
				, ".$rsnew["pegawai_id"]."
				)";
			ew_Execute($query);
		}
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
		//$tot_det = ew_ExecuteScalar("SELECT SUM(Value) FROM category_detail WHERE Cat_ID = ".$rsold["Cat_ID"]."");
		//ew_Execute("UPDATE categories SET Cat_Total = ".$tot_det." WHERE Cat_ID = ".$rsold["Cat_ID"]."");
		// cari jenis pengecualian

		$query = "select kode from t_jns_pengecualian where jns_id = ".$rsold["jns_id"]."";
		$kode = ew_ExecuteScalar($query);

		// jika kode TS dan field pegawai_id3 (digantikan oleh == null)
		if ($kode == "TS" and $rsold["pegawai_id3"] == null) {

			// hapus data lama
			$query = "
				delete from t_pengecualian_peg where
				tgl = '".$rsold["tgl"]."'
				and pegawai_id = ".$rsold["pegawai_id2"]."
				and jns_id = ".$rsold["jns_id"]."
				";
			ew_Execute($query);

			// insert data baru
			$query = "insert into t_pengecualian_peg values (
				null
				, ".$rsnew["pegawai_id2"]."
				, '".$rsnew["tgl"]."'
				, ".$rsnew["jns_id"]."
				, null
				, null
				, null
				, ".$rsnew["pegawai_id"]."
				)";
			ew_Execute($query);
		}
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
		// cari jenis pengecualian

		$query = "select kode from t_jns_pengecualian where jns_id = ".$rs["jns_id"]."";
		$kode = ew_ExecuteScalar($query);

		// jika kode TS dan field pegawai_id3 (digantikan oleh == null)
		if ($kode == "TS" and $rs["pegawai_id3"] == null) {

			// hapus data lama
			$query = "
				delete from t_pengecualian_peg where
				tgl = '".$rs["tgl"]."'
				and pegawai_id = ".$rs["pegawai_id2"]."
				and jns_id = ".$rs["jns_id"]."
				";
			ew_Execute($query);
		}
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
