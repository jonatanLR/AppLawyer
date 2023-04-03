<?php

namespace App\Entity;

use App\Repository\TipoCCRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoCCRepository::class)]
class TipoCC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'tipoCC', targetEntity: Cliente::class)]
    private Collection $clientes;

    #[ORM\OneToMany(mappedBy: 'tipoCC', targetEntity: Contrario::class)]
    private Collection $contrarios;

    public function __construct()
    {
        $this->clientes = new ArrayCollection();
        $this->contrarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Cliente>
     */
    public function getClientes(): Collection
    {
        return $this->clientes;
    }

    public function addCliente(Cliente $cliente): self
    {
        if (!$this->clientes->contains($cliente)) {
            $this->clientes->add($cliente);
            $cliente->setTipoCC($this);
        }

        return $this;
    }

    public function removeCliente(Cliente $cliente): self
    {
        if ($this->clientes->removeElement($cliente)) {
            // set the owning side to null (unless already changed)
            if ($cliente->getTipoCC() === $this) {
                $cliente->setTipoCC(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contrario>
     */
    public function getContrarios(): Collection
    {
        return $this->contrarios;
    }

    public function addContrario(Contrario $contrario): self
    {
        if (!$this->contrarios->contains($contrario)) {
            $this->contrarios->add($contrario);
            $contrario->setTipoCC($this);
        }

        return $this;
    }

    public function removeContrario(Contrario $contrario): self
    {
        if ($this->contrarios->removeElement($contrario)) {
            // set the owning side to null (unless already changed)
            if ($contrario->getTipoCC() === $this) {
                $contrario->setTipoCC(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
