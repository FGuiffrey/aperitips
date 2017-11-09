<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Auth\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event.
 *
 * @ORM\Table(
 *     name="events"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\EventRepository"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Event
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
     * @var int
     *
     * @ORM\Column(
     *     name="registered_count",
     *     type="integer",
     *     options={"default": 0}
     * )
     */
    private $registeredCount = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="scheduled_at",
     *     type="datetime"
     * )
     */
    private $scheduledAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="created_at",
     *     type="datetime"
     * )
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="updated_at",
     *     type="datetime"
     * )
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Auth\User",
     *     inversedBy="attendedEvents"
     * )
     * @ORM\JoinTable(
     *     name="event_registered"
     * )
     */
    private $registered;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Subject",
     *     mappedBy="event"
     * )
     */
    private $subjects;

    /**
     * @var \AppBundle\Entity\Auth\User
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Auth\User",
     *     inversedBy="createdEvents"
     * )
     * @ORM\JoinColumn(
     *     name="author_id",
     *     referencedColumnName="id"
     * )
     */
    private $author;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->registered = new ArrayCollection();
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
     * Get registeredCount.
     *
     * @return int
     */
    public function getRegisteredCount(): int
    {
        return $this->registeredCount;
    }

    /**
     * Set scheduledAt.
     *
     * @param \DateTime $scheduledAt
     *
     * @return Event
     */
    public function setScheduledAt(\DateTime $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    /**
     * Get scheduledAt.
     *
     * @return \DateTime
     */
    public function getScheduledAt(): \DateTime
    {
        return $this->scheduledAt;
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
     * Get registered.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistered(): Collection
    {
        return $this->registered;
    }

    /**
     * Get subjects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    /**
     * Set author.
     *
     * @param \AppBundle\Entity\Auth\User $author
     *
     * @return \AppBundle\Entity\Event
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \AppBundle\Entity\Auth\User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * Check if an user is registered.
     *
     * @param \AppBundle\Entity\Auth\User $user
     *
     * @return bool
     */
    public function isRegistered(User $user): bool
    {
        return $this->registered->contains($user);
    }

    /**
     * Add registered.
     *
     * @param \AppBundle\Entity\Auth\User $user
     *
     * @return \AppBundle\Entity\Event
     */
    public function addRegistered(User $user): self
    {
        if (!$this->isRegistered($user)) {
            $this->registered->add($user);
            $user->addAttendedEvents($this);

            ++$this->registeredCount;
        }

        return $this;
    }

    /**
     * Remove registered.
     *
     * @param \AppBundle\Entity\Auth\User $user
     */
    public function removeRegistered(User $user): void
    {
        $user->removeAttendedEvents($this);
        $this->registered->removeElement($user);

        --$this->registeredCount;
    }
}
