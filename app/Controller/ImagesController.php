<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImagesController
 *
 * @author krzys
 */
class ImagesController extends AppController{
    function showImg($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Nie znaleziono obrazka'));
        }

        $image = $this->Image->findById($id);
        if (!$image) {
            throw new NotFoundException(__('Nie znaleziono obrazka'));
        }
        
        $this->set('image', $image);
    }
}

?>
