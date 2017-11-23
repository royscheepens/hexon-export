<?php

namespace RoyScheepens\HexonExport\Models;

use Illuminate\Database\Eloquent\Model;

class OccasionOption extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'occasion_id', 'value'
    ];

    /**
     * Relations
     * ----------------------------------------
     */

    public function occasion()
    {
        return $this->belongsTo('RoyScheepens\HexonExport\Models\Occassion');
    }

}
