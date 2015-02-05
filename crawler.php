<?php
	$myfile = fopen("http://bgm.tv/feed/user/taxiway/timeline", "r") or die("Unable to open file!");
	$localtimeline = fopen("./public_html/timeline.txt", "w");

    $numberOfItems = 15;
	
	$map = array();
	$mapping = fopen("./public_html/mapping.txt", "r") or die("Unable to open file!");
	while (!feof($mapping)) {
		$u = fgets($mapping);
		$u = substr($u, 0, strlen($u) - 1);
		$t = fgets($mapping);
		$t = substr($t, 0, strlen($t) - 1);
		$map[$u] = $t;
	}
	fclose($mapping);
	
	$mapping = fopen("./public_html/mapping.txt", "w") or die("Unable to open file!");
	
	while (!feof($myfile)) {
		$line = fgets($myfile);
		fwrite($localtimeline, $line);
		$type = substr($line, 0, 6);
		if ($type == "看过" or $type == "在看" or $type == "搁置" or $type == "抛弃" or $type == "读过") {
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
				$page = fopen($url, "r") or die("Unable to open file!");
				while (!feof($page)) {
					$line = fgets($page);
					if (substr($line, 0, 7) == "<title>") {
						$title = substr($line, 7, strlen($line) - 7 - 9);
					}
				}
			}
			
			fwrite($mapping, $url);
			fwrite($mapping, "\n");
			fwrite($mapping, $title);
			fwrite($mapping, "\n");
			
			if (--$numberOfItems == 0) {
				break;
			}
		}
	}
	fclose($localtimeline);
	fclose($mapping);
	fclose($myfile);
?>
