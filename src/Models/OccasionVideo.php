<?php

namespace RoyScheepens\HexonExport\Models;

use Illuminate\Database\Eloquent\Model;

use Storage;

class OccasionVideo extends Model
{
    /**
     * The table name
     * todo: make this a config setting
     * @var string
     */
    protected $table = 'hexon_occasion_videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'occasion_id',
        'resource_id',
        'source',
        'code',
    ];

    /**
     * Relations
     * ----------------------------------------
     */

    public function occasion()
    {
        return $this->belongsTo('RoyScheepens\HexonExport\Models\Occasion');
    }

}
