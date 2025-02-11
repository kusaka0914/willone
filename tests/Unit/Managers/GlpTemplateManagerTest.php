<?php

namespace Tests\Unit\Managers;

use App\Managers\GlpTemplateManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class GlpTemplateManagerTest extends TestCase
{

    private $glpTemplateManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->glpTemplateManager = (new GlpTemplateManager);
    }

    /**
     *
     */
    public function testGetTemplateName()
    {
        $this->glpTemplateManager->getTemplateName('woa_001', false);
    }
}
