<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Cache;
use Config;
use DB;

class TagsController extends Controller
{
    /**
     * Get all the existing tags JSON in sorted order
     * method: get
     * route: api/tags
     * @return json {"data": {tags": [tag1, tag2, ...]}}
     */
    public function index()
    {
        $output = Cache::remember('tags', Config::get('cache.api.timeout'), function () {
            return ['data' => ['tags' => $this->getSortedTags()]];
        });

        return response()->json($output);
    }

    /**
     * Get a tag JSON by a given tag_id
     * method: get
     * route: api/tags/<tag_id>
     * @param  int $tag_id
     * @return json {"success": true or false, "data": {"tag": tag}};
     */
    public function show($tag_id)
    {
        $tag = Tag::find($tag_id);
        if ($tag == null) {
            return response()->json(["success" => false]);
        }
        return response()->json(["success" => true, "data" => ["tag" => $tag]]);
    }

    /**
     * Get the top n tags JSON sorted by number of associated posts
     * method: get
     * route: api/tags/top/<num> (if not clashes with api below)
     * @param int $num
     * @return json {"data": {"tags": [tag1, tag2, ...]}}
     */
    public function topNTags($num = 5)
    {
        $num = ($num > 20) ? 5 : $num;

        $output = Cache::remember('top_' . $num . '_tags', Config::get('cache.api.timeout'), function () {
            $tags = DB::table('tags')
                ->join('confession_tags', 'tags.confession_tag_id', '=', 'confession_tags.confession_tag_id')
                ->join('confessions', 'confessions.confession_id', '=', 'confession_tags.confession_id')
                ->where('confession_tag', 'REGEXP', '#[a-z]+')
                ->select(\DB::raw('tags.*, (confessions.fb_like_count + (confessions.fb_comment_count * 2)) / POW(DATEDIFF(NOW(), confessions.status_updated_at) + 2, 1.8) AS `popularity_rating`'))
                ->groupBy('confession_tag_id')
                ->orderBy('popularity_rating', 'DESC')
                ->orderBy('status_updated_at', 'DESC')
                ->limit(5)
                ->get();

            return ['data' => ['tags' => $tags]];
        });

        return response()->json($output);
    }

    /**
     * Get all tags in sorted order according to number of posts a tag belongs to.
     *
     * @return array [tag1, tag2, ...]
     */
    protected function getSortedTags()
    {
        $tags = Tag::where('confession_tag', 'REGEXP', '#[a-z]+')->get()
            ->filter(function ($tag) {
                return $tag->confessions()->approved()->count() > 0;
            })
            ->sortBy(function ($tag) {
                return -$tag->confessions()->approved()->count();
            });

        return array_values($tags->toArray());
    }
}
