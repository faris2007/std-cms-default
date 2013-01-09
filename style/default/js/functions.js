$(document).ready(function(){
    
    // For Menu
    $('.navigation li').hover(
            function () {
            $('ul', this).fadeIn();
            },
            function () {
            $('ul', this).fadeOut();
            }
    );
        
    $('#signatureurl').popupWindow({ 
        height:550, 
        width:400, 
        top:50, 
        left:50 
    }); 
    $('#contracturl').popupWindow({ 
        height:550, 
        width:900,
        scrollbars:1,
        top:50, 
        left:50 
    }); 
    if ($('#list').attr('dataajax') !== undefined) {
        $('#list').dataTable({
                    "aLengthMenu": [[50, 100, 250, 500, -1], [50, 100, 250, 500, "All"]],
                    "sPaginationType": "full_numbers",
                    "bJQueryUI": true,
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "../ajax/"+$('#list').attr('dataajax'),
                    "sServerMethod": "POST",
                    
                    "oLanguage": {
                            "oAria": {
                                "sSortAscending": " - click/return to sort ascending",
                                "sSortDescending": " - click/return to sort descending"
                            },
                            "oPaginate": {
                                "sFirst": "First",
                                "sLast": "Last",
                                "sNext": "Next",
                                "sPrevious": "Previous"
                            },
                            "sLengthMenu": "Display _MENU_ records per page",
                            "sZeroRecords": "Nothing found - sorry",
                            "sInfo": "Showing _START_ to _END_ of _TOTAL_ records",
                            "sInfoEmpty": "Showing 0 to 0 of 0 records",
                            "sInfoFiltered": "(filtered from _MAX_ total records)",
                            "sEmptyTable": "No data available in table",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "Please wait - loading...",
                            "sProcessing": "DataTables is currently busy",
                            "sSearch": "Search:"
                    }
            });
    }else{
        $('#list').dataTable({
                    "aLengthMenu": [[50, 100, 250, 500, -1], [50, 100, 250, 500, "All"]],
                    "sPaginationType": "full_numbers",
                    "bJQueryUI": true,
                    "oLanguage": {
                            "oAria": {
                                "sSortAscending": " - click/return to sort ascending",
                                "sSortDescending": " - click/return to sort descending"
                            },
                            "oPaginate": {
                                "sFirst": "First",
                                "sLast": "Last",
                                "sNext": "Next",
                                "sPrevious": "Previous"
                            },
                            "sLengthMenu": "Display _MENU_ records per page",
                            "sZeroRecords": "Nothing found - sorry",
                            "sInfo": "Showing _START_ to _END_ of _TOTAL_ records",
                            "sInfoEmpty": "Showing 0 to 0 of 0 records",
                            "sInfoFiltered": "(filtered from _MAX_ total records)",
                            "sEmptyTable": "No data available in table",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "Please wait - loading...",
                            "sProcessing": "DataTables is currently busy",
                            "sSearch": "Search:"
                    }
            });

    }
    
    
});

function action(url,type,id){
    if(type == 'enable'){
        $('#action').load(url);
        $('#action').css( "display", "block" );
        url.replace('enable','disable');
        $('#action').replace('0 -','');
        $('#action').replace('1 -','');
        $('#'+id).html('<img src="./style/default/icon/enable.png" onclick="action(\"'+url+'\",\'disable\',\"'+id+'\")" />');
        $('#action').fadeOut(5000, function(){
            $(this).css("display","none");
            $(this).html("");
        });
    }else if(type == 'disable'){
        $('#action').load(url);
        $('#action').css( "display", "block" );
        url.replace('disable','enable');
        $('#action').replace('0 -','');
        $('#action').replace('1 -','');
        $('#'+id).html('<img src="./style/default/icon/disable.png" onclick="action(\"'+url+'\",\'enable\',\"'+id+'\")" />');
        $('#action').fadeOut(5000, function(){
            $(this).css("display","none");
            $(this).html("");
        });
    }else if(type == 'delete'){
        var check = $.get(url,function(data){
            if(data.match(/1/g) != null)
            {
                data.replace('1 -','');
                $('#action').html(data);
                $('#action').css( "display", "block" );
                $('#'+id).click(function() {
                    //change the background color to red before removing
                    $(this).css("background-color","#FF3700");
                    $(this).css("color","#FFFFFF");
                    $(this).fadeOut(1000, function(){
                        $(this).remove();
                    });
                });

                $('#action').fadeOut(5000, function(){
                    $(this).css("display","none");
                    $(this).html("");
                });
            }
        });
    }
}
