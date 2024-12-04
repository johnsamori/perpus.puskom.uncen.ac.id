<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$SuratKeteranganDelete = &$Page;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { surat_keterangan: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fsurat_keterangandelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsurat_keterangandelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fsurat_keterangandelete" id="fsurat_keterangandelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION") && Csrf()->isEnabled()) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" id="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" id="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="surat_keterangan">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->Nomor_Surat->Visible) { // Nomor_Surat ?>
        <th class="<?= $Page->Nomor_Surat->headerCellClass() ?>"><span id="elh_surat_keterangan_Nomor_Surat" class="surat_keterangan_Nomor_Surat"><?= $Page->Nomor_Surat->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Tanggal_Surat->Visible) { // Tanggal_Surat ?>
        <th class="<?= $Page->Tanggal_Surat->headerCellClass() ?>"><span id="elh_surat_keterangan_Tanggal_Surat" class="surat_keterangan_Tanggal_Surat"><?= $Page->Tanggal_Surat->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
        <th class="<?= $Page->Nama_Mahasiswa->headerCellClass() ?>"><span id="elh_surat_keterangan_Nama_Mahasiswa" class="surat_keterangan_Nama_Mahasiswa"><?= $Page->Nama_Mahasiswa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Pejabat->Visible) { // Pejabat ?>
        <th class="<?= $Page->Pejabat->headerCellClass() ?>"><span id="elh_surat_keterangan_Pejabat" class="surat_keterangan_Pejabat"><?= $Page->Pejabat->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Arsip_Surat->Visible) { // Arsip_Surat ?>
        <th class="<?= $Page->Arsip_Surat->headerCellClass() ?>"><span id="elh_surat_keterangan_Arsip_Surat" class="surat_keterangan_Arsip_Surat"><?= $Page->Arsip_Surat->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->Nomor_Surat->Visible) { // Nomor_Surat ?>
        <td<?= $Page->Nomor_Surat->cellAttributes() ?>>
<span id="">
<span<?= $Page->Nomor_Surat->viewAttributes() ?>>
<?php if (!IsEmpty($Page->Nomor_Surat->getViewValue()) && $Page->Nomor_Surat->linkAttributes() != "") { ?>
<a<?= $Page->Nomor_Surat->linkAttributes() ?>><?= $Page->Nomor_Surat->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Nomor_Surat->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->Tanggal_Surat->Visible) { // Tanggal_Surat ?>
        <td<?= $Page->Tanggal_Surat->cellAttributes() ?>>
<span id="">
<span<?= $Page->Tanggal_Surat->viewAttributes() ?>>
<?= $Page->Tanggal_Surat->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
        <td<?= $Page->Nama_Mahasiswa->cellAttributes() ?>>
<span id="">
<span<?= $Page->Nama_Mahasiswa->viewAttributes() ?>>
<?= $Page->Nama_Mahasiswa->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Pejabat->Visible) { // Pejabat ?>
        <td<?= $Page->Pejabat->cellAttributes() ?>>
<span id="">
<span<?= $Page->Pejabat->viewAttributes() ?>>
<?= $Page->Pejabat->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Arsip_Surat->Visible) { // Arsip_Surat ?>
        <td<?= $Page->Arsip_Surat->cellAttributes() ?>>
<span id="">
<span<?= $Page->Arsip_Surat->viewAttributes() ?>>
<?= GetFileViewTag($Page->Arsip_Surat, $Page->Arsip_Surat->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Result?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
?>
<?php if (!$Page->IsModal && !$Page->isExport()) { ?>
<script>
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fsurat_keterangandelete.validateFields()){ew.prompt({title: ew.language.phrase("MessageDeleteConfirm"),icon:'question',showCancelButton:true},result=>{if(result) $("#fsurat_keterangandelete").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
