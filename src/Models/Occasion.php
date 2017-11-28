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
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that are appended to the model
     *
     * @var array
     */
    protected $appends = [
        // 'description',
        'name'
    ];

    /**
     * Which attributes to parse as dates
     *
     * @var array
     */
    protected $dates = [
        'sold_at'
    ];


    /**
     * Which attributes to cast
     *
     * @var array
     */
    protected $casts = [
        'build_year' => 'int'
    ];


    /**
     * Relations
     * ----------------------------------------
     */

    public function images()
    {
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccassionImage');
    }

    public function accessories()
    {
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccassionAccessory');
    }

    /**
     * Attributes
     * ----------------------------------------
     */

    public function getNameAttribute()
    {
        return implode(' ', [
            $this->brand,
            $this->model
        ]);
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
