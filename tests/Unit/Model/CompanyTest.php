<?php

namespace Tests\Unit\Model;

use App\Model\Company;
use Tests\TestCase;

class CompanyTest extends TestCase
{

    private $company;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->company = (new Company);
    }

    /**
     *
     */
    public function testGetCompanyById()
    {
        $this->assertNull($this->company->getCompanyById());
        $id = $this->company->insertGetId(['company_name' => 'testGetCompanyById']);
        $this->assertSame($id, $this->company->getCompanyById($id)->id);
        $this->company->where('id', $id)->delete();
    }

    /**
     *
     */
    public function testGetCompanyList()
    {
        $this->assertGreaterThan(0, count($this->company->getCompanyList()));
    }

    /**
     *
     */
    public function testUpdateSfOfficeId()
    {
        $this->assertSame(0, $this->company->updateSfOfficeId(0, ['sf_office_id' => 'test']));
        $this->assertSame(0, $this->company->updateSfOfficeId(1, []));
        $company = $this->company->first();
        $this->company->updateSfOfficeId($company->id, ['sf_office_id' => 'sf_office_id Unit Test']);
        $companyUpdate = $this->company->select('sf_office_id')->where('id', $company->id)->first();
        $this->assertSame('sf_office_id Unit Test', $companyUpdate->sf_office_id);
        $companyUpdate->sf_office_id = $company->sf_office_id;
        $companyUpdate->save();
    }

    /**
     *
     */
    public function testInsertSfOfficeId()
    {
        $this->assertSame(0, $this->company->insertSfOfficeId([]));
        $id = $this->company->insertSfOfficeId(['sf_office_id' => 'insertSfOfficeId Unit Test']);
        $this->assertGreaterThan(0, $id);
        $this->company->where('id', $id)->delete();
    }

    /**
     *
     */
    public function testGetDuplicateList()
    {
        $company = $this->company->where('del_flg', 0)->first();
        $id = $this->company->insertGetId(['company_name' => $company->company_name]);
        $this->assertGreaterThan(0, count($this->company->getDuplicateList()));
        $this->company->where('id', $id)->delete();
    }

    /**
     *
     */
    public function testDeleteDuplicateName()
    {
        $company = $this->company->where('del_flg', 0)->first();
        $id1 = $this->company->insertGetId(['company_name' => $company->company_name]);
        $id2 = $this->company->insertGetId(['company_name' => $company->company_name]);
        $this->assertGreaterThan(0, $this->company->deleteDuplicateName($company->company_name, $id2));
        $this->assertSame(1, Company::where([['company_name', $company->company_name], ['del_flg', 0]])->get()->count());
        $this->company->orWhere('id', $id1)->orWhere('id', $id2)->delete();
    }

    /**
     *
     */
    public function testGetJobCountList()
    {
        $this->assertGreaterThan(0, $this->company->getJobCountList()->count());
    }
}
