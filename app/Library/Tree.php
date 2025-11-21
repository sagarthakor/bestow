<?php

namespace App\Library;


class Tree
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private function emptyTree()
    {
        $tree_structure = [
            'L' => ['user' => null],
            'R' => ['user' => null],
        ];

        $tree_structure = [
            'L' => ['user' => null, 'children' => $tree_structure],
            'R' => ['user' => null, 'children' => $tree_structure],
        ];

        $levels = [];
        for ($i = 0; $i < 2; $i++) {

            if ($i == 0)
                $levels['L'] = ['user' => null, 'children' => $tree_structure];
            if ($i == 1)
                $levels['R'] = ['user' => null, 'children' => $tree_structure];
        }

        return collect(Helper::arrayToObject($levels));
    }

    /**
     * @param \Illuminate\Support\Collection $direct_downlines
     * @return \Illuminate\Support\Collection
     */
    public function create($direct_downlines)
    {
        return $this->emptyTree()->map(function ($level, $key) use ($direct_downlines) {

            if ($direct_downlines->count() > 0) {

                $level->user = $direct_downlines->filter(function ($downline) use ($key) { return $downline->leg == $key;})->first();

                $level->children = collect($level->children)->map( function ($child, $key) use ($level) {

                    if ($level->user) {

                        $child->user = $level->user->children->filter(function ($downline) use ($key) {
                            return $downline->leg == $key;
                        })->first();

                        $child->children = collect($child->children)->map( function ($child2, $key2) use ($child) {

                            if ($child->user) {

                                $child2->user = $child->user->children->filter( function ($downline) use ($key2) {
                                    return $downline->leg == $key2;
                                })->first();
                            }

                            return $child2;

                        });


                    }

                    return $child;
                });
            }

            return $level;
        });

    }


}