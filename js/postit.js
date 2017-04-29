$( document ).ready( function() {
    refresh_page();
    draw_add_note();
});

function show_all_notes( data ) {

    $.each(data.messages ,function(i, v) {
        // if(v.messages.x == 0 && v.messages.x == 0) {
        //     var width = Math.floor( Math.random() * w );
        //     var height= Math.floor( Math.random() * h );
        // }
        var width = v.x;
        var height = v.y;

        //console.log(width + " " + height)

        $('body').append('<div id=' + v.note_id + ' class="post-it let-go" style="position: absolute; top: ' + height + '; left: ' + width + '; z-index: ' + v.note_id + '"' +
            "onmouseup='update_position(" + v.note_id + ")'>" +
            '<div class="post-head">ID: ' + v.note_id + "</div><div class='align-right'>" +
            "<span class='delete'><a href='#' onClick='delete_note(" + v.note_id + ")'>[ X ]</a></span></div><br/><hr>" +
            v.message + '</div>');
    })
}

function draw_add_note() {
    $('body').append('<div class="post-it" id="new-note" style="z-index: 10000"><b>Leave A Message</b>' +
        '<br/><hr><textarea id="new" placeholder="Type Your Message Here" /></div>');
}

function post_note(s) {
    pos = $('#new-note').position();
    var x = pos.left;
    var y = pos.top;
    refresh_page(s, x, y);
    $('#new-note').remove()
    draw_add_note();
}

function delete_note(i) {
    $('#' + i).remove();
    refresh_page(undefined, undefined, undefined, i);
    //draw_add_note();
}

function update_position(i) {
    console.log(i);
    pos = $('#' + i).position();
    var x = pos.left;
    var y = pos.top;
    refresh_page(undefined, x, y, undefined, i);
}

function clear_notes() {
    $('.post-it').remove();
}

function refresh_page(message, x, y, del, update) {
    //console.log( message );
    $.ajax({
        type: 'POST',
        data: {
            'post' : true,
            'message': message,
            'x': x,
            'y': y,
            'del': del,
            'update': update
        },
        success: function (result) {
            //console.log(result);
            var data = ( JSON.parse(result) );
            console.log(data);
            if(!del && !update) { show_all_notes(data); }
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