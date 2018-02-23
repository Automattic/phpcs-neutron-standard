<?php

function foo($arg1) {

	echo $arg1;
}

// Previous line should report no multiple newlines
function bar() {
	foo();

	$val1 = 'baz';

	// Previous line should report no multiple newlines
	$val2 = $val1 . 'foo';
	foo($val2);

	// Previous line should report no multiple newlines
}
