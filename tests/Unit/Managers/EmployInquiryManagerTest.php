<?php

namespace Tests\Unit\Managers;

use App\Managers\EmployInquiryManager;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class EmployInquiryManagerTest extends TestCase
{

    private $employInquiryManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->employInquiryManager = (new EmployInquiryManager);
    }

    /**
     *
     */
    public function testCreateEntryEmployInquiry()
    {
        $this->employInquiryManager->createEntryEmployInquiry([
            'action'         => 'testCreateEntryEmployInquiry',
            'company_name'   => '【テスト】青木',
            'division_name'  => '【テスト】青木部署名',
            'name_kan'       => '【テスト】青木',
            'name_cana'      => 'フリガナ',
            'zip'            => '1210815',
            'addr1'          => 26,
            'addr2'          => 26021,
            'addr3'          => '島根',
            'tel'            => '09011112222',
            'mail'           => 'futoshi-aoki@bm-sms.co.jp',
            'inquiry'        => 1,
            'inquiry_detail' => 'フリーコメント',
        ],
            null,
            [
                'action1' => '',
                'action2' => '',
            ]);
    }

    /**
     *
     */
    public function testCreateOptoutEmployInquiry()
    {
        $this->employInquiryManager->createOptoutEmployInquiry([
            'mail'        => 'futoshi-aoki@bm-sms.co.jp',
            'stop_reason' => '充足している',
        ],
            null,
            [
                'action1' => '',
                'action2' => '',
            ]);
    }
}
