<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_auteur = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_auteur = null;

    /**
     * @var Collection<int, LivreAuteur>
     */
    #[ORM\OneToMany(targetEntity: LivreAuteur::class, mappedBy: 'auteur')]
    private Collection $livreAuteurs;

    public function __construct()
    {
        $this->livreAuteurs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->prenom_auteur . ' ' . $this->nom_auteur ;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAuteur(): ?string
    {
        return $this->nom_auteur;
    }

    public function setNomAuteur(string $nom_auteur): static
    {
        $this->nom_auteur = $nom_auteur;

        return $this;
    }

    public function getPrenomAuteur(): ?string
    {
        return $this->prenom_auteur;
    }

    public function setPrenomAuteur(string $prenom_auteur): static
    {
        $this->prenom_auteur = $prenom_auteur;

        return $this;
    }

    /**
     * @return Collection<int, LivreAuteur>
     */
    public function getLivreAuteurs(): Collection
    {
        return $this->livreAuteurs;
    }

    public function addLivreAuteur(LivreAuteur $livreAuteur): static
    {
        if (!$this->livreAuteurs->contains($livreAuteur)) {
            $this->livreAuteurs->add($livreAuteur);
            $livreAuteur->setAuteur($this);
        }

        return $this;
    }

    public function removeLivreAuteur(LivreAuteur $livreAuteur): static
    {
        if ($this->livreAuteurs->removeElement($livreAuteur)) {
            // set the owning side to null (unless already changed)
            if ($livreAuteur->getAuteur() === $this) {
                $livreAuteur->setAuteur(null);
            }
        }

        return $this;
    }




}
