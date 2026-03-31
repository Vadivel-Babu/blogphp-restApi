<?php

namespace App\Helpers;

class Validate
{
    public static function validation($data, $validateType)
    {
        if ($validateType === 'register') {
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['password'];

            if ($name === '' || $email === '' || $password === '') {
                return ['status' => false, 'message' => 'all fields are required'];
            }
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => false, 'message' => 'Invalid email formate'];
            }

            return ['status' => true];
        } elseif ($validateType === 'login') {
            $email = $data['email'];
            $password = $data['password'];

            if ($email === '' || $password === '') {
                return ['status' => false, 'message' => 'all fields are required'];
            }
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => false, 'message' => 'Invalid email formate'];
            }

            return ['status' => true];
        } elseif ($validateType === 'img') {
            $uploadDir = 'app/uploads/';
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $maxSize = 500 * 1024;
            $img = $data;
            $fileName = basename($img['name']);
            $fileSize = $img['size'];
            $fileTmp = $img['tmp_name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // 3. Validate Extension
            if (! in_array($ext, $allowedExtensions)) {
                return ['status' => false, 'message' => 'Only JPG, JPEG & PNG files are allowed.'];
            }

            // 4. Validate Size
            if ($fileSize > $maxSize) {
                return ['status' => false, 'message' => 'File size must be less than 500 KB.'];
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fileTmp);

            $allowedMime = ['image/jpeg', 'image/png'];
            if (! in_array($mime, $allowedMime)) {
                return ['status' => false, 'message' => ' Invalid image file.'];
            }
            $newFileName = uniqid('img_').'.'.$ext;
            $destination = $uploadDir.$newFileName;
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            if (move_uploaded_file($fileTmp, $destination)) {
                $imgUrl = "$protocol://$host/$destination";

                return ['status' => true, 'img' => $imgUrl];
            }
        }
    }
}
