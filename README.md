======================================
Cool - a tiny autowiring PHP framework
======================================

 * Author: Elmar Hinz <t3elmar@googlemail.com>
 * Version: 0.x.x
 * Stability: Alpha/Usable
 * License: MIT
 * Last update: See [ChangeLog](https://github.com/t3elmar/Cool/blob/master/ChangeLog)
 * Homepage: http://t3elmar.github.com/Cool/
 * Github: https://github.com/t3elmar/Cool/
 * OS: tested on POSIX i.e. Mac OS X, Unix, Linux, BSD
 * Dependencies: 
   * php > 5.3.3
   * git (recommended for installation)
 * The dependencies ship under their own licenses and are not part of this program
	
Features
========

- [x] Autowiring
- [x] Singletons
- [x] Services
- [x] Signals, Receivers, Hooks
- [x] Modularization
- [x] Autoloading

Installation
============

	git clone git@github.com:t3elmar/Cool.git

Autowiring - Dependency injection for dummies
=============================================

```php
class People { ... }
class Beer   { ... }
class Music  { ... } 

class Party {
	function __construct(People $people, Beer $beer, Music $music) {
		...
	}
}

$container = new \Cool\Container();
$party = $container->getInstance('Party');

// And the party is up and running ...
```

Autowiring, the details
-----------------------

Autowiring is triggert by a call to `getInstance($classname)` 
of the container.  It is based on the signature of the constructor. 

For each parameter in the construtor definition a classname has 
to be specified. Else it doesn't works and an error is thrown. 
It tries to to create an instance for each parameter 
by a recursive call to `getInstance`.

If `getInstance()` doesn't do what you want, you can still fall back
to the static call to `new` or implement your own factory.

### Out-look ###

> The recursive auto-instantiation of objects is a nice feature,
> but not the definition and strength of dependency injection.
> The strenght is flexibility. Future versions will provide mechanism,
> to replace classes (or interfaces in general) by subclasses 
> through configuration.

Singletons
==========

By implementing the interface `\Cool\Singleton` an object is managed as 
Singleton by the container, as long as it called by the conatainer
methods `getInstance` or `getService`.

```php
class SantaClaus implements \Cool\Singleton { ... }
```

The interface itself is empty. It serves as a flag.

Services
========

A service is a class, that implements a service interface.

```php
class PizzaCourier implements PizzaService { ... }
```

This interface defines the type of the service. 
It must extend `\Cool\Service`.

```php
interface PizzaService extends \Cool\Service { ... }
```

Interface `\Cool\Service` has one class method `canServe($mixedCriteria)`.

When it's method `getService($serviceType , $mixedCriteria)` is called,
the container asks all classes of the given service type, if they 
could serve the request. The first service that answers with TRUE 
is the winner. It is instantiated and returned ready to do the job. 

### Out-look ###
> This is the most simple algorythm to find the winnig service, but it's 
> simplicity by intention. Inject your own finders in future versions.

```php
// ... Concierge get me a pizza service! 
// ... We have a surprising monday party and the fridge is empty.

$pizzaService = $container->getService(
				'MyModule\PizzaService', array('dayOfWeek' => 'monday'));

//  Concierge wiring some pizza services, 
//  all closed on monday, until suddenly ...

class PizzaCourier implements PizzaService {  
	// ...
	static public function canServe($mixedCriteria) { 
		return ($mixedCriteria['dayOfWeek'] != 'tuesday');
	}
	// ...
}
```

### Hint ###

> It depends on the service type, what `canServe` uses as $mixedCriteria 
> and how it evaluates its answer. If you don't set a stricter type for
> $mixedCriteria in the interface definition,  you should at least 
> document it in that place.

### Hint ###

> canServe is a **static** method, a **class method**.
>
> To get inheritance work with static methods and variables,
> **late static bindings** are your friends:
> http://www.php.net/manual/en/language.oop5.late-static-bindings.php

Services are autoregistered. They must stay in a module directory
named `Services/` to work, while the interface definition belongs 
into `Interfaces/`.

	MyModule/Interfaces/PizzaService.php
	MyModule/Services/PizzaCourier.php

### Hint ###

> Instantiation is delegated to `getInstance`. That means that a service 
> must provide a construtor that satisfies the criteria of `getInstance`.

Signals, Receivers, Hooks
==========================

Signals are messesages, that are send at certain events in the code,
to inform others about them. They are typically targeted to be used 
by other modules. Hence, they are designed to support the other 
developers as far as possible.

### 1.) Visibility 

Signals are declared as classes in the module directory `Signals` to give them visibiltiy.

### 2.) Documentation 

The very same place is targeted to document them.

### 3.) Augmentation 

Signals may be augmented by special getters to make them 
more easy and intuitive to use by the receivers.

Signals
-------

They create and broadcast themself, by extending the class `AbstractSignal`,
that itself implementes the interface `\Cool\Signal`. 

```php
class MySignal extends \Cool\AbstractSignal { ... }
```
Signals are called by the static message send.  

There is nothing like signal injection, because signals are strongly
related to the class they are send by.

```php
MySignal::send($this, $optionallyMixedData);
```

The first parameter of `send` is the sending object. 
The second parameter is optionally any data that is expected by the signal.

```php
class MySpecialSignal extends \Cool\AbstractSignal { 

	/**
	* @param object the sender
	* @param \SpecialClass the special data
	*/
	static function send($sender, $specialObject) {
		assert($specialObject instanceOf \SpecialClass);
		parent::send($sender, $specialObject);
	}

	/**
	* @return \SpecialClass the special data
	*/
	function getSpecialObject() {
		return $this->getData();
	}
}
```

The method `send` can be specialized and special getters can by added.

The sender can by accessed by the method `getSender()`.

Receivers
---------

Receivers implement the interface `\Cool\Receivers`. They stay in the
module directory `Receivers`. The static method `listensTo` returns
the implementation of `\Cool\Signal` to listen to. That is all to
be done to register a receiver.

It's up to the receiver what to do with the signal.

```php
class MyReceiver implements \Cool\Receiver { 

	static functions listensTo() { return 'MySignal'; }

	/**
	* @param MySignal the expected signal
	*/
	function receive(\Cool\Signal $signal) { 
		assert($signal instanceOf self::listensTo());
		...  
	}

}
```

Using signals as hooks
----------------------

The static method `send` of the signal returns an iterator `\Cool\Receivers`
with all the listening receivers. By iterating the receivers and calling
methods on them the signalling mechanism can be used like a hook.

For this to work the listening receivers must provide the method that is
called by the sender, to hook them in.

```php
class MyClass { 

	function myFunction() { 
		$receivers = MySignal::send($this);
		foreach($receivers as $receiver) {
			$receiver->doSomthing();
		}
	}

}
```

It is a good idea to provide a hook interface defining the methods the
receiver must implement to be usable by this hook.

```php
	function myFunction() { 
		$receivers = MySignal::send($this);
		foreach($receivers as $receiver) {
			if(!$receiver isInstanceOf MyHookInterface) throw new Exception('invalid hook');
			$receiver->doSomthing();
		}
	}
```


Modularization
==============

A module is created by putting a directory into `Modules`.

	Cool/Modules/MyModule/

The Module hierarchy is flat. If you want to introduce hierarchy,
you have to do this in Form of the name. 

	Cool/Modules/MyCompanyMySuperModule/

The subdirectories `Interfaces`, `Classes`, `Services`, `Signals` 
and `Receivers` are the places where the autoloader is looking for. If your 
pathes differ from this, you have to provide your own loading mechanism.

	MyModule/Interfaces/
	MyModule/Classes/
	MyModule/Services/
	MyModule/Signals/
	MyModule/Receivers/

Services and Receivers must stay in their matching directory to 
be autoregistered.

	MyModule/Services/
	MyModule/Receivers/

Other recommended Directories

	MyModule/Configuration/
	MyModule/Executables/
	MyModule/Documentation/
	MyModule/Tests/

Coding guidelines
=================

The coding guidelines are recommendations. They don't differ from
the up-to-date mainstream:

* Use CamelCase as far as possible.
* Classnames start uppercase, functions and variables lowercase.
* Constants are all uppercase: TRUE, FALSE 
* Respect the security requirements of PHP programming
* Wrap all PHP code into classes, even configurations.
* Use namespaces.
* Write configurations as PHP array for now.
* Put a license into your module.
* When you call code from other modules, adhere their licences.
  They may not be as liberal as the Cool framework itself.


Goals of the design
===================

* Small footprint.
* Feature minimized. Features can be added by modules.
* Easy to get started.
* Human readable namings.
* Flat organization of module directories.
* Proofed by unit testing 
* MIT license, to let modules choose their own way 


Hello World
===========

Hello world as a service.

Setting up directories
----------------------

```sh
cd Cool/Modules/
mkdir HelloWorld/
cd HelloWorld/
mkdir Executables Configuration Interfaces Classes Services
```

GreeterService interface
------------------------
Path `Interfaces/GreeterService.php`:
```php
<?php namepace HelloWorld;
interface GreeterService extends \Cool\Service {
	// expects morning | evening| other string
	public static CanServe($timeOfDay);
	public greet($name);
}
?>
```

Greeter services
----------------
Path `Services/MorningGreeter.php`:
```php
<?php namepace HelloWorld;
class MorningGreeter implements GreeterService {
	static public CanServe($timeOfDay) { return $timeOfDay == 'morning'; }
	public greet($name) { print 'Good morning, '.$name.'!'; }
}
?>
```

Path `Services/EveningGreeter.php`:
```php
<?php namepace HelloWorld;
class EveningGreeter implements GreeterService {
	static public CanServe($timeOfDay) { return $timeOfDay == 'evening'; }
	public greet($name) { print 'Good evening, '.$name.'!'; }
}
?>
```
Path `Services/DefaultGreeter.php`:
```php
<?php namepace HelloWorld;
class DefaultGreeter implements GreeterService {
	static public canServe($timeOfDay) { 
		return $timeOfDay != 'evening' && $timeOfDay != 'morning'; }
	public greet($name) { print 'Hello '.$name.'!'; }
}
?>
```

The program
-----------
Path `Classes/HelloWorld.php`:

```php
<?php namepace HelloWorld;
class Program {
	private $container;

	// The container (a singleton) will inject itself. 
	public function __construct(\Cool\Container $container) {
		$this->container = $container;
	}

	public function go($name) {
		$timeOfDay = $this->getTimeOfDay();
		$container->getService('HelloWorld\GreeterService', $timeOfDay)->greet($name);
	}

	// returns a string: morning | evening | other
	public function getTimeOfDay() { ... }

}
?>
```

Configuration
-------------
Path `Configuration/Main.php`:
```php
<?php namepace HelloWorld;
class Main {
	static public main($argv) { 
		$moduleBase = __DIR__.'/../..';
		require_once($moduleBase.'/Cool/Classes/AutoLoader.php');
		$loader = new \Cool\AutoLoader();
		$loader->addModuleBase($moduleBase);
		$loader->go();
		$loader = new \Cool\DedicatedDirectoriesLoader();
		$loader->addModuleBase($moduleBase);
		$loader->go();
		$container = new \Cool\Container();
		$program = $container->getInstance('HelloWorld\Program');
		// the user provides his name as argument
		$program->go($argv[1]);
	}
}
?>
```

Executable
----------
Path `Executables/sayHello.sh`:
```php
#! /usr/bin/env php
<?php
require_once(__DIR__."/../Configuration/Main.php");
\HelloWorld\Main::main($argv);
?>
```
make it executable:
```sh
chmod +x Executables/sayHello.sh
```

TODO
====

* Signals and Receivers
* Add customized finders
* Improve documentation
* Improve documentation of the code
* More unit testing

