<?php

namespace RoyScheepens\HexonExport\Controllers;

use RoyScheepens\HexonExport\HexonExport;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class HandleExportController extends Controller
{
    /**
     * The request object
     * @var Request
     */
    protected $request;

    /**
     * Class Constructor
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        parent::__construct();
    }

    /**
     * Collects the data, converts it into XML and feeds it in the Export
     * @return String A '1' if all went well, or anything else if not.
     */
    public function handle()
    {
        $input = $this->request->getContent();

        try {
            $xml = new \SimpleXmlElement($input);

        } catch(\Exception $e) {

            // todo: log it

            echo 'Failed to parse XML due to malformed data';
            exit;
        }

        $result = HexonExport::process($xml);

        if($result->getStatus() !== TRUE)
        {
            echo $result->getErrors();
            exit;
        }

        // Hexon requires a response of '1' if all went well.
        echo '1';
        exit;
    }
}
