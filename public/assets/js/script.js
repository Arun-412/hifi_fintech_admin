$(document).ready(function(){
    $('.loader-section').fadeOut('slow');
    $('#show_password').hide();
    $('#c_show_password').hide();
    $('#hide_password').click(function(){
         $('#password').attr('type', 'text');
         $('#show_password').show();
         $('#hide_password').hide();
    });
    $('#show_password').click(function(){
        $('#password').attr('type', 'password');
        $('#show_password').hide();
        $('#hide_password').show();
    });
    $('#c_hide_password').click(function(){
        $('#c_password').attr('type', 'text');
        $('#c_show_password').show();
        $('#c_hide_password').hide();
   });
   $('#c_show_password').click(function(){
       $('#c_password').attr('type', 'password');
       $('#c_show_password').hide();
       $('#c_hide_password').show();
   });

   $('#report_data').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
    initComplete: function() {
        $('.buttons-copy').html('<i class="bi bi-clipboard-check-fill"></i> Copy  ')
        $('.buttons-csv').html('<i class="bi bi-filetype-csv"></i> CSV ')
        $('.buttons-excel').html('<i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel')
        $('.buttons-pdf').html('<i class="bi bi-file-earmark-pdf-fill"></i> PDF ')
        $('.buttons-print').html('<i class="bi bi-printer-fill"></i> Print')
       },
    buttons: [      
        {
            extend: 'copy',
            title: 'HIFI FINTECH Transaction Report',
            className: 'export_btn',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
            }
        },
        {
            extend: 'csv',
            title: 'HIFI FINTECH Transaction Report',
            className: 'export_btn',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
            },
            autoFilter: true,
            sheetName: 'HIFI FINTECH Transaction Report'
        },
        {
            extend: 'excel',
            className: 'export_btn',
            title: 'HIFI FINTECH Transaction Report',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
            },
            autoFilter: true,
            sheetName: 'HIFI FINTECH Transaction Report'
        },
        {
            extend: 'pdf',
            className: 'export_btn',
            title: 'HIFI FINTECH Transaction Report',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
            },
            orientation: 'portrait',
            pageSize: 'A4'
        },
        {
            extend: 'print',
            className: 'export_btn',
            title: 'HIFI FINTECH Transaction Report',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
            },
            orientation: 'portrait',
            pageSize: 'A4',
            messageBottom: null,
            customize: function (win) {
                $(win.document.body).find('table').addClass('print_table');
                $(win.document.body).find('th').css('border','1px solid gray');
                $(win.document.body).find('td').css('border','1px solid gray');
                $(win.document.body).find('h1').css('text-align','center');
            }
        },
    ]
});
$('#providers_list_table').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
   
    buttons: []
});
$('#services_list_table').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
   
    buttons: []
});
$('#payout_rules_table').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
   
    buttons: []
});
$('#wallet_topup').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
   
    buttons: []
});

$('#retailers_list').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
   
    buttons: []
});

$('#distributers_list').DataTable({
    dom:'Blfrtip',
    
    colReorder: true,
    fixedHeader: {
        header: true
    }, 
   
    buttons: []
});
});

$('#wallet_topup').on('click', 'tbody #top_up', function () {
    var data_row = $('#wallet_topup').DataTable().row($(this).closest('tr')).data();
    $('#w_user_code').val(data_row[0]);
    $('#w_shop_name').val(data_row[2]);
    $('#w_mobile_number').val(data_row[3]);
    $('#w_current_balance').val(data_row[4]);
    $('#w_hold_balance').val(data_row[5]);
    $('#wallet_top_up_model').modal('show');
})

$('#wallet_submit').on('click', function () {
    $('.loader-section').fadeIn('slow');
    $.ajax({
        url: "/wallet/actions",
        method:"POST",
        data: { 
            "shop_name":$('#w_shop_name').val(),
            "amount":$('#w_amount').val(),
            "user_code":$('#w_user_code').val(),
            "action_type":$('#wallet_action_type').find(":selected").val(),
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            if(data['status'] == true){
                $('#t_success_body').text(data['message']);
                $('#t_success').toast('show');
                $('#wallet_top_up_model').modal('hide');
                $('.loader-section').fadeOut('slow');
                setInterval(location.reload(),5000);
            }else{
                $('#t_failed_body').text(data['message']);
                $('#t_failed').toast('show');
                $('#wallet_top_up_model').modal('hide');
                $('.loader-section').fadeOut('slow');
            }
        },
        error: function (xhr, status, error) {
            var message = xhr['responseText'];
            message = JSON.parse(message);
            message= message['message'];
            $('#t_failed_body').text(message);
            $('#t_failed').toast('show');
            $('#wallet_top_up_model').modal('hide');
            $('.loader-section').fadeOut('slow');
        }
    });
});

