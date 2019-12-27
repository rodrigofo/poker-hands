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
    /** @var Collection */
    private Collection $groups;
    /** @var Collection */
    private Collection $shifted;
    /** @var mixed */
    private $distance;

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

    /**
     * @return Collection
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    /**
     * @return int
     */
    public function calculateScore(): int
    {
        $this->sortCards();
    }

    private function sortCards(): void
    {
        $this->processGroups();
        $this->processShifted();
        $this->calculateDistance();
    }

    private function processGroups(): void
    {
        $this->groups = Collection::make(self::FACES)
            ->map(
                function ($face, $index) {
                    return $this->faces->filter(
                        static function ($cardFace) use ($index) {
                            return $index === $cardFace;
                        }
                    )->count();
                }
            )
            ->sortByDesc(
                static function ($card) {
                    return $card;
                }
            )
            ->values();
    }

    private function processShifted(): void
    {
        $this->shifted = $this->faces->map(
            static function ($face) {
                return ($face + 1) % 13;
            }
        );
    }

    private function calculateDistance(): void
    {
        $this->distance = min(
            $this->faces->max() - $this->faces->min(),
            $this->shifted->max() - $this->shifted->min()
        );
    }
}
