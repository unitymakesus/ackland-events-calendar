
jQuery( function($){

    // Cleanup Form Before Submit
    $(".security-safe .wrap").on( "click", "input[type=submit]", function(e) {

        $("select option[value='-1']:selected").parent("select").attr('disabled', 'disabled');

    });


    // Import File Click
    $(".security-safe #export-import").on( "click", ".file-select", function(e) {

        $('.security-safe #export-import #import-file').trigger('click');

    });

    // Import File Change
    $(".security-safe #export-import").on( "change", "#import-file", function(e) {

        var filename = e.target.files[0].name;
        $(this).siblings('.file-selected').html(filename);

    });

});
