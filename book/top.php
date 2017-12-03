<!--PHP-->
<?php
	session_start();
	$loginget = "none";		// ���O�C���������ǂ���
	$yourmanage = "none";		// �Ǘ��҂��ǂ���
	
	// ���O�C�����Ă��Ȃ������烍�O�C����ʂɖ߂点��
	if(empty($_SESSION['id']))			// $_SESSION���ăT�[�o�[�ɕۑ�����Ă���̂����瑼PHP�ł����������̂��Ȃ�
	{
		loginget = "false";
		header("Location: ./index.php");
		exit();
	}
	else
	{
		loginget = "true";
		try
		{
			$db = new PDO('mysql:host=localhost;dbname=kelp_book;charset=utf8','kelp_book','cyber'); // �f�[�^�x�[�X�ڑ�
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);�@// �G���[�I�u�W�F�N�g�̍쐬

			// �ȉ�����
			yourmanage = $_SESSION['manager'];	// �Ǘ��҂����f
		}
		catch(PDOException $e)
		{
			exit('�f�[�^�x�[�X�������s:'.$e->getMessage());
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
		<?php if($loginget == "true") :?>		<!--���O�C���ς�-->
			<?php if($yourmanage == "no") :?>		<!--��ʂ̏ꍇ-->
				<h1>�R���s���[�^�����i�Ǘ�</h1>
				<br>
				<a href="/list">�ꗗ</a><br><br>	<!--URL�̎w��ꏊ�����܂����킩���ĂȂ��̂łƂ肠�����K��-->
				<a href="/lend">�ݏo</a><br><br>
				<br>
			<?php else:?>					<!--�Ǘ��҂̏ꍇ-->
				<h1>�R���s���[�^�����i�Ǘ�</h1>
				<br>
				<a href="/list">�ꗗ</a><br><br>
				<a href="/lend">�ݏo</a><br><br>
				<a href="/manage">�Ǘ�</a><br><br>
				<br>
			<?php endif ?>
		<?php else:?>					<!--���O�C�����ĂȂ�-->
			<?php 
				header("Location : index.php");
				exit();
			?>
		<?php endif ?>
	</body>
</html>