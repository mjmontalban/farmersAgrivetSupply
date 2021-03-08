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
         // get Today Sales
         $salesToday = $this->universal->get(
            false,
            "item_purchased",
            "SUM(total_payment) as total",
            "row",
            array(
                "DATE(purchased_date)" => date("Y-m-d"),
                "user_id" => $this->ion_auth->user()->row()->id
            )
        );
       $data["today"] = (!empty($salesToday->total)) ? $salesToday->total : 0;
       $data["content"] = 'user/dashboard';
       $this->_renderUserPage($data);
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
              "item_purchased.user_id" => $this->ion_auth->user()->row()->id
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
            $data["content"] = 'user/sold';
            $this->_renderUserPage($data);
        }
       
        
    }
    public function purchase(){
        $data["content"] = 'user/purchase';
        $this->_renderUserPage($data);

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
                redirect("users/purchase","refresh");
            }
            $orders["invoice_num"] = $rand;
            $data["orders"] = $orders;
            $data["content"] = 'user/summary';
            $this->_renderUserPage($data);
        }else{
            echo "Oppps. You should not be here.";die();
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
                "invoice.added_by" => $this->ion_auth->user()->row()->id
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
            $data["content"] = 'user/invoice';
            $this->_renderUserPage($data);
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
                "item_purchased.invoice_id" => $invoiceId,
                "item_purchased.user_id" => $this->ion_auth->user()->row()->id
            ),
            array(),
            array(
                "items" => "item_purchased.item_id = items.id"
            )
        );
        if(empty($orders)){
            echo "Invoice # not found on database. Please put the correct invoice #.";die();
        }
        
        $data["orders"] = $orders;
            
        $data["content"] = 'user/invoice_details';
        $this->_renderUserPage($data);
    }

    public function accountSettings(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data["userdata"] = $this->universal->get(
          false,
          "users",
          "*",
          "row",
          array(
              "id" => $user_id
          )
        );

        $data["content"] = 'user/settings';
        $this->_renderUserPage($data);
    }

    public function updateProfile(){
        $post = $this->input->post();
        if(!empty($this->emailChecker($post["email"]))){
            $this->session->set_flashdata("info","Email Already Taken!");
            redirect("users/accountSettings","refresh");
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

}