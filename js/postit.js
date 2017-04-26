$( document ).ready( function() {
    refresh_page();
});

function create_notes( data ) {
    var w = window.innerWidth;
    var h = window.innerHeight;

    $.each(data ,function(i, v) {
        var width = Math.floor( Math.random() * w );
        var height= Math.floor( Math.random() * h );

        $('body').append('<div class="post-it" style="position: absolute; top: ' + height + '; left: ' + width + ';">' +
            'Message ID: ' + v.messages.note_id + "<br/><hr>" +
            v.messages.message + '</div>');
    })

    var width = Math.floor( Math.random() * w );
    var height= Math.floor( Math.random() * h );

    $('body').append('<div class="post-it" id="new-note" style="position: absolute; top: ' + height + '; left: ' + width + ';"><b>Leave A Message</b>' +
        '<br/><hr><textarea id="new" placeholder="Type Your Message Here" /></div>')
}

function post_note(s) {
    alert(s);
    $('.post-it').remove();
    refresh_page(s);
}

function refresh_page(message) {
    $.ajax({
        type: 'POST',
        data: {
            'test': '1',
            'message': message
        },
        success: function (result) {
            var data = ( JSON.parse(result) );
            create_notes(data);
        },
        error: function (a) {
            console.log( JSON.stringify(a) );
        }
    }).done( function() {
        $('.post-it').draggable();

        $('#new-note').keypress(function (e) {
            if (e.which == 13) {
                post_note( $('#new').val() );
                return (false);
            }
        });

    });
}