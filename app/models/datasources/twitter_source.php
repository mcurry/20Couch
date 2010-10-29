<?php
/***************************************************************************
 20Couch

 Copyright (c) 2009-2010 Matt Curry

 @author      Matt Curry <matt@pseudocoder.com>
 @license     MIT
 
 More info at: http://www.20couch.com
****************************************************************************/
?>
<?php
App::import('Core', 'HttpSocket');
App::import('Vendor', 'Oauth');

class TwitterSource extends DataSource {
	var $Http = null;

	var $_schema = array('id', 'status', 'action', 'url_suffix', 'access_token', 'access_token_secret', 'provider_reply_key', 'provider_forward_key');
	var $_actions = array('request_token' => array('method' => 'post', 'url' => 'oauth/request_token', 'responseType' => 'raw'),
												'access_token' => array('method' => 'post', 'url' => 'oauth/access_token', 'responseType' => 'raw'),
												'verify' => array('url' => 'account/verify_credentials', 'rate' => false),
												'update' => array('url' => 'statuses/home_timeline', 'rate' => true),
												'single' => array('url' => 'statuses/show', 'rate' => true),
												'mentions' => array('url' => 'statuses/mentions', 'rate' => true),
												'messages' => array('url' => 'direct_messages', 'rate' => true),
												'follow' => array('url' => 'statuses/user_timeline', 'rate' => true),
												'unfriend' => array('base' => 'api1', 'url' => 'friendships/destroy', 'rate' => false),
												'search' => array('base' => 'search', 'url' => 'search', 'rate' => true),
												'update_status' => array('url' => 'statuses/update', 'rate' => true),
												'reply' => array('url' => 'statuses/update', 'rate' => true),
												'replies' => array('base' => 'search', 'url' => 'search', 'rate' => true),
												'forwardManual' => array('url' => 'statuses/update', 'rate' => true),
												'forwardApi' => array('base' => 'api1', 'url' => 'statuses/retweet', 'rate' => true));

	var $_scheme = 'http://';
	var $_urls = array('default' => 'twitter.com/',
										 'search' => 'search.twitter.com/',
										 'api1' => 'api.twitter.com/1/');

	var $_responseType = null;

	var $_params = array('id' => 'user_id',
											 'limit' => 'count',
											 'rpp' => 'rpp',
											 'search' => 'q',
											 'last_item_provider_key' => 'since_id',
											 'page' => 'page',
											 'pin' => 'oauth_verifier',
											 'request_token' => 'oauth_token',
											 'access_token' => 'oauth_token',
											 'access_token_secret' => 'access_token_secret',
											 'status' => 'status',
											 'provider_reply_key' => 'in_reply_to_status_id',
											 'provider_forward_key' => 'in_reply_to_status_id');

	var $_defaults = 	array('oauth_consumer_key' => 'ZKswRL73c3hL7msg1dzmQ',
													'oauth_consumer_secret' => 'jdeMEKml9S1zHviDrwjcuJ5oYddzRKoMV1kAMGsH0');

	function __construct($config) {
		$this->Http = new HttpSocket();

		if (defined("OPENSSL_KEYTYPE_RSA")) {
			$this->_scheme = 'https://';
		}

		$this->Oauth = new Oauth();
		parent::__construct($config);
	}

	function listSources() {
		return array('twitter_accounts', 'twitter_searches', 'twitter_follows', 'twitter_replies');
	}

	function queryAssociation() {
		return array();
	}

	function truncate() {
		return true;
	}

	function name($data) {
		return $data;
	}

	function describe($model) {
		return array_combine($this->_schema, $this->_schema);
	}

	function delete(&$model, $id = null) {
		$method = 'post';
		$queryData = $model->id;

		$action = 'unfriend';
		if (!empty($data['action'])) {
			$action = $data['action'];
			unset($data['action']);
		}
		$url = $this->__buildUrl(compact('action'));
		
		$queryData['conditions']['access_token'] = $queryData['auth']['access_token'];
		$queryData['conditions']['access_token_secret'] = $queryData['auth']['access_token_secret'];
		$query = $this->__buildQuery($queryData);
		$query = $this->Oauth->buildQuery($url, $method, array_merge($query, $this->_defaults));
		
		$response = $this->Http-> {$method}($url, $query);
		if($this->Http->response['status']['code'] == 200) {
			return true;
		}
		
		return false;
	}

