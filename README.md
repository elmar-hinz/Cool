======================================
Cool - a tiny autowiring PHP framework
======================================

 * Author: Elmar Hinz <t3elmar@googlemail.com>
 * Homepage: 
 * Version: 0.x.x
 * Stability: Alpha/Usable
 * License: MIT
 * Last update: See [ChangeLog](https://github.com/t3elmar/Cool/blob/master/ChangeLog)
 * OS: tested on POSIX i.e. Mac OS X, Unix, Linux, BSD
 * Dependencies: 
   * php > 5.3
   * git (recommended for installation)
 * The dependencies ship under their own licenses and are not part of this program
	
Features
========

-	[x] Autowiring
-	[x] Singletons
-	[x] Services
-	[ ] Hooks
-	[x] Modularization
-	[x] Autoloading

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

For each parameter a classname has to be declared. Else it fails.
It tries to to create an instance for each parameter 
by a recursive call to `getInstance`.

If `getInstance()` doesn't do what you want, you can still fall back
to the static call to `new` or implement your own factory.

For sure ´getInstance´ supports the singleton management of the container.

A later version of Cool will give the option to select the actual class
to use for a paramter interface by configuration. That will be the moment
it becomes real dependency injection, which real strength is flexibility, 
not lazyness. 

Singletons
==========

By implementing the interface `\Cool\Singleton` an object is managed as 
Singleton by the container, as long as it called by the conatainer
methods `getInstance`, `getService` or `getHook`.

Services
========

A service is a class, that implements a service interface.
This interface defines the type of the service and makes the class 
a service at all. The interface must extend `\Cool\Service`.

The interface `\Cool\Service` has one method `canServe($mixedCriteria)`. 
This is a **static** method, a class method. 

When the conatainer is called by `getService($serviceType , $mixedCriteria)'
it asks the classes of the requested service types if they can
answer to service request, until the first one answers with TRUE.
That is instantiated to handle the request. 

The instantiation is done by `getInstance` again. That means that
a service must provide a construtor that satisfies the criteria of
`getInstance`.

It depends on the service type, what it uses as $mixedCriteria and how
it decides to answer with TRUE or FALSE based on this criteria.

Services are autoregistered. They must stay in a module directory
named `Services/` to work. 


Hooks
=====

Not implemented yet.

Modularization
==============

A module is created by putting a directory into `Modules`.

	Cool/Modules/MyModule/

The Module hierarchy is flat. If you want to introduce hierarchy,
you have to do this in Form of the name. 

	Cool/Modules/MyCompanyMySuperModule/

The subdirectories `Interfaces`, `Classes`, `Hooks` and `Services`
are the places where the autoloader is looking for. If your pathes
differ from this, you have to provide your own loading mechanism.

	MyModule/Interfaces/
	MyModule/Classes/
	MyModule/Hooks/
	MyModule/Services/

Hooks and Services must stay in their matching directory to 
be autoregistered.

	MyModule/Hooks/
	MyModule/Services/

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
* Put a license in your module.
* When you call code from other moduels, adhere their licences.
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

TODO
====

* Improve documentation
* Improve documentation of the code
* More unit testing

