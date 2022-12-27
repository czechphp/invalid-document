<?php

declare(strict_types=1);

namespace Czechphp\InvalidDocument\Parser;

use Czechphp\InvalidDocument\Exception\InvalidArgumentException;
use Czechphp\InvalidDocument\Exception\ServerErrorException;
use Czechphp\InvalidDocument\Message\Message;
use Czechphp\InvalidDocument\Message\MessageInterface;
use DateTimeImmutable;
use DateTimeZone;
use function simplexml_load_string;

final class XmlParser implements ParserInterface
{
    private const YES = 'ano';
    private const NO = 'ne';
    private const NULL = '-';

    public function parse(string $content): MessageInterface
    {
        $xml = @simplexml_load_string($content);

        if ($xml === false) {
            throw new ServerErrorException("Unable to parse response. Content:\n" . $content);
        }

        // error
        if (isset($xml->chyba)) {
            // invalid request
            switch ($xml->chyba['spatny_dotaz']) {
                case self::YES:
                    throw new InvalidArgumentException((string) $xml->chyba);
                case self::NO:
                default:
                    throw new ServerErrorException((string) $xml->chyba);
            }
        }

        // request || response
        if (!isset($xml->dotaz) || !isset($xml->odpoved)) {
            throw new ServerErrorException("Unable to parse response. Content:\n" . $content);
        }

        $number = (string) $xml->dotaz['cislo'];

        $serialNumber = (string) $xml->dotaz['serie'];

        if ($serialNumber === self::NULL) {
            $serialNumber = null;
        }

        // document type as string which is incompatible with document type in request
        $documentType = (string) $xml->dotaz['typ'];

        $registered = ((string) $xml->odpoved['evidovano']) === self::YES;

        if (isset($xml->odpoved['evidovano_od'])) {
            $registeredAt = DateTimeImmutable::createFromFormat('j.n.Y', (string) $xml->odpoved['evidovano_od'], new DateTimeZone('Europe/Prague'));
            $registeredAt = $registeredAt->setTime(0, 0);
        } else {
            $registeredAt = null;
        }

        $updatedAt = DateTimeImmutable::createFromFormat('j.n.Y', (string) $xml->odpoved['aktualizovano'], new DateTimeZone('Europe/Prague'));
        $updatedAt = $updatedAt->setTime(0, 0);

        $serviceUpdatedAt = DateTimeImmutable::createFromFormat('j.n.Y', (string) $xml['posl_zmena'], new DateTimeZone('Europe/Prague'));
        $serviceUpdatedAt = $serviceUpdatedAt->setTime(0, 0);

        $serviceMessage = (string) $xml['pristi_zmeny'];

        return new Message($number, $serialNumber, $documentType, $registered, $registeredAt, $updatedAt, $serviceUpdatedAt, $serviceMessage);
    }
}
