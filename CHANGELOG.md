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

FUTURE ENHANCEMENTS

- Create APIBuilder class for easily create ZermeloAPI instances by giving a simple JSON file
	This could improve application conspectus and readability. Also, it's much cleaner to look at
- Add a custom autoloader or developer can use composer
- Fix the Class 'ZermeloAPI\Exception' not found message
