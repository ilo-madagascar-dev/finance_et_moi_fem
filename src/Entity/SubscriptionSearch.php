<?php

namespace App\Entity;

use DateTime;

class SubscriptionSearch 
{   
    /**
     * @var Abonnement|null
     */
    private $subscription;

    /**
     * @var string|null
     */
    private $town;

    /**
     * @var string|null 
     */
    private $postalCode;

    /**
     * @var Datetime|null 
     */
    private $dateDebutInterval;

    /**
     * @var Datetime|null 
     */
    private $dateFinInterval;


    public function getSubscription()
    {
        return $this->subscription;
    }

    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getTown():?string
    {
        return $this->town;
    }

    public function setTown(string $town)
    {
        $this->town = $town;

        return $this;
    }

    public function getPostalCode():?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode)
    {
        $this->postalCode =$postalCode;

        return $this;
    }

    public function getDateDebutInterval()
    {
        return $this->dateDebutInterval;
    }

    public function setDateDebutInterval($dateDebutInterval)
    {
        $this->dateDebutInterval =$dateDebutInterval;

        return $this;
    }

    public function getDateFinInterval()
    {
        return $this->dateFinInterval;
    }

    public function setDateFinInterval($dateFinInterval)
    {
        $this->dateFinInterval =$dateFinInterval;

        return $this;
    }
}