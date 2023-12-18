<?php 

namespace App\Helpers;

use App\Models\District;
use App\Models\Village;

class AdminArea
{

    public static function getDistrict(){
		
        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $districtModel  = new District();
        $district       = $districtModel->getAreaAdminKoordinator($authAdminDistrict);       
        return $district;
    }

	
}