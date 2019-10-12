<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Crons;
use Auth;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function view()
    {
        $cron = Crons::where('owner_id', Auth::user()->id)->get();
        $data = [
            'crons' => $cron,
        ];
        return view("view")->with($data);
    }

    public function create(Request $request)
    {
        $cron = new Crons;
        $cron->owner_id = Auth::user()->id;
        $cron->label = $request->label;
        $cron->learning = $request->learning ? isset($request->learning) : 0;
        $cron->allowance = $request->allowance;
        $cron->expected_duration = $request->expected_duration;
        $cron->cron_pattern = $request->cron_pattern;
        $cron->save();

        $start = "file_get_contents('https://monitor.uwebpro.com/start/" . base64_encode($cron->id) . "')";
        $end = "file_get_contents('https://monitor.uwebpro.com/end/" . base64_encode($cron->id) . "')";
        $data = [
            'result' => 'Successfully Added Cron use <pre>' . $start . '</pre> to at the beginning of your cron job and <pre>' . $end . '</pre> at the end to use the monitoring',
        ];
        return view('home')->with($data);
    }
}
