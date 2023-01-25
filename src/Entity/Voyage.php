<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Voyage
 *
 * @ORM\Table(name="voyage")
 * @ORM\Entity
 */
class Voyage
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @Assert\NotBlank(message=" le nom doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer un nom au mini de 3 caracteres"
     *
     *     )
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=150, nullable=false)
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @Assert\NotBlank(message=" destination doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer une destination au mini de 3 caracteres"
     *
     *     )
     * @var string
     *
     * @ORM\Column(name="Destination", type="string", length=30, nullable=false)
     * @Groups("post:read")
     */
    private $destination;

    /**
     * @Assert\NotBlank(message=" description doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer une description au mini de 3 caracteres"
     *
     *     )
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=2000, nullable=false)
     * @Groups("post:read")
     */
    private $description;

    /**
     * @Assert\NotBlank(message=" prix doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer un prix au mini de 3 caracteres"
     *
     *     )
     * @var int
     *
     * @ORM\Column(name="Prix", type="integer", nullable=false)
     * @Groups("post:read")
     */
    private $prix;

    /**
     * @Assert\NotBlank(message=" image doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer une image au mini de 3 caracteres"
     *
     *     )
     * @var string
     *
     * @ORM\Column(name="Image", type="string", length=150, nullable=false)
     * @Groups("post:read")
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
public function __toString():string
{
    return (string)$this->id;
}

}
