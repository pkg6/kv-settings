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

use Pkg6\KvSettings\Contracts\ContextInterface;
use Pkg6\KvSettings\Contracts\KeyInterface;

class Key implements KeyInterface
{
    public function generate($key, ContextInterface $context = null): string
    {
        return md5($key . serialize($context));
    }
}
