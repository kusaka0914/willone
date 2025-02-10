<?php

namespace Tests\Unit\Model;

use App\Model\MasterAddr1Mst;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class MasterAddr1MstTest extends TestCase
{

    private $masterAddr1Mst;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->masterAddr1Mst = (new MasterAddr1Mst);
    }

    /**
     *
     */
    public function testGetList()
    {
        $this->masterAddr1Mst->getList();
    }

    /**
     *
     */
    public function testGetPrefNames()
    {
        $this->masterAddr1Mst->getPrefNames();
    }

    /**
     *
     */
    public function testGetAddr1ById()
    {
        $this->masterAddr1Mst->getAddr1ById(26);
    }

    /**
     *
     */
    public function testGetAddr1idByName()
    {
        $this->masterAddr1Mst->getAddr1idByName('東京都');
    }

    /**
     *
     */
    public function testGetPrefIds()
    {
        $this->masterAddr1Mst->getPrefIds();
    }

    /**
     *
     */
    public function testGetArea()
    {
        $this->masterAddr1Mst->getArea(1);
    }

    /**
     *
     */
    public function testGetListPrefecture()
    {
        $this->masterAddr1Mst->getListPrefecture([11, 26]);
    }

    /**
     *
     */
    public function testGetAddr1ByRoma()
    {
        $this->masterAddr1Mst->getAddr1ByRoma('tokyo');
    }

    /**
     *
     */
    public function testGetJobCountList()
    {
        $this->masterAddr1Mst->getJobCountList('judoseifukushi');
    }
}
