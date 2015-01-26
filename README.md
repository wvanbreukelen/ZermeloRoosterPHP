# ZermeloRoosterPHP
A simple to use PHP wrapper for the Zermelo API to get student grids out of the Zermelo zportal.

This project does not have any connections to Zermelo itself!

### Getting a authentation token
To grab a authentation token for a specific student, you will need a code, that you can get from the Zermelo zportral itself.
Use the following method

```php
$this->grabAccessToken('user', 'code');
```
The authentation token is automatically saved to a cache file (cache.json).

### Get a student grid for the current week

This method returns an array with all the information about the grid

```php
$grid = $this->getAnnouncementsAhead('user');
```

### Get a student grid for a specific week in the future

To get a grid for example three weeks ahead, please use the following code

```php
$grid = $this->getAnnouncementsAhead('user', 3)
```
