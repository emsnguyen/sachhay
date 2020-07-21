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
    <table class="table table-hover" id="user-table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Banned</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < sizeof($users); $i++)
                <tr id="row-{{ $users[$i]->id }}">
                    <td>{{ $users[$i]->name }}</td>
                    <td>{{ $users[$i]->email }}</td>
                    <td>
                        @if ($users[$i]->role == 1)
                            Admin
                        @else
                            User
                        @endif
                    </td>
                    <td>
                        @if ($users[$i]->banned)
                            True
                        @else
                            False 
                        @endif
                    </td>
                    <td>
                        <li class="list-inline-item">
                            <button class="btn btn-success btn-sm rounded-0" type="button"
                                data-id="{{ $users[$i]->id }}" 
                                data-name="{{ $users[$i]->name }}" 
                                data-email="{{ $users[$i]->email }}"
                                data-role="{{ $users[$i]->role }}"
                                data-banned="{{ $users[$i]->banned }}" data-toggle="modal"
                                data-target="#editUserModal" title="Edit"><i class="fa fa-edit"></i></button>
                        </li>
                    </td>
                    <td>
                        <li class="list-inline-item">
                            <button class="btn btn-danger btn-sm rounded-0 btnDeleteCmt" onclick="deleteUser({{ $users[$i]->id }})"
                                type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i
                                    class="fa fa-trash"></i></button>
                        </li>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>
<div class="modal fade text-center py-5 subscribeModal-lg" id="editUserModal" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="pt-5 mb-0 text-secondary">Edit User Information</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form id="edit-form">
                            <div class="form-group">
                                <label for="name" class="text-info float-left">Name:</label><br>
                                <input class="form-control" type="text" name="name" required />
                            </div>
                            <div class="form-group">
                                <label for="email" class="text-info float-left">Email:</label><br>
                                <input class="form-control" type="text" name="email" required />
                            </div>
                            <div class="form-group">
                                <label for="role" class="text-info float-left">Role</label>
                                <select class="custom-select" name="role">
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="banned" class="text-info form-check-label float-left">Banned</label>
                                <input class="form-check-input" type="checkbox" name="banned" required />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" data-target="#edit-modal" 
                class="btn btn-primary" id="modal-btn-edit">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/users.js') }}"></script>
@endsection
