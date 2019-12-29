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

    /** @var Collection */
    private Collection $groups;
    /** @var Collection */
    private Collection $shifted;
    /** @var mixed */
    private $distance;
    /** @var int */
    private int $score;
    /** @var HandService */
    private HandService $hand;

    /**
     * @param HandService $hand
     *
     * @return $this
     */
    public function setHand(HandService $hand): self
    {
        $this->hand = $hand;

        return $this;
    }

    /**
     * @return PokerService
     */
    public function calculateScore(): self
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

        return $this;
    }

    private function sortCards(): void
    {
        $this->processGroups();
        $this->processShifted();
        $this->calculateDistance();
    }

    private function processGroups(): void
    {
        $this->groups = Collection::make($this->hand::FACES)
            ->map(
                function ($face, $index) {
                    return $this->hand->getFaces()->filter(
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
        $this->shifted = $this->hand->getFaces()->map(
            static function ($face) {
                return ($face + 1) % 13;
            }
        );
    }

    private function calculateDistance(): void
    {
        $this->distance = min(
            $this->hand->getFaces()->max() - $this->hand->getFaces()->min(),
            $this->shifted->max() - $this->shifted->min()
        );
    }

    /**
     * @return bool
     */
    private function isFlush(): bool
    {
        return $this->hand->getSuits()->every(
            function ($suit) {
                return $suit === $this->hand->getSuits()[0];
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
            && $this->hand->getFaces()[0] === array_flip($this->hand::FACES)['T']
            && $this->hand->getSuits()[0] === array_flip($this->hand::SUITS)['S'];
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
