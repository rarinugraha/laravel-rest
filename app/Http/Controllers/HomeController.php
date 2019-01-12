<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @SWG\Swagger(
 *      basePath="/api/v1/",
 *      @SWG\Info(
 *          title="Flights API",
 *          version="1.0.0"
 *      )
 * )
 */

/**
 * @SWG\SecurityScheme(
 *      securityDefinition="MyHeaderAuthentication",
 *      type="apiKey",
 *      in="header",
 *      name="Authorization"
 * )
 */

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
