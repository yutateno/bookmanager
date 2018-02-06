<!--貸し出すときの一覧-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none"; // ログインしたかどうか

	$code = "none"; // 書籍番号
	$name = "none"; // 書籍名
	$author = "none"; // 書籍著者
	$data = "none"; // 書籍発行年月日
	$loan = "none"; // 書籍貸出有無
	
	// ログインしていなかったらログイン画面に戻らせる
	if(!isset($_SESSION['id']) && empty($_SESSION['id'])) // $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
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
            
			// 一覧を取得
			if(!empty($_POST["searchname"]) && isset($_POST["searchname"]))
			{
				$sql = $db->prepare("SELECT code, name, author, data, loan FROM book WHERE name LIKE :searchname");
				$sql->bindValue(':searchname','%'.$_POST["searchname"].'%');
			}
			else
			{
				$sql = $db->prepare("SELECT code, name, author, data, loan FROM book");
			}
			
			$sql->execute();
			
			while($row =$sql->fetch())
			{
				$rows[] = $row;
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
            <!--一覧を表示-->
			<h1>書籍一覧</h1>
			<form action ="list.php" method ="POST"><input type ="text" name ="searchname">&emsp;&emsp;<input type ="submit" value ="検索"></form>
			<br>
			<table border='1'>
				<tr bgcolor='#99FF99'><td>タイトル</td><td>著者</td><td>発行年月日</td><td>貸出有無</td></tr>
				<?php
				foreach($rows as $row){
				?>
				<tr bgcolor='#EEEEEE'><td><?=htmlspecialchars($row['name'], ENT_QUOTES)?></td>
				<td><?=htmlspecialchars($row['author'], ENT_QUOTES)?></td>
				<td><?=htmlspecialchars($row['data'], ENT_QUOTES)?></td>
				<td><?php if($row['loan'] == 'yes') :?>
						有
					<?php else:?>
						無
					<?php endif ?>
				</td>
				<td>
					<form action="lend.php" method="POST">
						<input type="submit" value="詳細">
						<input type="hidden" name="code" value="<?=$row['code']?>">
					</form>
				</td>
				<?php
				}
				?>
            </table><br>
			<form action ='./top.php' method ='POST'><input type ='submit' value ='戻る'></form>
		<?php else:?>      <!--ここエラーじゃね？-->
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>