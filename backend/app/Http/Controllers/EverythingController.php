<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Client;
use App\Models\Parking;

class EverythingController extends Controller
{
    /* Show everything */
    public function showAll()
    {
        $clients = Client::query()->orderBy('name', 'asc')->get()->toArray();
        $buildings = Building::query()->orderBy('name', 'asc')->get()->toArray();
        $parkings = Parking::query()->orderBy('name', 'asc')->get()->toArray();

        $everything = array_merge(
            $clients,
            $buildings,
            $parkings
        );

        return response()->json($everything);
    }
}
