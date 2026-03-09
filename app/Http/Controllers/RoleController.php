<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class RoleController extends Controller
{
    private $repo;
    public function __construct()
    {
        $this->repo = new RoleRepository;
    }

    public function index()
    {
        return view('role.index');
    }

    public function data()
    {
        $roles = $this->repo->getData();
        return view('role.data', ['roles' => $roles]);
    }

    public function add()
    {
        return view('role.add', [
            'breadcrumb' => [
                ['name' => 'Tambah'],
            ],
        ]);
    }

    public function create(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->repo->create($request);
            DB::commit();
            return redirect()->route('roles')->with('message', 'Data peran berhasil ditambahkan!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('roles_add')->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function edit($id)
    {
        $role = $this->repo->getById($id);
        if (!isset($role)) {
            return redirect()->route('roles');
        }

        return view('role.edit', [
            'breadcrumb' => [
                ['name' => 'Edit'],
            ],
            'role' => $role,
        ]);
    }

    public function update(RoleRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->repo->update($id, $request);
            DB::commit();
            return redirect()->route('roles')->with('message', 'Data peran berhasil diperbarui!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('roles_edit', $id)->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->repo->delete($id);
            DB::commit();
            $res = [
                'status' => true,
                'message' => 'Data peran berhasil dihapus!',
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            $res = [
                'status' => false,
                'message' => 'Terjadi kesalahan! Silakan coba lagi!',
            ];
        }
        return response()->json($res);
    }
}
