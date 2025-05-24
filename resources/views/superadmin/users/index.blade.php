@extends('layouts.superadmin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <div class="container-fluid">

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1 class="text-success">User Management</h1>
                </div>


            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Verify Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach ($user->getRoleNames() as $role)
                                            <span class="badge bg-primary">{{ ucfirst($role) }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($user->is_verified === 1)
                                            <span class="badge bg-success">Verified</span>
                                            @elseif ($user->is_declined)
                                            <span class="badge bg-warning text-dark">Declined</span>
                                            @else
                                            <span class="badge bg-danger">Not Verified</span>
                                            @endif
                                        </td>


                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('users.edit', $user->id) }}">Edit</a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item">
                                                            Delete
                                                        </button>
                                                    </form>
                                                    @if (!$user->is_verified && !$user->is_declined)

                                                    <button type="button" data-toggle="modal"
                                                        data-target="#VerifyModal{{ $user->id }}"
                                                        class="dropdown-item text-success">
                                                        Verify
                                                    </button>
                                                    </form>
                                                    @endif

                                                    @if (!$user->is_declined && !$user->is_verified)
                                                    <button type="button" data-toggle="modal"
                                                        data-target="#DeclineModal{{ $user->id }}"
                                                        class="dropdown-item text-warning">
                                                        Decline
                                                    </button>
                                                    @endif


                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="VerifyModal{{ $user->id }}" tabindex="-1"
                                        aria-labelledby="VerifyModal{{ $user->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="VerifyModal{{ $user->id }}Label">Confirm
                                                        Verification</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('users-verify', $user) }}" method="POST"
                                                    class="d-inline">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-body">
                                                        Are you sure you want to verify this user?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Confirm and
                                                            Verifiy</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="modal fade" id="DeclineModal{{ $user->id }}" tabindex="-1"
                                        aria-labelledby="DeclineModal{{ $user->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="DeclineModal{{ $user->id }}Label">
                                                        Confirm Decline
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('users-declined', $user) }}" method="POST"
                                                    class="d-inline">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-body">
                                                        Are you sure you want to decline this user?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Confirm and
                                                            Decline</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                    </div>

                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
</div>


@endsection