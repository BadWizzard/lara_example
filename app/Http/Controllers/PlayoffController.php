<?php

namespace App\Http\Controllers;

use App\Models\Playoff\Business\PlayoffBuilder;
use Illuminate\View\View;

class PlayoffController extends Controller
{
    public function show(): View
    {
        $playoffBuilder = new PlayoffBuilder();
        $tree = $playoffBuilder->build();

        return view('partial.playoff', [
            'tree' => $tree,
        ]);
    }
}
