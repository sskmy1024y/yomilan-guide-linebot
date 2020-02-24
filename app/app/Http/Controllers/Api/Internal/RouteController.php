<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Services\LINEBot\Actions\Route_Action;
use App\Services\LINEBot\Actions\Visit_Action;
use App\Services\LINEBot\GroupHelper;
use App\Services\Route\Route_Generate;
use Illuminate\Http\Request;
use Util_Assert;
use Util_DateTime;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auto = new Route_Generate(Util_DateTime::createFromHis('10:00:00'));
        return response()->json(['error' => false, 'message' => '', 'data' => $auto->make()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $location = [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        return response()->json(['error' => false, 'message' => '', 'data' => $location]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group_id = GroupHelper::identify($id)->group_id;
        $date = Util_DateTime::createNow();
        $route = Route_Action::showCurrentRoute($group_id, $date);

        return response()->json(['error' => false, 'message' => '', 'data' => $route]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group_id = GroupHelper::identify($id)->group_id;
        $route = Visit_Action::initializeVisit($group_id);

        return response()->json(['error' => false, 'message' => '', 'data' => $route]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
