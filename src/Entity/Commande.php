<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mode_paiement = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $cout_total = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Adresse $adresse_facturation = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Adresse $adresse_livraison = null;

    /**
     * @var Collection<int, CommandeArticle>
     */
    #[ORM\OneToMany(targetEntity: CommandeArticle::class, mappedBy: 'commande', cascade:["persist"])]
    private Collection $commandeArticles;

    public function __construct()
    {
        $this->commandeArticles = new ArrayCollection();
        $this->status = "en cours";
        $this->date_commande = new \DateTime();
        $this->cout_total = 0.0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModePaiement(): ?string
    {
        return $this->mode_paiement;
    }

    public function setModePaiement(?string $mode_paiement): static
    {
        $this->mode_paiement = $mode_paiement;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getCoutTotal(): ?string
    {
        return $this->cout_total;
    }

    public function setCoutTotal(string $cout_total): static
    {
        $this->cout_total = $cout_total;

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

    public function getAdresseFacturation(): ?Adresse
    {
        return $this->adresse_facturation;
    }

    public function setAdresseFacturation(?Adresse $adresse_facturation): static
    {
        $this->adresse_facturation = $adresse_facturation;

        return $this;
    }

    public function getAdresseLivraison(): ?Adresse
    {
        return $this->adresse_livraison;
    }

    public function setAdresseLivraison(?Adresse $adresse_livraison): static
    {
        $this->adresse_livraison = $adresse_livraison;

        return $this;
    }

    /**
     * @return Collection<int, CommandeArticle>
     */
    public function getCommandeArticles(): Collection
    {
        return $this->commandeArticles;
    }

    public function addCommandeArticle(CommandeArticle $commandeArticle): static
    {
        if (!$this->commandeArticles->contains($commandeArticle)) {
            $this->commandeArticles->add($commandeArticle);
            $commandeArticle->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeArticle(CommandeArticle $commandeArticle): static
    {
        if ($this->commandeArticles->removeElement($commandeArticle)) {
            // set the owning side to null (unless already changed)
            if ($commandeArticle->getCommande() === $this) {
                $commandeArticle->setCommande(null);
            }
        }

        return $this;
    }
}
