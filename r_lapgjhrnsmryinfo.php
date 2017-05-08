<?php

// Global variable for table object
$r_lapgjhrn = NULL;

//
// Table class for r_lapgjhrn
//
class crr_lapgjhrn extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $lapgroup_id;
	var $lapgroup_nama;
	var $lapgroup_index;
	var $lapsubgroup_index;
	var $pegawai_id;
	var $tgl;
	var $jk_id;
	var $scan_masuk;
	var $scan_keluar;
	var $hk_def;
	var $pegawai_nama;
	var $pegawai_nip;
	var $jk_kd;
	var $pembagian2_nama;
	var $pembagian2_id;
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
	var $upah2;
	var $premi_malam2;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_lapgjhrn';
		$this->TableName = 'r_lapgjhrn';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// lapgroup_id
		$this->lapgroup_id = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_lapgroup_id', 'lapgroup_id', '`lapgroup_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->lapgroup_id->Sortable = TRUE; // Allow sort
		$this->lapgroup_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['lapgroup_id'] = &$this->lapgroup_id;
		$this->lapgroup_id->DateFilter = "";
		$this->lapgroup_id->SqlSelect = "";
		$this->lapgroup_id->SqlOrderBy = "";

		// lapgroup_nama
		$this->lapgroup_nama = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_lapgroup_nama', 'lapgroup_nama', '`lapgroup_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->lapgroup_nama->Sortable = TRUE; // Allow sort
		$this->lapgroup_nama->GroupingFieldId = 1;
		$this->lapgroup_nama->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->lapgroup_nama->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['lapgroup_nama'] = &$this->lapgroup_nama;
		$this->lapgroup_nama->DateFilter = "";
		$this->lapgroup_nama->SqlSelect = "";
		$this->lapgroup_nama->SqlOrderBy = "";
		$this->lapgroup_nama->FldGroupByType = "";
		$this->lapgroup_nama->FldGroupInt = "0";
		$this->lapgroup_nama->FldGroupSql = "";

		// lapgroup_index
		$this->lapgroup_index = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_lapgroup_index', 'lapgroup_index', '`lapgroup_index`', 16, EWR_DATATYPE_NUMBER, -1);
		$this->lapgroup_index->Sortable = TRUE; // Allow sort
		$this->lapgroup_index->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['lapgroup_index'] = &$this->lapgroup_index;
		$this->lapgroup_index->DateFilter = "";
		$this->lapgroup_index->SqlSelect = "";
		$this->lapgroup_index->SqlOrderBy = "";

		// lapsubgroup_index
		$this->lapsubgroup_index = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_lapsubgroup_index', 'lapsubgroup_index', '`lapsubgroup_index`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->lapsubgroup_index->Sortable = TRUE; // Allow sort
		$this->lapsubgroup_index->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['lapsubgroup_index'] = &$this->lapsubgroup_index;
		$this->lapsubgroup_index->DateFilter = "";
		$this->lapsubgroup_index->SqlSelect = "";
		$this->lapsubgroup_index->SqlOrderBy = "";

		// pegawai_id
		$this->pegawai_id = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;
		$this->pegawai_id->DateFilter = "";
		$this->pegawai_id->SqlSelect = "";
		$this->pegawai_id->SqlOrderBy = "";

		// tgl
		$this->tgl = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_tgl', 'tgl', '`tgl`', 133, EWR_DATATYPE_DATE, 0);
		$this->tgl->Sortable = TRUE; // Allow sort
		$this->tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['tgl'] = &$this->tgl;
		$this->tgl->DateFilter = "";
		$this->tgl->SqlSelect = "";
		$this->tgl->SqlOrderBy = "";

		// jk_id
		$this->jk_id = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_jk_id', 'jk_id', '`jk_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->jk_id->Sortable = TRUE; // Allow sort
		$this->jk_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['jk_id'] = &$this->jk_id;
		$this->jk_id->DateFilter = "";
		$this->jk_id->SqlSelect = "";
		$this->jk_id->SqlOrderBy = "";

		// scan_masuk
		$this->scan_masuk = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_scan_masuk', 'scan_masuk', '`scan_masuk`', 135, EWR_DATATYPE_DATE, 0);
		$this->scan_masuk->Sortable = TRUE; // Allow sort
		$this->scan_masuk->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['scan_masuk'] = &$this->scan_masuk;
		$this->scan_masuk->DateFilter = "";
		$this->scan_masuk->SqlSelect = "";
		$this->scan_masuk->SqlOrderBy = "";

		// scan_keluar
		$this->scan_keluar = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_scan_keluar', 'scan_keluar', '`scan_keluar`', 135, EWR_DATATYPE_DATE, 0);
		$this->scan_keluar->Sortable = TRUE; // Allow sort
		$this->scan_keluar->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['scan_keluar'] = &$this->scan_keluar;
		$this->scan_keluar->DateFilter = "";
		$this->scan_keluar->SqlSelect = "";
		$this->scan_keluar->SqlOrderBy = "";

		// hk_def
		$this->hk_def = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_hk_def', 'hk_def', '`hk_def`', 16, EWR_DATATYPE_NUMBER, -1);
		$this->hk_def->Sortable = TRUE; // Allow sort
		$this->hk_def->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['hk_def'] = &$this->hk_def;
		$this->hk_def->DateFilter = "";
		$this->hk_def->SqlSelect = "";
		$this->hk_def->SqlOrderBy = "";

		// pegawai_nama
		$this->pegawai_nama = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_pegawai_nama', 'pegawai_nama', '`pegawai_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->pegawai_nama->Sortable = TRUE; // Allow sort
		$this->pegawai_nama->GroupingFieldId = 3;
		$this->pegawai_nama->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->pegawai_nama->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['pegawai_nama'] = &$this->pegawai_nama;
		$this->pegawai_nama->DateFilter = "";
		$this->pegawai_nama->SqlSelect = "";
		$this->pegawai_nama->SqlOrderBy = "";
		$this->pegawai_nama->FldGroupByType = "";
		$this->pegawai_nama->FldGroupInt = "0";
		$this->pegawai_nama->FldGroupSql = "";

		// pegawai_nip
		$this->pegawai_nip = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_pegawai_nip', 'pegawai_nip', '`pegawai_nip`', 200, EWR_DATATYPE_STRING, -1);
		$this->pegawai_nip->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_nip'] = &$this->pegawai_nip;
		$this->pegawai_nip->DateFilter = "";
		$this->pegawai_nip->SqlSelect = "";
		$this->pegawai_nip->SqlOrderBy = "";

		// jk_kd
		$this->jk_kd = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_jk_kd', 'jk_kd', '`jk_kd`', 200, EWR_DATATYPE_STRING, -1);
		$this->jk_kd->Sortable = TRUE; // Allow sort
		$this->fields['jk_kd'] = &$this->jk_kd;
		$this->jk_kd->DateFilter = "";
		$this->jk_kd->SqlSelect = "";
		$this->jk_kd->SqlOrderBy = "";

		// pembagian2_nama
		$this->pembagian2_nama = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_pembagian2_nama', 'pembagian2_nama', '`pembagian2_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->pembagian2_nama->Sortable = TRUE; // Allow sort
		$this->pembagian2_nama->GroupingFieldId = 2;
		$this->pembagian2_nama->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->pembagian2_nama->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['pembagian2_nama'] = &$this->pembagian2_nama;
		$this->pembagian2_nama->DateFilter = "";
		$this->pembagian2_nama->SqlSelect = "";
		$this->pembagian2_nama->SqlOrderBy = "";
		$this->pembagian2_nama->FldGroupByType = "";
		$this->pembagian2_nama->FldGroupInt = "0";
		$this->pembagian2_nama->FldGroupSql = "";

		// pembagian2_id
		$this->pembagian2_id = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_pembagian2_id', 'pembagian2_id', '`pembagian2_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->pembagian2_id->Sortable = TRUE; // Allow sort
		$this->pembagian2_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['pembagian2_id'] = &$this->pembagian2_id;
		$this->pembagian2_id->DateFilter = "";
		$this->pembagian2_id->SqlSelect = "";
		$this->pembagian2_id->SqlOrderBy = "";

		// rumus_id
		$this->rumus_id = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_rumus_id', 'rumus_id', '`rumus_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->rumus_id->Sortable = TRUE; // Allow sort
		$this->rumus_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['rumus_id'] = &$this->rumus_id;
		$this->rumus_id->DateFilter = "";
		$this->rumus_id->SqlSelect = "";
		$this->rumus_id->SqlOrderBy = "";

		// rumus_nama
		$this->rumus_nama = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_rumus_nama', 'rumus_nama', '`rumus_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->rumus_nama->Sortable = TRUE; // Allow sort
		$this->fields['rumus_nama'] = &$this->rumus_nama;
		$this->rumus_nama->DateFilter = "";
		$this->rumus_nama->SqlSelect = "";
		$this->rumus_nama->SqlOrderBy = "";

		// hk_gol
		$this->hk_gol = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_hk_gol', 'hk_gol', '`hk_gol`', 16, EWR_DATATYPE_NUMBER, -1);
		$this->hk_gol->Sortable = TRUE; // Allow sort
		$this->hk_gol->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['hk_gol'] = &$this->hk_gol;
		$this->hk_gol->DateFilter = "";
		$this->hk_gol->SqlSelect = "";
		$this->hk_gol->SqlOrderBy = "";

		// umr
		$this->umr = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_umr', 'umr', '`umr`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->umr->Sortable = TRUE; // Allow sort
		$this->umr->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['umr'] = &$this->umr;
		$this->umr->DateFilter = "";
		$this->umr->SqlSelect = "";
		$this->umr->SqlOrderBy = "";

		// hk_jml
		$this->hk_jml = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_hk_jml', 'hk_jml', '`hk_jml`', 2, EWR_DATATYPE_NUMBER, -1);
		$this->hk_jml->Sortable = TRUE; // Allow sort
		$this->hk_jml->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['hk_jml'] = &$this->hk_jml;
		$this->hk_jml->DateFilter = "";
		$this->hk_jml->SqlSelect = "";
		$this->hk_jml->SqlOrderBy = "";

		// upah
		$this->upah = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_upah', 'upah', '`upah`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->upah->Sortable = TRUE; // Allow sort
		$this->upah->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['upah'] = &$this->upah;
		$this->upah->DateFilter = "";
		$this->upah->SqlSelect = "";
		$this->upah->SqlOrderBy = "";

		// premi_hadir
		$this->premi_hadir = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_premi_hadir', 'premi_hadir', '`premi_hadir`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->premi_hadir->Sortable = TRUE; // Allow sort
		$this->premi_hadir->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['premi_hadir'] = &$this->premi_hadir;
		$this->premi_hadir->DateFilter = "";
		$this->premi_hadir->SqlSelect = "";
		$this->premi_hadir->SqlOrderBy = "";

		// premi_malam
		$this->premi_malam = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_premi_malam', 'premi_malam', '`premi_malam`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->premi_malam->Sortable = TRUE; // Allow sort
		$this->premi_malam->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['premi_malam'] = &$this->premi_malam;
		$this->premi_malam->DateFilter = "";
		$this->premi_malam->SqlSelect = "";
		$this->premi_malam->SqlOrderBy = "";

		// pot_absen
		$this->pot_absen = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_pot_absen', 'pot_absen', '`pot_absen`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->pot_absen->Sortable = TRUE; // Allow sort
		$this->pot_absen->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['pot_absen'] = &$this->pot_absen;
		$this->pot_absen->DateFilter = "";
		$this->pot_absen->SqlSelect = "";
		$this->pot_absen->SqlOrderBy = "";

		// lembur
		$this->lembur = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_lembur', 'lembur', '`lembur`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->lembur->Sortable = TRUE; // Allow sort
		$this->lembur->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['lembur'] = &$this->lembur;
		$this->lembur->DateFilter = "";
		$this->lembur->SqlSelect = "";
		$this->lembur->SqlOrderBy = "";

		// upah2
		$this->upah2 = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_upah2', 'upah2', '`upah2`', 5, EWR_DATATYPE_NUMBER, -1);
		$this->upah2->Sortable = TRUE; // Allow sort
		$this->upah2->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['upah2'] = &$this->upah2;
		$this->upah2->DateFilter = "";
		$this->upah2->SqlSelect = "";
		$this->upah2->SqlOrderBy = "";

		// premi_malam2
		$this->premi_malam2 = new crField('r_lapgjhrn', 'r_lapgjhrn', 'x_premi_malam2', 'premi_malam2', '`premi_malam2`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->premi_malam2->Sortable = TRUE; // Allow sort
		$this->premi_malam2->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['premi_malam2'] = &$this->premi_malam2;
		$this->premi_malam2->DateFilter = "";
		$this->premi_malam2->SqlSelect = "";
		$this->premi_malam2->SqlOrderBy = "";
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_lapgjhrn`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`lapgroup_nama` ASC, `pembagian2_nama` ASC, `pegawai_nama` ASC";
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
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "`lapgroup_nama`";
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
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "`lapgroup_nama` ASC";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT SUM(`upah`) AS `sum_upah`, SUM(`premi_hadir`) AS `sum_premi_hadir`, SUM(`premi_malam`) AS `sum_premi_malam`, SUM(`pot_absen`) AS `sum_pot_absen`, SUM(`upah2`) AS `sum_upah2`, SUM(`premi_malam2`) AS `sum_premi_malam2` FROM " . $this->getSqlFrom();
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
