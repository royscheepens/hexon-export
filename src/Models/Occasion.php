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
        return $this->hasMany('RoyScheepens\HexonExport\Models\OccasionAccessory')->orderBy('name');
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

    public function getApkUntilFormattedAttribute()
    {
        return $this->apk_until->format('d-m-Y');
    }

    public function getPriceFormattedAttribute()
    {
        return '€ ' . number_format($this->price, 0, ',', '.') . ',-';
    }

    public function getRemarksAttribute($val)
    {
        return html_entity_decode($val, ENT_QUOTES | ENT_HTML5);
    }

    public function getLicensePlateFormattedAttribute()
    {
        if(! $this->license_plate)
        {
            return null;
        }

        $formatted = '';

        foreach (str_split($this->license_plate) as $char)
        {
            $type = is_numeric($char) ? 'number' : 'string';
            $prevChar = substr($formatted, -1);
            
            if($prevChar == '')
            {
                $formatted .= $char;
                continue;
            }

            $prevCharType = is_numeric($prevChar) ? 'number' : 'string';

            if($type != $prevCharType)
            {
                $formatted .= '-';
            }

            $formatted .= $char;
        }

        return $formatted;
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

    public function getMassFormattedAttribute()
    {
        return number_format($this->mass, 0, ',', '.') . 'kg';
    }

    public function getCylinderCapacityFormattedAttribute()
    {
        return $this->cylinder_capacity . ' cc';
    }

    public function getPowerAttribute()
    {
        if($this->power_hp && $this->power_kw) {
            return sprintf("%d pk / %d Kw", $this->power_hp, $this->power_kw);
        }

        if($this->power_hp) {
            return sprintf("%d pk", $this->power_hp);
        }

        if($this->power_kw) {
            return sprintf("%d Kw", $this->power_kw);
        }

        return null;
    }

    public function getCo2EmissionFormattedAttribute()
    {
        return $this->co2_emission . ' g/Km';
    }

    public function getFuelConsumptionCityFormattedAttribute()
    {
        return $this->fuel_consumption_city . ' l/100 Km';
    }
    
    public function getFuelConsumptionHighwayFormattedAttribute()
    {
        return $this->fuel_consumption_highway . ' l/100 Km';
    }

    public function getFuelConsumptionAvgFormattedAttribute()
    {
        return $this->fuel_consumption_avg . ' l/100 Km';
    }

    public function getRoadTaxAttribute()
    {
        if($this->road_tax_min && $this->road_tax_max) {
            return sprintf("€ %s - € %s p/kw", 
                number_format($this->road_tax_min, 0, ',', '.'), 
                number_format($this->road_tax_max, 0, ',', '.')
            );
        }

        return null;
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
