<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Karoly Negyesi <karoly@negyesi.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection;

class TagCache
{

    private array $tags = [];
    private array $names = [];

    public function setTags(string $id, array $tags): static
    {
        $this->clearTags($id);
        foreach ($tags as $key => $value) {
            if (is_array($value)) {
                $this->addTag($id, $key, $value);
            } else {
                $this->addTag($id, $value);
            }
        }

        return $this;
    }

    public function addTag(string $id, string $name, array $attributes = []): void
    {
        $this->tags[$name][$id][] = $attributes;
        $this->names[$id][] = $name;
    }

    public function clearTag(string $id, string $name): void
    {
        unset($this->tags[$name][$id]);
        $this->names[$id] = array_diff($this->names[$id] ?? [], [$name]);
    }

    public function clearTags(string $id): void
    {
        foreach ($this->names[$id] ?? [] as $name) {
            unset($this->tags[$name][$id]);
        }
        unset($this->names[$id]);
    }

    public function findTaggedServiceIds(string $name): array
    {
        return $this->tags[$name] ?? [];
    }

}
