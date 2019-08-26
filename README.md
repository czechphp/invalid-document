# InvalidDocument

[![Build Status](https://travis-ci.com/czechphp/invalid-document.svg?branch=master)](https://travis-ci.com/czechphp/invalid-document)
[![codecov](https://codecov.io/gh/czechphp/invalid-document/branch/master/graph/badge.svg)](https://codecov.io/gh/czechphp/invalid-document)

Library for checking if the document is registered as invalid in the database of Ministry of the Interior of the Czech Republic.
[Official information (en)](https://www.mvcr.cz/clanek/neplatne-doklady-ve-formatu-xml-en.aspx)

**Czech:** Knihovna pro kontrolu neplatnosti dokladů u Ministerstva vnitra České republiky.
[Oficiální informace (cs)](https://www.mvcr.cz/clanek/neplatne-doklady-ve-formatu-xml.aspx)

## Instalation

Install the latest version with

```
$ composer require czechphp/invalid-document
```

Choose and install your 
[PSR-18 HTTP Client implementation](https://packagist.org/providers/psr/http-client-implementation) and
[PSR-17 HTTP Factory implementation](https://packagist.org/providers/psr/http-factory-implementation).

Or just install recommended 
[kriswallsmith/buzz](https://packagist.org/packages/kriswallsmith/buzz) and 
[nyholm/psr7](https://packagist.org/packages/nyholm/psr7) with following

```
$ composer require czechphp/invalid-document kriswallsmith/buzz nyholm/psr7
```

## Basic usage
```php
<?php

use Czechphp\InvalidDocument\InvalidDocument;

$client = null; // anything that implements PSR-18 HTTP Client
$requestFactory = null; // anything that implements PSR-17 HTTP Factory

$invalidDocument = new InvalidDocument($client, $requestFactory);

$message = $invalidDocument->get(InvalidDocument::IDENTIFICATION_CARD, '123456', 'AB');

if (true === $message->isRegistered()) {
    // the document is absolutely not valid
    // for example the document might be stolen or just replaced with new one and this one was invalidated
}

if (false === $message->isRegistered()) {
    // the document is not kept in the registry of invalid documents
    // for example typo in the document number may return false
}
```
