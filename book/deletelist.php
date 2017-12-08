<!--削除するときの一覧-->
<!--PHP-->
<?php
	session_start();
    $loginget = "none";		// ログインしたかどうか
    $manager = "none";      // 管理者か判断
	
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
            $manager = $_SESSION['manager'];	// 管理者か判断

            // 一覧を取得
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
		<?php if($loginget == "true" && $manager == "yes") :?>		<!--ログイン済みであり管理者のとき-->
            <!--一覧を表示-->
		<?php else:?>
			<?php 
				header("Location : ./../index.php");
				exit();
			?>
		<?php endif ?>
	</body>
</html>