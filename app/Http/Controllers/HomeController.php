<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use View;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $games = Game::all();
        return View::make(
            'home',
            [
                'games' => $games,
            ]
        );
    }
}
