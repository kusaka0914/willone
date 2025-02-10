<?php

namespace Tests\Unit\Managers;

use App\Managers\AreaManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class AreaManagerTest extends TestCase
{

    private $areaManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->areaManager = (new AreaManager);
    }

    /**
     *
     */
    public function testGetListAddr1ById()
    {
        $this->areaManager->getListAddr1ById(26);
    }

    /**
     *
     */
    public function testGetListAddr2ById()
    {
        $this->areaManager->getListAddr2ById(11001);
    }

    /**
     *
     */
    public function testFindNearCities()
    {
        $this->areaManager->findNearCities(11001);
    }
}
