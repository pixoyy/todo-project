<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    private $repo;

    public function __construct()
    {
        $this->repo = new DashboardRepository();
    }
    public function index()
    {
        return view('dashboard.index');
    }

    public function data(Request $request){
        $dashboards = $this->repo->getData($request);
        return view('dashboard.index', ['dashboards' => $dashboards]);
    }

}
