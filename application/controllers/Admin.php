<?php 

class Admin extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->ion_auth->logged_in()){
			redirect('login');
		}
    }

    public function dashboard(){

        // get Today Sales
        $salesToday = $this->universal->get(
            false,
            "item_purchased",
            "SUM(total_payment) as total",
            "row",
            array(
                "DATE(purchased_date)" => date("Y-m-d")
            )
        );
        $data["today"] = $salesToday->total;
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

     
     $this->renderPage("admin/addItems",$data);

    }

    public function itemList(){
        $data["items"] = $this->universal->get(
            false,
            "items",
            "*",
            "all"
        );
         $data["main"] = 'itemlist';

     $this->renderPage("admin/itemList",$data);

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

    public function categoryItems($category = null,$name = null){
        if(is_null($category) || $category == "" || is_null($name) || $name == ""){
            echo "Invalid URL"; die();
        }
        
        $data["items"] = $this->universal->get(
          false,
          "items",
          "*",
          "all",
          array(
              "category_id" => $category
          )
        );
     $data["catName"] = urldecode($name);
     $data["main"] = 'categoryItems';
     $this->renderPage("admin/categoryItems",$data);
        
    }

    public function purchase(){
        $data["main"] = 'purchase';
        $this->renderPage("admin/purchase",$data);

    }

    public function getItemSelect(){
        $post = $this->input->post("searchTerm");

        $items = $this->universal->get(
            false,
            "items",
            "id,item_name as text",
            "all",
            array(),
            array(
                "item_name" => $post
            )
        );

        echo json_encode($items);
    }

    public function getDetailsCategory(){
        $post = $this->input->post("id");

        $get = $this->universal->get(
            false,
            "category",
            "*",
            "all",
            array(
                "id" => $post
            )
        );

        echo json_encode($get);
    }

    public function editDetailsCategory(){
        $post = $this->input->post();

        $update = $this->universal->update(
            "category",
            array(
                "category_name" => $post["cname"],
                "status" => $post["status"]
            ),
            array(
                "id" => $post["catId"]
            )
            );

        echo json_encode(array(
            "message" => "Updated Successfully!"
        ));
    }

    public function updateItems(){

        $post = $this->input->post();
        $update = $this->universal->update(
            "items",
            array(
                "item_name" => $post["iname"],
                "description" => $post["description"],
                "quantity" => $post["quantity"],
                "item_price" => $post["price"],
            ),
            array(
                "id" => $post["itemId"]
            )
        );

        echo json_encode(array(
            "message" => "Updated Successfully!"
        ));
    }

    public function purchaseSummary(){
        $postData = $this->input->post();
        $orders = array();
        if(!empty($postData)){
            foreach($postData["item"] as $key => $value){
                $details = $this->universal->get(
                    false,
                    "items",
                    "*",
                    "row",
                    array(
                       "id" => $value
                    )
                );
    
                $orders[] = array(
                  "item_id" => $value,
                  "category_id" => $details->category_id,
                  "item_name" => $details->item_name,
                  "price" => $details->item_price,
                  "description" => $details->description,
                  "order_quantity" => $postData["quantity"][$key],
                  "to_pay" => number_format($details->item_price * $postData["quantity"][$key],2)
                );
            }
            $data["orders"] = $orders;
            $data["main"] = 'summary';
            $this->renderPage("admin/summary",$data);
        }else{
            echo "Oppps. You should not be here.";die();
        }
        
    }

    public function generatePurchase(){
        $raw =  file_get_contents('php://input');
        $user_id = $this->ion_auth->user()->row()->id;
        $data = json_decode($raw);
        $orders = json_decode($data->orders);
        
        foreach($orders as $key => $order){
            $insert_purchase = $this->universal->insert(
                "item_purchased",
                array(
                    "item_id" => $order->item_id,
                    "category_id" => $order->category_id,
                    "purchased_qty" => $order->order_quantity,
                    "purchased_date" => date("Y-m-d H:i:s"),
                    "total_payment" => $order->to_pay,
                    "user_id" => $user_id
                )
            );
        }
        echo json_encode(array(
            "status" => true
        ));
    }

    public function test(){
        
    }
}