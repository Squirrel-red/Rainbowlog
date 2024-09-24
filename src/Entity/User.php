<?php

namespace App\Entity;


use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;


    #[ORM\Column(length: 150, nullable: true)]
    private ?string $avatar = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registration_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_login_date = null;

    #[ORM\Column(nullable: true)]
    private ?array $author_infos = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_profil_edit_time = null;



    /**
     * @var Collection<int, Alert>
     */
    #[ORM\OneToMany(targetEntity: Alert::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $alerts;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'publish')]
    private Collection $publish;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'user')]
    private Collection $evaluations;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'consumer', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(nullable: true)]
    private ?bool $isBlocked = null;

    #[ORM\Column(nullable: true)]
    private ?int $newMessages = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'sender')]
    private Collection $contacts;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'receiver')]
    private Collection $msgReceived;


    public function __construct()
    {
        $this->alerts = new ArrayCollection();
        $this->publish = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->msgReceived = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSimpleRoles(): array
    {
        // fonction utiliser pour filtrer les éléments du tableau. Elle prend deux arguments : le tableau à filtrer ($this->getRoles() dans ce cas) et une fonction de rappel qui définit la condition de filtrage.
        $filteredRoles = array_filter($this->getRoles(), function ($roles) {
            // retourne true pour conserver un rôle et false pour l'exclure. Dans ce cas, on exclut le rôle "ROLE_USER".
            return $roles !== 'ROLE_USER';
        });

        // applique une fonction donnée à chaque élément d'un tableau et retourne un nouveau tableau avec les résultats.
        $simpleRoles = array_map(function ($roles) {

            // utilisée pour formater chaque rôle restant. Elle convertit d'abord le rôle en minuscules (strtolower), puis retire le préfixe "ROLE_" et remplace les underscores par des espaces.
            
            return strtolower(str_replace(['ROLE_', '_'], ['', ' '], $roles));
        }, $filteredRoles);

        return $simpleRoles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

   

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(?\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getLastLoginDate(): ?\DateTimeInterface
    {
        return $this->last_login_date;
    }

    public function setLastLoginDate(?\DateTimeInterface $last_login_date): static
    {
        $this->last_login_date = $last_login_date;

        return $this;
    }

    public function getAuthorInfos(): ?array
    {
        return $this->author_infos;
    }

    public function setAuthorInfos(?array $author_infos): static
    {
        $this->author_infos = $author_infos;

        return $this;
    }



    public function getLastProfilEditTime(): ?\DateTimeInterface
    {
        return $this->last_profil_edit_time;
    }

    public function setLastProfilEditTime(?\DateTimeInterface $last_profil_edit_time): static
    {
        $this->last_profil_edit_time = $last_profil_edit_time;

        return $this;
    }

   
    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setUser($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getUser() === $this) {
                $alert->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getPublish(): Collection
    {
        return $this->publish;
    }

    public function addPublish(Experience $publish): static
    {
        if (!$this->publish->contains($publish)) {
            $this->publish->add($publish);
            $publish->setPublish($this);
        }

        return $this;
    }

    public function removePublish(Experience $publish): static
    {
        if ($this->publish->removeElement($publish)) {
            // set the owning side to null (unless already changed)
            if ($publish->getPublish() === $this) {
                $publish->setPublish(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setUser($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getUser() === $this) {
                $evaluation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setConsumer($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getConsumer() === $this) {
                $comment->setConsumer(null);
            }
        }

        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getNewMessages(): ?int
    {
        return $this->newMessages;
    }

    public function setNewMessages(int $newMessages): static
    {
        $this->newMessages = $newMessages;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setSender($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getSender() === $this) {
                $contact->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getMsgReceived(): Collection
    {
        return $this->msgReceived;
    }

    public function addMsgReceived(Contact $msgReceived): static
    {
        if (!$this->msgReceived->contains($msgReceived)) {
            $this->msgReceived->add($msgReceived);
            $msgReceived->setReceiver($this);
        }

        return $this;
    }

    public function removeMsgReceived(Contact $msgReceived): static
    {
        if ($this->msgReceived->removeElement($msgReceived)) {
            // set the owning side to null (unless already changed)
            if ($msgReceived->getReceiver() === $this) {
                $msgReceived->setReceiver(null);
            }
        }

        return $this;
    }

}
