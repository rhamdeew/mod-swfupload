<?php
$uploadDir = 'img/'; //папка для хранения файлов
$dir = ''; //базовый путь к скрипту
$mwidth = 500;

$allowedExt = array('jpg', 'jpeg', 'png', 'gif');
$maxFileSize = 10 * 1024 * 1024; //10 MB

//если получен файл
if (isset($_FILES)) {
    //проверяем размер и тип файла
    $ext = end(explode('.', strtolower($_FILES['Filedata']['name'])));
    if (!in_array($ext, $allowedExt)) {
        return;
    }
    if ($maxFileSize < $_FILES['Filedata']['size']) {
        return;
    }
    if (is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
	//Магия с созданием уникального имени. Начало
	    $fileName = $uploadDir.$_FILES['Filedata']['name'];
		$nameParts = explode('.', $_FILES['Filedata']['name']);
        $nameParts[count($nameParts)-2] =substr(md5(time()),7);
        $fileName = $uploadDir.implode('.', $nameParts);
		//если файл с таким именем уже существует... Невероятное! =)
        while(file_exists($fileName)) {
            //...добавляем текущее время к имени файла
            $nameParts = explode('.', $_FILES['Filedata']['name']);
            $nameParts[count($nameParts)-2] =substr(md5(time()),7);
            $fileName = $uploadDir.implode('.', $nameParts);
        }
		//Генерим путь к mini
		$dirParts=explode('/',$fileName);
		$dirParts[0].="/mini";
		$fileName2 = implode('/',$dirParts);
	//Магия с созданием уникального имени. Конец
        move_uploaded_file($_FILES['Filedata']['tmp_name'], $fileName);
		//Костыль ресайза картинок до width:500px; начало
		// Get new sizes
		list($width, $height, $type, $attr) = getimagesize($fileName);
		if($width>500) {
		$newwidth=500;
		$k=$newwidth/$width;
		$newheight = $height * $k;
		// Load
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		if($type==3) $source = imagecreatefrompng($fileName);
		elseif($type==1) $source = imagecreatefromgif($fileName);
		elseif($type==2) $source = imagecreatefromjpeg($fileName);
		else {
			// $error = array("success" => "false");
			// die(json_encode($error));
			die("IO error");
		}
	
		// Resize
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		if($type==3) $source = imagepng($thumb, $fileName2);
		elseif($type==1) $source = imagegif($thumb, $fileName2);
		elseif($type==2) $source = imagejpeg($thumb, $fileName2);
		else {
			// $error = array("success" => "false");
			// die(json_encode($error));
			die("IO error");
		}

		// $data = array(
		// 	"success" => "true",
		// 	"full_url" => $dir.$fileName,
		// 	"mini_url" => $dir.$fileName2,
		// 	);
		// echo json_encode($data);
		echo $dir.$fileName.'|'.$dir.$fileName2;
		// $ext='<a href="'.$dir.$fileName.'"><img src="'.$dir.$fileName2.'"/></a>';
		// echo $ext."<br/>";
		// echo '<input type="text" size="150" value="'.htmlspecialchars($ext).'"><br />';
		}
		//Костыль ресайза картинок до width:500px; конец
		else {

		// $data = array(
		// 	"success" => "true",
		// 	"full_url" => $dir.$fileName,
		// 	);
		// echo json_encode($data);
		echo $dir.$fileName;			
		}
    }
}
