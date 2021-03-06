<?php
	include 'util.php';
	$con = connectDB();
	$result = mysql_query("SELECT * FROM posts");
	$idArray = array();
	$dateArray = array();
	$preArray = array();
	$contentArray = array();
	$index = array();
	$i = 0;
	while($row = mysql_fetch_array($result)) {
		array_push($idArray, "p" . $row['id']);
		array_push($dateArray, str_replace("-", ".", $row['date']));
		array_push($preArray, $row['pre']);
		array_push($contentArray, $row['content']);
		array_push($index, $i);
		$i += 1;
	}
	for ($j = 0; $j < $i; ++$j) {
		$k = $j;
		$index[$k] = $j;
		while ($k > 0) {
			if ($dateArray[$index[$k]] > $dateArray[$index[$k - 1]] || ($dateArray[$index[$k]] == $dateArray[$index[$k - 1]] && $id[$index[$k]] > $id[$index[$k - 1]])) {
				$index[$k] = $index[$k - 1];
				$index[$k - 1] = $j;
				$k -= 1;
			} else {
				break;
			}
		}
		
	}
?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css.css" />
	<link rel="stylesheet" type="text/css" href="homepage.css" />
	<title>微辣酱</title>
	<script src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript">
		function toggleList(divClass) {
			$("div#" + divClass + " ul").slideToggle();
		}
	</script>
</head>

<body style="background-color:#1f1f1f">
	<div id="page">

		<div><h1 id="title">微辣酱</h1></div>

		<div id="intro">
			<div class="face rightfloat"><a href="http://huxizhou88.blog.163.com/"><img src="image/self3.jpg" alt="self3.jpg"/></a></div>
			<div class="face rightfloat"><a href="http://veramo.lofter.com/"><img src="image/self2.jpg" alt="self2.jpg"/></a></div>
			<div class="face rightfloat"><a href="http://www.weibo.com/huxiaomo619/"><img src="image/self4.jpg" alt="self4.jpg"/></a></div>
			<div class="face rightfloat"><a href="http://www.douban.com/people/vera_hu/"><img src="image/self1.jpg" alt="self1.jpg"/></a></div>
		</div>
		
		<div id="leftbar">
			<div id="index">
				<p class="center" onclick="toggleList('index')">Index</p>
				<ul>
<?php
	$size = count($dateArray);
	for ($i = 0; $i < $size; ++$i) {
		echo '<li><a href="#', $idArray[$index[$i]], '">', $dateArray[$index[$i]], "</a></li>\n";
	}
?>
				</ul>
			</div>
			
			<div id="links">
				<p class="center" onclick="toggleList('links')">Links</p>
			</div>
			
		</div>
		
		<div id="content">
<?php
	$size = count($dateArray);
	for ($i = 0; $i < $size; ++$i) {
		echo '<div class="paragraph" id="' . $idArray[$index[$i]] . '">' . "\n";
		if (strlen($preArray[$index[$i]]) != 0) {
			echo $preArray[$index[$i]] . "\n";
		}
		echo '<h2 class="date">' . $dateArray[$index[$i]] . "</h2>\n\n";
		echo $contentArray[$index[$i]] . "\n";
		echo "</div>\n";
	}
?>
		</div>
		
		<div id="rightbar">

			<div id="rssmovie" class="rss">
				<p class="center" onclick="toggleList('rssmovie')">Recent Movies</p>
				<ul>
<?php
	$myfile = fopen("http://movie.douban.com/people/vera_hu/collect", "r") or die("Unable to open file!");
	$numberOfItems = 10;
	while (!feof($myfile)) {
		$line = fgets($myfile);
		if (substr($line, 0, 38) == '                    <li class="title">') {
			$line = fgets($myfile);
			$ref = substr($line, 24);
			$ref = substr($ref, 0, strlen($ref) - 1);
			$line = fgets($myfile);
			$name = substr($line, 32);
			$name = substr($name, 0, strlen($name) - 6);
			
			$rating = 0;
			while (!feof($myfile)) {
				$line = fgets($myfile);
				if (substr($line, 0, 55) == '                                    <span class="rating') {
					$rating = intval(substr($line, 55, 1));
					break;
				}
			}
			
			$comment = "";
			while (!feof($myfile)) {
				$line = fgets($myfile);
				if (substr($line, 0, 46) == '                        <span class="comment">') {
					$comment = substr($line, 46, strlen($line) - 46 - 8);
					break;
				}
				if (substr($line, 0, 24) == '        <div class="item') {
					break;
				}
			}
			
			echo "					<li>", $ref, $name, "</a></li>\n";
			echo '					<p class="rating">';
			while ($rating--) {
				echo "★";
			}
			echo "</p>\n";
			if (strlen($comment) == 0) {
				echo '					<p class="comment">', "</p>\n";
			} else {
				echo '					<p class="comment">“', $comment, "”</p>\n";
			}
			if (--$numberOfItems == 0) {
				break;
			}
		}
	}
	fclose($myfile);
?>
				</ul>
			</div>
			
			<div id="rssbook" class="rss">
				<p class="center" onclick="toggleList('rssbook')">Recent Books</p>
				<ul>
<?php
	$myfile = fopen("http://book.douban.com/people/vera_hu/collect", "r") or die("Unable to open file!");
	$numberOfItems = 10;
	while (!feof($myfile)) {
		$line = fgets($myfile);
		if (substr($line, 0, 42) == '  <a href="http://book.douban.com/subject/') {
			$ref = strstr($line, '"');
			$ref = substr($ref, 1, strlen($ref) - 1);
			$name = strstr($ref, '"');
			$ref = substr($ref, 0, strlen($ref) - strlen($name));
			$name = substr($name, 1, strlen($name) - 1);
			$name = strstr($name, '"');
			$name = substr($name, 1, strlen($name) - 4);
			
			$rating = 0;
			while (!feof($myfile)) {
				$line = fgets($myfile);
				if (substr($line, 0, 25) == '      <span class="rating') {
					$rating = intval(substr($line, 25, 1));
					break;
				}
			}
			
			$comment = "";
			while (!feof($myfile)) {
				$line = fgets($myfile);
				if (substr($line, 0, 21) == '  <p class="comment">') {
					$line = fgets($myfile);
					$comment = substr($line, 6, strlen($line) - 7);
					break;
				}
			}
			
			echo '					<li><a href="', $ref, '">', $name, "</a></li>\n";
			echo '					<p class="rating">';
			while ($rating--) {
				echo "★";
			}
			echo "</p>\n";
			echo '					<p class="comment">“', $comment, "”</p>\n";
			if (--$numberOfItems == 0) {
				break;
			}
		}
	}
	fclose($myfile);
?>
				</ul>
			</div>
		</div>

	</div>
</body>
</html>
