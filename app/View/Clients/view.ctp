<h1 style="font-size: 190%;  color: #090"><?php echo $client['Client']['name'].' '. $client['Client']['surname']; ?></h1>
<p>adres: <?php echo $client['Client']['address']; ?></p>
<p>tel. :<?php echo $client['Client']['phone']; ?></p>
<p>email: <?php echo $client['Client']['email']; ?></p>
<?php echo $this->Html->link('Przegladaj zamowienia',    	array('controller'=> 'clients', 'action' => 'showClientOrders', 'clientId' => $client['Client']['id'])); ?><br />
