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
  $(document).on("click","#add_row",function(){
     let html = '<div class="row mt-4"><div class="col-md-4"><select name="item[]"  class="form-control select_item" id="">'+
                  '</select></div>'+
          
          '<div class="col-md-4" id="what">'+
             '<input type="text" name="quantity[]" class="form-control"> </div>'+
          '<div class="col-md-4">'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="checkbox" id="checkboxPrimary'+ctr+'" value="1" name="check[]">'+
                            '<label for="checkboxPrimary'+ctr+'">Confirm</label></div></div></div>';
    
     $("#mainContent").append(html);
     ctr++;
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
    var item = $(this).data("item");
    var description = $(this).data("description");
    var quantity = $(this).data("quantity");
    var price = $(this).data("price");

    $("#itemId").val(id);
    $("#iname").val(item);
    $("#description").val(description);
    $("#quantity").val(quantity);
    $("#price").val(price);
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

  $(document).on('click','#invoice_details',function(){
    var id = $(this).data("id");

    alert(id);
  });

});