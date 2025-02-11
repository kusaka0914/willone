<?php

namespace Tests\Unit\Managers;

use App\Managers\MasterManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class MasterManagerTest extends TestCase
{

    private $masterManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->masterManager = (new MasterManager);
    }

    /**
     *
     */
    public function testGetListReqDateById()
    {
        $this->masterManager->getListReqDateById(1);
    }

    /**
     *
     */
    public function testGetListEmpTypeById()
    {
        $this->masterManager->getListEmpTypeById(1);
    }

    /**
     *
     */
    public function testGetListLicenseByIds()
    {
        $this->masterManager->getListLicenseByIds([1, 2]);
    }

    /**
     *
     */
    public function testInsertMasterMst()
    {
        $this->masterManager->insertMasterMst('master_entry_course_mst',
            ['sfid' => 'a0R10000001ANmvEAG', 'query' => 'mbor-sptop', 'entry_category' => 'SEO']
        );
    }

    /**
     *
     */
    public function testGetJobtypeId()
    {
        $this->masterManager->getJobtypeId('柔道整復師');
    }
}
