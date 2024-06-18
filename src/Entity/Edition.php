<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EditionRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: EditionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]

class Edition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (name: 'id')]
    private ?int $edition_id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $annee_edition = null;

    #[ORM\Column]
    private ?int $nbr_pages = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $format = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $prix_vente = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(nullable: true)]
    private ?int $alerte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'edition_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'edition')]
    private ?Livre $livre = null;

    #[ORM\ManyToOne(inversedBy: 'edition')]
    private ?Editeur $editeur = null;

    /**
     * @var Collection<int, PanierArticle>
     */
    #[ORM\OneToMany(targetEntity: PanierArticle::class, mappedBy: 'edition')]
    private Collection $panierArticles;

    /**
     * @var Collection<int, CommandeArticle>
     */
    #[ORM\OneToMany(targetEntity: CommandeArticle::class, mappedBy: 'edition')]
    private Collection $commandeArticles;

    /**
     * @var Collection<int, FactureLigne>
     */
    #[ORM\OneToMany(targetEntity: FactureLigne::class, mappedBy: 'edition')]
    private Collection $factureLignes;

    public function __construct()
    {
        $this->panierArticles = new ArrayCollection();
        $this->commandeArticles = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->factureLignes = new ArrayCollection();
    }

    public function getEditionId(): ?int
    {
        return $this->edition_id;
    }

    public function getAnneeEdition(): ?int
    {
        return $this->annee_edition;
    }

    public function setAnneeEdition(?int $annee_edition): self
    {
        $this->annee_edition = $annee_edition;

        return $this;
    }

    public function getNbrPages(): ?int
    {
        return $this->nbr_pages;
    }

    public function setNbrPages(int $nbr_pages): static
    {
        $this->nbr_pages = $nbr_pages;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prix_vente;
    }

    public function setPrixVente(string $prix_vente): static
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
    public function getAlerte(): ?int
    {
        return $this->alerte;
    }

    public function setAlerte(?int $alerte): self
    {
        $this->alerte = $alerte;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
            $extension = $imageFile->guessExtension();
            $this->setImageName($this->imageName); 
            $this->imageName .= '.' . $extension;   
        }
    }

    // public function setImageFile(?File $imageFile = null): void
    // {
    //     $this->imageFile = $imageFile;
    //     if (null !== $imageFile) {
    //         $this->updatedAt = new \DateTimeImmutable();
    //         $newFileName = $this->getUploadFileNamePattern().'.'.$imageFile->guessExtension();
    //         $this->setImageName($newFileName);
    //     }
    // }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getUploadFileNamePattern()
    {
        $bookName = $this->getLivre()->getTitreLivre();
        $publisherName = $this->getEditeur()->getNomEditeur();
        $year = $this->getAnneeEdition();

        $bookName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $bookName);
        $publisherName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $publisherName);

        return sprintf('%s_%s_%s.[extension]', $bookName, $publisherName, $year);
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
            $panierArticle->setEdition($this);
        }

        return $this;
    }

    public function removePanierArticle(PanierArticle $panierArticle): static
    {
        if ($this->panierArticles->removeElement($panierArticle)) {
            // set the owning side to null (unless already changed)
            if ($panierArticle->getEdition() === $this) {
                $panierArticle->setEdition(null);
            }
        }

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
            $commandeArticle->setEdition($this);
        }

        return $this;
    }

    public function removeCommandeArticle(CommandeArticle $commandeArticle): static
    {
        if ($this->commandeArticles->removeElement($commandeArticle)) {
            // set the owning side to null (unless already changed)
            if ($commandeArticle->getEdition() === $this) {
                $commandeArticle->setEdition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FactureLigne>
     */
    public function getFactureLignes(): Collection
    {
        return $this->factureLignes;
    }

    public function addFactureLigne(FactureLigne $factureLigne): static
    {
        if (!$this->factureLignes->contains($factureLigne)) {
            $this->factureLignes->add($factureLigne);
            $factureLigne->setEdition($this);
        }

        return $this;
    }

    public function removeFactureLigne(FactureLigne $factureLigne): static
    {
        if ($this->factureLignes->removeElement($factureLigne)) {
            // set the owning side to null (unless already changed)
            if ($factureLigne->getEdition() === $this) {
                $factureLigne->setEdition(null);
            }
        }

        return $this;
    }
}
