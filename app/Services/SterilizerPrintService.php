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
            'NX'=>1, 
            'NY'=>1, 
            'SpaceX'=>1, 
            'SpaceY'=>1,
            'width'=>50, 
            'height'=>25, 
            'font-size'=>9
            ]);
        
        try {
            // error_log(print_r($data ,true));
            foreach ($data as $key => $label) {

                $cleaner = $label['cleaner']; 
                $units = $label['units_printed']; 
                $cycle_num = $label['cycle_num'];
                $sterilizer = $label['sterilizer'];
                $user = $label['user'];

                for ($i=0; $i < $units ; $i++) { 
                    error_log($i);
                    $text = sprintf("%s %s\n%s\n%s\n%s\n%s", 
                         Carbon::Now()->format('d-m-Y '), Carbon::Now()->format('h:i:s A'),
                         'Sterilizer: '.$sterilizer,
                         'Cycle # '.$cycle_num,
                         $cleaner,
                         $user);
                        $pdf->AddPage();
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
        return "-----BEGIN RSA PRIVATE KEY-----\n".
            "MIIEogIBAAKCAQEAsK5bVx2UqUxusJGEfxcTuY7XqXciH8zXZP2PaHxijJGC0a60\n".
            "/J18pQk60zdXfBqt1gdXDkTq2wrRYITH25izUt8QXO/oWYnnXVAWWBBtXef4eEQn\n".
            "qkF24PAINaUaI76lFf9OqpmpOAcZOLktyDsBUE4lm4pd6dKJ0JhBi+AwpWWDfGv5\n".
            "sXwxIy2lnLhZVMP8UuhO0IxkKKnoLSH5rHjAbhLTc7O2sVhkjP3ZJiF1OiVLgLkd\n".
            "E5ozRJY5DXth3mL0/OON1CRciQrkFzkRFSE20CNuBe4tFf7oR3/gnlSoGipzsZu+\n".
            "i0pagnxsZXWGSKGAOKKgaXNlz/anBWdSiQjVUQIDAQABAoIBAG6KN9WSSU9+5YYN\n".
            "FTOakZm32BBL3Aq/U0f5KD88C0BdTda4kr22HqT22FoLtu2Ll5Vf9ESWM8Ylx4e2\n".
            "q4Al0pQXwZoT83AJyrpkAAbLCFIHbzp3kcWAvcrQAqLlkCwq/Ah+Oo6cIvZN4qbN\n".
            "nOo4mILQ0dC1yjofol/i0qeb9XOUvIbfHxEuc7VRFx7SEjJYbWICMynWE+HoETe5\n".
            "Di1tzSVVFn4hBgsdHH9H/97Y2roV1+PPtFXy9VRn9kzcExgfpaFSIY1yYad46I2z\n".
            "5mVxtgI/NcMNoU5PxlHJGe4d3tCees1EITrYBhPi61K8JS58YOgXUP76It7m44cf\n".
            "oogwnT0CgYEA1NcTKrWXrdAVC8aXNHtUwwxxFKBzgcnSSHqcRnlR7lgIZ+ZR8APv\n".
            "0+Xb5E+M6vmyRQkhXEPjLuDjSXpalrpA2yODvEv6QOi6v/eVXQNlE/kZ98RVgukk\n".
            "9s921wd5THSiqRWhc5ATfO4YUeqfJwxprcBWbJ+SB5BQVsvyIryXb1MCgYEA1IIy\n".
            "0UrJreme3qb6T0IrrXaK+WfZg9nepANMWsVXkN3BLFE6t0yFjki9urs97WkYyfU1\n".
            "Cxqz/lsjK3tQMa0PUOQ7rqVqWjLuPMs7Qy3sY4wdP1MjUrxxn7HqaKz0I38Jqjt8\n".
            "VaOXm7SjcR9OOMJZR0ApVQVHl9G3cVVXCHQY6EsCgYBIjVgXQveFZF0IOpXoafIm\n".
            "2uLhKSC2qVlpI4KJ82keWhvjbvuXWYAzNzORzBw5LQy7w7T/gpS6siZIMdEXeD4L\n".
            "dTu+wBd6cNrX/UmI/1NBT7SuZNrRWTAkgev8uKt8iHW6NYlnn0FFYNQheFzwLqcH\n".
            "d3z+YhahRWxdagAMH0VvyQKBgDtXpFM3nDwZegjY/hs18jZ9XO+qpV4hb//DAAZ1\n".
            "By/oDPKQOGJJVx5Vh83mTbPHXmm/NLHGIi9MSfTxeYJ1vkorgdMSVDGd+VM2uXTY\n".
            "FSPzU5OEZt/Kybv7lebnLUHgTtWfyOetSD/t4JpNUK2e8AlqjGA2mjh2EtDuMOCm\n".
            "cvIjAoGAGDCLAZVSax5s7BYJKt3UXIv9rJzn96eqywdJvXyMIpXASrk0ijkFA+px\n".
            "EbrX3nJTGNNGnphn58Fu+yu4sppFGu7ZLXwaZpOfj0AHEL91GmbYbr9tISawV/cQ\n".
            "KsaOzVP7mdGGAUC00qFw74aar6OqtoXBz8QdG/Np4KIXxNxisEA=\n".
            "-----END RSA PRIVATE KEY-----";

    }


}

