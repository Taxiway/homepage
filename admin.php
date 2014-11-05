<?php
	include 'util.php';
	initialize();
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
	echo "<script>\n";
	echo 'idArr = ' . json_encode($idArray) . ";\n";
	echo 'dateArr = ' . json_encode($dateArray) . ";\n";
	echo 'preArr = ' . json_encode($preArray) . ";\n";
	echo 'contentArr = ' . json_encode($contentArray) . ";\n";
	echo "</script>\n";
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css.css" />
		<link rel="stylesheet" type="text/css" href="posts.css" />
		<link rel="stylesheet" type="text/css" href="admin.css" />
		<title>Admin</title>
		<script src="jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			function check() {
				return (confirm("Submit?"));
			}
			
			function showPreview() {
				$("#preview .paragraph").empty();
				var date = $("input[name='date']").val();
				date = date.replace(/-/g, ".");
				var pre = $("textarea[name='pre']").val();
				var content = $("textarea[name='content']").val();
				$("#preview .paragraph").append(pre);
				$("#preview .paragraph").append('<h2 class="date">' + date + "</h2>");
				$("#preview .paragraph").append(content);
			}
			
			function selectMenu(menuID) {
				$(".menuitem").css("background-color", "#515873");
				$("#" + menuID).css("background-color", "#963412");
			}
			
			function addContent(toAdd) {
				var content = $("textarea[name='content']").val();
				var textarea = document.getElementById("content");
				var start = textarea.selectionStart, end = textarea.selectionEnd;
				var newContent = content.substring(0, start) + toAdd + content.substring(end, content.length);
				$("textarea[name='content']").val(newContent);
				textarea.setSelectionRange(start + toAdd.length, start + toAdd.length);
				textarea.focus();
			}
			
			function getSelectedText() {
				var content = $("textarea[name='content']").val();
				var textarea = document.getElementById("content");
				var start = textarea.selectionStart, end = textarea.selectionEnd;
				return content.substring(start, end);
			}
			
			function addTag(obj) {
				var tag = obj.value;
				var openTag = tag, closeTag = tag;
				if (tag == "a") {
					openTag = 'a href=""';
				}
				var addString = "<" + openTag + ">" + getSelectedText() + "</" + closeTag + ">";
				addContent(addString);
			}
			
			function showLoginForm() {
				$("#addpostform").hide();
				$("#preview").hide();
				$("#menu").hide();
				$("#posts").hide();
			}
			
			function showAddPostForm() {
				$("#loginform").hide();
				$("#posts").hide();
				$("#addpostform").show();
				$("#preview").show();
				$(".editonly").hide();
				$("#addpostform input[value='Add']").show();
				$("#addpostform input[value='Modify']").hide();
				$("input[name='id']").val("NAN");
				
				selectMenu("addpost");
			}
			
			function showEditPostForm() {
				$("#loginform").hide();
				$("#preview").hide();
				$("#posts").show();
				$("#addpostform").hide();
				selectMenu("editpost");
			}
			
			function modifyPost(id) {
				$("#loginform").hide();
				$("#posts").hide();
				$("#addpostform").show();
				$("#preview").show();
				$(".editonly").show();
				$("#addpostform input[value='Add']").hide();
				$("#addpostform input[value='Modify']").show();
				
				for (var i = 0; i < idArr.length; ++i) {
					if (id == idArr[i]) {
						var date = dateArr[i];
						date = date.replace(/\./g, "-");
						var pre = preArr[i];
						var content = contentArr[i];
						$("input[name='id']").val(id.substring(1));
						$("input[name='date']").val(date);
						$("textarea[name='pre']").val(pre);
						$("textarea[name='content']").val(content);
					}
				}
				showPreview();
			}
			
			function deletePost(id) {
				if (confirm("Are you sure to delete the post?")) {
					location.href = 'editpost.php?id=' + id;
				}
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
	
	<div id="menu">
		<ul>
			<li><a href="#" class="menuitem" id="addpost" onclick="showAddPostForm()">Add Post</a></li>
			<li><a href="#" class="menuitem" id="editpost" onclick="showEditPostForm()">Edit Post</a></li>
		</ul>
		<p class="clear"></p>
	</div>
	<div id="addpostform">
		<form id="form" action="editpost.php" onsubmit="return check()" name="addpostform" method="post">
			<p class="label editonly">ID</p>
			<div class="form editonly"><input name="id" type="text" readonly="readonly"></input></div>
			<p class="label">Date</p>
<?php
	echo '<div class="form"><input name="date" type="date" value="20';
	echo date('y-m-d', time());
	echo '"></input></div>';
?>
			<p class="label">Pre-paragraph</p>
			<div class="form"><textarea name="pre" rows="10" cols="80"></textarea></div>
			<p class="label">Content</p>
			<div class="form"><textarea id="content" name="content" rows="20" cols="80"></textarea></div>
			<div id="buttons">
				<pre>Tags</pre>
				<input type="button" name="p" value="p" onclick="addTag(this)"></button>
				<input type="button" name="ul" value="ul" onclick="addTag(this)"></button>
				<input type="button" name="ol" value="ol" onclick="addTag(this)"></button>
				<input type="button" name="li" value="li" onclick="addTag(this)"></button>
				<input type="button" name="a" value="a" onclick="addTag(this)"></button>
				<pre>Special Char</pre>
				<input type="button" name="t" value="\t" onclick="addContent('\t')"></button>
			</div>
			<div class="form">
				<span>HTML:</span>
				<input type="radio" name="type" value="html" checked="checked"></input>
				<span">Plain:</span>
				<input type="radio" name="type" value="plain"></input>
			</div>
			<div class="form">
				<input type="button" value="Preview" onclick="showPreview()"></input>
				<input type="submit" value="Add"></input>
				<input type="submit" value="Modify"></input>
			</div>
		</form>
	</div>
	
	<div id="preview">
		<div class="paragraph">
		</div>
	</div>
	
	<div id="posts">
<?php
	$size = count($dateArray);
	for ($i = 0; $i < $size; ++$i) {
		echo '<div class="paragraph" id="' . $idArray[$i] . '">' . "\n";
		if (strlen($preArray[$i]) != 0) {
			echo $preArray[$i] . "\n";
		}
		echo '<h2 class="date">' . $dateArray[$i] . "</h2>\n\n";
		echo $contentArray[$i] . "\n";
		echo '<input type="button" value="Modify" onclick="modifyPost(' .
			"'" . $idArray[$i] . "'" . ')"></input>' . "\n";
		echo '<input type="button" value="Delete" onclick="deletePost(' .
			"'" . $idArray[$i] . "'" . ')"></input>' . "\n";
		echo "</div>\n";
	}
?>
	</div>
	
<?php
	function showLoginForm() {
		echo '<script>showLoginForm();</script>';
	}
	
	function showAdminPage() {
		echo '<script>showAddPostForm();</script>';
	}

	if (array_key_exists("logged", $_SESSION) && $_SESSION["logged"]) {
		showAdminPage();
	} else {
		showLoginForm();
	}
?>
	</body>
</html>