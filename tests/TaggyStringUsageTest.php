<?php 

class TaggyStringUsageTest extends TestCase
{
    protected $lesson;

    public function setUp()
    {
        parent::setUp();

        foreach (["Laravel", "PHP", "Redis", "Postgres", "Mysql"] as $tag) {
            TagStub::create([
                "name" => $tag,
                "slug" => str_slug($tag),
                "count" => 0
            ]);
        }

        $this->lesson = \LessonStub::create([
            "title" => "A lesson title"
        ]);

    }

    /** @test */
    public function can_tag_a_lesson()
    {
        $this->lesson->tag(["Laravel", "PHP"]);

        $this->assertCount(2, $this->lesson->tags);
    }
}
