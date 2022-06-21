<?php

namespace App\Http\Controllers;

use App\Models\Playoff\Business\PlayoffBuilder;
use Illuminate\Http\Request;

class PlayoffController extends Controller
{
    public function build(Request $request)
    {
        /*$request->validate([
            'sides' => 'required|int',
            'matches_first_round' => 'required|int',
            'matches_per_group' => 'required|int',
        ]);*/

        $playoffBuilder = new PlayoffBuilder();

        $playoffBuilder->sides = $request->sides;
        $playoffBuilder->matches_first_round = $request->matches_first_round;
        $playoffBuilder->matches_per_group = $request->matches_per_group;

        $tree = $playoffBuilder->build();

        dd($tree);
        return view('partial.playoff', $tree);
    }
}
