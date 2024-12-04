<?php

namespace PHPMaker2025\perpus2025baru;

use Psr\Container\ContainerInterface;

class SecurityServiceLocator implements ContainerInterface
{

    public function __construct(
        private ContainerInterface $locator,
    ) {
    }

    public function get(string $name)
    {
        return $this->locator->get($name);
    }

    public function has(string $name): bool
    {
        return $this->locator->has($name);
    }
}
