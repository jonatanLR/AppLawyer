<?php

namespace App\Entity;

use App\Repository\ExpedienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpedienteRepository::class)]
class Expediente
{
    #[ORM\Id]
    #[ORM\Column(length: 15, unique: true, type: 'string')]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'fecha_alta')]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(length: 255, columnDefinition: "ENUM('A', 'P', 'C')")]
    private ?string $estado = null;

    #[ORM\Column(length: 50, nullable: true, name: 'num_ref_exped')]
    private ?string $numRefExped = null;

    #[ORM\Column(length: 100, nullable: true, name: 'num_autos')]
    private ?string $numAutos = null;

    #[ORM\ManyToMany(targetEntity: Juez::class, inversedBy: 'expedientes')]
    private Collection $juezes;

    #[ORM\ManyToMany(targetEntity: Contrario::class, inversedBy: 'expedientes')]
    private Collection $contrarios;

    #[ORM\ManyToMany(targetEntity: Cliente::class, inversedBy: 'expedientes')]
    private Collection $clientes;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'expedientes')]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'expedientes')]
    private ?Procurador $procurador = null;

    #[ORM\ManyToOne(inversedBy: 'expedientes')]
    private ?Juzgado $juzgado = null;

    #[ORM\ManyToOne(inversedBy: 'expedientes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TpProcedimiento $tpProcedimiento = null;

    #[ORM\OneToMany(mappedBy: 'expediente', targetEntity: Actuacion::class, orphanRemoval: true)]
    private Collection $Actuaciones;

    public function __construct()
    {
        $this->juezes = new ArrayCollection();
        $this->contrarios = new ArrayCollection();
        $this->clientes = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->Actuaciones = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

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

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getNumRefExped(): ?string
    {
        return $this->numRefExped;
    }

    public function setNumRefExped(?string $numRefExped): self
    {
        $this->numRefExped = $numRefExped;

        return $this;
    }

    public function getNumAutos(): ?string
    {
        return $this->numAutos;
    }

    public function setNumAutos(?string $numAutos): self
    {
        $this->numAutos = $numAutos;

        return $this;
    }

    /**
     * @return Collection<int, Juez>
     */
    public function getJuezes(): Collection
    {
        return $this->juezes;
    }

    public function addJueze(Juez $jueze): self
    {
        if (!$this->juezes->contains($jueze)) {
            $this->juezes->add($jueze);
        }

        return $this;
    }

    public function removeJueze(Juez $jueze): self
    {
        $this->juezes->removeElement($jueze);

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
        }

        return $this;
    }

    public function removeContrario(Contrario $contrario): self
    {
        $this->contrarios->removeElement($contrario);

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
        }

        return $this;
    }

    public function removeCliente(Cliente $cliente): self
    {
        $this->clientes->removeElement($cliente);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getProcurador(): ?Procurador
    {
        return $this->procurador;
    }

    public function setProcurador(?Procurador $procurador): self
    {
        $this->procurador = $procurador;

        return $this;
    }

    public function getJuzgado(): ?Juzgado
    {
        return $this->juzgado;
    }

    public function setJuzgado(?Juzgado $juzgado): self
    {
        $this->juzgado = $juzgado;

        return $this;
    }

    public function getTpProcedimiento(): ?TpProcedimiento
    {
        return $this->tpProcedimiento;
    }

    public function setTpProcedimiento(?TpProcedimiento $tpProcedimiento): self
    {
        $this->tpProcedimiento = $tpProcedimiento;

        return $this;
    }

    /**
     * @return Collection<int, Actuacion>
     */
    public function getActuaciones(): Collection
    {
        return $this->Actuaciones;
    }

    public function addActuacione(Actuacion $actuacione): self
    {
        if (!$this->Actuaciones->contains($actuacione)) {
            $this->Actuaciones->add($actuacione);
            $actuacione->setExpediente($this);
        }

        return $this;
    }

    public function removeActuacione(Actuacion $actuacione): self
    {
        if ($this->Actuaciones->removeElement($actuacione)) {
            // set the owning side to null (unless already changed)
            if ($actuacione->getExpediente() === $this) {
                $actuacione->setExpediente(null);
            }
        }

        return $this;
    }
}
