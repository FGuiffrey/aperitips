<?php

namespace AppBundle\Entity\Auth;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class IdEntry.
 *
 * @ORM\Entity()
 */
class IdEntry
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(
     *     strategy="NONE"
     * )
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=false
     * )
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(
     *     strategy="NONE"
     * )
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=false
     * )
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(
     *     name="expiry_timestamp",
     *     type="integer",
     *     nullable=false
     * )
     */
    private $expiryTimestamp;

    /**
     * Get entityId.
     *
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * Set entityId.
     *
     * @param string $entityId
     *
     * @return IdEntry
     */
    public function setEntityId($entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param string $id
     *
     * @return IdEntry
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get expiryTimestamp.
     *
     * @return \DateTime
     */
    public function getExpiryTime(): DateTime
    {
        return (new DateTime())->setTimestamp($this->expiryTimestamp);
    }

    /**
     * Set expiryTime.
     *
     * @param \DateTime $expiryTime
     *
     * @return IdEntry
     */
    public function setExpiryTime(DateTime $expiryTime): self
    {
        $this->expiryTimestamp = $expiryTime->getTimestamp();

        return $this;
    }
}
