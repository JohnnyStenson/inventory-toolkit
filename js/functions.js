$(window).on("unload", function(){   
    $.ajax({
        type: "POST",
        url: 'inv-controller.php',
        data: {
            run: 'clear-session'
        },
        success: function(response){
        }
    });
});


$(document).ready(function(){
    //Acode

    /* get current type (for refresh and init)*/
    if($('#menuInvItem').length) get_inv_item();
    function get_inv_item(){
        $('#loading_overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'get_inv_item'
            },
            success: function(response)
            {
                if(0 != response){
                    $('#menuInvItem a').removeClass('btn_menuInvItem_selected');
                    $('#btn_Menu_' + response).addClass('btn_menuInvItem_selected');
                }
                $('#loading_overlay').css('display','none');
            }
        });
    }


        


    /* Change  Type */
    $('body').delegate('.btn_menuInvItem', 'click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var inv_item = $(this).data('type');

        if('inv' == inv_item){
            $('#btnShowHideOF').css('display', 'block');
        }else{
            $('#btnShowHideOF').css('display', 'none');
        }

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'set_inv_item',
                inv_item: inv_item
            },
            success: function(response)
            {
                $('#menuInvItem a').removeClass('btn_menuInvItem_selected');
                $('#btn_Menu_' + inv_item).addClass('btn_menuInvItem_selected');
                display_records();
            }
        });
    });


    /* Login */
    $('#btnLogin').on('click', function(e) {
        e.preventDefault();
        $('#frmLogin').submit();
    });
    $('#frmLogin').on('submit', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var pw = $('#pw').val();
        $.ajax({
            type: "POST",
            url: 'login.php',
            data: {
                pw: pw,
            },
            success: function(response)
            {
                location.reload();
            }
        });
    });


    /* Admin Drawer */
    $('#display').delegate('.openAdminDrawer', 'click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('.admin_drawer[data-id="' + id + '"]').css('display', 'block');
        $('.hide_drawer[data-id="' + id + '"]').css('display', 'none');
    });


    /* Change Description */
    $('#display').delegate('.changeDescription', 'click', function(e) {
        var id = $(this).data('id');
        $('.btn_changeDescription[data-id="' + id + '"]').css('display', 'block');
        $('.hide_changeDescription[data-id="' + id + '"]').css('display', 'none');
    });

    $('#display').delegate('.btn_changeDescription', 'click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var id = $(this).data('id');
        var location = $(this).data('location');
        var descr = $('.changeDescription[data-id="' + id + '"]').val();
        
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'changeDescription-inv',
                id: id, 
                descr: descr
            },
            success: function(response)
            {
                alert('Changed');
                display_records();
            }
        });
    });


    


    /**
     * 
     */
    /* Move Inventory */
    $('#display').delegate('.btn_moveinv', 'click', function(e) {
        e.preventDefault();
        var inv_id = $(this).data('id');

        $('.hide_moveinv[data-id="' + inv_id + '"]').css('display', 'none');
        $('#loading_overlay').css('display','block');

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'get-locations-with-inv',
                inv_id: inv_id, 
            },
            success: function(response)
            {
                $('.select_moveinv[data-id="' + inv_id + '"]').html(response);
                $('.frm_moveinv[data-id="' + inv_id + '"]').css('display', 'block');
                $('#loading_overlay').css('display','none');
            }
        });

    });
    $('#display').delegate('.select_moveinv', 'change', function(e) {
        var inv_id = $(this).data('id');
        var quant = parseInt($('.quant_moveinv[data-id="' + inv_id + '"]').val());
        
        var obj = $.parseJSON($(this).find('option:selected').val());
        var from_quant = parseInt(obj.from_quant);
        var move_loid = obj.move_loid;
        var loid = $('.quant_moveinv[data-id="' + inv_id + '"]').data('loid');
        var curr = parseInt($('.quant_moveinv[data-id="' + inv_id + '"]').data('curr'));

        /*alert('inv_id: ' + inv_id);
        alert('quant: ' + quant);
        alert('move_loid: ' + move_loid);
        alert('loid: ' + loid);
        alert('from_quant: ' + from_quant);
        alert('curr: ' + curr);*/

        if(quant = 0 || quant > from_quant){
            alert('Quantity must be greater than 0 and less than location moving from. Refreshing. Try again.');
            location.reload();
            return;
        }

        $('#loading_overlay').css('display','block');

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'move-inv',
                inv_id: inv_id, 
                quant: quant,
                move_loid: move_loid,
                loid: loid,
                from_quant: from_quant,
                curr: curr
            },
            success: function(response)
            {
                alert('Moved' + response);
                display_records();
            }
        });
    });


    /* Change Quantity */
    $('#display').delegate('.btn_displaychangequants', 'click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('.frm_changequants[data-id="' + id + '"]').css('display', 'block');
        $('.hide_changequants[data-id="' + id + '"]').css('display', 'none');
    });
    $('#display').delegate('.btn_changequants', 'click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var id = $(this).data('id');
        var location = $(this).data('location');
        var quant = parseFloat($('.quant_changequants[data-id="' + id + '"]').val());
        var restock = parseFloat($('.restock_changequants[data-id="' + id + '"]').val());
        var optimal = parseFloat($('.optimal_changequants[data-id="' + id + '"]').val());
        var max_quant = parseFloat($('.max_changequants[data-id="' + id + '"]').val());
        
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'changeQuant-inv',
                id: id, 
                location: location,
                quant: quant,
                restock: restock,
                optimal: optimal,
                max_quant: max_quant
            },
            success: function(response)
            {
                alert('Changed');
                display_records();
            }
        });
    });


    /* Consume Inventory */
    $('#display').delegate('.consumeQuant', 'click', function(e) {
        var id = $(this).data('id');
        $('.consumeJob[data-id="' + id + '"]').css('display', 'block');
        $('.lbl_consumeJob[data-id="' + id + '"]').css('display', 'block');

    });
    $('#display').delegate('.consumeJob', 'change', function(e) {

        var id = $(this).data('id');
        var location = $(this).data('location');
        var quant = parseInt($('.consumeQuant[data-id="' + id + '"]').val());
        var current = parseInt($(this).data('current'));
        var jobId = $(this).val();

        if(quant > current || 0 == quant || '' == quant){
            alert('Deducting too many or none. Refresh page and try again. / Deduzindo muitos ou nenhum. Atualize a página e tente novamente.');
            return;
        }
        $('#loading_overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'use-inv',
                id: id, 
                location: location,
                jobId: jobId,
                quant: quant
            },
            success: function(response)
            {
                alert('Deducted');
                display_records();
            }
        });
    });


    /* Move Item */
    $('#display').delegate('.moveItemLocation', 'change', function(e) {

        var id = $(this).data('id');
        var locId = $(this).val();
        $('#loading_overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'move-item',
                id: id, 
                new_loc_id: locId
            },
            success: function(response)
            {
                alert('Moved');
                display_records();
            }
        });
    });

    

    /* Location Buttons */
    $('#btnShowLocationButtons').on('click', function(e) {
        e.preventDefault();
        $('#menu').css('display','block');
        $('#display').css('display','none');
        $('#menuHidden').css('display','none');
    });
    $('.btnLocation').on('click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var location_id = $(this).data('id');
        var name = $(this).data('name');
        $('#location_name').text(name);
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                location_id: location_id,
                location_name: name,
                run: 'set_location_id'
            },
            success: function(response)
            {
                display_records();
            }
        });
    });
    

    /**
     * Keep Item Location
     */
    $('#display').delegate('.btn_keepitemlocation', 'click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#loading_overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'keep-item-location',
                id: id, 
            },
            success: function(response)
            {
                alert('Kept');
                display_records();
            }
        });
    });


    /**
     * Keep Inventory Location
     */
    $('#display').delegate('.btn_keepinvlocation', 'click', function(e) {
        var loi_id = $(this).data('loi');
        $('#loading_overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'keep-inv-location',
                loi_id: loi_id
            },
            success: function(response)
            {
                alert('Kept');
                display_records();
            }
        });
    });


    /**
     * 
     */
    $('#display').delegate('.btn_unassignLocation', 'click', function(e) {
        e.preventDefault();
        var loi_id = $(this).data('loi');
        $('#loading_overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'unassign-inv-location',
                loi_id: loi_id
            },
            success: function(response)
            {
                alert('Unassigned');
                display_records();
            }
        });
    });

    /**
     * Assign Inv to Location for All
     */
    //Get Locations not assigned to
    $('#display').delegate('.btn_assign2newlocation', 'click', function(e) {
        e.preventDefault();
        var inv_id = $(this).data('id');
        $('.hide_assign2location[data-id="' + inv_id + '"]').css('display','none');
        $('#loading_overlay').css('display','block');

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'nonassigned-inv-locations',
                inv_id: inv_id
            },
            success: function(response)
            {
                //alert(response);
                $('.select_assign2location[data-id="' + inv_id + '"]').html(response);
                $('.frm_assign2location[data-id="' + inv_id + '"]').css('display','block');
                $('#loading_overlay').css('display','none');
            }
        });
    });
    $('#display').delegate('.select_assign2location', 'change', function(e) {
        var inv_id = $(this).data('id');
        var assign_id = $('.select_assign2location[data-id="' + inv_id + '"]').val();
        var quant = parseInt($('.assign_quant[data-id="' + inv_id + '"]').val());
        var restock = parseInt($('.assign_restock[data-id="' + inv_id + '"]').val());
        var optimal = parseInt($('.assign_optimal[data-id="' + inv_id + '"]').val());
        var max = parseInt($('.assign_max[data-id="' + inv_id + '"]').val());

        $('#loading_overlay').css('display','block');

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'assign-inv-location',
                inv_id: inv_id,
                assign_id: assign_id,
                quant: quant,
                restock: restock,
                optimal: optimal,
                max: max
            },
            success: function(response)
            {
                alert('Assigned');
                display_records();
            }
        });
    });


    /**
     * Move Inv to Location Temporarily for All
     */
    //Get Locations not assigned to
    $('#display').delegate('.btn_movetemplocation', 'click', function(e) {
        e.preventDefault();
        var inv_id = $(this).data('id');
        $('.hide_assign2location[data-id="' + inv_id + '"]').css('display','none');
        $('#loading_overlay').css('display','block');

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'nonassigned-inv-locations',
                inv_id: inv_id
            },
            success: function(response)
            {
                //alert(response);
                $('.select_movetemplocation[data-id="' + inv_id + '"]').html(response);
                $('.frm_movetemplocation[data-id="' + inv_id + '"]').css('display','block');
                $('#loading_overlay').css('display','none');
            }
        });
    });
    $('#display').delegate('.select_movetemplocation', 'change', function(e) {
        var inv_id = $(this).data('id');
        var assign_id = $('.select_movetemplocation[data-id="' + inv_id + '"]').val();
        var quant = parseInt($('.assign_movetemplocation[data-id="' + inv_id + '"]').val());


        $('#loading_overlay').css('display','block');

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'temp-move-inv-location',
                inv_id: inv_id,
                assign_id: assign_id,
                quant: quant,
            },
            success: function(response)
            {
                alert('Assigned Temporarily');
                display_records();
            }
        });
    });


    /**
     * 
     */
    $('#btnShowHideOF').on('click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var show_hide = $(this).data('show_hide');
        var fulfillment;
        if('Show' == show_hide){
            $(this).removeClass('btn_blue_outline');
            $(this).addClass('blue_button');
            $(this).data('show_hide', 'Hide')
            $('#spShowHideOF').text('Hide');
            $('#btn_Menu_item').css('display', 'none');
            fulfillment = 1;
        }else{
            $(this).removeClass('blue_button');
            $(this).addClass('btn_blue_outline');
            $(this).data('show_hide', 'Show')
            $('#spShowHideOF').text('Show');
            $('#btn_Menu_item').css('display', 'inline-block');
            fulfillment = 0;
        }

        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'toggle-fulfillment',
                fulfillment: fulfillment
            },
            success: function(response)
            {
                display_records();
            }
        });
    });




});


