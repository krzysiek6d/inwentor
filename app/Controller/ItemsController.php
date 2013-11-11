

<?php

class ItemsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session', 'ItemExtension');
    public $components = array('Session');
    
    const defaultPaginateLimit = 10;
    const defaultSortField = 'Item.id';
    const defaultSortDirection = 'asc';
    
    //values stored in session
    
    //paginator:
    private $sort;
    private $direction;
    private $paginateLimit;
    //    
    private $search;
    private $fields;
    


    //values passed as a search criteria
    private $startPrice;
    private $endPrice;
    private $searchConditions;
    
    //bonus
    private $restore;
    private $orderId;
    private $joins;
    private function setSortingAndPaginationLimit()
    
    {
        if(isset($this->passedArgs['sort']))     
            $this->sort = $this->passedArgs['sort'];
        else if  ($this->Session->check('sort')) 
            $this->sort = $this->Session->read('sort');
        else 
        	$this->sort = self::defaultSortField;
        
        if (isset($this->passedArgs['direction']))  
            $this->direction = $this->passedArgs['direction'];
        else if ($this->Session->check('direction'))
            $this->direction = $this->Session->read ('direction');
        else 
        	$this->direction = self::defaultSortDirection;
        	
        if (isset($this->passedArgs['limit']))
            $this->paginateLimit = $this->passedArgs['limit'];
        else if ($this->Session->check('limit'))
            $this->paginateLimit = $this->Session->read('limit');
        else 
        	$this->paginateLimit = self::defaultPaginateLimit;
        
        $this->Session->write('sort', $this->sort);
        $this->Session->write('direction', $this->direction);
        $this->Session->write('limit', $this->paginateLimit);
    }
    
    private function setSearchFilters()
    {
		$this->startPrice =  '';
        $this->endPrice = '';
    	if($this->request->is('post'))
		{
            $this->search = $this->request->data['Item'];
            $this->startPrice = $this->request->data['Item']['startPrice'];  
            $this->endPrice = $this->request->data['Item']['endPrice']; 
		} 
    	else if($this->Session->check('search'))
    	{
    		$this->search = $this->Session->read('search');
    	}
    	else 
    	{
    		$this->search = array( 'name' => '', 
                                   'description' => '', 
                                   'startPrice' => '', 
                                   'endPrice' => '', 
                                   'type' => ''
                                   );
    	}
    	$this->Session->write('search', $this->search);
    }
    
    private function setClientAndOrderTypeSearchConditions()
    {
    	$this->orderId = isset($this->passedArgs['orderId']) ? $this->passedArgs['orderId'] : null;
    	
        if($this->orderId)               
            {
                $this->searchConditions['OrderItem.order_id'] = $this->orderId;
                //$this->fields[] = 'Client.name';
                //$this->fields[] = 'Order.id';
                //$this->loadModel('Client');
                //$this->loadModel('OrderItem');
            }
    }

    private function setSearchConditions()
    {
    	$this->setSearchFilters();
    	
    	if(!($this->startPrice==='') && !($this->endPrice==='') )
    	{
    		$this->searchConditions = array(
                    'AND' => array(
                        'Item.name LIKE ' => '%'.  $this->search['name'].'%',
                        'Item.description LIKE' => '%'.  $this->search['description'].'%',
                        'Item.type LIKE' => '%'.  $this->search['type'].'%',
                        'Item.price BETWEEN ? AND ?' => array($this->startPrice, $this->endPrice)
                    )
                );
    	}
    	else if(!($this->startPrice===''))
    	{
    		$this->searchConditions = array(
                    'AND' => array(
                        'Item.name LIKE ' => '%'.  $this->search['name'].'%',
                        'Item.description LIKE' => '%'.  $this->search['description'].'%',
                        'Item.type LIKE' => '%'.  $this->search['type'].'%',
                        'Item.price >' => $this->startPrice
                    )
                );
    	}
    	else if(!($this->endPrice==='') )
    	{
    		$this->searchConditions = array(
                    'AND' => array(
                        'Item.name LIKE ' => '%'.  $this->search['name'].'%',
                        'Item.description LIKE' => '%'.  $this->search['description'].'%',
                        'Item.type LIKE' => '%'.  $this->search['type'].'%',
                        'Item.price <' => $this->endPrice
                    )
                );
    	}
    	else
    	{
    		$this->searchConditions = array(
                    'AND' => array(
                        'Item.name LIKE ' => '%'.  $this->search['name'].'%',
                        'Item.description LIKE' => '%'.  $this->search['description'].'%',
                        'Item.type LIKE' => '%'.  $this->search['type'].'%'
                    )
                );
    	}
    	
    	$this->setClientAndOrderTypeSearchConditions();
    }
    
    private function clearAll()
    {
        if ($this->Session->check('search'))
            $this->Session->delete('search');
        if ($this->Session->check('sort'))
            $this->Session->delete('sort');
        if ($this->Session->check('direction'))
            $this->Session->delete('direction');
        if ($this->Session->check('limit'))
            $this->Session->delete('limit');
        $this->paginateLimit = self::defaultPaginateLimit;
        $this->searchConditions = null;
        $this->search = array( 'name' => '', 
                               'description' => '', 
                               'startPrice' => '', 
                               'endPrice' => '', 
                               'type' => ''
                             );
        $this->sort=  self::defaultSortField;
        $this->direction= self::defaultSortDirection;
    }

    
    private function __findIndexPageAndRedirectToItWithGivenItemId($itemId)
    {
        $itemRow = 0;
     
        $items = $this->orderId ?
            $items = $this->Item->OrderItem->find('all', array(
                'conditions' => $this->searchConditions,
                'fields' => 'Item.id',
                'order' => array($this->sort => $this->direction),
            )) :
            $items = $this->Item->find('all', array(
                'conditions' => $this->searchConditions,
                'fields' => 'Item.id',
                'order' => array($this->sort => $this->direction),
            ));
        
        
        foreach ($items as $item)
            if ($item['Item']['id'] == $itemId) break; else $itemRow++;
        $page = floor($itemRow / $this->paginateLimit)+1;

        $url = $this->passedArgs;
        $url['page'] = $page;
        $url['itemId'] = null;
        $this->redirect($url);
    }

    
    private function __findNeighbors($id)
    {
        $prev = null;
        $next = null;
        $wasfound = false;
        $all = $this->Item->find('all', array(
            'conditions' => $this->searchConditions,
            'fields' => 'Item.id',
            'order' => array($this->sort => $this->direction),
         ));
        foreach ( $all as $item)
        {
            if ($item['Item']['id'] == $id)
            {
                $wasfound = true;
                continue;
            }
            if ($wasfound)
            {
                $next = $item['Item']['id'];
                break;
            }
            $prev = $item['Item']['id'];
        }
        $neighbors['prev'] = $prev;
        $neighbors['next'] = $next;
        return $neighbors;
    }
    
    private function __findNeighborsInSelectedClient($id)
    {
    	Debugger::dump($this->sort);
    	
        $this->searchConditions['OrderItem.order_id'] = $this->orderId;
        //$this->fields[] = 'Client.name';
        //$this->fields[] = 'Order.id';
        //$this->loadModel('Client');
        $this->loadModel('Order');
        $fields = array('Item.id');
        $prev = null;
        $next = null;
        $wasfound = false;
        $all = $this->Item->OrderItem->find('all', array(
            'conditions' => $this->searchConditions,
            'fields' => 'Item.id',
            'order' => array($this->sort => $this->direction),
         ));
        foreach ( $all as $item)
        {
            if ($item['Item']['id'] == $id)
            {
                $wasfound = true;
                continue;
            }
            if ($wasfound)
            {
                $next = $item['Item']['id'];
                break;
            }
            $prev = $item['Item']['id'];
        }
        $neighbors['prev'] = $prev;
        $neighbors['next'] = $next;
        return $neighbors;    
    }
    

    public function index() {
    	
    	
        $validate = array(
                'allowEmpty' => true,
            );
        $this->Item->validate = $validate;
        
        
        $this->fields = array(
            'Item.id', 
            'Item.name', 
            'Item.price', 
            'Item.type', 
        );
        $this->setSortingAndPaginationLimit();
        $this->setSearchConditions();
        
        $itemId = isset($this->passedArgs['itemId']) ? $this->passedArgs['itemId'] : null;
        if ($itemId != null) $this->__findIndexPageAndRedirectToItWithGivenItemId($itemId);

        if (isset($this->passedArgs['clear']))$this->clearAll();
        
        Debugger::dump($this->sort);
            $this->paginate = array(
                       'limit' => $this->paginateLimit, 
                       'conditions' => $this->searchConditions,
                       'fields' => $this->fields,
                       'order' => array($this->sort => $this->direction),

                );
            
            
            $items = $this->orderId ? $this->paginate($this->Item->OrderItem) : $this->paginate('Item'); //?????????
            
            $this->set('items', $items);
            $this->set('search', $this->search);
            $this->set('orderId', $this->orderId);
            $this->set('limit', $this->paginateLimit);         
             
    }
    

    public function view($id) {

    	if (!$id) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index'));
        }
        
        $item = $this->Item->findById($id);
        if (!$item) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index'));
        }
        
        $this->setSortingAndPaginationLimit();
        $this->setSearchConditions();

    	Debugger::dump($this->sort);
        if ($this->orderId != NULL) {
             $neighbors = $this->__findNeighborsInSelectedClient($id);
        }
        else $neighbors = $this->__findNeighbors($id);
        
        $this->set('item', $item);
        $this->set('neighbors', $neighbors);
        $this->set('comesFromOrder', $this->orderId);
        $this->set('sort', $this->sort);
        $this->set('direction', $this->direction);
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                if (is_uploaded_file($this->request->data['Image']['File']['tmp_name'])) {
                    
                    $this->Item->Image->create();
                    $this->request->data['Image']['item_id'] = $this->Item->id;
                    $this->request->data['Image']['name'] = $this->request->data['Image']['File']['name'];
                    $this->request->data['Image']['type'] = $this->request->data['Image']['File']['type'];
                    $this->request->data['Image']['size'] = $this->request->data['Image']['File']['size'];
                    $this->request->data['Image']['data'] = $this->getImageData($this->request->data['Image']['File']['tmp_name']);

                    $this->Item->Image->save($this->request->data);
                }
                $this->Session->setFlash('Dodano do bazy.');
                $this->redirect($this->redirect($this->referer()));
            } else {
                $this->Session->setFlash('Wyst��pi�� b����d podczas dodawania do bazy.');
            }
        }
    }
    
    public function edit($id = null) {
        
        $redirectTo = $this->referer(); //array('action' => 'index','restore' => 'true');
        
        if (!$id) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect($redirectTo);
        }

        $item = $this->Item->findById($id);
        if (!$item) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect($redirectTo);
        }
        $this->set('item',$item);
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Item->id = $id;
            if ($this->Item->save($this->request->data)) {                
                if (is_uploaded_file($this->request->data['Image']['File']['tmp_name'])) {
                    
                    $this->request->data['Image']['id'] = $item['Image']['id'];
                    $this->request->data['Image']['item_id'] = $this->Item->id;
                    $this->request->data['Image']['name'] = $this->request->data['Image']['File']['name'];
                    $this->request->data['Image']['type'] = $this->request->data['Image']['File']['type'];
                    $this->request->data['Image']['size'] = $this->request->data['Image']['File']['size'];
                    $this->request->data['Image']['data'] = $this->getImageData($this->request->data['Image']['File']['tmp_name']);

                    $this->Item->Image->save($this->request->data);
                }
                $this->Session->setFlash('Edycja zakończona powodzeniem.');
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Session->setFlash('Edycja nie powiodła się.');
            }
        }

        if (!$this->request->data) {
            $this->request->data = $item;
        }
    }

    public function getImageData($file, $newWidth = 480, $newHeight=480){
        
        $src_img = imagecreatefromstring(file_get_contents($file));
        $oldWidth=imageSX($src_img);
        $oldHeight=imageSY($src_img);
        
        if($oldHeight != $newHeight)
        {
            $filename = $file.'tmp';
            
            if($oldWidth < $oldHeight)
            {
                $thumb_w=$oldWidth*($newWidth/$oldHeight);
                $thumb_h=$newHeight;
            }
            else
            {
                $thumb_h=$oldHeight*($newHeight/$oldWidth);
                $thumb_w=$newWidth;
            }
            
            $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
            imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$oldWidth,$oldHeight);
            imagejpeg($dst_img,$filename); 
            
            $data = file_get_contents($filename);
            
            unlink($filename);
            unlink($file);
            imagedestroy($dst_img);
            
            return $data;
        }
        return file_get_contents($file);
    }
    
    public function viewPdf($id = null){
        if (!$id) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index','restore'=>'true'));
        }
        $items = $this->Item->find('all', array( 'conditions' => array('Item.id' => $id)));
        if (!$items) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index','restore'=>'true'));
        }
        $this->set('items', $items);
    }
    /*
    public function viewPdfClient($clientId)
    {
                
        if (!$clientId) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('action' => 'index'));
        }
        
        $joins = array(
            array('table' => 'images',
                'alias' => 'Image',
                'type' => 'LEFT',
                'conditions' => array(
                    'Image.item_id = Order.item_id',
                )
            ),
        );
        
        $fields = array('Item.id', 'Item.name', 'Item.price', 'Item.description', 'Item.type', 'Item.price', 'Image.id', 'Image.item_id', 'Image.name', 'Image.type', 'Image.size', 'Image.data');
        
        $items = $this->Item->Order->find(
                'all', 
                array(
                    'fields' => $fields, 
                    'joins' => $joins, 
                    'conditions' => array(
                        'Order.client_id' => $clientId,
                    )
                )
        );
        //Debugger::dump($items);
        if (!$items) {
            $this->Session->setFlash('Nie znaleziono obiektu o podanym identyfikatorze.');
            $this->redirect(array('controller' =>'clients', 'action' => 'index'));
        }
        $this->set('items', $items);
    }
*/

    public function showbarcode($data=null, $size=3){
        $this->set('data', $data);
        $this->set('size',$size);
    }
    
}

