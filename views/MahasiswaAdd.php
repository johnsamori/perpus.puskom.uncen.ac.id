<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$MahasiswaAdd = &$Page;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { mahasiswa: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fmahasiswaadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fmahasiswaadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["Nim", [fields.Nim.visible && fields.Nim.required ? ew.Validators.required(fields.Nim.caption) : null], fields.Nim.isInvalid],
            ["Nama_Mahasiswa", [fields.Nama_Mahasiswa.visible && fields.Nama_Mahasiswa.required ? ew.Validators.required(fields.Nama_Mahasiswa.caption) : null], fields.Nama_Mahasiswa.isInvalid],
            ["No_Reg_Anggota", [fields.No_Reg_Anggota.visible && fields.No_Reg_Anggota.required ? ew.Validators.required(fields.No_Reg_Anggota.caption) : null], fields.No_Reg_Anggota.isInvalid],
            ["Fakultas", [fields.Fakultas.visible && fields.Fakultas.required ? ew.Validators.required(fields.Fakultas.caption) : null], fields.Fakultas.isInvalid],
            ["Jurusan", [fields.Jurusan.visible && fields.Jurusan.required ? ew.Validators.required(fields.Jurusan.caption) : null], fields.Jurusan.isInvalid],
            ["Program_Studi", [fields.Program_Studi.visible && fields.Program_Studi.required ? ew.Validators.required(fields.Program_Studi.caption) : null], fields.Program_Studi.isInvalid],
            ["Jenjang", [fields.Jenjang.visible && fields.Jenjang.required ? ew.Validators.required(fields.Jenjang.caption) : null], fields.Jenjang.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "Fakultas": <?= $Page->Fakultas->toClientList($Page) ?>,
            "Jurusan": <?= $Page->Jurusan->toClientList($Page) ?>,
            "Program_Studi": <?= $Page->Program_Studi->toClientList($Page) ?>,
        })
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
<?php // Begin of Card view by Masino Sinaga, September 10, 2023 ?>
<?php if (!$Page->IsModal) { ?>
<div class="col-md-12">
  <div class="card shadow-sm">
    <div class="card-header">
	  <h4 class="card-title"><?php echo Language()->phrase("AddCaption"); ?></h4>
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
<form name="fmahasiswaadd" id="fmahasiswaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION") && Csrf()->isEnabled()) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" id="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" id="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mahasiswa">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Nim->Visible) { // Nim ?>
    <div id="r_Nim"<?= $Page->Nim->rowAttributes() ?>>
        <label id="elh_mahasiswa_Nim" for="x_Nim" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Nim->caption() ?><?= $Page->Nim->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Nim->cellAttributes() ?>>
<span id="el_mahasiswa_Nim">
<input type="<?= $Page->Nim->getInputTextType() ?>" name="x_Nim" id="x_Nim" data-table="mahasiswa" data-field="x_Nim" value="<?= $Page->Nim->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->Nim->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nim->formatPattern()) ?>"<?= $Page->Nim->editAttributes() ?> aria-describedby="x_Nim_help">
<?= $Page->Nim->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Nim->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
    <div id="r_Nama_Mahasiswa"<?= $Page->Nama_Mahasiswa->rowAttributes() ?>>
        <label id="elh_mahasiswa_Nama_Mahasiswa" for="x_Nama_Mahasiswa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Nama_Mahasiswa->caption() ?><?= $Page->Nama_Mahasiswa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Nama_Mahasiswa->cellAttributes() ?>>
