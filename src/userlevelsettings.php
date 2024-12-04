<?php

namespace PHPMaker2025\perpus2025baru;

/**
 * User levels
 *
 * @var array<int, string, string>
 * [0] int User level ID
 * [1] string User level name
 * [2] string User level hierarchy
 */
$USER_LEVELS = [["-2","Anonymous",""],
    ["0","Default",""]];

/**
 * User roles
 *
 * @var array<int, string>
 * [0] int User level ID
 * [1] string User role name
 */
$USER_ROLES = [["-1","ROLE_ADMIN"],
    ["0","ROLE_DEFAULT"]];

/**
 * User level permissions
 *
 * @var array<string, int, int>
 * [0] string Project ID + Table name
 * [1] int User level ID
 * [2] int Permissions
 */
// Begin of modification by Masino Sinaga, September 17, 2023
$USER_LEVEL_PRIVS_1 = [["{72B3345B-D446-402B-9B28-F1CD922DC9A6}announcement","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}announcement","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}help","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}help","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}help_categories","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}help_categories","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}home.php","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}home.php","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}languages","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}languages","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}settings","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}settings","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}theuserprofile","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}theuserprofile","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}userlevelpermissions","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}userlevelpermissions","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}userlevels","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}userlevels","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}users","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}users","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}fakultas","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}fakultas","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}jurusan","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}jurusan","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}mahasiswa","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}mahasiswa","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}pejabat","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}pejabat","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}program_studi","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}program_studi","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}surat_keterangan","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}surat_keterangan","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}suratket.php","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}suratket.php","0","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}vsrtket","-2","0"],
    ["{72B3345B-D446-402B-9B28-F1CD922DC9A6}vsrtket","0","0"]];
$USER_LEVEL_PRIVS_2 = [["{72B3345B-D446-402B-9B28-F1CD922DC9A6}breadcrumblinksaddsp","-1","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}breadcrumblinkschecksp","-1","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}breadcrumblinksdeletesp","-1","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}breadcrumblinksmovesp","-1","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}loadhelponline","-2","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}loadaboutus","-2","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}loadtermsconditions","-2","8"],
					["{72B3345B-D446-402B-9B28-F1CD922DC9A6}printtermsconditions","-2","8"]];
$USER_LEVEL_PRIVS = array_merge($USER_LEVEL_PRIVS_1, $USER_LEVEL_PRIVS_2);
// End of modification by Masino Sinaga, September 17, 2023

/**
 * Tables
 *
 * @var array<string, string, string, bool, string>
 * [0] string Table name
 * [1] string Table variable name
 * [2] string Table caption
 * [3] bool Allowed for update (for userpriv.php)
 * [4] string Project ID
 * [5] string URL (for OthersController::index)
 */
// Begin of modification by Masino Sinaga, September 17, 2023
$USER_LEVEL_TABLES_1 = [["announcement","announcement","Announcement",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","announcementlist"],
    ["help","help","Help (Details)",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","helplist"],
    ["help_categories","help_categories","Help (Categories)",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","helpcategorieslist"],
    ["home.php","home","Home",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","home"],
    ["languages","languages","Languages",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","languageslist"],
    ["settings","settings","Application Settings",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","settingslist"],
    ["theuserprofile","theuserprofile","User Profile",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","theuserprofilelist"],
    ["userlevelpermissions","userlevelpermissions","User Level Permissions",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","userlevelpermissionslist"],
    ["userlevels","userlevels","User Levels",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","userlevelslist"],
    ["users","users","Users",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","userslist"],
    ["fakultas","fakultas","fakultas",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","fakultaslist"],
    ["jurusan","jurusan","jurusan",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","jurusanlist"],
    ["mahasiswa","mahasiswa","mahasiswa",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","mahasiswalist"],
    ["pejabat","pejabat","pejabat",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","pejabatlist"],
    ["program_studi","program_studi","program studi",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","programstudilist"],
    ["surat_keterangan","surat_keterangan","surat keterangan",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","suratketeranganlist"],
    ["suratket.php","suratket","Surat_Keterangan",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","suratket"],
    ["vsrtket","vsrtket","vsrtket",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","vsrtketlist"]];
$USER_LEVEL_TABLES_2 = [["breadcrumblinksaddsp","breadcrumblinksaddsp","System - Breadcrumb Links - Add",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","breadcrumblinksaddsp"],
						["breadcrumblinkschecksp","breadcrumblinkschecksp","System - Breadcrumb Links - Check",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","breadcrumblinkschecksp"],
						["breadcrumblinksdeletesp","breadcrumblinksdeletesp","System - Breadcrumb Links - Delete",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","breadcrumblinksdeletesp"],
						["breadcrumblinksmovesp","breadcrumblinksmovesp","System - Breadcrumb Links - Move",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","breadcrumblinksmovesp"],
						["loadhelponline","loadhelponline","System - Load Help Online",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","loadhelponline"],
						["loadaboutus","loadaboutus","System - Load About Us",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","loadaboutus"],
						["loadtermsconditions","loadtermsconditions","System - Load Terms and Conditions",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","loadtermsconditions"],
						["printtermsconditions","printtermsconditions","System - Print Terms and Conditions",true,"{72B3345B-D446-402B-9B28-F1CD922DC9A6}","printtermsconditions"]];
$USER_LEVEL_TABLES = array_merge($USER_LEVEL_TABLES_1, $USER_LEVEL_TABLES_2);
// End of modification by Masino Sinaga, September 17, 2023
