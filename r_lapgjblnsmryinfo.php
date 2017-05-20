<?php

// Global variable for table object
$r_lapgjbln = NULL;

//
// Table class for r_lapgjbln
//
class crr_lapgjbln extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $gjbln_id;
	var $bagian;
	var $divisi;
	var $nama;
	var $nip;
	var $gp;
	var $t_jbtn;
	var $p_absen;
	var $t_malam;
	var $t_lembur;
	var $t_hadir;
	var $t_um;
	var $j_bruto;
	var $p_aspen;
	var $p_bpjs;
	var $j_netto;
	var $start;
	var $end;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_lapgjbln';
		$this->TableName = 'r_lapgjbln';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// gjbln_id
		$this->gjbln_id = new crField('r_lapgjbln', 'r_lapgjbln', 'x_gjbln_id', 'gjbln_id', '`gjbln_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->gjbln_id->Sortable = TRUE; // Allow sort
		$this->gjbln_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['gjbln_id'] = &$this->gjbln_id;
		$this->gjbln_id->DateFilter = "";
		$this->gjbln_id->SqlSelect = "";
		$this->gjbln_id->SqlOrderBy = "";

		// bagian
		$this->bagian = new crField('r_lapgjbln', 'r_lapgjbln', 'x_bagian', 'bagian', '`bagian`', 200, EWR_DATATYPE_STRING, -1);
		$this->bagian->Sortable = TRUE; // Allow sort
		$this->bagian->GroupingFieldId = 1;
		$this->bagian->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->bagian->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->bagian->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['bagian'] = &$this->bagian;
		$this->bagian->DateFilter = "";
		$this->bagian->SqlSelect = "";
		$this->bagian->SqlOrderBy = "";
		$this->bagian->FldGroupByType = "";
		$this->bagian->FldGroupInt = "0";
		$this->bagian->FldGroupSql = "";

		// divisi
		$this->divisi = new crField('r_lapgjbln', 'r_lapgjbln', 'x_divisi', 'divisi', '`divisi`', 200, EWR_DATATYPE_STRING, -1);
		$this->divisi->Sortable = TRUE; // Allow sort
		$this->divisi->GroupingFieldId = 2;
		$this->divisi->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->divisi->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->divisi->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['divisi'] = &$this->divisi;
		$this->divisi->DateFilter = "";
		$this->divisi->SqlSelect = "";
		$this->divisi->SqlOrderBy = "";
		$this->divisi->FldGroupByType = "";
		$this->divisi->FldGroupInt = "0";
		$this->divisi->FldGroupSql = "";

		// nama
		$this->nama = new crField('r_lapgjbln', 'r_lapgjbln', 'x_nama', 'nama', '`nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->nama->Sortable = TRUE; // Allow sort
		$this->nama->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['nama'] = &$this->nama;
		$this->nama->DateFilter = "";
		$this->nama->SqlSelect = "";
		$this->nama->SqlOrderBy = "";

		// nip
		$this->nip = new crField('r_lapgjbln', 'r_lapgjbln', 'x_nip', 'nip', '`nip`', 200, EWR_DATATYPE_STRING, -1);
		$this->nip->Sortable = TRUE; // Allow sort
		$this->nip->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['nip'] = &$this->nip;
		$this->nip->DateFilter = "";
		$this->nip->SqlSelect = "";
		$this->nip->SqlOrderBy = "";

		// gp
		$this->gp = new crField('r_lapgjbln', 'r_lapgjbln', 'x_gp', 'gp', '`gp`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->gp->Sortable = TRUE; // Allow sort
		$this->gp->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['gp'] = &$this->gp;
		$this->gp->DateFilter = "";
		$this->gp->SqlSelect = "";
		$this->gp->SqlOrderBy = "";

		// t_jbtn
		$this->t_jbtn = new crField('r_lapgjbln', 'r_lapgjbln', 'x_t_jbtn', 't_jbtn', '`t_jbtn`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->t_jbtn->Sortable = TRUE; // Allow sort
		$this->t_jbtn->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['t_jbtn'] = &$this->t_jbtn;
		$this->t_jbtn->DateFilter = "";
		$this->t_jbtn->SqlSelect = "";
		$this->t_jbtn->SqlOrderBy = "";

		// p_absen
		$this->p_absen = new crField('r_lapgjbln', 'r_lapgjbln', 'x_p_absen', 'p_absen', '`p_absen`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->p_absen->Sortable = TRUE; // Allow sort
		$this->p_absen->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['p_absen'] = &$this->p_absen;
		$this->p_absen->DateFilter = "";
		$this->p_absen->SqlSelect = "";
		$this->p_absen->SqlOrderBy = "";

		// t_malam
		$this->t_malam = new crField('r_lapgjbln', 'r_lapgjbln', 'x_t_malam', 't_malam', '`t_malam`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->t_malam->Sortable = TRUE; // Allow sort
		$this->t_malam->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['t_malam'] = &$this->t_malam;
		$this->t_malam->DateFilter = "";
		$this->t_malam->SqlSelect = "";
		$this->t_malam->SqlOrderBy = "";

		// t_lembur
		$this->t_lembur = new crField('r_lapgjbln', 'r_lapgjbln', 'x_t_lembur', 't_lembur', '`t_lembur`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->t_lembur->Sortable = TRUE; // Allow sort
		$this->t_lembur->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['t_lembur'] = &$this->t_lembur;
		$this->t_lembur->DateFilter = "";
		$this->t_lembur->SqlSelect = "";
		$this->t_lembur->SqlOrderBy = "";

		// t_hadir
		$this->t_hadir = new crField('r_lapgjbln', 'r_lapgjbln', 'x_t_hadir', 't_hadir', '`t_hadir`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->t_hadir->Sortable = TRUE; // Allow sort
		$this->t_hadir->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['t_hadir'] = &$this->t_hadir;
		$this->t_hadir->DateFilter = "";
		$this->t_hadir->SqlSelect = "";
		$this->t_hadir->SqlOrderBy = "";

		// t_um
		$this->t_um = new crField('r_lapgjbln', 'r_lapgjbln', 'x_t_um', 't_um', '`t_um`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->t_um->Sortable = TRUE; // Allow sort
		$this->t_um->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['t_um'] = &$this->t_um;
		$this->t_um->DateFilter = "";
		$this->t_um->SqlSelect = "";
		$this->t_um->SqlOrderBy = "";

		// j_bruto
		$this->j_bruto = new crField('r_lapgjbln', 'r_lapgjbln', 'x_j_bruto', 'j_bruto', '`j_bruto`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->j_bruto->Sortable = TRUE; // Allow sort
		$this->j_bruto->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['j_bruto'] = &$this->j_bruto;
		$this->j_bruto->DateFilter = "";
		$this->j_bruto->SqlSelect = "";
		$this->j_bruto->SqlOrderBy = "";

		// p_aspen
		$this->p_aspen = new crField('r_lapgjbln', 'r_lapgjbln', 'x_p_aspen', 'p_aspen', '`p_aspen`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->p_aspen->Sortable = TRUE; // Allow sort
		$this->p_aspen->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['p_aspen'] = &$this->p_aspen;
		$this->p_aspen->DateFilter = "";
		$this->p_aspen->SqlSelect = "";
		$this->p_aspen->SqlOrderBy = "";

		// p_bpjs
		$this->p_bpjs = new crField('r_lapgjbln', 'r_lapgjbln', 'x_p_bpjs', 'p_bpjs', '`p_bpjs`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->p_bpjs->Sortable = TRUE; // Allow sort
		$this->p_bpjs->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['p_bpjs'] = &$this->p_bpjs;
		$this->p_bpjs->DateFilter = "";
		$this->p_bpjs->SqlSelect = "";
		$this->p_bpjs->SqlOrderBy = "";

		// j_netto
		$this->j_netto = new crField('r_lapgjbln', 'r_lapgjbln', 'x_j_netto', 'j_netto', '`j_netto`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->j_netto->Sortable = TRUE; // Allow sort
		$this->j_netto->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['j_netto'] = &$this->j_netto;
		$this->j_netto->DateFilter = "";
		$this->j_netto->SqlSelect = "";
		$this->j_netto->SqlOrderBy = "";

		// start
		$this->start = new crField('r_lapgjbln', 'r_lapgjbln', 'x_start', 'start', '`start`', 133, EWR_DATATYPE_DATE, 0);
		$this->start->Sortable = TRUE; // Allow sort
		$this->start->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['start'] = &$this->start;
		$this->start->DateFilter = "";
		$this->start->SqlSelect = "";
		$this->start->SqlOrderBy = "";

		// end
		$this->end = new crField('r_lapgjbln', 'r_lapgjbln', 'x_end', 'end', '`end`', 133, EWR_DATATYPE_DATE, 0);
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_gjbln`";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT SUM(`gp`) AS `sum_gp`, SUM(`t_jbtn`) AS `sum_t_jbtn`, SUM(`p_absen`) AS `sum_p_absen`, SUM(`t_malam`) AS `sum_t_malam`, SUM(`t_hadir`) AS `sum_t_hadir`, SUM(`t_um`) AS `sum_t_um`, SUM(`j_bruto`) AS `sum_j_bruto`, SUM(`p_aspen`) AS `sum_p_aspen`, SUM(`p_bpjs`) AS `sum_p_bpjs`, SUM(`j_netto`) AS `sum_j_netto` FROM " . $this->getSqlFrom();
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
