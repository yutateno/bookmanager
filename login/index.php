<!--処理-->
<?php
    session_start();
    
    if(isset($_SESSION['id']) && !empty($_SESSION['id']))
    {
        $id =$_SESSION['id'];
        $name =$_SESSION['name'];
    }
    else
    {
        header("Location : ../index.php");
        exit();
    }
?>

<!--HTML-->
<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="utf-8">
        <title>メインメニュー</title>
    </head>
    <body>
        ログイン：<?php echo "$id $name"; ?>
    </body>
</html>
