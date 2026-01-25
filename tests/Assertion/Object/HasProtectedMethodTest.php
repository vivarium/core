<?php

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Object;

use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\Object\HasProtectedMethod;
use Vivarium\Test\Assertion\Stub\PrivateConstructorStub;
use Vivarium\Test\Assertion\Stub\ProtectedConstructorStub;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Assertion\Object\HasProtectedMethod */
final class HasProtectedMethodTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::assert()
     * @dataProvider provideSuccess()
     */
    public function testAssert(string|object $class, string $method): void
    {
        static::expectNotToPerformAssertions();

        (new HasProtectedMethod($method))
            ->assert($class);
    }

    /**
     * @covers ::__construct()
     * @covers ::assert()
     * @dataProvider provideFailure()
     * @dataProvider provideInvalid()
     */
    public function testAssertException(string|object $class, string $method, string $message): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage($message);

        (new HasProtectedMethod($method))
            ->assert($class);
    }

    /**
     * @covers ::__construct()
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testInvoke(string|object $class, string $method): void
    {
        static::assertTrue(
            (new HasProtectedMethod($method))($class),
        );
    }

    /**
     * @covers ::__construct()
     * @covers ::__invoke()
     * @dataProvider provideFailure()
     */
    public function testInvokeFailure(string|object $class, string $method): void
    {
        static::assertFalse(
            (new HasProtectedMethod($method))($class),
        );
    }

    /** @return array<array{0:object|class-string, 1:string}> */
    public static function provideSuccess(): array
    {
        return [
            [StubClass::class, 'protectedMethod'],
            [new StubClass(), 'protectedMethod'],
            [ProtectedConstructorStub::class, '__construct'],
        ];
    }

    /** @return array<array{0:class-string, 1:string, 2:string}> */
    public static function provideFailure(): array
    {
        return [
            [
                StubClass::class,
                'publicMethod',
                'Expected "' . StubClass::class . '" to have a protected method named "publicMethod".',
            ],
            [
                StubClass::class,
                'privateMethod',
                'Expected "' . StubClass::class . '" to have a protected method named "privateMethod".',
            ],
            [
                StubClass::class,
                'nonExistentMethod',
                'Expected "' . StubClass::class . '" to have a protected method named "nonExistentMethod".',
            ],
            [
                StubClass::class,
                '__construct',
                'Expected "' . StubClass::class . '" to have a protected method named "__construct".',
            ],
            [
                PrivateConstructorStub::class,
                '__construct',
                'Expected "' . PrivateConstructorStub::class . '" to have a protected method named "__construct".',
            ],
        ];
    }

    /** @return array<array{0:string, 1:string, 2:string}> */
    public static function provideInvalid(): array
    {
        return [
            [
                'RandomString',
                'protectedMethod',
                'Value must be either class, interface or object. Got "RandomString"',
            ],
        ];
    }
}
