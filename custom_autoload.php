<?php

function register_zermelo_api()
{
	if (file_exists('Cache.php'))
	{
		include_once('Cache.php');
	} else {
		throw new Exception("Cache.php has not been found!");
	}
	
	if (file_exists('Zermelo.php'))
	{
		include_once('Zermelo.php');
	} else {
		throw new Exception("Zermelo.php has not been found!");
	}	
}
