<!--借りている人の一覧表示-->
<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ログインしたかどうか
	$yourmanage = "none";		// 管理者かどうか

	$code = "none"; // 書籍番号
	$name = "none"; // 書籍名
	$author = "none"; // 書籍著者
	$data = "none"; // 書籍発行年月日
	$loan = "none"; // 書籍貸出有無
	$loanid = "none"; // 書籍貸出中の人ID

	$booknum = "none"; // 貸出されている書籍のリスト数
	
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
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成

			// 以下処理
            $yourmanage = $_SESSION['manager'];	// 管理者か判断
            
			// 書籍を取得して、書籍を借りている人だけを取得する
			$sql = $db->prepare("SELECT code, name, author, data, loan, loanid FROM book WHERE loan = 'yes'");
			$sql->execute();
			
			while($row =$sql->fetch())
			{
				$rows[] = $row;
				$booknum = "some";
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
            <!--借りている人の一覧「書籍名」「人」「借りた日」-->
			<h1>書籍貸し出し中のみ一覧</h1>
			<?php if($booknum == "none") :?>
				削除できる書籍が存在しません。<br>
			<?php else :?>
				<table border='1'>
					<tr bgcolor='#99FF99'><td>タイトル</td><td>著者</td><td>発行年月日</td><td>貸出有無</td><td>貸出者</td></tr>
					<?php
					foreach($rows as $row){
					?>
					<tr bgcolor='#EEEEEE'><td><?=htmlspecialchars($row['name'], ENT_QUOTES)?></td>
					<td><?=htmlspecialchars($row['author'], ENT_QUOTES)?></td>
					<td><?=htmlspecialchars($row['data'], ENT_QUOTES)?></td>
					<td><?=htmlspecialchars($row['loan'], ENT_QUOTES)?></td>
					<td><?=htmlspecialchars($row['loanid'], ENT_QUOTES)?></td>
					<?php
					}
					?>
            	</table><br>
			<?php endif ?>
			<form action ='./manage.php' method ='POST'><input type ='submit' value ='戻る'></form>
		<?php else:?>					<!--ログインしてない-->
			<h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../index.php'>ログイン画面へ</a>
		<?php endif ?>
	</body>
</html>