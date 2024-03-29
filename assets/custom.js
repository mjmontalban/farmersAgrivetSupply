$(document).ready(function(){
  var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  var category = $("#categoryTable").DataTable({
      'responsive' : true
  });

  var item = $("#itemTable").DataTable({
    'responsive' : true
  });

  const initSelect2 = () => {
    $(".select_item").select2({
      theme: 'bootstrap4',
      placeholder:'--Select Item--',
      ajax: {
        url: site_url + 'admin/getItemSelect',
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
  }
    
  initSelect2();
  var ctr = 2;
  var ch = 1;
  $(document).on("click","#add_row",function(){
     let html = '<div class="row mt-4"><div class="col-md-4"><select name="item[]"  class="form-control select_item" id="">'+
                  '</select></div>'+
          
          '<div class="col-md-4" id="what">'+
             '<input type="text" name="quantity[]" class="form-control"> </div>'+
          '<div class="col-md-4">'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="checkbox" id="checkboxPrimary'+ctr+'" value="1" name="check['+ch+']">'+
                            '<label for="checkboxPrimary'+ctr+'">Confirm</label></div></div></div>';
    
     $("#mainContent").append(html);
     ctr++;
     ch++;
     initSelect2();
  });

  
$(document).on('click','#updateCat',function(){
  var id = $(this).data("id");

  $.ajax({
    url : site_url + 'admin/getDetailsCategory',
    method : 'POST',
    data : {
      id : id
    },
    dataType : 'json',
    success : function (response){
      $('#cname').val(response[0].category_name);
      $('#status').val(response[0].status);
      $('#catId').val(response[0].id);
    }
  })
});

$(document).on('submit','#form_edit',function(e){
  e.preventDefault();
  $.ajax({
    url : site_url + 'admin/editDetailsCategory',
    method : 'POST',
    data : new FormData(this),
    contentType: false,
    processData: false,
    dataType : 'json',
    success : function (response){
       Toast.fire({
          icon: 'success',
          title: response.message
       });

       setTimeout(function(){ location.reload(); }, 2000);

    }
  })
});

  $(document).on('click','#itemButtonUpdate',function(){
    var id = $(this).data("id");
    $.ajax({
      url : site_url + 'admin/getItemDetailsById',
      method : 'POST',
      data : {
        id : id
      },
      dataType : 'json',
      success : function (response){
          $("#itemId").val(response.id);
          $("#iname").val(response.item_name);
          $("#description").val(response.description);
          $("#quantity").val(response.quantity);
          $("#price").val(response.supplier_price_cost);
          $(".pricingOptions").val(response.pricingOption);
          var html = ``;
          var bid = ``;
          if (response.pricingOption == 0){
            html = `<div class="col-md-12">
            <label for="exampleInputPassword1">Price</label>
            <input type="text" class="form-control numOnly" value="${response.fix_price}" name="price" required>
          </div>`;
          bid = `<div class="col-md-12">
          <label for="exampleInputPassword1">Minimum Bidding</label>
          <input type="text" class="form-control numOnly" value="${response.fix_bid}" name="fix_bid" required>
        </div>`;
          }else if(response.pricingOption == 1){
            html = `<div class="col-md-4">
            <label for="exampleInputPassword1">Price Per Bundle</label>
            <input type="text" class="form-control numOnly" value="${response.per_bundle}" name="per_bundle" required>
          </div>
          <div class="col-md-4">
            <label for="exampleInputPassword1">Price Per Half Bundle</label>
            <input type="text" class="form-control numOnly" value="${response.per_half_bundle}" name="per_half_bundle" required>
          </div>
          <div class="col-md-4">
            <label for="exampleInputPassword1">Price Per Piece</label>
            <input type="text" class="form-control numOnly" value="${response.per_piece}" name="per_piece" required>
          </div>`;
          bid = `<div class="col-md-4">
            <label for="exampleInputPassword1">Bid Per Bundle</label>
            <input type="text" class="form-control numOnly" value="${response.bid_per_bundle}" name="bid_bundle" required>
          </div>
          <div class="col-md-4">
            <label for="exampleInputPassword1">Bid Per Half Bundle</label>
            <input type="text" class="form-control numOnly" value="${response.bid_per_half_bundle}" name="bid_half_bundle" required>
          </div>
          <div class="col-md-4">
            <label for="exampleInputPassword1">Bid Per Piece</label>
            <input type="text" class="form-control numOnly" value="${response.bid_per_piece}" name="bid_piece" required>
          </div>`;
          }else{
            html = `<div class="col-md-4">
            <label for="exampleInputPassword1">Price Per Sack</label>
            <input type="text" class="form-control numOnly" value="${response.per_sack}" name="per_sack" required>
          </div>
          <div class="col-md-4">
            <label for="exampleInputPassword1">Price Per Half Sack</label>
            <input type="text" class="form-control numOnly" value="${response.per_half_sack}" name="per_half_sack" required>
          </div>
          <div class="col-md-4">
            <label for="exampleInputPassword1">Price Per Kilogram</label>
            <input type="text" class="form-control numOnly" value="${response.per_kilogram}" name="per_kilogram" required>
          </div>`;
          bid = `<div class="col-md-4">
          <label for="exampleInputPassword1">Bid Per Sack</label>
          <input type="text" class="form-control numOnly" value="${response.bid_per_sack}" name="bid_sack" required>
        </div>
        <div class="col-md-4">
          <label for="exampleInputPassword1">Bid Per Half Sack</label>
          <input type="text" class="form-control numOnly" value="${response.bid_per_half_sack}" name="bid_half_sack" required>
        </div>
        <div class="col-md-4">
          <label for="exampleInputPassword1">Bid Per Kilogram</label>
          <input type="text" class="form-control numOnly" value="${response.bid_per_kilogram}" name="bid_kilogram" required>
        </div>`;
          }
    $(".priceList").html(html);
    $(".bidding").html(bid);
      }
    })
   
  });

  $(document).on('submit','#form_edit_items',function(e){
    e.preventDefault();
    
    $.ajax({
      url : site_url + 'admin/updateItems',
      method : 'POST',
      data : new FormData(this),
      contentType : false,
      processData : false,
      dataType : 'json',
      success : function (response){
        Toast.fire({
          icon: 'success',
          title: response.message
        });

         setTimeout(function(){ location.reload(); }, 2000);
      }
    })
  })

  

  $(document).on('click','#confirm',function(){
    Swal.queue([{
      title: 'Confirm Purchase?',
      confirmButtonText: 'Confirm',
      showCancelButton: true,
      allowOutsideClick: false,
      text:
        'This will generate purchase',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return fetch(site_url+'admin/generatePurchase',
            {
              method: 'POST',
              credentials: 'same-origin',
              mode: 'same-origin',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(
                {
                  orders : $('#orderList').val()
                }
              )
            }
        )
          .then(response => response.json())
          .then(data => Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Purchase Completed',
              showConfirmButton: false,
              timer: 1500
            }).then(function(){
              window.location.href = site_url + 'admin/purchase';
            })
          )
          .catch(() => {
            Swal.insertQueueStep({
              icon: 'error',
              title: 'Unable to get your public IP'
            })
          })
      }
    }])
  });

  $(document).on('click','.modifyAccessButton',function(){
    var id = $(this).data("id");
    $('#user_id').val(id);
  });

  $(document).on('submit','#modifyAccessForm',function(evt){
    evt.preventDefault();
    $.ajax({
      url : site_url + 'admin/updateUserAccess',
      method : 'POST',
      data : new FormData(this),
      contentType : false,
      processData : false,
      dataType : 'json',
      success : function (response){
        Toast.fire({
          icon: 'success',
          title: response.message
        });

         setTimeout(function(){ location.reload(); }, 2000);
      }
    })
  });

  $('#date_filter').datepicker({
    todayHighlight: true,
    format: "yyyy-mm-dd"
  });

  $(document).on("change",".pricingOptions",function(){
    var value = $(this).val();
    var html = ``;
    var bid = ``;
    if (value == 0){
      html = `<div class="col-md-12">
      <label for="exampleInputPassword1">Price</label>
      <input type="text" class="form-control numOnly" name="price" required>
    </div>`;
    bid = `<div class="col-md-12">
    <label for="exampleInputPassword1">Minimum Bidding</label>
    <input type="text" class="form-control numOnly" name="fix_bid" required>
  </div>`;
    }else if(value == 1){
      html = `<div class="col-md-4">
      <label for="exampleInputPassword1">Price Per Bundle</label>
      <input type="text" class="form-control numOnly" name="per_bundle" required>
    </div>
    <div class="col-md-4">
      <label for="exampleInputPassword1">Price Per Half Bundle</label>
      <input type="text" class="form-control numOnly" name="per_half_bundle" required>
    </div>
    <div class="col-md-4">
      <label for="exampleInputPassword1">Price Per Piece</label>
      <input type="text" class="form-control numOnly" name="per_piece" required>
    </div>`;
    bid = `<div class="col-md-4">
      <label for="exampleInputPassword1">Bid Per Bundle</label>
      <input type="text" class="form-control numOnly" name="bid_bundle" required>
    </div>
    <div class="col-md-4">
      <label for="exampleInputPassword1">Bid Per Half Bundle</label>
      <input type="text" class="form-control numOnly" name="bid_half_bundle" required>
    </div>
    <div class="col-md-4">
      <label for="exampleInputPassword1">Bid Per Piece</label>
      <input type="text" class="form-control numOnly" name="bid_piece" required>
    </div>`;
    }else{
      html = `<div class="col-md-4">
      <label for="exampleInputPassword1">Price Per Sack</label>
      <input type="text" class="form-control numOnly" name="per_sack" required>
    </div>
    <div class="col-md-4">
      <label for="exampleInputPassword1">Price Per Half Sack</label>
      <input type="text" class="form-control numOnly" name="per_half_sack" required>
    </div>
    <div class="col-md-4">
      <label for="exampleInputPassword1">Price Per Kilogram</label>
      <input type="text" class="form-control numOnly" name="per_kilogram" required>
    </div>`;
    bid = `<div class="col-md-4">
    <label for="exampleInputPassword1">Bid Per Sack</label>
    <input type="text" class="form-control numOnly" name="bid_sack" required>
  </div>
  <div class="col-md-4">
    <label for="exampleInputPassword1">Bid Per Half Sack</label>
    <input type="text" class="form-control numOnly" name="bid_half_sack" required>
  </div>
  <div class="col-md-4">
    <label for="exampleInputPassword1">Bid Per Kilogram</label>
    <input type="text" class="form-control numOnly" name="bid_kilogram" required>
  </div>`;
    }
    $(".priceList").html(html);
    $(".bidding").html(bid);

  })

  const regex = /[^\d.]|\.(?=.*\.)/g;
  const subst=``;



$(document).on('keyup','.numOnly',function(){
  const str = this.value;
  const result = str.replace(regex, subst);
  this.value=result;
});

  $(document).on('submit','#form_edit_items',function(evt){
    evt.preventDefault();
    alert("xx")
  });
});