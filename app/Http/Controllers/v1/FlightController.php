<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\FlightService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    protected $flights;
    public function __construct(FlightService $service)
    {
        $this->flights = $service;

        $this->middleware('auth:api', ['only' => ['index', 'store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Get(
     *      path="/flights",
     *      tags={"flights"},
     *      summary="Flight List",
     *      @SWG\Response(response=200, description="Success"),
     *      security={{"MyHeaderAuthentication":{}}}
     * )
     */
    public function index()
    {
        $parameters = request()->input();

        $data = $this->flights->getFlights($parameters);

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Get(
     *      path="/flights/create?include={include}&status={status}",
     *      tags={"flights"},
     *      summary="Include Data & Filter Flight By Status",
     *      @SWG\Parameter(
     *          name="include",
     *          in="path",
     *          description="Include Data",
     *          required=false,
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="status",
     *          in="path",
     *          description="Filter by Status",
     *          required=false,
     *          type="string"
     *      ),
     *      @SWG\Response(response=200, description="Success"),
     *      @SWG\Response(response=404, description="Not Found"),
     * )
     */
    public function create()
    {
        $parameters = request()->input();

        $data = $this->flights->getFlights($parameters);

        if (!empty($data)) {
            return response()->json($data);
        } else {
            return response()->json('Not Found', 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Post(
     *     path="/flights",
     *     tags={"flights"},
     *     summary="Add A New Flight",
     *     @SWG\Parameter(
     *          name="schedule",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/"),
     *     ),
     *     @SWG\Response(response=201, description="Created"),
     *     @SWG\Response(response=422, description="Field Is Required"),
     *     @SWG\Response(response=500, description="Internal Server Error"),
     *     security={{"MyHeaderAuthentication":{}}}
     * )
     */
    public function store(Request $request)
    {
        $this->flights->validate($request->all());

        try {
            $flight = $this->flights->createFlight($request);
            return response()->json($flight, 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Get(
     *      path="/flights/{flightNumber}",
     *      tags={"flights"},
     *      summary="Flight ID",
     *      @SWG\Parameter(
     *          name="flightNumber",
     *          in="path",
     *          description="Search By Flight Number",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(response=200, description="Success"),
     *      @SWG\Response(response=404, description="Not Found"),
     * )
     */
    public function show($id)
    {
        $parameters = request()->input();

        $parameters['flightNumber'] = $id;
        $data = $this->flights->getFlights($parameters);

        if (!empty($data)) {
            return response()->json($data);
        } else {
            return response()->json('Not Found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Put(
     *      path="/flights/{flightNumber}",
     *      tags={"flights"},
     *      summary="Update Flight",
     *      @SWG\Parameter(
     *          name="flightNumber",
     *          in="path",
     *          required=true,
     *          type="string",
     *          description="flightNumber",
     *      ),
     *      @SWG\Parameter(
     *          name="user",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/"),
     *      ),
     *      @SWG\Response(response=200, description="Success"),
     *      @SWG\Response(response=404, description="Not Found"),
     *      @SWG\Response(response=422, description="Field Is Required"),
     *      @SWG\Response(response=505, description="Internal Server Error"),
     *      security={{"MyHeaderAuthentication":{}}}
     *  )
     *
     */
    public function update(Request $request, $id)
    {
        $this->flights->validate($request->all());

        try {
            $flight = $this->flights->updateFlight($request, $id);
            return response()->json($flight, 200);
        } catch (ModelNotFoundException $ex) {
            throw $ex;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Delete(
     *      path="/flights/{flightNumber}",
     *      tags={"flights"},
     *      operationId="deleteFlight",
     *      summary="Remove Flight",
     *      @SWG\Parameter(
     *          name="flightNumber",
     *          in="path",
     *          required=true,
     *          type="string",
     *          description="FlightNumber",
     *      ),
     *      @SWG\Response(response=204, description="No Content"),
     *      @SWG\Response(response=404, description="Not Found"),
     *      @SWG\Response(response=505, description="Internal Server Error"),
     *      security={{"MyHeaderAuthentication":{}}}
     *  )
     */
    public function destroy($id)
    {
        try {
            $flight = $this->flights->deleteFlight($id);
            return response()->make('', 204);
        } catch (ModelNotFoundException $ex) {
            throw $ex;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
