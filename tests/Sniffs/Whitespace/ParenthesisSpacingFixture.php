<?php

function doSomething( $arg1 ) {
	doSomethingElse( $arg1 );
	doSomethingElse($arg1);
	doSomethingElse( $arg1);
	doSomethingElse($arg1 );
}

function doSomethingElse( $arg1 ) {
	if (! isset($arg1)) {
		$arg1 = 'go';
	}
	if (! isset( $arg1)) {
		$arg1 = 'go';
	}
	if (! isset($arg1 )) {
		$arg1 = 'go';
	}
	if ( ! isset($arg1 )) {
		$arg1 = 'go';
	}
	$val1 = ( $arg1 === 'go' ) ? 'foo' : 'bar';
	$val1 = ($arg1 === 'go') ? 'foo' : 'bar';
	$val1 = ( $arg1 === 'go') ? 'foo' : 'bar';
	$val1 = ($arg1 === 'go' ) ? 'foo' : 'bar';
	doManyArgs( $arg1, $val1, $val2 );
	doManyArgs($arg1, $val1, $val2);
	doManyArgs( $arg1, $val1, $val2);
	doManyArgs($arg1, $val1, $val2 );
}
