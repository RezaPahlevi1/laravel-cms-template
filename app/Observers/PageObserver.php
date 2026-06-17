<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function saving(Page $page): void
    {
        $this->updateFullPathAndDepth($page);
    }

    public function saved(Page $page): void
    {
        // Update full_path semua children jika slug berubah
        if ($page->wasChanged('slug') || $page->wasChanged('parent_id')) {
            $this->updateChildrenPaths($page);
        }

        Cache::forget('nav_tree');
    }

    public function deleted(Page $page): void
    {
        Cache::forget('nav_tree');
    }

    private function updateFullPathAndDepth(Page $page): void
    {
        if ($page->parent_id === null) {
            $page->full_path = '/' . $page->slug;
            $page->depth     = 0;
            return;
        }

        $parent = Page::find($page->parent_id);

        if (!$parent) {
            $page->full_path = '/' . $page->slug;
            $page->depth     = 0;
            return;
        }

        // Enforce max depth 2 (0-indexed) = 3 level max
        if ($parent->depth >= 2) {
            $page->parent_id  = $parent->parent_id;
            $page->depth      = $parent->depth;
            $page->full_path  = $parent->full_path;
            return;
        }

        $page->full_path = $parent->full_path . '/' . $page->slug;
        $page->depth     = $parent->depth + 1;
    }

    private function updateChildrenPaths(Page $page): void
    {
        foreach ($page->children as $child) {
            $child->full_path = $page->full_path . '/' . $child->slug;
            $child->depth     = $page->depth + 1;
            $child->saveQuietly(); // saveQuietly agar tidak trigger observer berulang

            // Rekursif untuk grandchildren
            if ($child->children->isNotEmpty()) {
                $this->updateChildrenPaths($child);
            }
        }
    }
}