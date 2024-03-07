<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

use Illuminate\Http\Request;

use App\Models\live_positions;

class dataFetchController extends Controller
{
    public function getMeetings($trackId){

        //return dd($trackId);

        $getSeasons = Http::get('https://f1.sportmonks.com/api/v1.0/seasons',[
            'api_token' => 'ssvgFPz5rOHQEiTz1FiwAhAePBWjzRhRaiIx6sA4qt86FHdDz82529lrdQIC'
        ]);

        $trackData = Http::get('https://f1.sportmonks.com/api/v1.0/tracks/season/'.$trackId, [
            'api_token' => 'ssvgFPz5rOHQEiTz1FiwAhAePBWjzRhRaiIx6sA4qt86FHdDz82529lrdQIC'
        ]);

        //return dd($response->throw()->json());

        return Inertia::render('Welcome',[
            'seasons' => fn () => $getSeasons->throw()->json(),
            'trackData' => $trackData->throw()->json(),
            'live_positions' => live_positions::all()->throw()
        ]);
    }
}
