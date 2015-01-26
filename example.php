<?php

// Including the Zermelo API PHP
require('Zermelo.php');

// Create a new Zermelo instance
$zermelo = new ZermeloAPI('hereyourschoolname');

// Simply receive a user grid
$grid = $zermelo->getStudentGridAhead('herethestudentid');

// Print that grid out to the user
print_r($grid);