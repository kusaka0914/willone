<?php

namespace Tests\Unit\Managers;

use App\Managers\WoaOpportunityManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaOpportunityManagerTest extends TestCase
{

    private $woaOpportunityManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaOpportunityManager = (new WoaOpportunityManager);
    }

    /**
     *
     */
    public function testGetNearOfficeByCustomer()
    {
        $this->woaOpportunityManager->getNearOfficeByCustomer(
            (object) [
                'longitude' => 128426600,
                'latitude'  => 503166360,
            ],
            10,
            true,
            26,
            10
        );
    }
}
