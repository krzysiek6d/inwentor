<?php

echo $client['Client']['name'] . ' ' . $client['Client']['surname'];
echo '<p>zamowienia klienta</p>';
foreach($client['Order'] as $order)
{
	echo $this->Html->link($order['id'], array('controller'=> 'items', 'action' => 'index', 'orderId' => $order['id'])) . '<br />' ;
}


?>