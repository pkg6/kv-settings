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

use PHPUnit\Framework\TestCase;
use Pkg6\KvSettings\Value;

class ValueTest extends TestCase
{
    /**
     * @test
     *
     * @param  mixed  $value
     *
     * @dataProvider valuesToTest
     */
    public function it_serializes_values($value): void
    {
        $serializer = new Value();

        self::assertEquals(
            serialize($value),
            $serializer->serialize($value)
        );
    }

    /**
     * @test
     *
     * @param  mixed  $value
     *
     * @dataProvider valuesToTest
     */
    public function it_unserializes_values($value): void
    {
        $serializer = new Value();

        $serialized = serialize($value);

        self::assertEquals(
            $value,
            $serializer->unserialize($serialized)
        );
    }
    public function valuesToTest(): array
    {
        return [
            [null],
            [1],
            [1.1],
            ['string'],
            [['array' => 'array']],
            [(object) ['a' => 'b']],
        ];
    }
}
