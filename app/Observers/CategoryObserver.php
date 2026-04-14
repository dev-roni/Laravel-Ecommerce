<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        // যেসব child এই category-র parent ছিল
        // nullOnDelete() ইতিমধ্যে parent_id = NULL করে দিয়েছে
        // এখন শুধু level ঠিক করতে হবে

        Category::where('parent_id', null)
            ->where('level', '!=', 1)
            ->each(function ($child) {
                $this->recalculateLevel($child);
            });
    }

    // শুধু level ঠিক করতে 
    private function recalculateLevel(Category $category): void
    {
        $newOrder = Category::where('parent_id', $category->parent_id)
                        ->where('id', '!=', $category->id) // নিজেকে বাদ দিয়ে
                        ->max('order') + 1;

        $newLevel = $category->parent_id
            ? Category::find($category->parent_id)->level + 1
            : 1;

        if ($category->level !== $newLevel) {

            $category->update(['level' => $newLevel,'order' => $newOrder,]);

            // এই category-র children-ও ঠিক করো
            $category->children->each(fn($child) => $this->recalculateLevel($child));
        }
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
