1.0.1

- Bug fix for days that have the first hour free, second free etc.
	Developers could experience parsing problems when free hours are not added to the hour counter.
- Added some documentation to getClasses, resolveTeacherClasses, resolveCancelledClasses and getGridPortion
- Moved caching functionality to a new class named "ZermeloAPI\\Cache". 
	Easier maintenance for core development and better extending possibilities
- Added set/getCache method and cache parameter to ZermeloAPI class
- Added ZermeloAPI\Cache namespace to ZermeloAPI class
- Changed caching references in ZermeloAPI class
- Added file requirement to README

1.0.2
- Require Cache.php in example file
- Fixed Cache.php spacing
- Added composer

1.0.3
- Changed function method name from register_api to register_zermelo_api
- Added custom autoloader, if the developer decides to not use composer
- Fixed some grammar mistakes
- Some small parameter rename in grabAccessToken method
- Renamed parseDataString to parseHttpDataString, function of this method is now described more briefly
- Added clearCache method
- Documentated curl request
- Some documentation changes

1.0.4
- Fixed a couple of issues, see #10

1.0.5
- Automatically create cache files if they don't exist

1.0.6
- Some small (documentation) fixes

FUTURE ENHANCEMENTS

- Create APIBuilder class for easily create ZermeloAPI instances by giving a simple JSON file
	This could improve application conspectus and readability. Also, it's much cleaner to look at
