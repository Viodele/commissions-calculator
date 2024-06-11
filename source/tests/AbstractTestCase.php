<?php declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @throws Exception
     */
    final protected function createFinalMock(string $originalClassName): MockObject
    {
        return $this->createMock($originalClassName);
    }

    final protected function createConfiguredFinalMock(): MockObject
    {

    }
}
