<?php

namespace Tests\Unit\Model;

use App\Model\ParameterMaster;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class ParameterMasterTest extends TestCase
{

    private $parameterMaster;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->parameterMaster = (new ParameterMaster);
    }

    /**
     *
     */
    public function testGetSyokusyuText()
    {
        $this->parameterMaster->getSyokusyuText([1]);
    }

    /**
     *
     */
    public function testGetSyokusyuType()
    {
        $this->parameterMaster->getSyokusyuType(['整体師・セラピスト', '柔道整復師']);
    }

    /**
     *
     */
    public function testGetExperienceParameterData()
    {
        $this->parameterMaster->getExperienceParameterData(['genre_id' => 2, 'value2' => 'judoseifukushi']);
    }

    /**
     *
     */
    public function testGetCategory()
    {
        $this->parameterMaster->getCategory(['genre_id' => 2, 'value2' => 'judoseifukushi']);
    }
}
