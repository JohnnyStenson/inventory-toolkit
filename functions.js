$(document).ready(function(){
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

                $.ajax({
                    type: "POST",
                    url: 'inv-controller.php',
                    data: {
                        location_id: location,
                        run: 'display-inv'
                    },
                    success: function(response)
                    {
                        document.getElementById("display").innerHTML =response;
                        $('#display').css('display','block');
                        $('#menu').css('display','none');
                        $('#menuHidden').css('display','block');
                        document.getElementById("locationName").innerHTML =name;
                        $('#loading_overlay').css('display','none');
                }
            });
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

                $.ajax({
                    type: "POST",
                    url: 'inv-controller.php',
                    data: {
                        location_id: location,
                        run: 'display-inv'
                    },
                    success: function(response)
                    {
                        document.getElementById("display").innerHTML =response;
                        $('#display').css('display','block');
                        $('#menu').css('display','none');
                        $('#menuHidden').css('display','block');
                        document.getElementById("locationName").innerHTML =name;
                        $('#loading_overlay').css('display','none');
                }
            });
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

        //alert('quant: ' + quant + ' \ncurrent: ' + current);

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

                $.ajax({
                    type: "POST",
                    url: 'inv-controller.php',
                    data: {
                        location_id: location,
                        run: 'display-inv'
                    },
                    success: function(response)
                    {
                        document.getElementById("display").innerHTML =response;
                        $('#display').css('display','block');
                        $('#menu').css('display','none');
                        $('#menuHidden').css('display','block');
                        document.getElementById("locationName").innerHTML =name;
                        $('#loading_overlay').css('display','none');
                }
            });
        }
    });
        
    });

    /* Location Buttons */
    $('.btnLocation').on('click', function(e) {
        e.preventDefault();
        $('#loading_overlay').css('display','block');
        var id = $(this).data('id');
        var name = $(this).data('name');
        $.ajax({
            type: "POST",
            url: 'inv-controller.php',
            data: {
                location_id: id,
                run: 'display-inv'
            },
            success: function(response)
            {
                document.getElementById("display").innerHTML =response;
                $('#display').css('display','block');
                $('#menu').css('display','none');
                $('#menuHidden').css('display','block');
                document.getElementById("locationName").innerHTML =name;
                $('#loading_overlay').css('display','none');
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