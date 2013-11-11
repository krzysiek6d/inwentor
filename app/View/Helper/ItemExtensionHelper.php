<?php

class ItemExtensionHelper extends AppHelper{
    var $helpers = array('Form');
    
    function createSearchLink($Item, $field)
    {
        $data = array(
            'Item' => array(
                    'title' => '',
                    'description' => '',
                    'startPrice' => '',
                    'endPrice' => '',
                    'type' => ''
                    )
        );
        switch($field)
            {
                case 'price'  : 
                    $data['Item']['startPrice'] = $Item['Item']['rice'];
                    $data['Item']['endPrice'] = $Item['Item']['Price'];
                    break;
                case 'type'  :
                    $data['Item']['type'] = $Item['Item']['type'];
                    break;
                default :
                    break;
            }
        Debugger::dump($data);    
        return $this->Form->postLink(
                $Item['Item'][$field], 
                array('action' => 'index', $Item['Item'][$field],
                    'Item.name' => $data['Item']['name'],
                    'Item.description' => $data['Item']['description'],
                    'Item.startPrice' => $data['Item']['startPrice'],
                    'Item.endPrice' => $data['Item']['endPrice'],
                    'Item.type' => $data['Item']['type'],
                    ),
                '',
                false
                );

    }
    
    public function createPdf($Items)
    {

        
        $this->layout = 'ajax';
        ob_start();
        while (@ob_end_clean());
        header("Content-type: application/pdf");


        App::import('Vendor','tcpdf/tcpdf'); 
        
            
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        foreach ($Items as $Item) {

            $pdf->SetFont('dejavusans', '', 8, '', true);
            $pdf->AddPage();
            $html = 
            '<h1>Nazwa: ' . $Item['Item']['name'] ."<br /></h1>" .
            "<h2>Cena: " . $Item['Item']['price'] ."</h2>" .
            "<h2>Rodzaj pracy: " . $Item['Item']['type'] ."</h2>" .        
            '<p>Opis: ' . $Item['Item']['description'] . '</p>' .
			'<p>Cena: ' . $Item['Item']['price'] . '</p>'
            ;
            $pdf->writeHTMLCell($w=0, $h=0, $x='85', $y='15', $html, $border=0, $ln=2, $fill=0, $reseth=true, $align='', $autopadding=true);


            //BarCode
            $style = array(
                    'border' => 0,
                    'vpadding' => 'auto',
                    'hpadding' => 'auto',
                    'fgcolor' => array(0,0,0),
                    'bgcolor' => false, 
                    'module_width' => 1, 
                    'module_height' => 1 );

            $url = Configure::read('BarcodeBaseURL');
            $barcodeText = $url.$Item['Item']['id'];
            $pdf->write2DBarcode($barcodeText, 'QRCODE,H', 7, 10, 40, 40, $style, 'N');


            if (!$Item['Image']['id']=="")
            {
                $imageMaxSize = 70;
                $image = imagecreatefromstring($Item['Image']['data']);
                $imageWidth = imagesx($image);
                $imageHeight = imagesy($image);
                if($imageWidth > $imageHeight)
                    $pdf->Image('@'.$Item['Image']['data'],10,50,$imageMaxSize,'','','','',false);
                else
                    $pdf->Image('@'.$Item['Image']['data'],10,50,'',$imageMaxSize,'','','',false);
            }

            $pdf->write2DBarcode($barcodeText, 'QRCODE,H', 7, 210, 45, 45, $style, 'N');
            $pdf->write2DBarcode($barcodeText, 'QRCODE,H', 60, 210, 45, 45, $style, 'N');


        }

        $pdf->Output('file.pdf', 'FD');
        ob_end_flush();
    }
    
    public function generateBarCode($data, $size=3)
    {
        $this->layout = 'ajax';
        header("Content-Type: image/png");
        ob_start();
        while (@ob_end_clean());
        App::import('Vendor','tcpdf/2dbarcodes'); 
        $barcodeText = $data;
        $barcode = new TCPDF2DBarcode($barcodeText, 'QRCODE,H');
        $barcode->getBarcodePNG($size,$size);
        ob_end_flush();
    }
    
}


