<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_user", type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(name="pwd", type="string")
     */
    private $password;

   // /**
   //  * @var string|null
    // *
     //* @ORM\Column(type="string", length=255, nullable=true ,options={"default"="NULL"})
     //*/
    //private $reset_token;

    /**
     * @var string|null
     *
     * @ORM\Column(name="poste", type="string", length=30, nullable=true, options={"default"="NULL"})
     */
    private $poste = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="salaire", type="string", length=30, nullable=true, options={"default"="NULL"})
     */
    private $salaire = 'NULL';

    /**
     * @ORM\Column(name="role", type="json")
     */
    private $roles = [];





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPoste(): ?string
    {
        return $this->poste;
    }

    /**
     * @param string|null $poste
     */
    public function setPoste(?string $poste): void
    {
        $this->poste = $poste;
    }

    /**
     * @return string|null
     */
    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    /**
     * @param string|null $salaire
     */
    public function setSalaire(?string $salaire): void
    {
        $this->salaire = $salaire;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    ///**
     //* @return mixed
     //*/
   // public function getResetToken()
    //{
       // return $this->reset_token;
   // }

    ///**
    // * @param mixed $reset_token
    // */
    //public function setResetToken($reset_token): void
    //{
       // $this->reset_token = $reset_token;
   // }

    public function __toString() {
        return (String)$this->id;
    }
}