	function read($model, $queryData=array()) {
		$queryData = array_merge(array('page' => 1), $queryData);

		$method = 'get';
		if (!empty($this->_actions[$queryData['action']]['method'])) {
			$method = $this->_actions[$queryData['action']]['method'];
		}

		if (!empty($queryData['auth'])) {
			$queryData['conditions']['access_token'] = $queryData['auth']['access_token'];
			$queryData['conditions']['access_token_secret'] = $queryData['auth']['access_token_secret'];
		}

		$url = $this->__buildUrl($queryData);
		$query = $this->__buildQuery($queryData);

		$query = $this->Oauth->buildQuery($url, $method, array_merge($query, $this->_defaults));

		$response = $this->Http-> {$method}($url, $query);

		if (!empty($this->Http->response['header']['X-Ratelimit-Remaining'])) {
			Configure::write('Twitter.apiRequestsRemaining', $this->Http->response['header']['X-Ratelimit-Remaining']);
		}

		$responseCode = $this->Http->response['status']['code'];
		$this->Http->response = null;

		if ($responseCode >= 400) {
			return false;
		}

		return $this->__parseResponse($response);
	}

	function create($model, $fields=array(), $values=array()) {
		$data = array_combine($fields, $values);

		$action = 'update_status';
		if (!empty($data['action'])) {
			$action = $data['action'];
			unset($data['action']);
		}

		$urlSuffix = '';
		if (!empty($data['url_suffix'])) {
			$urlSuffix = $data['url_suffix'];
			unset($data['url_suffix']);
		}

		$url = $this->__buildUrl(compact('action', 'urlSuffix'));
		$query = $this->__buildQuery($data);
		$query = $this->Oauth->buildQuery($url, 'post', array_merge($query, $this->_defaults));

		$response = $this->Http->post($url, $query);
		if (!empty($this->Http->response['header']['X-Ratelimit-Remaining'])) {
			Configure::write('Twitter.apiRequestsRemaining', $this->Http->response['header']['X-Ratelimit-Remaining']);
		}
		$this->Http->response = null;

		return $this->__parseResponse($response);
	}

	function __buildUrl($queryData) {
		$baseUrl = $this->_urls['default'];
		if (!empty($this->_actions[$queryData['action']]['base'])) {
			$baseUrl = $this->_urls[$this->_actions[$queryData['action']]['base']];
		}

		$this->_responseType = 'json';
		if (!empty($this->_actions[$queryData['action']]['responseType'])) {
			$this->_responseType = $this->_actions[$queryData['action']]['responseType'];
		}

		$url = $this->_scheme . $baseUrl . $this->_actions[$queryData['action']]['url'];

		if (!empty($queryData['urlSuffix'])) {
			$url .= '/' . $queryData['urlSuffix'];
		}

		if ($this->_responseType != 'raw') {
			$url .= '.' . $this->_responseType;
		}

		return $url;
	}

	function __buildQuery($queryData) {
		$query = array();
		foreach($this->_params as $key => $value) {
			if (!empty($queryData[$key])) {
				$query[$value] = $queryData[$key];
			}
		}

		if (!empty($queryData['conditions'])) {
			foreach($queryData['conditions'] as $key => $value) {
				if (!$value) {
					continue;
				}

				if (!empty($this->_params[$key])) {
					$query[$this->_params[$key]] = $value;
				} else {
					$query[$key] = $value;
				}
			}
		}

		return $query;
	}

	function __parseResponse($response) {
		switch ($this->_responseType) {
			case 'json':
				//thankyou SO http://stackoverflow.com/questions/1777382/php-jsondecode-on-a-32bit-server
				$response = preg_replace('/id":(\d+)/', 'id":"$1"', $response);

				return json_decode($response, true);
			default:
				return $response;
		}
	}
}
?>