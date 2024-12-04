<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$MahasiswaDelete = &$Page;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { mahasiswa: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fmahasiswadelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fmahasiswadelete")
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
<form name="fmahasiswadelete" id="fmahasiswadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION") && Csrf()->isEnabled()) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" id="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" id="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mahasiswa">
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
<?php if ($Page->Nim->Visible) { // Nim ?>
        <th class="<?= $Page->Nim->headerCellClass() ?>"><span id="elh_mahasiswa_Nim" class="mahasiswa_Nim"><?= $Page->Nim->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
        <th class="<?= $Page->Nama_Mahasiswa->headerCellClass() ?>"><span id="elh_mahasiswa_Nama_Mahasiswa" class="mahasiswa_Nama_Mahasiswa"><?= $Page->Nama_Mahasiswa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->No_Reg_Anggota->Visible) { // No_Reg_Anggota ?>
        <th class="<?= $Page->No_Reg_Anggota->headerCellClass() ?>"><span id="elh_mahasiswa_No_Reg_Anggota" class="mahasiswa_No_Reg_Anggota"><?= $Page->No_Reg_Anggota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Fakultas->Visible) { // Fakultas ?>
        <th class="<?= $Page->Fakultas->headerCellClass() ?>"><span id="elh_mahasiswa_Fakultas" class="mahasiswa_Fakultas"><?= $Page->Fakultas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Jurusan->Visible) { // Jurusan ?>
        <th class="<?= $Page->Jurusan->headerCellClass() ?>"><span id="elh_mahasiswa_Jurusan" class="mahasiswa_Jurusan"><?= $Page->Jurusan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Program_Studi->Visible) { // Program_Studi ?>
        <th class="<?= $Page->Program_Studi->headerCellClass() ?>"><span id="elh_mahasiswa_Program_Studi" class="mahasiswa_Program_Studi"><?= $Page->Program_Studi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Jenjang->Visible) { // Jenjang ?>
        <th class="<?= $Page->Jenjang->headerCellClass() ?>"><span id="elh_mahasiswa_Jenjang" class="mahasiswa_Jenjang"><?= $Page->Jenjang->caption() ?></span></th>
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
<?php if ($Page->Nim->Visible) { // Nim ?>
        <td<?= $Page->Nim->cellAttributes() ?>>
<span id="">
<span<?= $Page->Nim->viewAttributes() ?>>
<?= $Page->Nim->getViewValue() ?></span>
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
<?php if ($Page->No_Reg_Anggota->Visible) { // No_Reg_Anggota ?>
        <td<?= $Page->No_Reg_Anggota->cellAttributes() ?>>
<span id="">
<span<?= $Page->No_Reg_Anggota->viewAttributes() ?>>
<?= $Page->No_Reg_Anggota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Fakultas->Visible) { // Fakultas ?>
        <td<?= $Page->Fakultas->cellAttributes() ?>>
<span id="">
<span<?= $Page->Fakultas->viewAttributes() ?>>
<?= $Page->Fakultas->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Jurusan->Visible) { // Jurusan ?>
        <td<?= $Page->Jurusan->cellAttributes() ?>>
<span id="">
<span<?= $Page->Jurusan->viewAttributes() ?>>
<?= $Page->Jurusan->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Program_Studi->Visible) { // Program_Studi ?>
        <td<?= $Page->Program_Studi->cellAttributes() ?>>
<span id="">
<span<?= $Page->Program_Studi->viewAttributes() ?>>
<?= $Page->Program_Studi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Jenjang->Visible) { // Jenjang ?>
        <td<?= $Page->Jenjang->cellAttributes() ?>>
<span id="">
<span<?= $Page->Jenjang->viewAttributes() ?>>
<?= $Page->Jenjang->getViewValue() ?></span>
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
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fmahasiswadelete.validateFields()){ew.prompt({title: ew.language.phrase("MessageDeleteConfirm"),icon:'question',showCancelButton:true},result=>{if(result) $("#fmahasiswadelete").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
