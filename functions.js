/*$('.tag').on('click', function(e) {
    e.preventDefault();
    var tagId = $(this).data('id');
    $.ajax({
        type: "POST",
        url: 'display-tagged-inventory.php',
        data: {id: tagId},
        success: function(response)
        {
            document.getElementById("display").innerHTML =response;
       }
   });
    alert(tagId);
});*/
/* Admin Drawer */
$('#display').delegate('.openAdminDrawer', 'click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('.admin_drawer[data-id="' + id + '"]').css('display', 'block');

});


/* Change Quantity */
$('#display').delegate('.changeQuant', 'click', function(e) {
    var id = $(this).data('id');
    $('.btn_changeQuant[data-id="' + id + '"]').css('display', 'block');
});
$('#display').delegate('.btn_changeQuant', 'click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var location = $(this).data('location');
    var quant = $('.changeQuant[data-id="' + id + '"]').val();
    
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
                    id: id,
                    run: 'display-inv'
                },
                success: function(response)
                {
                    document.getElementById("display").innerHTML =response;
                    $('#display').css('display','block');
                    $('#menu').css('display','none');
                    $('#menuHidden').css('display','block');
                    document.getElementById("locationName").innerHTML =name;
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
    var quant = $('.consumeQuant[data-id="' + id + '"]').val();
    var current = $(this).data('current');
    var jobId = $(this).val();

    if(quant > current || 0 == quant || '' == quant){
        alert('Deducting too many or none. Refresh page and try again. / Deduzindo muitos ou nenhum. Atualize a página e tente novamente.');
        return;
    }
    
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
                    id: id,
                    run: 'display-inv'
                },
                success: function(response)
                {
                    document.getElementById("display").innerHTML =response;
                    $('#display').css('display','block');
                    $('#menu').css('display','none');
                    $('#menuHidden').css('display','block');
                    document.getElementById("locationName").innerHTML =name;
               }
           });
       }
   });
    
});

/* Location Buttons */
$('.btnLocation').on('click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var name = $(this).data('name');
    $.ajax({
        type: "POST",
        url: 'inv-controller.php',
        data: {
            id: id,
            run: 'display-inv'
        },
        success: function(response)
        {
            document.getElementById("display").innerHTML =response;
            //document.getElementById("display").show();
            $('#display').css('display','block');
            //document.getElementById("menu").hide();
            $('#menu').css('display','none');
            $('#menuHidden').css('display','block');
            document.getElementById("locationName").innerHTML =name;
       }
   });
});

/* Display Location Buttons */
$('#btnShowLocationButtons').on('click', function(e) {
    e.preventDefault();
    $('#menu').css('display','block');
    $('#display').css('display','none');
});