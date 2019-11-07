jQuery( document ).ready( function($) {
    $('.wp-menu-management-main ul li').click(function(){
        $('.wp-menu-management-main ul li').removeClass('active'); 
        $(this).addClass('active');
        let classname = '.' + $(this).attr('data-tab');
        if($(classname).hasClass($(this).attr('data-tab'))){ 
            $('.tab-content').removeClass('active'); 
            $(classname).addClass('active');
            $('.tab-content.active').show();
        }          
    });

    $(".btn-delete-menus").on("click", function(){
        var get_select_val = $("#select-delete-menu").val();
        if(get_select_val =='select-menu-empt'){
            alert('Please select menu from dropdown');
        }
    });
    $(".btn-export-menus").on("click", function(){
        var get_select_val1 = $("#select-export-menu").val();
        if(get_select_val1 =='select-menu'){
            alert('Please select menu from dropdown');
        }
    });
});

