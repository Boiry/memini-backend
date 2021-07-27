<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class ImageUploader
{

    public function upload($image, $type)
    {
        $fp = fopen("/var/www/html/projet-memini/app-symfony/public/uploads/" . $type . "s/tmp.png", "w+");
        $data = explode(',', $image);
        fwrite($fp, base64_decode($data[1]));
        fclose($fp);

        $filesystem = new Filesystem();
        $uniqueID = uniqid();
        $filesystem->rename("/var/www/html/projet-memini/app-symfony/public/uploads/" . $type . "s/tmp.png", "/var/www/html/projet-memini/app-symfony/public/uploads/" . $type . "s/" . $type . "-" . $uniqueID . ".png");
        $fileName = "uploads/" . $type . "s/" . $type . "-" . $uniqueID . ".png";
        return $fileName;
    }
}
