<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Contracts\Support\Renderable;
use View;

class GameController extends Controller
{
    /**
     * @param Game $game
     *
     * @return Renderable
     */
    public function game(Game $game): Renderable
    {
        return View::make('game', [
            'game' => $game,
        ]);
    }
}
