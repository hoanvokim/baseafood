<?php
/**
 * Created by IntelliJ IDEA.
 * User: NhuTran
 * Date: 1/29/16
 * Time: 10:33 PM
 * To change this template use File | Settings | File Templates.
 */
class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Baseafood';

        $this->load->view('layout/header', $data);
        $this->load->view('pages/home', $data);
        $this->load->view('layout/footer');
    }
}