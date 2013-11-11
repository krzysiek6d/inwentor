<?php

class Client extends AppModel {
    var $name = 'Client';
    public $hasMany = array(
        'Order' => array(
            'dependent' => true
        )
    );
}