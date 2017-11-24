<?php

namespace RoyScheepens\HexonExport\Models;

use Illuminate\Database\Eloquent\Model;

class Occasion extends Model
{
    /**
     * The table name
     * todo: make this a config setting
     * @var string
     */
    protected $table = 'hexon_occasions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // todo: add more
        'hexon_id'
    ];

    /**
     * Relations
     * ----------------------------------------
     */

    public function images()
    {
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccassionImage');
    }

    public function options()
    {
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccassionOption');
    }

    /**
     * Scopes
     * ----------------------------------------
     */

    /**
     * Returns only occasions that are sold
     * @param  Builder $query The query builder instance
     * @return Builder
     */
    public function scopeSold($query)
    {
        // todo: check
        return $query->where('price', 0);
    }

    /**
     * Returns only occasions that are not sold
     * @param  Builder $query The query builder instance
     * @return Builder
     */
    public function scopeNotSold($query)
    {
        // todo: check
        return $query->where('price >', 0);
    }

}
