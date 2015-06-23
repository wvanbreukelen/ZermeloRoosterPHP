# ZermeloRoosterPHP

A PHP wrapper for the Zermelo API to get information out of the Zermelo zportal.
Let me now what you did build with it, I am really curious :)

Create a pull request if you like to contribute, or create if you have any issues so I can help you out :)

Also, have a look at the following rewrites in other languages:

Python - https://github.com/wvanbreukelen/ZermeloRoosterPython (abandoned for now)
C++ - https://github.com/JulianvDoorn/ZermeloAPICppVersion (managed by JulianvDoorn)

### Installing and creating a API instance
To create a new API instance, you may want to require the composer autoloader.
So add the following to your composer.json file

```json
{
    "require": {
        "wvanbreukelen/zermelo-rooster-php": "1.0.*"
    }
}
```

And running composer update

If you like, you can also use the build-in autoloader that I have created by using the following code

```php
require('custom_autoload.php');

$zermelo = new ZermeloAPI('hereyourschoolname');
```

It's also possible to connect with the HTTPS protocol enabled, if you webserver supports this feature. Just add the true boolean to the constructor.

```php
$zermelo = new ZermeloAPI('hereyourschoolname', true)
```


#### Authentation tokens
To receive a authentation token for a specific student, you will need a code, that you can get from the Zermelo zportral itself.
Use the following method

```php
$zermelo->grabAccessToken('user', 'code');
```
The authentation token will be automatically saved to a cache file (cache.json).
If you received your authentation token, you can skip this method because the class will automatically check for the existance in "cache.json"

#### Grids

This method returns an array with all the information about the grid. The class does automatically optimize and sort the grid out. Also, some easy to use parameters like timestamps are added.

```php
$grid = $zermelo->getStudentGrid('user');
```

To get a grid for example three weeks ahead, please use the following code

```php
$grid = $zermelo->getStudentGridAhead('user', 3)
```

#### Announcements

It is possible to receive all of the user's announcements by using the following method

```php
$messages = $zermelo->getAnnouncements('user');
```

Also the future

```php
$messages = $zermelo->getAnnouncementsAhead('user', 3);
```

#### Remarks

This project does not have any connections to Zermelo itself! I cannot guarentee any expectations of the class.
