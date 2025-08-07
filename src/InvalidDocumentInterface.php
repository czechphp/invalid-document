<?php

declare(strict_types=1);

namespace Czechphp\InvalidDocument;

use Czechphp\InvalidDocument\Exception\InvalidArgumentException;
use Czechphp\InvalidDocument\Exception\ServerErrorException;
use Czechphp\InvalidDocument\Message\MessageInterface;

interface InvalidDocumentInterface
{
	public const URI = 'https://aplikace.mv.gov.cz/neplatne-doklady/Doklady.aspx';

	/**
	 * Občanský průkaz
	 */
	public const IDENTIFICATION_CARD = 0;

	/**
	 * Centrálně vydávaný cestovní pas
	 */
	public const CENTRALLY_ISSUED_PASSPORT = 4;

	/**
	 * Cestovní pas vydaný okresním úřadem
	 */
	public const REGIONALLY_ISSUED_PASSPORT = 5;

	/**
	 * Zbrojní průkaz
	 */
	public const GUN_LICENSE = 6;

	/**
	 * @throws InvalidArgumentException
	 * @throws ServerErrorException
	 */
	public function get(int $documentType, string $number): MessageInterface;
}
