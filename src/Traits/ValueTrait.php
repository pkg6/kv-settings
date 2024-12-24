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

use Pkg6\KvSettings\Contracts\ValueInterface;
use Pkg6\KvSettings\Value;

trait ValueTrait
{
    /**
     * @var ValueInterface
     */
    protected $value;

    public function getValue(): ValueInterface
    {
        if (is_null($this->value)) {
            $this->value = new Value();
        }

        return $this->value;
    }

    public function setValue(ValueInterface $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $serialized
     *
     * @return mixed
     */
    protected function unserializeValue($serialized)
    {
        try {
            return $this->getValue()->unserialize($serialized);
        } catch (\Throwable $e) {
            return $serialized;
        }
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function serializeValue($value): string
    {
        return $this->getValue()->serialize($value);
    }
}
