<?php

namespace App\Utilities\Inspections;

use Illuminate\Support\Facades\App;

class Spam implements InspectionInterface
{

    protected $inspections =[
        InvalidKeywords::class,
        DetectKeyHeldDown::class
    ];

    public function detect($body)
    {
        foreach ($this->inspections as $inspection){
            app($inspection)->detect($body);
        }
        return false;
    }

}
