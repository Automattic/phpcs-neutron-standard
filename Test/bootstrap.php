<?php
/**
 * Bootstrap file for tests.
 *
 * Load either the PHPCS 2.x or 3.x bootstrap file depending on an environment variable.
 *
 * This file is intended for use with Travis where the environment variable
 * will be available.
 *
 * For running the unit tests manually, see the instructions below and in
 * the CONTRIBUTING.MD document.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.13.0
 */

// Get the PHPCS BRANCH used from an environment variable.
$phpcs_branch = getenv( 'PHPCS_BRANCH' );

if ( false === $phpcs_branch ) {
	echo 'To manually run the unit tests you need to use either of the following commands:
	
For running the unit tests with PHPCS 3.x:
phpunit --bootstrap="./Test/phpcs3-bootstrap.php" --filter NeutronStandard /path/to/PHP_CodeSniffer/tests/AllTests.php
';

	die( 1 );
}

if ( '2' !== $phpcs_branch[0] ) {
	include __DIR__ . DIRECTORY_SEPARATOR . 'phpcs3-bootstrap.php';
}
