<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_pipedrive
{
	public static function create_org_and_person($domain, $api_token, $person, $organization)
	{
		// try adding an organization and get back the ID
		$org_id = create_organization($api_token, $organization);
		// if the organization was added successfully add the person and link it to the organization
		if ($org_id) {
		 $person['org_id'] = $org_id;
		 // try adding a person and get back the ID
		 $person_id = create_person($api_token, $person);

	 	} else {
		  echo "There was a problem with adding the organization!";
	 	}
	}

	public static function create_person($domain, $api_token, $person) 
	{
		$url = "https://" . $domain . ".pipedrive.com/v1/persons?api_token=" . $api_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $person);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		// create an array from the data that is sent back from the API
		$result = json_decode($output, 1);
		// check if an id came back
		if (!empty($result['data']['id'])) {
			$person_id = $result['data']['id'];
			return $person_id;
		} else {
			return false;
		}
	}

	// Adds a Note to a Company
	// $content needs to have 'content' string and 'org_id' number
	public static function add_note_to_org($domain, $api_token, $content)
	{
		$url = "https://" . $domain . ".pipedrive.com/v1/notes?api_token=" . $api_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		// create an array from the data that is sent back from the API
		$result = json_decode($output, 1);
		// check if an id came back
		if (!empty($result['data']['id'])) {
			$note_id = $result['data']['id'];
			return $note_id;
		} else {
			return false;
		}

	}

 
	public static function create_organization($domain, $api_token, $organization)
	{
		$url = "https://" . $domain . ".pipedrive.com/v1/organizations?api_token=" . $api_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $organization);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		// create an array from the data that is sent back from the API
		$result = json_decode($output, 1);
		// check if an id came back
		if (!empty($result['data']['id'])) {
			$org_id = $result['data']['id'];
			return $org_id;
		} else {
			return false;
		}
	}
}
?>