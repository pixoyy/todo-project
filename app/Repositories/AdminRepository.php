<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    public function getData($request, $n = 10)
    {
        $search = $request->query('query');
        $status = $request->query('status');
        $roleId = $request->query('role_id');

        return Admin::with('role')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone_number', 'LIKE', "%$search%");
                });
            })
            ->when(!empty($status) || $status === 0 || $status === '0', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($roleId), function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            })
            ->orderByDesc('status')
            ->orderByDesc('id')
            ->paginate($n);
    }

    public function getById($id)
    {
        return Admin::with('role')->find($id);
    }

    public function getRoleOptions()
    {
        return Role::where('status', 1)->orderBy('name')->get(['id', 'name']);
    }

    public function create($request)
    {
        Admin::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => isset($request->status) ? $request->status : 0,
            'phone_number' => $request->phone_number,
        ]);
    }

    public function update($id, $request)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return [
                'status' => false,
                'message' => 'User tidak ditemukan!',
            ];
        }

        $payload = [
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'status' => isset($request->status) ? $request->status : 0,
            'phone_number' => $request->phone_number,
        ];

        if (!empty($request->password)) {
            $payload['password'] = Hash::make($request->password);
        }

        $admin->update($payload);

        return [
            'status' => true,
            'message' => 'User berhasil diperbarui!',
        ];
    }

    public function delete($id)
    {
        $admin = Admin::with('role')->find($id);
        if (!$admin) {
            return [
                'status' => false,
                'message' => 'User tidak ditemukan!',
            ];
        }

        if ((int) Auth::id() === (int) $admin->id) {
            return [
                'status' => false,
                'message' => 'Akun yang sedang digunakan tidak dapat dihapus.',
            ];
        }

        if (optional($admin->role)->name === 'Master') {
            return [
                'status' => false,
                'message' => 'User dengan role Master tidak dapat dihapus.',
            ];
        }

        $hasProjects = Project::where('admin_id', $admin->id)->exists();
        $hasAssignedTasks = Task::where('assigned_admin_id', $admin->id)->exists();
        $hasCreatedTasks = Task::where('created_by_admin_id', $admin->id)->exists();

        if ($hasProjects || $hasAssignedTasks || $hasCreatedTasks) {
            return [
                'status' => false,
                'message' => 'User tidak dapat dihapus karena masih dipakai pada project/task.',
            ];
        }

        $admin->delete();

        return [
            'status' => true,
            'message' => 'User berhasil dihapus!',
        ];
    }
}