/**
 * Ajax Upload Picture 
 */
/* Replace Picture */
function replacePicture(inv_id) {

    $('#loading_overlay').css('display','block');

    var form_data = new FormData(document.getElementById('frmReplacePicture_' + inv_id));
    jQuery.each($('.replacePicture[data-id="' + inv_id + '"]')[0].files, function(i, file) {
        form_data.append(i, file);
    });

    $.ajax({
        type: "POST",
        cache:false,
        processData: false,
        contentType: false,
        data: form_data,
        url: 'upload.php',
        success: function(response)
        {
            display_records();
        }
    });
};


/**
 * 
 */
function display_records(){
    $.ajax({
        type: "POST",
        url: 'inv-controller.php',
        data: {
            run: 'display-inv'
        },
        success: function(response)
        {
            document.getElementById("display").innerHTML =response;
            $('#display').css('display','block');
            $('#menu').css('display','none');
            $('#menuHidden').css('display','block');
            $('#loading_overlay').css('display','none');
        }
    });
}


/**
 * BEGIN: Scroll to Top Button
 

var btn_top = document.getElementById("btn_top");
alert()
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function(btn_top) {
    var style_top = getComputedStyle(btn_top);
    alert(style_top.height)
    scrollFunction(style_top.height)
};

function scrollFunction(height_top) {
if (document.body.scrollTop > height_top || document.documentElement.scrollTop > height_top) {
    btn_top.style.display = "block";
} else {
    btn_top.style.display = "none";
}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
document.body.scrollTop = 0;
document.documentElement.scrollTop = 0;
}*/