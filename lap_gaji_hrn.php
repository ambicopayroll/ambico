<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$lap_gaji_hrn_php = NULL; // Initialize page object first

class clap_gaji_hrn_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{9712DCF3-D9FD-406D-93E5-FEA5020667C8}";

	// Table name
	var $TableName = 'lap_gaji_hrn.php';

	// Page object name
	var $PageObjName = 'lap_gaji_hrn_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'lap_gaji_hrn.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
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
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		 // Close connection

		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "lap_gaji_hrn_php", $url, "", "lap_gaji_hrn_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($lap_gaji_hrn_php)) $lap_gaji_hrn_php = new clap_gaji_hrn_php();

// Page init
$lap_gaji_hrn_php->Page_Init();

// Page main
$lap_gaji_hrn_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($_SERVER["HTTP_HOST"] == "localhost" or $_SERVER["HTTP_HOST"] == "36.80.56.64") {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}
elseif ($_SERVER["HTTP_HOST"] == "ambico.nma-indonesia.com") {
	include "adodb5/adodb.inc.php";
	$conn = ADONewConnection('mysql');
	$conn->Connect('mysql.idhostinger.com','u945388674_ambi2','M457r1P 81','u945388674_ambi2');
}
echo "masuk";
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ambico');
$pdf->SetTitle('Upah');
$pdf->SetSubject('Upah');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('times', 'BI', 20);

// add a page
//$pdf->AddPage("L", "A4");
$pdf->AddPage();
$pdf->SetFont('times', '', 10);

$html  = '<table border="0" width="300">';
$html .= '<tr><td>DAFTAR UPAH HARIAN LEPAS</td></tr>';
$html .= '<tr><td>PT AMBICO - CARAT</td></tr>';
$html .= '<tr><td>Periode '.$_POST["start"].' s.d. '.$_POST["end"].'</td></tr>';
$html .= '</table>';

$html .= '<table border="1" width="100%">';
$html .= '
	<tr>
		<th rowspan="2" align="center"width="35">No.</th>
		<th rowspan="2" align="center" width="150">Nama / Bagian</th>
		<th rowspan="2" align="center" width="50">NP</th>
		<th rowspan="2" align="center">Total Upah</th>
		<th colspan="2" align="center">Premi</th>
		<th rowspan="2" align="center">Absen</th>
		<th rowspan="2" align="center">Jumlah Terima</th>
	</tr>';
$html .= '
	<tr>
		<th align="center">Malam</th>
		<th align="center">Hadir</th>
	</tr>';

$mno = 1;

/*$msql = "
	select * from
		v_jdw_krj_def a
		left join t_rumus_peg b on a.pegawai_id = b.pegawai_id
		left join t_rumus c on b.rumus_id = c.rumus_id
	where
		tgl between '".$_POST['start']."' and '".$_POST['end']."'
		and c.hk_gol = a.hk_def
		and a.pegawai_id not in (select pegawai_id from t_rumus2_peg)
	order by
		a.pegawai_id
		, tgl
	"; echo $msql; exit; */
$msql = "
	select
		e.lapgroup_id
		, e.lapgroup_nama
		, e.lapgroup_index
		, d.lapsubgroup_index
		, a.*
		, c.*
	from
		v_jdw_krj_def a
		left join t_rumus_peg b on a.pegawai_id = b.pegawai_id
		left join t_rumus c on b.rumus_id = c.rumus_id
		left join t_lapsubgroup d on a.pembagian2_id = d.pembagian2_id
		left join t_lapgroup e on d.lapgroup_id = e.lapgroup_id
	where
		tgl between '".$_POST['start']."' and '".$_POST['end']."'
		and c.hk_gol = a.hk_def
		and a.pegawai_id not in (select pegawai_id from t_rumus2_peg)
	order by
		e.lapgroup_index
		, d.lapsubgroup_index
		, a.pegawai_id
		, a.tgl
	"; //echo $msql; exit;
