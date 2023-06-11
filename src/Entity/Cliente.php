<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(length: 255, columnDefinition: "ENUM('P', 'PT', 'O')",)]
    // private ?string $tipo = null;

    #[ORM\ManyToMany(targetEntity: Expediente::class, mappedBy: 'clientes')]
    private Collection $expedientes;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;

    #[ORM\ManyToOne(inversedBy: 'clientes')]
    private ?TipoCC $tipoCC = null;

    public function __construct()
    {
        $this->expedientes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $expediente->addCliente($this);
        }

        return $this;
    }

    public function removeExpediente(Expediente $expediente): self
    {
        if ($this->expedientes->removeElement($expediente)) {
            $expediente->removeCliente($this);
        }

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    public function getTipoCC(): ?TipoCC
    {
        return $this->tipoCC;
    }

    public function setTipoCC(?TipoCC $tipoCC): self
    {
        $this->tipoCC = $tipoCC;

        return $this;
    }

    public function __toString()
    {
        return $this->getPersona()->getNombre();
    }

}
