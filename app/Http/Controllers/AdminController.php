<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminController extends Controller
{
    private $repo;

    public function __construct()
    {
        $this->repo = new AdminRepository();
    }

    public function index()
    {
        return view('admin.index', [
            'roles' => $this->repo->getRoleOptions(),
        ]);
    }

    public function data(Request $request)
    {
        $users = $this->repo->getData($request);
        return view('admin.data', ['users' => $users]);
    }

    public function detail($id)
    {
        $user = $this->repo->getById($id);
        if (!isset($user)) {
            return redirect()->route('users');
        }

        return view('admin.detail', [
            'breadcrumb' => [
                ['name' => 'Detail'],
            ],
            'user' => $user,
        ]);
    }

    public function add()
    {
        return view('admin.add', [
            'roles' => $this->repo->getRoleOptions(),
            'breadcrumb' => [
                ['name' => 'Tambah'],
            ],
        ]);
    }

    public function create(AdminRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->repo->create($request);
            DB::commit();
            return redirect()->route('users')->with('message', 'User berhasil ditambahkan!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('users_add')->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function edit($id)
    {
        $user = $this->repo->getById($id);
        if (!isset($user)) {
            return redirect()->route('users');
        }

        return view('admin.edit', [
            'roles' => $this->repo->getRoleOptions(),
            'breadcrumb' => [
                ['name' => 'Edit'],
            ],
            'user' => $user,
        ]);
    }

    public function update(AdminRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->repo->update($id, $request);
            if (!$res['status']) {
                DB::rollBack();
                return redirect()->route('users')->with('message', $res['message']);
            }

            DB::commit();
            return redirect()->route('users')->with('message', $res['message']);
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('users_edit', $id)->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $res = $this->repo->delete($id);
            if ($res['status']) {
                DB::commit();
            } else {
                DB::rollBack();
            }
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
