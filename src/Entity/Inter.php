<?php

namespace App\Entity;

use App\Repository\InterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterRepository::class)
 */
class Inter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Anomalie;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Salaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inters")
     */
    private $technicien;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $presence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAnomalie(): ?string
    {
        return $this->Anomalie;
    }

    public function setAnomalie(?string $Anomalie): self
    {
        $this->Anomalie = $Anomalie;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->Salaire;
    }

    public function setSalaire(?float $Salaire): self
    {
        $this->Salaire = $Salaire;

        return $this;
    }

    public function getTechnicien(): ?User
    {
        return $this->technicien;
    }

    public function setTechnicien(?User $technicien): self
    {
        $this->technicien = $technicien;

        return $this;
    }

    public function isPresence(): ?bool
    {
        return $this->presence;
    }

    public function setPresence(?bool $presence): self
    {
        $this->presence = $presence;

        return $this;
    }
}
