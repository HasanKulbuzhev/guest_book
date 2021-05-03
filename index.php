<?php ?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Гостевая книга</h1>



			<div>
				<nav>
				  <ul class="pagination">
					<li class="disabled">
						<a href="?page=1"  aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
<?php


//Коннектимся к базе и устанваливаем кодировку
	$host = 'localhost';
	$user = 'hasan';
	$password = '123';
	$db_name = 'test';
	$link = mysqli_connect($host, $user,$password,$db_name);
	mysqli_query($link,"SET NAMES 'utf8'");

//Записываем в базу введенные данные,если они были введены

	if (isset($_POST['name']) and isset($_POST['text'])) {
		$query = 'INSERT INTO `Table_comments` (`name`, `text`, `date`) VALUES (\'' . $_POST['name'] . '\' ,\'' . $_POST['text'] . '\' , \'' . date('YmdHis', time()) . '\')';
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
				
		header("Refresh: 0");
	}

//Тут у нас находится пагинация , тут же у нас будет выборка данных из базы
	
	$query = "SELECT COUNT(*) as count FROM Table_comments WHERE id>0";
	$result = mysqli_query($link, $query) or die( mysqli_error($link) );
	$count = mysqli_fetch_assoc($result)['count'];
	print_r($count);
	$j = 1;

						$i = 1;
					while($count > 1){
						
						if ($_REQUEST['page'] == $i) {
							$query = "SELECT * FROM Table_comments WHERE id > 0 ORDER BY date desc LIMIT " . $j . ",5";
							echo '<li class="active">';
						}
						else echo '<li>';
					echo '<a href="?page=' . $i. '">' .$i . '</a></li>';
						$j+=5;
						$i++;
						$count-=5;
						
					} 

//Берем данные из базы и добавляем в переменную $data в виде массива

	$result = mysqli_query($link, $query) or die(mysqli_error($link));

	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);




?>
					<li>
						<a href="?page=5" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				  </ul>
				</nav>
			</div>

<?php

//Выводим данные из перменной $data

	foreach($data as $key => $value) { 
				
				echo '<div class="note">
				<p>
					<span class="date">' . $value['date'] . '</span>
					<span class="name">' . $value['name'] . '</span>
				</p>
				<p>
				' . $value['text'] . '
				</p>
				</div>	';
	}



?>


			<div class="info alert alert-info">
				Запись успешно сохранена!
			</div>
			<div id="form">
				<form action="#form" method="POST">
					<p><input class="form-control" placeholder="Ваше имя" name = "name"></p>
					<p><textarea class="form-control" placeholder="Ваш отзыв" name = "text"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить" ></p>
				</form>
			</div>
		</div>
	</body>
</html>

