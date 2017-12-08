<!--貸し出すときの一覧-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none"; // ログインしたかどうか

	$code = "none";
	$name = "none";
	$author = "none";
	$data = "none";
	$loan = "none";
	//$first = "true";
	
	// ログインしていなかったらログイン画面に戻らせる
	if(!isset($_SESSION['id']) && empty($_SESSION['id'])) // $_SESSIONってサーバーに保存されてるものだから他PHPでも引っ張れるのかなと
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
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // エラーオブジェクトの作成

            // 以下処理
            
			// 一覧を取得
			$sql = $db->prepare("SELECT code, name, author, data, loan FROM book");
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
	</head>
	<body>
		<?php if($loginget == "true") :?>		<!--ログイン済み-->
            <!--一覧を表示-->
			<table>
				<tr><td>タイトル</td><td>著者</td><td>発行年月日</td><td>貸出有無</td></tr>
				<?php
				foreach($rows as $row){
				?>
				<tr><td><?=htmlspecialchars($row['name'], ENT_QUOTES)?></td>
				<td><?=htmlspecialchars($row['author'], ENT_QUOTES)?></td>
				<td><?=htmlspecialchars($row['data'], ENT_QUOTES)?></td>
				<td><?=htmlspecialchars($row['loan'], ENT_QUOTES)?></td>
				<td>
					<form action="lend.php" method="post">
						<input type="submit" value="詳細">
						<input type="hidden" name="code" value="<?=$row['code']?>">
					</form>
				</td>
				<?php
				}
				?>
            </table><br>
		<?php else:?>      <!--ここエラーじゃね？-->
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>