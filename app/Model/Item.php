<?php

class Item extends AppModel {
    var $name = 'Item';
    
    public $hasOne = array(
        'Image' => array(
            'dependent' => true
        ),
    );
    public $hasMany = array(
        'OrderItem' => array(
            'dependent' => true
        )
    );
    public $hasAndBelongsToMany = array(
        'Order' => array(
            'joinTable' => 'order_items',
            'dependent' => false
        )
    );
    
    
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'Wype³nij pole'
        ),
    );
}