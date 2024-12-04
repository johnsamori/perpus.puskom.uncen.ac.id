<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$MahasiswaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<?php // Begin of Card view by Masino Sinaga, September 10, 2023 ?>
<?php if (!$Page->IsModal) { ?>
<div class="col-md-12">
  <div class="card shadow-sm">
    <div class="card-header">
	  <h4 class="card-title"><?php echo Language()->phrase("ViewCaption"); ?></h4>
	  <div class="card-tools">
	  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
	  </button>
	  </div>
	  <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
<?php } ?>
<?php // End of Card view by Masino Sinaga, September 10, 2023 ?>
<form name="fmahasiswaview" id="fmahasiswaview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { mahasiswa: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fmahasiswaview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fmahasiswaview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script<?= Nonce() ?>>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (Config("CSRF_PROTECTION") && Csrf()->isEnabled()) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" id="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" id="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mahasiswa">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Nim->Visible) { // Nim ?>
    <tr id="r_Nim"<?= $Page->Nim->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_Nim"><?= $Page->Nim->caption() ?></span></td>
        <td data-name="Nim"<?= $Page->Nim->cellAttributes() ?>>
<span id="el_mahasiswa_Nim">
<span<?= $Page->Nim->viewAttributes() ?>>
<?= $Page->Nim->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
    <tr id="r_Nama_Mahasiswa"<?= $Page->Nama_Mahasiswa->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_Nama_Mahasiswa"><?= $Page->Nama_Mahasiswa->caption() ?></span></td>
        <td data-name="Nama_Mahasiswa"<?= $Page->Nama_Mahasiswa->cellAttributes() ?>>
<span id="el_mahasiswa_Nama_Mahasiswa">
<span<?= $Page->Nama_Mahasiswa->viewAttributes() ?>>
<?= $Page->Nama_Mahasiswa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->No_Reg_Anggota->Visible) { // No_Reg_Anggota ?>
    <tr id="r_No_Reg_Anggota"<?= $Page->No_Reg_Anggota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_No_Reg_Anggota"><?= $Page->No_Reg_Anggota->caption() ?></span></td>
        <td data-name="No_Reg_Anggota"<?= $Page->No_Reg_Anggota->cellAttributes() ?>>
<span id="el_mahasiswa_No_Reg_Anggota">
<span<?= $Page->No_Reg_Anggota->viewAttributes() ?>>
<?= $Page->No_Reg_Anggota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Fakultas->Visible) { // Fakultas ?>
    <tr id="r_Fakultas"<?= $Page->Fakultas->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_Fakultas"><?= $Page->Fakultas->caption() ?></span></td>
        <td data-name="Fakultas"<?= $Page->Fakultas->cellAttributes() ?>>
<span id="el_mahasiswa_Fakultas">
<span<?= $Page->Fakultas->viewAttributes() ?>>
<?= $Page->Fakultas->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Jurusan->Visible) { // Jurusan ?>
    <tr id="r_Jurusan"<?= $Page->Jurusan->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_Jurusan"><?= $Page->Jurusan->caption() ?></span></td>
        <td data-name="Jurusan"<?= $Page->Jurusan->cellAttributes() ?>>
<span id="el_mahasiswa_Jurusan">
<span<?= $Page->Jurusan->viewAttributes() ?>>
<?= $Page->Jurusan->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Program_Studi->Visible) { // Program_Studi ?>
    <tr id="r_Program_Studi"<?= $Page->Program_Studi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_Program_Studi"><?= $Page->Program_Studi->caption() ?></span></td>
        <td data-name="Program_Studi"<?= $Page->Program_Studi->cellAttributes() ?>>
<span id="el_mahasiswa_Program_Studi">
<span<?= $Page->Program_Studi->viewAttributes() ?>>
<?= $Page->Program_Studi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Jenjang->Visible) { // Jenjang ?>
    <tr id="r_Jenjang"<?= $Page->Jenjang->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mahasiswa_Jenjang"><?= $Page->Jenjang->caption() ?></span></td>
        <td data-name="Jenjang"<?= $Page->Jenjang->cellAttributes() ?>>
<span id="el_mahasiswa_Jenjang">
<span<?= $Page->Jenjang->viewAttributes() ?>>
<?= $Page->Jenjang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php // Begin of Card view by Masino Sinaga, September 10, 2023 ?>
<?php if (!$Page->IsModal) { ?>
		</div>
     <!-- /.card-body -->
     </div>
  <!-- /.card -->
</div>
<?php } ?>
<?php // End of Card view by Masino Sinaga, September 10, 2023 ?>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
?>
<?php if (!$Page->IsModal && !$Page->isExport()) { ?>
<script>
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fmahasiswaadd.validateFields()){ew.prompt({title: ew.language.phrase("MessageAddConfirm"),icon:'question',showCancelButton:true},result=>{if(result)$("#fmahasiswaadd").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<?php if (!$Page->IsModal && !$Page->isExport()) { ?>
<script>
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fmahasiswaedit.validateFields()){ew.prompt({title: ew.language.phrase("MessageEditConfirm"),icon:'question',showCancelButton:true},result=>{if(result)$("#fmahasiswaedit").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
