<?php

// Next line should report no define allowed
define("FOO", "bar");

function test() {
	// Next line should report no define allowed
	define("FOO", "bar");
}
