<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Drank
 *
 * @ORM\Table(name="drank")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DrankRepository")
 */
class Drank
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="UserId", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="SessionId", type="integer")
     */
    private $sessionId;

    /**
     * @var int
     *
     * @ORM\Column(name="BeverageId", type="integer")
     */
    private $beverageId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Drank
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set sessionId
     *
     * @param integer $sessionId
     *
     * @return Drank
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return int
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set beverageId
     *
     * @param integer $beverageId
     *
     * @return Drank
     */
    public function setBeverageId($beverageId)
    {
        $this->beverageId = $beverageId;

        return $this;
    }

    /**
     * Get beverageId
     *
     * @return int
     */
    public function getBeverageId()
    {
        return $this->beverageId;
    }
}

