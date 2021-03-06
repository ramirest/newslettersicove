<?php
/*
This file can be used to retrieve archives from a remote SendStudio installation and display on a local web server with different formatting, look and feel etc.
*/

/*
The location of the RSS feed in the SendStudio installation. To show an archive of all mailing lists available, then this path will point to the rss.php file. To specify which mailing list to show, use the ID of the list eg. rss.php?List=24

Inside your SendStudio control panel, browse to the Manage Mailing Lists option and click on the RSS icon. This will be the path to the RSS feed for that specific mailing list.
*/

define('XML_URL', 'http://domain.com/ssnx/rss.php');

// How many newsletters should we show in the archives?
define('NUMBER_OF_ENTRIES_TO_SHOW', 50);

// The length of the email summary to show (number of characters).
define('MAX_POST_LENGTH', 40);

// Setup your HTML header here
$htmlheader = "
<html>
<head>
<title>Newsletter Archives</title>
<style>

body {
	font-family: Tahoma;
	font-size: 11px;
	color: black;
	line-height: 1.5;
	background-color: #F3F2E9;
	margin: 20px;
}

a {
	font-size: 14px;
}

.container {
	border: 1px #CAC7BD solid;
	background-color: #FFFFFF;
	padding: 20px
}

.smalltext {
	font-size: 9px;
}

.heading
{
	font-size: 18px;
	font-weight: normal;
	font-family: Tahoma;
}
</style>
</head>
<body>
	<div class='container'>
";

$htmlfooter = "
	</div>
</body>
</html>
";

$content = GetContent();
if ($content == '') {
	echo 'No content retrieved from feed, aborting.<br/>';
	exit();
}

$items = FetchXmlNode('item', $content, true);

$mycontent = $htmlheader . "<table border='0' cellspacing='0' cellpadding='0'>";
$mycontent .= "<tr><td class='heading'>Newsletter Archives<br><br></td></tr>";
$numbershown = 1;
foreach($items as $itempos => $item) {
	if ($numbershown > NUMBER_OF_ENTRIES_TO_SHOW) break;

	$title = FetchXmlNode('title', $item);
	$link = FetchXmlNode('link', $item);
	$author = FetchXmlNode('author', $item);
	$postdate = FetchXmlNode('pubdate', $item);

	$postcontent = urldecode(FetchXmlNode('content', $item));

	$post = '';
	preg_match('%<\!\[cdata\[(.*?)\]\]>%is', $postcontent, $match);
	if (!empty($match)) {
		$post = $match[1];
	}
	$shortpost = GetShortPost($post);

	$mycontent .= '
		<tr>
			<td>
				<a href="' . $link . '" class="heading1">' . $title . '</a><br>
				<a href="' . $link . '" class="smalltext">' . $shortpost . '</a><br>
				<span class="smalltext">Posted: ' . $postdate . ', By: ' . $author . '</span><br><br>
			</td>
		</tr>
	';
	$numbershown++;
}

$mycontent .= '</table>';
$mycontent .= '<br/>Email marketing by SendStudio</a>';
echo $mycontent;

function GetShortPost($content='') {
	$content = trim($content);
	$post = str_replace("\r", "\n", $content);
	$post = str_replace("\n", "", $post);
	if (strlen($post) > MAX_POST_LENGTH) {
		$shortpost = substr($post, 0, MAX_POST_LENGTH);
		if (substr($shortpost, -3, 3) != '...') {
			$shortpost .= '...';
		}
	} else {
		$shortpost = $post;
	}
	return $shortpost;
}

function TimeDifference($posttime, $timenow) {
	$difference = $timenow - $posttime;

	if ($difference < ONE_MINUTE) {
		$timechange = number_format($difference, 0) . ' second';
		if ($difference > 1) $timechange .= 's';
	}
	if ($difference > ONE_MINUTE && $difference < ONE_HOUR) {
		$num_mins = floor($difference / ONE_MINUTE);
		$timechange  = number_format($num_mins, 0) . ' minute';
		if ($num_mins > 1) $timechange .= 's';
	}
	if ($difference >= ONE_HOUR && $difference < ONE_DAY) {
		$hours = floor($difference/ONE_HOUR);
		$mins = floor($difference % ONE_HOUR) / ONE_MINUTE;

		$timechange = number_format($hours, 0) . ' hour';
		if ($hours > 1) $timechange .= 's';

		$timechange .= ', ' . number_format($mins, 0) . ' minute';
		if ($mins > 1) $timechange .= 's';
	}

	if ($difference >= ONE_DAY && $difference < ONE_WEEK) {
		$days = floor($difference / ONE_DAY);
		$timechange = number_format($days, 0) . ' day';
		if ($days > 1) $timechange .= 's';
	}

	if ($difference >= ONE_WEEK) {
		return date('d-M-Y', $posttime);
	}

	$timechange .= ' ago';
	return $timechange;
}


function FetchXmlNode($node='', $xml='', $all=false) {
	if ($node == '') {
		return false;
	}
	if ($all) {
		preg_match_all('%<('.$node.'[^>]*)>(.*?)</'.$node.'>%is', $xml, $matches);
	} else {
		preg_match('%<('.$node.'[^>]*)>(.*?)</'.$node.'>%is', $xml, $matches);
	}

	if (!isset($matches[2])) {
		return false;
	}

	return $matches[2];
}

function GetContent()
{
	$url = XML_URL . '?Fetch=' . NUMBER_OF_ENTRIES_TO_SHOW;

	if (function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$pagedata = curl_exec($ch);

		if (!$pagedata) {
			$error = curl_error($ch);
		}
		curl_close($ch);

		if (!$pagedata) {
			echo "Error: " . $error . "<br/>";
		}
		$pagedata = trim($pagedata);
		return $pagedata;
	}

	if (ini_get('allow_url_fopen')) {
		if (!$fp = fopen($url, 'r')) {
			echo 'Unable to open url, aborting.<br/>';
			exit();
		}
		$pagedata = '';
		while(!feof($fp)) {
			$pagedata .= fread($fp, 1024);
		}
		fclose($fp);
		$pagedata = trim($pagedata);
		return $pagedata;
	}

	echo 'Your server does not support curl or have allow_url_fopen switched on. Unfortunately we cannot get content without either of these options being available. Please speak to your administrator.<br/>';
	exit();
}
?>
