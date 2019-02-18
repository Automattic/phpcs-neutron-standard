# Neutron PHP Standard <a href="https://travis-ci.org/Automattic/phpcs-neutron-standard"><img src="https://img.shields.io/travis/Automattic/phpcs-neutron-standard/master.svg" alt="Build status" /></a>

These are a set of modern (PHP >7) linting guidelines meant to be applied in addition to the [the WordPress coding standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) for WordPress development. Because of the newer PHP version, it is not suitable for work on Core WordPress, but may be useful for those who are not bound by PHP 5.2.

These guidelines are being developed primarily for a team within [Automattic](https://automattic.com/), but anyone is free to use them, suggest changes, or report bugs.

This project is a [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) "standard" (a collection of rules or "sniffs") that can be included in any project.

## Installation

To use these rules in a project which is set up using [composer](https://href.li/?https://getcomposer.org/), we recommend using the [phpcodesniffer-composer-installer library](https://href.li/?https://github.com/DealerDirect/phpcodesniffer-composer-installer) which will automatically use all installed standards in the current project with the composer type `phpcodesniffer-standard` when you run phpcs.

```
composer require --dev squizlabs/php_codesniffer dealerdirect/phpcodesniffer-composer-installer
composer require --dev automattic/phpcs-neutron-standard
```

If you want this standard, the WordPress standard, the VariableAnalysis standard, and other customizations, you can install the meta-standard [NeutronRuleset](https://github.com/Automattic/phpcs-neutron-ruleset) instead.

```
composer require --dev squizlabs/php_codesniffer dealerdirect/phpcodesniffer-composer-installer
composer require --dev automattic/phpcs-neutron-ruleset
```

## Configuration

When installing sniff standards in a project, you edit a `phpcs.xml` file with the `rule` tag inside the `ruleset` tag. The `ref` attribute of that tag should specify a standard, category, sniff, or error code to enable. It’s also possible to use these tags to disable or modify certain rules. The [official annotated file](https://href.li/?https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-ruleset.xml) explains how to do this.

```xml
<?xml version="1.0"?>
<ruleset name="MyStandard">
 <description>My library.</description>
 <rule ref="NeutronStandard"/>
</ruleset>
```

## Usage

Most editors have a phpcs plugin available, but you can also run phpcs manually. To run phpcs on a file in your project, just use the command-line as follows (the `-s` causes the sniff code to be shown, which is very important for learning about an error).

```
vendor/bin/phpcs -s src/MyProject/MyClass.php 
```

-----

# Guidelines

View the Guidelines [here](./GUIDELINES.md).
