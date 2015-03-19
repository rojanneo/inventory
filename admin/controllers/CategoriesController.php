<?php

class CategoriesController extends Controller {

    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }

    public function indexAction() {
        echo 'here';
    }

    public function addAction() {
        $data['categories'] = getModel('category')->getCollection();
        $this->view->renderAdmin('categories/new.phtml', $data);
    }

    public function addPostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        getModel('category')->insert($post_data);
        redirect('admin/categories/add');
    }

}
