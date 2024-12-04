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
 * Entity class for "fakultas" table
 */

#[Entity]
#[Table("fakultas", options: ["dbId" => "DB"])]
class Fakulta extends AbstractEntity
{
    #[Id]
    #[Column(name: "id_fakulttas", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $IdFakulttas;

    #[Column(name: "`Nama_Fakultas`", options: ["name" => "Nama_Fakultas"], type: "string", nullable: true)]
    private ?string $NamaFakultas;

    public function getIdFakulttas(): int
    {
        return $this->IdFakulttas;
    }

    public function setIdFakulttas(int $value): static
    {
        $this->IdFakulttas = $value;
        return $this;
    }

    public function getNamaFakultas(): ?string
    {
        return HtmlDecode($this->NamaFakultas);
    }

    public function setNamaFakultas(?string $value): static
    {
        $this->NamaFakultas = RemoveXss($value);
        return $this;
    }
}
