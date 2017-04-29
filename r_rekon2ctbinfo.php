<?php

// Global variable for table object
$r_rekon2 = NULL;

//
// Table class for r_rekon2
//
class crr_rekon2 extends crTableCrosstab {
	var $pegawai_id;
	var $tgl;
	var $jk_id;
	var $hk_def;
	var $pegawai_pin;
	var $pegawai_nama;
	var $pembagian2_nama;
	var $jk_kd;
	var $jk_m;
	var $jk_k;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_rekon2';
		$this->TableName = 'r_rekon2';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// pegawai_id
		$this->pegawai_id = new crField('r_rekon2', 'r_rekon2', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;
		$this->pegawai_id->DateFilter = "";
		$this->pegawai_id->SqlSelect = "";
		$this->pegawai_id->SqlOrderBy = "";

		// tgl
		$this->tgl = new crField('r_rekon2', 'r_rekon2', 'x_tgl', 'tgl', '`tgl`', 133, EWR_DATATYPE_DATE, 0);
		$this->tgl->Sortable = TRUE; // Allow sort
		$this->tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['tgl'] = &$this->tgl;
		$this->tgl->DateFilter = "";
		$this->tgl->SqlSelect = "SELECT DISTINCT `tgl`, `tgl` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->tgl->SqlOrderBy = "`tgl`";
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Past", $ReportLanguage->Phrase("Past"), "ewr_IsPast");
		ewr_RegisterFilter($this->tgl, "@@Future", $ReportLanguage->Phrase("Future"), "ewr_IsFuture");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Last30Days", $ReportLanguage->Phrase("Last30Days"), "ewr_IsLast30Days");
		ewr_RegisterFilter($this->tgl, "@@Last14Days", $ReportLanguage->Phrase("Last14Days"), "ewr_IsLast14Days");
		ewr_RegisterFilter($this->tgl, "@@Last7Days", $ReportLanguage->Phrase("Last7Days"), "ewr_IsLast7Days");
		ewr_RegisterFilter($this->tgl, "@@Next7Days", $ReportLanguage->Phrase("Next7Days"), "ewr_IsNext7Days");
		ewr_RegisterFilter($this->tgl, "@@Next14Days", $ReportLanguage->Phrase("Next14Days"), "ewr_IsNext14Days");
		ewr_RegisterFilter($this->tgl, "@@Next30Days", $ReportLanguage->Phrase("Next30Days"), "ewr_IsNext30Days");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@Yesterday", $ReportLanguage->Phrase("Yesterday"), "ewr_IsYesterday");
		ewr_RegisterFilter($this->tgl, "@@Today", $ReportLanguage->Phrase("Today"), "ewr_IsToday");
		ewr_RegisterFilter($this->tgl, "@@Tomorrow", $ReportLanguage->Phrase("Tomorrow"), "ewr_IsTomorrow");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastMonth", $ReportLanguage->Phrase("LastMonth"), "ewr_IsLastMonth");
		ewr_RegisterFilter($this->tgl, "@@ThisMonth", $ReportLanguage->Phrase("ThisMonth"), "ewr_IsThisMonth");
		ewr_RegisterFilter($this->tgl, "@@NextMonth", $ReportLanguage->Phrase("NextMonth"), "ewr_IsNextMonth");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastTwoWeeks", $ReportLanguage->Phrase("LastTwoWeeks"), "ewr_IsLast2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastWeek", $ReportLanguage->Phrase("LastWeek"), "ewr_IsLastWeek");
		ewr_RegisterFilter($this->tgl, "@@ThisWeek", $ReportLanguage->Phrase("ThisWeek"), "ewr_IsThisWeek");
		ewr_RegisterFilter($this->tgl, "@@NextWeek", $ReportLanguage->Phrase("NextWeek"), "ewr_IsNextWeek");
		ewr_RegisterFilter($this->tgl, "@@NextTwoWeeks", $ReportLanguage->Phrase("NextTwoWeeks"), "ewr_IsNext2Weeks");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");
		ewr_RegisterFilter($this->tgl, "@@LastYear", $ReportLanguage->Phrase("LastYear"), "ewr_IsLastYear");
		ewr_RegisterFilter($this->tgl, "@@ThisYear", $ReportLanguage->Phrase("ThisYear"), "ewr_IsThisYear");
		ewr_RegisterFilter($this->tgl, "@@NextYear", $ReportLanguage->Phrase("NextYear"), "ewr_IsNextYear");

		// jk_id
		$this->jk_id = new crField('r_rekon2', 'r_rekon2', 'x_jk_id', 'jk_id', '`jk_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->jk_id->Sortable = TRUE; // Allow sort
		$this->jk_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['jk_id'] = &$this->jk_id;
		$this->jk_id->DateFilter = "";
		$this->jk_id->SqlSelect = "";
		$this->jk_id->SqlOrderBy = "";

		// hk_def
		$this->hk_def = new crField('r_rekon2', 'r_rekon2', 'x_hk_def', 'hk_def', '`hk_def`', 16, EWR_DATATYPE_NUMBER, -1);
		$this->hk_def->Sortable = TRUE; // Allow sort
		$this->hk_def->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['hk_def'] = &$this->hk_def;
		$this->hk_def->DateFilter = "";
		$this->hk_def->SqlSelect = "";
		$this->hk_def->SqlOrderBy = "";

		// pegawai_pin
		$this->pegawai_pin = new crField('r_rekon2', 'r_rekon2', 'x_pegawai_pin', 'pegawai_pin', '`pegawai_pin`', 200, EWR_DATATYPE_STRING, -1);
		$this->pegawai_pin->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_pin'] = &$this->pegawai_pin;
		$this->pegawai_pin->DateFilter = "";
		$this->pegawai_pin->SqlSelect = "";
		$this->pegawai_pin->SqlOrderBy = "";

		// pegawai_nama
		$this->pegawai_nama = new crField('r_rekon2', 'r_rekon2', 'x_pegawai_nama', 'pegawai_nama', '`pegawai_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->pegawai_nama->Sortable = TRUE; // Allow sort
		$this->pegawai_nama->GroupingFieldId = 2;
		$this->fields['pegawai_nama'] = &$this->pegawai_nama;
		$this->pegawai_nama->DateFilter = "";
		$this->pegawai_nama->SqlSelect = "";
		$this->pegawai_nama->SqlOrderBy = "";

		// pembagian2_nama
		$this->pembagian2_nama = new crField('r_rekon2', 'r_rekon2', 'x_pembagian2_nama', 'pembagian2_nama', '`pembagian2_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->pembagian2_nama->Sortable = TRUE; // Allow sort
		$this->pembagian2_nama->GroupingFieldId = 1;
		$this->fields['pembagian2_nama'] = &$this->pembagian2_nama;
		$this->pembagian2_nama->DateFilter = "";
		$this->pembagian2_nama->SqlSelect = "";
		$this->pembagian2_nama->SqlOrderBy = "";

		// jk_kd
		$this->jk_kd = new crField('r_rekon2', 'r_rekon2', 'x_jk_kd', 'jk_kd', '`jk_kd`', 200, EWR_DATATYPE_STRING, -1);
		$this->jk_kd->Sortable = TRUE; // Allow sort
		$this->fields['jk_kd'] = &$this->jk_kd;
		$this->jk_kd->DateFilter = "";
		$this->jk_kd->SqlSelect = "";
		$this->jk_kd->SqlOrderBy = "";

		// jk_m
		$this->jk_m = new crField('r_rekon2', 'r_rekon2', 'x_jk_m', 'jk_m', '`jk_m`', 134, EWR_DATATYPE_TIME, -1);
		$this->jk_m->Sortable = TRUE; // Allow sort
		$this->jk_m->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectTime");
		$this->fields['jk_m'] = &$this->jk_m;
		$this->jk_m->DateFilter = "";
		$this->jk_m->SqlSelect = "";
		$this->jk_m->SqlOrderBy = "";

		// jk_k
		$this->jk_k = new crField('r_rekon2', 'r_rekon2', 'x_jk_k', 'jk_k', '`jk_k`', 134, EWR_DATATYPE_TIME, -1);
		$this->jk_k->Sortable = TRUE; // Allow sort
		$this->jk_k->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectTime");
		$this->fields['jk_k'] = &$this->jk_k;
		$this->jk_k->DateFilter = "";
		$this->jk_k->SqlSelect = "";
		$this->jk_k->SqlOrderBy = "";
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
	// Column field

	var $ColumnField = "";

	function getColumnField() {
		return ($this->ColumnField <> "") ? $this->ColumnField : "`tgl`";
	}

	function setColumnField($v) {
		$this->ColumnField = $v;
	}

	// Column date type
	var $ColumnDateType = "";

	function getColumnDateType() {
		return ($this->ColumnDateType <> "") ? $this->ColumnDateType : "d";
	}

	function setColumnDateType($v) {
		$this->ColumnDateType = $v;
	}

	// Column captions
	var $ColumnCaptions = "";

	function getColumnCaptions() {
		global $ReportLanguage;
		return ($this->ColumnCaptions <> "") ? $this->ColumnCaptions : "";
	}

	function setColumnCaptions($v) {
		$this->ColumnCaptions = $v;
	}

	// Column names
	var $ColumnNames = "";

	function getColumnNames() {
		return ($this->ColumnNames <> "") ? $this->ColumnNames : "";
	}

	function setColumnNames($v) {
		$this->ColumnNames = $v;
	}

	// Column values
	var $ColumnValues = "";

	function getColumnValues() {
		return ($this->ColumnValues <> "") ? $this->ColumnValues : "";
	}

	function setColumnValues($v) {
		$this->ColumnValues = $v;
	}

	// From
	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_jdw_krj_def_pegawai`";
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
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT `pembagian2_nama`, `pegawai_nama`, <DistinctColumnFields> FROM " . $this->getSqlFrom();
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
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "`pembagian2_nama`, `pegawai_nama`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`pembagian2_nama` ASC, `pegawai_nama` ASC";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Select Distinct
	var $_SqlDistinctSelect = "";

	function getSqlDistinctSelect() {
		return ($this->_SqlDistinctSelect <> "") ? $this->_SqlDistinctSelect : "SELECT DISTINCT DATE_FORMAT(`tgl`,'%Y-%m-%d') FROM `v_jdw_krj_def_pegawai`";
	}

	function SqlDistinctSelect() { // For backward compatibility
		return $this->getSqlDistinctSelect();
	}

	function setSqlDistinctSelect($v) {
		$this->_SqlDistinctSelect = $v;
	}

	// Distinct Where
	var $_SqlDistinctWhere = "";

	function getSqlDistinctWhere() {
		$sWhere = ($this->_SqlDistinctWhere <> "") ? $this->_SqlDistinctWhere : "";
		return $sWhere;
	}

	function SqlDistinctWhere() { // For backward compatibility
		return $this->getSqlDistinctWhere();
	}

	function setSqlDistinctWhere($v) {
		$this->_SqlDistinctWhere = $v;
	}

	// Distinct Order By
	var $_SqlDistinctOrderBy = "";

	function getSqlDistinctOrderBy() {
		return ($this->_SqlDistinctOrderBy <> "") ? $this->_SqlDistinctOrderBy : "DATE_FORMAT(`tgl`,'%Y-%m-%d') ASC";
	}

	function SqlDistinctOrderBy() { // For backward compatibility
		return $this->getSqlDistinctOrderBy();
	}

	function setSqlDistinctOrderBy($v) {
		$this->_SqlDistinctOrderBy = $v;
	}
	var $ColCount;
	var $Col;
	var $DistinctColumnFields = "";

	// Load column values
	function LoadColumnValues($filter = "") {
		global $ReportLanguage;
		$conn = &$this->Connection();

		// Build SQL
		$sSql = ewr_BuildReportSql($this->getSqlDistinctSelect(), $this->getSqlDistinctWhere(), "", "", $this->getSqlDistinctOrderBy(), $filter, "");

		// Load recordset
		$rscol = $conn->Execute($sSql);

		// Get distinct column count
		$this->ColCount = ($rscol) ? $rscol->RecordCount() : 0;

/* Uncomment to show phrase
		if ($this->ColCount == 0) {
			if ($rscol) $rscol->Close();
			echo "<p>" . $ReportLanguage->Phrase("NoDistinctColVals") . $sSql . "</p>";
			exit();
		}
*/
		$this->Col = &ewr_Init2DArray($this->ColCount+1, 3, NULL);
		$colcnt = 0;
		while (!$rscol->EOF) {
			if (is_null($rscol->fields[0])) {
				$wrkValue = EWR_NULL_VALUE;
				$wrkCaption = $ReportLanguage->Phrase("NullLabel");
			} elseif ($rscol->fields[0] == "") {
				$wrkValue = EWR_EMPTY_VALUE;
				$wrkCaption = $ReportLanguage->Phrase("EmptyLabel");
			} else {
				$wrkValue = $rscol->fields[0];
				$wrkCaption = $rscol->fields[0];
			}
			$colcnt++;
			$this->Col[$colcnt] = new crCrosstabColumn($wrkValue, $wrkCaption, TRUE);
			$rscol->MoveNext();
		}
		$rscol->Close();

		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of distinct values

		$nGrps = 2;
		$this->SummaryFields[0] = new crSummaryField('x_hk_def', 'hk_def', '`hk_def`', 'MAX');
		$this->SummaryFields[0]->SummaryCaption = $ReportLanguage->Phrase("RptMax");
		$this->SummaryFields[0]->SummaryVal = &ewr_InitArray($this->ColCount+1, NULL);
		$this->SummaryFields[0]->SummaryValCnt = &ewr_InitArray($this->ColCount+1, NULL);
		$this->SummaryFields[0]->SummaryCnt = &ewr_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SummaryFields[0]->SummarySmry = &ewr_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SummaryFields[0]->SummarySmryCnt = &ewr_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SummaryFields[0]->SummaryInitValue = NULL;
		$this->SummaryFields[1] = new crSummaryField('x_jk_kd', 'jk_kd', '`jk_kd`', 'MAX');
		$this->SummaryFields[1]->SummaryCaption = $ReportLanguage->Phrase("RptMax");
		$this->SummaryFields[1]->SummaryVal = &ewr_InitArray($this->ColCount+1, NULL);
		$this->SummaryFields[1]->SummaryValCnt = &ewr_InitArray($this->ColCount+1, NULL);
		$this->SummaryFields[1]->SummaryCnt = &ewr_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SummaryFields[1]->SummarySmry = &ewr_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SummaryFields[1]->SummarySmryCnt = &ewr_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SummaryFields[1]->SummaryInitValue = NULL;

		// Update crosstab sql
		$sSqlFlds = "";
		$cnt = count($this->SummaryFields);
		for ($is = 0; $is < $cnt; $is++) {
			$smry = &$this->SummaryFields[$is];
			for ($colcnt = 1; $colcnt <= $this->ColCount; $colcnt++) {
				$sFld = ewr_CrossTabField($smry->SummaryType, $smry->FldExpression, $this->getColumnField(), $this->getColumnDateType(), $this->Col[$colcnt]->Value, "'", "C" . $is . $colcnt, $this->DBID);
				if ($sSqlFlds <> "")
					$sSqlFlds .= ", ";
				$sSqlFlds .= $sFld;
			}
		}
		$this->DistinctColumnFields = $sSqlFlds;
	}

	// Table Level Group SQL
	// First Group Field

	var $_SqlFirstGroupField = "";

	function getSqlFirstGroupField() {
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "`pembagian2_nama`";
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
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "`pembagian2_nama` ASC";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT <DistinctColumnFields> FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Group By Aggregate
	var $_SqlGroupByAgg = "";

	function getSqlGroupByAgg() {
		return ($this->_SqlGroupByAgg <> "") ? $this->_SqlGroupByAgg : "";
	}

	function SqlGroupByAgg() { // For backward compatibility
		return $this->getSqlGroupByAgg();
	}

	function setSqlGroupByAgg($v) {
		$this->_SqlGroupByAgg = $v;
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
