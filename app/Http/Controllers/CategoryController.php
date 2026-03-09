<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryController extends Controller
{
    private $repo;

    public function __construct()
    {
        $this->repo = new CategoryRepository();
    }

    public function index()
    {
        return view('category.index', [
            'projects' => $this->repo->getProjectOptions(),
        ]);
    }

    public function data(Request $request)
    {
        $categories = $this->repo->getData($request);
        return view('category.data', ['categories' => $categories]);
    }

    public function detail($id)
    {
        $category = $this->repo->getById($id);
        if (!isset($category)) {
            return redirect()->route('categories');
        }

        return view('category.detail', [
            'breadcrumb' => [
                ['name' => 'Detail'],
            ],
            'category' => $category,
        ]);
    }

    public function add()
    {
        return view('category.add', [
            'projects' => $this->repo->getProjectOptions(),
            'breadcrumb' => [
                ['name' => 'Tambah'],
            ],
        ]);
    }

    public function create(CategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->repo->create($request);
            DB::commit();
            return redirect()->route('categories')->with('message', 'Category berhasil ditambahkan!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('categories_add')->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
        }
    }

    public function edit($id)
    {
        $category = $this->repo->getById($id);
        if (!isset($category)) {
            return redirect()->route('categories');
        }

        return view('category.edit', [
            'projects' => $this->repo->getProjectOptions(),
            'breadcrumb' => [
                ['name' => 'Edit'],
            ],
            'category' => $category,
        ]);
    }

    public function update(CategoryRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->repo->update($id, $request);
            DB::commit();
            return redirect()->route('categories')->with('message', 'Category berhasil diperbarui!');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('categories_edit', $id)->withInput()->with('message', 'Terjadi kesalahan! Silakan coba lagi!');
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
