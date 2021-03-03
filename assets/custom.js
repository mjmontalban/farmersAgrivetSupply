$(document).ready(function(){

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
                            '<input type="checkbox" id="checkboxPrimary'+ctr+'" name="check[]">'+
                            '<label for="checkboxPrimary'+ctr+'">Confirm</label></div></div></div>';
    
     $("#mainContent").append(html);
     ctr++;
     initSelect2();
  });

  


});