<?php

namespace PHPMaker2025\perpus2025baru;

// Page object
$ResetPassword = &$Page;
?>
<script<?= Nonce() ?>>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<script<?= Nonce() ?>>
var freset_password;
loadjs.ready(["wrapper", "head"], function() {
    let $ = jQuery;
    ew.PAGE_ID ||= "reset_password";
    window.currentPageID ||= "reset_password";
    let form = new ew.FormBuilder()
        .setId("freset_password")
        // Add field
        .addFields([
            ["email", [ew.Validators.required(ew.language.phrase("Email")), ew.Validators.email], <?= $Page->Email->IsInvalid ? "true" : "false" ?>]
        ])

        // Validate
        .setValidate(
            async function() {
                if (!this.validateRequired)
                    return true; // Ignore validation
                var fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation
        .setValidateRequired(ew.CLIENT_VALIDATE)
        .build();
    window[form.id] = form;
    window.currentForm ||= form;
    loadjs.done(form.id);
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<div class="ew-reset-pwd-box">
    <div class="card">
        <div class="card-body">
<form name="freset_password" id="freset_password" class="ew-form ew-forgot-pwd-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION") && Csrf()->isEnabled()) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" id="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" id="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<p class="login-box-msg"><?= $Language->phrase("ResetPwdTitle") ?></p>
    <div class="row gx-0">
        <input type="text" name="<?= $Page->Email->FieldVar ?>" id="<?= $Page->Email->FieldVar ?>" value="<?= HtmlEncode($Page->Email->CurrentValue) ?>" size="50" maxlength="255" placeholder="<?= HtmlEncode($Language->phrase("UserEmail", true)) ?>"<?= $Page->Email->editAttributes() ?>>
        <div class="invalid-feedback"><?= $Language->phrase("IncorrectEmail") ?></div>
    </div>
<div class="d-grid mb-3">
    <button class="btn btn-primary ew-btn disabled enable-on-init" name="btn-submit" id="btn-submit" type="submit" formaction="<?= CurrentPageUrl(false) ?>"><?= $Language->phrase("SendPwd") ?></button>
</div>
</form>
        </div>
    </div>
</div>
<?php
$Page->showPageFooter();
?>
<script<?= Nonce() ?>>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
