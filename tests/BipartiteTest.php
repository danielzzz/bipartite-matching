<?php

namespace Test;

use Danielzzz\Bipartite\Bipartite;
use PHPUnit\Framework\TestCase;

class BipartiteTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testMatching($expected, $input): void
    {
        $bipartite = new Bipartite();

        $result = $bipartite->match($input);
        $this->assertEqualsCanonicalizing($result, $expected);
    }

    public function provideData(): array
    {
        return [
            [
                [
                    'a' => 4,
                    'b' => 3,
                    'c' => 2,
                    'd' => 5,
                    'e' => 1,
                ],
                [
                    'c' => [3, 2, 4],
                    'a' => [4, 1],
                    'b' => [3],

                    'e' => [1],
                    'd' => [1, 5],
                ],
            ],
            // simple
            [
                ['a' => 2, 'b' => 1],
                ['a' => [2], 'b' => [1, 2]],
            ],
            // numeric keys
            [
                [1 => 2, 3 => 1],
                [1 => [2], 3 => [1, 2]],
            ],
            // numeric keys 2
            [
                [0 => 'a', 1 => 'c', 2 => 'd', 3 => 'b'],
                [
                    '0' => ['a', 'c'],
                    '1' => ['c'],
                    '2' => ['d'],
                    '3' => ['c', 'b'],
                ],
            ],
            // example
            [
                ['personA' => 'person1', 'personB' => 'person3', 'personC' => 'person4', 'personD' => 'person2'],
                [
                    'personA' => ['person3', 'person1'],
                    'personB' => ['person3'],
                    'personC' => ['person2', 'person4'],
                    'personD' => ['person2'],
                ],
            ],
        ];
    }
}
