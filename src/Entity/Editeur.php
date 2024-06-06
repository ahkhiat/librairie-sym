<?php

namespace App\Entity;

use App\Repository\EditeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditeurRepository::class)]
class Editeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_editeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse_editeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code_postal_editeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville_editeur = null;

    #[ORM\Column(length: 255)]
    private ?string $pays_editeur = null;

    /**
     * @var Collection<int, Edition>
     */
    #[ORM\OneToMany(targetEntity: Edition::class, mappedBy: 'editeur')]
    private Collection $edition;

    public function __construct()
    {
        $this->edition = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nom_editeur ?? '';
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEditeur(): ?string
    {
        return $this->nom_editeur;
    }

    public function setNomEditeur(string $nom_editeur): static
    {
        $this->nom_editeur = $nom_editeur;

        return $this;
    }

    public function getAdresseEditeur(): ?string
    {
        return $this->adresse_editeur;
    }

    public function setAdresseEditeur(string $adresse_editeur): static
    {
        $this->adresse_editeur = $adresse_editeur;

        return $this;
    }

    public function getCodePostalEditeur(): ?string
    {
        return $this->code_postal_editeur;
    }

    public function setCodePostalEditeur(string $code_postal_editeur): static
    {
        $this->code_postal_editeur = $code_postal_editeur;

        return $this;
    }

    public function getVilleEditeur(): ?string
    {
        return $this->ville_editeur;
    }

    public function setVilleEditeur(string $ville_editeur): static
    {
        $this->ville_editeur = $ville_editeur;

        return $this;
    }

    public function getPaysEditeur(): ?string
    {
        return $this->pays_editeur;
    }

    public function setPaysEditeur(string $pays_editeur): static
    {
        $this->pays_editeur = $pays_editeur;

        return $this;
    }

    /**
     * @return Collection<int, Edition>
     */
    public function getEdition(): Collection
    {
        return $this->edition;
    }

    public function addEdition(Edition $edition): static
    {
        if (!$this->edition->contains($edition)) {
            $this->edition->add($edition);
            $edition->setEditeur($this);
        }

        return $this;
    }

    public function removeEdition(Edition $edition): static
    {
        if ($this->edition->removeElement($edition)) {
            // set the owning side to null (unless already changed)
            if ($edition->getEditeur() === $this) {
                $edition->setEditeur(null);
            }
        }

        return $this;
    }


   
    
}
