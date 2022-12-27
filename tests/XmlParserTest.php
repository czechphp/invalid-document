<?php

declare(strict_types=1);

namespace Czechphp\InvalidDocument\Tests;

use Czechphp\InvalidDocument\Exception\InvalidArgumentException;
use Czechphp\InvalidDocument\Exception\ServerErrorException;
use Czechphp\InvalidDocument\Message\Message;
use Czechphp\InvalidDocument\Parser\XmlParser;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

final class XmlParserTest extends TestCase
{
    public function testValid(): void
    {
        $parser = new XmlParser();
        $content = <<<XML
<?xml version="1.0" ?>
<doklady_neplatne   posl_zmena="12.8.2010"   pristi_zmeny="brief notice">
      <dotaz   typ="OP"   cislo="12345678"   serie="-"/>
      <odpoved   aktualizovano="1.1.2019"   evidovano="ne" />
</doklady_neplatne>
XML;
        $expected = new Message(
            '12345678',
            null,
            'OP',
            false,
            null,
            new DateTime('1.1.2019', new DateTimeZone('Europe/Prague')),
            new DateTime('12.8.2010', new DateTimeZone('Europe/Prague')),
            'brief notice'
        );

        $this->assertEquals($expected, $parser->parse($content));
    }

    public function testInvalid(): void
    {
        $parser = new XmlParser();
        $content = <<<XML
<?xml version="1.0" ?>
<doklady_neplatne   posl_zmena="12.8.2010"   pristi_zmeny="brief notice">
      <dotaz   typ="OPs"   cislo="12345678"   serie="AB"/>
      <odpoved   aktualizovano="1.1.2019"   evidovano="ano"
        evidovano_od="1.1.2019" />
</doklady_neplatne>
XML;
        $expected = new Message(
            '12345678',
            'AB',
            'OPs',
            true,
            new DateTime('1.1.2019', new DateTimeZone('Europe/Prague')),
            new DateTime('1.1.2019', new DateTimeZone('Europe/Prague')),
            new DateTime('12.8.2010', new DateTimeZone('Europe/Prague')),
            'brief notice'
        );

        $this->assertEquals($expected, $parser->parse($content));
    }

    public function testInvalidRequest(): void
    {
        $parser = new XmlParser();
        $content = <<<XML
<?xml version="1.0" ?>
<doklady_neplatne   posl_zmena="12.8.2010"   pristi_zmeny="brief notice">
    <chyba   spatny_dotaz="ano">error description</chyba>
</doklady_neplatne>
XML;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('error description');

        $parser->parse($content);
    }

    public function testServerError(): void
    {
        $parser = new XmlParser();
        $content = <<<XML
<?xml version="1.0" ?>
<doklady_neplatne   posl_zmena="12.8.2010"   pristi_zmeny="brief notice">
    <chyba   spatny_dotaz="ne">error description</chyba>
</doklady_neplatne>
XML;

        $this->expectException(ServerErrorException::class);
        $this->expectExceptionMessage('error description');

        $parser->parse($content);
    }

    public function testInvalidResponse(): void
    {
        $parser = new XmlParser();
        $content = <<<TXT
Server error
TXT;

        $this->expectException(ServerErrorException::class);
        $this->expectExceptionMessage("Unable to parse response. Content:\nServer error");

        $parser->parse($content);
    }

    public function testMissingContent(): void
    {
        $parser = new XmlParser();
        $content = <<<XML
<?xml version="1.0" ?>
<doklady_neplatne   posl_zmena="12.8.2010"   pristi_zmeny="brief notice">
</doklady_neplatne>
XML;

        $this->expectException(ServerErrorException::class);
        $this->expectExceptionMessage("Unable to parse response. Content:\n" . $content);

        $parser->parse($content);
    }
}
