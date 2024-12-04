<?php

namespace PHPMaker2025\perpus2025baru;

use DebugBar\Bridge\DoctrineCollector as BaseDoctrineCollector;

/**
 * Collects Doctrine queries
 *
 * Uses the DebugStack logger to collects data about queries
 */
class DoctrineCollector extends BaseDoctrineCollector
{

    public function __construct(DebugStack $debugStack)
    {
        $this->debugStack = $debugStack;
    }
}
