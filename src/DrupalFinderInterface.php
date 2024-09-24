<?php

/**
 * @file
 * Contains \DrupalFinder\DrupalFinderInterface.
 */

namespace DrupalFinder;

interface DrupalFinderInterface
{
    /**
     * Get the Drupal root path.
     *
     * This is the directory containing Drupal's index.php.
     */
    public function getDrupalRoot(): ?string;

    /**
     * Get the path to the Composer root directory.
     */
    public function getComposerRoot(): ?string;

    /**
     * Get the path to the "vendor" directory.
     */
    public function getVendorDir(): ?string;

}
