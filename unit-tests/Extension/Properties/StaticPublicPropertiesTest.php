<?php

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Extension\Properties;

use PHPUnit\Framework\TestCase;

class StaticPublicPropertiesTest extends TestCase
{
    public function testAssertations()
    {
        $this->assertNull(\Test\Properties\StaticPublicProperties::$someNull);
        $this->assertNull(\Test\Properties\StaticPublicProperties::$someNullInitial);
        $this->assertFalse(\Test\Properties\StaticPublicProperties::$someFalse);
        $this->assertTrue(\Test\Properties\StaticPublicProperties::$someTrue);
        $this->assertSame(\Test\Properties\StaticPublicProperties::$someInteger, 10);
        $this->assertSame(\Test\Properties\StaticPublicProperties::$someDouble, 10.25);
        $this->assertSame(\Test\Properties\StaticPublicProperties::$someString, 'test');
    }

    public function testIssues1904()
    {
        $value = &\Test\Properties\StaticPublicProperties::$someString;
        $value = 'test1';
        $this->assertSame(\Test\Properties\StaticPublicProperties::$someString, $value);
        \Test\Properties\StaticPublicProperties::$someString = 'test2';
        $this->assertSame(\Test\Properties\StaticPublicProperties::$someString, $value);
        \Test\Properties\StaticPublicProperties::setSomeString('test3');
        $this->assertSame(\Test\Properties\StaticPublicProperties::$someString, $value);
    }
}
