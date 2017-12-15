<?php
    session_start();

	$status = "none";
	$inputerror = "false";
	$emptyerror = "false";
	$fatalerror = "false";
    
    try{
        $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        if(isset($_SESSION["id"]))
		{
		    $status = "allready";
		}
		else if(!empty($_POST["id"]) && !empty($_POST["password"]))
		{
            $id        = $_POST['id'];
            $password  = $_POST['password'];
            
            $sql = $db->prepare("SELECT password,name,manager,address FROM user WHERE id = ?");
            $sql->bindValue(1,"{$id}");
            $sql->execute();

            if($sql->rowCount() == 1)
            {
                $row = $sql->fetch();
                if(password_verify ($password, $row['password']))
                {
                    $_SESSION["id"]      = $id;
                    $_SESSION["name"]    = $row['name'];
                    $_SESSION["manager"] = $row['manager'];
                    $status = "success";
                }
                else
                {
					$status = "empty";
					$inputerror = "true";
                }
            }
            else
            {
				$status = "empty";
				$fatalerror = "true";
            }
        }
        else
        {
			$sql = $db->prepare("SELECT * FROM user");
            $sql->execute();
			if($sql->rowCount() == 0)
			{
				$_SESSION['first'] = "yes";
				header("Location: ./first.php");
				exit();
			}
			else
			{
				$status = "empty";
				if(!empty($_POST['id']) || !empty($_POST['password']))
				{
					$emptyerror = "true";
				}
			}
        }
    }
    catch(PDOException $e)
    {
        exit('データベース処理失敗:'.$e->getMessage());   
    }
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>本の管理</title>
	</head>
	<body>
		<h1>本の管理</h1>
		<?php  if($status == "allready") :
		    header("Location: book/top.php");
			exit();
		?>
	    <?php elseif ($status == "success") :
		    header("Location: book/top.php");
			exit();
		?>
		<?php elseif($status == "empty") : ?>
		    <form action='index.php' method='POST'>
			    ログインID:<input type='text' name='id'><br>
			    パスワード：<input type='password' name='password'><br>
			    <p><input type ='submit' value='ログイン'></p>
			</form>
			<?php if($inputerror == "true") echo "パスワードかIDが間違っています。"; ?>
			<?php if($emptyerror == "true") echo "IDかパスワードが入力されていません。"; ?>
			<?php if($fatalerror == "true") echo "不正なIDです。再度繰り返して変わらなければ管理者に連絡してください。"; ?>
		<?php else :  ?>
			エラー　クッキーを削除してやり直してください。
		<?php endif; ?>
	</body>
</html>