<span id="el_mahasiswa_Nama_Mahasiswa">
<input type="<?= $Page->Nama_Mahasiswa->getInputTextType() ?>" name="x_Nama_Mahasiswa" id="x_Nama_Mahasiswa" data-table="mahasiswa" data-field="x_Nama_Mahasiswa" value="<?= $Page->Nama_Mahasiswa->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Nama_Mahasiswa->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nama_Mahasiswa->formatPattern()) ?>"<?= $Page->Nama_Mahasiswa->editAttributes() ?> aria-describedby="x_Nama_Mahasiswa_help">
<?= $Page->Nama_Mahasiswa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Nama_Mahasiswa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->No_Reg_Anggota->Visible) { // No_Reg_Anggota ?>
    <div id="r_No_Reg_Anggota"<?= $Page->No_Reg_Anggota->rowAttributes() ?>>
        <label id="elh_mahasiswa_No_Reg_Anggota" for="x_No_Reg_Anggota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->No_Reg_Anggota->caption() ?><?= $Page->No_Reg_Anggota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->No_Reg_Anggota->cellAttributes() ?>>
<span id="el_mahasiswa_No_Reg_Anggota">
<input type="<?= $Page->No_Reg_Anggota->getInputTextType() ?>" name="x_No_Reg_Anggota" id="x_No_Reg_Anggota" data-table="mahasiswa" data-field="x_No_Reg_Anggota" value="<?= $Page->No_Reg_Anggota->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->No_Reg_Anggota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->No_Reg_Anggota->formatPattern()) ?>"<?= $Page->No_Reg_Anggota->editAttributes() ?> aria-describedby="x_No_Reg_Anggota_help">
<?= $Page->No_Reg_Anggota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->No_Reg_Anggota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Fakultas->Visible) { // Fakultas ?>
    <div id="r_Fakultas"<?= $Page->Fakultas->rowAttributes() ?>>
        <label id="elh_mahasiswa_Fakultas" for="x_Fakultas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Fakultas->caption() ?><?= $Page->Fakultas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Fakultas->cellAttributes() ?>>
<span id="el_mahasiswa_Fakultas">
    <select
        id="x_Fakultas"
        name="x_Fakultas"
        class="form-select ew-select<?= $Page->Fakultas->isInvalidClass() ?>"
        <?php if (!$Page->Fakultas->IsNativeSelect) { ?>
        data-select2-id="fmahasiswaadd_x_Fakultas"
        <?php } ?>
        data-table="mahasiswa"
        data-field="x_Fakultas"
        data-value-separator="<?= $Page->Fakultas->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Fakultas->getPlaceHolder()) ?>"
        <?= $Page->Fakultas->editAttributes() ?>>
        <?= $Page->Fakultas->selectOptionListHtml("x_Fakultas") ?>
    </select>
    <?= $Page->Fakultas->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Fakultas->getErrorMessage() ?></div>
<?= $Page->Fakultas->Lookup->getParamTag($Page, "p_x_Fakultas") ?>
<?php if (!$Page->Fakultas->IsNativeSelect) { ?>
<script<?= Nonce() ?>>
loadjs.ready("fmahasiswaadd", function() {
    var options = { name: "x_Fakultas", selectId: "fmahasiswaadd_x_Fakultas" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmahasiswaadd.lists.Fakultas?.lookupOptions.length) {
        options.data = { id: "x_Fakultas", form: "fmahasiswaadd" };
    } else {
        options.ajax = { id: "x_Fakultas", form: "fmahasiswaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.mahasiswa.fields.Fakultas.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Jurusan->Visible) { // Jurusan ?>
    <div id="r_Jurusan"<?= $Page->Jurusan->rowAttributes() ?>>
        <label id="elh_mahasiswa_Jurusan" for="x_Jurusan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Jurusan->caption() ?><?= $Page->Jurusan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Jurusan->cellAttributes() ?>>
<span id="el_mahasiswa_Jurusan">
    <select
        id="x_Jurusan"
        name="x_Jurusan"
        class="form-select ew-select<?= $Page->Jurusan->isInvalidClass() ?>"
        <?php if (!$Page->Jurusan->IsNativeSelect) { ?>
        data-select2-id="fmahasiswaadd_x_Jurusan"
        <?php } ?>
        data-table="mahasiswa"
        data-field="x_Jurusan"
        data-value-separator="<?= $Page->Jurusan->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Jurusan->getPlaceHolder()) ?>"
        <?= $Page->Jurusan->editAttributes() ?>>
        <?= $Page->Jurusan->selectOptionListHtml("x_Jurusan") ?>
    </select>
    <?= $Page->Jurusan->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Jurusan->getErrorMessage() ?></div>
<?= $Page->Jurusan->Lookup->getParamTag($Page, "p_x_Jurusan") ?>
<?php if (!$Page->Jurusan->IsNativeSelect) { ?>
<script<?= Nonce() ?>>
loadjs.ready("fmahasiswaadd", function() {
    var options = { name: "x_Jurusan", selectId: "fmahasiswaadd_x_Jurusan" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmahasiswaadd.lists.Jurusan?.lookupOptions.length) {
        options.data = { id: "x_Jurusan", form: "fmahasiswaadd" };
    } else {
        options.ajax = { id: "x_Jurusan", form: "fmahasiswaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.mahasiswa.fields.Jurusan.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Program_Studi->Visible) { // Program_Studi ?>
    <div id="r_Program_Studi"<?= $Page->Program_Studi->rowAttributes() ?>>
        <label id="elh_mahasiswa_Program_Studi" for="x_Program_Studi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Program_Studi->caption() ?><?= $Page->Program_Studi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Program_Studi->cellAttributes() ?>>
<span id="el_mahasiswa_Program_Studi">
    <select
        id="x_Program_Studi"
        name="x_Program_Studi"
        class="form-select ew-select<?= $Page->Program_Studi->isInvalidClass() ?>"
        <?php if (!$Page->Program_Studi->IsNativeSelect) { ?>
        data-select2-id="fmahasiswaadd_x_Program_Studi"
        <?php } ?>
        data-table="mahasiswa"
        data-field="x_Program_Studi"
        data-value-separator="<?= $Page->Program_Studi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Program_Studi->getPlaceHolder()) ?>"
        <?= $Page->Program_Studi->editAttributes() ?>>
        <?= $Page->Program_Studi->selectOptionListHtml("x_Program_Studi") ?>
    </select>
    <?= $Page->Program_Studi->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Program_Studi->getErrorMessage() ?></div>
<?= $Page->Program_Studi->Lookup->getParamTag($Page, "p_x_Program_Studi") ?>
<?php if (!$Page->Program_Studi->IsNativeSelect) { ?>
<script<?= Nonce() ?>>
loadjs.ready("fmahasiswaadd", function() {
    var options = { name: "x_Program_Studi", selectId: "fmahasiswaadd_x_Program_Studi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmahasiswaadd.lists.Program_Studi?.lookupOptions.length) {
        options.data = { id: "x_Program_Studi", form: "fmahasiswaadd" };
    } else {
        options.ajax = { id: "x_Program_Studi", form: "fmahasiswaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.mahasiswa.fields.Program_Studi.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Jenjang->Visible) { // Jenjang ?>
    <div id="r_Jenjang"<?= $Page->Jenjang->rowAttributes() ?>>
        <label id="elh_mahasiswa_Jenjang" for="x_Jenjang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Jenjang->caption() ?><?= $Page->Jenjang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Jenjang->cellAttributes() ?>>
<span id="el_mahasiswa_Jenjang">
<input type="<?= $Page->Jenjang->getInputTextType() ?>" name="x_Jenjang" id="x_Jenjang" data-table="mahasiswa" data-field="x_Jenjang" value="<?= $Page->Jenjang->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Jenjang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Jenjang->formatPattern()) ?>"<?= $Page->Jenjang->editAttributes() ?> aria-describedby="x_Jenjang_help">
<?= $Page->Jenjang->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Jenjang->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fmahasiswaadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fmahasiswaadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
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
<?php
$Page->showPageFooter();
?>
<?php if (!$Page->IsModal && !$Page->isExport()) { ?>
<script>
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fmahasiswaadd.validateFields()){ew.prompt({title: ew.language.phrase("MessageAddConfirm"),icon:'question',showCancelButton:true},result=>{if(result)$("#fmahasiswaadd").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<script<?= Nonce() ?>>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("mahasiswa");
});
</script>
<?php if (Config("MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD")) { ?>
<script>
loadjs.ready("head", function() { $("#fmahasiswaadd:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()})});
</script>
<?php } ?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
