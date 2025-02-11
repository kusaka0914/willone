<?php

namespace Tests\Unit\Model;

use App\Model\EmployInquiry;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class EmployInquiryTest extends TestCase
{

    private $employInquiry;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->employInquiry = (new EmployInquiry);
    }

    /**
     *
     */
    public function testGetSfLinkDataForAccountMail()
    {
        $this->employInquiry->getSfLinkDataForAccountMail(1);
    }

    /**
     *
     */
    public function testUpdateSfFlag()
    {
        $this->employInquiry->updateSfFlag(1, 1);
    }
}
