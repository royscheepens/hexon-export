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

    public function image()
    {
        return $this->hasOne('RoyScheepens\HexonExport\Models\OccasionImage')->orderBy('id', 'asc');
    }

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

    public function getNameFullAttribute()
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

    public function getColorFormattedAttribute()
    {
        return ucwords(mb_strtolower($this->color));
    }

    public function getMileageFormattedAttribute()
    {
        $units = [
            'K' => 'km',
            'M' => 'm'
        ];

        return number_format($this->mileage, 0, ',', '.') . ' ' . $units[$this->mileage_unit];
    }

    public function getVatMarginFormattedAttribute()
    {
        return $this->vat_margin === 'M' ? 'Marge' : 'BTW';
    }

    public function getVehicleTaxFormattedAttribute()
    {
        return '€ ' . number_format($this->vehicle_tax, 0, ',', '.') . ',-';
    }

    public function getDeliveryCostsFormattedAttribute()
    {
        return '€ ' . number_format($this->delivery_costs, 0, ',', '.') . ',-';
    }

    public function getRoadTaxMinFormattedAttribute()
    {
        return '€ ' . number_format($this->road_tax_min, 0, ',', '.') . ',-';
    }

    public function getRoadTaxMaxFormattedAttribute()
    {
        return '€ ' . number_format($this->road_tax_max, 0, ',', '.') . ',-';
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
        return $query->where('sold', true);
    }

    /**
     * Returns only occasions that are not sold
     * @param  Builder $query The query builder instance
     * @return Builder
     */
    public function scopeNotSold($query)
    {
        return $query->where('sold', false);
    }

}
