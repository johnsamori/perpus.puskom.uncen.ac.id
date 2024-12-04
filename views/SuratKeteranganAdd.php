<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$SuratKeteranganAdd = &$Page;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { surat_keterangan: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fsurat_keteranganadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsurat_keteranganadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["Nomor_Surat", [fields.Nomor_Surat.visible && fields.Nomor_Surat.required ? ew.Validators.required(fields.Nomor_Surat.caption) : null], fields.Nomor_Surat.isInvalid],
            ["Tanggal_Surat", [fields.Tanggal_Surat.visible && fields.Tanggal_Surat.required ? ew.Validators.required(fields.Tanggal_Surat.caption) : null, ew.Validators.datetime(fields.Tanggal_Surat.clientFormatPattern)], fields.Tanggal_Surat.isInvalid],
            ["Nama_Mahasiswa", [fields.Nama_Mahasiswa.visible && fields.Nama_Mahasiswa.required ? ew.Validators.required(fields.Nama_Mahasiswa.caption) : null], fields.Nama_Mahasiswa.isInvalid],
            ["Pejabat", [fields.Pejabat.visible && fields.Pejabat.required ? ew.Validators.required(fields.Pejabat.caption) : null], fields.Pejabat.isInvalid],
            ["Arsip_Surat", [fields.Arsip_Surat.visible && fields.Arsip_Surat.required ? ew.Validators.fileRequired(fields.Arsip_Surat.caption) : null], fields.Arsip_Surat.isInvalid]
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
            "Nama_Mahasiswa": <?= $Page->Nama_Mahasiswa->toClientList($Page) ?>,
            "Pejabat": <?= $Page->Pejabat->toClientList($Page) ?>,
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
<form name="fsurat_keteranganadd" id="fsurat_keteranganadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION") && Csrf()->isEnabled()) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" id="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" id="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="surat_keterangan">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Nomor_Surat->Visible) { // Nomor_Surat ?>
    <div id="r_Nomor_Surat"<?= $Page->Nomor_Surat->rowAttributes() ?>>
        <label id="elh_surat_keterangan_Nomor_Surat" for="x_Nomor_Surat" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Nomor_Surat->caption() ?><?= $Page->Nomor_Surat->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Nomor_Surat->cellAttributes() ?>>
<span id="el_surat_keterangan_Nomor_Surat">
<input type="<?= $Page->Nomor_Surat->getInputTextType() ?>" name="x_Nomor_Surat" id="x_Nomor_Surat" data-table="surat_keterangan" data-field="x_Nomor_Surat" value="<?= $Page->Nomor_Surat->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->Nomor_Surat->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nomor_Surat->formatPattern()) ?>"<?= $Page->Nomor_Surat->editAttributes() ?> aria-describedby="x_Nomor_Surat_help">
<?= $Page->Nomor_Surat->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Nomor_Surat->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Tanggal_Surat->Visible) { // Tanggal_Surat ?>
    <div id="r_Tanggal_Surat"<?= $Page->Tanggal_Surat->rowAttributes() ?>>
        <label id="elh_surat_keterangan_Tanggal_Surat" for="x_Tanggal_Surat" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Tanggal_Surat->caption() ?><?= $Page->Tanggal_Surat->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Tanggal_Surat->cellAttributes() ?>>
<span id="el_surat_keterangan_Tanggal_Surat">
<input type="<?= $Page->Tanggal_Surat->getInputTextType() ?>" name="x_Tanggal_Surat" id="x_Tanggal_Surat" data-table="surat_keterangan" data-field="x_Tanggal_Surat" value="<?= $Page->Tanggal_Surat->EditValue ?>" placeholder="<?= HtmlEncode($Page->Tanggal_Surat->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Tanggal_Surat->formatPattern()) ?>"<?= $Page->Tanggal_Surat->editAttributes() ?> aria-describedby="x_Tanggal_Surat_help">
<?= $Page->Tanggal_Surat->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Tanggal_Surat->getErrorMessage() ?></div>
<?php if (!$Page->Tanggal_Surat->ReadOnly && !$Page->Tanggal_Surat->Disabled && !isset($Page->Tanggal_Surat->EditAttrs["readonly"]) && !isset($Page->Tanggal_Surat->EditAttrs["disabled"])) { ?>
<script<?= Nonce() ?>>
loadjs.ready(["fsurat_keteranganadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker(
        "fsurat_keteranganadd",
        "x_Tanggal_Surat",
        ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options),
        {"inputGroup":true}
    );
});
</script>
<?php } ?>
<script<?= Nonce() ?>>
loadjs.ready(['fsurat_keteranganadd', 'jqueryinputmask'], function() {
	options = {
		'jitMasking': false,
		'removeMaskOnSubmit': true
	};
	ew.createjQueryInputMask("fsurat_keteranganadd", "x_Tanggal_Surat", jQuery.extend(true, "", options));
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Nama_Mahasiswa->Visible) { // Nama_Mahasiswa ?>
    <div id="r_Nama_Mahasiswa"<?= $Page->Nama_Mahasiswa->rowAttributes() ?>>
        <label id="elh_surat_keterangan_Nama_Mahasiswa" for="x_Nama_Mahasiswa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Nama_Mahasiswa->caption() ?><?= $Page->Nama_Mahasiswa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Nama_Mahasiswa->cellAttributes() ?>>
<span id="el_surat_keterangan_Nama_Mahasiswa">
    <select
        id="x_Nama_Mahasiswa"
        name="x_Nama_Mahasiswa"
        class="form-select ew-select<?= $Page->Nama_Mahasiswa->isInvalidClass() ?>"
        <?php if (!$Page->Nama_Mahasiswa->IsNativeSelect) { ?>
        data-select2-id="fsurat_keteranganadd_x_Nama_Mahasiswa"
        <?php } ?>
        data-table="surat_keterangan"
        data-field="x_Nama_Mahasiswa"
        data-value-separator="<?= $Page->Nama_Mahasiswa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Nama_Mahasiswa->getPlaceHolder()) ?>"
        <?= $Page->Nama_Mahasiswa->editAttributes() ?>>
        <?= $Page->Nama_Mahasiswa->selectOptionListHtml("x_Nama_Mahasiswa") ?>
    </select>
    <?= $Page->Nama_Mahasiswa->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Nama_Mahasiswa->getErrorMessage() ?></div>
<?= $Page->Nama_Mahasiswa->Lookup->getParamTag($Page, "p_x_Nama_Mahasiswa") ?>
<?php if (!$Page->Nama_Mahasiswa->IsNativeSelect) { ?>
<script<?= Nonce() ?>>
loadjs.ready("fsurat_keteranganadd", function() {
    var options = { name: "x_Nama_Mahasiswa", selectId: "fsurat_keteranganadd_x_Nama_Mahasiswa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsurat_keteranganadd.lists.Nama_Mahasiswa?.lookupOptions.length) {
        options.data = { id: "x_Nama_Mahasiswa", form: "fsurat_keteranganadd" };
    } else {
        options.ajax = { id: "x_Nama_Mahasiswa", form: "fsurat_keteranganadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.surat_keterangan.fields.Nama_Mahasiswa.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Pejabat->Visible) { // Pejabat ?>
    <div id="r_Pejabat"<?= $Page->Pejabat->rowAttributes() ?>>
        <label id="elh_surat_keterangan_Pejabat" for="x_Pejabat" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Pejabat->caption() ?><?= $Page->Pejabat->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Pejabat->cellAttributes() ?>>
<span id="el_surat_keterangan_Pejabat">
    <select
        id="x_Pejabat"
        name="x_Pejabat"
        class="form-select ew-select<?= $Page->Pejabat->isInvalidClass() ?>"
        <?php if (!$Page->Pejabat->IsNativeSelect) { ?>
        data-select2-id="fsurat_keteranganadd_x_Pejabat"
        <?php } ?>
        data-table="surat_keterangan"
        data-field="x_Pejabat"
        data-value-separator="<?= $Page->Pejabat->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Pejabat->getPlaceHolder()) ?>"
        <?= $Page->Pejabat->editAttributes() ?>>
        <?= $Page->Pejabat->selectOptionListHtml("x_Pejabat") ?>
    </select>
    <?= $Page->Pejabat->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Pejabat->getErrorMessage() ?></div>
<?= $Page->Pejabat->Lookup->getParamTag($Page, "p_x_Pejabat") ?>
<?php if (!$Page->Pejabat->IsNativeSelect) { ?>
<script<?= Nonce() ?>>
loadjs.ready("fsurat_keteranganadd", function() {
    var options = { name: "x_Pejabat", selectId: "fsurat_keteranganadd_x_Pejabat" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsurat_keteranganadd.lists.Pejabat?.lookupOptions.length) {
        options.data = { id: "x_Pejabat", form: "fsurat_keteranganadd" };
    } else {
        options.ajax = { id: "x_Pejabat", form: "fsurat_keteranganadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.surat_keterangan.fields.Pejabat.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Arsip_Surat->Visible) { // Arsip_Surat ?>
    <div id="r_Arsip_Surat"<?= $Page->Arsip_Surat->rowAttributes() ?>>
        <label id="elh_surat_keterangan_Arsip_Surat" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Arsip_Surat->caption() ?><?= $Page->Arsip_Surat->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Arsip_Surat->cellAttributes() ?>>
<span id="el_surat_keterangan_Arsip_Surat">
<div id="fd_x_Arsip_Surat" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_Arsip_Surat"
        name="x_Arsip_Surat"
        class="form-control ew-file-input"
        title="<?= $Page->Arsip_Surat->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="surat_keterangan"
        data-field="x_Arsip_Surat"
        data-size="255"
        data-accept-file-types="<?= $Page->Arsip_Surat->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->Arsip_Surat->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->Arsip_Surat->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_Arsip_Surat_help"
        <?= ($Page->Arsip_Surat->ReadOnly || $Page->Arsip_Surat->Disabled) ? " disabled" : "" ?>
        <?= $Page->Arsip_Surat->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->Arsip_Surat->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Arsip_Surat->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_Arsip_Surat" id= "fn_x_Arsip_Surat" value="<?= $Page->Arsip_Surat->Upload->FileName ?>">
<input type="hidden" name="fa_x_Arsip_Surat" id= "fa_x_Arsip_Surat" value="0">
<table id="ft_x_Arsip_Surat" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsurat_keteranganadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsurat_keteranganadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){if(fsurat_keteranganadd.validateFields()){ew.prompt({title: ew.language.phrase("MessageAddConfirm"),icon:'question',showCancelButton:true},result=>{if(result)$("#fsurat_keteranganadd").submit();});return false;} else { ew.prompt({title: ew.language.phrase("MessageInvalidForm"), icon: 'warning', showCancelButton:false}); }});});
</script>
<?php } ?>
<script<?= Nonce() ?>>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("surat_keterangan");
});
</script>
<?php if (Config("MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD")) { ?>
<script>
loadjs.ready("head", function() { $("#fsurat_keteranganadd:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()})});
</script>
<?php } ?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
