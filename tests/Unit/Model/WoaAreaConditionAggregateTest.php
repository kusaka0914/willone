<?php

namespace Tests\Unit\Model;

use App\Model\WoaAreaConditionAggregate;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaAreaConditionAggregateTest extends TestCase
{

    private $woaAreaConditionAggregate;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaAreaConditionAggregate = (new WoaAreaConditionAggregate);
    }

    /**
     *
     */
    public function testGetAreaData()
    {
        $this->woaAreaConditionAggregate->getAreaData([
            'addr0_id'   => 2,
            'job_type'   => 'judoseifukushi',
            'search_key' => 'pref',
            'conditions' => 'pref',
        ]);
    }

    /**
     *
     */
    public function testGetAggregateData()
    {
        $this->woaAreaConditionAggregate->getAggregateData([
            'job_type'   => 'judoseifukushi',
            'addr1'      => 26,
            'conditions' => 'pref',
        ]);
    }
}
