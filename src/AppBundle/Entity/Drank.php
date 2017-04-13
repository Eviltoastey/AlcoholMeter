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
     * @ORM\ManyToOne(targetEntity="Session")
     */
    private $sessionId;

    /**
     * @ORM\ManyToOne(targetEntity="Beverage")
     */
    private $beverageId;

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float")
     */
    private $amount;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }


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
     * Set sessionId
     *
     * @param Session $sessionId
     *
     * @return Drank
     */
    public function setSessionId(Session $sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return Session
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set beverageId
     *
     * @param Beverage $beverageId
     *
     * @return Drank
     */
    public function setBeverageId(Beverage $beverageId)
    {
        $this->beverageId = $beverageId;

        return $this;
    }

    /**
     * Get beverageId
     *
     * @return Beverage
     */
    public function getBeverageId()
    {
        return $this->beverageId;
    }
}

