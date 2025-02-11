<?php

namespace Tests\Unit\Model;

use App\Model\WoaOpportunity;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaOpportunityTest extends TestCase
{

    private $woaOpportunity;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaOpportunity = (new WoaOpportunity);
    }

    /**
     *
     */
    public function testGetJobIdList()
    {
        $this->woaOpportunity->getJobIdList();
    }

    /**
     *
     */
    public function testGetOrderInfoByJobId()
    {
        $this->woaOpportunity->getOrderInfoByJobId(10);
    }

    /**
     *
     */
    public function testGetAreaJobCount()
    {
        $this->woaOpportunity->getAreaJobCount(26, [1]);
    }

    /**
     *
     */
    public function testUpdateCompanyId()
    {
        $this->woaOpportunity->updateCompanyId('a2p2t0000000dLGAAY', 5861);
    }

    /**
     *
     */
    public function testNewjob()
    {
        $this->woaOpportunity->newjob(26, 26001, 1, ['test']);
    }

    /**
     *
     */
    public function testJobSearch()
    {
        $this->woaOpportunity->jobSearch([
            'addr1_id'       => 26,
            'addr2_id'       => 26002,
            'job_type_group' => 'judoseifukushi',
            'limit'          => 30,
            'offset'         => 0,
        ]);
    }

    /**
     *
     */
    public function testJobSearchCount()
    {
        $this->woaOpportunity->jobSearchCount([
            'addr1_id'       => 26,
            'addr2_id'       => 26002,
            'job_type_group' => 'judoseifukushi',
            'limit'          => 30,
            'offset'         => 0,
        ]);
    }

    /**
     *
     */
    public function testJobSearchAggregateCount()
    {
        $this->woaOpportunity->jobSearchAggregateCount([
            'addr1_id'       => 87,
            'groupby'        => 'addr2',
            'business'       => 'グループホーム',
            'job_type_group' => 'other',
        ]);
    }

    /**
     *
     */
    public function testGetFilteringListByAddr1()
    {
        $this->woaOpportunity->getFilteringListByAddr1(26);
    }

    /**
     *
     */
    public function testGetWoaOpportunityByJobId()
    {
        $this->woaOpportunity->getWoaOpportunityByJobId(3);
    }

    /**
     *
     */
    public function testGetAddr2CountOpportunityList()
    {
        $this->woaOpportunity->getAddr2CountOpportunityList(['addr1_id' => 25], 5);
    }

    /**
     *
     */
    public function testWolinkingData()
    {
        $this->woaOpportunity->wolinkingData(5041);
    }
}
