<?php

namespace Czechphp\InvalidDocument\Tests;

use Czechphp\InvalidDocument\Message\Message;
use DateTime;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testCanCreateAndRead()
    {
        $number = '1';
        $serialNumber = 'A';
        $documentType = 1;
        $registered = true;
        $registeredAt = new DateTime('');
        $updatedAt = new DateTime('');
        $serviceUpdatedAt = new DateTime('');
        $serviceMessage = 'message';

        $message = new Message($number, $serialNumber, $documentType, $registered, $registeredAt, $updatedAt, $serviceUpdatedAt, $serviceMessage);

        $this->assertEquals($number, $message->getNumber());
        $this->assertEquals($serialNumber, $message->getSerialNumber());
        $this->assertEquals($documentType, $message->getDocumentType());
        $this->assertEquals($registered, $message->isRegistered());
        $this->assertEquals($registeredAt, $message->getRegisteredAt());
        $this->assertEquals($updatedAt, $message->getUpdatedAt());
        $this->assertEquals($serviceUpdatedAt, $message->getServiceUpdatedAt());
        $this->assertEquals($serviceMessage, $message->getServiceMessage());
    }
}
