<!--貸出の本を選択した後の画面-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ログインしたかどうか

	$code = "none"; // 書籍のID
	$name = "none"; // 書籍の名前
	$author = "none"; // 書籍の著者名
	$data = "none"; // 書籍の発行年月日
	$loan = "none"; // 書籍を借りている人がいるかどうか
	$loanid = "none"; // 貸出者

	$status = "none"; // 現在の状態
	$loanflag = "none"; // 貸出したかどうか

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
			// 書籍の内容を取得
			if(!empty($_POST['code']))
			{
				$_SESSION['bookcode'] = $_POST['code'];
			}
			if(!empty($_SESSION['bookcode']))
			{
				$code = $_SESSION['bookcode'];
			}

			if(!empty($_POST['loan']))
			{
				$loanflag = $_POST['loan'];
			}

			$sql = $db->prepare("SELECT code, name, author, data, loan, loanid FROM book");
			$sql->execute();

			while($row =$sql->fetch())
			{
				if($code == $row['code'])
				{
					$name = $row['name'];
					$author = $row['author'];
					$data = $row['data'];
					$loan = $row['loan'];
					$loanid = $row['loanid'];
					$status = "success";
					if($loanflag == "true")
					{
						$loan = "yes";
						$loanid = $_SESSION['id'];
					}
				}
			}

			if($loanflag == "true")
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
		<?php if($loginget == "true") :?>		<!--ログイン済み-->
            <!--書籍の内容を表示-->
			<h1>書籍詳細</h1>
			<?php if($status == "success") :?>
				<!--借りるコマンドを表示-->
				<table>
                    <tr><td>タイトル</td>
						<td><?=htmlspecialchars($name, ENT_QUOTES)?></td></tr>
                    <tr><td>著者</td>
						<td><?=htmlspecialchars($author, ENT_QUOTES)?></td></tr>
                    <tr><td>発行年月日</td>
						<td><?=htmlspecialchars($data, ENT_QUOTES)?></td></tr>
                    <tr><td>貸出有無</td>
						<td>
							<?php if($loan == "yes") :?>
								有
							<?php else :?>
								無
							<?php endif ?></td></tr>
                </table><br>
				<?php if($loan == "no") :?> <!--貸出コマンドを表示-->
					<form action="lend.php" method="POST">
						<input type="hidden" name="loan" value="true">
						<input type="submit" value="借りる">
					</form>
				<?php else :?>
					<?php if($loanid == $_SESSION['id']) :?> <!--ユーザーが貸し出しているとき-->
						あなたが貸し出しています。
					<?php else :?>
						貸し出している人がいるため借りられません
					<?php endif ?>
				<?php endif ?>
				<form action ='./list.php' method ='POST'><input type ='submit' value ='戻る'></form>
			<?php else :?>
				<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            	書籍が存在しませんでした。管理者に問い合わせてください
				<?=htmlspecialchars($status, ENT_QUOTES)?>
            	<a href = './top.php'>トップ画面へ</a>
			<?php endif ?>	
		<?php else:?>      <!--ここエラーじゃね？-->
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>