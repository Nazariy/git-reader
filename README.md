# git-reader
Library to containing helpers to provide information about GIT repository like commits, branches and contributors

##Usage

```php
<?php

use GitReader\Repository;

$repository = new Repository('/path/to/repository');

// List Branches
$branches = $reporitory->getBranches();

/** Results
Array
(
    [0] => Array
        (
            [hash] => 584ce306cbc017f1d0a0b4d75a9c7cd005780d8c
            [type] => heads
            [path] => refs/heads/master
            [name] => master
        )

    [1] => Array
        (
            [hash] => 584ce306cbc017f1d0a0b4d75a9c7cd005780d8c
            [type] => remotes
            [path] => refs/remotes/origin/HEAD
            [name] => origin/HEAD
        )

    [2] => Array
        (
            [hash] => 584ce306cbc017f1d0a0b4d75a9c7cd005780d8c
            [type] => remotes
            [path] => refs/remotes/origin/master
            [name] => origin/master
        )

)
*/

// Show Contributors
$contributors = $repository->getContributors();

/** Results
Array
(
    [0] => Array
        (
            [name] => Nazariy Slyusarchuk
            [email] => Nazariy@users.noreply.github.com
            [commits] => 4
        )

)

// Show Stats
$stats = $instance->getShortStatGraph();

/**
Array
(
    [0] => Array
        (
            [author] => Nazariy Slyusarchuk <Nazariy@users.noreply.github.com>
            [author_email] => Nazariy@users.noreply.github.com
            [author_name] => Slyusarchuk
            [changes] => Array
                (
                    [0] => Update README.md
                )

            [comment] => Update README.md
            [date] => DateTime Object
                (
                    [date] => 2019-08-06 18:09:49.000000
                    [timezone_type] => 1
                    [timezone] => +03:00
                )

            [hash] => 584ce306cbc017f1d0a0b4d75a9c7cd005780d8c
            [stats] => 1 file changed, 3 insertions(+), 2 deletions(-)
        )

    [1] => Array
        (
            [author] => Nazariy Slyusarchuk <Nazariy@users.noreply.github.com>
            [author_email] => Nazariy@users.noreply.github.com
            [author_name] => Slyusarchuk
            [changes] => Array
                (
                    [0] => Update README.md
                )

            [comment] => Update README.md
            [date] => DateTime Object
                (
                    [date] => 2019-08-06 18:08:21.000000
                    [timezone_type] => 1
                    [timezone] => +03:00
                )

            [hash] => 8b9d42871cdaa34efc613bc9f62613870f0996a6
            [stats] => 1 file changed, 2 insertions(+), 1 deletion(-)
        )
)
*/
```
