<?php
if ( ! defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
	define( 'PHP_CODESNIFFER_IN_TESTS', true );
}

$ds = DIRECTORY_SEPARATOR;

// Get the PHPCS dir from an environment variable.
$phpcsDir = getenv( 'PHPCS_DIR' );

// This may be a Composer install.
if ( false === $phpcsDir && is_dir( dirname( __DIR__ ) . $ds . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer' ) ) {
	$phpcsDir = dirname( __DIR__ ) . $ds . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer';
} elseif ( false !== $phpcsDir ) {
	$phpcsDir = realpath( $phpcsDir );
}

// Try and load the PHPCS autoloader.
if ( false !== $phpcsDir && file_exists( $phpcsDir . $ds . 'autoload.php' ) ) {
	require_once $phpcsDir . $ds . 'autoload.php';

	/*
	 * As of PHPCS 3.1, PHPCS support PHPUnit 6.x, but needs a bootstrap, so
	 * load it if it's available.
	 */
	if ( file_exists( $phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php' ) ) {
		require_once $phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php';
	}
} else {
	echo 'Uh oh... can\'t find PHPCS. Are you sure you are using PHPCS 3.x ?

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.
';

	die( 1 );
}

unset( $ds, $phpcsDir );
