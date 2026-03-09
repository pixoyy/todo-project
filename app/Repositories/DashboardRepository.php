<?php

namespace App\Repositories;

use App\Models\Dashboard;

class DashboardRepository{

    public function getData($request){



        return view('dashboard.index');
    }

    public function getById($id){

    }
}
