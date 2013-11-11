<?php

class OrdersController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');

    private function __addElement($itemId, $clientId)
    {
        $data = array('item_id' => $itemId, 'client_id' => $clientId);
        $this->Order->create();
        try {$this->Order->save($data);} catch (Exception $e){ throw $e; }
    }
    

    
    
    public function add() {
       if ($this->request->is('post')) {
           $dataToSave = $this->request->data;
           if(isset($dataToSave['items']))
                foreach ($dataToSave['items'] as $itemId) {
                    try{
                        $this->__addElement($itemId, $dataToSave['Client']['id']);
                        $this->Session->setFlash(__('Dodano do zam�wienia'));
                    }
                    catch(Exception $e)
                    {
                        if($e->getCode() == 23000) continue; //duplicate - ignore it
                        else{
                            $this->Session->setFlash(__('Wystąpił błąd podczas dodawania obiektu do raportu.\n ' . $e->getMessage()));
                            $this->redirect($this->referer());
                        }
                    }
                }
           $this->redirect($this->referer());
        }    
    }
    
    public function delete($id) {
        $this->Order->id = $id;
        if (!$this->Order->exists()) {
            $this->Session->setFlash(__('Nie znaleziono obiektu do usunięcia.'));
            $this->redirect($this->referer());
        }
        if ($this->Order->delete()) {
            $this->Session->setFlash(__('Usunięto obiekt z raportu'));
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Wystąpił błąd podczas usuwania obiektu z raportu'));
        $this->redirect($this->referer());
    }
    
}