
<?php

$itemTypes = array(''=>'');
$itemTypes = $itemTypes + Configure::read('itemTypes');

if($orderId)
{
    echo '<h1 style="font-size: 190%;  color: #090">Klient: ...dodac nazwe klienta...</h1>';
}

    echo '<div class="searchForm">';
        if($orderId) 
        	echo $this->Form->create(array( 'url' => array('controller' => 'items', 'action'=> 'index', 'page' => 1, 'orderId' => $orderId)));
        else 
        	echo $this->Form->create(array( 'url' => array('controller' => 'items', 'action'=> 'index', 'page' => 1)));
        echo $this->Form->input('Item.name',         array('label'=>'Nazwa',     'type' => 'text', 'value' => $search['name']));
        echo $this->Form->input('Item.description',   array('label'=>'Opis',      'type' => 'text', 'value' => $search['description']));
        echo $this->Form->input('Item.startPrice',     array('label'=>'Cena od',   'type' => 'text', 'value' => $search['startPrice']));
        echo $this->Form->input('Item.endPrice',       array('label'=>'Cena do',   'type' => 'text', 'value' => $search['endPrice']));
        echo $this->Form->input('Item.type',          array(
                    'label'=>'Rodzaj pracy',
                    'options' => $itemTypes,
                    'value' => $search['type']
                ));

        echo $this->Form->end('Szukaj');
    echo '</div>';
    echo '<div style="clear: both"></div>';


?>

<p>
<?php 
    if(!$orderId)
    {    
        echo $this->Form->create(array('action' => 'add', 'type' => 'get'));
        echo $this->Form->end('Dodaj pozycję');
    }
?></p>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Item.name', 'Nazwa'); ?></th>
        <th><?php echo $this->Paginator->sort('Item.price', 'Cena'); ?></th>
        <th><?php echo $this->Paginator->sort('Item.type', 'Rodzaj pracy'); ?></th>
    </tr>

    <?php foreach ($items as $item): ?>
    <tr>
        <td><?php echo $this->Html->link($item['Item']['name'], array('action' => 'view', $item['Item']['id'], 'orderId' => $orderId)); ?></td>
        <td><?php echo $item['Item']['price']; ?></td>
        <td><?php echo $item['Item']['type']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php 


echo $this->Paginator->numbers(); 
echo $this->Paginator->prev(' « Prev ', null, null, array('class' => 'disabled')); 
echo " ";
echo $this->Paginator->next(' Next » ', null, null, array('class' => 'disabled')); 
echo $this->Paginator->counter(); 
?>

<script>
    function limit(value){
        window.location = '<?php echo $this->Html->url(array("action" => "index", "restore" => "true", 'orderId'=> $orderId));?>'+'/limit:'+value;
    }
    function SelectElement(valueToSelect){    
        var element = document.getElementById('limitSelect');
        element.value = valueToSelect;
    }
    
    $(document).ready(function(){
        SelectElement(<?php echo $limit ?>);
    });
    
</script>
<div style="float: right">
    Ilosc pozycji na stronie: 
    <select id="limitSelect" ONCHANGE="limit(this.options[this.selectedIndex].value);">
      <option value="10"  style="cursor:pointer;">10</option>
      <option value="20"  style="cursor:pointer;">20</option>
      <option value="50"  style="cursor:pointer;">50</option>
      <option value="100" style="cursor:pointer;">100</option>
    </select>
</div>
<div style="clear: both"></div>