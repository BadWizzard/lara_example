<?php

namespace App\Models\Playoff\Business;

use App\Models\Match\Transfer\MatchTransfer;
use App\Models\Round\Persistence\RoundModel as Round;

class PlayoffBuilder
{
    /**
     * @var int
     */
    public $sides = 2;

    /**
     * @var int
     */
    public $matches_first_round = 16;

    /**
     * @var int
     */
    public $matches_per_group = 4;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    private $rounds;

    public function __construct()
    {
        $this->rounds = Round::all();
    }

    public function build(): array
    {
        $tree = [
            'final_match' => [
                'round' => $this->getRoundsCount(),
                'name' => '',
                'match' => new MatchTransfer(),
            ],
            'main' => [],
        ];

        for ($side = 1; $side <= $this->sides; $side++) {
            $tree['main'][] = [
                'side' => $side,
                'rounds' => $this->getRoundsPerSideCount(),
                'trees' => $this->buildTrees($side),
            ];
        }

        if ($this->sides > 1) {
            $finalRound = $tree['final_match']['round'] - 1;
            $tree['final_match']['name'] = $this->getRoundName($finalRound);
        }

        return $tree;
    }

    /**
     * @param int $side
     * @return array
     */
    private function buildTrees(int $side): array
    {
        $trees = [];
        $rounds = $this->getRoundsCount();
        $match_position_by_round = [];

        $matches_per_round = $this->matches_first_round;
        $tree_data = [
            'rounds' => [],
        ];

        for ($round = 0; $round < $rounds; $round++) {
            $round_data = [
                'round' => $round,
                'name' => $this->getRoundName($round),
                'matches' => [],
            ];

            if (empty($match_position_by_round[$round])) {
                $match_position_by_round[$round] = 0;
            }

            for ($game = 0; $game < $matches_per_round; $game++) {
                $match_group_index = floor($game / $this->matches_per_group);

                $round_data['matches'][$match_group_index][] = new MatchTransfer();

                $match_position_by_round[$round]++;
            }

            $tree_data['rounds'][] = $round_data;

            if ($matches_per_round < 2) {
                break;
            } else {
                $matches_per_round /= 2;
            }
        }

        $trees[] = $tree_data;

        return $trees;
    }

    /**
     * @return int
     */
    private function getRoundsPerSideCount(): int
    {
        $rounds_per_side_count = 1;
        $matches = $this->matches_first_round;

        do {
            $rounds_per_side_count++;
            $matches = ceil(($matches / 2));
        } while ($matches >= 2);

        return $rounds_per_side_count;
    }

    /**
     * @return int
     */
    private function getRoundsCount(): int
    {
        if ($this->sides > 1) {
            return $this->getRoundsPerSideCount() + 1;
        }

        return $this->getRoundsPerSideCount();
    }

    /**
     * @param int $round
     * @return string
     */
    private function getRoundName(int $round): string
    {
        foreach ($this->rounds as $r) {
            if ($r['round'] == $round) {
                return $r['name'];
            }
        }

        return '';
    }
}
