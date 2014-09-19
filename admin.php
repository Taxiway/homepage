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
		<link rel="stylesheet" type="text/css" href="admin.css" />
		<title>Admin</title>
		<script src="jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			function check() {
				return (confirm("Submit?"));
			}
			
			function showPreview() {
				$(".paragraph").empty();
				var date = $("input[name='date']").val();
				date = date.replace(/-/g, ".");
				var pre = $("textarea[name='pre']").val();
				var content = $("textarea[name='content']").val();
				$(".paragraph").append(pre);
				$(".paragraph").append('<h2 class="date">' + date + "</h2>");
				$(".paragraph").append(content);
			}
		</script>
	</head>
	
	<body>
	
	<div id="loginform">
		<h1 id="title">Login to admin page</h1>
		<form action="checklogin.php" name="loginform" method="post">
			<p class="label">Name</p>
			<div class="form"><input name="name" type="text"></input></div>
			<p class="label">Password</p>
			<div class="form"><input name="password" type="password"></input></div>
			<div class="form"><input value="Login" type="submit"></input></div>
		</form>
	</div>
	
	<div id="addpostform">
		<h1 id="title">Add Post</h1>
		<form id="form" action="addpost.php" onsubmit="return check()" name="loginform" method="post">
			<p class="label">Date</p>
<?php
	echo '<div class="form"><input name="date" type="date" value="20';
	echo date('y-m-d', time());
	echo '"></input></div>';
?>
			<p class="label">Pre-paragraph</p>
			<div class="form"><textarea name="pre" rows="10" cols="80"></textarea></div>
			<p class="label">Content</p>
			<div class="form"><textarea name="content" rows="20" cols="80"></textarea></div>
			<div class="form">
				<span>HTML:</span>
				<input type="radio" name="type" value="html" checked="checked"></input>
				<span">Plain:</span>
				<input type="radio" name="type" value="plain"></input>
			</div>
			<div class="form">
				<input type="button" value="Preview" onclick="showPreview()"></input>
				<input type="submit"></input>
			</div>
		</form>
	</div>
	
	<div id="preview">
		<div class="paragraph">
		</div>
	</div>
	
<?php
	function showLoginForm() {
		echo '<script>$("#addpostform").remove(); $("#preview").remove();</script>';
	}
	
	function showAdminPage() {
		echo '<script>$("#loginform").remove();</script>';
		$con = connectDB();
		mysql_close($con);
	}

	if (array_key_exists("logged", $_SESSION) && $_SESSION["logged"]) {
		showAdminPage();
	} else {
		showLoginForm();
	}
?>
	</body>
</html>