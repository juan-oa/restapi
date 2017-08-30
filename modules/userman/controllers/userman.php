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
                                'newdid_name' => $params['newdid_name'],
                                'newdid' => $params['newdid'],
                                'newdidcid' => $params['newdidcid'],
                                'ringtimer' => $params['ring_time'],
                                'recording_in_external' => $params['recording_in_external'],
                                'recording_out_external' => $params['recording_out_external'],
                                'recording_in_internal' => $params['recording_in_internal'],
                                'recording_out_internal' => $params['recording_out_internal'],
                                'callwaiting' => $params['callwaiting'],
                                
                        );
                        $settings = array(
                                        "dial" => array("value" => '',
                                                                        "flag" => 0),
                                        "devicetype" => array("value" => $params['device_type']),
                                        "user" => array("value" => $params['id']),
                                        "description" => array("value" => $params['description']),
                                        "emergency_cid" => array("value" => $params['emergency_cid']),
                                        "secret" => array(
                                                                                        "value" => $params['secret'],
                                                                                        "flag" => $flag++),
                                        "transport" => array(
                                        												"value" => $params['transport'],
                                        												"flag" => $flag++),
                                        "nat" => array(
                                        												"value" => $params['nat'],
                                        												"flag" => $flag++),

                                        "account" =>array(								"value" => $params['id'],
                                        												"flag" => $flag++),
                                        
                                        "accountcode" => array(							"value" => $params['account_code'],
                                        												"flag" => $flag++),
                                        "avpf" => array(                                
                                                                                        "value" => $params['avpf'],
                                        												"flag" => $flag++),
                                        "force_avp" => array(
                                        												"value" => $params['force_avp'],
                                        												"flag" => $flag++),

                                        "deny" => array(								"value" => $params['deny'],
                                        												"flag" => $flag++),

                                        "dtmfmode" => array(							"value" => $params['dtmf_mode'],
                                        												"flag" => $flag++),

                                        "canreinvite" => array(							"value" => $params['can_reinvite'],
                                        												"flag" => $flag++),

                                        "context" => array(								"value" => $params['context'],
                                        												"flag" => $flag++),

                                        "host" => array(								"value" => $params['host'],
                                        												"flag" => $flag++),

                                        "defaultuser" => array(							"value" => $params['default_user'],
                                        												"flag" => $flag++),

                                        "trustrpid" => array(							"value" => $params['trustrpid'],
                                        												"flag" => $flag++),

                                        "sendrpid" => array(							"value" => $params['sendrpid'],
                                        												"flag" => $flag++),

                                        "type" => array(								"value" => $params['connection_type'],
                                        												"flag" => $flag++),

                                        "session-timers" => array(						"value" => $params['session_timers'],
                                        												"flag" => $flag++),

                                        "port" => array(								"value" => $params['port'],
                                        												"flag" => $flag++),

                                        "qualify" => array(								"value" => $params['qualify'],
                                        												"flag" => $flag++),

                                        "qualifyfreq" => array(							"value" => $params['qualify_freq'],
                                        												"flag" => $flag++),

                                        "encryption" => array(							"value" => $params['encryption'],
                                        												"flag" => $flag++),

                                        "namedcallgroup" => array(						"value" => $params['named_call_group'],
                                        												"flag" => $flag++),

                                        "namedpickupgroup" => array(					"value" => $params['named_pickup_group'],
                                        												"flag" => $flag++),

                                        "permit" => array(								"value" => $params['permit'],
                                        												"flag" => $flag++),

                                        "callerid" => array(							"value" => $params['caller_id'],
                                        												"flag" => $flag++),


                                );
                        $dtls = array( 'enabled' => $params['enable'],
                                        'certificate' => $params['certificate'],
                                        'verify' => $params['verify'],
                                        'setup' => $params['setup'],
                                        'rekey' => $params['rekey']);

                        		core_users_add($vars);
                        		FreePBX::Certman()->addDTLSOptions($params['id'],$dtls);
                                FreePBX::Core()->addDevice($params['id'], 'sip', $settings, $editmode=false);
                                FreePBX::Core()->addDevice($params['id'], $settings);
                                needreload();
                 } catch (Exception $e) {
                        echo('Problem with: '.$e);
                } 
       }

       function delete_user_id($params){
                FreePBX::Core()->delUser($params['id']);
                FreePBX::Core()->delDevice($params['id'],$editmode=false);
                needreload();
       }

}