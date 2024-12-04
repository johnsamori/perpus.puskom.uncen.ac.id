<?php

namespace PHPMaker2025\perpus2025baru;

/**
 * Crosstab column class
 */
class CrosstabColumn
{

    public function __construct(
        public string $Caption,
        public mixed $Value,
        public bool $Visible = true,
    ) {
    }
}
