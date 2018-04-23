<?php

namespace App\Services;
    
use App\Services\FpdfLabelService as FpdfLabelService;
use Carbon\Carbon; 
use Exception;

class SterilizerPrintService 
{

    public function __construct( )
    {
    }

    public function generatePdfTag ($data)
    {
        $filename = rand();
        $path = $_SERVER["DOCUMENT_ROOT"] . "/temp_docs/".$filename.".pdf";
        // error_log(print_r($data,true));
    
        $pdf = new FpdfLabelService([        
            'paper-size'=>[50.8, 25.4], 
            'metric'=>'mm', 
            'marginLeft'=>0, 
            'marginTop'=>0, 
            'NX'=>2, 
            'NY'=>2, 
            'SpaceX'=>2, 
            'SpaceY'=>2,
            'width'=>50, 
            'height'=>25, 
            'font-size'=>9
            ]);
        
        try {
            $pdf->AddPage();
            foreach ($data as $key => $label){

                $cleaner = $label['cleaner']; 
                $units = $label['units_printed']; 
                $cycle_num = $label['cycle_num'];
                $sterilizer = $label['sterilizer'];
                $user = $label['user'];

                for ($i=0; $i < $units ; $i++) { 
                    $text = sprintf("%s %s\n%s\n%s\n%s\n%s", 
                         Carbon::Now()->format('d-m-Y '), Carbon::Now()->format('h:i:s A'),
                         'Sterilizer: '.$sterilizer,
                         'Cycle # '.$cycle_num,
                         $cleaner,
                         $user);

                    $pdf->Add_Label($text);
                }
            }

            $pdf->Output($path, 'F');
            $b64Doc = chunk_split(base64_encode(file_get_contents($path)));

        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        
        return [$b64Doc, $path];
    }

    public function getPrivateKey ()
    {
        return "-----BEGIN PRIVATE KEY-----\n" .
            "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDOZqhJi+ifWOxX\n" .
            "IL9xPuiRudtYfKSxGkE2MB6gAkK0mKpLZ/ZAnAVfYoNKVxkuW3ZR3lea790muSfZ\n" .
            "unA3DRpaLj+tl3++iSny6JV4u1PMbzmQdonDh6CP/lV5OewvZMBEAcl2Fz4p8PtN\n" .
            "e8wjNjPLmOrYexUhesS+XyMxAv2ENwo+ed3vzytTjI2deOZHLaoLn+tUHu6oGTGd\n" .
            "C/V2yNjpJm59rTuCtlE8v/zMf+nHR/FbWVJZps//lDqfSGxsQoOgJXdmOpBvWemi\n" .
            "e6GcwwApIg44bc5SIZL0zfX4vtultjvZqpgntfIlCJRIO/XZTok8X8NP1Bo13dBT\n" .
            "3fesHkbbAgMBAAECggEBAJNsK6NsdRjr0NNipprijgcYMx1+2btNv2pO8HDlbHPF\n" .
            "30zA0tjPF99LY+9DSs8bsOVE5FncHZ+8/EO9sM0BZOMoyX7aCPo0ymufyLNVScNp\n" .
            "ZjpTEI9CXmK7DJ3ry1EGq3VnuVfJvjMC1tw/Ik28Nz6i2IsLTsp8/+3h0ib9tcoq\n" .
            "pVMURUzx0rMHx5whVSnqrrwWScOHbkHJloVWbWQW9b/QI6KVcy9dG0XAa1MJ3tC1\n" .
            "6LiP41eXd0ZbYkSwlpzxpqpqcM4G+/4tLKLnSx7o6Me/6jp1Nl+wfu/Arv53BkGj\n" .
            "38JNoDgL+JtYUG+UuU/tp/gTOWZFxkTGf04KJMVDcUECgYEA77+ihKwv5bT6SEMH\n" .
            "GfGYveEimPFb0Gc1WU50G/udKZNWbWnK1nClqDYA//ubjPTBlZULE63DbNYw88Pl\n" .
            "ZCINIvqlOIw5Fkhww41ffuqOCkJatx7j6SWANRVUfw8+SSGKZKcFL4Q8NbwF0E2F\n" .
            "U0DE0/P1/ZLpx7M8e4KbJLguwGsCgYEA3GRXTu6j1cDnEYT3NfcOzJsqXr8dXg7i\n" .
            "EkLzOyzPYaO5K0WaEG3dXoj2b74mJL2sYarkYMdIeFaZ6GiLvGJFMh3YCW27ufqV\n" .
            "kX3XDVKOK9UT1CDLIoUMCjrfgqn8c0YyX7CqDfGgFJhB2GEFAsH54E2GirLCMwoD\n" .
            "t3lHtXTvb1ECgYA7F1eeLbZsfovFrcedTF/iRRA69Bo2JooOpfiWQRD+g1MlCpM1\n" .
            "AXTDHfRnI4O2XUktbhvZ0NU/CRftrRXn9UCZo39/jYgwhtBMUpb7+CHkQ3CKe/Ge\n" .
            "sGCGNOirnH00lzC+GDB+ArC4acxhCApZd7hLqXCy/DfkwbehlZJZnY/gWwKBgGEt\n" .
            "OTYLkV/x47Dc4SgBXJwpGsCWIjvT8ii9LoR8GKDj0QTcdrchykchuw/AVw9CNt0F\n" .
            "vhdSJ4kwHkLUvjB9YCdd82EM6oZxkDuLsiQkr51yIYEaSJda9NkZ5m21yCOH4zRA\n" .
            "MGtfjAcor3faJ5x0rVoStO05LtnUY5klt9Gx0tVRAoGAFTyda7ahE7L51NLs+ItW\n" .
            "4pvUGhaYsVltTIzseTWZh5ln6UeaskdkGIKqripY0WZlw7qfkwl4dWEFQgfdtgtZ\n" .
            "ko9dQ/IBiZcecWvhP2kxbITkQdioo7w1/oZgdz2FX45+6Pnufifc4LlmVrBFp16O\n" .
            "Y2NV/hMTwkYPzAMuq73kUlo=\n" .
            "-----END PRIVATE KEY-----\n";
    }


}

