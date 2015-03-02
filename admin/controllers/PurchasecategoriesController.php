<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchasecategoriesController
 *
 * @author Neo
 */
class PurchasecategoriesController extends Controller {
    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }
    
    public function indexAction()
    {
        $data['categories'] = getModel('purchasecategory')->getSubCategories();
        $this->view->renderAdmin('purchasecategories/list.phtml',$data);
    }
    
    public function newAction()
    {
        $data['p_categories'] = getModel('purchasecategory')->getCollection(array('is_active'=>'1'));
        $this->view->renderAdmin('purchasecategories/form.phtml',$data);
    }

    public function editajaxAction($id)
    {
        $data['p_categories'] = getModel('purchasecategory')->getCollection(array('is_active'=>'1'));
        $data['category'] = getModel('purchasecategory')->load($id);
        $this->view->renderWithoutAnything('purchasecategories/editform.phtml',$data);
    }

    public function addAction()
    {
        $data['p_categories'] = getModel('purchasecategory')->getCollection(array('is_active'=>'1'));
        $this->view->renderWithoutAnything('purchasecategories/form.phtml',$data);
    }
    
    public function newpostAction()
    {
        loadHelper('inputs');
        $post_data = getPost();
        if(getModel('purchasecategory')->insert($post_data))
        {
            AdminSession::addSuccessMessage('Purchase Category Created');
        }
        else
        {
            AdminSession::addErrorMessage('Failed to create Purchase Category');
        }
        
        redirect('admin/purchasecategories');
    }
    
    public function getSubCategoriesAction($parent_id)
    {
        $data['sub_cats'] = getModel('purchasecategory')->getSubCategories($parent_id);
        $html = $this->view->renderWithoutAnything('purchasecategories/sublist.phtml',$data);
        echo $html;
    }
}
