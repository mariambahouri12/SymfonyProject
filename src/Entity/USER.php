<?php

namespace App\Entity;

use App\Repository\USERRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: USERRepository::class)]
#[ORM\Table(name: '`user`')]
class USER
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 100)]
    private ?string $mail = null;


    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column]
    private ?float $height = null;

    #[ORM\Column(length: 50)]
    private ?string $gender = null;

    #[ORM\Column]
    private ?int $weight = null;

    /**
     * @var Collection<int, PersonalProgram>
     */
    #[ORM\OneToMany(targetEntity: PersonalProgram::class, mappedBy: 'user')]
    private Collection $personalPrograms;

    public function __construct()
    {
        $this->personalPrograms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, PersonalProgram>
     */
    public function getPersonalPrograms(): Collection
    {
        return $this->personalPrograms;
    }

    public function addPersonalProgram(PersonalProgram $personalProgram): static
    {
        if (!$this->personalPrograms->contains($personalProgram)) {
            $this->personalPrograms->add($personalProgram);
            $personalProgram->setUser($this);
        }

        return $this;
    }

    public function removePersonalProgram(PersonalProgram $personalProgram): static
    {
        if ($this->personalPrograms->removeElement($personalProgram)) {
            // set the owning side to null (unless already changed)
            if ($personalProgram->getUser() === $this) {
                $personalProgram->setUser(null);
            }
        }

        return $this;
    }
}
