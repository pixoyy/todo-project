<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TaskController extends Controller
{
    private $repo;

    public function __construct()
    {
        $this->repo = new TaskRepository();
    }

    public function index()
    {
        $options = $this->repo->getFormOptions();

        return view('task.index', [
            'projects' => $options['projects'],
            'admins' => $options['admins'],
        ]);
    }

    public function data(Request $request)
    {
        $tasks = $this->repo->getData($request);
        return view('task.data', ['tasks' => $tasks]);
    }

    public function detail($id)
    {
        $task = $this->repo->getById($id);
        if (!isset($task)) {
            return redirect()->route('tasks');
        }

        return view('task.detail', [
            'breadcrumb' => [
                ['name' => 'Detail'],
            ],
            'task' => $task,
        ]);
    }

    public function add()
    {
        $options = $this->repo->getFormOptions();

        return view('task.add', [
            'projects' => $options['projects'],
            'admins' => $options['admins'],
            'breadcrumb' => [
                ['name' => 'Tambah'],
            ],
        ]);
    }

    public function create(TaskRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->repo->create($request);
            DB::commit();
            return redirect()->route('tasks')->with('message', 'Task berhasil ditambahkan!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('tasks_add')->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function edit($id)
    {
        $task = $this->repo->getById($id);
        if (!isset($task)) {
            return redirect()->route('tasks');
        }

        $options = $this->repo->getFormOptions();
        return view('task.edit', [
            'projects' => $options['projects'],
            'admins' => $options['admins'],
            'breadcrumb' => [
                ['name' => 'Edit'],
            ],
            'task' => $task,
        ]);
    }

    public function update(TaskRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->repo->update($id, $request);
            if (!$res['status']) {
                DB::rollBack();
                return redirect()->route('tasks')->with('message', $res['message']);
            }

            DB::commit();
            return redirect()->route('tasks')->with('message', $res['message']);
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('tasks_edit', $id)->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
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
