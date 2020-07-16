@extends('layouts/master')
@section('title', 'User Management')

@section('content')
<div class="container mt-5">
    @if(!empty(Session::get('success')))
        <div class="alert alert-success"> {{ Session::get('success') }}</div>
    @endif
    @if(!empty(Session::get('error')))
        <div class="alert alert-danger"> {{ Session::get('error') }}</div>
    @endif
    <h2>Current Users</h2>
    <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Banned</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < sizeof($users); $i++)
            <tr>
                <th scope="row">{{$i + 1}}</th>
                <td>{{$users[$i]->name}}</td>
                <td>{{$users[$i]->email}}</td>
                <td>{{$users[$i]->role}}</td>
                <td>{{$users[$i]->banned}}</td>
                <td>
                    <li class="list-inline-item">
                        <button class="btn btn-success btn-sm rounded-0" onclick="" type="button" data-toggle="tooltip" 
                            {{-- id="btnEditCmt-{{$comment->id}}" --}}
                            data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                    </li>
                </td>
                <td>
                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-sm rounded-0 btnDeleteCmt" onclick="" type="button" data-toggle="tooltip"
                            {{-- id="btnDeleteCmt-{{$comment->id}}" --}}
                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>
                    </li>
                </td>
            </tr>
        @endfor
    </tbody>
    </table>
</div>

@endsection
