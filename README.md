# Autho
A lightweight library for handling authentication

Example of validation
```php
<?php
include 'mackilanu/Autho/src/Autho.php';

use Mackilanu\Autho\Autho;

$username = Autho::setUsername("foobar");
$password = Autho::setPassword("foobarbaz");
Autho::validate();

```
