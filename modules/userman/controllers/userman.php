<?php

class restapi_Userman {
	private $api;
	
	function __construct($api) {
		$this->api = & $api;
	}	
	
	function index() {
		return $this->get();
	}

	/**
	 * @verb GET
	 * @returns 
	 * @uri /userman
	 */
	function get($params) {

	}

	/**
	 * @verb GET
	 * @returns - list of users
	 * @uri /userman/users
	 */
	function get_users($params) {
		$userman = setup_userman();
		if ($userman) {
			$users = $userman->getAllUsers();
			foreach ($users as $user) {
				$user['assigned'] = $userman->getAssignedDevices($user['id']);

				$list[] = $user;
			}
			return $list;
		}

		return false;
	}

	/**
	 * @verb GET
	 * @returns - a userman user
	 * @uri /userman/users/:id
	 */
	function get_user_id($params) {
		if ($params['id'] == 'none') {
			/* Don't do that. */
			return false;
		}

		$userman = setup_userman();
		if ($userman) {
			$user = $userman->getUserByUsername($params['id']);
			if ($user) {
				$user['assigned'] = $userman->getAssignedDevices($user['id']);
			}

			return $user;
		}

		return false;
	}

	/**
	 * @verb GET
	 * @returns - list of extensions
	 * @uri /userman/extensions
	 */
	function get_extensions($params) {
		$userman = setup_userman();
		if ($userman) {
			$users = $userman->getAllUsers();
			foreach ($users as $user) {
				if ($user['default_extension'] == NULL || $user['default_extension'] == 'none') {
					continue;
				}

				$list[$user['default_extension']] = array(
					"id" => $user['id'],
					"username" => $user['username'],
					"description" => $user['description']
				);
			}
			return $list;
		}

		return false;
	}

	/**
	 * @verb GET
	 * @returns - a userman user
	 * @uri /userman/extensions/:id
	 */
	function get_extension_id($params) {
		if ($params['id'] == 'none') {
			/* Don't do that. */
			return false;
		}

		$userman = setup_userman();
		if ($userman) {
			$user = $userman->getUserByDefaultExtension($params['id']);
			if ($user) {
				$user['assigned'] = $userman->getAssignedDevices($user['id']);
			}

			return $user;
		}

		return false;
	}

	function put_user_id($params) {
				$flag = 2;
                try {
                        $vars = array(
                                'extension' => $params['id'],
                                'name' => $params['name'],
                                'password' => $params['secret'],
                                'secret' => $params['secret'],
                                'sipname' => $params['name'],
                                'outboundcid' => $params['outbound'],
                                'userman_username' => $params['name'],
                                'userman_password' => $params['secret'],
                                'newdid_name' => '',
                                'newdid' => '',
                                'newdidcid' => '',
                                'ringtimer' => '',
                                'recording_in_external' => 'force',
                                'recording_out_external' => 'force',
                                'recording_in_internal' => 'force',
                                'recording_out_internal' => 'force',
                                'callwaiting' => 'enabled',
                                
                        );
                        $settings = array(
                                        "dial" => array("value" => '',
                                                                        "flag" => 0),
                                        "devicetype" => array("value" => 'sip'),
                                        "user" => array("value" => $params['id']),
                                        "description" => array("value" => $params['name']),
                                        "emergency_cid" => array("value" => ''),
                                        "secret" => array(
                                                                                        "value" => $params['secret'],
                                                                                        "flag" => $flag++),
                                        "transport" => array(
                                        												"value" => 'wss,ws',
                                        												"flag" => $flag++),
                                        "nat" => array(
                                        												"value" => 'yes',
                                        												"flag" => $flag++),
                                        "avpf" => array(
                                        												"value" => 'yes',
                                        												"flag" => $flag++),
                                        "force_avp" => array(
                                        												"value" => 'yes',
                                        												"flag" => $flag++),
                                );
                        $dtls = array( 'enabled' => 'yes',
                                        'certificate' => 1,
                                        'verify' => 'fingerprint',
                                        'setup' => 'actpass',
                                        'rekey' => 0);

                        		core_users_add($vars);
                        		FreePBX::Certman()->addDTLSOptions($params['id'],$dtls);
                                FreePBX::Core()->addDevice($params['id'], 'sip', $settings, $editmode=false);
                 } catch (Exception $e) {
                        echo('Problem with: '.$e);
                } 
       }


}
