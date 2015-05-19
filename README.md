# ZermeloRoosterPHP
A simple to use PHP wrapper for the Zermelo API to get student grids out of the Zermelo zportal.

This project does not have any connections to Zermelo itself!

### API
To create a new API instance, you simply use the following code

```php
require('Zermelo.php');

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
$grid = $zermelo->getStudentGridAhead('user');
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
$messages = $zermelo->getAnnouncements('user', 3);
```