<?php 

class Admin extends MY_Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->ion_auth->logged_in()){
			redirect('login');
		}else{
            if(!$this->ion_auth->is_admin()){
                echo "You must be administrator to view access this page!";die();
            }
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
        // get total quantity today
        $qtyToday = $this->universal->get(
            false,
            "item_purchased",
            "SUM(purchased_qty) as totalQty",
            "row",
            array(
                "DATE(purchased_date)" => date("Y-m-d")
            )
        );
        $users = $this->universal->get(
            false,
            "users",
            "COUNT(id) as totalUsers",
            "row",
            array(
                "active" => 1
            )
        );
        $invoice = $this->universal->get(
            false,
            "invoice",
            "COUNT(id) as totalInvoice",
            "row",
            array(
                "DATE(date_added)" => date("Y-m-d")
            )
        );
        $data["sales"] = (!empty($salesToday->total)) ? $salesToday->total : 0;
        $data["qty"] = (!empty($qtyToday->totalQty)) ? $qtyToday->totalQty : 0;
        $data["usersTotal"] = $users->totalUsers;
        $data["totalInvoice"] = $invoice->totalInvoice;
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
        $rand = $this->randomInvoiceNumber();
        if(!empty($postData)){
            foreach($postData["item"] as $key => $value){
                if(!isset($postData["check"][$key])){
                    continue;
                }
                $details = $this->universal->get(
                    false,
                    "items",
                    "*",
                    "row",
                    array(
                       "id" => $value
                    )
                );
    
                $orders["items"][] = array(
                  "item_id" => $value,
                  "category_id" => $details->category_id,
                  "item_name" => $details->item_name,
                  "price" => $details->item_price,
                  "description" => $details->description,
                  "order_quantity" => $postData["quantity"][$key],
                  "to_pay" => $details->item_price * $postData["quantity"][$key]
                );
            }
            if(empty($orders["items"])){
                $this->session->set_flashdata("message","Please CONFIRM purchase by checking the checkbox!");
                redirect("admin/purchase","refresh");
            }
            $orders["invoice_num"] = $rand;
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
        // echo '<pre>';
        // print_r($orders->invoice_num);die();
        $insertInvoice = $this->universal->insert(
            "invoice",
            array(
                "id" => $orders->invoice_num,
                "date_added" => date("Y-m-d H:i:s"),
                "added_by" => $this->ion_auth->user()->row()->id
            )
        );
        foreach($orders->items as $key => $order){
            $insert_purchase = $this->universal->insert(
                "item_purchased",
                array(
                    "item_id" => $order->item_id,
                    "category_id" => $order->category_id,
                    "purchased_qty" => $order->order_quantity,
                    "purchased_date" => date("Y-m-d H:i:s"),
                    "total_payment" => $order->to_pay,
                    "user_id" => $user_id,
                    "invoice_id" => $orders->invoice_num
                )
            );
        }
        echo json_encode(array(
            "status" => true
        ));
    }

    public function soldItems(){
        $post = $this->input->post();
        if(!empty($post)){
            $draw = $this->input->post('draw');
            $length = $this->input->post('length');
            $offset = $this->input->post('start');
            $search = $this->input->post('search');
            $order = $this->input->post('order');
            $columns = $this->input->post('columns');
            if(!empty($order)){
                $setorder =  array($columns[$order[0]['column']]['data'] => $order[0]['dir']);
              }else{
                $setorder = array();
              }
            $where = array(
            
            );
        
            if(empty($search['value'])){
                $like = array();
            }else{
                $like = array(
                    'items.item_name' => $search['value'],
                    'category.category_name' => $search['value'],
                    'users.first_name' => $search['value'],
                );
            }
        
            $result = $this->universal->datatables(
                'item_purchased',
                'item_purchased.*,items.item_name,users.first_name,category.category_name',
                $where,
                array(
                    "items" => "item_purchased.item_id = items.id",
                    "users" => "item_purchased.user_id = users.id",
                    "category" => "item_purchased.category_id = category.id"
                ),
                array($length => $offset),
                $setorder,
                $like,
                true
            );
            echo json_encode(
                array(
                    'draw' => intval($draw),
                    "recordsTotal" => $result['recordsTotal'],
                    "recordsFiltered" => $result['recordsFiltered'],
                    "data" => $result['data']
                )
            );
        }else{
            $data["main"] = 'soldItems';

            $this->renderPage("admin/sold",$data);
        }
       
        
    }
    public function randomInvoiceNumber() {
        $result = '';
    
        for($i = 0; $i < 6; $i++) {
            $result .= mt_rand(0, 9);
        }
        $check = $this->universal->get(
            false,
            "invoice",
            "*",
            "all",
            array(
                "id" => $result
            )
        );
        if(!empty($check)){
            $this->randomInvoiceNumber();
        }else{
            return $result;
        }     
    }

    public function invoiceSearch(){
        $post = $this->input->post();
        if(!empty($post)){
            $draw = $this->input->post('draw');
            $length = $this->input->post('length');
            $offset = $this->input->post('start');
            $search = $this->input->post('search');
            $order = $this->input->post('order');
            $columns = $this->input->post('columns');
            if(!empty($order)){
                $setorder =  array($columns[$order[0]['column']]['data'] => $order[0]['dir']);
              }else{
                $setorder = array();
              }
            $where = array(
            
            );
        
            if(empty($search['value'])){
                $like = array();
            }else{
                $like = array(
                    'invoice.id' => $search['value'],
                    'DATE(invoice.date_added)' => $search['value'],
                    'users.first_name' => $search['value'],
                );
            }
        
            $result = $this->universal->datatables(
                'invoice',
                'invoice.*,users.first_name',
                $where,
                array(
                    "users" => "invoice.added_by = users.id",
                ),
                array($length => $offset),
                $setorder,
                $like,
                true
            );
            echo json_encode(
                array(
                    'draw' => intval($draw),
                    "recordsTotal" => $result['recordsTotal'],
                    "recordsFiltered" => $result['recordsFiltered'],
                    "data" => $result['data']
                )
            );
        }else{
            $data["main"] = 'invoice';
            
            $this->renderPage("admin/invoice",$data);
        }
    }

    public function manageUsers(){
        $post = $this->input->post();
        if(!empty($post)){
            $draw = $this->input->post('draw');
            $length = $this->input->post('length');
            $offset = $this->input->post('start');
            $search = $this->input->post('search');
            $order = $this->input->post('order');
            $columns = $this->input->post('columns');
            if(!empty($order)){
                $setorder =  array($columns[$order[0]['column']]['data'] => $order[0]['dir']);
              }else{
                $setorder = array();
              }
            $where = array(
                "users_groups.group_id" => 2
            );
        
            if(empty($search['value'])){
                $like = array();
            }else{
                $like = array(
                    'users.first_name' => $search['value'],
                    'users.email' => $search['value'],
                    'users.phone' => $search['value'],
                );
            }
        
            $result = $this->universal->datatables(
                'users',
                'users.*,',
                $where,
                array(
                    "users_groups" => "users.id = users_groups.user_id",
                ),
                array($length => $offset),
                $setorder,
                $like,
                true
            );
            echo json_encode(
                array(
                    'draw' => intval($draw),
                    "recordsTotal" => $result['recordsTotal'],
                    "recordsFiltered" => $result['recordsFiltered'],
                    "data" => $result['data']
                )
            );
        }else{
            $data["main"] = 'myusers';
            
            $this->renderPage("admin/myusers",$data);
        }
    }

    public function invoice_details($invoiceId = null){
        if(is_null($invoiceId)){
            echo "ERROR URL!"; die();
        }

        $orders = $this->universal->get(
            false,
            "item_purchased",
            "item_purchased.*,items.item_name,items.description,items.item_price",
            "array",
            array(
                "invoice_id" => $invoiceId
            ),
            array(),
            array(
                "items" => "item_purchased.item_id = items.id"
            )
        );
        if(empty($orders)){
            echo "Invoice # not found on database. Please put the correct invoice #.";die();
        }
        $data["main"] = 'invoice_details';
        $data["orders"] = $orders;
            
        $this->renderPage("admin/invoice_details",$data);
    }
    public function updateUserAccess(){
        $id = $this->input->post("user_id");
        $status = $this->input->post("setActive");
        $this->universal->update(
            "users",
            array(
                "active" => $status
            ),
            array(
                "id" => $id
            )
        );

        echo json_encode(array(
            "message" => "Updated Successfully!"
        ));
    }

    public function accountSettings(){
        $admin_id = $this->ion_auth->user()->row()->id;
        $data["userdata"] = $this->universal->get(
          false,
          "users",
          "*",
          "row",
          array(
              "id" => $admin_id
          )
        );

        $data["main"] = 'settings';    
        $this->renderPage("admin/settings",$data);
    }

    public function updateProfile(){
        $post = $this->input->post();
        if(!empty($this->emailChecker($post["email"]))){
            $this->session->set_flashdata("info","Email Already Taken!");
            redirect("admin/accountSettings","refresh");
        }
        $update = $this->universal->update(
            "users",
            array(
                "first_name" => $post["firstname"],
                "last_name" => $post["lastname"],
                "phone" => $post["phone"],
                "email" => $post["email"]
            ),
            array(
                "id" => $post["user_id"]
            )
        );
        if($update){
            $this->session->set_flashdata("info","Profile Updated!");
            redirect("admin/accountSettings","refresh");
        }
    }
    public function emailChecker($em){
        return $this->universal->get(false,"users","email","row",array("email"=>$em));
    }

    public function analytics(){
        $data["main"] = 'analytics';
        if(empty($this->input->post())){
            $date = date("Y-m-d");
        }else{
            $date = $this->input->post("fdate");
        }
        $data["analytics"] = $this->universal->get(
            false,
            "item_purchased",
            "SUM(purchased_qty) as quantity, SUM(total_payment) as sales,CONCAT(users.first_name,' ',users.last_name) as user",
            "all",
            array(
                "DATE(purchased_date)" => $date
            ),
            array(),
            array(
                "users" => "item_purchased.user_id = users.id"
            ),
            array(),
            array(),
            array(
                "item_purchased.user_id"
            ),
        );
        $data["fdate"] = $date;
        $this->renderPage("admin/analytics",$data);
    }
}