@extends('layouts/master')
@section('title', 'Add a new book')
@section('content')
<script>
    function loadPreview(input) {
        if (input.files) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var html = '<img src=' + '"' + e.target.result + '"' + ' class="cover-image"/>';
                $("#cover-images").html(html);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
<div class="container">
    <p class="h2 text-center">Add a new book</p>
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="/dashboard/books" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Upload Cover Image:</label>
            {{-- <input class="browse-input" type="file" required id="cover-image" onchange="loadPreview(this);" name="images" multiple/> --}}
            <input class="browse-input" type="file" name="file" class="form-control" required id="cover-image"
                onchange="loadPreview(this);">
            <span id="cover-images"></span>
        </div>
        <div class="form-group">
            <label>Title:</label>
            <input class="form-control" type="text" name="title" required placeholder="Book title" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Author:</label>
            <input class="form-control" type="text" name="author" required placeholder="Author name" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Isbn:</label>
            <input class="form-control" type="text" name="isbn" required placeholder="Isbn" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Publisher:</label>
            <input class="form-control" type="text" name="publisher" required placeholder="Publisher" />
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <label>Review:</label>
            <textarea class="form-control" type="text" name="review" required placeholder="Review"></textarea>
            <span class="Error"></span>
        </div>
        <div class="form-group">
            <input class="btn btn-primary btn-block" type="submit" value="Add" />
        </div>
    </form>
</div>

@endsection
