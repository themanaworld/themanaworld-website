/**
 * Created by Telshin on 10/22/13.
 */
$( '.spoilers-button' ).toggle( function() {
    $( this ).parents( '.spoilers' ).find( '.spoilers-body' ).show();
    $( this ).children( '.spoilers-show' ).hide();
    $( this ).children( '.spoilers-hide' ).show();
}, function() {
    $( this ).parents( '.spoilers' ).find( '.spoilers-body' ).hide();
    $( this ).children( '.spoilers-show' ).show();
    $( this ).children( '.spoilers-hide' ).hide();
});
