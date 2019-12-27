<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PokerService
{
    /** @var array */
    public const HANDS = [
        'High Card',
        'Pair',
        'Two Pairs',
        'Three of a Kind',
        'Straight',
        'Flush',
        'Full House',
        'Four of a Kind',
        'Straight Flush',
        'Royal Flush',
    ];
    /** @var array */
    private const FACES = [2, 3, 4, 5, 6, 7, 8, 9, 'T', 'J', 'Q', 'K', 'A'];
    /** @var array */
    private const SUITS = ['C', 'D', 'H', 'S'];

    /** @var Collection */
    private Collection $cards;
    /** @var Collection */
    private Collection $faces;
    /** @var Collection */
    private Collection $suits;

    /**
     * Hand constructor.
     *
     * @param Collection|null $cards
     */
    public function __construct(?Collection $cards = null)
    {
        if ($cards) {
            $this->setCards($cards);
        }
    }

    /**
     * @param Collection $cards
     */
    private function setCards(Collection $cards): void
    {
        $this->cards = $cards->map(
            static function ($card) {
                return str_replace('\n', '', trim($card));
            }
        );

        $this->setFaces();
        $this->setSuits();
    }

    /**
     * @return $this
     */
    private function setFaces(): self
    {
        $this->faces = $this->cards
            ->map(
                static function ($card) {
                    return array_flip(self::FACES)[$card[0]];
                }
            )
            ->sort()
            ->values();

        return $this;
    }

    /**
     * @return $this
     */
    private function setSuits(): self
    {
        $this->suits = $this->cards
            ->map(
                static function ($card) {
                    return array_flip(self::SUITS)[$card[1]];
                }
            );

        return $this;
    }
}
