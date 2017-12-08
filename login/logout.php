<?php
    session_start();
    $_SESSION = array(); 

    session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>kelp本棚</title>
	</head>
	<body>
		<h1>ログアウト</h1>
		<a href = "../index.php">ログイン画面へ</a>
	</body>
</html>
