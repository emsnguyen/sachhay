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
            {{-- your own rating --}}
            <div class="text-center" id="rating-area">
                <span class="fa fa-star my-rating" id="rating-1"></span>
                <span class="fa fa-star my-rating" id="rating-2"></span>
                <span class="fa fa-star my-rating" id="rating-3"></span>
                <span class="fa fa-star my-rating" id="rating-4"></span>
                <span class="fa fa-star my-rating" id="rating-5"></span>
                <p class="processing-text">Processing</p>
            </div>
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
<script src="{{asset('/js/bookSingle.js')}}"></script>
@endsection
