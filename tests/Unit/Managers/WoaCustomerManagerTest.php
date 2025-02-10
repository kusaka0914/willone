<?php

namespace Tests\Unit\Managers;

use App\Managers\WoaCustomerManager;
use App\Model\WoaCustomer;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class WoaCustomerManagerTest extends TestCase
{

    private $woaCustomerManager;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->woaCustomerManager = (new WoaCustomerManager);
    }

    /**
     *
     */
    public function testRegistWoaCustomerFromApi()
    {
        $id = $this->woaCustomerManager->registWoaCustomer([
            'name_kan'             => 'registWoaCustomer name_kan',
            'name_cana'            => 'registWoaCustomer name_cana',
            'birth'                => 'registWoaCustomer birth',
            'addr1'                => 26,
            'addr2'                => 26001,
            'addr3'                => 'registWoaCustomer addr3',
            'tel'                  => 'registWoaCustomer tel',
            'mail'                 => 'registWoaCustomer mail',
            'license'              => 'registWoaCustomer license',
            'req_emp_type'         => 'registWoaCustomer req_emp_type',
            'req_date'             => 'registWoaCustomer req_date',
            'retirement_intention' => 'registWoaCustomer retirement_intention',
            'graduation_year'      => 'registWoaCustomer graduation_year',
            'src_site_name'        => 'registWoaCustomer src_site_name',
            'src_customer_id'      => 'registWoaCustomer src_customer_id',
            'action'               => 'registWoaCustomer action',
            'action_first'         => 'registWoaCustomer action_first',
            'template_id'          => 'registWoaCustomer template_id',
            'branch'               => 'registWoaCustomer branch',
            'ip'                   => 'registWoaCustomer ip',
            'ua'                   => 'registWoaCustomer ua',
            'entry_memo'           => 'registWoaCustomer entry_memo',
            'salesforce_flag'      => 0,
        ], true);
        WoaCustomer::where('id', $id)->delete();
    }

    /**
     *
     */
    public function testRegistWoaCustomer()
    {
        $id = $this->woaCustomerManager->registWoaCustomer([
            'name_kan'                  => 'registWoaCustomer name_kan',
            'name_cana'                 => 'registWoaCustomer name_cana',
            'birth'                     => 'registWoaCustomer birth',
            'zip'                     => '1100001',
            'addr1'                     => 26,
            'addr2'                     => 26001,
            'addr3'                     => 'registWoaCustomer addr3',
            'mob_phone'                 => '09011112222',
            'mail'                      => 'registWoaCustomer@mail.co.jp',
            'license_text'              => 'registWoaCustomer license',
            'req_emp_type_text'         => 'registWoaCustomer req_emp_type',
            'req_date_text'             => 'registWoaCustomer req_date',
            'retirement_intention_text' => 'registWoaCustomer retirement_intention',
            'graduation_year'           => 'registWoaCustomer graduation_year',
            'entry_order'               => 'registWoaCustomer entry_order',
            'introduce_name'            => 'registWoaCustomer introduce_name',
            'cp_sfid'                   => 'registWoaCustomer cp_sfid',
            'action'                    => 'registWoaCustomer action',
            'action_first'              => 'registWoaCustomer action_first',
            'entry_category_manual'     => 'registWoaCustomer entry_category_manual',
            't'                         => 'registWoaCustomer template_id',
            'branch'                    => 'registWoaCustomer branch',
            'ip'                        => 'registWoaCustomer ip',
            'ua'                        => 'registWoaCustomer ua',
            'entry_memo'                => 'registWoaCustomer entry_memo',
            'agreement_flag'            => 1,
            'moving_flg'                => 'registWoaCustomer moving_flg',
        ], false);
        WoaCustomer::where('id', $id)->delete();
    }
}
