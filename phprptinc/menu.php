<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new crMenu(EWR_MENUBAR_ID); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(76, "mi_r_rekon", $ReportLanguage->Phrase("CrosstabReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("76", "MenuText") . $ReportLanguage->Phrase("CrosstabReportMenuItemSuffix"), "r_rekonctb.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
