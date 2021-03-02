<?php 

class Admin extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->ion_auth->logged_in()){
			redirect('login');
		}
    }

    public function dashboard(){
        $data["main"] = 'dashboard';
        $this->renderPage("admin/dashboard",$data);
    }

    public function addCategory(){
        $data["categories"] = $this->universal->get(
            false,
            "category",
            "*",
            "all"
        );
        $data["main"] = 'addCategory';
        $this->renderPage("admin/addCategory",$data);
    }

    public function items(){
     $data["categories"] = $this->universal->get(
         false,
         "category",
         "*",
         "all",
         array(
             "status" => 1
         )
     );

     $data["items"] = $this->universal->get(
        false,
        "items",
        "*",
        "all"
    );
     $data["main"] = 'items';
     $this->renderPage("admin/addItems",$data);

    }

    public function getCategories(){

    }

    public function insertCategory(){
        $post = $this->input->post();
        $insert = $this->universal->insert(
          "category",
          array(
              "category_name" => $post["category_name"],
              "status" => $post["status"],
              "add_date" => date("Y-m-d H:i:s")
          )
        );
        if($insert){
            $this->session->set_flashdata("message","Inserted Succesfully!");
            redirect("admin/addCategory");
        }
    }
    public function insertItems(){
        $post = $this->input->post();
        $insert = $this->universal->insert(
          "items",
          array(
              "item_name" => $post["item_name"],
              "category_id" => $post["category"],
              "description" => $post["description"],
              "quantity" => $post["quantity"],
              "item_price" => $post["price"],
              "add_date" => date("Y-m-d H:i:s")
          )
        );
        if($insert){
            $this->session->set_flashdata("message","Inserted Succesfully!");
            redirect("admin/items");
        }
    }
}