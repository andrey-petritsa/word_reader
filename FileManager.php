<?php
require_once "Settings.php";

class FileManager {
    private function getFileExtension($file_name) {
        $file_info = new SplFileInfo($file_name);
        $file_extension = $file_info->getExtension();
        return $file_extension;
    }

    public function generateRandomFileName($file_extension) {
        //Имя генерируется на основании текующей даты + 10 буквы латинского алфавита
        return date('Y-m-d') . "-" . $this->generateRandomWord() . sprintf(".%s", $file_extension);
    }

    private function generateRandomWord() {
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 10);
    }

    public function isFileValid($file_extension) {
        if($file_extension != "docx" && $file_extension != "doc") {
            return false;
        }
        return true;
    }

    private function createDirectory($dir_path) {
        //Создает директорию куда будут загружаться файлы
        @mkdir($dir_path, 0777, true);
    }

    public function saveFile($file_name, $save_file_path, $file_extension) {
        if($this->isFileValid($file_extension)) {
            if(!is_dir(Settings::$directory_path)) { //Если нету директории куда можно сохранить файл
                $this->createDirectory(Settings::$directory_path);
            }
            $isFileSaved = copy($file_name, $save_file_path);
            if($isFileSaved) {
                return "Файл был успешно сохранен";
            }
            else {
                return "Неизвестная ошибка при сохранении файла";
            }

        }
        else {
            return "Поддерживается только формат .doc и .docx";
        }
    }
}

