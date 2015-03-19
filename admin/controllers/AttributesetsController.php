<?php

class AttributesetsController extends Controller {

    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }

    public function indexAction() {
        $data['sets'] = getModel('attributeset')->getCollection();
        $this->view->renderAdmin('attributeset/list.phtml', $data);
    }

    public function newAction() {
        $data['attributes'] = getModel('attribute')->getCollection();
        $this->view->renderAdmin('attributeset/new.phtml', $data);
    }

    public function newPostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        $attributes = $post_data['attributes'];
        unset($post_data['attributes']);
        $attribute_set_id = getModel('attributeset')->insert($post_data);
        if ($attributes) {
            foreach ($attributes as $count => $attribute) {
                echo $attribute;
                $relation = array('attribute_set_id' => $attribute_set_id, 'attribute_id' => $attribute, 'sort_order' => $count);
                getModel('attributeset')->insertRelation($relation);
            }
        }

        redirect('admin/attributesets');
    }

    public function deleteAction($id) {
        getModel('attributeset')->delete(array('type' => 'AND', 'attribute_set_id' => $id));
        redirect('admin/attributesets');
    }

    public function editAction($id) {
        $data['attributeset'] = getModel('attributeset')->load(array('type' => 'AND', 'attribute_set_id' => $id));
        $data['attributes'] = getModel('attribute')->getCollection();
        $data['selected_attributes'] = getModel('attributeset')->getAttributes(array('type' => 'AND', 'attribute_set_id' => $id));
        $this->view->renderAdmin('attributeset/edit.phtml', $data);
    }

    public function editPostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        getModel("attributeset")->update($post_data);
        $relation_array = array();
        foreach ($post_data['attributes'] as $count => $attribute) {
            $condition = array('type' => 'AND', 'attribute_id' => $attribute, 'attribute_set_id' => $post_data['attribute_set_id']);
            $id = getModel('attributeset')->relationExists($condition);
            if (!$id) {
                $relation = array('attribute_set_id' => $post_data['attribute_set_id'], 'attribute_id' => $attribute, 'sort_order' => $count);
                $id = getModel('attributeset')->insertRelation($relation);
            }
            array_push($relation_array, $id);
        }
        getModel('attributeset')->deleteExtraRelations($relation_array, $post_data['attribute_set_id']);

        redirect('admin/attributesets');
    }

}
