<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Building;
use Illuminate\Http\Request;

class ClientController extends Controller {

    /* Show all clients */
    public function showAllClients() {
        $sortedAll = Client::orderBy('name', 'asc')->get();

        return response()->json($sortedAll);
    }

    /* Show one client by ID */
    public function showOneClient($id) {
        $client = Client::query()->where('id', $id)->first();

        if($client){
            $thisClientArray = [];
            array_push($thisClientArray, $client->toArray());

            $thisClientId = $client->id;

            $siblingClients = Client::query()
                ->where('id', '!=', $thisClientId)
                ->get();

            $childrenBuildings = Building::query()
                ->where('client_id', $thisClientId)
                ->orderBy('name', 'asc')
                ->get();

            $siblingClientsArray = $siblingClients->toArray();
            $childrenBuildingsArray = $childrenBuildings->toArray();

            foreach ($thisClientArray as &$val) $val['relation'] = null;
            foreach ($siblingClientsArray as &$val) $val['relation'] = 'sibling';
            foreach ($childrenBuildingsArray as &$val) $val['relation'] = 'child';

            $allClients = array_merge(
                $thisClientArray,
                $siblingClientsArray
            );

            $allClients = collect($allClients)->sortBy('name')->toArray();

            $response = array_merge(
                $allClients,
                $childrenBuildingsArray
            );

            return response()->json($response);
        } else {
            return response('No client with ID '.$id.' found.', 208);
        }
    }

    /* Add/Create a client */
    public function createClient(Request $request) {
        $client = Client::where('name', $request->name)->first();

        if($client) {
            return response('Please use other name, this client already present.', 208);
        } else {
            Client::create($request->all());

            return response($request->name." - added", 201);
        }
    }

//    public function update($id, Request $request) {
//        $client = Client::findOrFail($id);
//        $client->update($request->all());
//
//        return response()->json($client, 200);
//    }
//
//    public function delete($id) {
//        Client::findOrFail($id)->delete();
//        return response('Deleted Successfully', 200);
//    }
}
