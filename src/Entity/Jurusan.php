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
 * Entity class for "jurusan" table
 */

#[Entity]
#[Table("jurusan", options: ["dbId" => "DB"])]
class Jurusan extends AbstractEntity
{
    #[Id]
    #[Column(name: "id_jurusan", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $IdJurusan;

    #[Column(name: "`Jurusan`", options: ["name" => "Jurusan"], type: "string", nullable: true)]
    private ?string $_Jurusan;

    public function getIdJurusan(): int
    {
        return $this->IdJurusan;
    }

    public function setIdJurusan(int $value): static
    {
        $this->IdJurusan = $value;
        return $this;
    }

    public function get_Jurusan(): ?string
    {
        return HtmlDecode($this->_Jurusan);
    }

    public function set_Jurusan(?string $value): static
    {
        $this->_Jurusan = RemoveXss($value);
        return $this;
    }
}
