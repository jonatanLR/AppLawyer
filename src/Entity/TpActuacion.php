<?php

namespace App\Entity;

use App\Repository\TpActuacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TpActuacionRepository::class)]
class TpActuacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'tpActuacion', targetEntity: Actuacion::class)]
    private Collection $actuaciones;

    public function __construct()
    {
        $this->actuaciones = new ArrayCollection();
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, Actuacion>
     */
    public function getActuaciones(): Collection
    {
        return $this->actuaciones;
    }

    public function addActuacione(Actuacion $actuacione): self
    {
        if (!$this->actuaciones->contains($actuacione)) {
            $this->actuaciones->add($actuacione);
            $actuacione->setTpActuacion($this);
        }

        return $this;
    }

    public function removeActuacione(Actuacion $actuacione): self
    {
        if ($this->actuaciones->removeElement($actuacione)) {
            // set the owning side to null (unless already changed)
            if ($actuacione->getTpActuacion() === $this) {
                $actuacione->setTpActuacion(null);
            }
        }

        return $this;
    }
}
