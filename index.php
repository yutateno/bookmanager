<!--処理-->
<?php
    session_start();
    $status ="none";
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
        }
        catch(PDOException $e)
        {
            exit('データベース処理失敗:'.$e->getMessage());   
        }
        $status = "empty";
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

        <?php elseif($status == "  ") :?>

        <?php endif;?>
    </body>
</html>