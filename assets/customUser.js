$(document).ready(function(){
    const initSelect2 = () => {
        $(".select_item").select2({
          theme: 'bootstrap4',
          placeholder:'--Select Item--',
          ajax: {
            url: site_url + 'users/getItemSelect',
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
            return fetch(site_url+'users/generatePurchase',
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
                  window.location.href = site_url + 'users/purchase';
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
});