$( document ).ready(function() {
    console.log("Jquery Loaded");
    $('#add-button').click(function(){
        $('#add-div').show();
    });

    $('#rad-dict').click(function(){
        $('#search_form').attr('placeholder', 'Enter A Word');
    });

    $('#rad-words').click(function(){
        $('#search_form').attr('placeholder', 'Enter A URL');
    });

    $('#rad-links').click(function(){
        $('#search_form').attr('placeholder', 'Enter A URL');
    });
});

function replace_tag_with_input(tag) {
    var t = $(tag).text();
    $(tag).replaceWith(function(){
        return $("<input type='text' value='" + t + "' name='a'>", {html: $(this).html()});
    });
}

function replace_tag_with_textbox(tag) {
    var t = $(tag).text();
    $(tag).replaceWith(function(){
        return $("<textarea name='d'>" + t + "</textarea>", {html: $(this).html()});
    });
}

function replace_tag_with_dropdown(tag) {
    $(tag).replaceWith(function(){
        return $("<select name='t'><option>n.</option><option>v.</option><option>adj.</option><option>???</option></select>", {html: $(this).html()});
    });
}

function name_tag(tag, s) {
    $(tag).attr('name', s);
}

function replace_this(i) {
    replace_tag_with_input('#word' + i);
    replace_tag_with_dropdown('#wt' + i);
    replace_tag_with_textbox('#def' + i);
    $('#add-in-edit' + i).show();
}

function delete_this(i) {
        if (confirm("Are you sure you want to delete this definition?")) {
            $('#id' + i).attr('name', 'c');
            $('#hid' + i).attr('name', 'a');
            $('#id' + i).closest("form").submit();
        }
}