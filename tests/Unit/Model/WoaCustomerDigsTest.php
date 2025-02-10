<?php

namespace Tests\Unit\Model;

use App\Model\WoaCustomerDigs;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaCustomerDigsTest extends TestCase
{

    private $woaCustomerDigs;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaCustomerDigs = (new WoaCustomerDigs);
    }

    /**
     *
     */
    public function testGetSfLinkData()
    {
        $this->woaCustomerDigs->getSfLinkData(10);
    }

    /**
     *
     */
    public function testUpdateSfFlag()
    {
        $this->woaCustomerDigs->updateSfFlag(11, 1);
    }

}
