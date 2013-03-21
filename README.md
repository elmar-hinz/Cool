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

[x] Autowiring
[x] Singletons
[x] Services
[ ] Hooks
[x] Modularization
[x] Autoloading

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

Autowiring is triggert by the call to `getInstance($classname)` 
of the container.  It is based on the signature of the constructor. 
For each parameter a classname has to be declared. Else it fails.
It tries to to create an instance for each parameter 
by a recursive call to `getInstance`.

A later version of Cool will give the option to select subclasses
for a paramter interface in the configuration. 

If getInstance() doesn't do what you want, you can still fall back
to the classical call to `new` or implent your own factory.

For sure ´getInstance´ supports the singleton management of the container.

Singletons
==========

By implementing the interface `\Cool\Singleton` an object is managed as 
Singleton by the container, as long as it called by the conatainer
methods `getInstance`, `getService` or `getHook`.

Services
========

A service is a class, that implements a service interface.
A service interface must extend `\Cool\Service`.

The interface `\Cool\Service` has one method `canServe($mixedCriteria)`. 
This is a **static** method, a class method. 

When the conatainer is called by `getService($serviceType , $mixedCriteria)'
it asks the classes of the requested service types if they can
answer to service request, until the first one answers with TRUE.
That is instanciated to handle the request. 

The instantiation is done by `getInstance` again. That means that
a service must provide a construtor that satisfies the criteria of
`getInstance`.

It depends on the service type, what it uses as $mixedCriteria and how
it decides to answer with TRUE or FALSE based on this criteria.

Hooks
=====

Not implemented yet.

Modularization
==============




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

