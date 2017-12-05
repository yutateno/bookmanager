<!--自身が借りている本を表示-->
<!--PHP-->
<?php
	session_start();
    $loginget = "none";		// ログインしたかどうか
    $userID = "none";       // ユーザーID
	
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
            
            // 自身のIDから借りている本を取得する
            $userID = $_SESSION['id'];
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
            <!--借りている本の一覧を表示-->
		<?php else:?>
			<?php 
				header("Location : ./../index.php");
				exit();
			?>
		<?php endif ?>
	</body>
</html>