<?php
    session_start();

    $status = "none";
    $keyerror = "false";
    if(isset($_SESSION['id']) && $_SESSION['manager'] == 'yes')
    {
        try{
            $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成
            
            if(isset($_POST['keyword']))
            {
                if(!empty($_POST['keyword']))
                {

                    $keyword = $_POST['keyword'];
                    $sql = $db->prepare("SELECT id,name FROM user WHERE id LIKE ? OR name LIKE ?");
                    $sql->bindValue(1,"%{$keyword}%");
                    $sql->bindValue(2,"%{$keyword}%");
                    $sql->execute();

                    $status ="delete";
                }
                else
                {
                    $status ="search";
                    $keyerror ="true";
                }
            }
            else if(isset($_POST['delete']))
            {
                if(is_array($_POST['delete']))
                {
                    $delete = $_POST['delete'];
                    $managererror ="false";
                    for($i =0; $i < count($delete); $i++)
                    {
                        if($i == 0)
                        {
                            $sql = $db->prepare("SELECT manager FROM user WHERE id = ? FOR UPDATE");
                        }
                        else
                        {
                            $sql = $db->prepare("SELECT manager FROM user WHERE id = ? ");
                        }
                        $sql->bindValue(1,"$delete[$i]");
                        $sql->execute();
                        $row = $sql->fetch();
                        if($row['manager'] =="yes")
                        {
                            $managererror ="true";
                            break;
                        }
                    }
                    if($managererror =="false")
                    {
                        $db->beginTransaction();                        
                        for($i =0; $i < count($delete); $i++)
                        {
                            if($i == 0)
                            {
                                $sql = $db->prepare("DELETE FROM user WHERE id = ? FOR UPDATE");
                            }
                            else
                            {
                                $sql = $db->prepare("DELETE FROM user WHERE id = ?");
                            }
                            
                            $sql->bindValue(1, $delete[$i]);
                            $sql->execute();
                        }
                        $db->commit();
                        $status ="success";
                    }
                    else
                    {
                        $status ="managererror";
                    }
                }
                else
                {
                    $status ="error";
                }
            }
            else
            {
                $status ="search";
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
        <title>ユーザー削除</title>
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
        <h1>ユーザー削除</h1>
        <?php if($status == "success") :
              header("Location: ./groupautomanage.php");
              exit();
        ?>
        <?php elseif($status =="delete") : ?>
            <h2>削除メンバー指定</h2>
            <form action ='delete.php' method ='POST'>
                <table rules="all" frame="border">
                    <?php while($row = $sql->fetch()) echo "<tr><td><input type='checkbox' name='delete[]' value=" . $row['id'] . "></td><td>" . $row['id'] . " " . $row['name'] . "</td></tr>"; ?>
                </table>
                <br>
                <input type='submit' value ='削除'>
               </form>
               <br>
               <form action ='delete.php' method ='POST'><input type='submit' value ='戻る'></form>
        <?php elseif($status =="search") : ?>
            <h2>ユーザー検索</h2>
            <form action ='delete.php' method ='POST'>
                <table rules="all" frame="border">
                    <tr><td>IDか名前を入力(一部でも可)　</td><td><input type ='text' name ='keyword' ></td></tr>
                </table>
                <br>
                <input type ='submit' value ='検索'>
            </form>
            <?php if($keyerror =="true") echo "キーワードが未入力です。"; ?>
            <form action ='index.php' method ='POST'><p><input type ='submit' value ='戻る'></p></form>
        <?php elseif($status =="managererror") : ?>
            <h2>権限エラー</h2>
            管理者権限を持ったメンバーが削除対象になりました。<br>
            最初からやり直してください。<br>
            <p><a href ='delete.php'>最初に戻る</a></p>
            <p><a href ='index.php'>管理者画面へ</a></p>
        <?php else :
                echo "<h2>エラー</h2>";
                echo $status;
                echo "<a href ='../user/logout.php'>ログアウト</a>";
        ?>
        <?php endif; ?>
    </body>
</html>
