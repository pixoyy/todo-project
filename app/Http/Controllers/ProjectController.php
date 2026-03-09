<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProjectController extends Controller
{
    private $repo;
    public function __construct()
    {
        $this->repo = new ProjectRepository();
    }
    public function index()
    {
        return view(view: 'project.index');
    }

    public function data(Request $request)
    {
        $projects = $this->repo->getData($request);
        return view('project.data', ['projects' => $projects]);
    }

    public function detail($id)
    {
        $project = $this->repo->getById($id);
        if (!isset($project)) {
            return redirect()->route('projects');
        }

        return view('project.detail', [
            'breadcrumb' => [
                ['name' => 'Detail'],
            ],
            'project' => $project,
        ]);
    }

    public function add()
    {
        return view('project.add', [
            'breadcrumb' => [
                ['name' => 'Tambah'],
            ],
        ]);
    }

    public function create(ProjectRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->repo->create($request);
            DB::commit();
            return redirect()->route('projects')->with('message', 'Project berhasil ditambahkan!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('projects_add')->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function edit($id)
    {
        $project = $this->repo->getById($id);
        if (!isset($project)) {
            return redirect()->route('projects');
        }

        return view('project.edit', [
            'breadcrumb' => [
                ['name' => 'Edit'],
            ],
            'project' => $project,
        ]);
    }

    public function update(ProjectRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->repo->update($id, $request);
            DB::commit();
            return redirect()->route('projects')->with('message', 'Project berhasil diperbarui!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('projects_edit', $id)->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
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
