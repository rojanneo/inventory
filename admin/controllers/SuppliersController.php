<?php

class SuppliersController extends Controller {

    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }

    public function indexAction() {
        $data['suppliers'] = getModel('supplier')->getCollection();
        $this->view->renderAdmin('suppliers/suppliers.phtml', $data);
    }

    public function newAction() {
        $this->view->renderAdmin('suppliers/form.phtml');
    }

    public function newpostAction() {
        loadHelper('inputs');
        $data = getPost();

        if (getModel('supplier')->insert($data)) {
            AdminSession::addSuccessMessage('Supplier Added');
        } else {
            AdminSession::addErrorMessage('Error adding supplier');
        }

        redirect('admin/suppliers');
    }

    public function filterSuppliersAction() {
        loadHelper('inputs');
        $data_post = getPost();
        $data['suppliers'] = getModel('supplier')->getFilteredList($data_post);
        //var_dump($data);die;
        $html = $this->view->renderWithoutAnything('suppliers/filter.phtml', $data);

        echo $html;
    }

    public function editAction($supplier_id) {
        $data['supplier'] = getModel('supplier')->load($supplier_id);
        $this->view->renderAdmin('suppliers/form.phtml', $data);
    }

    public function editpostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        if (getModel('supplier')->update($post_data)) {
            AdminSession::addSuccessMessage('Supplier edited successfully');
        } else
            AdminSession::addErrorMessage('Supplier Editing Failed');

        redirect('admin/suppliers');
    }

}
