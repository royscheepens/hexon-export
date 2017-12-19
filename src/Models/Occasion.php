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
        'name',
        'price_formatted',
        // 'description',
    ];

    /**
     * Which attributes to parse as dates
     *
     * @var array
     */
    protected $dates = [
        'apk_until',
        'sold_at'
    ];

    /**
     * Which attributes to cast
     *
     * @var array
     */
    protected $casts = [
        'build_year' => 'int',
        'sold' => 'boolean'
    ];

    /**
     * Route Binding
     * ----------------------------------------
     */

    public function getRouteKeyName()
    {
        // todo: make configurable
        return 'slug';
    }

    /**
     * Relations
     * ----------------------------------------
     */

    public function images()
    {
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccasionImage');
    }

    public function accessories()
    {
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccasionAccessory');
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

    public function getFullNameAttribute()
    {
        return implode(' ', [
            $this->brand,
            $this->model,
            $this->type
        ]);
    }

    public function getPriceFormattedAttribute()
    {
        return '€ ' . number_format($this->price, 0, ',', '.') . ',-';
    }

    public function getMileageFormattedAttribute()
    {
        $units = [
            'K' => 'km',
            'M' => 'm'
        ];

        return number_format($this->mileage, 0, ',', '.') . ' ' . $units[$this->mileage_unit];
    }

    public function getFuelTypeFormattedAttribute()
    {
        $types = [
            'B' => 'Benzine',
            'D' => 'Diesel',
            'L' => 'LPG',
            '3' => '', // todo
            'E' => 'Elektrisch',
            'H' => 'Waterstof',
            'C' => '', // todo
            'O' => '', // todo
        ];

        return $types[$this->fuel_type];
    }

    public function getTransmissionFormattedAttribute()
    {
        $types = [
            'H' => 'Handgeschakeld',
            'A' => 'Automaat',
            'S' => 'Sequentieel',
            'C' => '' // todo
        ];

        return $types[$this->transmission];
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
