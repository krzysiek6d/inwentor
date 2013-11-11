<h1>Dodaj</h1>
<?php
echo $this->Html->script('fileUpload');
echo $this->Form->create('Item', array('action' => 'add', 'type' => 'file'));

echo $this->Form->input('Item.name', array('label'=>'Nazwa', 'type' => 'text'));
echo $this->Form->input('Item.price', array('label'=>'Cena', 'type' => 'text'));
echo $this->Form->input('Item.description', array('label'=>'Opis'));
echo $this->Form->input('Item.type', array(
            'label'=>'Rodzaj pracy',
            'options' => Configure::read('itemTypes')
        ));
echo $this->Form->file('Image.File');
echo $this->Html->div('error', '', array('id' => 'ImageFileError', 'style' => $this->Html->style(array('display' => 'none'))));
echo $this->Form->end('Dodaj');
?>