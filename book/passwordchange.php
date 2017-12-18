<?php
    session_start();
    $status ="none";
    $inputerror ="false";
    $passerror ="false";
    if(isset($_SESSION["id"]))
    {
        if(isset($_POST['nowpass']) && !empty($_POST['nowpass']) && isset($_POST['newpass']) && !empty($_POST['newpass']) && isset($_POST['renewpass']) && !empty($_POST['renewpass']))
        {
            $nowpass =$_POST['nowpass'];
            $newpass =$_POST['newpass'];
            $renewpass =$_POST['renewpass'];

            if($newpass == $renewpass)
            {
                try{
                    $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber');
                    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                    $sql =$db->prepare("SELECT password FROM user WHERE id = ? ");
                    $sql->bindValue(1,$_SESSION['id']);
                    $sql->execute();

                    $row =$sql->fetch();
                    if(password_verify($nowpass, $row[0]))
                    {
                        $hash =  password_hash($newpass, PASSWORD_DEFAULT);
                        $sql =$db->prepare("UPDATE user  SET password = ? WHERE id = ? ");
                        $sql->bindValue(1,$hash);
                        $sql->bindValue(2,$_SESSION['id']);
                        $sql->execute();
                        $status ="success";
                    }
                    else
                    {
                        $status ="change";
                        $passerror ="nowpass";
                    }
                }
                catch(PDOException $e)
                {
                    exit('データベース処理失敗:'.$e->getMessage());   
                }
            }
            else
            {
                $status ="change";
                $passerror ="repass";
            }
        }
        else if((isset($_POST['nowpass']) && !empty($_POST['nowpass'])) || (isset($_POST['newpass']) && !empty($_POST['newpass'])) || (isset($_POST['renewpass']) && !empty($_POST['renewpass'])))
        {
            $status ="change";
            $inputerror ="true";
        }
        else
        {
            $status ="change";
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
		<meta charset="UTF-8">
		<title>kelp連絡網-パスワード変更</title>
		<a href = '../login/logout.php'>ログアウト</a>
		&emsp;
		<a href ='top.php'>ユーザーメニュー</a>
		<?php
		    if($_SESSION['manager'] == "yes")
		    {
		            echo "<span style ='float:right'><a href = '../manager/index.php'>管理者メニュー</a></span>";
		    }
		?>
		<hr>
	</head>
	<body>
		<?php if($status =="change") :?>
		    <h1>パスワード変更</h1>
		    <form action ='passwordchange.php' method ='POST'>
		        <h3>現在のパスワードと新しいパスワードを入力してください。</h3>
		        <p>
		            現在のパスワード　　　　　：<input type ='password' name ='nowpass'><br>
		            新しいパスワード　　　　　：<input type ='password' name ='newpass'><br>
		            新しいパスワード（再入力）：<input type ='password' name ='renewpass'>
		        </p>
		        <p><input type ='submit' value ='変更'></p>
		    </form>
		    <form action ='index.php' method ='POST'><input type ='submit' value ='戻る'></form>
		    <?php if($inputerror =="true") echo "未入力があります。<br>"; ?>
		    <?php if($passerror =="nowpass") echo "現在のパスワードが間違っています。<br>"; ?>
		    <?php if($passerror =="repass") echo "再入力のパスワードが間違っています。<br>"; ?>
		<?php elseif($status =="success") :?>
		    <h1>パスワード変更完了</h1>
		    <a href ='top.php'>メニューへ</a>
        <?php else : ?>
            <h1>エラー</h1>
            ----------管理者に問い合わせてください----------<br>
            <a href ='./top.php'>メニューへ</a>
        <?php endif; ?>
		
	</body>
</html>
