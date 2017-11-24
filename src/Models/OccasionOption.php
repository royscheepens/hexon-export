<?php

namespace RoyScheepens\HexonExport\Models;

use Illuminate\Database\Eloquent\Model;

class OccasionOption extends Model
{
    /**
     * The table name
     * todo: make this a config setting
     * @var string
     */
    protected $table = 'hexon_occasion_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'occasion_id', 'name'
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
