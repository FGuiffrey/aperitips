<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Auth\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringy\Stringy;

/**
 * Subject.
 *
 * @ORM\Table(
 *     name="subjects"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\SubjectRepository"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Subject
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

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
     *     name="name",
     *     type="string",
     *     length=255
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="slug",
     *     type="string",
     *     length=255
     * )
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="content",
     *     type="text"
     * )
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="status",
     *     type="string",
     *     length=10
     * )
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(
     *     name="reason",
     *     type="text",
     *     nullable=true
     * )
     */
    private $reason;

    /**
     * @var int
     *
     * @ORM\Column(
     *     name="vote_count",
     *     type="integer",
     *     options={"default" : 0}
     * )
     */
    private $voteCount = 0;

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
     * @var \AppBundle\Entity\Auth\User
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Auth\User",
     *     inversedBy="createdSubjects"
     * )
     * @ORM\JoinColumn(
     *     name="author_id",
     *     referencedColumnName="id"
     * )
     */
    private $author;

    /**
     * @var \AppBundle\Entity\Event
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Event",
     *     inversedBy="subjects"
     * )
     * @ORM\JoinColumn(
     *     name="event_id",
     *     referencedColumnName="id"
     * )
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Auth\User",
     *     inversedBy="subjects"
     * )
     * @ORM\JoinTable(
     *     name="subject_speaker"
     * )
     */
    private $speakers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Auth\User",
     *     inversedBy="votes"
     * )
     * @ORM\JoinTable(
     *     name="subject_vote"
     * )
     */
    private $votes;

    /**
     * Subject constructor.
     */
    public function __construct()
    {
        $this->speakers = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return \AppBundle\Entity\Subject
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return \AppBundle\Entity\Subject
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
     * Set content.
     *
     * @param string $content
     *
     * @return \AppBundle\Entity\Subject
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return \AppBundle\Entity\Subject
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set reason.
     *
     * @param string $reason
     *
     * @return \AppBundle\Entity\Subject
     */
    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason.
     *
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * Get voteCount.
     *
     * @return int
     */
    public function getVoteCount(): int
    {
        return $this->voteCount;
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
     * Set author.
     *
     * @param \AppBundle\Entity\Auth\User $user
     *
     * @return \AppBundle\Entity\Subject
     */
    public function setAuthor(User $user): self
    {
        $this->author = $user;

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
     * Set event.
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return \AppBundle\Entity\Subject
     */
    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return \AppBundle\Entity\Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * Get speakers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpeakers(): Collection
    {
        return $this->speakers;
    }

    /**
     * Get votes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * Add registered.
     *
     * @param \AppBundle\Entity\Auth\User $user
     *
     * @return \AppBundle\Entity\Subject
     */
    public function addSpeaker(User $user): self
    {
        if (!$this->speakers->contains($user)) {
            $this->speakers->add($user);
            $user->addSubject($this);
        }

        return $this;
    }

    /**
     * Remove registered.
     *
     * @param \AppBundle\Entity\Auth\User $user
     */
    public function removeSpeaker(User $user): void
    {
        $user->removeSubject($this);
        $this->speakers->removeElement($user);
    }

    /**
     * @param \AppBundle\Entity\Auth\User $user
     *
     * @return bool
     */
    public function hasVoted(User $user): bool
    {
        return $this->votes->contains($user);
    }

    /**
     * Add vote.
     *
     * @param \AppBundle\Entity\Auth\User $user
     *
     * @return \AppBundle\Entity\Subject
     */
    public function addVote(User $user): self
    {
        if (!$this->hasVoted($user)) {
            $this->votes->add($user);
            $user->addVote($this);

            ++$this->voteCount;
        }

        return $this;
    }
}
