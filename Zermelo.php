<?php

/**
 * This Zermelo API class was developed by wvanbreukelen, 2015
 * This project does not have any connections/is related to Zermelo itself!
 *
 * It contains all methods you can use to easily start your own app development
 */
class ZermeloAPI
{

	/**
	 * The school to use by the API
	 * @var string
	 */
	protected $school;

	/**
	 * Construct a new Zermelo instance, by any given school
	 * 
	 * @param string $school The school you want to add
	 */
	public function __construct($school)
	{
		$this->setSchool(strtolower($school));
	}

	/**
	 * Get a student grid, by looking forward in weeks
	 * @param  string  $id    The student id
	 * @param  integer $weeks The weeks to look forward, standard is one
	 * @return array          The parsed grid
	 */
	public function getStudentGridAhead($id, $weeks = 1)
	{
		if ($weeks == 1)
		{
			$start = strtotime('today midnight');
			$end = strtotime('next saturday');
		} else {
			$start = strtotime('+' . $weeks . ' weeks monday');
			$end = strtotime('+' . $weeks . ' weeks saturday');
		}
		
		return $this->getStudentGrid($id, $start, $end);
	}

	/**
	 * Get a student grid
	 * @param  string $id    The student id
	 * @param  string $start The start timestamp of the grid
	 * @param  string $end   The stop timestamp of the grid
	 * @return array         The grid itself or nothing on a fail
	 */
	public function getStudentGrid($id, $start = null, $end = null)
	{
		if (is_null($start)) $start = strtotime('last monday', strtotime('tomorrow'));
		if (is_null($end)) $end = strtotime('last saturday', strtotime('tomorrow'));

		$token = $this->getToken($id);

		$raw = $this->callApi("api/v2/appointments", array('access_token' => $token, 'start' => $start, 'end' => $end, 'user' => '~me'));

		$json = json_decode($raw, true)['response'];

		if ($this->validateData($json))
		{
			$grid = $this->optimizeGrid($json['data']);

			return $grid;
		}

		return array();
	}

	/**
	 * Get announcements, by looking forward in weeks
	 * @param  string  $id    The student id
	 * @param  integer $weeks The weeks to look forward
	 * @return array         The announcements
	 */
	public function getAnnouncementsAhead($id, $weeks = 1)
	{
		if ($weeks == 1)
		{
			$start = strtotime('next monday');
			$end = strtotime('next saturday');
		} else {
			$start = strtotime('+' . $weeks . ' weeks monday');
			$end = strtotime('+' . $weeks . ' weeks saturday');
		}

		return $this->getAnnouncements($id, $start, $end);
	}

	/**
	 * Get announcements
	 * @param  string $id    The student id
	 * @param  string $start The start timestamp
	 * @param  string $end   The end timestamp
	 * @return array         The announcements
	 */
	public function getAnnouncements($id, $start = null, $end = null)
	{
		if (is_null($start)) $start = strtotime('last monday', strtotime('tomorrow'));
		if (is_null($end)) $end = strtotime('last saturday', strtotime('tomorrow'));

		$token = $this->getToken($id);

		$raw = $this->callApi("api/v2/announcements", array('access_token' => $token, 'start' => $start, 'end' => $end));

		$json = json_decode($raw, true)['response'];

		if ($this->validateData($json))
		{
			return $json['data'];
		}

		return null;
	}

	/**
	 * Grab a access token from the Zermelo API
	 * @param  string  $user User to grab a token
	 * @param  string  $code The code gained by the user itself
	 * @param  boolean $save Automatically save to access token to a cache file
	 * @return mixed         The token results
	 */
	public function grabAccessToken($user, $code, $save = true)
	{
		$code = str_replace(' ', '', $code);

		$raw = $this->callApiPost("api/v2/oauth/token", array('grant_type' => 'authorization_code', 'code' => $code));

		if (strpos($raw, 'Error report') !== false)
		{
			echo "Cannot grab access token, maybe the code is invalid?";
			return null;
		}

		$json = json_decode($raw, true);

		if ($save) $this->saveToken($user, $json['access_token']);

		echo "Finished grabbing access token!";

		return $json;
	}

