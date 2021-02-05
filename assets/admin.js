$(document).ready(function () {

    // set correct encoding for post form
    $('form#post').attr('enctype', 'multipart/form-data');

    // set correct status
    var status = $('#status').attr('current');
    $('#status').val(status);

    // id counter for images
    var id_counter = 2;

    // add image input
    $('body').on('click', 'span.sbwcit-add-img', function (e) {
        e.preventDefault();
        var content = '<div class="sbwcit-add-gall-img col-20">';
        content += '<!-- add/remove image -->';
        content += '<div class="sbwcit-add-rem-img">';
        content += '<span class="sbwcit-add-img" title="Add">+</span>';
        content += '<span class="sbwcit-rem-img rem-input" title="Remove">-</span>';
        content += '</div>';
        content += '<label for="sbwcit_gall_imgs">Select gallery image:</label>';
        content += '<input type="file" name="sbwcit_gall_imgs[]" id="sbwcit_gall_imgs_' + id_counter + '" class="sbwcit_gall_imgs">';
        content += '</div>';

        id_counter++;

        $('div#sbwcit-gallery-cont').append(content);
    });

    // remove image input
    $('body').on('click', 'span.sbwcit-rem-img.rem-input', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    // remove image
    $('span.sbwcit-rem-img').each(function () {
        $(this).click(function (e) {

        });
    });

    // save post data
    $('input#publish').click(function (e) {
        e.preventDefault();

        // setup data
        var form_data = new FormData();

        // sort out sending of file/img data
        $('.sbwcit_gall_imgs').each(function (index, element) {
            form_data.append("sbwcit_gall_img_" + index, $(this).prop("files")[0]);
        });

        // other pertinent data to be sent
        form_data.append('issue_date', $('#issue_date').val());
        form_data.append('ticket', $('#ticket').val());
        form_data.append('order_no', $('#order_no').val());
        form_data.append('rep_order_no', $('#rep_order_no').val());
        form_data.append('reason', $('#reason').val());
        form_data.append('ref_amt', $('#ref_amt').val());
        form_data.append('status', $('#status').val());
        form_data.append('post_author', $('#post_author').val());
        form_data.append('post_type', $('#post_type').val());
        form_data.append('post_ID', $('#post_ID').val());
        form_data.append('post_title', $('#title').val());

        // append wp_ajax action
        form_data.append('action', 'sbwcit_save_issue_data');

        // AJAX request
        $.ajax({
            url: ajaxurl,
            data: form_data,
            contentType: false,
            processData: false,
            cache: false,
            type: 'POST',
            success: function (response) {
                if (response == 'success') {
                    location.reload();
                }
            }
        });

    });

});