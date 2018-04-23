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

        // return "-----BEGIN PRIVATE KEY-----\n" .
        //     "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDOZqhJi+ifWOxX\n" .
        //     "IL9xPuiRudtYfKSxGkE2MB6gAkK0mKpLZ/ZAnAVfYoNKVxkuW3ZR3lea790muSfZ\n" .
        //     "unA3DRpaLj+tl3++iSny6JV4u1PMbzmQdonDh6CP/lV5OewvZMBEAcl2Fz4p8PtN\n" .
        //     "e8wjNjPLmOrYexUhesS+XyMxAv2ENwo+ed3vzytTjI2deOZHLaoLn+tUHu6oGTGd\n" .
        //     "C/V2yNjpJm59rTuCtlE8v/zMf+nHR/FbWVJZps//lDqfSGxsQoOgJXdmOpBvWemi\n" .
        //     "e6GcwwApIg44bc5SIZL0zfX4vtultjvZqpgntfIlCJRIO/XZTok8X8NP1Bo13dBT\n" .
        //     "3fesHkbbAgMBAAECggEBAJNsK6NsdRjr0NNipprijgcYMx1+2btNv2pO8HDlbHPF\n" .
        //     "30zA0tjPF99LY+9DSs8bsOVE5FncHZ+8/EO9sM0BZOMoyX7aCPo0ymufyLNVScNp\n" .
        //     "ZjpTEI9CXmK7DJ3ry1EGq3VnuVfJvjMC1tw/Ik28Nz6i2IsLTsp8/+3h0ib9tcoq\n" .
        //     "pVMURUzx0rMHx5whVSnqrrwWScOHbkHJloVWbWQW9b/QI6KVcy9dG0XAa1MJ3tC1\n" .
        //     "6LiP41eXd0ZbYkSwlpzxpqpqcM4G+/4tLKLnSx7o6Me/6jp1Nl+wfu/Arv53BkGj\n" .
        //     "38JNoDgL+JtYUG+UuU/tp/gTOWZFxkTGf04KJMVDcUECgYEA77+ihKwv5bT6SEMH\n" .
        //     "GfGYveEimPFb0Gc1WU50G/udKZNWbWnK1nClqDYA//ubjPTBlZULE63DbNYw88Pl\n" .
        //     "ZCINIvqlOIw5Fkhww41ffuqOCkJatx7j6SWANRVUfw8+SSGKZKcFL4Q8NbwF0E2F\n" .
        //     "U0DE0/P1/ZLpx7M8e4KbJLguwGsCgYEA3GRXTu6j1cDnEYT3NfcOzJsqXr8dXg7i\n" .
        //     "EkLzOyzPYaO5K0WaEG3dXoj2b74mJL2sYarkYMdIeFaZ6GiLvGJFMh3YCW27ufqV\n" .
        //     "kX3XDVKOK9UT1CDLIoUMCjrfgqn8c0YyX7CqDfGgFJhB2GEFAsH54E2GirLCMwoD\n" .
        //     "t3lHtXTvb1ECgYA7F1eeLbZsfovFrcedTF/iRRA69Bo2JooOpfiWQRD+g1MlCpM1\n" .
        //     "AXTDHfRnI4O2XUktbhvZ0NU/CRftrRXn9UCZo39/jYgwhtBMUpb7+CHkQ3CKe/Ge\n" .
        //     "sGCGNOirnH00lzC+GDB+ArC4acxhCApZd7hLqXCy/DfkwbehlZJZnY/gWwKBgGEt\n" .
        //     "OTYLkV/x47Dc4SgBXJwpGsCWIjvT8ii9LoR8GKDj0QTcdrchykchuw/AVw9CNt0F\n" .
        //     "vhdSJ4kwHkLUvjB9YCdd82EM6oZxkDuLsiQkr51yIYEaSJda9NkZ5m21yCOH4zRA\n" .
        //     "MGtfjAcor3faJ5x0rVoStO05LtnUY5klt9Gx0tVRAoGAFTyda7ahE7L51NLs+ItW\n" .
        //     "4pvUGhaYsVltTIzseTWZh5ln6UeaskdkGIKqripY0WZlw7qfkwl4dWEFQgfdtgtZ\n" .
        //     "ko9dQ/IBiZcecWvhP2kxbITkQdioo7w1/oZgdz2FX45+6Pnufifc4LlmVrBFp16O\n" .
        //     "Y2NV/hMTwkYPzAMuq73kUlo=\n" .
        //     "-----END PRIVATE KEY-----\n";
    }


}

