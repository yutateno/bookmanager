<!--借りている本に関する内容を表示-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none";	  // ログインしたかどうか

	$code = "none"; // 書籍のID
	$name = "none"; // 書籍の名前
	$author = "none"; // 書籍の著者名
	$data = "none"; // 書籍の発行年月日
	$loan = "none"; // 書籍を借りている人がいるかどうか
	$loanid = "none"; // 貸出者

	$status = "none"; // 現在の状態
	$returnflag = "none"; // 返却したかどうか
	
	// ログインしていなかったらログイン画面に戻らせる
	if(!isset($_SESSION['id']) && empty($_SESSION['id']))    // $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
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
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  // エラーオブジェクトの作成

            // 以下処理
            
			// 借りている本の内容を取得
			if(!empty($_POST['code']))
			{
				$_SESSION['bookcode'] = $_POST['code'];
			}
			if(!empty($_SESSION['bookcode']))
			{
				$code = $_SESSION['bookcode'];
			}

			if(!empty($_POST['return']))
			{
				$returnflag = $_POST['return'];
			}

			$sql = $db->prepare("SELECT code, name, author, data FROM book");
			$sql->execute();

			while($row =$sql->fetch())
			{
				if($code == $row['code'])
				{
					$name = $row['name'];
					$author = $row['author'];
					$data = $row['data'];
					$status = "success";
					if($returnflag == "true")
					{
						$loan = "no";
						$loanid = "none";
					}
				}
			}

			if($returnflag == "true")
			{
				$sql = $db->prepare("UPDATE book SET loan = :loan, loanid = :loanid WHERE code = :code");
				$params = array(':loan' => $loan, ':loanid' => $loanid, ':code' => $code);
				$sql->execute($params);
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
		<?php if($loginget == "true") :?>    <!--ログイン済み-->
            <!--借りている本の内容を表示-->
			<h1>貸出中書籍詳細</h1>
			<?php if($status == "success") :?>
				<?php if($returnflag == "true") :?>
					返却が完了しました。<br>
				<?php else :?>
					<!--返却コマンドを表示-->
					<table border='1'>
                    	<tr><td bgcolor='#99FF99'>タイトル</td>
							<td bgcolor='#EEEEEE'><?=htmlspecialchars($name, ENT_QUOTES)?></td></tr>
                    	<tr><td bgcolor='#99FF99'>著者</td>
							<td bgcolor='#EEEEEE'><?=htmlspecialchars($author, ENT_QUOTES)?></td></tr>
                    	<tr><td bgcolor='#99FF99'>発行年月日</td>
							<td bgcolor='#EEEEEE'><?=htmlspecialchars($data, ENT_QUOTES)?></td></tr>
                	</table><br>
					<!--貸出コマンドを表示-->
					<form action="lended.php" method="POST">
						<input type="hidden" name="return" value="true">
						<input type="submit" value="返却">
					</form>
				<?php endif ?>
			<?php else :?>
				<?php if($returnflag == "true") :?>
					返却が完了しました。<br>
				<?php else :?>
					<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            		書籍が存在しませんでした。管理者に問い合わせてください
					<?=htmlspecialchars($status, ENT_QUOTES)?>
				<?php endif ?>
            	<a href = './top.php'>トップ画面へ</a>
			<?php endif ?>
			<form action ='./lendlist.php' method ='POST'><input type ='submit' value ='戻る'></form>
		<?php else:?>
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>