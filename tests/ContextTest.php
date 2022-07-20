<?php

use PHPUnit\Framework\TestCase;

use App\EvolvContext\Context;
require_once __DIR__ . '/../App/EvolvContext.php';


class ContextTest extends TestCase {
    protected Context $context;

    public function setUp(): void {
        $this->context = new Context();
        $this->context->initialize('user_id');
    }

    /**
     * @group context_set
     */
    public function testNewKeyIsAddedToLocalContext() {
        // Act
        $this->context->set('native.newUser', true, true);

        // Assert
        $this->assertEquals($this->context->localContext, ['native' => ['newUser' => true]]);
    }

    /**
     * @group context_set
     */
    public function testNewKeyIsAddedToRemoteContext() {
        // Act
        $this->context->set('native.newUser', true);

        // Assert
        $this->assertEquals($this->context->remoteContext, ['native' => ['newUser' => true]]);
    }

    /**
     * @group context_remove
     */
    public function testKeyIsRemovedFromLocalContext() {
        // Arrange
        $this->context->set('native', ['newUser' => true, 'pdp' => true], true);

        // Act
        $removed = $this->context->remove('native.newUser');

        // Assert
        $this->assertTrue($removed);
        $this->assertEquals($this->context->localContext, ['native' => ['pdp' => true]]);
    }

    /**
     * @group context_remove
     */
    public function testKeyIsRemovedFromRemoteContext() {
        // Act
        $this->context->set('native', ['newUser' => true, 'pdp' => true]);

        // Act
        $removed = $this->context->remove('native.newUser');

        // Assert
        $this->assertTrue($removed);
        $this->assertEquals($this->context->remoteContext, ['native' => ['pdp' => true]]);
    }
}
