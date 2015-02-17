<?php

namespace app\helpers;

class Notes
{
    public static function array2csv(array $array)
    {
       if (count($array) == 0) {
         return null;
       }
       ob_start();
       $df = fopen("php://output", 'w');
       fputcsv($df, array_keys(reset($array)));
       foreach ($array as $row) {
          fputcsv($df, $row);
       }
       fclose($df);
       return ob_get_clean();
    }
    
    public static function downloadSendHeaders($fileName)
    {
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        header("Content-Disposition: attachment;filename={$fileName}");
        header("Content-Transfer-Encoding: binary");
    }
    
    public static function getEncodedImage($filePath)
    {
        $imagePath = \Yii::$app->basePath . $filePath;
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($imagePath);
        return 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
    }
    
    public static function saveImage($image)
    {
        $uploadPath = '/upload/' . $image->file->baseName . '.' . $image->file->extension;
        $filePath = \Yii::$app->basePath . $uploadPath;
        $image->file->saveAs($filePath);
        return $uploadPath;
    }
}