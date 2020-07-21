@extends('layouts/master')
@section('title', 'Book Edit')
@section('content')
<script>
    function loadPreview(input) {
        if (input.files) {
            var reader = new FileReader();
            reader.onload = function (e) {
                // var html = '<img src=' + '"' + e.target.result + '"' + ' id="cover-image"/>';
                $("#cover-image").attr('src', e.target.result);
            }
            $("input[name=isImageUpdated]").val("1");
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
<div class="container">
    <p class="h2 text-center">Edit your book</p>
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            {{-- <strong>Whoops!</strong> There were some problems with your input. --}}
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form 
    action="{{ route('books.update',$book->id) }}"
    {{-- action="/dashboard/books/{{$book->id}}"  --}}
    method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <div><label>Update Cover Image:</label></div>
            <input type="hidden" value="0" name="isImageUpdated"/>
            @if(is_object($book->images))
                @foreach($book->images as $image)
                    <img id="cover-image" src="{{ asset($image->url) }}" alt="book cover" />
                    {{-- src="{{ asset($image->url) }}" alt="" --}}
                @endforeach
            @else
                <h5 class="card-title">No image available yet</h5>
            @endif
            <input class="browse-input" type="file" name="file" class="form-control" id="cover-image"
                onchange="loadPreview(this);">
        </div>
        <div class="form-group">
            <label>Title:</label>
        <input class="form-control" type="text" name="title" required value={{$book->title}} placeholder="Book title" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Author:</label>
            <input class="form-control" type="text" name="author" value={{$book->author}} required placeholder="Author name" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Isbn:</label>
            <input class="form-control" type="text" name="isbn" value={{$book->isbn}} required placeholder="Isbn" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Publisher:</label>
            <input class="form-control" type="text" name="publisher"  value={{$book->publisher}} required placeholder="Publisher" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Review:</label>
            <textarea class="form-control" type="text" name="review" required placeholder="Review">{{$book->review}}</textarea>
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <input class="btn btn-primary btn-block" type="submit" value="Update" />
        </div>
    </form>
</div>

@endsection
