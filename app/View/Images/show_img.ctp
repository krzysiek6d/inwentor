<?php
    $this->layout = 'ajax';
    /*ob_start();
    while (@ob_end_clean());
    header('Content-type: ' . $image['Image']['type']); 
    //header('Content-length:' . $image['Image']['size']);
    //header('Content-Disposition: inline; filename="'.$image['Image']['name'].'"');
    echo($image['Image']['data']);
    ob_end_flush();*/
    while (@ob_end_clean());
    header('Content-type: ' . $image['Image']['type']);
    $image = imagecreatefromstring($image['Image']['data']);
    imagejpeg($image);
    imagedestroy($image);
?>
