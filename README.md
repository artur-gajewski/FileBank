# FileBank module for Zend Framework 2

This module provides a way to store files in a structured database and file accessors to obtain the file for download or display without
the need to setup public folders and worry about security and htaccess configurations. 

View Helper is also provided for access to file's download URL as well as file information.

This module is in active development stage. See the bottom of this README for upcoming features.

Requirements:

- PHP 5.3
- Zend Framework 2
- Doctrine 2

See [https://github.com/artur-gajewski/FileBank](https://github.com/artur-gajewski/FileBank)

Follow me on twitter: @GajewskiArtur


## Installation with Composer

Go to your project directory and add the following line to "require" list in composer.json file:

```php
"artur-gajewski/file-bank": "dev-master"
```

Now run the Composer:

```php
php composer.phar install
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
Next, create a new table in your application's MySQL database by running the two queries in data/create_scema.sql file:

```php
CREATE TABLE filebank (
  id int(11) NOT NULL auto_increment,
  name varchar(250) NOT NULL,
  size int(11) NOT NULL,
  mimetype varchar(250) NOT NULL,
  isactive int(11) NOT NULL,
  savepath varchar(250) NOT NULL,
  keywords varchar(500),
  PRIMARY KEY (id)
);

CREATE TABLE filebank_keyword (
  id int(11) NOT NULL auto_increment,
  fileid int(11) NOT NULL,
  value varchar(250),
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

```php
'params' => array(
        'filebank_folder'   => '/data/filebank/', 
        'default_is_active' => true,
        'chmod'             => 0755,
    ),
```

- filebank_folder   -> filebank root folder where the files will be saved to
- default_is_active -> should the file be set as active by default
- chmod             -> after a folder is created, what chmod should it have


## Accessing FileBank from a controller

FileBank module is accessible via Service Locator:

```php
$fileBank = $this->getServiceLocator()->get('FileBank');
```

When you obtain the service and create the object, you can then use it to do the magic:

```php
$entity = $fileBank->save('/tmp/myfile.jpg');
```

The return value is the file's FileBank entity from which you can get information usable in view helper.

You can also attach keywords for a file by adding an array to the save() function

```php
$keywords = array('Foo', 'Bar', 'Hey');

$entity = $fileBank->save('/tmp/myfile.jpg', keywords);
```

This will attach these three keywords to the file. You can fetch the files with a certain keyword(s) as follows:

```php
$fileBank->getFilesByKeywords(array('Foo', 'Hey'));
```

This will return an array of FileBank entities that match the keywords Foo and Hey. The keyword usage is case INSENSITIVE in both saving and fetching functions.


## Downloading files from FileBank

This package comes along with router and controller to enable file downloads directory from view scripts. All
you need to do is point your view script's link to:

```php
http://YourApplication.com/files/<id>
```
ID is the identier for the file saved into FileBank.

In the view script, you can use FileBank's view helper, which returns file's entity:

```php
<a href="<?php echo $this->getFileById(145)->getUrl(); ?>">Download <?php echo $this->getFileById(145)->getName(); ?></a>
```

Or, the file can be directly placed into IMG tag to display the image along with title data:

```php
<img title="<?php echo $this->getFileById(145)->getName(); ?>" src="<?php echo $this->getFileById(145)->getUrl(); ?>"/>
```

Once a user clicks on this generated URL, a download prompt will appear and file is available for download.


## Coming up...

Features to be added in some point:

- Version control of uploaded files
- Image editing with GD (multiple different sizes per saved image, configurable parameters)


## Questions or comments?

Feel free to email me with any questions or comments about this module

[info@arturgajewski.com](mailto:info@arturgajewski.com)