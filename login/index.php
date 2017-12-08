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
        <a href = '../login/logout.php'>ログアウト</a>
		&emsp;
		<a href ='../login/index.php'>ユーザーメニュー</a>
		<?php
		    if($_SESSION['manager'] == "yes")
		    {
		            echo "<span style ='float:right'><a href = 'index.php'>管理者メニュー</a></span>";
		    }
		?>
		<hr>
    </head>
    <body>
        ログイン：<?php echo "$id $name"; ?>
    </body>
</html>
