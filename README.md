# FileBank module for Zend Framework 2

This module provides a way to store files in a structured database consisting of folders and database.

Requirements:

- PHP 5.3
- Zend Framework 2
- Doctrine 2
- GD

See [https://github.com/artur-gajewski/FileBank](https://github.com/artur-gajewski/FileBank)

@Author: Artur Gajewski


## Installation

Go to your vendor directory and clone the module from Github:

```php
git clone https://github.com/artur-gajewski/FileBank
```

Then add 'FileBank' into the Module array in APPLICATION_ROOT/config/application.config.php

```php
<?php
return array(
    'modules' => array(
        ...
        'FileBank',
        ...
    ),
);
```
Next, create a new table in your application's MySQL database:

```php
CREATE TABLE filebank (
  id int(11) NOT NULL auto_increment,
  name varchar(250) NOT NULL,
  size int(11) NOT NULL,
  mimetype varchar(250) NOT NULL,
  isactive int(11) NOT NULL,
  PRIMARY KEY (id)
);
```


## Configuration of Doctrine and FileBank parameters

FileBank module uses Doctrine to access a database table specified for it. FileBank saves file information into database and functions are provided to utilize this information at any time.

The FileBank module package includes its own configuration in FileBank/config/module.config.php, but you should never touch any configurations found in modules installed under vendor directory.

Instead, if you want to override any configuration parameters provided with FileBank module, you should create a new configuration file APPLICATION_ROOT/config/autoload/FileBank.global.php

```php
<?php
return array(
    ...
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'username',
                    'password' => 'password',
                    'dbname'   => 'database_name',
                )
            )
        ),
        ...
```

This will override the default settings of provided package with your own settings.

You can also modify default parameter settings that are provided with the package
so that they would suit your needs. Such parameters are:

- fileBankFolder = filebank root folder where the files will be saved to
- defaultIsActive = should the file be set as active by default
- chmod = after a folder is created, what chmod should it have


## Accessing FileBank from a controller

FileBank module is accessible via Service Locator:

```php
$fileBank = $this->getServiceLocator()->get('FileBank');
```

When you obtain the service and create the object, you can then use it to do the magic:

```php
$newFile = $fileBank->save('/tmp/myfile.jpg');
```

## Questions or comments?

Feel free to email me with any questions or comments about this module

[info@arturgajewski.com](mailto:info@arturgajewski.com)