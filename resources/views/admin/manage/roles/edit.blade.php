@extends('admin.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Edit Role</h3>
        </div>
    </div>
    <div class="card">
            <div class="card-body">
                <form action="{{ route('rolesUpdate', $role->id) }}" method="POST">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="display_name">Name (Display Name)</label>
                                <input type="text" class="form-control" name="display_name" id="display_name" value="{{ $role->display_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Slug</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $role->name }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" name="description" id="description" value="{{ $role->description }}">
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin: auto;">
                        @foreach($permissions as $permission)
                            <div class="col-md-3">
                                <label class="form-check-label">
                                    <!-- Important to put v-model, so that vue can catch array input -->
                                    <input type="checkbox" class="form-check-input" name="permissionsSelected" v-model="permissionsSelected" value="{{$permission->id}}">
                                    {{$permission->display_name}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <!-- Catch the array selected checkbox -->
                    <input type="hidden" name="permissions" :value="permissionsSelected">
                    <br>
                    <button class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                // pluck to enable permissions selected
                // single bracket for vuejs
                permissionsSelected: {!! $role->permissions->pluck('id') !!}
            }
        });
    </script>
@endsection