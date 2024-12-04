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
 * Entity class for "mahasiswa" table
 */

#[Entity]
#[Table("mahasiswa", options: ["dbId" => "DB"])]
class Mahasiswa extends AbstractEntity
{
    #[Id]
    #[Column(type: "string", unique: true)]
    private string $Nim;

    #[Column(name: "`Nama_Mahasiswa`", options: ["name" => "Nama_Mahasiswa"], type: "string", nullable: true)]
    private ?string $NamaMahasiswa;

    #[Column(name: "`No_Reg_Anggota`", options: ["name" => "No_Reg_Anggota"], type: "string", nullable: true)]
    private ?string $NoRegAnggota;

    #[Column(type: "string", nullable: true)]
    private ?string $Fakultas;

    #[Column(type: "string", nullable: true)]
    private ?string $Jurusan;

    #[Column(name: "`Program_Studi`", options: ["name" => "Program_Studi"], type: "string", nullable: true)]
    private ?string $ProgramStudi;

    #[Column(type: "string", nullable: true)]
    private ?string $Jenjang;

    public function getNim(): string
    {
        return $this->Nim;
    }

    public function setNim(string $value): static
    {
        $this->Nim = $value;
        return $this;
    }

    public function getNamaMahasiswa(): ?string
    {
        return HtmlDecode($this->NamaMahasiswa);
    }

    public function setNamaMahasiswa(?string $value): static
    {
        $this->NamaMahasiswa = RemoveXss($value);
        return $this;
    }

    public function getNoRegAnggota(): ?string
    {
        return HtmlDecode($this->NoRegAnggota);
    }

    public function setNoRegAnggota(?string $value): static
    {
        $this->NoRegAnggota = RemoveXss($value);
        return $this;
    }

    public function getFakultas(): ?string
    {
        return HtmlDecode($this->Fakultas);
    }

    public function setFakultas(?string $value): static
    {
        $this->Fakultas = RemoveXss($value);
        return $this;
    }

    public function getJurusan(): ?string
    {
        return HtmlDecode($this->Jurusan);
    }

    public function setJurusan(?string $value): static
    {
        $this->Jurusan = RemoveXss($value);
        return $this;
    }

    public function getProgramStudi(): ?string
    {
        return HtmlDecode($this->ProgramStudi);
    }

    public function setProgramStudi(?string $value): static
    {
        $this->ProgramStudi = RemoveXss($value);
        return $this;
    }

    public function getJenjang(): ?string
    {
        return HtmlDecode($this->Jenjang);
    }

    public function setJenjang(?string $value): static
    {
        $this->Jenjang = RemoveXss($value);
        return $this;
    }
}
