	<?php

//MYSQLデータベースへ接続
		$dsn = 'mysql:dbname=データベース名;host=ホスト名';
		$user = 'ユーザー名';
		$password = 'パスワード';
		$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブルを設定
	$sql = "CREATE TABLE IF NOT EXISTS mission5x(
		id INT AUTO_INCREMENT PRIMARY KEY,
		name char(32),
		comment TEXT,
		date DATETIME
		);";
	$stmt = $pdo->query($sql);

//パスワードを設定
	$correct_pass = "this";

//送信フォーム
		if (!empty ($_POST["name"]) && !empty ($_POST["comment"]) && empty ($_POST["editnum"])){
		if ($_POST["pass"]==$correct_pass){

			$sql = $pdo -> prepare("INSERT INTO mission5x(name, comment, date) VALUES(:name, :comment, :date)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$name = $_POST["name"];
			$comment = $_POST["comment"];
			$date = date("Y/m/d H:i:s");
			$sql -> execute();

		}elseif($_POST["pass"]==""){
			$err[] = "パスワードを入力してください";
		}else{
			$err[] = "パスワードが間違っています";
		}
		}elseif(empty ($_POST["name"]) && !empty ($_POST["comment"]) && empty ($_POST["editnum"])){
			$err[] = "名前を入力してください";
		}elseif(!empty ($_POST["name"]) && empty ($_POST["comment"]) && empty ($_POST["editnum"])){
			$err[] = "コメントを入力してください";
		}

//削除フォーム
		if (!empty ($_POST["delno"])){
		if ($_POST["pass"]==$correct_pass){

			$id = $_POST["delno"];
			$sql = 'delete from mission5x where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

		}elseif($_POST["pass"]==""){
			$err[] = "パスワードを入力してください";
		}else{
			$err[] = "パスワードが間違っています";
		}
		}

//編集フォーム
		if (!empty ($_POST["editno"])){
		if ($_POST["pass"]==$correct_pass){

			$id = $_POST["editno"];
			$sql = 'SELECT * FROM mission5x WHERE id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$results = $stmt->fetchAll();
				foreach ($results as $row){
					$edit_num = $row["id"];
					$edit_name = $row["name"];
					$edit_comment = $row["comment"];
				}

		}elseif($_POST["pass"]==""){
			$err[] = "パスワードを入力してください";
		}else{
			$err[] = "パスワードが間違っています";
		}
		}

//編集投稿フォーム
		if (!empty ($_POST["name"]) && !empty ($_POST["comment"]) && !empty ($_POST["editnum"])){
		if ($_POST["pass"]==$correct_pass){

			$id = $_POST["editnum"];
			$name = $_POST["name"];
			$comment = $_POST["comment"];
			$sql = 'update mission5x set name=:name,comment=:comment where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

		}elseif($_POST["pass"]==""){
			$err[] = "パスワードを入力してください";
		}else{
			$err[] = "パスワードが間違っています";
		}
		}elseif(empty ($_POST["name"]) && !empty ($_POST["comment"]) && !empty ($_POST["editnum"])){
			$err[] = "名前を入力してください";
		}elseif(!empty ($_POST["name"]) && empty ($_POST["comment"]) && !empty ($_POST["editnum"])){
			$err[] = "コメントを入力してください";
		}

	?>

<!DOCTYPE html>
<html>

	<head>
		<title>WEB掲示板</title>
		<meta charset="utf-8">
	</head>

<body>


	<form action="mission_5x.php" method="post">

	名前<input type="text" name="name" value=<?php if (!empty ($edit_name)){echo $edit_name;} ?> ><br>
	コメント<input type="text" name="comment" value=<?php if (!empty ($edit_comment)){echo $edit_comment;} ?> ><br>
	パスワード<input type="text" name="pass" >
	<input type="submit" value="送信">
	<input type="hidden" name="editnum" value=<?php if (!empty ($edit_num)){echo $edit_num;} ?> >
	</form>


	<form action="mission_5x.php" method="post">

	削除番号<input type="text" name="delno"><br>
	パスワード<input type="text" name="pass" >
	<input type="submit" value="削除">

	</form>


	<form action="mission_5x.php" method="post">

	編集番号<input type="text" name="editno"><br>
	パスワード<input type="text" name="pass" >
	<input type="submit" value="編集">

	</form>


	<ul>

	<?php 
//エラー表示フォーム
				if(isset($err)){
					foreach($err as $value){ ?>

			<li><?php echo $value; ?></li>

	<?php	 }
			 	} ?>

	</ul>


</body>
</html>

<?php

//投稿表示フォーム

	$sql = 'SELECT * FROM mission5x ORDER BY id DESC';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
		foreach ($results as $row){
			echo $row['id'].' ';
			echo $row['name'].' ';
			echo $row['comment'].' ';
			echo $row['date'].'<br>';
		echo "<hr>";
		}

//MYSQLデータベースへの接続を閉じる
$dbh = null;

	?>