<?php
require("phpqrcode/qrlib.php");
$tempdir = "";
QRcode::png($row['Nomor_Surat'] . "  " . $row['Nama_Mahasiswa'] . "  " . $row['Nama_Program_Studi'], $tempdir . "file.png", QR_ECLEVEL_L, 3);
