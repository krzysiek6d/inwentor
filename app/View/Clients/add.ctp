
<?php echo $this->Form->create('Client'); ?>
    <fieldset>
        <legend><?php echo __('Dodaj nowego klienta'); ?></legend>
        <?php echo $this->Form->input('Client.name', array('label'=>'Imiê klienta', 'type' => 'text')); ?>
        <?php echo $this->Form->input('Client.surname', array('label'=>'Nazwisko klienta', 'type' => 'text')); ?>
    </fieldset>
<?php echo $this->Form->end(__('Ok')); ?>
