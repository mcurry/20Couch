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
class SiteHelper extends AppHelper {
	var $helpers = array('Text', 'Html');

	var $_imgSites = array('/yfrog.com\/([\n\w]{1,})/i' => '<a href="http://yfrog.com/%s"><img src="http://yfrog.com/%s.th.jpg" /></a>',
												 '/twitpic.com\/([\n\w]{1,})/i' => '<a href="http://twitpic.com/%s"><img src="http://twitpic.com/show/thumb/%s" /></a>');

	function sortByName($item1, $item2) {
		return strcasecmp($item1["name"], $item2["name"]);
	}

	function enhanceText($text) {
		//links
		$text = $this->autoLink($text);

		//hashtags
		$text = preg_replace('/(?<=^|\s)#(\w+)/', '<a href="http://twitter.com/search?q=%23$1">$0</a>', $text);

		//usernames
		$text = preg_replace('/(?<=^|\s)@(\w+)/', '<a href="http://twitter.com/$1">$0</a>', $text);

		//highlight search terms
		if (!empty($this->data['Item']['search'])) {
			$text = $this->Text->highlight($text, $this->data['Item']['search'], array('format' => '<span class="search-word">\1</span>', 'html' => true));
		}

		//images
		foreach($this->_imgSites as $regex => $html) {
			preg_match_all($regex, $text, $matches);
			if (!empty($matches[1])) {
				$matches = array_unique($matches[1]);
				$text .= '<br />';
				foreach($matches as $match) {
					$text .= sprintf($html, $match, $match);
				}
			}
		}

		return $text;
	}

	function isForward($text) {
		if (preg_match('/^rt[: ]/i', $text)) {
			return true;
		}

		if (preg_match('/ rt[: ]/i', $text)) {
			return true;
		}

		if (stripos($text, '(via ') !== false) {
			return true;
		}

		return false;
	}

	function checkOrX($trueOrFalse) {
		if ($trueOrFalse) {
			return '/img/icons/accept.png';
		} else {
			return '/img/icons/cancel.png';
		}
	}

	function timeAgoInWords($dateTime) {
		$diff = time() - strtotime($dateTime);
		$timeAgo = '';

		if ($diff < 60) {
			$timeAgo = "just now";
		} else if ($diff < 3600) {
			$timeAgo = round($diff / 60) . " minutes ago";
		} else if ($diff < 86400) {
			$timeAgo = round($diff / 60 / 60) . " hours ago";
		} else {
			$timeAgo = round($diff / 60 / 60 / 24) . " days ago";
		}

		return $this->output($timeAgo);
	}

	function autoLinkUrls($text, $htmlOptions = array()) {
		$decodedText = html_entity_decode($text);

		preg_match_all('#(?<!href="|">)((?:http|https|ftp|nntp)://[^ <)]+)#i', $decodedText, $matches);
		foreach($matches[0] as $match) {
			$text = str_replace($match, $this->Html->link($match, $match), $text);
		}
		return $text;
	}

	function autoLink($text, $htmlOptions=array()) {
		return $this->Text->autoLinkEmails($this->autoLinkUrls($text, $htmlOptions), $htmlOptions);
	}

	function staticVersion($file) {
		if (Configure::read('staticAssets') && Configure::read('debug') == 0) {
			return $file . '-' . Configure::read('Version');
		} else {
			return $file;
		}
	}

	function getSubItems($item, $items, $replies, $mobile=false, $depth=0, $recur=false) {
		$subItems = array();

		if ($mobile && $depth > 0) {
			return array();
		}

		if (!empty($item['Item']['reply_status_provider_key']) && !empty($replies[$item['Item']['reply_status_provider_key']])) {
			$subItems['archive'] = array($replies[$item['Item']['reply_status_provider_key']]);
		}

		if (isset($replies) && !in_array($item['Item']['provider_key'], array_keys($replies))) {
			$subReplies = Set::extract('/Item[reply_status_provider_key=' . $item['Item']['provider_key'] . ']/..', $items);

			if ($mobile) {
				$subSubReplies = array();
				foreach($subReplies as $reply) {
					$subSubReplies = array_merge($subSubReplies, $this->getSubItems($reply, $items, $replies, $mobile, $depth, true));
				}

				$subReplies = array_merge($subReplies, $subSubReplies);
			}

			if ($subReplies) {
				if ($recur) {
					$subItems = $subReplies;
				} else {
					$sortedReplies = array();
					foreach($subReplies as $reply) {
						$sortedReplies[$reply['Item']['posted']] = $reply;
					}
					ksort($sortedReplies);
					$subItems['new'] = array_values($sortedReplies);
				}
			}
		}

		return $subItems;
	}
}
?>