<?php

namespace ZermeloAPI;

class Cache
{

	/**
	 * The file location used for token caching
	 * @var string
	 */
	protected $fileLocation;

	/**
	 * Construct a new cache instance with a given storage location, by default cache.json
	 * @param string $fileLocation The cache file location
	 */
	public function __construct($fileLocation = 'cache.json')
	{
		$this->setFileLocation($fileLocation);
	}

	/**
	 * Save a token to the cache file
	 * @param  string $user  The student id
	 * @param  string $token The access token to save
	 * @return mixed
	 */
	protected function saveToken($user, $token)
	{
		$current = json_decode(file_get_contents($this->getFileLocation()), true);

		$current['tokens'][$user] = $token;

		file_put_contents($this->getFileLocation(), json_encode($current));
	}

	/**
	 * Get a token from the cache file
	 * @param  string $id The student id
	 * @return string     The token
	 */
	public function getToken($id)
	{
		$current = json_decode(file_get_contents($this->getFileLocation()), true);

		if (isset($current['tokens'][$id]))
		{
				return $current['tokens'][$id];
		}

		throw new Exception("Cannot get token for " . $id);
	}

	/**
	 * Set file location
	 * @param string $location File location
	 */
	public function setFileLocation($location)
	{
		if (file_exists($location))
		{
			$this->fileLocation = $location;
		}
	}

	/**
	 * Get file location
	 * @return string File location
	 */
	public function getFileLocation()
	{
		return $this->fileLocation;
	}
}