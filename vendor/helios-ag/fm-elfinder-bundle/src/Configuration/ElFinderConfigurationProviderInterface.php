<?php

declare(strict_types=1);

namespace App\Configuration;

/**
 * Interface ElFinderConfigurationProviderInterface.
 */
interface ElFinderConfigurationProviderInterface
{
    public function getConfiguration(string $instance): array;
}
