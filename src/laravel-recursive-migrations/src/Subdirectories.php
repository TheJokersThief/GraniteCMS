<?php

namespace ThibaudDauce\LaravelRecursiveMigrations;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

trait Subdirectories
{
    /**
     * Fetch all subdirectories from a path.
     * The original path is included in the array.
     *
     * @param string $path
     * @return string[]
     */
    protected function subdirectories($path)
    {
        return Collection::make(Finder::create()->in($path)->directories())
            ->map(function (SplFileInfo $directory) {
                return $directory->getPathname();
            })
            ->prepend($path)
            ->values()
            ->toArray();
    }

    /**
     * Fetch all subdirectories from an array of base paths.
     * The original paths are included in the array.
     *
     * @param string[] $paths
     * @return string[]
     */
    protected function allSubdirectories($paths)
    {
        return Collection::make($paths)
            ->flatMap(function ($path) {
                return $this->subdirectories($path);
            })
            ->sort()
            ->toArray();
    }
}
