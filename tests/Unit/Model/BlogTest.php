<?php

namespace Tests\Unit\Model;

use App\Model\Blog;
use Tests\TestCase;

class BlogTest extends TestCase
{

    private $blog;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->isLocal()) {
            $this->markTestSkipped('db connection test is not enabled.');
        }
        $this->blog = (new Blog);
    }

    /**
     *
     */
    public function testBloglist()
    {
        $this->assertGreaterThan(0, $this->blog->bloglist()->count());
    }

    /**
     *
     */
    public function testGetExperienceData()
    {
        $this->assertGreaterThan(0, $this->blog->getExperienceData(['offset' => 1])->count());
        $this->assertGreaterThan(0, $this->blog->getExperienceData([])->count());
    }

    /**
     *
     */
    public function testGetExperienceCategoryData()
    {
        $this->assertGreaterThan(0, $this->blog->getExperienceCategoryData([
            'category_id' => 8,
            'offset'      => 0,
            'open_flg'    => 1,
            'type'        => 2,
        ])['count']);
    }

    /**
     *
     */
    public function testGetBlogDataCount()
    {
        $this->assertGreaterThan(0, $this->blog->getBlogDataCount([
            'type'     => 2,
            'open_flg' => 1,
            'offset'   => 0,
        ]));
    }
}
