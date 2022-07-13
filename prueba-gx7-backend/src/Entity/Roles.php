<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
#[ApiResource]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nombre;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: EmpleadoRol::class)]
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
            $empleadoRol->setRole($this);
        }

        return $this;
    }

    public function removeEmpleadoRol(EmpleadoRol $empleadoRol): self
    {
        if ($this->empleadoRols->removeElement($empleadoRol)) {
            // set the owning side to null (unless already changed)
            if ($empleadoRol->getRole() === $this) {
                $empleadoRol->setRole(null);
            }
        }

        return $this;
    }
}
