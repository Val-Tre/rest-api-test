<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /* Show all parkings */
    public function showAllParkings()
    {
        return response()->json(Parking::orderBy('name', 'asc')->get());
    }

    /* Show one parking by ID with parent/sibling/children tree */
    public function showOneParking($id)
    {
        $parking = Parking::query()->where('id', $id)->first();

        if ($parking){
            $parkingArray = [];
            array_push($parkingArray, $parking->toArray());

            $parkingIds = $parking->building_ids ?: [];
            $parkingIds = array_map('intval', $parkingIds ?: []);

            $parentBuildings = Building::query()->whereIn('id', $parkingIds ?: [])->get();

            $buildingIds = $parentBuildings->pluck('id')->toArray();

            $siblingParkings = Parking::query()
                ->whereJsonContains('building_ids', $buildingIds, 'or')
                ->where('id', '!=', $parking->id)
                ->get();

            $parentBuildingsArray = $parentBuildings->toArray();
            $siblingParkingsArray = $siblingParkings->toArray();

            foreach ($parentBuildingsArray as &$val) $val['relation'] = 'parent';
            foreach ($siblingParkingsArray as &$val) $val['relation'] = 'sibling';
            foreach ($parkingArray as &$val) $val['relation'] = null;

            $response = array_merge(
                $parentBuildingsArray,
                $siblingParkingsArray,
                $parkingArray
            );

            return response()->json($response);
        } else {
            return response('No parking with ID '.$id.' found.', 208);
        }
    }

    /* Create/add a parking */
    public function createParking(Request $request)
    {
        $isParking = Parking::where('name', $request->name)->first();

        if ($isParking) {
            return response('Please use other name, this parking already present.', 208);
        } else {

            $payload = [
                'name' => $request->get('name'),
                'building_ids' => json_decode($request->get('building_ids')),
            ];

            $payload['building_ids'] = array_map('intval', $payload['building_ids']);

            $parking = Parking::create($payload);

            return response($request->name." - added", 201);
        }
    }
}
