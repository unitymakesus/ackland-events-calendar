
jQuery( function($){

    // Cleanup Form Before Submit
    $(".security-safe .wrap").on( "click", "input[type=submit]", function(event) {

        $("select option[value='-1']:selected").parent("select").attr('disabled', 'disabled');

    });

});
