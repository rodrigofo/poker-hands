<?php

namespace App\Services;

use App\Exceptions\EmptyFileException;
use App\Move;
use Illuminate\Support\Collection;

class MoveService
{
    /**
     * @param string $file
     *
     * @return array|bool
     *
     * @throws EmptyFileException
     */
    public function parseFile(string $file)
    {
        $hands = [];
        $lines = explode(PHP_EOL, $file);

        if (empty($lines)) {
            throw new EmptyFileException();
        }

        foreach ($lines as $line) {
            // skip empty lines
            if (empty($line)) {
                continue;
            }

            $cards = explode(' ', str_replace('\n', '', $line));
            $hands[] = array_chunk($cards, 5);
        }

        return $hands;
    }

    /**
     * @param $uploadedMoves
     *
     * @return array
     */
    public function handleGameMoves($uploadedMoves): array
    {
        $pokerService = new PokerService();
        $moves = [];

        foreach ($uploadedMoves as $move) {
            [$left, $right] = $move;

            $hand1 = new HandService(Collection::make($left));
            $game1 = $pokerService->setHand($hand1)->calculateScore()->getScore();

            $hand2 = new HandService(Collection::make($right));
            $game2 = $pokerService->setHand($hand2)->calculateScore()->getScore();

            $moveInst = new Move();
            $moveInst->hand_1 = (string) $hand1;
            $moveInst->hand_2 = (string) $hand2;
            // @see: https://wiki.php.net/rfc/combined-comparison-operator
            $moveInst->winner = (string) ($game1 <=> $game2);

            $moves[] = $moveInst;
        }

        return $moves;
    }
}
