<?php

namespace PHPMaker2025\perpus2025baru;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Page Loading Event
 */
class PageLoadingEvent extends GenericEvent
{
    public const NAME = "page.loading";

    public function getPage(): mixed
    {
        return $this->subject;
    }
}
