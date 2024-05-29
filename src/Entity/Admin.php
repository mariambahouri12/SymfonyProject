<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $adminname = null;

    #[ORM\Column(length: 100)]
    private ?string $mail = null;

    #[ORM\Column(length: 50)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdminname(): ?string
    {
        return $this->adminname;
    }

    public function setAdminname(string $adminname): static
    {
        $this->adminname = $adminname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
