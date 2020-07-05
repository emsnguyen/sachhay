@extends('layouts/master')
@section('title', 'Dashboard')
@section('content')
<div class="container mt-5">
    <h2>Book Detail</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card grid">
        <div class="card-body">
            @if(is_object($book->images))
                @foreach($book->images as $image)
                    <img id="cover-image" src="{{ asset($image->url) }}" alt="book cover" />
                @endforeach
            @else
                <h5 class="card-title">No image available yet</h5>
            @endif
            {{-- your own rating --}}
            <?php 
                $user = Auth::user();
                $isBanned = $user->banned;
                $isAdmin = $user->role === 1;
                $isBookCreator = $book->created_by === $user->name;
                $canAddRating = $isAdmin || (!$isBanned && !$isBookCreator);
            ?>
            @if($canAddRating)
                <div class="text-center" id="rating-area">
                    <span class="fa fa-star my-rating" id="rating-1"></span>
                    <span class="fa fa-star my-rating" id="rating-2"></span>
                    <span class="fa fa-star my-rating" id="rating-3"></span>
                    <span class="fa fa-star my-rating" id="rating-4"></span>
                    <span class="fa fa-star my-rating" id="rating-5"></span>
                    <p class="processing-text">Processing</p>
                </div>
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
                    <ul class="list-inline m-0">
                        <li class="list-inline-item">
                            <div class="text-secondary">
                                <p class="card-text">{{ $comment->content }}</p>
                                <p class="card-text">
                                    <em>{{ $comment->created_by }}</em> - <span>{{ $comment->created_at }}</span>
                                </p>
                                <p class="card-text" id="comment-error"></p>
                            </div>
                        </li>
                        <?php 
                            $user = Auth::user();
                            $isBanned = $user->banned;
                            $isAdmin = $user->role === 1;
                            $isCommentCreator = $comment->created_by === $user->name;
                            $canUpdateOrDeleteComment = !$isBanned && ($isAdmin || $isCommentCreator);                    
                        ?>
                        @if($canUpdateOrDeleteComment)
                            <li class="list-inline-item">
                                <button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                    data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                            </li>
                            <li class="list-inline-item">
                                <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>
                            </li>
                        @endif
                    </ul>
                    <hr />
                @endforeach
            </div>
            {{-- Add comment form --}}
            {{-- check role  --}}
            <?php 
                $user = Auth::user();
                $isBanned = $user->banned;
                $isAdmin = $user->role === 1;
                $isBookCreator = $book->created_by === $user->name;
                $canAddComment = $isAdmin || (!$isBanned && !$isBookCreator);
            ?>
            @if($canAddComment)
                <form id="comment_form">
                    <div class="form-group">
                        <textarea id="comment-content" name="content" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add comment</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- //TODO: check quyen --}}
            {{-- @can('update-book', $book) --}}

            <?php 
                $user = Auth::user();
                $isBanned = $user->banned;
                $isAdmin = $user->role === 1;
                $isBookCreator = $book->created_by === $user->name;
                $canUpdateOrDelete = !$isBanned && ($isAdmin || $isBookCreator);
            ?>
            @if($canUpdateOrDelete)
                {{-- edit form --}}
                <a href="/dashboard/books/{{ $book->id }}/edit" class="btn btn-primary">Edit</a>
                {{-- delete form --}}
                <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete book</button>
                </form>
            @endif
        </div>
    </div>

</div>
<script src="{{ asset('/js/bookSingle.js') }}"></script>
@endsection
