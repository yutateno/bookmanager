<!--書籍追加のページ-->
<!--PHP-->
<?php
	session_start();
	
	$loginget = "none";		// ログインしたかどうか
	$yourmanage = "none";	// 管理者かどうか
	$inputerror = "false";	// 未入力エラー
	$status = "none";		// 状態

	// 書籍情報
	$barcode = "none";		// バーコード
	$title = "none";		// タイトル
	$author = "none";		// 著者
	$data = "none";			// 発行年月日
	
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
				header("Location: ../index.php");
				exit();
			}
			else
			{
				if(!empty($_POST['bookid']) && !empty($_POST['booktitle'])
				&& !empty($_POST['author']) && !empty($_POST['issuedata']))	// 入力済み
				{
					$inputerror = "false";

					$barcode = $_POST['bookid'];
					$title = $_POST['booktitle'];
					$author = $_POST['author'];
					$data = $_POST['issuedata'];

					$sql =$db->prepare("INSERT INTO book VALUES('$barcode', '$title', '$author', '$data', 'no', 'none')");
					$sql->execute();

					$status = "success";
				}
				else if(!empty($_POST['bookid']) || !empty($_POST['booktitle'])
				|| !empty($_POST['author']) || !empty($_POST['issuedata']))	// 入力不足
				{
					$inputerror = "true";
					$status = "none";
				}
				else
				{
					$inputerror = "false";
					$status = "none";
				}
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
		<?php if($loginget == "true" && $yourmanage == "yes") :?>		<!--ログイン済みであり管理者である-->
            <!--追加する書籍に必要なコマンド-->
			<h1>書籍追加</h1>
			追加する書籍の情報を入力してください。<br>
			<form action = "popup.php" method = "POST">
                <table border='1'>
                    <tr><td bgcolor='#99FF99'>バーコード</td><td bgcolor='#EEEEEE'><input type ="text" name ="bookid"></td></tr>
                    <tr><td bgcolor='#99FF99'>タイトル</td><td bgcolor='#EEEEEE'><input type ="text" name ="booktitle"></td></tr>
                    <tr><td bgcolor='#99FF99'>著者</td><td bgcolor='#EEEEEE'><input type ="text" name ="author"></td></tr>
                    <tr><td bgcolor='#99FF99'>発行年月日</td><td bgcolor='#EEEEEE'><input type ="text" name ="issuedata"></td></tr>
                </table><br>
                <input type ="submit" value ="登録">
            </form>
			<?php if($status == "success") echo "登録完了しました";?>
			<?php if($inputerror == "true") echo "未入力があります。";?>
			<br>
			<form action ='./manage.php' method ='POST'><input type ='submit' value ='戻る'></form>
		<?php else:?>
		<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>