<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Building;
use App\Models\Parking;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /* Show all buildings */
    public function showAllBuildings()
    {
        return response()->json(Building::orderBy('name', 'asc')->get());
    }

    /* Show one building by ID */
    public function showOneBuilding($id)
    {
        $building = Building::query()->where('id', $id)->first();

        if ($building){
            $thisBuildingArray = [];
            array_push($thisBuildingArray, $building->toArray());

            $thisBuildingId = $building->id;
            $thisBuildingClientId = $building->client_id;

            $parentClient = Client::query()
                ->where('id', $thisBuildingClientId)
                ->get();

            $siblingBuildings = Building::query()
                ->where('client_id', $thisBuildingClientId)
                ->where('id', '!=', $thisBuildingId)
                ->get();

            $childrenParkings = Parking::query()
                ->whereJsonContains('building_ids', $thisBuildingId, 'or')
                ->orderBy('name', 'asc')
                ->get();

            $parentClientsArray = $parentClient->toArray();
            $siblingBuildingsArray = $siblingBuildings->toArray();
            $childrenParkingsArray = $childrenParkings->toArray();

            foreach ($parentClientsArray as &$val) $val['relation'] = 'parent';
            foreach ($thisBuildingArray as &$val) $val['relation'] = null;
            foreach ($siblingBuildingsArray as &$val) $val['relation'] = 'sibling';
            foreach ($childrenParkingsArray as &$val) $val['relation'] = 'child';

            $allBuildings = array_merge(
                $thisBuildingArray,
                $siblingBuildingsArray
            );

            $allBuildings = collect($allBuildings)->sortBy('name')->toArray();
            $childrenParkingsArray = collect($childrenParkingsArray)->sortBy('name')->toArray();

            $response = array_merge(
                $parentClientsArray,
                $allBuildings,
                $childrenParkingsArray
            );

            return response()->json($response);
        } else {
            return response('No building with ID '.$id.' found.', 208);
        }
    }

    /* Create/add a building */
    public function createBuilding(Request $request)
    {
        $building = Building::where('name', $request->name)->first();

        if ($building) {
            return response()->json('Please use other name, this building already present.', 208);
        } else {
            Building::create($request->all());

            return response($request->name." - added", 201);
        }
    }
}
