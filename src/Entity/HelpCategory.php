<?php

namespace PHPMaker2025\perpus2025baru\Entity;

use DateTime;
use DateTimeImmutable;
use DateInterval;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\DBAL\Types\Types;
use PHPMaker2025\perpus2025baru\AdvancedUserInterface;
use PHPMaker2025\perpus2025baru\AbstractEntity;
use PHPMaker2025\perpus2025baru\AdvancedSecurity;
use PHPMaker2025\perpus2025baru\UserProfile;
use PHPMaker2025\perpus2025baru\UserRepository;
use function PHPMaker2025\perpus2025baru\Config;
use function PHPMaker2025\perpus2025baru\EntityManager;
use function PHPMaker2025\perpus2025baru\RemoveXss;
use function PHPMaker2025\perpus2025baru\HtmlDecode;
use function PHPMaker2025\perpus2025baru\HashPassword;
use function PHPMaker2025\perpus2025baru\Security;

/**
 * Entity class for "help_categories" table
 */

#[Entity]
#[Table("help_categories", options: ["dbId" => "DB"])]
class HelpCategory extends AbstractEntity
{
    #[Id]
    #[Column(name: "`Category_ID`", options: ["name" => "Category_ID"], type: "integer", unique: true)]
    #[GeneratedValue]
    private int $CategoryId;

    #[Column(type: "string")]
    private string $Language;

    #[Column(name: "`Category_Description`", options: ["name" => "Category_Description"], type: "string")]
    private string $CategoryDescription;

    public function getCategoryId(): int
    {
        return $this->CategoryId;
    }

    public function setCategoryId(int $value): static
    {
        $this->CategoryId = $value;
        return $this;
    }

    public function getLanguage(): string
    {
        return HtmlDecode($this->Language);
    }

    public function setLanguage(string $value): static
    {
        $this->Language = RemoveXss($value);
        return $this;
    }

    public function getCategoryDescription(): string
    {
        return HtmlDecode($this->CategoryDescription);
    }

    public function setCategoryDescription(string $value): static
    {
        $this->CategoryDescription = RemoveXss($value);
        return $this;
    }
}
