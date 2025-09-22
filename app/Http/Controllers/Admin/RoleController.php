<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('role-table')) {
            return redirect()->back()->with('error', __('messages.Access Denied'));
        }

        $query = Role::where('guard_name', 'admin');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('guard_name', 'LIKE', "%{$request->search}%");
            });
        }

        $data = $query->withCount('permissions')->paginate(10);
        
        return view('admin.roles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('role-add')) {
            return redirect()->back()->with('error', __('messages.Access Denied'));
        }

        $permissions = Permission::where('guard_name', 'admin')->get();
        $permissionGroups = $this->getPermissionGroups($permissions);
        
        return view('admin.roles.create', compact('permissions', 'permissionGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('role-add')) {
            return redirect()->back()->with('error', __('messages.Access Denied'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'admin'
            ]);

            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->route('admin.role.index')
                ->with('success', __('messages.Role created successfully'));
        } catch (Exception $e) {
            Log::error('Role creation failed: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()
                ->withErrors(__('messages.Something went wrong'))
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!Gate::allows('role-edit')) {
            return redirect()->back()->with('error', __('messages.Access Denied'));
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::where('guard_name', 'admin')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $permissionGroups = $this->getPermissionGroups($permissions);
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'permissionGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('role-edit')) {
            return redirect()->back()->with('error', __('messages.Access Denied'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => $request->name]);
            
            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->route('admin.role.index')
                ->with('success', __('messages.Role updated successfully'));
        } catch (Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()
                ->withErrors(__('messages.Something went wrong'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Gate::allows('role-delete')) {
            return redirect()->back()->with('error', __('messages.Access Denied'));
        }

        try {
            $role = Role::findOrFail($id);
            
            // Check if role is assigned to any users
            if ($role->users()->count() > 0) {
                return redirect()->back()
                    ->with('error', __('messages.Cannot delete role assigned to users'));
            }

            $role->delete();
            return redirect()->route('admin.role.index')
                ->with('success', __('messages.Role deleted successfully'));
        } catch (Exception $e) {
            Log::error('Role deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.Something went wrong'));
        }
    }

    /**
     * AJAX delete method
     */
    public function delete(Request $request)
    {
        if (!Gate::allows('role-delete')) {
            return response()->json(['success' => false, 'message' => __('messages.Access Denied')]);
        }

        try {
            $role = Role::findOrFail($request->id);
            
            if ($role->users()->count() > 0) {
                return response()->json(['success' => false, 'message' => __('messages.Cannot delete role assigned to users')]);
            }

            $role->delete();
            return response()->json(['success' => true, 'message' => __('messages.Role deleted successfully')]);
        } catch (Exception $e) {
            Log::error('Role deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => __('messages.Something went wrong')]);
        }
    }

    /**
     * Get permissions grouped by module
     */
    private function getPermissionGroups($permissions)
    {
        $groups = [];
        
        foreach ($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $module = $parts[0];
            
            if (!isset($groups[$module])) {
                $groups[$module] = [];
            }
            
            $groups[$module][] = $permission;
        }

        return $groups;
    }
}