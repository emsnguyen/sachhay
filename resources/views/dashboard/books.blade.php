@extends('layouts/master')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <h2>Available Books</h2>
    @foreach($books as $book)
        <div class="card">
            <h5 class="card-header">{{ $book->title }}</h5>
            <div class="card-body">
                @if(is_object($book->images->first()))
                    <img src="{{ asset($book->images->first()->url) }}" alt="" class="src"/>
                @else
                    <h5 class="card-title">No image</h5>
                @endif

                <p class="card-text">{{ $book->review }}</p>
                <p class="card-text">
                    <strong>{{count($book->comments)}}</strong> comments
                </p>
                <p class="card-text">
                    @php
                        $averageRating = $book->ratings->map(function($rating){
                            return $rating->value;
                        })->avg();
                    @endphp
                    @for ($i = 0; $i < $averageRating; $i++)
                        <button type="button" class="btnrating btn btn-warning btn-lg" data-attr="1" id="rating-star-1">
                            <i class="fa fa-star" aria-hidden="true"></i>
                        </button>
                    @endfor
                    @for ($i = $averageRating; $i < 5; $i++)
                        <button type="button" class="btnrating btn btn-default btn-lg" data-attr="1" id="rating-star-1">
                            <i class="fa fa-star" aria-hidden="true"></i>
                        </button>
                    @endfor
                </p>
                <a href="/dashboard/books/{{$book->id}}" class="btn btn-primary">View details</a>
            </div>
        </div>
    @endforeach
    @if(!empty(Session::get('success')))
        <div class="alert alert-success"> {{ Session::get('success') }}</div>
    @endif
    @if(!empty(Session::get('error')))
        <div class="alert alert-danger"> {{ Session::get('error') }}</div>
    @endif
</div>

@endsection
