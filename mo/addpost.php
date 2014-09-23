<?php
	include 'util.php';
	initialize();
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css.css" />
		<title>Add post</title>
		<script src="jquery-1.11.1.min.js"></script>
	</head>
	
	<body>
<?php
	if (array_key_exists("logged", $_SESSION) && $_SESSION["logged"]) {
		$date = $pre = $content = $type = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$con = connectDB();
			if (!empty($_POST["date"])) {
				$date = $_POST["date"];
			}
			if (!empty($_POST["pre"])) {
				$pre = $_POST["pre"];
			}
			if (!empty($_POST["content"])) {
				$content = $_POST["content"];
			}
			if (!empty($_POST["type"])) {
				$type = $_POST["type"];
			}
			echo $date. $pre. $content. $type;
			
			$query = "INSERT INTO posts (date, pre, content) VALUES ('" .
				$date . "', '" . $pre . "', '" . $content . "')";
			mysql_query($query);
			mysql_close($con);
		}
	} else {
		echo "<script>location.href='admin.php';</script>";
	}
?>
	</body>
</html>