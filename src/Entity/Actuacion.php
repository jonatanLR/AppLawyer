<?php

namespace App\Entity;

use App\Repository\ActuacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActuacionRepository::class)]
class Actuacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'fecha_alta')]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $direccion = null;

    #[ORM\OneToMany(mappedBy: 'actuacion', targetEntity: Documento::class)]
    private Collection $documentos;

    #[ORM\OneToMany(mappedBy: 'actuacion', targetEntity: Nota::class, orphanRemoval: true)]
    private Collection $notas;

    #[ORM\ManyToOne(inversedBy: 'actuaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TpActuacion $tpActuacion = null;

    public function __construct()
    {
        $this->documentos = new ArrayCollection();
        $this->notas = new ArrayCollection();
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

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return Collection<int, Documento>
     */
    public function getDocumentos(): Collection
    {
        return $this->documentos;
    }

    public function addDocumento(Documento $documento): self
    {
        if (!$this->documentos->contains($documento)) {
            $this->documentos->add($documento);
            $documento->setActuacion($this);
        }

        return $this;
    }

    public function removeDocumento(Documento $documento): self
    {
        if ($this->documentos->removeElement($documento)) {
            // set the owning side to null (unless already changed)
            if ($documento->getActuacion() === $this) {
                $documento->setActuacion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Nota>
     */
    public function getNotas(): Collection
    {
        return $this->notas;
    }

    public function addNota(Nota $nota): self
    {
        if (!$this->notas->contains($nota)) {
            $this->notas->add($nota);
            $nota->setActuacion($this);
        }

        return $this;
    }

    public function removeNota(Nota $nota): self
    {
        if ($this->notas->removeElement($nota)) {
            // set the owning side to null (unless already changed)
            if ($nota->getActuacion() === $this) {
                $nota->setActuacion(null);
            }
        }

        return $this;
    }

    public function getTpActuacion(): ?TpActuacion
    {
        return $this->tpActuacion;
    }

    public function setTpActuacion(?TpActuacion $tpActuacion): self
    {
        $this->tpActuacion = $tpActuacion;

        return $this;
    }
}
