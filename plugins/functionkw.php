<?php
require("phpqrcode/qrlib.php");
$tempdir="";
	QRcode::png($row['Id_Kwitansi']." ".$row['Uraian']." ".$row['Nama_Pegawai']." ".$row['Tanggal'],$tempdir."file.png",QR_ECLEVEL_L, 3);
	//QRcode::png('test',$tempdir."filekw.png",QR_ECLEVEL_L, 3);
?>