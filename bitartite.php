<?php

$input = [
    'b' => [1, 4],
    'e' => [7, 3, 6],
    'j' => [2, 5, 4],
    'l' => [7, 2],
    't' => [7, 6, 5],
    'a' => [3, 6],
    'r' => [6, 7],
];

$input = [
    'a' => [1],
    'b' => [3],
    'c' => [2, 3],
];

$output = bipartiteMatch($input);
var_dump($output);

function bipartiteMatch($connectionsLeft)
{
    $obj = new stdClass();
    //get connections right

    $leftKeys = array_keys($connectionsLeft);
    foreach ($leftKeys as $u)
    {
        foreach ($connectionsLeft[$u] as $v)
        {
            $connectionsRight[$v][] = $u;
        }
    }

    $allConnections = $connectionsLeft;
    foreach ($connectionsRight as $i => $v)
    {
        $allConnections[$i] = $v;
    }

    $result = [];
    foreach ($leftKeys as $u)
    {
        $obj->connections = $allConnections;
        $obj->result      = [];
        $obj->current     = $u;

        $obj = paths($obj);
        //merge temp result with global one
        foreach ($obj->result as $key => $val)
        {
            if (in_array($key, $leftKeys))
            {
                $result[$key] = $val;
            }
        }
    }

    return array_unique($result);
}

function paths($obj)
{
    echo 'current: '.$obj->current."\n";
    $keys = array_keys($obj->connections);
    //var_dump($keys);
    if (in_array($obj->current, $keys))
    {
        if (!empty($obj->connections[$obj->current]))
        {
            // echo "found ".$obj->current."\n";
            // echo "connections: ".join(", ", $obj->connections[$obj->current])."\n";
            // store current connection
            if (!empty($obj->connections[$obj->current]))
            {
                $nextNode                   = array_pop($obj->connections[$obj->current]);
                $obj->result[$obj->current] = $nextNode;
            }
            // echo "nextNode: ".$nextNode."\n";
            //unset current node and all connections to it
            unset($obj->connections[$obj->current]);

            // echo "connections left: ".join(", ", array_keys($obj->connections))."\n";

            foreach ($obj->connections as $key => $tmpConnections)
            {
                $tmpConnections         = array_diff($tmpConnections, [$obj->current]);
                $obj->connections[$key] = $tmpConnections;
            }

            if (isset($nextNode))
            {
                $obj->current = $nextNode;
                $obj          = paths($obj);
            }
        }

        return $obj;
    }
}

/*
function bipartiteMatch($input)
{
    $allKeys = array_keys($input);
    $matchingLeft = [];
    $matchingRight = [];

    foreach ($input as $u => $connections) {
        foreach ($graph[$u] as $v) {
            if (!in_array($v, $matching)) {
                $matchingV[$v] = $u;
                break;
            }
        }
    }

    foreach ($matchingV as $v => $u) {
        $matching[$u] = $v;
    }



    while (true) {
        $unmatched = array_diff($allKeys, array_values($matching));

        var_dump($unmatched);
        $k = [];
        foreach ($umatched as $u) {
            foreach ($graph[$u] as $v) {
            }
        }
        return $matching;
    }
}*/
    /*
    while (1) {


        # structure residual graph into layers
        # pred[u] gives the neighbor in the previous layer for u in U
        # preds[v] gives a list of neighbors in the previous layer for v in V
        # unmatched gives a list of unmatched vertices in final layer of V,
        # and is also used as a flag value for pred[u] when u is in the first layer
        $preds = [];
        $unmatched = [];

        foreach ($graph as $u) {
            $pred[$u] = [];
        }

        for ($matching as $v) {
            unset($pred[$u]);
        }
        for v in matching:
            del pred[matching[v]]
        layer = list(pred)

        # repeatedly extend layering structure by another pair of layers
        while layer and not unmatched:
            newLayer = {}
            for u in layer:
                for v in graph[u]:
                    if v not in preds:
                        newLayer.setdefault(v,[]).append(u)
            layer = []
            for v in newLayer:
                preds[v] = newLayer[v]
                if v in matching:
                    layer.append(matching[v])
                    pred[matching[v]] = v
                else:
                    unmatched.append(v)

        # did we finish layering without finding any alternating paths?
        if not unmatched:
            unlayered = {}
            for u in graph:
                for v in graph[u]:
                    if v not in preds:
                        unlayered[v] = None
            return (matching,list(pred),list(unlayered))

        # recursively search backward through layers to find alternating paths
        # recursion returns true if found path, false otherwise
        def recurse(v):
            if v in preds:
                L = preds[v]
                del preds[v]
                for u in L:
                    if u in pred:
                        pu = pred[u]
                        del pred[u]
                        if pu is unmatched or recurse(pu):
                            matching[v] = u
                            return 1
            return 0

        for v in unmatched: recurse(v)
    }
}
*/
