<?php

namespace Tests\Unit\Model;

use App\Model\MasterEntryCourseMst;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class MasterEntryCourseMstTest extends TestCase
{

    private $masterEntryCourseMst;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->masterEntryCourseMst = (new MasterEntryCourseMst);
    }

    /**
     *
     */
    public function testGetEntryCategory()
    {
        $this->masterEntryCourseMst->getEntryCategory('mbor-sptop');
    }
}
