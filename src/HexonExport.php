<?php

namespace RoyScheepens\HexonExport;

use RoyScheepens\HexonExport\Models\Occasion;
use RoyScheepens\HexonExport\Models\OccasionImage;
use RoyScheepens\HexonExport\Models\OccasionAccessory;

use Storage;

use Illuminate\Support\Str;
use Carbon\Carbon;

class HexonExport {

    /**
     * The Hexon Id of the resource
     * @var Number
     */
    protected $resourceId;

    /**
     * The local resource, based on the Hexon Id
     * @var Occasion
     */
    protected $resource;

    /**
     * Maps attributes from the export to model attributes
     * @var Array
     */
    protected $occasionAttributeMap = [
    ];

    /**
     * Array of errors
     * @var array
     */
    protected $errors = [];

    /**
     * Class Constructor
     */
    function __construct()
    {
        // todo: add option to set image disk on storage
    }


    public function handle(\SimpleXmlElement $xml)
    {
        // The resource id from Hexon
        $this->resourceId = (int) $xml->voertuignr_hexon;

        // Perform an insert/update or delete, based on the action supplied
        switch ($xml->attributes()->actie)
        {
            // Inserts or updates the existing record
            case 'add':
            case 'change':

                // Check if the resource has any images
                if(empty($xml->afbeeldingen))
                {
                    $this->setError('No images supplied, cannot proceed.');
                    return;
                }

                // Get the existing resource or create it with the resourceId
                $this->resource = Occasion::where('hexon_id', $this->resourceId)->firstOrCreate([
                    'resource_id' => $this->resourceId
                ]);

                // Set the attributes
                $this->setAttribute('brand', $xml->merk);
                $this->setAttribute('model', $xml->model);
                $this->setAttribute('type', $xml->type);
                $this->setAttribute('price', $xml->verkoopprijs_particulier, 'int');
                $this->setAttribute('build_year', $xml->bouwjaar); // todo: int?
                $this->setAttribute('license_plate', $xml->kenteken);
                $this->setAttribute('fuel_type', $xml->brandstof);
                $this->setAttribute('mileage', $xml->tellerstand, 'int');
                $this->setAttribute('mileage_unit', $xml->tellerstand, 'int');
                $this->setAttribute('transmission', $xml->transmissie);
                $this->setAttribute('energy_label', $xml->energie_label);

                $this->setAttribute('sold', (string) $xml->verkocht === 'j', 'boolean');
                $this->setAttribute('sold_at', $xml->verkocht_datum, 'date');

                // btw marge

                $this->setAccessories($xml->accessoires); // ??

                $this->storeImages($xml->afbeeldingen);

                // Try to save the resource
                try {
                    $this->resource->save();

                } catch(\Exception $e) {
                    // $this->setError($e->getMessage());
                    $this->setError('Unable to save or update resource.');
                }

                break;

            // Deletes the resource and all associated data
            case 'delete':

                $this->resource = Occasion::where('hexon_id', $this->resourceId)->first();

                if(! $this->resource)
                {
                    $this->setError('Error deleting resource. Resource could not be found.');
                    return;
                }

                $this->resource->delete();
                break;

            // Nothing to do here...
            default:
                break;
        }

        // Store the XML to disk
        $this->saveXml($xml);
    }

    /**
     * Sets an attribute to the resource and casts to desired type
     * @param string $attr  The attribut key to set
     * @param mixed  $value The value
     * @param string $type  To which type to cast
     */
    private function setAttribute($attr, $value, $type = 'string', $fallback = null)
    {
        switch ($type) {
            case 'int':
                $value = (int) $value;
                break;

            case 'string':
                $value = (string) $value;
                break;

            case 'boolean':
                $value = (bool) $value;
                break

            case 'date':
                $value = Carbon::parse($value);

                // todo: test if a valid date

                break;
        }

        if( empty($value) )
        {
            $value = $fallback;
        }

        $this->resource->setAttribute($attr, $value);
    }

    private function setAccessories($accessories)
    {
        // First, remove all accessories
        $this->resource->accessoires->delete();

        foreach ($accessories as $accessory)
        {
            $this->resource->accessories->create([
                'name' => $accessory
            ]);
        }
    }

    /**
     * Stores the images to disk
     * @param  Array $images An array of images
     * @return void
     */
    private function storeImages($images)
    {
        // todo: do we need to delete all images before storing?
        // this could be very slow
        $this->resource->images->delete();

        foreach ($images as $imageId => $imageUrl)
        {
            if( $contents = file_get_contents($imageUrl) )
            {
                $filename = implode('_', [
                    $this->resourceId,
                    $imageId
                ]).'jpg';

                $imageResource = $this->resource->images->create([
                    'resource_id' => $imageId,
                    'filename' => $filename
                ]);

                Storage::disk('public')->put($imageResource->path, $contents);

                $imageResource->save();

            } else {
                // todo: handle exception
            }
        }
    }

    /**
     * Stores the XML to disk
     * @param  SimpleXmlElement $xml The XML data to write to disk
     * @return void
     */
    private function saveXml($xml)
    {
        $filename = implode('_', [
            Carbon::format('Y-m-dH:i:s'),
            $this->resourceId
        ]).'xml';

        Storage::put(config('hexon-export.xml_storage_path') . $filename, $xml);
    }

    /**
     * Do we have any errors?
     * @return boolean True if we do, false if not
     */
    public function hasErrors()
    {
        return count($this->errors) <> 0;
    }

    /**
     * Returns the errors
     * @return array Array of errors
     */
    public function getErrors()
    {
        if($this->hasErrors())
        {
            return $this->errors;
        }

        return [];
    }

    /**
     * Set an error
     * @param string $err The error description
     */
    public function setError($err)
    {
        array_push($this->errors, $err);
    }
}