	/**
	 * Invalidate a access token from the Zermelo API
	 * @param  string $id The student id
	 * @return bool       Succeeded
	 */
	public function invalidateAccessToken($id)
	{
		$token = $this->getToken($id);

		$raw = $this->callApiPost("/api/v2/oauth/logout", array('access_token' => $token));

		if (strlen($raw) === 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Set the school to use
	 * @param string $school School to use
	 */
	public function setSchool($school)
	{
		$this->school = $school;
	}

	/**
	 * Validate the grid data received from the Zermelo API
	 * @param  array $data The grid data to validate
	 * @return bool        Successfull or not
	 */
	protected function validateData($data)
	{
		if ($data['status'] != "200")
		{
			if ($data['status'] == "401")
			{
				throw new Exception("Cannot get data, access token is invalid!");

				return false;
			} else {
				throw new Exception("Something went wrong. Error: " . $data['message']);

				return false;
			}
		}

		return true;
	}

	/**
	 * Optimize a grid
	 * @param  array  $grid The grid to optimize
	 * @return arrau        The optimized grid
	 */
	protected function optimizeGrid(array $grid = array())
	{

		$grid = $this->sortGrid($grid);

		foreach ($grid as $id => $row)
		{
			$grid[$id]['start_date'] = date('d/m/Y G:i', $row['start']);
			$grid[$id]['end_date'] = date('d/m/Y G:i', $row['end']);

			$grid[$id]['hour'] = round(($grid[$id]['start'] - strtotime(date('d-m-Y', $grid[$id]['start']) . ' 8:30')) / 3600);

			if ($grid[$id]['hour'] == 0) $grid[$id]['hour'] = 1; 
		}



		return $grid;
	}

	/**
	 * Sort a grid by timestamp
	 * @param  array  $grid The grid to sort
	 * @return array        The sorted grid
	 */
	protected function sortGrid(array $grid = array())
	{
		foreach ($grid as $key => $node)
		{
			$timestamps[$key] = $node['start'];
		}

		array_multisort($timestamps, SORT_ASC, $grid);

		return $grid;
	}

	/**
	 * Get the Zermelo API base URL
	 * @param  string $uri The uri
	 * @return string      The base URL
	 */
	protected function getBaseUrl($uri = "")
	{
		return "https://" . $this->school . ".zportal.nl/" . $uri;
	}

	/**
	 * Save a token to the cache file
	 * @param  string $user  The student id
	 * @param  string $token The access token to save
	 * @return mixed
	 */
	protected function saveToken($user, $token)
	{
		$current = array();

		if (file_exists('cache.json'))
		{
			$current = json_decode(file_get_contents('cache.json'), true);
		}

		$current['tokens'][$user] = $token;

		file_put_contents('cache.json', json_encode($current));
	}

	/**
	 * Get a token from the cache file
	 * @param  string $id The student id
	 * @return string     The token
	 */
	public function getToken($id)
	{
		if (file_exists('cache.json'))
		{
			$current = json_decode(file_get_contents('cache.json'), true);

			if (isset($current['tokens'][$id]))
			{
				return $current['tokens'][$id];
			}
		}

		echo "Cannot get token for " . $id;
		return null;
	}

	/**
	 * Call the API by using the HTTP GET method
	 * @param  string $uri        Uri to interact
	 * @param  array  $datafields The datafields
	 * @return string             The raw results from the API
	 */
	private function callApi($uri, $datafields = array())
	{
		$url = $this->getBaseUrl($uri);

		$data = $this->parseDataString($datafields);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url . $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/**
	 * Call the API by using the HTTP POST method
	 * @param  string $uri        Uri to interact
	 * @param  array  $datafields The datafields
	 * @return string             The raw results from the API
	 */
	private function callApiPost($uri, $datafields = array())
	{
		$url = $this->getBaseUrl($uri);

		$data = rtrim(ltrim($this->parseDataString($datafields), '?'), '&');

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($datafields));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/**
	 * Parse a data string from a datafields array
	 * @param  array  $datafields The datafields arrau
	 * @return string             The parsed data string
	 */
	private function parseDataString(array $datafields = array())
	{
		$string = "?";

		foreach ($datafields as $key => $value)
		{
			$string .= $key . "=" . $value . "&";
		}

		return $string;
	}
}
