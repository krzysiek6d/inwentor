<?php

class Order extends AppModel {
    var $name = 'Order';
    public $belongsTo = array(
        'Client' => array(
            'dependent' => false
        )
    );
    public $hasMany = array(    
        'OrderItem' => array(
            'dependent' => false
        )
    );
}