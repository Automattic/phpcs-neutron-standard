<?php

function doSomething($arg1, $arg2, $arg3) {
}

$funcName = 'doSomething';
// Next line should report variable functions are not allowed
$funcName('foo', 'bar', 'baz');
$args = ['foo', 'bar', 'baz'];
// Next line should report variable functions are not allowed
$funcName(...$args);
doSomething('foo', 'bar', 'baz');
doSomething(...$args);
