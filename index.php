<!--処理-->
<?php
    session_start();
    $status ="none";
    $inputerror ="false";
    $iderror ="false";
    $passerror ="false";

    if(isset($_SESSION["id"]))
    {
        header("Location: ./login/index.php");
		exit();
    } 
    else
    {
        try
        {
            $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber');
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            if(!empty($_POST['id']) && !empty($_POST['password']))
            {
                $id =$_POST['id'];
                $password =$_POST['password'];

                $sql =$db->prepare("SELECT * FROM user WHERE id = ?");
                $sql->bindValue(1,$id);
                $sql->execute();
                
                $i =0;
                while($row =$sql->fetch())
                {
                    if($password == $row['password'])
                    {
                        $_SESSION['id'] =$id;
                        $_SESSION['name'] =$row['name'];
                        $_SESSION['namager'] =$row['manager'];
                        $status ="success";
                    }
                    else
                    {
                        $status ="empty";
                        $passerror ="true";
                    }
                    $i++;
                }
                if($i == 0)
                {
                    $status ="empty";
                    $iderror ="true";
                }
            }
            else if(!empty($_POST['id']) || !empty($_POST['password']))
            {
                $status ="empty";
                $inputerror ="true";
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
?>
<!--html-->
<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="utf-8">
        <title></title>
    </head>
    <body>
        <?php if($status == "empty") :?>
            <h1>コンピュータ部備品管理</h1>
            ログインしてください<br>
            <form action ="index.php" method ="POST">
                <table>
                    <tr><td>ID</td><td><input type ="text" name ="id"></td></tr>
                    <tr><td>PASSWORD</td><td><input type ="password" name ="password"></td></tr>
                </table><br>
                <input type ="submit" value ="ログイン">
            </form>
            <?php if($inputerror == "true") echo "未入力があります。";?>
            <?php if($iderror == "true") echo "入力されたIDは存在しません。";?>
            <?php if($passerror == "true") echo "パスワードが間違っています。";?>
        <?php elseif($status == "success") :?>
            <?php 
                header("Location : login/index.php");
                exit();
            ?>
        <?php endif;?>
    </body>
</html>