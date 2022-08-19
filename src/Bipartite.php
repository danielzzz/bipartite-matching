<?php

/*
 * (c) DAN ZELISKO <daniel@zelisko.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Danielzzz\Bipartite;

class Bipartite
{
    private array $currentMatches         = [];
    private array $unmatchedStartVertices = [];
    private array $unmatchedEndVertices   = [];

    private function prepareVerticesRegistry(array $input): void
    {
        array_walk($input, function ($vals, $key) {
            $this->unmatchedStartVertices[$key] = true;
        });

        $endVertices = array_unique(array_merge(...(array_values($input))));
        array_walk($endVertices, function ($key) {
            $this->unmatchedEndVertices[$key] = true;
        });
    }

    private function findInitialMatches(array $input): array
    {
        $matchedEndVertices = [];
        foreach ($input as $startVertex => $endVertices)
        {
            if (isset($this->currentMatches[$startVertex]))
            {
                continue;
            }

            foreach ($endVertices as $endVertex)
            {
                if (in_array($endVertex, $matchedEndVertices))
                {
                    continue;
                }
                $matchedEndVertices[]               = $endVertex;
                $this->currentMatches[$startVertex] = $endVertex;
                $this->removeMatchedVericesFromRegistry($startVertex, $endVertex);
                break;
            }
        }

        return $this->currentMatches;
    }

    private function removeMatchedVericesFromRegistry($startVertex, $endVertex): void
    {
        unset($this->unmatchedStartVertices[$startVertex]);
        unset($this->unmatchedEndVertices[$endVertex]);
    }

    public function allStartVerticesAreMatched(): bool
    {
        return empty($this->unmatchedEndVertices);
    }

    private function isAnAugmentedPath(array $path): bool
    {
        $length = count($path);
        if (0 === $length)
        {
            return false;
        }

        $startsWithNotMatched = in_array($path[0], array_keys($this->unmatchedStartVertices));
        $endsWithNotMatched   = in_array($path[$length - 1], array_keys($this->unmatchedEndVertices));

        return $startsWithNotMatched && $endsWithNotMatched;
    }

    public function match(array $input): array
    {
        $this->prepareVerticesRegistry($input);
        $this->findInitialMatches($input);

        if ($this->allStartVerticesAreMatched())
        {
            return $this->currentMatches;
        }

        $paths = $this->findAllPathsForUnmatchedVertices($input);

        foreach ($paths as $path)
        {
            if (!$this->isAnAugmentedPath($path))
            {
                continue;
            }

            $this->updateMatchesWithAnAugmentedPath($path);
        }

        return $this->currentMatches;
    }

    private function findAllPathsForUnmatchedVertices(array $input): array
    {
        $allConnections = $this->getAllConnections($input);
        $paths          = [];
        foreach (array_keys($this->unmatchedStartVertices) as $startingNode)
        {
            $paths = array_merge($paths, $this->findPaths($startingNode, $allConnections));
        }

        return $paths;
    }

    private function updateMatchesWithAnAugmentedPath(array $path): void
    {
        for ($key = 0; $key < count($path); $key = $key + 2)
        {
            $startVortex                        = $path[$key];
            $endVortex                          = $path[$key + 1];
            $this->currentMatches[$startVortex] = $endVortex;
        }
    }

    private function findPaths($startingNode, $availableConnections, $currentPath = [], $paths = [])
    {
        if (!isset($availableConnections[$startingNode]))
        {
            $paths[] = $currentPath;

            return $paths;
        }

        $currentPath[] = $startingNode;

        $childConnections = $availableConnections[$startingNode];
        unset($availableConnections[$startingNode]);
        foreach ($childConnections as $nextNode)
        {
            $paths = $this->findPaths($nextNode, $availableConnections, $currentPath, $paths);
        }

        return $paths;
    }

    private function getAllConnections(array $connections): array
    {
        $nodesFrom = array_keys($connections);
        foreach ($nodesFrom as $node)
        {
            foreach ($connections[$node] as $nodesTo)
            {
                $connections[$nodesTo][] = $node;
            }
        }

        return $connections;
    }
}
