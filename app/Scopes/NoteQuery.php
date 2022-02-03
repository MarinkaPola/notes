<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;

class NoteQuery
{
    /**
     * @param Builder $query
     * @param string|null $search
     * @return Builder
     */
    public static function search(Builder $query, ?string $search): Builder
    {
        if ($search) {
            $search = '%' . $search . '%';
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', $search)
                    ->orWhere('text', 'like', $search)
                    ->orWhereHas('user', static function($query) use ($search) {
                        $query->where('name', 'like', $search);
            });
        });}
        return $query;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
     public static function published(Builder $query): Builder
      {
          return $query->where('visibility', 1);
      }
}
