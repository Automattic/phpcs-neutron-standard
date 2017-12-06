# Coding standards for Neutron <a href="https://travis-ci.org/Automattic/phpcs-neutron-standard"><img src="https://img.shields.io/travis/Automattic/phpcs-neutron-standard/master.svg" alt="Build status" /></a>

The key words "MUST", "MUST NOT", "SHOULD", "SHOULD NOT", and "MAY" in this document are to be interpreted as described in [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

## Linting

**New PHP files MUST pass a linter (ruleset to be determined) before being committed. Linting exceptions are allowed, but should be explicitly stated using linter comments.**

**New code in existing PHP files MUST pass a linter (ruleset to be determined) before being committed. Linting exceptions are allowed, but should be explicitly stated using linter comments.**

**Existing code MAY break linter rules.**

Because [the WordPress coding standards use phpcs](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards), we suggest using [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) for this purpose.

Groups of phpcs standards can be easily installed on a project by using [composer](https://getcomposer.org/) and [phpcodesniffer-composer-installer](https://github.com/DealerDirect/phpcodesniffer-composer-installer).

## Strict types

**New PHP files MUST include a strict types directive.**

The directive looks like this: `declare( strict_types=1 );`

This prevents automatic scalar type conversion when validating types (so-called "weak typing"). You can [read about this directive here](http://php.net/manual/en/migration70.new-features.php#migration70.new-features.scalar-type-declarations).

## Extract

**New code MUST NOT use the extract function.**

Using `extract()` declares variables without declaration statements which obfuscates where those variables are defined.

## Magic methods

**New code MUST NOT use the magic methods __get, __set, or __serialize.**

**New code SHOULD NOT use the magic methods __invoke, __call, or __callStatic.**

Magic methods can hide behavior which can mislead future developers. Traditional getters and setters are usually easier to understand.

We allow the possibility of legitimate use-cases of the "callable" magic methods which can be used to increase readability without increasing complexity. This is subjective and must be considered on a per-case basis.

## Global functions

**New code MUST NOT add functions to the global namespace.**

Global functions risk causing namespace collisions, and thus require explicit namespacing in the function name itself. Since the language supports namespaces for organization, we can use them to increase readability and reduce that risk.

The remaining advantage of global functions is portability across a wide codebase that may require versions of PHP which did not support namespaces. This should not be an issue for payments code since its public API should be only used by non-Jetpack methods or over HTTP.

## Static methods

**New code MUST NOT introduce non-pure static methods. For this purpose, a pure method always returns the same result for the same arguments with no side effects.**

Side effects include any database access (even read access), network requests, console output, writing files, changing global variables, sending an email, or writing to IRC or Slack.

Static methods are typically called directly, like `MyClass::myMethod()`, which is effectively a namespaced global function call. This means that the code which calls the static function has an implicit and tightly-coupled dependency on the static function and its class. It is implicit because it may not be obvious to the developer using the code, and it is tightly coupled because it cannot be mocked.

For example, if a static method sends a message to Slack or makes a database update, a test for the calling code has no direct mechanism to prevent that Slack message or the database call.

If the static method is pure, the same dependency exists, but it becomes an implementation detail related to the data in the calling code, reducing the risks.

## Namespace functions

**New code MUST NOT introduce non-pure namespace functions outside of a class. For this purpose, a pure method always returns the same result for the same arguments with no side effects.**

Namespace functions, while greatly reducing the risk of collisions, are still a form of global function call. Using them therefore carries the same risks as using global functions or static methods. See the section on Static methods for why this can create unnecessary risk.

## Functions with side effects

**New code MUST NOT call non-pure functions that are not methods of an object except within classes whose specific purpose are to make side effects. For this purpose, a pure method always returns the same result for the same arguments with no side effects.**

Side effects include any database access (even read access), network requests, console output, writing files, changing global variables, sending an email, or writing to IRC or Slack.

To be more specific, a function should not make a call like `update_database()` directly. Instead it should call `$this->database->update_database()`. In this example, `$this->database` should be an instance of a class whose sole purpose is to update a database. `$this->database` should be injected into the class or function which uses it.

Calling a function with side effects creates an implicit and tightly-coupled dependency on that function. It is implicit because it may not be obvious to the developer using the code, and it is tightly coupled because it cannot be mocked.

For example, if a function sends a message to Slack or makes a database update, a test for the calling code has no direct mechanism to prevent that Slack message or the database call.

On the other hand, if the code being tested is explicitly in a class whose sole purpose is to send messages or update a database, the risk is reduced. In this situation, special measures can be taken to mock the side effects, or they can just be assumed to be correct. Such classes should have as little business logic as possible in order to reduce the need for automated testing.

If the static method is pure, the same dependency exists, but it becomes an implementation detail related to the data in the calling code, reducing the risks.

## Global variables

**New code MUST NOT use global variables except within classes whose specific purpose are to make side effects.**

Global variables create implicit and tightly coupled dependencies on arbitrary data. These dependencies cannot be easily mocked and may not be obvious to someone using the code.

On the other hand, if the code being tested is explicitly in a class whose sole purpose is to use a global variable, the risk is reduced. In this situation, special measures can be taken to mock the side effects, or they can just be assumed to be correct. Such classes should have as little business logic as possible in order to reduce the need for automated testing.

## Explicit Types

**New code SHOULD use argument and return types when possible.**

Strong types allow the compiler to spot errors when PHP code is being executed, preventing subtle bugs by making usage explicit. It also allows the compiler to optimize execution of function calls. Finally, it aids in function design by exposing implicit assumptions about the data being passed between parts of a code base and making them explicit. This can help future developers (or our future selves) from mistaking these assumptions.

This is complicated by return types which can be multiple values. For example, some functions can return `WP_Error` or `string`.

This could be solved by using a wrapper class at the expense of additional complexity when using the value. One possible implementation of this is the `Maybe` [described in this post](http://payton.codes/2017/11/11/maybe-returning-errors-in-php/) (this concept is [more often called a `Result` in functional programming](https://doc.rust-lang.org/std/result/enum.Result.html)).

## Constants

**New code MUST NOT use the define keyword.**

Often using the `define` keyword is actually just a way of global variables, except worse because they cannot be changed for testing without special hacks. Any conditions which rely on those constants cannot be tested.

Replacing strings, magic numbers, etc., that are easy to misspell or confuse is a valid use of constants. Using the `const` keyword for defining these within a class is a good way of making sure these are isolated and namespaced. Using `define` creates global constants, which can certainly be useful in PHP code, but it's rare that new ones need to be created.

The problem appears when trying to test code whose flow is dependent on these constants. For example:

```php
function doSomething() {
  if (FOOBAR) {
    doX();
  } else {
    doY();
  }
}
```

In this case it’s really hard to write tests for `doSomething()` because `FOOBAR` is being used as an implicit global variable. When using some testing architectures (like phpunit), it’s not possible to change a constant even between different tests (because they’re run in the same PHP process and constants, by definition, cannot change).

## Function naming

**New functions MUST begin with a verb.**

**New functions SHOULD have names which describe the purpose, arguments, and return value of the function as explicitly as possible.**

Start all functions with get..., is..., does..., update... etc. as appropriate. Ideally a function name will explain basically what arguments it requires and what it does or what it returns. This is not always possible, but if you find it hard to craft an appropriate name it might be a sign that the function does too much and should be split.

For example, consider a function which handles a WordPress shortcode (the second argument of the `add_shortcode()` function). Assuming the shortcode is `foobar`, then what should we call the handler function?

We might call it `foobar_shortcode`, but that doesn't really say what it does.

`process_foobar_shortcode` is better, but still ambiguous about what the function returns.

`get_markup_from_foobar_shortcode` is great because it tells us what the input and the output will be and what the function does.

## Function size

**New functions SHOULD be fewer than 20 lines, excluding comments and whitespace.**

Long functions contain code that cannot be seen all at once, and therefore often require scrolling up and down in order to follow the flow of execution. In this way they tend to resemble a program itself, and can hide bugs using the same patterns that hide bugs in code which does not use functions at all.

For one example, a 50-line function which uses the value of the variable `$foo` on line 35 means that a developer might need to scroll up to line 1 to find its definition. If the definition is changed or removed, or the variable modified between the definition and usage, bugs can easily appear. This can be helped by using linters, but readability is still sacrificed.

The code in functions naturally will grow over time, but as it does so it is important to reconsider if any of the function's parts deserve to be moved into their own functions. Helper functions or private methods are excellent ways to do this.

## Array functions

**New code SHOULD use PHP array functions when possible to clarify the purpose of a loop.**

Using `foreach` is convenient, but can hide the purpose of a loop. This is subjective, and so it's hard to have a hard rule, but in general it's worth considering what you actually want to do with a loop and see if it's possible to make its meaning explicit by using an array function.

If a loop does multiple things, then we must consider if it's worthwhile to split it into multiple loops, each which only do one thing. This might at first seem less efficient, but in many cases the arrays in question are quite small and multiple loops will increase readability much more than they will affect performance.

So why does `foreach` exist in the first place? In olden times program execution flowed from one statement to the next. To repeat a block of statements more than once you’d just fiddle with the program counter manually using goto, and this was the way of the world.

The problem with goto is that it is a blunt instrument. It masks intent. The semantics of goto are “jump to line N and continue with the current state”. But in practice we don’t often really want to jump to an arbitrary line. Maybe we want to branch if some condition is met, or repeat a block of code a fixed number of times, or abandon a block of code if some condition is met. Thus control structures like if, while, switch, try/catch, and foreach were born. They provide a more precise vocabulary for expressing intent. Which, as a bonus, is easier to optimize, since the compiler/interpreter can make stronger assumptions about what the program means.

Specifically, foreach expresses the intent “repeat this block of code for each entry of an array”. That sounds a lot like what array map and reduce do except that they are more precise. The intent of array_map is “apply this function to each item in an array and preserve the array shape”, while the intent of reduce is “consume the items in this array to get a summary value”.

## Side effects in files

**New code MUST NOT have side effects in class constructors.**

**New PHP files MUST NOT have side effects outside of a function.**

Side effects include any database access (even read access), network requests, console output, writing files, changing global variables, sending an email, or writing to IRC or Slack.

Class constructors are meant to initialize a new object, setting default values and preparing any data which was passed in as a dependency. They also happen to be a "free" function call which happens when the class is instantiated, which means that when a class's purpose is simple, they're often used to start doing what that class is designed to do.

Because class constructors are typically called explicitly with the `new` keyword and the class name, this latter pattern is effectively using the class constructor as a global or static function. If the function has side effects, we create implicit and tightly coupled dependencies between the instantiating code and the side effects.

When writing tests, we might want to avoid side effects (like Slack messages or database writing) but if they are in a constructor we may not know about them, and we may not be able to mock them. Even worse, any code which creates the class will probably not expect them either.

If a class has only one purpose, it's best to create a single instance method, like `run()` or `doSomething()` (ideally one that actually describes the purpose of the function) and use that to activate the side effects.

Even more challenging than side effects in a constructor is when just requiring a PHP file performs side effects. This is almost always unexpected and can be a real challenge when testing. File imports should be a totally pure operation.

## Array shorthand

**New code MUST NOT use `array()` to create array primitives; use the `[]` shorthand syntax instead.**

This is just for the sake of consistency. The shorthand is fewer characters to type and is commonly used outside of WordPress code that must support PHP < 5.4.

## Yoda conditions

**New code MAY use Yoda conditions, but it is not required.**

**New code MUST NOT use an assignment inside a condition without also including an explicit comparison operator.**

This replaces the [Yoda conditions rule](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#yoda-conditions) of the WordPress coding guidelines.

Yoda conditions are not as natural to write, and in many cases are unnecessary to protect against accidental assignment. A more accurate protection is to require explicit comparisons inside conditional statements.

In PHP, an assignment expression evaluates to the the value of the variable being assigned. Secondly, a condition expression without an explicit comparator checks for the "truthiness" of the expression. This allows for the common pattern of:

```php
if ($foo = getFoo())
```

This pattern is a shortcut for:

```php
$foo = getFoo();
if ($foo == true)
```

There is nothing wrong with this shortcut, but Yoda conditions were introduced to protect against the case where this was actually intended to be:

```php
if($foo == getFoo())
```

To solve this problem and still allow the shortcut, we can require that any condition that has an assignment must also have a comparator. This makes the shortcut explicit and can easily be checked by a linter. So the above shortcut must now be written as:

```php
if(($foo = getFoo()) == true)
```

## Inheritance

**New code SHOULD NOT use inheritance when sharing methods is the only purpose.**

**New code SHOULD limit the depth of inheritance to no more than one level.**

Class inheritance is a mechanism for sharing code, but it also implies identity. If the purpose of inheritance is just to bring in helper functions or pure functions, then inheritance is probably not the best method. In these cases, the saying "composition over inheritance" becomes relevant; it's possible to make external functions available to a class by injecting instances of other classes.

In the case of PHP, this applies both to direct inheritance with the `extends` keyword and mixin inheritance with the `trait` and `use` keywords.

This is not to say that inheritance is bad. It's just that it's easy to reach for it as a code sharing mechanism when it is not appropriate.

When inheritance is appropriately used, it's important to use it carefully because it can greatly increase the cognitive load of reading the code.

If a class inherits from another class, which itself inherits from a third class, then it starts to become hard to keep track of behavior and where that behavior is defined.

For example, if you were to read the following class, could you guess where the method `getData()` is defined?

```php
class MyClass extends ClassC {
  use ClassB;
  public function doSomething() {
    echo $this->getData();
  }
}
```

It could be either `ClassC` or `ClassB`. But what if `ClassC` inherits from `ClassA`? In that case it could be in `ClassA`. But that's only if `ClassC` does not override the method; and if it does override the method and calls `parent::getData`, then we have to consider `ClassA` anyway.

Occasionally this sort of inheritance is necessary, but more often it is a form of coupling classes together that will cause problems later. In effect, calling an inherited method is a form of tight coupling because the method creates a dependency between two classes that cannot be easily mocked.

## Passing IDs

**New code SHOULD pass complete objects rather than database IDs as function arguments.**

A common WordPress pattern is to pass a site ID or a user ID into a function that manipulates site or user data; then that function will query the database to get the data it needs to use.

The problem with this pattern is that it turns what might be a pure data-manipulation function into a non-pure function with a dependency on the database. Pure functions are much easier to test, easier to move around, and often easier to understand.

It's often possible to fetch the data from the database first (possibly in a separate function) and then inject the entire object into the function that manipulates it. This makes it so that the functions each do only one thing, increasing readability and decreasing dependencies.

## Composition Roots

**New code SHOULD NOT instantiate objects except in classes whose sole purpose is to instantiate classes (so-called "factories").**

**New code SHOULD instantiate objects in as few places as possible.**

Dependency injection of classes means creating instances outside of the class that needs it, and passing the instance in as an argument. This decouples the class from its dependencies by moving the responsibility up one level. In the case of classes using classes which use other classes, this becomes a tree of dependency injection.

At the top of a such a tree of injection is a "root" class which kicks off the whole chain. This class must create all the dependencies used by the functions it calls. This is called the "composition root". In real-world code there is often multiple such roots and sometimes the distinction isn't entirely clear, but attempting to keep the number of roots as small as possible makes it easier to alter dependencies and to find where classes are defined.

When a class is instantiated multiple places, it must have all its dependencies provided in each of those places. If one of those dependencies changes, or a new one is added, it means finding all the places where the instances are created and changing them all. This is risky and time-consuming.

Instead, if a single function is used to instantiate a class (typically a static function called a "factory"), then it becomes possible to just make the change in one place. If a new configuration of dependencies is desired, it's possible to just create a new factory.

## Newlines

**New code MUST NOT have more than one adjacent blank line.**

Whitespace is useful for separating logical sections of code, but excess whitespace takes up too much of the screen. One empty line is usually sufficient to separate sections of code.

## Variable Functions

**New code SHOULD NOT call Variable Functions.**

**New code MUST NOT use call_user_func or call_user_func_array.**

Having variable function names prevents easily tracing the usage and definition of a function. If a function signature needs to be changed or removed, for example, a developer would typically search the code base for uses of that function name. With variable functions, a function name could be created by joining strings together or otherwise manipulating a string, making it nearly impossible to find that use. Even if the string is unmodified, it may be defined somewhere far away from the place where it is called, again making it hard to trace its use. Lastly, with a function name as a string, it's possible for the string to be accidentally modified or to be set to something unexpected, potentially causing a fatal error.

Instead, we can use a mapping function to transform a string into a hard-coded function call. For example, here are three ways to call the function stored in `$myFunction`; notice how the third option actually has the function name in the code where it is called.

```php
call_user_func($myFunction, 'hello');
```

```php
$myFunction('hello');
```

```php
switch($myFunction) {
  case 'speak':
    speak('hello');
    break;
}
```
