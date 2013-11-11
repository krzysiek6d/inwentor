<?php

class ClientsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session', 'ItemExtension'); 
    public $components = array('Session');
    
    public function index() { //lista klientow
    	$this->Client->recursive = -1;
    	$clients = $this->Client->find('all');
        $this->set('clients', $clients);
    }

    public function view() { //info o kliencie.. imie, nazwisko itp
    	$this->Client->recursive = -1;
    	$id = $this->passedArgs['id'];
        $client = $this->Client->findById($id);
        $this->set('client', $client);
    }

    public function showClientOrders() // lista zamowien klienta
    {
   		$clientId = $this->passedArgs['clientId'];
   		$client = $this->Client->findById($clientId);
    	$this->set('client', $client);
    	$this->render('/Orders/index');
    	Debugger::dump($client);
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Client->create();
            if ($this->Client->save($this->request->data)) {
                $this->Session->setFlash(__('Dodano nowego klienta'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Wyst¹pi³ b³¹d podczas dodawania nowego klienta.'));
            }
        }
    }
    
    

    public function edit($id = null) {
        if (!$id) {
            $this->Session->setFlash('Nie znaleziono klienta.');
            $this->redirect(array('action' => 'index'));
        }

        $client = $this->Client->findById($id);
        if (!$client) {
            $this->Session->setFlash('Nie znaleziono klienta.');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('client',$client);
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Client->id = $id;
            if ($this->Client->save($this->request->data)) {                
                $this->Session->setFlash('Edycja zakoñczona powodzeniem.');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Edycja nie powiod³a siê.');
            }
        }

        if (!$this->request->data) {
            $this->request->data = $client;
        }
    }

    public function delete($id) {
        $this->Client->id = $id;
        if (!$this->Client->exists()) {
            throw new NotFoundException(__('Nie znaleziono klienta.'));
        }
        if ($this->Report->delete()) {
            $this->Session->setFlash(__('Usuniêto obiekt'));
            $this->redirect(array('controller' => 'clients', 'action' => 'index'));
        }
        $this->Session->setFlash(__('Wyst¹pi³ b³¹d podczas usuwania obiektu'));
        $this->redirect(array('controller' => 'clients', 'action' => 'index'));    
    }
    
    
    /*
    public function pdfreport($id)
    {
        
        if (!$id) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index'));
        }
        
        $joins = array(
            array('table' => 'images',
                'alias' => 'Image',
                'type' => 'LEFT',
                'conditions' => array(
                    'Image.puzzle_id = Item.id',
                )
            ),
        );
        
        $fields = array('Item.id', 'Item.name', 'Item.description', 'Item.type', 'Image.id', 'Image.puzzle_id', 'Image.name', 'Image.type', 'Image.size', 'Image.data');
        
        $items = $this->Client->Order->Item->find(
                'all', 
                array(
                    'fields' => $fields, 
                    'joins' => $joins, 
                    'conditions' => array(
                        'Client.id' => $id,
                    )
                )
        );
        
        if (!$items) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('items', $item);
    }*/
}