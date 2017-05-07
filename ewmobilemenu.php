<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(77, "mmi_home_php", $Language->MenuPhrase("77", "MenuText"), "home.php", -1, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(78, "mmci_Setup", $Language->MenuPhrase("78", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(32, "mmi_pembagian1", $Language->MenuPhrase("32", "MenuText"), "pembagian1list.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}pembagian1'), FALSE, FALSE);
$RootMenu->AddMenuItem(44, "mmi_t_lapgroup", $Language->MenuPhrase("44", "MenuText"), "t_lapgrouplist.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_lapgroup'), FALSE, FALSE);
$RootMenu->AddMenuItem(33, "mmi_pembagian2", $Language->MenuPhrase("33", "MenuText"), "pembagian2list.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}pembagian2'), FALSE, FALSE);
$RootMenu->AddMenuItem(43, "mmi_t_jk", $Language->MenuPhrase("43", "MenuText"), "t_jklist.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_jk'), FALSE, FALSE);
$RootMenu->AddMenuItem(47, "mmi_t_rumus2", $Language->MenuPhrase("47", "MenuText"), "t_rumus2list.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_rumus2'), FALSE, FALSE);
$RootMenu->AddMenuItem(46, "mmi_t_rumus", $Language->MenuPhrase("46", "MenuText"), "t_rumuslist.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_rumus'), FALSE, FALSE);
$RootMenu->AddMenuItem(30, "mmi_pegawai", $Language->MenuPhrase("30", "MenuText"), "pegawailist.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(41, "mmi_t_jdw_krj_def", $Language->MenuPhrase("41", "MenuText"), "t_jdw_krj_deflist.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_jdw_krj_def'), FALSE, FALSE);
$RootMenu->AddMenuItem(10230, "mmi_t_pengecualian_peg", $Language->MenuPhrase("10230", "MenuText"), "t_pengecualian_peglist.php?cmd=resetall", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_pengecualian_peg'), FALSE, FALSE);
$RootMenu->AddMenuItem(50, "mmi_t_user", $Language->MenuPhrase("50", "MenuText"), "t_userlist.php", 78, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}t_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(10224, "mmci_Proses", $Language->MenuPhrase("10224", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10227, "mmi_gen_jdw_krj__php", $Language->MenuPhrase("10227", "MenuText"), "gen_jdw_krj_.php", 10224, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}gen_jdw_krj_.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(10077, "mmci_Laporan", $Language->MenuPhrase("10077", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10225, "mmri_r5fatt5flog", $Language->MenuPhrase("10225", "MenuText"), "r_att_logsmry.php", 10077, "{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}", AllowListMenu('{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}r_att_log'), FALSE, FALSE);
$RootMenu->AddMenuItem(10080, "mmri_r5fjdw5fkrj5fdef", $Language->MenuPhrase("10080", "MenuText"), "r_jdw_krj_defsmry.php", 10077, "{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}", AllowListMenu('{6A79AFFA-AA3A-4CBB-8572-5F6C56B1E5B1}r_jdw_krj_def'), FALSE, FALSE);
$RootMenu->AddMenuItem(10083, "mmi_gen_rekon__php", $Language->MenuPhrase("10083", "MenuText"), "gen_rekon_.php", 10077, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}gen_rekon_.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(10229, "mmi_lap_gaji2__php", $Language->MenuPhrase("10229", "MenuText"), "lap_gaji2_.php", 10077, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}lap_gaji2_.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(10228, "mmi_lap_gaji__php", $Language->MenuPhrase("10228", "MenuText"), "lap_gaji_.php", 10077, "", AllowListMenu('{9712DCF3-D9FD-406D-93E5-FEA5020667C8}lap_gaji_.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
