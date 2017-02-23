<?php 

namespace Brychtaj\Taggy;

use Brychtaj\Taggy\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait TaggableTrait
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, "taggable");
    }

    public function tag($tags)
    {
        $this->addTags($this->getWorkableTags($tags));
    }

    public function untag($tags = null)
    {
        if ($tags === null) {
            $this->removeAllTags();
            return;
        }

        $this->removeTags($this->getWorkableTags($tags));
    }

    public function retag($tags)
    {
        $this->removeAllTags();
        $this->tag($tags);
    }

    protected function removeAllTags()
    {
        $this->removeTags($this->tags);
    }

    protected function removeTags(Collection $tags)
    {
        $this->tags()->detach($tags);

        foreach ($tags->where("count", ">", 0) as $tag) {
            $tag->decrement("count");
        }
    }

    protected function addTags(Collection $tags)
    {
        $sync = $this->tags()->syncWithoutDetaching($tags->pluck("id")->toArray());

        foreach (array_get($sync, "attached") as $attachedId) {
            $tags->where("id", $attachedId)->first()->increment("count");
        }
    }

    protected function getWorkableTags($tags)
    {
        if (is_array($tags)) {
            return $this->getTagModels($tags);
        }

        if ($tags instanceof Model) {
            return $this->getTagModels([$tags->slug]);
        }

        return $this->filterTagCollection($tags);
    }

    protected function filterTagCollection(Collection $tags)
    {
        return $tags->filter(function($tag){
            return $tag instanceof Model;
        });
    }

    protected function getTagModels($tags)
    {
        return Tag::whereIn("slug", $this->normaliseTagNames($tags))->get();
    }

    protected function normaliseTagNames(array $tags)
    {
        return array_map(function($tag){
            return str_slug($tag);
        } , $tags);
    }
}
