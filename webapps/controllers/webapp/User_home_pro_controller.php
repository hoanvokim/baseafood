<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: hoanvo
 * Date: 3/5/16
 * Time: 10:23 AM
 */
class User_home_pro_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('utilities');
        $this->utilities->loadPropertiesFiles($this->lang);
    }

    public function index()
    {
        $data['title'] = 'Baseafood';
        $this->load->view('pages/home_pro', $data);
    }

}
