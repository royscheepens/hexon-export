<?php

namespace RoyScheepens\HexonExport\Observers;

use RoyScheepens\HexonExport\Models\Occasion;

class OccasionObserver
{
    /**
     * Deletes occasion related records
     *
     * @param  Occasion  $occasion
     * @return void
     */
    public function deleting(Occasion $occasion)
    {
        $occasion->accessories->delete();

        $occasion->images->delete();
    }
}