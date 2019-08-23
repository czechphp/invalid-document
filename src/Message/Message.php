<?php

namespace Czechphp\InvalidDocument\Message;

use DateTimeInterface;

final class Message implements MessageInterface
{
    /**
     * @var string
     */
    private $number;

    /**
     * @var string|null
     */
    private $serialNumber;

    /**
     * @var string
     */
    private $documentType;

    /**
     * @var bool
     */
    private $registered;

    /**
     * @var DateTimeInterface|null
     */
    private $registeredAt;

    /**
     * @var DateTimeInterface
     */
    private $updatedAt;

    /**
     * @var DateTimeInterface
     */
    private $serviceUpdatedAt;

    /**
     * @var string
     */
    private $serviceMessage;

    public function __construct(string $number, ?string $serialNumber, string $documentType, bool $registered, ?DateTimeInterface $registeredAt, DateTimeInterface $updatedAt, DateTimeInterface $serviceUpdatedAt, string $serviceMessage)
    {
        $this->number = $number;
        $this->serialNumber = $serialNumber;
        $this->documentType = $documentType;
        $this->registered = $registered;
        $this->registeredAt = $registeredAt;
        $this->updatedAt = $updatedAt;
        $this->serviceUpdatedAt = $serviceUpdatedAt;
        $this->serviceMessage = $serviceMessage;
    }

    public function getNumber() : string
    {
        return $this->number;
    }

    public function getSerialNumber() : ?string
    {
        return $this->serialNumber;
    }

    public function getDocumentType() : string
    {
        return $this->documentType;
    }

    public function isRegistered() : bool
    {
        return $this->registered;
    }

    public function getRegisteredAt() : ?DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getServiceUpdatedAt() : DateTimeInterface
    {
        return $this->serviceUpdatedAt;
    }

    public function getServiceMessage() : string
    {
        return $this->serviceMessage;
    }
}
