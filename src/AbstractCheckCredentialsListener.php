<?php

namespace PHPMaker2025\perpus2025baru;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

/**
 * Abstract check credentials listener
 */
abstract class AbstractCheckCredentialsListener implements EventSubscriberInterface
{
    public static int $priority = 8; // Earlier than built-in CheckCredentialsListener
    abstract public function authenticate(string $username, string $password): bool;

    public function checkPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        if ($passport->hasBadge(PasswordCredentials::class)) {
            $user = $passport->getUser();
            if (!$user instanceof PasswordAuthenticatedUserInterface) {
                throw new \LogicException(sprintf('Class "%s" must implement "%s" for using password-based authentication.', get_debug_type($user), PasswordAuthenticatedUserInterface::class));
            }

            /** @var PasswordCredentials $badge */
            $badge = $passport->getBadge(PasswordCredentials::class);
            if ($badge->isResolved()) {
                return;
            }
            $presentedPassword = $badge->getPassword();
            if ('' === $presentedPassword) {
                throw new BadCredentialsException('The presented password cannot be empty.');
            }
            if (!$this->authenticate($user->getUserIdentifier(), $presentedPassword)) {
                throw new BadCredentialsException('The presented password is invalid.');
            }
            $badge->markResolved();
            if (!$passport->hasBadge(PasswordUpgradeBadge::class)) {
                $passport->addBadge(new PasswordUpgradeBadge($presentedPassword));
            }
            return;
        }
    }
    public static function getSubscribedEvents(): array
    {
        return [CheckPassportEvent::class => ['checkPassport', static::$priority]];
    }
}
