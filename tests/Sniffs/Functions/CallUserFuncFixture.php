<?php

function doSomething($arg1, $arg2, $arg3) {
}

// Next line should report call_user_func is not allowed
call_user_func('doSomething', 'foo', 'bar', 'baz');
$args = ['foo', 'bar', 'baz'];
// Next line should report call_user_func is not allowed
call_user_func_array('doSomething', $args);
doSomething('foo', 'bar', 'baz');
doSomething(...$args);
