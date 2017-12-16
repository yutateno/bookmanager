<!--deletelistから選択された削除する参考書の説明と削除ボタン-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ログインしたかどうか
	$yourmanage = "none";		// 管理者かどうか

	$code = "none"; // 書籍のID
	$name = "none"; // 書籍の名前
	$author = "none"; // 書籍の著者名
	$data = "none"; // 書籍の発行年月日
	$loan = "none"; // 書籍を借りている人がいるかどうか

	$status = "none"; // 現在の状態
	$deleteflag = "none"; // 削除したかどうか
	
	// ログインしていなかったらログイン画面に戻らせる
	if(!isset($_SESSION['id']) && empty($_SESSION['id']) )			// $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
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
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成

			// 以下処理
            $yourmanage = $_SESSION['manager'];	// 管理者か判断
            
			// 書籍の内容を取得
			// 書籍の内容を取得
			if(!empty($_POST['code']))
			{
				$_SESSION['bookcode'] = $_POST['code'];
			}
			if(!empty($_SESSION['bookcode']))
			{
				$code = $_SESSION['bookcode'];
			}

			if(!empty($_POST['delete']))
			{
				$deleteflag = $_POST['delete'];
			}

			$sql = $db->prepare("SELECT code, name, author, data, loan FROM book");
			$sql->execute();

			while($row =$sql->fetch())
			{
				if($code == $row['code'])
				{
					$name = $row['name'];
					$author = $row['author'];
					$data = $row['data'];
					$loan = $row['loan'];
					$status = "success";
				}
			}

			if($deleteflag == "true")
			{
				$sql = $db->prepare("DELETE FROM book WHERE code = $code");
				$sql->execute();
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
		<?php if($loginget == "true" && $yourmanage == "yes") :?>		<!--ログイン済みであり管理者のとき-->
            <!--参考書の内容を表示と削除のコマンド-->
			<h1>書籍削除詳細</h1>
			<?php if($status == "success") :?>
				<!--削除コマンドを表示-->
				<?php if($deleteflag == "true") :?>
					削除が完了しました。<br>
				<?php else :?>
					<table border='1'>
                    	<tr><td bgcolor='#99FF99'>タイトル</td>
							<td bgcolor='#EEEEEE'><?=htmlspecialchars($name, ENT_QUOTES)?></td></tr>
                    	<tr><td bgcolor='#99FF99'>著者</td>
							<td bgcolor='#EEEEEE'><?=htmlspecialchars($author, ENT_QUOTES)?></td></tr>
                    	<tr><td bgcolor='#99FF99'>発行年月日</td>
							<td bgcolor='#EEEEEE'><?=htmlspecialchars($data, ENT_QUOTES)?></td></tr>
                    	<tr><td bgcolor='#99FF99'>貸出有無</td>
							<td bgcolor='#EEEEEE'>無</td></tr>
                	</table><br>
					<?php if($loan == "no") :?> <!--貸出コマンドを表示-->
						<form action="delete.php" method="POST">
							<input type="hidden" name="delete" value="true">
							<input type="submit" value="削除">
						</form>
					<?php else :?>
						たった今誰かが貸し出しました。
					<?php endif ?>
				<?php endif ?>
				<br>
				<form action ='./deletelist.php' method ='POST'><input type ='submit' value ='戻る'></form>
			<?php else :?>
				<?php if($deleteflag == "true") :?>
					削除が完了しました。<br>
					<form action ='./deletelist.php' method ='POST'><input type ='submit' value ='戻る'></form>
				<?php else :?>
					<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            		書籍が存在しませんでした。管理者に問い合わせてください
					<?=htmlspecialchars($status, ENT_QUOTES)?>
            		<a href = './top.php'>トップ画面へ</a>
				<?php endif ?>
			<?php endif ?>
		<?php else:?>
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>