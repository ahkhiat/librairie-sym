<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $isCommande = false;

    /**
     * @var Collection<int, PanierArticle>
     */
    #[ORM\OneToMany(targetEntity: PanierArticle::class, mappedBy: 'panier')]
    private Collection $panierArticles;

    public function __construct()
    {
        $this->panierArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PanierArticle>
     */
    public function getPanierArticles(): Collection
    {
        return $this->panierArticles;
    }

    public function addPanierArticle(PanierArticle $panierArticle): static
    {
        if (!$this->panierArticles->contains($panierArticle)) {
            $this->panierArticles->add($panierArticle);
            $panierArticle->setPanier($this);
        }

        return $this;
    }

    public function removePanierArticle(PanierArticle $panierArticle): static
    {
        if ($this->panierArticles->removeElement($panierArticle)) {
            // set the owning side to null (unless already changed)
            if ($panierArticle->getPanier() === $this) {
                $panierArticle->setPanier(null);
            }
        }

        return $this;
    }

    public function isCommande(): bool
    {
        return $this->isCommande;
    }

    public function setIsCommande(bool $isCommande): self
    {
        $this->isCommande = $isCommande;

        return $this;
    }

}
