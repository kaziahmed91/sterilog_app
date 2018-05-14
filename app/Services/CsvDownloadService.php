<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;


use Carbon\Carbon; 
use Exception;
use App\Companies as CompaniesModel;
use App\User as UserModel;
use App\Sterilizer as SterilizeModel;
use App\Cleaners as CleanersModel;
use App\Cycles as CyclesModel;

class CsvDownloadService 
{   
    function __construct()
    {
        
    }

    public function downloadCsv($collection, $type)
    {
        try {            
            // dd($collection);
            return new StreamedResponse(function() use ($type, $collection ) {
                // Open output stream
                // Add CSV headers
                $headers = $this->getHeaders($type);
                $handle = fopen('php://output', 'w');
                fwrite($handle, $bom = (chr(0xEF).chr(0xBB).chr(0xBF)));
                fputcsv($handle, $headers);

                if ($type === 'spore'){
                    $collection->chunk(200, function($rows) use($handle) {
                        foreach ($rows as $key => $row) {
                            // Add a new row with data
                            $formattedData = $this->formatSporeData($key, $row);
                            fputcsv($handle, $formattedData);
                        }
                    });
                } else if ($type === 'sterile') {
                    $collection->chunk(200, function($rows) use($handle) {
                        foreach ($rows as $key => $row) {
                            $formattedData = $this->formatSterilizeData($key, $row);
                            error_log(print_r($formattedData,true));
                            fputcsv($handle, $formattedData);
                        }
                    });
                }
                
                // Close the output stream
                fclose($handle);
            }, 200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="export.csv"',
            ]
        );

        } catch (Exception $e){
            throw $e;
        }
    }

    private function formatSterilizeData($key, $row)
    {
        try {       
            // error_log($row->completed_on);
            $removUser =  !is_null($row->removalUser) ? 
                $row->removalUser->first_name.' '.$row->removalUser->last_name : '';
            $removDt=  is_null($row->completed_on) ? "" : Carbon::parse($row->completed_on)->format('d-m-Y');
            $removTm=  is_null($row->completed_on) ? "" : Carbon::parse($row->completed_on)->format('h:i:s A');
            $type1 = '';
            $type4 = '';
            $type5 = '';
            $paramsVerified = '';
            $type5_tested = $row->type_5_testing === 1 ? 'True' : 'False';
            $init_comment = !is_null($row->comment) ? $row->comment : '';
            $addit_comment = !is_null($row->additional_comments)? $row->additional_comments : '';

            if ( !is_null($row->completed_on)) {
                $type1 = $row->type1 === 0 ? 'Unsterile' : "Sterile";
                $type4 = $row->type4 === 0 ? 'Unsterile' : "Sterile";
                if ($row->type_5_testing === 1) {
                    $type5 = $row->type5 === 0 ? 'Unsterile' : "Sterile";
                }
                if ($row->params_verified === 1 ) $paramsVerified = 'Yes';
                if ($row->params_verified === 0 ) $paramsVerified = 'No';
                if (is_Null($row->params_verified) ) $paramsVerified = 'Failed';
            }
            
        } catch (Exception $e) {
            error_log($e->getLine());
            error_log($e->getMessage());
            throw $e;
        }
        return $formatted = [
            $key + 1,
            $row->entryUser->first_name.' '.$row->entryUser->last_name,
            $removUser,
            Carbon::parse($row->entry_at)->format('d-m-Y'), 
            Carbon::parse($row->entry_at)->format('h:i:s A'),
            $removDt,
            $removTm,
            $row->sterilizer->sterilizer_name,
            $row->cleaners->name,
            $row->units_printed,
            $row->cycle_number,
            $type1,
            $type4,
            $type5_tested,
            $type5,
            $paramsVerified,
            $init_comment,
            $addit_comment
        ];
    }
    
    private function formatSporeData($key, $row)
    {
        $removUser =  !is_null($row->removalUser) > 0  ? 
            $row->removalUser->first_name.' '.$row->removalUser->last_name : '';
        $removDt= !is_null($row->removal_at) ? Carbon::parse($row->removal_at)->format('d-m-Y') : '';
        $removTm= !is_null($row->removal_at)  ? Carbon::parse($row->removal_at)->format('h:i:s A') : '';
        $control = '';
        $test = '';
        $init_comment =  !is_null($row->initial_comments) ? $row->initial_comments : '';
        $addit_comment = !is_null($row->additional_comments) ? $row->additional_comments : '';
    
        if (!is_null($row->removal_at)) {
            $control = $row->control_sterile === '0' ? 'Unsterile' : "Sterile";
            $test = $row->test_sterile === '0' ? 'Unsterile' : "Sterile";
        }
        
        return $formatted = [
            $key + 1, 
            $row->entryUser->first_name.' '.$row->entryUser->last_name,
            $removUser,
            Carbon::parse($row->entry_at)->format('d-m-Y'), 
            Carbon::parse($row->entry_at)->format('h:i:s A'),
            $removDt,
            $removTm,
            $row->entry_cycle_number, 
            $row->lot_number,
            $row->sterilizer->sterilizer_name,
            $control, 
            $test, 
            $init_comment,
            $addit_comment,
        ];

    }

    private function getHeaders($type)
    {   
        if ($type === 'spore') {
            $headers =
            [
                'Id',
                'Entry Operator', 
                'Removal Operator', 
                'Entry Date',
                'Entry Time',
                'Removal Date', 
                'Removal Time', 
                'Entry Cycle Number',
                'Lot Number',
                'Sterilizer',
                'Control Sterile',
                'Test Sterile',
                'Initial Comments', 
                'Additional Comments'
            ];
        } else if ($type === 'sterile') {
            $headers = [
                'Id',
                'Entry Operator', 
                'Removal Operator', 
                'Entry Date',
                'Entry Time',
                'Removal Date', 
                'Removal Time', 
                'Sterilizer', 
                'Cleaner', 
                'Units Printed', 
                'Cycle Number', 
                'Type 1 Test', 
                'Type 4 Test', 
                'Type 5 Tested', 
                'Type 5 Test', 
                'Parameters Verified', 
                'Initial Comments', 
                'Additional Comments', 
            ];
        }
        return $headers;
    }

}