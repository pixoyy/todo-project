<?php

namespace App\Http\Controllers;

use App\Repositories\AuthorizationRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthorizationController extends Controller
{
    private $repo;
    private $roleRepo;
    public function __construct()
    {
        $this->repo = new AuthorizationRepository;
        $this->roleRepo = new RoleRepository;
    }

    public function index()
    {
        $roles = $this->roleRepo->getRoles();
        $modules = $this->repo->getModules($roles->first()->id);
        $data = view('authorization.data', ['modules' => $modules]);
        return view('authorization.index', ['roles' => $roles, 'data' => $data]);
    }

    public function changeRole(Request $request)
    {
        $roleId = $request->query('id');
        $modules = $this->repo->getModules($roleId);
        return view('authorization.data', ['modules' => $modules]);
    }

    public function save(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->repo->save($request);
            DB::commit();
            $res = [
                'status' => true,
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
