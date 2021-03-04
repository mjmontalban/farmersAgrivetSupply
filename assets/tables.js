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
});