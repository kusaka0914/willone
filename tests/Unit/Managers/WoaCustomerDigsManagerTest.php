<?php

namespace Tests\Unit\Managers;

use App\Managers\WoaCustomerDigsManager;
use App\Model\WoaCustomerDigs;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaCustomerDigsManagerTest extends TestCase
{

    private $woaCustomerDigsManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaCustomerDigsManager = (new WoaCustomerDigsManager);
    }

    /**
     *
     */
    public function testRegistWoaCustomerDigs()
    {
        $id = $this->woaCustomerDigsManager->registWoaCustomerDigs([
            'salesforce_id'             => 'registWoaCustomerDigs salesforce_id',
            'req_emp_type_text'         => 'registWoaCustomerDigs req_emp_type_text',
            'req_date_text'             => 'registWoaCustomerDigs req_date_text',
            'retirement_intention_text' => 'registWoaCustomerDigs retirement_intention_text',
            'entry_route'               => 'registWoaCustomerDigs  entry_route',
            'mail'                      => 'registWoaCustomerDigs mail',
            'action_first'              => 'registWoaCustomerDigs action_first',
            'action'                    => 'registWoaCustomerDigs action',
            't'                         => 'registWoaCustomerDigs t',
            'ip'                        => 'registWoaCustomerDigs ip',
            'ua'                        => 'registWoaCustomerDigs ua',
            'web_customer_id'           => 'registWoaCustomerDigs web_customer_id',
        ]);
        WoaCustomerDigs::where('id', $id)->delete();
    }
}
