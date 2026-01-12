<?php

namespace App\Event;

class ContactRequestEvent
{
    private $contactData;

    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    public function getContactData()
    {
        return $this->contactData;
    }
}