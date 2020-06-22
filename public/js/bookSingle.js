$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    $('.processing-text').hide();
    var ratingValue = -1;
    $('#comment_form').on('submit', function (event) {
        event.preventDefault();
        var content = $('#comment-content').val();
        var book_id = $("#book_id").val();
        $.ajax({
            url: "/dashboard/comments",
            method: "POST",
            data: {
                content,
                book_id
            },
            dataType: "JSON",
            success: function (data) {
                $('#comment_form')[0].reset();
                // add comment to comment listing section
                var html = '<div class="text-secondary">';
                html += '<p class="card-text">' + data.content + '</p>';
                html += '<p class="card-text">';
                html += '<em>' + data.created_by + '</em> - <span>' + data.created_at +
                    '</span></p>';
                html += '</div><hr/>';
                $('#comment-listing').append(html);
                var oldCommentCounter = parseInt($('#comment-counter').html());
                $('#comment-counter').html(oldCommentCounter + 1);
                $('#comment_form').hide();
            }
        })
    });
    $('.my-rating').mouseenter(function () {
        var elemId = $(this).attr('id');
        var currentId = elemId.substring(elemId.lastIndexOf('-')+1);
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
            var currentId = elemId.substring(elemId.lastIndexOf('-')+1);
            for (var i = 1; i <= currentId; i++) {
                $('#rating-' + i).removeClass(' checked');
            }
        }
        
    });
    $('.my-rating').on('click', function () {
        var elemId = $(this).attr('id');
        var currentId = elemId.substring(elemId.lastIndexOf('-')+1);
        ratingValue = currentId;
        var book_id = $("#book_id").val();
        $('.processing-text').show();
        $(".my-rating").hide();
        $.ajax({
            url: "/dashboard/ratings",
            method: "POST",
            data: {
                book_id,
                value:ratingValue
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
                // var html = '';
                // for (var i = 1; i <= currentId; i++) {
                //     html += '<span class="fa fa-star my-rating checked" id="rating-'+i+'"></span>';
                // }
                // for (var i = currentId+1; i <= 5; i++) {
                //     html += '<span class="fa fa-star my-rating" id="rating-'+i+'"></span>';
                // }
                // $('#rating-area').empty();
                // $('#rating-area').html(html);
            }
        })
    });
    
});
