<!--借りている人の一覧表示-->
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
            
            // 書籍を取得して、書籍を借りている人だけを取得する
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
		<?php if($loginget == "true" && $yourmanage == "YES") :?>		<!--ログイン済みであり管理者である-->
            <!--借りている人の一覧「書籍名」「人」「借りた日」-->
		<?php else:?>					<!--ログインしてない-->
			<?php 
				header("Location : ./../index.php");
				exit();
			?>
		<?php endif ?>
	</body>
</html>