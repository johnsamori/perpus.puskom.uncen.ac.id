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
 * Entity class for "vsrtket" table
 */

#[Entity]
#[Table("vsrtket", options: ["dbId" => "DB"])]
class Vsrtket extends AbstractEntity
{
    #[Column(name: "`Nama_Mahasiswa`", options: ["name" => "Nama_Mahasiswa"], type: "string", nullable: true)]
    private ?string $NamaMahasiswa;

    #[Column(type: "string", nullable: true)]
    private ?string $Nama;

    #[Id]
    #[Column(name: "`Nomor_Surat`", options: ["name" => "Nomor_Surat"], type: "string")]
    private string $NomorSurat;

    #[Column(name: "`Tanggal_Surat`", options: ["name" => "Tanggal_Surat"], type: "date", nullable: true)]
    private ?DateTime $TanggalSurat;

    #[Column(type: "string", nullable: true)]
    private ?string $Jurusan;

    #[Column(name: "`No_Reg_Anggota`", options: ["name" => "No_Reg_Anggota"], type: "string", nullable: true)]
    private ?string $NoRegAnggota;

    #[Column(type: "string", nullable: true)]
    private ?string $Jenjang;

    #[Column(name: "`Nama_Program_Studi`", options: ["name" => "Nama_Program_Studi"], type: "string", nullable: true)]
    private ?string $NamaProgramStudi;

    #[Column(name: "`Nama_Fakultas`", options: ["name" => "Nama_Fakultas"], type: "string", nullable: true)]
    private ?string $NamaFakultas;

    public function getNamaMahasiswa(): ?string
    {
        return HtmlDecode($this->NamaMahasiswa);
    }

    public function setNamaMahasiswa(?string $value): static
    {
        $this->NamaMahasiswa = RemoveXss($value);
        return $this;
    }

    public function getNama(): ?string
    {
        return HtmlDecode($this->Nama);
    }

    public function setNama(?string $value): static
    {
        $this->Nama = RemoveXss($value);
        return $this;
    }

    public function getNomorSurat(): string
    {
        return $this->NomorSurat;
    }

    public function setNomorSurat(string $value): static
    {
        $this->NomorSurat = $value;
        return $this;
    }

    public function getTanggalSurat(): ?DateTime
    {
        return $this->TanggalSurat;
    }

    public function setTanggalSurat(?DateTime $value): static
    {
        $this->TanggalSurat = $value;
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

    public function getNoRegAnggota(): ?string
    {
        return HtmlDecode($this->NoRegAnggota);
    }

    public function setNoRegAnggota(?string $value): static
    {
        $this->NoRegAnggota = RemoveXss($value);
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

    public function getNamaProgramStudi(): ?string
    {
        return HtmlDecode($this->NamaProgramStudi);
    }

    public function setNamaProgramStudi(?string $value): static
    {
        $this->NamaProgramStudi = RemoveXss($value);
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
