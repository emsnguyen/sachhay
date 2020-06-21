@extends('layouts/master')
@section('title', 'Dashboard')
@section('content')
<div class="container mt-5">
    <h2>Book Detail</h2>
    <div class="card grid">
        <div class="card-body">
            @if(is_object($book->images))
                @foreach($book->images as $image)
                    <img id="cover-image" src="{{ $image->url }}" alt="book cover" />
                @endforeach
            @else
                <h5 class="card-title">No image available yet</h5>
            @endif

        </div>
        <div class="card-body">
            <input type="hidden" class="text" id="book_id" value="{{ $book->id }}" />
            {{-- title --}}
            <h5 class="card-header">{{ $book->title }}</h5>
            {{-- rating --}}
            <div class="card">
                @php
                    $averageRating = $book->ratings->map(function($rating){
                    return $rating->value;
                    })->avg();
                @endphp
                <div class="card-body">
                    @for($i = 0; $i < $averageRating; $i++)
                        <span class="fa fa-star checked"></span>
                    @endfor
                    @for($i = $averageRating; $i < 5; $i++)
                        <span class="fa fa-star"></span>
                    @endfor
                    (<span id="rating-counter">{{ count($book->ratings) }}</span> votes)
                </div>
            </div>
            {{-- review --}}
            <p class="card-text">{{ $book->review }}</p>
        </div>
    </div>

    <div class="card">
        <h6 class="card-header">
            <p><span id="comment-counter">{{ count($book->comments) }}</span> Comments</p>
        </h6>
        <div class="card-body">
            <div id="comment-listing">
                @foreach($book->comments as $comment)
                    <div class="text-secondary">
                        <p class="card-text">{{ $comment->content }}</p>
                        <p class="card-text">
                            <em>{{ $comment->created_by }}</em> - <span>{{ $comment->created_at }}</span>
                        </p>
                        <p class="card-text" id="comment-error"></p>
                    </div>
                    <hr />
                @endforeach
            </div>
            {{-- Add comment form --}}
            <form id="comment_form">
                <div class="form-group">
                    <textarea id="comment-content" name="content" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add comment</button>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- //TODO: check quyen --}}
            <a href="/dashboard/books/{{ $book->id }}" class="btn btn-primary">Edit</a>
            {{-- //TODO: check quyen --}}
            <a href="/dashboard/books/{{ $book->id }}" class="btn btn-primary">Delete</a>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
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
                    console.log("data: " + data);
                    console.log("data.content: " + data.content);
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
    });

</script>
@endsection
