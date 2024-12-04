<?php

namespace PHPMaker2025\perpus2025baru;

/**
 * Captcha interface
 */
interface CaptchaInterface
{

    public function getHtml(): string;

    public function getConfirmHtml(): string;

    public function validate(): bool;

    public function getScript(): string;
}
