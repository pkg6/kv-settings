<?php

/*
 * This file is part of the pkg6/kv-settings
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * (L) Licensed <https://opensource.org/license/MIT>
 *
 * (A) zhiqiang <https://www.zhiqiang.wang>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\KvSettings;

use OutOfBoundsException;
use Pkg6\KvSettings\Contracts\ContextInterface;

class Context implements ContextInterface
{
    /**
     * @var array
     */
    protected $arguments = [];

    public function __construct(array $arguments = [])
    {
        foreach ($arguments as $name => $value) {
            $this->set($name, $value);
        }
    }

    public function get(string $name)
    {
        if ( ! $this->has($name)) {
            throw new OutOfBoundsException(
                sprintf('"%s" is not part of the context.', $name)
            );
        }

        return $this->arguments[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->arguments[$name]);
    }

    public function remove(string $name): ContextInterface
    {
        unset($this->arguments[$name]);

        return $this;
    }

    public function set(string $name, $value): ContextInterface
    {
        $this->arguments[$name] = $value;

        return $this;
    }

    public function count()
    {
        return count($this->arguments);
    }
}
