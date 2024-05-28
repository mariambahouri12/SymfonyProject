<?php

namespace App\Entity;

use App\Repository\PersonalProgramRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalProgramRepository::class)]
class PersonalProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'personalPrograms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?USER $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExerciceSurPoidsFemme $exercise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?USER
    {
        return $this->user;
    }

    public function setUser(?USER $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getExercise(): ?ExerciceSurPoidsFemme
    {
        return $this->exercise;
    }

    public function setExercise(?ExerciceSurPoidsFemme $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }
}
