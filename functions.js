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

/* Consume Inventory */
$('.consumeInventory').on('click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var name = $(this).data('location');
    alert(id);
    alert(name);
});

/* Location Buttons */
$('.btnLocation').on('click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var name = $(this).data('name');
    $.ajax({
        type: "POST",
        url: 'display-inventory-by-location.php',
        data: {id: id},
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