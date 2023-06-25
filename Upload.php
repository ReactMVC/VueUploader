<?php

/*
Project Name: Vue Uploader

Class FileUploader
Developer: Hossein Pira
Email: h3dev.pira@gmail.com
Telegram: @h3dev
*/

class FileUploader
{
    private $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'mp4');
    private $maxSize = 1048576000000000000;
    private $webURL;

    public function __construct($webURL)
    {
        $this->webURL = $webURL;
    }

    public function upload($file)
    {
        if (empty($file) || !isset($file)) {
            throw new Exception('No File Selected');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed');
        }

        $fileInfo = pathinfo($file['name']);
        $fileExt = strtolower($fileInfo['extension']);

        if (!in_array($fileExt, $this->allowedTypes)) {
            throw new Exception('Invalid file type');
        }

        if ($file['size'] > $this->maxSize) {
            throw new Exception('File size exceeds limit');
        }

        $filename = uniqid() . '.' . $fileExt;

        while (file_exists('uploads/' . $filename)) {
            $filename = uniqid() . '.' . $fileExt;
        }

        move_uploaded_file($file['tmp_name'], 'uploads/' . $filename);

        return $this->webURL . '/' . 'uploads/' . $filename;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploader = new FileUploader("http://localhost:8000");

    try {
        header('Content-type: application/json; charset=utf-8');
        $fileUrl = $uploader->upload($_FILES['file']);
        echo json_encode(array('url' => $fileUrl), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        header('Content-type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode(array('error' => $e->getMessage()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
} else {
    echo "Method Error";
}