$rs = $conn->Execute($msql);
while (!$rs->EOF) {
	$mlapgroup_id = $rs->fields["lapgroup_id"];
	$mlapgroup_nama = $rs->fields["lapgroup_nama"];
	$mtotal1 = 0;
	$html .= '
		<tr>
			<td>&nbsp;</td>
			<td><b>'.$mlapgroup_nama.'</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>';
	while ($rs->fields["lapgroup_id"] == $mlapgroup_id and !$rs->EOF) {
		$mpembagian2_id = $rs->fields["pembagian2_id"];
		$mpembagian2_nama = $rs->fields["pembagian2_nama"];
		$mtotal2 = 0;
		$html .= '
			<tr>
				<td>&nbsp;</td>
				<td align="center"><b>'.$mpembagian2_nama.'</b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>';
		while ($rs->fields["pembagian2_id"] == $mpembagian2_id and !$rs->EOF) {
			$mpegawai_id = $rs->fields["pegawai_id"];
			$mpegawai_nama = $rs->fields["pegawai_nama"];
			$mpegawai_nip = $rs->fields["pegawai_nip"];
			$mupah = 0;
			$mpremi_malam = 0;
			$mpremi_hadir = 0;
			$mtidak_masuk = 0;
			$mpot_absen = 0;
			$mjml_premi_malam = 0;
			while ($mpegawai_id == $rs->fields["pegawai_id"] and !$rs->EOF) {
				
				// check data valid
				$data_valid = false; //echo "tgl. ".$rs->fields["tgl"].$rs->fields["scan_masuk"];
				if (!is_null($rs->fields["scan_masuk"]) and !is_null($rs->fields["scan_keluar"])) {
					$data_valid = true; //echo "valid";
					$mupah += $rs->fields["upah"];
				}
				
				// hitung premi hadir & pot. absen
				// echo substr($rs2->fields["jk_kd"], -1); exit;
				if (!$data_valid and substr($rs->fields["jk_kd"], -1) != "L") {
					$mpremi_hadir = 0;
					$msql = "select f_cari_pengecualian(".$mpegawai_id.", '".$rs->fields["tgl"]."') as ada";
					$rs3 = $conn->Execute($msql); // echo $msql; exit;
					if ($rs3->fields["ada"]) {
						
					}
					else {
						$mpot_absen += $rs->fields["pot_absen"];
					}
				}
				
				//$mupah += $rs->fields["upah"];
				if (substr($rs->fields["jk_kd"], 0, 2) == "S3") {
					//$mjml_premi_malam++;
					//if ($mjml_premi_malam <= 5) {
						$mpremi_malam += $rs->fields["premi_malam"];
					//}
				}
				$mpremi_hadir = $rs->fields["premi_hadir"];
				//if (is_null($rs->fields["gol_hk"])) {
				/*if (is_null($rs->fields["scan_masuk"]) or is_null($rs->fields["scan_keluar"])) {
					$mtidak_masuk = 1;
					$mpot_absen += $rs->fields["pot_absen"];
				}*/
				$rs->MoveNext();
			}
			$mtotal = $mupah + $mpremi_malam + $mpremi_hadir - $mpot_absen;
			$mtotal2 += $mupah;
			// echo $mupah." ".$mpremi_malam." ".($mtidak_masuk ? "0" : $mpremi_hadir)." ".$mtotal;
			$html .= '<tr><td align="right">'.$mno.'.&nbsp;</td><td>&nbsp;'.$mpegawai_nama.'</td><td align="center">'.$mpegawai_nip.'</td>'.'<td align="right">'.number_format($mupah).'</td>'.'<td align="right">'.number_format($mpremi_malam).'</td>'.'<td align="right">'.($mtidak_masuk ? "" : number_format($mpremi_hadir)).'</td>'.'<td align="right">'.number_format($mpot_absen).'</td>'.'<td align="right">'.number_format($mtotal).'</td></tr>';
			$mno++;			
		}
		$html .= '
			<tr>
				<td>&nbsp;</td>
				<td>Sub Total</td>
				<td>&nbsp;</td>
				<td align="right">'.number_format($mtotal2).'</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>';
		$mtotal1 += $mtotal2;
	}
	$html .= '
		<tr>
			<td>&nbsp;</td>
			<td>Total '.$mlapgroup_nama.'</td>
			<td>&nbsp;</td>
			<td align="right">'.number_format($mtotal1).'</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>';
	$mgrand_total += $mtotal1;
}
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>Total Seluruh Bagian</td>
		<td>&nbsp;</td>
		<td align="right">'.number_format($mgrand_total).'</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>';

$html .= '</table>';
//$html .= $msql;
//$pdf->writeHTML($html, true, false, true, false, '');
//$pdf->Output('Upah.pdf', 'I');
echo $html;

$rs->Close();
//$conn->Close();

// header("location: ./payroll_.php?ok=1");

?>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$lap_gaji_hrn_php->Page_Terminate();
?>
