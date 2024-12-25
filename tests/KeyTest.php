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

namespace Pkg6\KvSettings\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Pkg6\KvSettings\Context;
use Pkg6\KvSettings\Key;

class KeyTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function it_calls_serializer_when_generating_a_key(): void
    {
        $context = new Context;
        $context->set('serialized', '1');
        $generator = new Key();
        $this->assertEquals(md5('key' . serialize($context)), $generator->generate("key", $context));
    }

    protected function getContextSerializerMock()
    {
        return Mockery::mock(Key::class);
    }
}
