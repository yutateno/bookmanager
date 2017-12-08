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
	$first = "true";
	
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

			print("<table border=1>");
			
			while($row =$sql->fetch())
			{
				$code = $row['code'];
				$name = $row['name'];
				$author = $row['author'];
				$data = $row['data'];
				$loan = $row['loan'];

				if($first == "false")
				{
					print("<tr>");
				
					print("<td>".$name."</td>");
					print("<td>".$author."</td>");
					print("<td>".$data."</td>");
					print("<td>".$loan."</td>");

					print("</tr>");
				}

				$first = "false";
			}
			
			print("</table>");
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
		<?php else:?>      <!--ここエラーじゃね？-->
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>