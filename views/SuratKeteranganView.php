<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$SuratKeteranganView = &$Page;
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
<form name="fsurat_keteranganview" id="fsurat_keteranganview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { surat_keterangan: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fsurat_keteranganview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsurat_keteranganview")
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
<input type="hidden" name="t" value="surat_keterangan">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Nomor_Surat->Visible) { // Nomor_Surat ?>
    <tr id="r_Nomor_Surat"<?= $Page->Nomor_Surat->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_surat_keterangan_Nomor_Surat"><?= $Page->Nomor_Surat->caption() ?></span></td>
        <td data-name="Nomor_Surat"<?= $Page->Nomor_Surat->cellAttributes() ?>>
<span id="el_surat_keterangan_Nomor_Surat">
<span<?= $Page->Nomor_Surat->viewAttributes() ?>>
<?php if (!IsEmpty($Page->Nomor_Surat->getViewValue()) && $Page->Nomor_Surat->linkAttributes() != "") { ?>
<a<?= $Page->Nomor_Surat->linkAttributes() ?>><?= $Page->Nomor_Surat->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Nomor_Surat->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Tanggal_Surat->Visible) { // Tanggal_Surat ?>
    <tr id="r_Tanggal_Surat"<?= $Page->Tanggal_Surat->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_surat_keterangan_Tanggal_Surat"><?= $Page->Tanggal_Surat->caption() ?></span></td>
        <td data-name="Tanggal_Surat"<?= $Page->Tanggal_Surat->cellAttributes() ?>>
<span id="el_surat_keterangan_Tanggal_Surat">
<span<?= $Page->Tanggal_Surat->viewAttributes() ?>>
<?= $Page->Tanggal_Surat->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
    <tr id="r_Nama_Mahasiswa"<?= $Page->Nama_Mahasiswa->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_surat_keterangan_Nama_Mahasiswa"><?= $Page->Nama_Mahasiswa->caption() ?></span></td>
        <td data-name="Nama_Mahasiswa"<?= $Page->Nama_Mahasiswa->cellAttributes() ?>>
<span id="el_surat_keterangan_Nama_Mahasiswa">
<span<?= $Page->Nama_Mahasiswa->viewAttributes() ?>>
<?= $Page->Nama_Mahasiswa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Pejabat->Visible) { // Pejabat ?>
    <tr id="r_Pejabat"<?= $Page->Pejabat->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_surat_keterangan_Pejabat"><?= $Page->Pejabat->caption() ?></span></td>
        <td data-name="Pejabat"<?= $Page->Pejabat->cellAttributes() ?>>
<span id="el_surat_keterangan_Pejabat">
<span<?= $Page->Pejabat->viewAttributes() ?>>
<?= $Page->Pejabat->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Arsip_Surat->Visible) { // Arsip_Surat ?>
    <tr id="r_Arsip_Surat"<?= $Page->Arsip_Surat->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_surat_keterangan_Arsip_Surat"><?= $Page->Arsip_Surat->caption() ?></span></td>
        <td data-name="Arsip_Surat"<?= $Page->Arsip_Surat->cellAttributes() ?>>
<span id="el_surat_keterangan_Arsip_Surat">
<span<?= $Page->Arsip_Surat->viewAttributes() ?>>
<?= GetFileViewTag($Page->Arsip_Surat, $Page->Arsip_Surat->getViewValue(), false) ?>
</span>
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
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fsurat_keteranganadd.validateFields()){ew.prompt({title: ew.language.phrase("MessageAddConfirm"),icon:'question',showCancelButton:true},result=>{if(result)$("#fsurat_keteranganadd").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<?php if (!$Page->IsModal && !$Page->isExport()) { ?>
<script>
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fsurat_keteranganedit.validateFields()){ew.prompt({title: ew.language.phrase("MessageEditConfirm"),icon:'question',showCancelButton:true},result=>{if(result)$("#fsurat_keteranganedit").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
