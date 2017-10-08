<?php

namespace AppBundle\Entity\Auth;

use AppBundle\Entity\Event;
use AppBundle\Entity\Subject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringy\Stringy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class User.
 *
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @UniqueEntity("slug")
 *
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="uq_username", columns={"username"}),
 *         @ORM\UniqueConstraint(name="uq_email", columns={"email"}),
 *         @ORM\UniqueConstraint(name="uq_slug", columns={"slug"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\Auth\UserRepository"
 * )
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(
     *     strategy="AUTO"
     * )
     * @ORM\Column(
     *     name="id",
     *     type="integer"
     * )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="username",
     *     type="string"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="email",
     *     type="string"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="slug",
     *     type="string",
     *     length=45
     * )
     */
    private $slug;

    /**
     * @var null|string
     *
     * @ORM\Column(
     *     name="firstname",
     *     type="string",
     *     nullable=true
     * )
     */
    private $firstname;

    /**
     * @var null|string
     *
     * @ORM\Column(
     *     name="lastname",
     *     type="string",
     *     nullable=true
     * )
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="picture",
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    private $picture;

    /**
     * @var File
     *
     * @Vich\UploadableField(
     *     mapping="user_picture",
     *     fileNameProperty="picture"
     * )
     * @Assert\Image(
     *     maxSize = "500k"
     * )
     */
    private $pictureFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="created_at",
     *     type="datetime",
     *     nullable=false
     * )
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="updated_at",
     *     type="datetime",
     *     nullable=false
     * )
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Auth\Role",
     *     inversedBy="users"
     * )
     * @ORM\JoinColumn(
     *     name="role_id",
     *     referencedColumnName="id"
     * )
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Event",
     *     mappedBy="registered"
     * )
     * @ORM\JoinColumn(
     *     name="event_id",
     *     referencedColumnName="id"
     * )
     */
    private $attendedEvents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Event",
     *     mappedBy="author"
     * )
     */
    private $createdEvents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Subject",
     *     mappedBy="author"
     * )
     */
    private $createdSubjects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Subject",
     *     mappedBy="speakers"
     * )
     * @ORM\JoinColumn(
     *     name="subject_id",
     *     referencedColumnName="id"
     * )
     */
    private $subjects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Subject",
     *     mappedBy="votes"
     * )
     * @ORM\JoinColumn(
     *     name="subject_id",
     *     referencedColumnName="id"
     * )
     */
    private $votes;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->attendedEvents = new ArrayCollection();
        $this->createdEvents = new ArrayCollection();
        $this->createdSubjects = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setSlug(string $slug): self
    {
        $this->slug = Stringy::create($slug)->slugify()->trim();

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Get fullname.
     *
     * @return null|string
     */
    public function getFullname(): ?string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Set picturePath.
     *
     * @param null|string $picture
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setPicture(?string $picture = null): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picturePath.
     *
     * @return null|string
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * Set pictureFile.
     *
     * @param null|\Symfony\Component\HttpFoundation\File\File $file
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function setPictureFile(?File $file): self
    {
        $this->pictureFile = $file;

        if ($file instanceof File) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get pictureFile.
     *
     * @return null|\Symfony\Component\HttpFoundation\File\File
     */
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    /**
     * Set createdAt.
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    /**
     * Get attendedEvents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttendedEvents(): Collection
    {
        return $this->attendedEvents;
    }

    /**
     * Get createdEvents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedEvents(): Collection
    {
        return $this->createdEvents;
    }

    /**
     * Get createdSubjects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreatedSubjects(): Collection
    {
        return $this->createdSubjects;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return '';
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->roles]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        list($this->id, $this->username, $this->roles) = unserialize($serialized);
    }

    /**
     * Add role.
     *
     * @param \AppBundle\Entity\Auth\Role $role
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addUser($this);
        }

        return $this;
    }

    /**
     * Remove role.
     *
     * @param \AppBundle\Entity\Auth\Role $role
     */
    public function removeRole(Role $role): void
    {
        $this->roles->removeElement($role);
    }

    /**
     * Add event.
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function addAttendedEvents(Event $event): self
    {
        if (!$this->attendedEvents->contains($event)) {
            $this->attendedEvents->add($event);
            $event->addRegistered($this);
        }

        return $this;
    }

    /**
     * Remove event.
     *
     * @param \AppBundle\Entity\Event $event
     */
    public function removeAttendedEvents(Event $event): void
    {
        $this->attendedEvents->removeElement($event);
    }

    /**
     * Add subject.
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->addSpeaker($this);
        }

        return $this;
    }

    /**
     * Remove subject.
     *
     * @param \AppBundle\Entity\Subject $subject
     */
    public function removeSubject(Subject $subject): void
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Add vote.
     *
     * @param \AppBundle\Entity\Subject
     *
     * @return \AppBundle\Entity\Auth\User $user
     */
    public function addVote(Subject $subject): self
    {
        if (!$this->votes->contains($subject)) {
            $this->votes->add($subject);
            $subject->addVote($this);
        }

        return $this;
    }
}
