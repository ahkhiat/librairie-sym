<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ISBN = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_livre = null;

    #[ORM\Column(length: 255)]
    private ?string $theme_livre = null;

    /**
     * @var Collection<int, Edition>
     */
    #[ORM\OneToMany(targetEntity: Edition::class, mappedBy: 'livre')]
    private Collection $edition;

    /**
     * @var Collection<int, LivreAuteur>
     */
    #[ORM\OneToMany(targetEntity: LivreAuteur::class, mappedBy: 'livre')]
    private Collection $livreAuteurs;

    public function __construct()
    {
        $this->edition = new ArrayCollection();
        $this->livreAuteurs = new ArrayCollection();

    }

    public function __toString(): string
    {
        return $this->titre_livre ?? '';
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getTitreLivre(): ?string
    {
        return $this->titre_livre;
    }

    public function setTitreLivre(string $titre_livre): static
    {
        $this->titre_livre = $titre_livre;

        return $this;
    }

    public function getThemeLivre(): ?string
    {
        return $this->theme_livre;
    }

    public function setThemeLivre(string $theme_livre): static
    {
        $this->theme_livre = $theme_livre;

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
            $edition->setLivre($this);
        }

        return $this;
    }

    public function removeEdition(Edition $edition): static
    {
        if ($this->edition->removeElement($edition)) {
            // set the owning side to null (unless already changed)
            if ($edition->getLivre() === $this) {
                $edition->setLivre(null);
            }
        }

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
            $livreAuteur->setLivre($this);
        }

        return $this;
    }

    public function removeLivreAuteur(LivreAuteur $livreAuteur): static
    {
        if ($this->livreAuteurs->removeElement($livreAuteur)) {
            // set the owning side to null (unless already changed)
            if ($livreAuteur->getLivre() === $this) {
                $livreAuteur->setLivre(null);
            }
        }

        return $this;
    }


    
}

    


