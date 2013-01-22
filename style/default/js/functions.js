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
                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "sPaginationType": "full_numbers",
                    "bJQueryUI": true,
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": base_url+"ajax/"+$('#list').attr('dataajax'),
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
                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
    
    $('#addNewPermission').click(function(){
        if($(this).attr('val') == 'open'){
            $('#addnewHidden').css( "display", "block" );
            $(this).attr('src',base_url+"style/default/icon/del.png");
            $(this).attr('val',"close");
        }else{
            $('#addnewHidden').css( "display", "none" );
            $(this).attr('src',base_url+"style/default/icon/add.png");
            $(this).attr('val',"open");
        }
    });
    $('#removeAdd').click(function(){
        $('#addnewHidden').css( "display", "none" );
    });
    
    $('#service_name').change(function(){
        var type = $(this).val();
        var check = $.get(base_url+"group/getData/"+type, function(data){
            $('#functions').html(data);
        });
    });
    
    $('#addButton').click(function (){
        $.post(base_url+"group/action/addp/"+$('#groupId').val(), { service_name: $('#service_name').val(), functions: $('#functions').val(),value:$('#value').val() },
            function(data) {
                
                $('#action').html(data);
                $('#action').css( "display", "block" );
                
                $('#action').fadeOut(5000, function(){
                    $(this).css("display","none");
                    $(this).html("");
                });
        });
    });
    
    $('#type_url').change(function(){
        if($(this).val() != 'page'){
            $('#select_page').css("display",'none');
            $('#select_url').css("display",'block');
        }else{
            $('#select_page').css("display",'block');
            $('#select_url').css("display",'none');
        }
    });
    
    $(".styleDate").datepicker();
    
    tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
    
});

function action(url,type,id,i){
    var check;
    if(type == 'enable'){
        $('#action').load(url);
        $('#action').css( "display", "block" );
        url = url.replace('enable','disable');
        disableImg = $('#'+id).attr('src').replace('disable.png','enable.png');
        $('#'+id).attr('src',disableImg);
        $('#'+id).attr('onclick',"action('"+url+"','disable','"+id+"','"+i+"')");
        $('#'+id).attr('alt','تعطيل');
        $('#'+id).attr('title','تعطيل');
        $('#action').fadeOut(5000, function(){
            $(this).css("display","none");
            $(this).html("");
        });
    }else if(type == 'disable'){
        $('#action').load(url);
        $('#action').css( "display", "block" );
        url = url.replace('disable','enable');
        enableImg = $('#'+id).attr('src').replace('enable.png','disable.png');
        $('#'+id).attr('src',enableImg);
        $('#'+id).attr('onclick',"action('"+url+"','disable','"+id+"','"+i+"')");
        $('#'+id).attr('alt','تفعيل');
        $('#'+id).attr('title','تفعيل');
        $('#action').fadeOut(5000, function(){
            $(this).css("display","none");
            $(this).html("");
        });
    }else if(type == 'delete'){
        check = $.get(url,function(data){
            if(data.match(/1/g) != null)
            {
                data.replace('1 -','');
                $('#action').html(data);
                $('#action').css( "display", "block" );
                /*$('#'+id).click(function() {
                    //change the background color to red before removing
                    $(this).css("background-color","#FF3700");
                    $(this).css("color","#FFFFFF");
                    $(this).fadeOut(1000, function(){
                        $(this).remove();
                    });
                });*/
                restoreImg = $('#deleteimg'+i).attr('src').replace('del.png','restore.png');
                //restoreImg.replace('del','resotre');
                $('#deleteimg'+i).attr('src',restoreImg);
                url = url.replace('delete','restore');
                $('#deleteimg'+i).attr('onclick',"action('"+url+"','restore','"+id+"','"+i+"')");
                $('#deleteimg'+i).attr('alt','استرجاع');
                $('#deleteimg'+i).attr('title','استرجاع');
                $('#action').fadeOut(5000, function(){
                    $(this).css("display","none");
                    $(this).html("");
                });
            }
        });
    }else if(type == 'deletep'){
        check = $.get(url,function(data){
            if(data.match(/1/g) != null)
            {
                data.replace('1 -','');
                $('#action').html(data);
                $('#action').css( "display", "block" );
                /*$('#'+id).click(function() {
                    //change the background color to red before removing
                    $(this).css("background-color","#FF3700");
                    $(this).css("color","#FFFFFF");
                    $(this).fadeOut(1000, function(){
                        $(this).remove();
                    });
                });*/
                $('#action').fadeOut(5000, function(){
                    $(this).css("display","none");
                    $(this).html("");
                });
            }
        });
    }else if(type == 'restore'){
        check = $.get(url,function(data){
            if(data.match(/1/g) != null)
            {
                data.replace('1 -','');
                $('#action').html(data);
                $('#action').css( "display", "block" );
                /*$('#'+id).click(function() {
                    //change the background color to red before removing
                    $(this).css("background-color","#FF3700");
                    $(this).css("color","#FFFFFF");
                    $(this).fadeOut(1000, function(){
                        $(this).remove();
                    });
                });*/
                delImg = $('#deleteimg'+i).attr('src').replace('restore.png','del.png');
                //delImg.replace('restore','del');
                $('#deleteimg'+i).attr('src',delImg);
                url = url.replace('restore','delete');
                $('#deleteimg'+i).attr('onclick',"action('"+url+"','delete','"+id+"','"+i+"')");
                $('#deleteimg'+i).attr('alt','حذف');
                $('#deleteimg'+i).attr('title','حذف');
                $('#action').fadeOut(5000, function(){
                    $(this).css("display","none");
                    $(this).html("");
                });
            }
        });
    }
}

function addNewPermission(tableId){
    $('#'+tableId+' tbody').append('<tr><td>#</td><td></td></tr>');
}
