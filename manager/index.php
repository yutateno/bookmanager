<?php
    session_start();
    if(isset($_SESSION['id']) && $_SESSION['manager'] == 'yes')
    {
        $login = "true";
    }
    else if(isset($_SESSION['id']))
    {
        $login = "false";
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
        <meta charset="UTF-8">
        <title>管理メニュー</title>
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
        <?php if($login == "true"): ?>
            <h1>管理メニュー</h1>
            <h2>管理者メニューです。</h2><br>
            <a href='member.php'>ユーザー一覧</a><br><br>
            <a href='register.php'>ユーザー登録</a><br><br>
            <a href='delete.php'>ユーザー削除</a><br><br>
            <a href='manager.php'>管理者編集</a><br><br>
        <?php elseif($login == "false") : ?>
            管理者権限がありません。管理者に問い合わせてください。
            <a href = '../book/top.php'>ユーザー画面へ</a>
        <?php else : ?>
            <h1>ーーーーーーーーーー　エラー　ーーーーーーーーーー</h1>
            管理者に問い合わせてください
            <a href = '../book/top.php'>ユーザー画面へ</a>
        <?php endif; ?>
    </body>
</html>