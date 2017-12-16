<?php
    session_start();

    $status = "none";
    $inputerror ="false";
    if(isset($_SESSION['id']) && $_SESSION['manager'] == 'yes')
    {
        try{
            $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成
            
            if(isset($_POST['manager']) && is_array($_POST['manager']))
            {
                $manager = $_POST['manager'];

                if($_SESSION['edit'] =="add")
                {
                    for($i =0;$i < count($manager);$i++)
                    {
                        $sql =$db->prepare("UPDATE user SET manager ='yes' WHERE id = ?");
                        $sql->bindValue(1,$manager[$i]);
                        $sql->execute();

                    }
                    $status ="success";
                }
                else if($_SESSION['edit'] =='delete')
                {
                    for($i =0;$i < count($manager);$i++)
                    {
                        if($_SESSION['id'] == $manager[$i])
                        {
                            $managererror ="true";
                            break;
                        }
                        else
                        {
                            $managererror ="false";
                        }
                    }
                    
                    if($managererror == "false")
                    {
                        for($i =0;$i < count($manager);$i++)
                        {
                            $sql =$db->prepare("UPDATE user SET manager ='no' WHERE id = ? ");
                            $sql->bindValue(1,$manager[$i]);
                            $sql->execute();
                        }
                        $status ="success";
                    }
                    else
                    {
                        $status ="deleteerror";
                    }
                }
                else
                {
                    $status ="error";
                }
            }
            else if(isset($_POST['select']))
            {
                $select =$_POST['select'];
                
                if($select =='add')
                {
                    $sql =$db->prepare("SELECT id,name FROM user WHERE manager='no'");
                    $sql->execute();

                    $status ="add";
                }
                else if($select =='delete')
                {
                    $sql =$db->prepare("SELECT id,name FROM user WHERE manager='yes'");
                    $sql->execute();

                    $status ="delete";
                }
                else
                {
                    $inputerror ="true";
                    $status ="select";
                }
                
            }
            else
            {
                $status ="select";
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
        <title>管理者権限編集</title>
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
        <h1>管理者権限編集</h1>
        <?php if($status == "success") : ?>
            <h2>編集完了</h2>
            <p><a href ='index.php'>管理者画面へ</a></p>
            <p><a href ='manager.php'>続けて編集</a></p>
        <?php elseif($status =="add") : ?>
            <h2>管理者追加</h2>
            <form action ='manager.php' method ='POST'>
                <table rules="all" frame="border">
                    <?php while($row =$sql->fetch()){ echo "<tr><td><input type ='checkbox' name ='manager[]' value =" . $row['id'] . "></td><td>　" . $row['id'] . "&emsp;" . $row['name'] . "</td></tr>";}$_SESSION['edit'] ="add"; ?>
                </table>
                <p><input type ='submit' value ='編集'></p>
            </form>
            <form action ='manager.php' method ='POST'><input type ='submit' value ='戻る'></form>
        <?php elseif($status =="deleteerror") : ?>
            <h2>エラー</h2>
            自分の管理者権限を削除することはできません。<br>
            最初からやり直してください。
            <form action ='manager.php' method ='POST'><p><input type ='submit' value ='戻る'></p></form>
            <form action ='index.php' method ='POST'><p><input type ='submit' value ='管理者画面へ'></p></form>
        <?php elseif($status =="delete") : ?>
            <h2>管理者削除</h2>
            <form action ='manager.php' method ='POST'>
                <table rules="all" frame="border">
                    <?php while($row =$sql->fetch()) { echo "<tr><td><input type ='checkbox' name ='manager[]' value =" . $row['id'] . "></td><td>　" . $row['id'] . "&emsp;" . $row['name'] . "</td></tr>"; } $_SESSION['edit'] ="delete"; ?>
                </table>
                <p><input type ='submit' value ='編集'></p>
            </form>
            <form action ='manager.php' method ='POST'><input type ='submit' value ='戻る'></form>
        <?php elseif($status =="select") : ?>
            <form action ='manager.php' method ='POST'>
                管理者権限の編集内容を選択してください。<br><br>
                <table rules="all" frame="border">
                    <tr><td><input type ='radio' name ='select' value ='add'></td><td>追加</td></tr>
                    <tr><td><input type ='radio' name ='select' value ='delete'></td><td>削除</td></tr>
                </table>
                <p><input type ='submit' value ='決定'></p>
            </form>
            <form action ='index.php' method ='POST'><input type ='submit' value ='戻る'></form>
            <?php if($inputerror =='true') echo "編集内容を選択していません。"; ?>
        <?php else : ?>
            <h2>エラー</h2>
            <?php echo $status; ?>
            <a href ='../user/logout.php'>ログアウト</a>
        <?php endif; ?>
    </body>
</html>
