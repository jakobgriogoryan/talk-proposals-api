<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Proposal;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tag model unit tests.
 */
class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test tag has proposals relationship.
     */
    public function test_tag_has_proposals(): void
    {
        $tag = Tag::factory()->create();
        $proposal = Proposal::factory()->create();
        $proposal->tags()->attach($tag->id);

        $this->assertTrue($tag->proposals->contains($proposal));
    }

    /**
     * Test tag search by name scope.
     */
    public function test_tag_search_by_name_scope(): void
    {
        Tag::factory()->create(['name' => 'Laravel']);
        Tag::factory()->create(['name' => 'Vue.js']);
        Tag::factory()->create(['name' => 'Laravel Testing']);

        $results = Tag::searchByName('Laravel')->get();

        $this->assertCount(2, $results);
    }

    /**
     * Test tag name is unique.
     */
    public function test_tag_name_uniqueness(): void
    {
        Tag::factory()->create(['name' => 'PHP']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Tag::factory()->create(['name' => 'PHP']);
    }
}
