<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ログインしたかどうか
	$yourmanage = "none";		// 管理者かどうか
	
	// ログインしていなかったらログイン画面に戻らせる
	if(!isset($_SESSION['id']) && empty($_SESSION['id']))			// $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
	{
		$loginget = "false";
		header("Location: ../index.php");
		exit();
	}
	else
	{
		$loginget = "true";
		try
		{
			$db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーオブジェクトの作成

			// 以下処理
			$yourmanage = $_SESSION['manager'];	// 管理者か判断
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
		<a href = '../login/logout.php'>ログアウト</a>
		&emsp;
		<a href ='./top.php'>ユーザーメニュー</a>
		<?php
		    if($_SESSION['manager'] == "yes")
		    {
		            echo "<span style ='float:right'><a href = '../manager/index.php'>管理者メニュー</a></span>";
		    }
		?>
		<hr>
	</head>
	<body>
		<?php if($loginget == "true") :?>		<!--ログイン済み-->
			<h1>コンピュータ部備品管理</h1>
			<br>
			<a href="./list.php">一覧</a><br><br>	<!--URLの指定場所がいまいちわかってないのでとりあえず適当 >> 直した--><!--URLの指定場所がいまいちわかってないのでとりあえず適当-->
			<a href="./lendlist.php">借りている本</a><br><br>
			<?php if($yourmanage == "yes") echo "<a href='./manage.php'>管理</a><br><br>";?>    <!--管理者の場合-->
			<br>
		<?php else:?>      <!--ここエラーじゃね？-->
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>