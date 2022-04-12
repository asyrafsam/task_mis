<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Role;
use App\User;
use App\Permissions;
use App\Department;

class AdminController extends Controller
{
    public function __construct()
    {
        // To check whether role administrator or not
        $this->middleware('auth');
        $this->middleware('role:administrator');
    }

    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function UsersIndex()
    {
        $users = User::orderBy('id', 'desc')->paginate(5);
        $count = 1;

        // $data = [
        //     'users' => $users,
        //     'count' => $count
        // ];

        return view('admin.manage.users.index', compact('users', 'count'));
    }

    public function UsersCreate()
    {
        $roles = Role::all();
        $departments = Department::all();

        // $data = [
        //     'roles' => $roles
        // ];

        return view('admin.manage.users.create', compact('roles', 'departments'));
    }

    public function UsersStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users'
        ]);

        if(!empty($request->password)) {
            $password = trim($request->password);
        } else {
            $password = 'password';
        }

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = ($request->department_id == 0) ? null : $request->department_id;
        $user->password = Hash::make($password);
        $user->save();

        $user->syncRoles(explode(',', $request->roles));
        
        return redirect()->route('usersIndex');
    }

    public function usersShow($id)
    {
        $user = User::findOrFail($id);

        // $data = [
        //     'user' => $user
        // ];
        
        return view('admin.manage.users.show', compact('user'));
    }

    public function UsersEdit($id)
    {
        $user = User::findOrFail($id);
        $role = Role::all();
        $departments = Department::all();
        
        // $data = [
        //     'user' => $user,
        //     'role' => $role
        // ];

        return view('admin.manage.users.edit', compact('user', 'role', 'departments'));
    }

    public function UsersUpdate(Request $request, $id)
    {
        // $this->validate($request, [
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users,email,' . $id,
        // ]);

        $user = User::findOrFail($id);

        if($request->password_options == 'manual') {
            $user->password = Hash::make($request->password);
        } else if($request->password_options == 'auto'){
            $password = 'password';
            $user->password = Hash::make($password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = ($request->department_id == 0) ? null : $request->department_id;
        $user->save();

        $user->syncRoles(explode(',', $request->roles));
        
        return redirect()->route('usersShow', $id);
    }

    // Permissions Method | Start
    public function permissionsIndex()
    {
        // Paginate to create links for table pagination
        $permissions = Permission::orderBy('id', 'desc')->paginate(10);
        $count = 1;

        return view('admin.manage.permission.index', compact('permissions', 'count'));
    }

    public function permissionsShow($id)
    {
        $permission = Permission::findOrFail($id);

        return view('admin.manage.permission.show', compact('permission'));
    }

    public function permissionsCreate()
    {
        return view('admin.manage.permission.create');
    }

    public function permissionsStore(Request $request)
    {
        if ($request->permission_type == 'basic') {
            $this->validate($request, [
                'display_name' => 'required|max:255',
                'name' => 'required|max:255|alphadash|unique:permissions,name',
                'description' => 'sometimes|max:255'
            ]);

            // Create new object since not update method
            $permission = new Permission;
            $permission->display_name   = $request->display_name;
            $permission->name           = $request->name;
            $permission->description    = $request->description;
            $permission->save();

            return redirect()->route('permissionsIndex');

        } elseif($request->permission_type == 'crud') {
            $this->validate($request, [
                'resource' => 'required|min:3|max:100|alpha'
            ]);

            $crud = explode(',', $request->crud_selected);

            if (count($crud) > 0) {
                foreach ($crud as $x) {
                    $slug           = strtolower($x) . '-' . strtolower($request->resource);
                    $display_name   = ucwords($x . ' ' . $request->resource);
                    $description    = "Allow a user to " . strtoupper($x) . ' a ' . ucwords($request->resource);

                    // Create new object since not update method
                    $permission = new Permission;
                    $permission->display_name   = $display_name;
                    $permission->name           = $slug;
                    $permission->description    = $description;
                    $permission->save();
                }
            }

            return redirect()->route('permissionsIndex');
        }else {
            return redirect()->route('permissionsCreate')->withInput();
        }
    }

    public function permissionsEdit($id)
    {
        $permissions = Permission::findOrFail($id);
        return view('admin.manage.permission.edit', compact('permissions'));
    }

    public function permissionsUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->display_name   = $request->display_name;
        $permission->description    = $request->description;
        $permission->save();

        return redirect()->route('permissionsShow', $id);
    }

    // Permissions Method | End

    // Roles Methods | Start

    public function rolesIndex()
    {
        $roles = Role::orderBy('id', 'desc')->paginate(10);;

        return view('admin.manage.roles.index', compact('roles'));
    }

    public function rolesCreate()
    {
        $permissions = Permission::all();

        return view('admin.manage.roles.create', compact('permissions'));
    }

    public function rolesStore(Request $request)
    {
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'name' => 'required|max:255|alphadash|unique:roles,name',
            'description' => 'sometimes|max:255'
        ]);

        // Create new object since not update method
        $role = new Role;
        $role->display_name   = $request->display_name;
        $role->name           = $request->name;
        $role->description    = $request->description;
        $role->save();

        if ($request->permissions)
        {
            $role->syncPermissions(explode(',', $request->permissions));
        }

        return redirect()->route('rolesIndex');
    }

    public function rolesShow($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.manage.roles.show', compact('role'));
    }

    public function rolesEdit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.manage.roles.edit', compact('role', 'permissions'));
    }

    public function rolesUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);

        // Create new object since not update method
        $role = Role::findOrFail($id);
        $role->display_name   = $request->display_name;
        $role->description    = $request->description;
        $role->save();

        if ($request->permissions)
        {
            $role->syncPermissions(explode(',', $request->permissions));
        }

        return redirect()->route('rolesShow', $id);
    }
    // Roles Method | End

    // Departments Method | Start

    public function departmentsIndex()
    {
        $departments = Department::orderBy('department', 'asc')->paginate(10);

        return view('admin.manage.departments.index', compact('departments'));
    }

    public function departmentsCreate()
    {
        return view('admin.manage.departments.create');
    }

    public function departmentsStore(Request $request)
    {
        $this->validate($request, [
            'department' => 'required|max:255',
            'organized_by' => 'required|max:255'
        ]);

        $department = new Department;

        $department->department = $request->department;
        $department->organized_by = $request->organized_by;
        $department->save();

        return redirect()->route('departmentsIndex');
    }

    public function departmentsShow($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.manage.departments.show', compact('department'));
    }

    public function departmentsEdit($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.manage.departments.edit', compact('department'));
    }

    public function departmentsUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'department' => 'required|max:255',
            'organized_by' => 'required|max:255'
        ]);

        $department = Department::findOrFail($id);

        $department->department = $request->department;
        $department->organized_by = $request->organized_by;
        $department->save();

        return redirect()->route('departmentsIndex');
    }

    // Departments Method || End
}
