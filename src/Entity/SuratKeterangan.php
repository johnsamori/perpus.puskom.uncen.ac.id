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
 * Entity class for "surat_keterangan" table
 */

#[Entity]
#[Table("surat_keterangan", options: ["dbId" => "DB"])]
class SuratKeterangan extends AbstractEntity
{
    #[Id]
    #[Column(name: "`Nomor_Surat`", options: ["name" => "Nomor_Surat"], type: "string", unique: true)]
    private string $NomorSurat;

    #[Column(name: "`Tanggal_Surat`", options: ["name" => "Tanggal_Surat"], type: "date", nullable: true)]
    private ?DateTime $TanggalSurat;

    #[Column(name: "`Nama_Mahasiswa`", options: ["name" => "Nama_Mahasiswa"], type: "string", nullable: true)]
    private ?string $NamaMahasiswa;

    #[Column(type: "string", nullable: true)]
    private ?string $Pejabat;

    #[Column(name: "`Arsip_Surat`", options: ["name" => "Arsip_Surat"], type: "string", nullable: true)]
    private ?string $ArsipSurat;

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

    public function getNamaMahasiswa(): ?string
    {
        return HtmlDecode($this->NamaMahasiswa);
    }

    public function setNamaMahasiswa(?string $value): static
    {
        $this->NamaMahasiswa = RemoveXss($value);
        return $this;
    }

    public function getPejabat(): ?string
    {
        return HtmlDecode($this->Pejabat);
    }

    public function setPejabat(?string $value): static
    {
        $this->Pejabat = RemoveXss($value);
        return $this;
    }

    public function getArsipSurat(): ?string
    {
        return HtmlDecode($this->ArsipSurat);
    }

    public function setArsipSurat(?string $value): static
    {
        $this->ArsipSurat = RemoveXss($value);
        return $this;
    }
}
