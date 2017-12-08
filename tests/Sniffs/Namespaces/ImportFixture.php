<?php
use My\Full\Classname;
use My\Full\ClassnameTwo as AnotherClass;
// The following line should report an unused import
use My\Full\UnusedClass;
// The following line should report an unused import
use My\Full\UnusedClassTwo as AnotherUnusedClass;
use function My\Full\functionName;
use function My\Full\functionName as anotherFunction;
// The following line should report an unused import
use function My\Full\unusedFunctionName;
// The following line should report an unused import
use function My\Full\unusedFunctionNameTwo as anotherUnusedFunction;
use const My\Full\MY_CONST;
// The following line should report an unused import
use const My\Full\UNUSED_CONST;
use First;
// The following line should report an unused import
use Second;

new Classname();
new AnotherClass();
functionName();
anotherFunction(MY_CONST);
anotherFunction('UNUSED_CONST');
new \Third\Second\First();
