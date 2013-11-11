<?php 
echo '<div class="viewItem">';
if ($comesFromOrder != NULL) 
    echo $this->Html->link('POWROT', array(
        'action' => 'index', 
        'itemId' => $item['Item']['id'], 
        'sort' => $sort, 
        'direction' => $direction,
        'orderId' => $comesFromOrder,
        ));
else
    echo $this->Html->link('POWROT', array(
        'action' => 'index', 
        'itemId' => $item['Item']['id'], 
        'sort' => $sort, 
        'direction' => $direction)
    );


echo '<h1>'.$item['Item']['name'].'</h1>'; 
if (!$item['Image']['id']=="")
    echo $this->Html->image('../images/showImg/'.$item['Image']['id']) . ' ' ; 

echo $this->Html->image('../items/showbarcode/'.$item['Item']['id']);

echo '<p><b>Cena:</b> '. $item['Item']['price'].  '</p>';   
echo '<p><b>Rodzaj pracy:</b> '. $item['Item']['type'].  '</p>';  
echo '<p><b>Opis:</b> ' . $item['Item']['description'].'</p>';

echo $this->Html->link('PDF', array('action'=>'viewPdf', $item['Item']['id']));
echo '<br />';
echo $this->Html->link('edytuj!', array('action' => 'edit', $item['Item']['id']));
echo '<br />';

if($neighbors['prev']!="")
    echo $this->Html->link('poprzedni', array(
        'action' => 'view',  
        'orderId' => $comesFromOrder,
        $neighbors['prev']
        )
    );
    echo '<br />';
if($neighbors['next']!="")
    echo $this->Html->link('nastepny', array(
        'action' => 'view',  
        'orderId' => $comesFromOrder,
        $neighbors['next']
        )
    );

echo '</div>';
?>