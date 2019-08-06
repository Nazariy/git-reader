# git-reader
Library to containing helpers to provide information about GIT repository like commits, branches and contributors

##Usage

```php
use GitReader\Repository;

$repository = new Repository('/path/to/repository');
```

```php 
$branches = $reporitory->getBranches();

/** Results
array (size=3)
  0 => 
    array (size=4)
      'hash' => string '49e0902be200062cd89abefd730fd945ff1082be' (length=40)
      'type' => string 'heads' (length=5)
      'path' => string 'refs/heads/master' (length=17)
      'name' => string 'master' (length=6)
  1 => 
    array (size=4)
      'hash' => string '49e0902be200062cd89abefd730fd945ff1082be' (length=40)
      'type' => string 'remotes' (length=7)
      'path' => string 'refs/remotes/origin/HEAD' (length=24)
      'name' => string 'origin/HEAD' (length=11)
  2 => 
    array (size=4)
      'hash' => string '49e0902be200062cd89abefd730fd945ff1082be' (length=40)
      'type' => string 'remotes' (length=7)
      'path' => string 'refs/remotes/origin/master' (length=26)
      'name' => string 'origin/master' (length=13)
*/
```

```php
$contributors = $repository->getContributors();

/** Results
array (size=1)
  0 => 
    array (size=3)
      'name' => string 'Nazariy Slyusarchuk' (length=19)
      'email' => string 'Nazariy@users.noreply.github.com' (length=32)
      'commits' => int 1
*/
```

```php
$stats = $instance->getShortStatGraph();
/**
array (size=1)
  0 => 
    array (size=8)
      'author' => string 'Nazariy Slyusarchuk <Nazariy@users.noreply.github.com>' (length=54)
      'author_email' => string 'Nazariy@users.noreply.github.com' (length=32)
      'author_name' => string 'Slyusarchuk' (length=11)
      'changes' => 
        array (size=1)
          0 => string 'Initial commit' (length=14)
      'comment' => string 'Initial commit' (length=14)
      'date' => 
        object(DateTime)[6]
          public 'date' => string '2019-08-06 17:37:33.000000' (length=26)
          public 'timezone_type' => int 1
          public 'timezone' => string '+03:00' (length=6)
      'hash' => string 'f8759da2d82889b22278723148525dde3087e6db' (length=40)
      'stats' => string '2 files changed, 23 insertions(+)' (length=33)
*/
```