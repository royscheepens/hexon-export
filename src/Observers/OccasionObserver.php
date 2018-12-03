<?php

namespace RoyScheepens\HexonExport\Observers;

use RoyScheepens\HexonExport\Models\Occasion;
use Illuminate\Support\Collection;

use Storage;

class OccasionObserver
{
    /**
     * Adds a unique slug to the resource
     * @param  Occasion $occasion The resource to add the slug to
     * @return void
     */
    public function creating(Occasion $occasion)
    {
        if(! $occasion->slug)
        {
            $slug = str_slug($occasion->name_full);

            $slug = $this->makeSlugUnique($occasion, $slug);

            $occasion->slug = $slug;
        }
    }

    /**
     * Deletes occasion related records
     *
     * @param  Occasion  $occasion
     * @return void
     */
    public function deleting(Occasion $occasion)
    {
        $occasion->accessories()->delete();
        $occasion->accessorygroups()->delete();

        $occasion->images()->delete();

        // Delete the directory that contains all images of this occasion
        Storage::disk('public')->deleteDirectory(
            config('hexon-export.images_storage_path') . $occasion->resource_id
        );
    }

    /**
     * Enforces unique slugs
     * Code heavily borrowed from https://github.com/cviebrock/eloquent-sluggable
     * @param  Occasion $occasion   The resource to add the slug to
     * @param  string $slug         The slug to make unique
     * @return string               The unique slug
     */
    protected function makeSlugUnique($occasion, $slug)
    {
        $list = Occasion::all()->pluck('slug', $occasion->getKeyName());

        $separator = '-';

        // If we have no slugs already, or the slug we want to
        // check is not present, then return it
        if(
            $list->count() === 0 ||
            $list->contains($slug) === FALSE
        ) {
            return $slug;
        }

        // If the slug is in the list, but it is of our own model,
        // then also just return it
        if ($list->has($occasion->getKey()))
        {
            $currentSlug = $list->get($occasion->getKey());

            if (
                $currentSlug === $slug ||
                strpos($currentSlug, $slug) === 0
            ) {
                return $currentSlug;
            }
        }

        // Add a suffix to the slug, e.g. '-1'
        $suffix = $this->generateSuffix($slug, $separator, $list, $occasion);

        return $slug . $separator . $suffix;
    }

    /**
     * Generate a unique suffix for the given slug (and list of existing, "similar" slugs.
     *
     * @param string $slug
     * @param string $separator
     * @param \Illuminate\Support\Collection $list
     * @return string
     */
    protected function generateSuffix(string $slug, string $separator, Collection $list, Occasion $occasion): string
    {
        $len = strlen($slug . $separator);

        // If the slug already exists, but belongs to our model, return the current suffix.
        if ($list->search($slug) === $occasion->getKey())
        {
            $suffix = explode($separator, $slug);
            return end($suffix);
        }

        $list->transform(function ($value, $key) use ($len)
        {
            return (int)substr($value, $len);
        });

        // Find the highest value and return one greater.
        return $list->max() + 1;
    }

}