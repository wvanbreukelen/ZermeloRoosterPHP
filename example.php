<?php

// Including the Zermelo API PHP
require('Zermelo.php');

// Create a new Zermelo instance
$zermelo = new ZermeloAPI('griftland');

print_r($zermelo->getStudentGridAhead('120259'));