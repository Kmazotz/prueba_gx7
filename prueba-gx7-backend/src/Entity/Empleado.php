<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EmpleadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpleadoRepository::class)]
#[ApiResource]
class Empleado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nombre;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 1)]
    private $sexo;

    #[ORM\ManyToOne(targetEntity: Areas::class, inversedBy: 'empleados')]
    #[ORM\JoinColumn(nullable: false)]
    private $area_id;

    #[ORM\Column(type: 'integer')]
    private $boletin;

    #[ORM\Column(type: 'text')]
    private $descripcion;

    #[ORM\OneToMany(mappedBy: 'empleado', targetEntity: EmpleadoRol::class)]
    private $empleadoRols;

    public function __construct()
    {
        $this->empleadoRols = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getAreaId(): ?Areas
    {
        return $this->area_id;
    }

    public function setAreaId(?Areas $area_id): self
    {
        $this->area_id = $area_id;

        return $this;
    }

    public function getBoletin(): ?int
    {
        return $this->boletin;
    }

    public function setBoletin(int $boletin): self
    {
        $this->boletin = $boletin;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, EmpleadoRol>
     */
    public function getEmpleadoRols(): Collection
    {
        return $this->empleadoRols;
    }

    public function addEmpleadoRol(EmpleadoRol $empleadoRol): self
    {
        if (!$this->empleadoRols->contains($empleadoRol)) {
            $this->empleadoRols[] = $empleadoRol;
            $empleadoRol->setEmpleado($this);
        }

        return $this;
    }

    public function removeEmpleadoRol(EmpleadoRol $empleadoRol): self
    {
        if ($this->empleadoRols->removeElement($empleadoRol)) {
            // set the owning side to null (unless already changed)
            if ($empleadoRol->getEmpleado() === $this) {
                $empleadoRol->setEmpleado(null);
            }
        }

        return $this;
    }
}
