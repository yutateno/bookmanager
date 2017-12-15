<?php
    $hash =  password_hash("cyber", PASSWORD_DEFAULT);  
    try
    {
        $db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // データベース接続
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		// エラーオブジェクトの作成

        $sql = $db->prepare("UPDATE  user SET password =? WHERE id = 'cyber'");
        $sql->bindValue(1,$hash);
        $sql->execute();
    }
    catch(PDOException $e)
    {
        exit('データベース処理失敗:'.$e->getMessage());   
    }
?>