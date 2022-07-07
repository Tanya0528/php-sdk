<?php

use PHPUnit\Framework\TestCase;

use App\EvolvContext\Context;
require_once __DIR__ . '/../App/EvolvContext.php';


class ContextTest extends TestCase {
    protected $context;

    public function setUp(): void {
        $this->context = new Context();
        $this->context->initialize('user_id');
    }

    public function testNewKeyIsAddedToLocalContext() {
        // Act
        $this->context->set('native.newUser', true, true);

        // Assert
        $this->assertEquals($this->context->getLocalContext(), ['native' => ['newUser' => true]]);
    }

    public function testNewKeyIsAddedToRemoteContext() {
        // Act
        $this->context->set('native.newUser', true);

        // Assert
        $this->assertEquals($this->context->getRemoteContext(), ['native' => ['newUser' => true]]);
    }

}
