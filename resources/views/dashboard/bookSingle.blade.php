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
            <?php
                $rated = 0;
            ?>
            @if($canAddRating)
               @foreach ($book->ratings as $rating)
                   @if ($rating->created_by == $user->name)
                        <?php
                            $rated = $rating->value;
                        ?>
                        <div class="text-center" id="rating-area">
                            @for($i = 1; $i <= $rating->value; $i++)
                                <span class="fa fa-star my-rating checked" id="rating-{{$i}}"></span>    
                            @endfor
                            @for($i = $rating->value+1; $i <= 5; $i++)
                                <span class="fa fa-star my-rating" id="rating-{{$i}}"></span>    
                            @endfor
                            <p class="processing-text">Processing</p>
                        </div>
                        @break
                   @endif
               @endforeach
               @if($rated == 0) 
                <div class="text-center" id="rating-area">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="fa fa-star my-rating" id="rating-{{$i}}"></span>    
                    @endfor
                    <p class="processing-text">Processing</p>
                </div>
                @endif
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
                    <ul class="list-inline m-0" id="ul-{{$comment->id}}">
                        <li class="list-inline-item">
                            <div class="text-secondary" id="comment-wrapper-{{$comment->id}}">
                                <p class="card-text" id="comment-detail-{{$comment->id}}">{{ $comment->content }}</p>
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
                                <button class="btn btn-success btn-sm rounded-0 btnEditCmt" onclick="showEditCommentForm({{$comment->id}}, '{{$comment->content}}')" type="button" data-toggle="tooltip" 
                            {{-- onclick="editComment({{$comment->id}});"  --}}
                            id="btnEditCmt-{{$comment->id}}"
                                    data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                            </li>
                            <li class="list-inline-item">
                                <button class="btn btn-danger btn-sm rounded-0 btnDeleteCmt" onclick="deleteComment({{$comment->id}})" type="button" data-toggle="tooltip"
                                    {{-- onclick="deleteComment({{$comment->id}});" --}}
                                    id="btnDeleteCmt-{{$comment->id}}"
                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>
                            </li>
                        @endif
                        <hr />
                    </ul>
                    
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
