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
class Oauth {
	var $defaults = array('oauth_version' => '1.0',
												'oauth_signature_method' => 'HMAC-SHA1',
											 );


	function buildQuery($url, $method='POST', $query=array()) {
		$key = $query['oauth_consumer_secret'] . '&';
		unset($query['oauth_consumer_secret']);

		if (!empty($query['access_token_secret'])) {
			$key .= $query['access_token_secret'];
			unset($query['access_token_secret']);
		}

		$query = array_merge($this->defaults, $query);
		$query['oauth_nonce'] = md5(uniqid(rand(), true));
		$query['oauth_timestamp'] = time();
		ksort($query);
		$query['oauth_signature'] = $this->__signQuery($url, $method, $query, $key);

		return $query;
	}

	function __signQuery($url, $method, $query, $key) {
		$queryTemp = array();
		foreach($query as $k => $v) {
			//$queryTemp[] = $k . '=' . rawurlencode(utf8_encode($v));
			$queryTemp[] = $k . '=' . rawurlencode($v);
		}

		$query = implode('&', $queryTemp);
		//$base = sprintf('%s&%s&%s', strtoupper($method), rawurlencode(utf8_encode($url)), rawurlencode(utf8_encode($query)));
		$base = sprintf('%s&%s&%s', strtoupper($method), rawurlencode($url), rawurlencode($query));
		return base64_encode(hash_hmac('sha1', $base, $key, true));
	}
}
?>