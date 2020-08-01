<?php
require_once "FileManager.php";
require_once "Settings.php";
header('Content-Type: application/json');
$x = Settings::$x;
$y = Settings::$y;


if (isset($_FILES['doc_file']) && $_FILES['doc_file']['error'] === UPLOAD_ERR_OK) {
    $file_manager = new FileManager();
    $file_from_user_temp_path = $_FILES['doc_file']['tmp_name'];
    $original_file_name = $_FILES['doc_file']['name'];
    $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);

    if($file_manager->isFileValid($file_extension)) { // Если файл поддерживается программой
        $uploaded_file_path = Settings::$directory_path . "/" . $file_manager->generateRandomFileName($file_extension);
        $saved_file_path = $file_manager->saveFile($file_from_user_temp_path, $uploaded_file_path, $file_extension);
	//$python_command = sprintf(__DIR__ . "/" . 'word_reader.py %s', $uploaded_file_path . " 2>&1");
        $python_command = escapeshellcmd(sprintf(__DIR__ . "/" . 'word_reader.py %s', $uploaded_file_path));
        $script_output = shell_exec($python_command);
        if(is_numeric($script_output)) {
            $money = round((int)$script_output / $x * $y);
            echo json_encode(array("word_count" => $script_output, "link" => $uploaded_file_path, "money" => $money, "status" => "ok"));
        }
        else {
            echo json_encode(array("status" => "Ошибка..."));
        }

    }
    else
        echo json_encode(array("status" => "Неверный тип файла. Загрузите .doc или .docx"));
}
else {
        echo json_encode(array("status" => "Вы не загрузили файл"));
}

