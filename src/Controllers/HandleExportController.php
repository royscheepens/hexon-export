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
    }

    /**
     * Collects the data, converts it into XML and feeds it in the Export class
     * @return String A '1' if all went well, or a 422 with reasons why if not
     */
    public function handle()
    {
        $input = $this->request->getContent();

        try {
            $xml = new \SimpleXmlElement($input);

        } catch(\Exception $e) {

            // todo: log it

            abort(422, 'Failed to parse XML due to malformed data.');
        }

        $export = new HexonExport();

        $result = $export->handle($xml);

        if($result->hasErrors())
        {
            abort(422, implode('\n', $result->getErrors()));
            exit;
        }

        // Hexon requires a response of '1' if all went well.
        exit('1');
    }
}
