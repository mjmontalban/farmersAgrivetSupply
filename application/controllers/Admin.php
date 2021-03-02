<?php 

class Admin extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->ion_auth->logged_in()){
			redirect('login');
		}
    }

    public function dashboard(){
        $this->renderPage("admin/dashboard",$data = array());
    }

    public function addCategory(){

    }

    public function addItems(){

    }

    public function getCategories(){

    }

    public function getItems(){
        
    }
}