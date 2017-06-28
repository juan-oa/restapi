<?php

class restapi_Core {
	private $api;

	function __construct($api) {
		$this->api = & $api;
	}

	function index() {
		$this->get();
	}

	/**
	 * @verb GET
	 * @return
	 * @uri /core
	 */
	function get($parmas) {

	}

	/**
	 * @verb GET
	 * @return - a list of users
	 * @uri /core/users
	 */
	function get_users() {
		return core_users_list();
	}

	/**
	 * @verb GET
	 * @returns - a user resource
	 * @uri /core/users/:id
	 */
	function get_user_id($params) {
		$base = core_users_get($params['id']);

		// Now, find their voicemail information.
		$z = file("/etc/asterisk/voicemail.conf");
		foreach ($z as $line) {
			$res = explode("=>", $line);
			if (!isset($res[1]))
				continue;

			if (trim($res[0]) == trim($params['id'])) {
				$base['vm'] = trim($res[1]);
				return $base;
			}
		}

		// No voicemail found.
		return $base;
	}

	// Create EXTENSION on PBX (using extension number and name for now(will change to vals with json later))
	function put_user_id($params) {
                $vars = array(
                     'extension' => $params['id'],
                     'name' => $params['name'],
                 );
                core_users_add($vars);
                $settings = array(
	                "dial" => array("value" => ''),
	                "devicetype" => array("value" => 'sip'),
	                "user" => array("value" => $params['id']),
	                "description" => array("value" => $params['name']),
	                "emergency_cid" => array("value" => 'emergency_cid_man')
                );
                return FreePBX::Core()->addDevice($params['id'], 'sip', $settings, $editmode=false);
                //core_devices_addsip($params['id'],'SIP');
       }
}
