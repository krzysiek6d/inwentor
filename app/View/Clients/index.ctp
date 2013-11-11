<p><p><?php // create button
    echo $this->Form->create(array('action' => 'add', 'type' => 'get'));
    echo $this->Form->end('Dodaj nowego klienta');
?></p>

</p>
<table>
    <tr>
        <th>Imiê i nazwisko</th>
    </tr>

    <?php foreach ($clients as $client): ?>
    <tr>
        <td><?php echo $this->Html->link($client['Client']['name']. ' ' . $client['Client']['surname'],    array('controller'=> 'clients', 'action' => 'view', 'id' => $client['Client']['id'])); ?></td>
    </tr>
    <?php endforeach; ?>

</table>