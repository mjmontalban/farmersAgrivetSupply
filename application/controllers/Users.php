<?php 

class Users extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->ion_auth->logged_in()){
			redirect('login');
		}else{
            if($this->ion_auth->is_admin()){
                echo "You have no authority to view access this page!";die();
            }
        }
    }

    public function dashboard(){
        echo "users dashbaord";
    }

}