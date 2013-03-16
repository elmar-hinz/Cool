
CAUTION
=======

For now this is pure experimental.

Autowiring - Dependency injection for dummies
---------------------------------------------

::

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










