<?php
    session_start();

    $status = "none";
    $idstatus = "false";
    $iderror = "false";
    $passerror = "false";
    $allreadyerror = "false";
    $inputerror = "false";

    if(isset($_SESSION['id']) && $_SESSION['manager'] == 'yes')
    {
        try{
            $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成
            
            if(!empty($_POST["id"]) && !empty($_POST["name"]) && !empty($_POST["password"]) && !empty($_POST["repassword"]) && !empty($_POST["manager"]))
            {
                $id        = $_POST["id"];
                $name      = $_POST["name"];
                $password  = $_POST["password"];
                $manager   = $_POST["manager"];

                
                $check = $db->prepare("SELECT id FROM user");
                $check->execute();
                while($row = $check->fetch())
                {
                    if($id == $row['id'])
                    {
                        $idstatus ="true";
                        break;
                    }
                }
                
                if($password == $_POST["repassword"] && $idstatus =="false")
                {
                    $status = "success";
                    $hash =  password_hash($password, PASSWORD_DEFAULT);
                    $sql = $db->prepare("INSERT INTO user(id,name,password,manager) VALUES( ? , ? , ? , ?)");
                    $sql->bindValue(1,"{$id}");
                    $sql->bindValue(2,"{$name}");
                    $sql->bindValue(3,"{$hash}");
                    $sql->bindValue(4,"{$manager}");
                    $sql->execute();
                }
                else if($idstatus =="true")
                {
                    $status ="empty";
                    $allreadyerror = "true";
                }
                else
                {
                    $status = "empty";
                    $passerror = "true";
                }
            }
            else if(!empty($_POST["id"]) || !empty($_POST["name"]) || !empty($_POST["year"]) || !empty($_POST["month"]) || !empty($_POST["day"]) || !empty($_POST["address"]) || !empty($_POST["password"]) || !empty($_POST["repassword"]) || !empty($_POST["manager"]))
            {
                $status = "empty";
                $inputerror = "true";
                
            }
            else
            {
                $status = "empty";
            }
        
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
        <title>ユーザー登録</title>
        <a href = '../login/logout.php'>ログアウト</a>
		&emsp;
		<a href ='../book/top.php'>ユーザーメニュー</a>
		<?php
		    if($_SESSION['manager'] == "yes")
		    {
		            echo "<span style ='float:right'><a href = 'index.php'>管理者メニュー</a></span>";
		    }
		?>
		<hr>
    </head>
    <body>
        <h1>ユーザー登録</h1>
        <?php if($status == "success") : ?>
            <h2>登録完了</h2>
            <table>
                <tr bgcolor='#99FF99'><th>ID</th><th>NAME</th><th>PASSWORD</th><th>MANAGER</th></tr>
                <?php echo "<tr bgcolor='#EEEEEE'><td>$id</td><td>$name</td><td>ここには表示できません</td><td>$manager</td></tr>"; ?>
            </table>
            <a href='index.php'>管理者画面へ戻る</a><br>
            <a href='register.php'>続けて登録</a>
        <?php elseif ($status == "empty") : ?>
            <form action ='register.php' method ='POST'>
                <table rules="all" frame="border">
                    <tr><td>ID(学籍番号イニシャルあり)</td><td><input type = 'text' name ='id'></td></tr>
                    <tr><td>氏名</td><td><input type ='text' name ='name'></td></tr>
                    <tr><td>パスワード</td><td><input type ='password' name ='password'></td></tr>
                    <tr><td>パスワード再入力</td><td><input type ='password' name ='repassword'></td></tr>
                </table>
                <input type ='hidden' name ='manager' value ='no'><br>
                <input type ='submit' value ='登録'>
            </form>

            <form action ='index.php' method ='POST'><p><input type ='submit' value ='戻る'></p></form>
                
            <?php if($passerror == "true") echo "パスワード入力が一致していません。"; ?>
            <?php if($allreadyerror == "true") echo "そのIDはすでに登録されています。"; ?>
            <?php if($inputerror == "true")  echo "未入力があります。"; ?>
        
        <?php else :?>
            <?php header("Location : ../index.php"); exit(); ?>
            
        <?php endif; ?> 
    </body>
</html>
