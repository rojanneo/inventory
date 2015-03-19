<?php

class AttributesController extends Controller {

    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }

    public function indexAction() {
        $data['attributes'] = getModel('attribute')->getCollection();
        $this->view->renderAdmin('attributes/list.phtml', $data);
    }

    public function newAction() {
        $this->view->renderAdmin('attributes/new.phtml');
    }

    public function newPostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        $attribute_id = getModel('attribute')->insert($post_data);
        if ($post_data['attribute_type'] == 'select' or $post_data['attribute_type'] == 'multiselect') {
            $option = array();
            $count = 0;
            $sort_order = $post_data['sort_order'];
            foreach ($post_data['value'] as $val) {
                $option['attribute_id'] = $attribute_id;
                $option['value'] = $val;
                $option['sort_order'] = $sort_order[$count++];
                getModel('option')->insert($option);
            }
        }
        redirect('admin/attributes');
    }

    public function deleteAction($id) {
        getModel('attribute')->delete(array('type' => 'AND', 'attribute_id' => $id));
        redirect('admin/attributes');
    }

    public function editAction($id) {
        $data['attribute'] = getModel('attribute')->load(array('type' => 'AND', 'attribute_id' => $id));
        $this->view->renderAdmin('attributes/edit.phtml', $data);
    }

    public function editPostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        getModel('attribute')->update($post_data);
        if ($post_data['attribute_type'] == 'select' or $post_data['attribute_type'] == 'multiselect') {
            $values = $post_data['value'];
            $sort_orders = $post_data['sort_order'];
            $optionModel = getModel('option');
            $id_array = array();
            foreach ($values as $id => $value) {
                if ($optionModel->exists($id)) {
                    $optionModel->update(array('type' => 'AND', 'id' => $id, 'attribute_id' => $post_data['attribute_id'], 'value' => $value, 'sort_order' => $sort_orders[$id]));
                } else {
                    $optionModel->insert(array('type' => 'AND', 'attribute_id' => $post_data['attribute_id'], 'value' => $value, 'sort_order' => $sort_orders[$id]));
                }
                array_push($id_array, $id);
            }
            $optionModel->deleteExtraOptions($id_array);
        }
        redirect('admin/attributes');
    }

}
