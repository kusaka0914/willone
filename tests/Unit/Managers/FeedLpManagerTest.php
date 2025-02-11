<?php

namespace Tests\Unit\Managers;

use App\Managers\FeedLpManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class FeedLpManagerTest extends TestCase
{

    private $feedLpManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->feedLpManager = (new FeedLpManager);
    }

    /**
     *
     */
    public function testGetABTestPattern()
    {
        $this->feedLpManager->getABTestPattern('stanby');
    }
}
