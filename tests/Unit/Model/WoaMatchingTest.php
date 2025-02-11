<?php

namespace Tests\Unit\Model;

use App\Model\WoaMatching;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaMatchingTest extends TestCase
{

    private $woaMatching;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaMatching = (new WoaMatching);
    }

    /**
     *
     */
    public function testRegistWoaMatching()
    {
        $id = $this->woaMatching->registWoaMatching([
            'salesforce_id' => 'RegistWoaMatching Unit Test salesforce_id',
            'orderId'       => 'RegistWoaMatching Unit Test orderId',
            'entryStatus'   => 'RegistWoaMatching Unit Test entryStatus',
            'action'        => 'RegistWoaMatching Unit Test action',
            'action_first'  => 'RegistWoaMatching Unit Test action_first',
            'mailmgzFlag'   => 1,
        ]);
        WoaMatching::where('id', $id)->delete();
    }

    /**
     *
     */
    public function testRegistWoaMatchingFromKurohon()
    {
        $id = $this->woaMatching->registWoaMatchingFromKurohon([
            'user'         => 'registWoaMatchingFromKurohon Unit Test user',
            'orderId'      => 'registWoaMatchingFromKurohon Unit Test orderId',
            'entryStatus'  => 'registWoaMatchingFromKurohon Unit Test entryStatus',
            'action'       => 'registWoaMatchingFromKurohon Unit Test action',
            'action_first' => 'registWoaMatchingFromKurohon Unit Test action_first',
            'mailmgzFlag'  => 1,
        ]);
        WoaMatching::where('id', $id)->delete();
    }

    /**
     *
     */
    public function testGetSfLinkData()
    {
        $this->woaMatching->getSfLinkData(339);
    }

    /**
     *
     */
    public function testUpdateSfFlag()
    {
        $this->woaMatching->updateSfFlag(339, 1);
    }
}
