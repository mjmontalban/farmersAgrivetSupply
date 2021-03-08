$(document).ready(function(){
    var soldTables = $("#itemSoldTable").DataTable({
        "processing" : true,
        "serverSide": true,
        "responsive": true,
        "order": [[0,'desc']],
        "ajax": {
            "url" : site_url + 'admin/soldItems',
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
            "url" : site_url + 'admin/invoiceSearch',
            "type": "POST"
        },
        "columns" : [
            {"data" : "id"},
            {"data" : "first_name"},
            {"data" : "date_added"},
            {
                "data" : "id" , render : function(data, type, row, meta){
                    return '<button class="btn btn-block btn-primary btn-sm" id="invoice_details" data-id="'+row.id+'">Show Details</button>';
                }
            },
            
        ]
    });

    var usersTables = $("#usersTable").DataTable({
        "processing" : true,
        "serverSide": true,
        "responsive": true,
        "order": [[0,'desc']],
        "ajax": {
            "url" : site_url + 'admin/manageUsers',
            "type": "POST"
        },
        "columns" : [
            {"data" : "first_name"},
            {"data" : "email"},
            {
                "data" : "last_login" , render : function(data, type, row, meta){
                    return new Date(Number(row.last_login)).toDateString();
                }
            },
            {
                "data" : "id" , render : function(data, type, row, meta){
                    return '<button class="btn btn-block btn-primary btn-sm" id="modify_access" data-id="'+row.id+'">Modify Access</button>';
                }
            },
            
        ]
    });
});