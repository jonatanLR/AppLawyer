<?php

namespace App\Entity;

use App\Repository\DocumentoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentoRepository::class)]
class Documento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'documentos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Actuacion $actuacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getActuacion(): ?Actuacion
    {
        return $this->actuacion;
    }

    public function setActuacion(?Actuacion $actuacion): self
    {
        $this->actuacion = $actuacion;

        return $this;
    }
}
