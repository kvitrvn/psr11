<?php

declare(strict_types=1);

namespace Kvitrvn\Psr11;

use Kvitrvn\Psr11\Exception\ContainerException;
use Kvitrvn\Psr11\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $entries = [];

    public function get(string $id): mixed
    {
        if (false === $this->has($id)) {
            throw new NotFoundException(\sprintf('No entry found for identifier "%s".', $id));
        }

        try {
            $entry = $this->entries[$id];

            if (\is_callable($entry)) {
                return $entry($this);
            }

            return $this->entries[$id];
        } catch (\Throwable $e) {
            throw new ContainerException(\sprintf('Error while retrieving the entry "%s".', $id), 0, $e);
        }
    }

    public function set(string $id, mixed $entry): void
    {
        $this->entries[$id] = $entry;
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->entries);
    }
}
