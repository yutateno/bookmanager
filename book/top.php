<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ログインしたかどうか
	$yourmanage = "none";		// 管理者かどうか
	
	// ログインしていなかったらログイン画面に戻らせる
	if(empty($_SESSION['id']))			// $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
	{
		$loginget = "false";
		header("Location: ./../index.php");
		exit();
	}
	else
	{
		$loginget = "true";
		try
		{
			$db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成

			// 以下処理
			$yourmanage = $_SESSION['namager'];	// 管理者か判断
		}
		catch(PDOException $e)
		{
			exit('データベース処理失敗:'.$e->getMessage());
		}
	}
?>

<!--html-->
<!DOCTYPE html>
<html lang ="ja">
	<head>
		<meta charset ="utf-8">
		<title></title>
	</head>
	<body>
		<?php if($loginget == "true") :?>		<!--ログイン済み-->
			<?php if($yourmanage == "yes"):?>					<!--管理者の場合-->
				<h1>コンピュータ部備品管理</h1>
				<br>
				<a href="Location : ./book/list.php">一覧</a><br><br>
				<a href="Location : ./book/lendlist.php">借りている本</a><br><br>
				<a href="Location : ./book/manage.php">管理</a><br><br>
				<br>
			<?php else :?>		<!--一般の場合-->
				<h1>コンピュータ部備品管理</h1>
				<br>
				<a href="Location : ./book/list.php">一覧</a><br><br>	<!--URLの指定場所がいまいちわかってないのでとりあえず適当--><!--URLの指定場所がいまいちわかってないのでとりあえず適当-->
				<a href="Location : ./book/lendlist.php">借りている本</a><br><br>
				<br>
			<?php endif ?>
		<?php else:?>					<!--ログインしてない-->
			<?php 
				header("Location : ./../index.php");
				exit();
			?>
		<?php endif ?>
	</body>
</html>