<?php

namespace App\Entity;

use App\Repository\EditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditionRepository::class)]
class Edition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $annee_edition = null;

    #[ORM\Column]
    private ?int $nbr_pages = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $format = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $prix_vente = null;

    #[ORM\ManyToOne(inversedBy: 'edition')]
    private ?Livre $livre = null;

    #[ORM\ManyToOne(inversedBy: 'edition')]
    private ?Editeur $editeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnneeEdition(): ?\DateTimeInterface
    {
        return $this->annee_edition;
    }

    public function setAnneeEdition(\DateTimeInterface $annee_edition): static
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
}
