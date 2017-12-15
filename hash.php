<?php
    $hash =  password_hash("cyber", PASSWORD_DEFAULT);  
    try
    {
        $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成

        $sql = $db->prepare("UPDATE FROM user SET password ='cyber' WHERE id = 'cyber'");
        $sql->execute();

        header("Location : ./index.php");
        exit();
    }
    catch(PDOException $e)
    {
        exit('データベース処理失敗:'.$e->getMessage());   
    }
?>