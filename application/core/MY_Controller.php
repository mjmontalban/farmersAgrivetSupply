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

    public function _renderUserPage($data){
        $this->load->view("includes/user/header",$data);
        $this->load->view($data["content"]);
        $this->load->view("includes/user/footer");
    }
}