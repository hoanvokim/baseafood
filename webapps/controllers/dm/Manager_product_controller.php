<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: NhuTran
 * Date: 1/31/16
 * Time: 1:23 AM
 * To change this template use File | Settings | File Templates.
 */
class Manager_product_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', '', TRUE);
        $this->load->model('category_model', '', TRUE);
        $this->load->model('product_model', '', TRUE);
        $this->load->model('tags_model', '', TRUE);
    }

    public function index()
    {
        if (!$this->is_login()) {
            $this->load_login_view();
            return;
        }
        $data['title'] = 'Product administration';
        $categories = $this->category_model->findAll();
        $products_category = array();
        foreach ($categories as $category) {
            $products = $this->product_model->findAllProductByCategory($category['id']);
            array_push($products_category, array(
                'id' => $category['id'],
                'en_name' => $category['en_name'],
                'products' => $products,
            ));
        }

        $data['categories'] = $products_category;
        $this->load->view('pages/admin/product/index', $data);
    }

    public function create_new()
    {
        $this->load->library('upload');
        if (!$this->is_login()) {
            $this->load_login_view();
            return;
        }
        $categoryId = $this->uri->segment(2);
        if ($categoryId) {
            $data['category'] = $this->category_model->findById($categoryId);
        }
        $data['title'] = 'Product creation';
        $data['error'] = '';
        $data['tags'] = $this->tags_model->findAll();
        $this->load->view('pages/admin/product/create', $data);
    }

    public function post_create_new()
    {
        if (!$this->is_login()) {
            $this->load_login_view();
            return;
        }
        $data['title'] = 'Product creation';
        $this->load->library('form_validation');
        $this->load->library('upload', $this->get_config());
        $categoryId = $this->input->post('cid');
        if ($categoryId) {
            $data['category'] = $this->category_model->findById($categoryId);
        }
        $this->form_validation->set_rules('en_name', 'en_name', 'trim|required|is_unique[product.en_name]', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.'
        ));
        $this->form_validation->set_rules('vi_name', 'vi_name', 'trim|required|is_unique[product.vi_name]', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.'
        ));
        if ($this->upload->do_upload('userfile')) {
            $upload_files = $this->upload->data();
            $file_path = 'assets/upload/images/products/' . $upload_files['file_name'];
            if ($this->form_validation->run() == TRUE) {
                $this->product_model->insert($this->input->post('vi_name'), $this->input->post('en_name'), $this->input->post('cid'), $file_path, $this->input->post('size'), $this->input->post('packing'));
                $tags = $this->input->post('tags');
                $product = $this->product_model->findByEnName($this->input->post('en_name'));
                foreach ($tags as $tag) {
                    $this->tags_model->saveReferenceProduct($tag, $product['id']);
                }
                redirect('product-manager', 'refresh');
            }
        }
        $data['tags'] = $this->tags_model->findAll();
        $data['error'] = $this->upload->display_errors();
        $this->load->view('pages/admin/product/create', $data);
    }

    public function update()
    {
        $this->load->library('upload');
        if (!$this->is_login()) {
            $this->load_login_view();
            return;
        }
        $data['title'] = 'Edit product';
        $data['error'] = '';
        $product = $this->product_model->findById($this->uri->segment(3));
        $data['tags'] = $this->tags_model->findAll();
        $data['active_tags'] = $this->tags_model->findByProduct($product['id']);
        if ($product) {
            $data['product'] = $product;
            $data['category'] = $this->category_model->findById($product['fk_category']);
            $this->load->view('pages/admin/product/update', $data);
            return;
        }
        redirect('product-manager', 'refresh');
    }

    public function post_update()
    {
        if (!$this->is_login()) {
            $this->load_login_view();
            return;
        }
        $data['title'] = 'Edit product';
        $this->load->library('form_validation');
        $this->load->library('upload', $this->get_config());
        $categoryId = $this->input->post('cid');
        if ($categoryId) {
            $data['category'] = $this->category_model->findById($categoryId);
        }
        $this->form_validation->set_rules('vi_name', 'vi_name', 'trim|required|callback_check_unique_vi_name', array(
            'required' => 'You have not provided %s.',
            'check_unique_vi_name' => 'This %s already exists.'
        ));
        $this->form_validation->set_rules('en_name', 'en_name', 'trim|required|callback_check_unique_en_name', array(
            'required' => 'You have not provided %s.',
            'check_unique_en_name' => 'This %s already exists.'
        ));


        if ($this->upload->do_upload()) {
            $upload_files = $this->upload->data();
            $file_path = 'assets/upload/images/products/' . $upload_files['file_name'];
            if ($this->form_validation->run() == TRUE) {
                $this->product_model->update($this->input->post('pid'),
                    $this->input->post('vi_name'),
                    $this->input->post('en_name'),
                    $this->input->post('cid'),
                    $file_path,
                    $this->input->post('size'),
                    $this->input->post('packing'));
                redirect('product-manager', 'refresh');
            }
        }

        $error = isset($_FILES['userfile']['error']) ? $_FILES['userfile']['error'] : 4;
        $upload_error = false;
        if (sizeof($this->upload->error_msg) == 1 && $error == 4) {
            if ($this->form_validation->run() == TRUE) {
                $this->product_model->update($this->input->post('pid'),
                    $this->input->post('vi_name'),
                    $this->input->post('en_name'),
                    $this->input->post('cid'),
                    null,
                    $this->input->post('size'),
                    $this->input->post('packing'));
                redirect('product-manager', 'refresh');
            }
        }
        else {
            $upload_error = true;
        }
        if ($upload_error) {
            $data['error'] = $this->upload->display_errors();
        }
        else {
            $data['error'] = '';
        }

        $product = $this->product_model->findById($this->input->post('pid'));
        $product['vi_name'] = $this->input->post('vi_name');
        $product['en_name'] = $this->input->post('en_name');
        $product['size'] = $this->input->post('size');
        $product['packing'] = $this->input->post('packing');
        $data['product'] = $product;
        $data['tags'] = $this->tags_model->findAll();
        $data['active_tags'] = $this->tags_model->findByProduct($product['id']);
        $this->load->view('pages/admin/product/update', $data);
    }

    public function delete()
    {
        if (!$this->is_login()) {
            $this->load_login_view();
            return;
        }
        $data['title'] = 'Delete product?';
        $product = $this->product_model->findById($this->uri->segment(3));
        if ($product) {
            $data['product'] = $product;
            $this->load->view('pages/admin/product/delete', $data);
            return;
        }
        redirect('product-manager', 'refresh');
    }

    public function post_delete()
    {
        $id = $this->uri->segment(2);
        $product = $this->product_model->findById($id);
        if ($product) {
            @unlink($product['url']);
            $this->product_model->delete($id);
        }
        redirect('product-manager', 'refresh');
    }

    private function get_config()
    {
        return array(
            'upload_path' => "./assets/upload/images/products/",
            'allowed_types' => "gif|jpg|png|jpeg",
            'overwrite' => TRUE,
            'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
        );

    }

    function check_unique_vi_name($vi_name)
    {
        $result = null;
        $id = $this->input->post('pid');
        $product = $this->product_model->findById($id);
        if ($product['vi_name'] == $vi_name) {
            return true;
        }
        if (isset($vi_name)) {
            $result = $this->product_model->findByViName($vi_name);
        }
        return !isset($result);
    }

    function check_unique_en_name($en_name)
    {
        $result = null;
        $id = $this->input->post('pid');
        $product = $this->product_model->findById($id);
        if ($product['en_name'] == $en_name) {
            return true;
        }
        if (isset($en_name)) {
            $result = $this->product_model->findByEnName($en_name);
        }
        return !isset($result);
    }
}
