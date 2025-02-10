<?php

namespace Tests\Unit\Managers;

use App\Managers\SelectBoxManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class SelectBoxManagerTest extends TestCase
{

    private $selectBoxManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->selectBoxManager = (new SelectBoxManager);
    }

    /**
     *
     */
    public function testSysPrefectureSb()
    {
        $this->selectBoxManager->sysPrefectureSb();
    }

    /**
     *
     */
    public function testSysCitySb()
    {
        $this->selectBoxManager->sysCitySb(26);
    }

    /**
     *
     */
    public function testGetPrefCityListByZipCode()
    {
        $this->selectBoxManager->getPrefCityListByZipCode('1100001');
    }

    /**
     *
     */
    public function testGetPrefCityListAllByZipCode()
    {
        $this->selectBoxManager->getPrefCityListAllByZipCode('1100001');
    }

    /**
     *
     */
    public function testSysLicenseMstSbNew()
    {
        $this->selectBoxManager->sysLicenseMstSbNew();
    }

    /**
     *
     */
    public function testSysEmpTypeMstSb()
    {
        $this->selectBoxManager->sysEmpTypeMstSb();
    }

    /**
     *
     */
    public function testSysReqdateMstSb()
    {
        $this->selectBoxManager->sysReqdateMstSb();
    }

    /**
     *
     */
    public function testSysBirthYearSb()
    {
        $this->selectBoxManager->sysBirthYearSb();
    }

    /**
     *
     */
    public function testSysGraduationYearSb()
    {
        $this->selectBoxManager->sysGraduationYearSb();
    }

    /**
     *
     */
    public function testGetJobNameById()
    {
        $this->selectBoxManager->getJobNameById(1);
    }
}
