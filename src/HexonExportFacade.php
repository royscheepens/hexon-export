<?php

namespace RoyScheepens\HexonExport;

use Illuminate\Support\Facades\Facade;

class HexonExportFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
      return 'hexon-export';
  }
}