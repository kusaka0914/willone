<?php

namespace Tests\Unit\Model;

use App\Model\Kaitou;
use Tests\TestCase;

// 取り合えずSQLの疎通確認だけで、assertの確認は未
class KaitouTest extends TestCase
{

    private $kaitou;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->kaitou = (new Kaitou);
    }

    /**
     *
     */
    public function testGet()
    {
        $this->kaitou->get(1);
    }

    /**
     *
     */
    public function testGetList()
    {
        $this->kaitou->getList();
    }

    /**
     *
     */
    public function testRemove()
    {
        $this->kaitou->remove(1);
    }
}
