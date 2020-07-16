$(document).ready(function () {
    $('.processing-text').hide();
    var ratingValue = -1;

    // add comment
    $('#comment_form').on('submit', function (event) {
        event.preventDefault();
        var content = $('#comment-content').val();
        var book_id = $("#book_id").val();
        $.ajax({
            url: "/dashboard/comments",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            data: {
                content,
                book_id
            },
            dataType: "JSON",
            success: function (data) {
                $('#comment_form')[0].reset();
                // create html for comment
                var html = '';
                // comment info
                html += '<ul class="list-inline m-0" id="ul-'+data.id+'">';
                html += '<li class="list-inline-item">';
                html += '<div class="text-secondary">';

                html += '<p class="card-text">' + data.content + '</p>';
                html += '<p class="card-text">';
                html += '<em>' + data.created_by + '</em> - <span>' + data.created_at +
                    '</span></p>';
                html += '</div>';
                html += '</li>';

                // add edit and comment button 
                html += '<li class="list-inline-item">';
                html += '<button class="btn btn-success btn-sm rounded-0 btnEditCmt" onclick="showEditCommentForm('+data.id+ ','+data.content +')" type="button" data-toggle="tooltip" ';
                html += 'id="btnEditCmt-' + data.id + '"';
                html += 'data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>';
                html += '</li>';
                html += '<li class="list-inline-item">';
                html += '<button class="btn btn-danger btn-sm rounded-0 onclick="deleteComment('+data.id+')" btnDeleteCmt" type="button" data-toggle="tooltip"';
                html += 'id="btnDeleteCmt-' + data.id + '"';
                html += 'data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>';
                html += '</li><hr/>';
                html += '</ul>';

                // add comment to comment listing section
                $('#comment-listing').append(html);
                var oldCommentCounter = parseInt($('#comment-counter').html());
                $('#comment-counter').html(oldCommentCounter + 1);
                // $('#comment_form').hide();
            }
        })
    });
    // add rating
    $('.my-rating').mouseenter(function () {
        var elemId = $(this).attr('id');
        var currentId = elemId.substring(elemId.lastIndexOf('-') + 1);
        for (var i = 1; i <= 5; i++) {
            $('#rating-' + i).removeClass(' checked');
        }
        for (var i = 1; i <= currentId; i++) {
            $('#rating-' + i).addClass(' checked');
        }
    });
    $('.my-rating').mouseleave(function () {
        if (ratingValue != -1) {
            for (var i = 1; i <= 5; i++) {
                $('#rating-' + i).removeClass(' checked');
            }
            for (var i = 1; i <= ratingValue; i++) {
                $('#rating-' + i).addClass(' checked');
            }
        } else {
            var elemId = $(this).attr('id');
            var currentId = elemId.substring(elemId.lastIndexOf('-') + 1);
            for (var i = 1; i <= currentId; i++) {
                $('#rating-' + i).removeClass(' checked');
            }
        }

    });
    $('.my-rating').on('click', function () {
        var elemId = $(this).attr('id');
        var currentId = elemId.substring(elemId.lastIndexOf('-') + 1);
        ratingValue = currentId;
        var book_id = $("#book_id").val();
        $('.processing-text').show();
        $(".my-rating").hide();
        $.ajax({
            url: "/dashboard/ratings",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            data: {
                book_id,
                value: ratingValue,
                // __token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "JSON",
            success: function (res) {
                for (var i = 1; i <= 5; i++) {
                    $('#rating-' + i).removeClass(' checked');
                }
                for (var i = 1; i <= ratingValue; i++) {
                    $('#rating-' + i).addClass(' checked');
                }
                $('.processing-text').hide();
                $(".my-rating").show();
            }
        })
    });

});

function deleteComment(id) {
    var idVal = id;
    $.ajax({
        url: "/dashboard/comments/" + idVal,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "DELETE",
        // dataType: "JSON",
        success: function (data) {
            var oldCommentCounter = parseInt($('#comment-counter').html());
            $('#comment-counter').html(oldCommentCounter - 1);
            
            // var ulId = document.querySelector('#ul-' + idVal);
            // ulId.parentNode.removeChild(ulId);
            $('#ul-'+idVal).remove();
        },
        error: function(req, err) {
            console.log("Ajax response fail: " + err);
        }
    });
}
function editComment(id) {
    var content = $("#comment-content-"+ id).val();
    $.ajax({
        url: "/dashboard/comments/" + id,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            content
        },
        method: "PUT",
        // dataType: "JSON",
        success: function (data) {
            $("#comment-detail-"+ id).text(data.content);
            $("#comment-detail-"+ id).show();
            $("#btnEditCmt-"+ id).show();
            $("#edit-form-"+ id).remove();     
        
        },
        error: function(req, err) {
            console.log("Ajax response fail: " + err);
        }
    });
}        
function showEditCommentForm(id, content) {
    // hide comment detail
    $("#comment-detail-"+ id).hide();
    $("#btnEditCmt-"+ id).hide();
    // show comment input form
    var html = '<form class="edit-form" id="edit-form-'+id+'">';
    html += '<div class="form-group">';
    html += '<textarea id="comment-content-'+id+'" name="content" class="form-control">'+content+'</textarea>'
    html +='</div>';
    html += '<div class="form-group">';
    html += '<button type="button" class="btn btn-primary" onclick="editComment('+id+')">Save</button>';
    html += '<button type="button" class="btn btn-danger" onclick="cancelEdit('+id+')">Cancel</button>';
    html += '</div>';
    html += '</form>';
    $("#comment-wrapper-"+ id).append(html);   
}
function cancelEdit(id) {
    $("#comment-detail-"+ id).show();
    $("#btnEditCmt-"+ id).show();
    $("#edit-form-"+ id).remove();   
}