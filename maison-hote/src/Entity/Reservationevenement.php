<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservationevenement
 *
 * @ORM\Table(name="reservationevenement", indexes={@ORM\Index(name="idEvent", columns={"idEvent"})})
 * @ORM\Entity
 */
class Reservationevenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idre;

    /**
     * @var string
     *
     * @ORM\Column(name="nomRE", type="string", length=255, nullable=false)
     */
    private $nomre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRE", type="date", nullable=false)
     */
    private $datere;

    /**
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEvent", referencedColumnName="idEvent")
     * })
     */
    private $idevent;

    public function getIdre(): ?int
    {
        return $this->idre;
    }

    public function getNomre(): ?string
    {
        return $this->nomre;
    }

    public function setNomre(string $nomre): self
    {
        $this->nomre = $nomre;

        return $this;
    }

    public function getDatere(): ?\DateTimeInterface
    {
        return $this->datere;
    }

    public function setDatere(\DateTimeInterface $datere): self
    {
        $this->datere = $datere;

        return $this;
    }

    public function getIdevent(): ?Evenement
    {
        return $this->idevent;
    }

    public function setIdevent(?Evenement $idevent): self
    {
        $this->idevent = $idevent;

        return $this;
    }


}
