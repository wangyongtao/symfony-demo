<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase
{
    public function testSomething(): void
    {
        $input = '1,2,3,4,5';
        $filtered = get_valid_user_types(explode(',', $input));
//        var_dump($filtered);
        $this->assertNotEmpty($filtered);
        $this->assertEquals(3, count($filtered));

        // failed test case
        $input = 'asd,4,5';
        $filtered = get_valid_user_types(explode(',', $input));
        $this->assertEquals(0, count($filtered));
    }
}
