<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Just\Some\Test\Auth;

use Brain\Nonces\NonceInterface;


/**
 * Interface SettingsPageAuthInterface
 *
 * @package Just\Some\Test\Auth
 */
interface SettingsPageAuthInterface
{

    /**
     * @param array $request_data
     *
     * @return bool
     */
    public function isAllowed(array $request_data = []): bool;

    /**
     * @return NonceInterface
     */
    public function nonce(): NonceInterface;

    /**
     * @return string
     */
    public function cap(): string;
}
