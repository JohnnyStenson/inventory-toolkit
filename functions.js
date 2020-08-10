$(document).ready(function(){

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

    });


    /* Change Description */
    $('#display').delegate('.changeDescription', 'click', function(e) {
        var id = $(this).data('id');
        $('.btn_changeDescription[data-id="' + id + '"]').css('display', 'block');
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


    /* Change Quantity */
    $('#display').delegate('.changeQuant', 'click', function(e) {
        var id = $(this).data('id');
        $('.btn_changeQuant[data-id="' + id + '"]').css('display', 'block');
    });
    $('#display').delegate('.btn_changeQuant', 'click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var id = $(this).data('id');
        var location = $(this).data('location');
        var quant = parseInt($('.changeQuant[data-id="' + id + '"]').val());
        
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                run: 'changeQuant-inv',
                id: id, 
                location: location,
                quant: quant
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
    $('.btnLocation').on('click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var location_id = $(this).data('id');
        var name = $(this).data('name');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                location_id: location_id,
                run: 'set_location_id'
            },
            success: function(response)
            {
                document.getElementById("locationName").innerHTML =name;
                display_records();

            }
        });
    });


    /* Display Location Buttons */
    $('#btnShowLocationButtons').on('click', function(e) {
        e.preventDefault();
        $('#menu').css('display','block');
        $('#display').css('display','none');
        $('#locationName').css('display','none');
    });

    
});

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