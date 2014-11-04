<?php
	include 'util.php';
	$con = connectDB();
	$result = mysql_query("SELECT * FROM posts");
	$idArray = array();
	$dateArray = array();
	$preArray = array();
	$contentArray = array();
	while($row = mysql_fetch_array($result)) {
		array_push($idArray, "p" . $row['id']);
		array_push($dateArray, str_replace("-", ".", $row['date']));
		array_push($preArray, $row['pre']);
		array_push($contentArray, $row['content']);
	}
	$idArray = array_reverse($idArray);
	$dateArray = array_reverse($dateArray);
	$preArray = array_reverse($preArray);
	$contentArray = array_reverse($contentArray);
?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css.css" />
	<link rel="stylesheet" type="text/css" href="homepage.css" />
	<title>二人のロケット</title>
	<script src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript">
		function toggleList(divClass) {
			$("div#" + divClass + " ul").slideToggle();
		}
	</script>
</head>

<body>
	<div id="page">

		<div><h1 id="title">二人のロケット</h1></div>

		<div id="intro">
			<div id="face" class="rightfloat"><a href="http://ja.wikipedia.org/wiki/%E5%8F%A4%E4%BA%95%E5%BC%98%E4%BA%BA"><img src="shu.png" alt="shu.png"/></a></div>
			<h1 id="headline">I'm everywhere:</h1>
			<table id="contact">
				<tr>
					<td>weibo:</td>
					<td><a href="http://www.weibo.com/taxiway">http://www.weibo.com/taxiway</a></td>
				</tr>
				<tr>
					<td>lofter:</td>
					<td><a href="http://taxiway.lofter.com/">http://taxiway.lofter.com/</a></td>
				</tr>
				<tr>
					<td>bangumi:</td>
					<td><a href="http://bgm.tv/user/taxiway">http://bgm.tv/user/taxiway</a></td>
				</tr>
				<tr>
					<td>mail:</td>
					<td><a href="mailto:hang.hang.zju@gmail.com">hang.hang.zju@gmail.com</a></td>
				</tr>
			</table>
		</div>
		
		<div id="leftbar">
			<div id="index">
				<p class="center" onclick="toggleList('index')">Index</p>
				<ul>
<?php
	$size = count($dateArray);
	for ($i = 0; $i < $size; ++$i) {
		echo '<li><a href="#', $idArray[$i], '">', $dateArray[$i], "</a></li>\n";
	}
?>
				</ul>
			</div>
			
			<div id="links">
				<p class="center" onclick="toggleList('links')">Links</p>
				<ul>
					<li>her
						<ul>
							<li><a href="http://mo.hhanger.com/">vera</a></li>
						</ul>
					<li>me
						<ul>
							<li><a href="http://taxiway.lofter.com/">lofter</a></li>
							<li><a href="http://www.weibo.com/taxiway">weibo</a></li>
						</ul>
					</li>
					<li>anime
						<ul>
							<li><a href="http://bgm.tv/user/taxiway">bangumi</a></li>
							<li><a href="http://bt.ktxp.com/">ktxp</a></li>
							<li><a href="http://u2.dmhy.org/">u2</a></li>
						</ul>
					</li>
					<li>music
						<ul>
							<li><a href="mp3tag.html">mp3 list</a></li>
							<li><a href="https://open.cd/">open.cd</a></li>
							<li><a href="http://jpopsuki.eu/">jpopsuki</a></li>
							<li><a href="http://www.japanao.com/forum.php?mod=forumdisplay&amp;fid=117">japanao</a></li>
							<li><a href="http://ja.wikipedia.org/wiki/%E3%83%A1%E3%82%A4%E3%83%B3%E3%83%9A%E3%83%BC%E3%82%B8">wiki japan</a></li>
						</ul>
					</li>
					<li>other
						<ul>
						<li><a href="http://acm.zju.edu.cn/~hhanger/ZJUerXTCer.html">ZJUerXTCer</a></li>
						</ul>
					</li>
				</ul>
			</div>
			
		</div>
		
		<div id="content">
<?php
	$size = count($dateArray);
	for ($i = 0; $i < $size; ++$i) {
		echo '<div class="paragraph" id="' . $idArray[$i] . '">' . "\n";
		if (strlen($preArray[$i]) != 0) {
			echo $preArray[$i] . "\n";
		}
		echo '<h2 class="date">' . $dateArray[$i] . "</h2>\n\n";
		echo $contentArray[$i] . "\n";
		echo "</div>\n";
	}
?>
		</div>
		
		<div id="rightbar">

			<div id="rss">
				<p class="center" onclick="toggleList('rss')">Recent Bangumi</p>
				<ul>
<?php
	$myfile = fopen("timeline.txt", "r") or die("Unable to open file!");
	$numberOfItems = 15;
	
	$map = array();
	$mapping = fopen("mapping.txt", "r") or die("Unable to open file!");
	while (!feof($mapping)) {
		$u = fgets($mapping);
		$u = substr($u, 0, strlen($u) - 1);
		$t = fgets($mapping);
		$t = substr($t, 0, strlen($t) - 1);
		$map[$u] = $t;
	}
	fclose($mapping);
	
	while (!feof($myfile)) {
		$line = fgets($myfile);
		$type = substr($line, 0, 6);
		if ($type == "看过" or $type == "在看" or $type == "搁置" or $type == "抛弃") {
			if ($type == "搁置") {
				$type = "搁置了";
			}
			$url = substr($line, 10 + strlen($type), strlen($line) - 10 - strlen($type));
			$other = strstr($url, '"');
			$url = substr($url, 0, strlen($url) - strlen($other));
			if (substr($url, 0, 1) == "/") {
				$url = "http:" . $url;
			}
			
			$title = "";
			if (array_key_exists($url, $map)) {
				$title = $map[$url];
			} else {
				$title = "ERROR!!";
			}
			
			echo "					<li>", $type, ' <a href="', $url, '">', $title, "</a></li>\n";
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
	
	<script>
		function caldelta(delta) {
			if (delta == 0 || delta <= 1 && delta >= -1) {
				return delta;
			}
			var neg = false;
			if (delta < 0) {
				neg = true;
				delta = -delta;
			}
			delta *= 0.1;
			if (delta < 1) {
				delta = 1;
			}
			if (neg) {
				delta = -delta;
			}
			return delta;
		}
		function scroll(){
			var t = 500;
			var offset = $("div#" + "rss").offset();
			var margin = parseFloat($("div#" + "rss").css("margin-top"));
			//alert(document.body.scrollTop + " " + offset.top + " " + margin);
			var d = document.documentElement.scrollTop || document.body.scrollTop;
			var delta = caldelta(d - offset.top);
			margin += delta;
			if (margin < 30) {
				margin = 30;
			}
			if (delta != 0 && margin != 30) {
				t = 10;
			}
			$("div#" + "rss").css("margin-top", margin + "px");
			$("div#" + "index").css("margin-top", margin + "px");
			setTimeout(arguments.callee, t);
		}
		scroll();
	</script>
</body>
</html>
