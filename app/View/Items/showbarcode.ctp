<?php
    $url = Configure::read('BarcodeBaseURL');
    $barcodeText = $url.$data;
    $this->ItemExtension->generateBarCode($barcodeText,$size);
?>
