$(document).ready(function(){
    var soldTables = $("#itemSoldTable").DataTable({
        "processing" : true,
        "serverSide": true,
        "responsive": true,
        "order": [[0,'desc']],
        "ajax": {
            "url" : site_url + 'users/soldItems',
            "type": "POST"
        },
        "columns" : [
            {"data" : "item_name"},
            {"data" : "category_name"},
            {"data" : "purchased_qty"},
            {"data" : "total_payment"},
            {"data" : "purchased_date"},
            {"data" : "first_name"}
        ]
    });

    var invoiceTables = $("#invoiceTable").DataTable({
        "processing" : true,
        "serverSide": true,
        "responsive": true,
        "order": [[0,'desc']],
        "ajax": {
            "url" : site_url + 'users/invoiceSearch',
            "type": "POST"
        },
        "columns" : [
            {"data" : "id"},
            {"data" : "first_name"},
            {"data" : "date_added"},
            {
                "data" : "id" , render : function(data, type, row, meta){
                    return '<a href="'+site_url+'users/invoice_details/'+row.id+'" target="_blank" class="btn btn-block btn-primary btn-sm" id="invoice_details">Show Details</a>';
                }
            },
            
        ]
    });
});