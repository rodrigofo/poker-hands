<?php

namespace App\Http\Controllers;

use App\Exceptions\EmptyFileException;
use App\Game;
use App\Services\MoveService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use View;

class HomeController extends Controller
{
    /** @var MoveService */
    private MoveService $moveService;

    public function __construct()
    {
        $this->moveService = new MoveService();
    }

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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function upload(Request $request): Response
    {
        $file = $request->file('game');

        try {
            $contents = isset($file) ? $file->get() : '';
        } catch (FileNotFoundException $e) {
            $contents = '';
        }

        if (empty($contents)) {
            return \Redirect::route('home')
                ->with('message', 'Upload failed!')
                ->with('upload_status', false);
        }

        try {
            $uploadedMoves = $this->moveService->parseFile($contents);
        } catch (EmptyFileException $e) {
            return \Redirect::route('home')
                ->with('message', $e->getMessage())
                ->with('upload_status', false);
        }

        $moves = $this->moveService->handleGameMoves($uploadedMoves);

        /** @var Game $game */
        $game = Game::create();
        $game->moves()->saveMany($moves);

        return \Redirect::route('home')
            ->with('message', 'Game uploaded successfully!')
            ->with('upload_status', true)
            ->with('game', $game->id);
    }
}
