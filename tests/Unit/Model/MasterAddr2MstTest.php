<?php

namespace Tests\Unit\Model;

use App\Model\MasterAddr2Mst;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class MasterAddr2MstTest extends TestCase
{

    private $masterAddr2Mst;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->masterAddr2Mst = (new MasterAddr2Mst);
    }

    /**
     *
     */
    public function testMasterAddr1()
    {
        $this->masterAddr2Mst->masterAddr1();
    }

    /**
     *
     */
    public function testGetCityNames()
    {
        $this->masterAddr2Mst->getCityNames();
    }

    /**
     *
     */
    public function testGetAddr2idByName()
    {
        $this->masterAddr2Mst->getAddr2idByName('札幌市中央区');
    }

    /**
     *
     */
    public function testGetAddr2ById()
    {
        $this->masterAddr2Mst->getAddr2ById(11001);
    }

    /**
     *
     */
    public function testGetAddr2WithAddr1ByIds()
    {
        $this->masterAddr2Mst->getAddr2WithAddr1ByIds([11001, 11002]);
    }

    /**
     *
     */
    public function testGetAddr2List()
    {
        $this->masterAddr2Mst->getAddr2List();
    }

    /**
     *
     */
    public function testGetAddr2ListByAddr1Id()
    {
        $this->masterAddr2Mst->getAddr2ListByAddr1Id(11);
    }

    /**
     *
     */
    public function testGetAddr2ByRoma()
    {
        $this->masterAddr2Mst->getAddr2ByRoma('hokkaido', 'sapporoshichuoku');
    }
}
