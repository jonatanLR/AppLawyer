<?php

namespace App\Entity;

use App\Repository\JuezRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JuezRepository::class)]
class Juez
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $num_profesion = null;

    #[ORM\OneToOne(mappedBy: 'juez', cascade: ['persist', 'remove'])]
    private ?Persona $persona = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumProfesion(): ?string
    {
        return $this->num_profesion;
    }

    public function setNumProfesion(string $num_profesion): self
    {
        $this->num_profesion = $num_profesion;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(Persona $persona): self
    {
        // set the owning side of the relation if necessary
        if ($persona->getJuez() !== $this) {
            $persona->setJuez($this);
        }

        $this->persona = $persona;

        return $this;
    }
}
