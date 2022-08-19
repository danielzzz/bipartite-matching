# Bipartite matching using the Hopcroft-Karp Algorithm

Takes an unweighted bipartite graph as input and returns the maximal cardinality matching.

This is a PHP implementation of the Hopcroft-Karp biparite matching algorithm.  
https://en.wikipedia.org/wiki/Hopcroft%E2%80%93Karp_algorithm

## Practical usage

This algorithm can be used for example to maximize number of meetings when we have each member of a group A requesting multiple meetings with people from the group B.

```
$input = [
    'personA' => ['person3', 'person1'],
    'personB' => ['person3'],
    'personC' => ['person4', 'person2'],
    'personD' => ['person2']
];

$bipartite = new Bipartite();
$result = $bipartite->match($input);

/*
^ array:4 [
  "personA" => "person1"
  "personC" => "person4"
  "personD" => "person2"
  "personB" => "person3"
]

*/
```