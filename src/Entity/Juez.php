<?php

namespace App\Entity;

use App\Repository\JuezRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: JuezRepository::class)]
#[UniqueEntity('num_profesion','Este numero ya esta en uso')]
class Juez
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name:'numprofesion',length: 8, unique:true,)]
    #[Assert\NotBlank]
    private ?string $num_profesion = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Persona $persona = null;

    #[ORM\ManyToMany(targetEntity: Expediente::class, mappedBy: 'juezes')]
    private Collection $expedientes;

    public function __construct()
    {
        $this->expedientes = new ArrayCollection();
    }

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

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * @return Collection<int, Expediente>
     */
    public function getExpedientes(): Collection
    {
        return $this->expedientes;
    }

    public function addExpediente(Expediente $expediente): self
    {
        if (!$this->expedientes->contains($expediente)) {
            $this->expedientes->add($expediente);
            $expediente->addJueze($this);
        }

        return $this;
    }

    public function removeExpediente(Expediente $expediente): self
    {
        if ($this->expedientes->removeElement($expediente)) {
            $expediente->removeJueze($this);
        }

        return $this;
    }

}
