<?php

namespace Tests\Unit\Model;

use App\Model\WoaCustomer;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaCustomerTest extends TestCase
{

    private $woaCustomer;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaCustomer = (new WoaCustomer);
    }

    /**
     *
     */
    public function testGetSfLinkData()
    {
        $this->woaCustomer->getSfLinkData(10);
    }

    /**
     *
     */
    public function testUpdateSfFlag()
    {
        $this->woaCustomer->updateSfFlag(11, 1);
    }

    /**
     *
     */
    public function testGetCustomer()
    {
        $this->woaCustomer->getCustomer(10);
    }
}
