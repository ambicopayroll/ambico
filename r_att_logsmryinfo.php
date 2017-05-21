<?php

// Global variable for table object
$r_att_log = NULL;

//
// Table class for r_att_log
//
class crr_att_log extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $sn;
	var $att_id;
	var $pin;
	var $pegawai_nip;
	var $pegawai_nama;
	var $scan_date_tgl;
	var $scan_date_tgl_jam;
	var $scan_date;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_att_log';
		$this->TableName = 'r_att_log';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// sn
		$this->sn = new crField('r_att_log', 'r_att_log', 'x_sn', 'sn', '`sn`', 200, EWR_DATATYPE_STRING, -1);
		$this->sn->Sortable = TRUE; // Allow sort
		$this->fields['sn'] = &$this->sn;
		$this->sn->DateFilter = "";
		$this->sn->SqlSelect = "";
		$this->sn->SqlOrderBy = "";

		// att_id
		$this->att_id = new crField('r_att_log', 'r_att_log', 'x_att_id', 'att_id', '`att_id`', 200, EWR_DATATYPE_STRING, -1);
		$this->att_id->Sortable = TRUE; // Allow sort
		$this->fields['att_id'] = &$this->att_id;
		$this->att_id->DateFilter = "";
		$this->att_id->SqlSelect = "";
		$this->att_id->SqlOrderBy = "";

		// pin
		$this->pin = new crField('r_att_log', 'r_att_log', 'x_pin', 'pin', '`pin`', 200, EWR_DATATYPE_STRING, -1);
		$this->pin->Sortable = TRUE; // Allow sort
		$this->fields['pin'] = &$this->pin;
		$this->pin->DateFilter = "";
		$this->pin->SqlSelect = "";
		$this->pin->SqlOrderBy = "";

		// pegawai_nip
		$this->pegawai_nip = new crField('r_att_log', 'r_att_log', 'x_pegawai_nip', 'pegawai_nip', '`pegawai_nip`', 200, EWR_DATATYPE_STRING, -1);
		$this->pegawai_nip->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_nip'] = &$this->pegawai_nip;
		$this->pegawai_nip->DateFilter = "";
		$this->pegawai_nip->SqlSelect = "";
		$this->pegawai_nip->SqlOrderBy = "";

		// pegawai_nama
		$this->pegawai_nama = new crField('r_att_log', 'r_att_log', 'x_pegawai_nama', 'pegawai_nama', '`pegawai_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->pegawai_nama->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_nama'] = &$this->pegawai_nama;
		$this->pegawai_nama->DateFilter = "";
		$this->pegawai_nama->SqlSelect = "";
		$this->pegawai_nama->SqlOrderBy = "";

		// scan_date_tgl
		$this->scan_date_tgl = new crField('r_att_log', 'r_att_log', 'x_scan_date_tgl', 'scan_date_tgl', '`scan_date_tgl`', 133, EWR_DATATYPE_DATE, -1);
		$this->scan_date_tgl->Sortable = TRUE; // Allow sort
		$this->scan_date_tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['scan_date_tgl'] = &$this->scan_date_tgl;
		$this->scan_date_tgl->DateFilter = "";
		$this->scan_date_tgl->SqlSelect = "SELECT DISTINCT `scan_date_tgl`, `scan_date_tgl` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->scan_date_tgl->SqlOrderBy = "`scan_date_tgl`";
		ewr_RegisterFilter($this->scan_date_tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->scan_date_tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->scan_date_tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->scan_date_tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->scan_date_tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->scan_date_tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->scan_date_tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->scan_date_tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->scan_date_tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->scan_date_tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");

		// scan_date_tgl_jam
		$this->scan_date_tgl_jam = new crField('r_att_log', 'r_att_log', 'x_scan_date_tgl_jam', 'scan_date_tgl_jam', '`scan_date_tgl_jam`', 200, EWR_DATATYPE_STRING, -1);
		$this->scan_date_tgl_jam->Sortable = TRUE; // Allow sort
		$this->fields['scan_date_tgl_jam'] = &$this->scan_date_tgl_jam;
		$this->scan_date_tgl_jam->DateFilter = "";
		$this->scan_date_tgl_jam->SqlSelect = "";
		$this->scan_date_tgl_jam->SqlOrderBy = "";

		// scan_date
		$this->scan_date = new crField('r_att_log', 'r_att_log', 'x_scan_date', 'scan_date', '`scan_date`', 135, EWR_DATATYPE_DATE, 9);
		$this->scan_date->Sortable = TRUE; // Allow sort
		$this->scan_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_SEPARATOR"], $ReportLanguage->Phrase("IncorrectDateYMD"));
		$this->fields['scan_date'] = &$this->scan_date;
		$this->scan_date->DateFilter = "";
		$this->scan_date->SqlSelect = "";
		$this->scan_date->SqlOrderBy = "";
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
			if ($ofld->GroupingFieldId == 0) {
				if ($ctrl) {
					$sOrderBy = $this->getDetailOrderBy();
					if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
						$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
					} else {
						if ($sOrderBy <> "") $sOrderBy .= ", ";
						$sOrderBy .= $sSortField . " " . $sThisSort;
					}
					$this->setDetailOrderBy($sOrderBy); // Save to Session
				} else {
					$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
				}
			}
		} else {
			if ($ofld->GroupingFieldId == 0 && !$ctrl) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_att_log`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Table Level Group SQL
	// First Group Field

	var $_SqlFirstGroupField = "";

	function getSqlFirstGroupField() {
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "";
	}

	function SqlFirstGroupField() { // For backward compatibility
		return $this->getSqlFirstGroupField();
	}

	function setSqlFirstGroupField($v) {
		$this->_SqlFirstGroupField = $v;
	}

	// Select Group
	var $_SqlSelectGroup = "";

	function getSqlSelectGroup() {
		return ($this->_SqlSelectGroup <> "") ? $this->_SqlSelectGroup : "SELECT DISTINCT " . $this->getSqlFirstGroupField() . " FROM " . $this->getSqlFrom();
	}

	function SqlSelectGroup() { // For backward compatibility
		return $this->getSqlSelectGroup();
	}

	function setSqlSelectGroup($v) {
		$this->_SqlSelectGroup = $v;
	}

	// Order By Group
	var $_SqlOrderByGroup = "";

	function getSqlOrderByGroup() {
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "";
	}

	function SqlOrderByGroup() { // For backward compatibility
		return $this->getSqlOrderByGroup();
	}

	function setSqlOrderByGroup($v) {
		$this->_SqlOrderByGroup = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {

			//$sUrlParm = "order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort();
			$sUrlParm = "order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort();
			return ewr_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		case "x_pegawai_nip":
			$sSqlWrk = "";
		$sSqlWrk = "SELECT DISTINCT `pegawai_nip`, `pegawai_nip` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `v_att_log`";
		$sWhereWrk = "{filter}";
		$this->pegawai_nip->LookupFilters = array("dx1" => '`pegawai_nip`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "DB", "f0" => '`pegawai_nip` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter));
			$sSqlWrk = "";
		$this->Lookup_Selecting($this->pegawai_nip, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `pegawai_nip` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_pegawai_nama":
			$sSqlWrk = "";
		$sSqlWrk = "SELECT DISTINCT `pegawai_nama`, `pegawai_nama` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `v_att_log`";
		$sWhereWrk = "{filter}";
		$this->pegawai_nama->LookupFilters = array("dx1" => '`pegawai_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "DB", "f0" => '`pegawai_nama` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter));
			$sSqlWrk = "";
		$this->Lookup_Selecting($this->pegawai_nama, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `pegawai_nama` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

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

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
