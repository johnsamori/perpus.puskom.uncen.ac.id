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
 * Entity class for "program_studi" table
 */

#[Entity]
#[Table("program_studi", options: ["dbId" => "DB"])]
class ProgramStudi extends AbstractEntity
{
    #[Id]
    #[Column(name: "id_programstudi", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $IdProgramstudi;

    #[Column(name: "`Nama_Program_Studi`", options: ["name" => "Nama_Program_Studi"], type: "string", nullable: true)]
    private ?string $NamaProgramStudi;

    public function getIdProgramstudi(): int
    {
        return $this->IdProgramstudi;
    }

    public function setIdProgramstudi(int $value): static
    {
        $this->IdProgramstudi = $value;
        return $this;
    }

    public function getNamaProgramStudi(): ?string
    {
        return HtmlDecode($this->NamaProgramStudi);
    }

    public function setNamaProgramStudi(?string $value): static
    {
        $this->NamaProgramStudi = RemoveXss($value);
        return $this;
    }
}
