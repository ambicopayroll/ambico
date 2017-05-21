<?php

// Global variable for table object
$r_laplemburh = NULL;

//
// Table class for r_laplemburh
//
class crr_laplemburh extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $laplembur_id;
	var $no;
	var $bagian;
	var $divisi;
	var $nama;
	var $nip;
	var $jml_jam;
	var $tarif;
	var $total_lembur;
	var $start;
	var $end;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_laplemburh';
		$this->TableName = 'r_laplemburh';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// laplembur_id
		$this->laplembur_id = new crField('r_laplemburh', 'r_laplemburh', 'x_laplembur_id', 'laplembur_id', '`laplembur_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->laplembur_id->Sortable = TRUE; // Allow sort
		$this->laplembur_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['laplembur_id'] = &$this->laplembur_id;
		$this->laplembur_id->DateFilter = "";
		$this->laplembur_id->SqlSelect = "";
		$this->laplembur_id->SqlOrderBy = "";

		// no
		$this->no = new crField('r_laplemburh', 'r_laplemburh', 'x_no', 'no', '`no`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->no->Sortable = TRUE; // Allow sort
		$this->no->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['no'] = &$this->no;
		$this->no->DateFilter = "";
		$this->no->SqlSelect = "";
		$this->no->SqlOrderBy = "";

		// bagian
		$this->bagian = new crField('r_laplemburh', 'r_laplemburh', 'x_bagian', 'bagian', '`bagian`', 200, EWR_DATATYPE_STRING, -1);
		$this->bagian->Sortable = TRUE; // Allow sort
		$this->bagian->GroupingFieldId = 1;
		$this->bagian->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->bagian->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['bagian'] = &$this->bagian;
		$this->bagian->DateFilter = "";
		$this->bagian->SqlSelect = "";
		$this->bagian->SqlOrderBy = "";
		$this->bagian->FldGroupByType = "";
		$this->bagian->FldGroupInt = "0";
		$this->bagian->FldGroupSql = "";

		// divisi
		$this->divisi = new crField('r_laplemburh', 'r_laplemburh', 'x_divisi', 'divisi', '`divisi`', 200, EWR_DATATYPE_STRING, -1);
		$this->divisi->Sortable = TRUE; // Allow sort
		$this->divisi->GroupingFieldId = 2;
		$this->divisi->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->divisi->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['divisi'] = &$this->divisi;
		$this->divisi->DateFilter = "";
		$this->divisi->SqlSelect = "";
		$this->divisi->SqlOrderBy = "";
		$this->divisi->FldGroupByType = "";
		$this->divisi->FldGroupInt = "0";
		$this->divisi->FldGroupSql = "";

		// nama
		$this->nama = new crField('r_laplemburh', 'r_laplemburh', 'x_nama', 'nama', '`nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;
		$this->nama->DateFilter = "";
		$this->nama->SqlSelect = "";
		$this->nama->SqlOrderBy = "";

		// nip
		$this->nip = new crField('r_laplemburh', 'r_laplemburh', 'x_nip', 'nip', '`nip`', 200, EWR_DATATYPE_STRING, -1);
		$this->nip->Sortable = TRUE; // Allow sort
		$this->fields['nip'] = &$this->nip;
		$this->nip->DateFilter = "";
		$this->nip->SqlSelect = "";
		$this->nip->SqlOrderBy = "";

		// jml_jam
		$this->jml_jam = new crField('r_laplemburh', 'r_laplemburh', 'x_jml_jam', 'jml_jam', '`jml_jam`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->jml_jam->Sortable = TRUE; // Allow sort
		$this->jml_jam->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['jml_jam'] = &$this->jml_jam;
		$this->jml_jam->DateFilter = "";
		$this->jml_jam->SqlSelect = "";
		$this->jml_jam->SqlOrderBy = "";

		// tarif
		$this->tarif = new crField('r_laplemburh', 'r_laplemburh', 'x_tarif', 'tarif', '`tarif`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->tarif->Sortable = TRUE; // Allow sort
		$this->tarif->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['tarif'] = &$this->tarif;
		$this->tarif->DateFilter = "";
		$this->tarif->SqlSelect = "";
		$this->tarif->SqlOrderBy = "";

		// total_lembur
		$this->total_lembur = new crField('r_laplemburh', 'r_laplemburh', 'x_total_lembur', 'total_lembur', '`total_lembur`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->total_lembur->Sortable = TRUE; // Allow sort
		$this->total_lembur->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['total_lembur'] = &$this->total_lembur;
		$this->total_lembur->DateFilter = "";
		$this->total_lembur->SqlSelect = "";
		$this->total_lembur->SqlOrderBy = "";

		// start
		$this->start = new crField('r_laplemburh', 'r_laplemburh', 'x_start', 'start', '`start`', 133, EWR_DATATYPE_DATE, 0);
		$this->start->Sortable = TRUE; // Allow sort
		$this->start->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['start'] = &$this->start;
		$this->start->DateFilter = "";
		$this->start->SqlSelect = "";
		$this->start->SqlOrderBy = "";

		// end
		$this->end = new crField('r_laplemburh', 'r_laplemburh', 'x_end', 'end', '`end`', 133, EWR_DATATYPE_DATE, 0);
		$this->end->Sortable = TRUE; // Allow sort
		$this->end->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['end'] = &$this->end;
		$this->end->DateFilter = "";
		$this->end->SqlSelect = "";
		$this->end->SqlOrderBy = "";
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_laplemburh`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`bagian` ASC, `divisi` ASC";
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
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "`bagian`";
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
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "`bagian` ASC";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT SUM(`total_lembur`) AS `sum_total_lembur` FROM " . $this->getSqlFrom();
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
