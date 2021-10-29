<?php

namespace App\Entity;

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
}