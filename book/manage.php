<!--管理者ページ-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ログインしたかどうか
	$yourmanage = "none";		// 管理者かどうか
	
	// ログインしていなかったらログイン画面に戻らせる
	if(!isset($_SESSION['id']) && empty($_SESSION['id']))			// $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
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
			$yourmanage = $_SESSION['manager'];	// 管理者か判断

			if($yourmanage == "no")		// 管理者じゃなかったら
			{
				header("Location: ./../index.php");
				exit();
			}
			else
			{

			}
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
		<?php if($loginget == "true" && $yourmanage == "yes") :?>		<!--ログイン済みであり管理者である-->
            <h1>管理者ページ</h1>
				<br>
				<a href="Location : ./book/popup.php">書籍追加</a><br><br>
				<a href="Location : ./book/deletelist.php">書籍削除</a><br><br>
				<a href="Location : ./book/lenderhuman.php">借りている人の表示</a><br><br>
				<br>
		<?php else:?>
			<?php 
				header("Location : ./../index.php");
				exit();
			?>
		<?php endif ?>
	</body>
</html>