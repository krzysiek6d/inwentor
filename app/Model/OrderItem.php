<?php

class OrderItem extends AppModel {
    var $name = 'OrderItem';
    public $belongsTo = array(
        'Item' => array(
            'dependent' => false
        ),
        'Order' => array(
            'dependent' => false
        )
    );
}