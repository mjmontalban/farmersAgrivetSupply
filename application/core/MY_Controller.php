<?php 

class MY_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function renderPage($content,$data = array()){
        $this->load->view("includes/header",$data);
        $this->load->view($content);
        $this->load->view("includes/footer");
    }
}