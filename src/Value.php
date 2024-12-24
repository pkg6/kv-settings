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

use Pkg6\KvSettings\Contracts\ValueInterface;

class Value implements ValueInterface
{
    public function serialize($value): string
    {
        return serialize($value);
    }

    public function unserialize($serialized)
    {
        return unserialize($serialized);
    }
}
