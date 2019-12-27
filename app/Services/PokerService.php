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
    /** @var int */
    private int $score;

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

        // High card
        $this->score = 0;

        if ($this->isOnePair()) {
            $this->score = 1;
        }

        if ($this->isTwoPairs()) {
            $this->score = 2;
        }

        if ($this->isThreeOfKind()) {
            $this->score = 3;
        }

        if ($this->isStraight()) {
            $this->score = 4;
        }

        if ($this->isFlush()) {
            $this->score = 5;
        }

        if ($this->isFullHouse()) {
            $this->score = 6;
        }

        if ($this->isFourOfKind()) {
            $this->score = 7;
        }

        if ($this->isStraightFlush()) {
            $this->score = 8;
        }

        if ($this->isRoyalFlush()) {
            $this->score = 9;
        }

        return $this->score;
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

    /**
     * @return bool
     */
    private function isFlush(): bool
    {
        return $this->suits->every(
            function ($suit) {
                return $suit === $this->suits[0];
            }
        );
    }

    /**
     * @return bool
     */
    private function isStraight(): bool
    {
        return $this->groups[0] === 1 && $this->distance < 5;
    }

    /**
     * @return bool
     */
    private function isStraightFlush(): bool
    {
        return $this->isStraight() && $this->isFlush();
    }

    /**
     * @return bool
     */
    private function isFourOfKind(): bool
    {
        return $this->groups[0] === 4;
    }

    /**
     * @return bool
     */
    private function isFullHouse(): bool
    {
        return $this->groups[0] === 3 && $this->groups[1] === 2;
    }

    /**
     * @return bool
     */
    private function isThreeOfKind(): bool
    {
        return $this->groups[0] === 3;
    }

    /**
     * @return bool
     */
    private function isTwoPairs(): bool
    {
        return $this->groups[0] === 2 && $this->groups[1] === 2;
    }

    /**
     * @return bool
     */
    private function isOnePair(): bool
    {
        return $this->groups[0] === 2;
    }

    /**
     * @return bool
     */
    private function isRoyalFlush(): bool
    {
        return $this->isStraightFlush()
            && $this->faces[0] === array_flip(self::FACES)['T']
            && $this->suits[0] === array_flip(self::SUITS)['S'];
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
