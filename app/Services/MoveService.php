<?php

namespace App\Services;

use App\Exceptions\EmptyFileException;
use App\Move;
use Illuminate\Support\Collection;

class MoveService
{
    /** @var PokerService */
    private PokerService $pokerService;

    public function __construct(PokerService $pokerService)
    {
        $this->pokerService = $pokerService;
    }

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
        $moves = [];

        foreach ($uploadedMoves as $move) {
            [$left, $right] = $move;
            [$winner, $score] = $this->getScore(Collection::make($left), Collection::make($right));

            $moveInst = new Move();
            $moveInst->hand_1 = implode(' ', $left);
            $moveInst->hand_2 = implode(' ', $right);
            $moveInst->winner = (string) $winner;
            $moveInst->score = $score;

            $moves[] = $moveInst;
        }

        return $moves;
    }

    /**
     * @param Collection $left
     * @param Collection $right
     *
     * @return array [$winner, $score]
     */
    private function getScore(Collection $left, Collection $right): array
    {
        $hand1 = new HandService($left);
        $game1 = $this->pokerService->setHand($hand1)->calculateScore()->getScore();

        $hand2 = new HandService($right);
        $game2 = $this->pokerService->setHand($hand2)->calculateScore()->getScore();

        /**
         * For the calculation below @see: https://wiki.php.net/rfc/combined-comparison-operator
         */
        $winner = $game1 <=> $game2;
        $score = $winner === 1 ? $game1 : $game2;

        if (0 === $winner) {
            $winner = $hand1->getHigherCard() <=> $hand2->getHigherCard();
            $score = 0;
        }

        return [$winner, $score];
    }
}
