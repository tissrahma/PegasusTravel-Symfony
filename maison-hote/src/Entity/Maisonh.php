<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Symfony\Component\Validator\Constraints as Assert;




/**
 * Maisonh
 *
 * @ORM\Table(name="maisonh")
 * @ORM\Entity
 */
class Maisonh
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_maison", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMaison;

    /**
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer un nom au mini de 3 caracteres"
     *
     *     )
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message=" localisation doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer une localisation au minimum de 5 caracteres"
     *
     *     )

     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=1000, nullable=false)
     */
    private $localisation;

    /**
     * @Assert\NotBlank(message=" description doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un titre au mini de 5 caracteres"
     *
     *     )

     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @Assert\NotBlank(message=" prix doit etre non vide")
     * @var float
     *@Assert\Positive
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @Assert\NotBlank(message=" image doit etre non vide")
     * @var string
     *
     * @ORM\Column(name="image_maison", type="string", length=100, nullable=false)
     */
    private $imageMaison;


    public function getIdMaison(): ?int
    {
        return $this->idMaison;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImageMaison()
    {
        return $this->imageMaison;
    }

    public function setImageMaison($imageMaison)
    {
        $this->imageMaison = $imageMaison;

        return $this;
    }

    public function __toString():string
    {
        return (string)$this->idMaison;
    }

}
