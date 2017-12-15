<?php
    session_start();

    $status = "none";
    if(isset($_SESSION['id']) && $_SESSION['manager'] == 'yes')
    {
        try{
            $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成
            
            $sql =$db->prepare("SELECT id,name,manager FROM user");
            $sql->execute();
            
            $status ="success";    
            
        }
        catch(PDOException $e)
        {
            exit('データベース処理失敗:'.$e->getMessage());   
        }
    }
    else
    {
        header("Location: ../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset = "UTF-8">
        <title>ユーザー一覧</title>
        <a href = '../user/logout.php'>ログアウト</a>
		&emsp;
		<a href ='../user/index.php'>ユーザーメニュー</a>
		<?php
		    if($_SESSION['manager'] == "yes")
		    {
		            echo "<span style ='float:right'><a href = 'index.php'>管理者メニュー</a></span>";
		    }
		?>
		<hr>
    </head>
    <body>
        <h1>ユーザー一覧</h1>
        <?php if($status =="success") : ?>
            <table>
                <tr bgcolor='#99FF99'><th>ID</th><th>NAME</th><th>MANAGER</th></tr>
                <?php while($row =$sql->fetch()){ echo "<tr bgcolor='#EEEEEE'><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['manager']  . "</td></tr>"; }?>
            </table>
            <form action ='index.php' method ='POST'><p><input type ='submit' value ='戻る'></p></form>
        <?php endif; ?>
    </body>
</html>
