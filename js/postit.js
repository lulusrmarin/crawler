$( document ).ready( function() {
    refresh_page();
    draw_add_note();
});

function create_notes( data ) {

    $.each(data ,function(i, v) {
        // if(v.messages.x == 0 && v.messages.x == 0) {
        //     var width = Math.floor( Math.random() * w );
        //     var height= Math.floor( Math.random() * h );
        // }
        var width = v.messages.x;
        var height = v.messages.y;

        //console.log(width + " " + height)

        $('body').append('<div id= ' + v.messages.note_id + ' class="post-it" style="position: absolute; top: ' + height + '; left: ' + width + ';">' +
            '<div class="post-head">ID: ' + v.messages.note_id + "</div><div class='align-right'>" +
            "<span class='delete'><a href='#' onClick='delete_note(" + v.messages.note_id + ")'>[ X ]</a></span></div><br/><hr>" +
            v.messages.message + '</div>');
    })
}

function draw_add_note() {
    $('body').append('<div class="post-it" id="new-note"><b>Leave A Message</b>' +
        '<br/><hr><textarea id="new" placeholder="Type Your Message Here" /></div>');
}

function post_note(s) {
    pos = $('#new-note').position();
    var x = pos.left;
    var y = pos.top;
    $('.post-it').remove();
    refresh_page(s, x, y);
    draw_add_note();
}

function delete_note(i) {
    $('.post-it').remove();
    refresh_page(undefined, undefined, undefined, i);
    draw_add_note();
}

function refresh_page(message, x, y, del) {
    //console.log( del );
    $.ajax({
        type: 'POST',
        data: {
            'post' : true,
            'message': message,
            'x': x,
            'y': y,
            'del': del
        },
        success: function (result) {
            console.log(result);
            var data = ( JSON.parse(result) );
            console.log(data);
            create_notes(data);
        },
        error: function (a) {
            //console.log( JSON.stringify(a) );
        }
    }).done( function() {
        $('.post-it').draggable({ stack: ".post-it" });

        $('#new-note').keypress(function (e) {
            if (e.which == 13) {
                post_note( $('#new').val() );
                return (false);
            }
        });

    });
}