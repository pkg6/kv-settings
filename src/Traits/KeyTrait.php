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

namespace Pkg6\KvSettings\Traits;

use Pkg6\KvSettings\Contracts\KeyInterface;
use Pkg6\KvSettings\Key;

trait KeyTrait
{
    /**
     * @var KeyInterface
     */
    protected $key;

    public function getKey(): KeyInterface
    {
        if (is_null($this->key)) {
            $this->key = new Key();
        }

        return $this->key;
    }

    public function setKey(KeyInterface $key)
    {
        $this->key = $key;

        return $this;
    }
    /**
     * @param string $key
     *
     * @return string
     */
    protected function getKeyForStorage(string $key): string
    {
        return $this->getKey()->generate($key, $this->context);
    }
}
