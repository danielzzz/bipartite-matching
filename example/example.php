<?php

require __DIR__.'/../vendor/autoload.php';

use Danielzzz\Bipartite\Bipartite;

$input = [
    'personA' => ['person3', 'person1'],
    'personB' => ['person3'],
    'personC' => ['person4', 'person2'],
    'personD' => ['person2'],
];

$bipartite = new Bipartite();
$result    = $bipartite->match($input);

dump($result);
