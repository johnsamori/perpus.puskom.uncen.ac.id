<?php

namespace PHPMaker2025\perpus2025baru;

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(4, "mi_home", $Language->menuPhrase("4", "MenuText"), "home", -1, substr("mi_home", strpos("mi_home", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}home.php'), false, false, "fa-home", "", false, true);
$sideMenu->addMenuItem(48, "mci_Master", $Language->menuPhrase("48", "MenuText"), "", -1, substr("mci_Master", strpos("mci_Master", "mi_") + 3), true, false, true, "fa-book", "", false, true);
$sideMenu->addMenuItem(50, "mi_fakultas", $Language->menuPhrase("50", "MenuText"), "fakultaslist", 48, substr("mi_fakultas", strpos("mi_fakultas", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}fakultas'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(51, "mi_jurusan", $Language->menuPhrase("51", "MenuText"), "jurusanlist", 48, substr("mi_jurusan", strpos("mi_jurusan", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}jurusan'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(54, "mi_program_studi", $Language->menuPhrase("54", "MenuText"), "programstudilist", 48, substr("mi_program_studi", strpos("mi_program_studi", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}program_studi'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(53, "mi_pejabat", $Language->menuPhrase("53", "MenuText"), "pejabatlist", 48, substr("mi_pejabat", strpos("mi_pejabat", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}pejabat'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(49, "mci_Data", $Language->menuPhrase("49", "MenuText"), "", -1, substr("mci_Data", strpos("mci_Data", "mi_") + 3), true, false, true, "fa-book", "", false, true);
$sideMenu->addMenuItem(52, "mi_mahasiswa", $Language->menuPhrase("52", "MenuText"), "mahasiswalist", 49, substr("mi_mahasiswa", strpos("mi_mahasiswa", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}mahasiswa'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(55, "mi_surat_keterangan", $Language->menuPhrase("55", "MenuText"), "suratketeranganlist", 49, substr("mi_surat_keterangan", strpos("mi_surat_keterangan", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}surat_keterangan'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(16, "mi_theuserprofile", $Language->menuPhrase("16", "MenuText"), "theuserprofilelist", -1, substr("mi_theuserprofile", strpos("mi_theuserprofile", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}theuserprofile'), false, false, "fa-user", "", false, true);
$sideMenu->addMenuItem(5, "mi_help_categories", $Language->menuPhrase("5", "MenuText"), "helpcategorieslist", -1, substr("mi_help_categories", strpos("mi_help_categories", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}help_categories'), false, false, "fa-book", "", false, true);
$sideMenu->addMenuItem(6, "mi_help", $Language->menuPhrase("6", "MenuText"), "helplist?cmd=resetall", 5, substr("mi_help", strpos("mi_help", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}help'), false, false, "fa-book", "", false, true);
$sideMenu->addMenuItem(13, "mci_Terms_and_Condition", $Language->menuPhrase("13", "MenuText"), "javascript:void(0);|||getTermsConditions();return false;", 5, substr("mci_Terms_and_Condition", strpos("mci_Terms_and_Condition", "mi_") + 3), true, false, true, "fas fa-cannabis", "", false, true);
$sideMenu->addMenuItem(14, "mci_About_Us", $Language->menuPhrase("14", "MenuText"), "javascript:void(0);|||getAboutUs();return false;", 5, substr("mci_About_Us", strpos("mci_About_Us", "mi_") + 3), true, false, true, "fa-question", "", false, true);
$sideMenu->addMenuItem(12, "mci_ADMIN", $Language->menuPhrase("12", "MenuText"), "", -1, substr("mci_ADMIN", strpos("mci_ADMIN", "mi_") + 3), true, false, true, "fa-key", "", false, true);
$sideMenu->addMenuItem(1, "mi_users", $Language->menuPhrase("1", "MenuText"), "userslist?cmd=resetall", 12, substr("mi_users", strpos("mi_users", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}users'), false, false, "fa-user", "", false, true);
$sideMenu->addMenuItem(3, "mi_userlevels", $Language->menuPhrase("3", "MenuText"), "userlevelslist", 12, substr("mi_userlevels", strpos("mi_userlevels", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}userlevels'), false, false, "fa-tags", "", false, true);
$sideMenu->addMenuItem(2, "mi_userlevelpermissions", $Language->menuPhrase("2", "MenuText"), "userlevelpermissionslist", 12, substr("mi_userlevelpermissions", strpos("mi_userlevelpermissions", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}userlevelpermissions'), false, false, "fa-file", "", false, true);
$sideMenu->addMenuItem(8, "mi_settings", $Language->menuPhrase("8", "MenuText"), "settingslist", 12, substr("mi_settings", strpos("mi_settings", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}settings'), false, false, "fa-tools", "", false, true);
$sideMenu->addMenuItem(7, "mi_languages", $Language->menuPhrase("7", "MenuText"), "languageslist", 12, substr("mi_languages", strpos("mi_languages", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}languages'), false, false, "fa-flag", "", false, true);
$sideMenu->addMenuItem(15, "mi_announcement", $Language->menuPhrase("15", "MenuText"), "announcementlist", 12, substr("mi_announcement", strpos("mi_announcement", "mi_") + 3), AllowListMenu('{72B3345B-D446-402B-9B28-F1CD922DC9A6}announcement'), false, false, "fas fa-bullhorn", "", false, true);
echo $sideMenu->toScript();
