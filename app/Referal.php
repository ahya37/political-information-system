<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Referal extends Model
{
    public function getReferals()
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_referal
                from users as a
                join users as b on a.user_id = b.id
                where a.village_id is not null
                group by b.name, b.id
                order by count(a.id) desc limit 5";
        return DB::select($sql);
    }

    public function getReferalProvince($province_id)
    {
        $sql = "SELECT b.id, b.name , count(DISTINCT (a.id)) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                left join districts as   d on c.district_id = d.id 
                left join regencies as e on d.regency_id = e.id
                where e.province_id = $province_id
                group by b.name, b.id
                order by count(a.id) desc limit 5";
        return DB::select($sql);
    }

    public function getInputerProvince($province_id)
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                join villages as d on c.village_id = d.id
                join districts as e on d.district_id = e.id
                join regencies as f on e.regency_id = f.id
                where f.province_id = $province_id
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getInputers()
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getInputerRegency($regency_id)
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                join villages as d on c.village_id = d.id
                join districts as e on d.district_id = e.id
                where e.regency_id = $regency_id
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getInputerAdminMember($userId)
    {
        $sql = "SELECT b.id, b.name,  COUNT(DISTINCT (a.id)) as total_data from users as a
                join users as b on a.cby = b.id  
                join admin_dapils as c on b.id = c.admin_user_id
                join villages as d on a.village_id = d.id
                join districts as e on d.district_id = e.id
                JOIN admin_dapil_district as f on e.id = f.district_id 
                join admin_dapils as g on f.admin_dapils_id = g.id 
                where g.admin_user_id = $userId
                group by b.id , b.name order by  COUNT(DISTINCT (a.id)) desc limit 5";
        return DB::select($sql);
    }


    public function getDataInputerAdminMemberCaleg($userId)
    {
        $sql = "SELECT b.id, b.name,  COUNT(DISTINCT (a.id)) as total_data from users as a
                join users as b on a.cby = b.id  
                join admin_dapils as c on b.id = c.admin_user_id
                join villages as d on a.village_id = d.id
                join districts as e on d.district_id = e.id
                JOIN admin_dapil_district as f on e.id = f.district_id 
                -- join admin_dapils as g on f.admin_dapils_id = g.id
                join admin_caleg as h on  h.admin_caleg_user_id = b.id
                where h.caleg_user_id = $userId and a.user_id = $userId
                group by b.id , b.name order by  COUNT(DISTINCT (a.id)) desc limit 5";
        return DB::select($sql);
    }

    public function getInputerDistrict($district_id)
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                join villages as d on c.village_id = d.id
                join admin_dapil_district as e on b.id = e.admin_dapils_id
                where e.district_id = $district_id and  d.district_id = $district_id
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getInputerDistrictCaleg($district_id, $userId)
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                join villages as d on c.village_id = d.id
                join admin_dapil_district as e on b.id = e.admin_dapils_id
                where e.district_id = $district_id and  d.district_id = $district_id
                and a.user_id = $userId
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getReferalRegency($regency_id)
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                left join districts as d on c.district_id = d.id 
                where d.regency_id = $regency_id and a.village_id is not null 
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferalAdminMember($user_id)
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                left join districts as d on c.district_id = d.id
                join admin_dapil_district as e on d.id = e.district_id
                join admin_dapils as f on e.admin_dapils_id = f.id 
                where  a.village_id is not null  and f.admin_user_id = $user_id
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }
    
    public function getReferalAdminMemberCaleg($user_id)
    {
        $sql = "SELECT b.id, b.name , count(distinct(a.id)) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                left join districts as d on c.district_id = d.id
                join admin_dapil_district as e on d.id = e.district_id
                join admin_dapils as f on e.admin_dapils_id = f.id
                where  a.village_id is not null
                and b.user_id = $user_id
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferalDistrict($district_id)
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                where c.district_id = $district_id
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getInputerVillage($village_id)
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                join villages as d on c.village_id = d.id
                join admin_dapil_village as e on b.id = e.admin_dapil_id 
                where e.village_id = $village_id and d.id = $village_id
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getInputerVillageCaleg($village_id, $userId)
    {
        $sql = "SELECT a.id, a.name, count(c.id) as total_data from users as a
                join admin_dapils as b on a.id = b.admin_user_id
                join users as c on a.id = c.cby
                join villages as d on c.village_id = d.id
                join admin_dapil_village as e on b.id = e.admin_dapil_id 
                where e.village_id = $village_id and d.id = $village_id and c.user_id = $userId
                group by a.id, a.name order by count(c.id) desc";
        return DB::select($sql);
    }

    public function getReferalVillage($village_id)
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                where c.id = $village_id
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferalVillageCaleg($village_id, $userId)
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                where c.id = $village_id and b.user_id = $userId
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }

    public function getReferealByMounthAdmin($mounth, $year)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and b.village_id is not null 
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp order by COUNT(b.id) desc";
        return DB::select($sql);
    }
    
    public function getReferealByMounthAdminProvince($mounth, $year, $province_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and e.province_id = $province_id
                group by  a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getInputByMounthAdminProvince($mounth, $year, $province_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and e.province_id = $province_id
                group by  a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getInputByMounthAdminNational($mounth, $year)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and b.village_id is not null
                group by  a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByDefault()
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                where b.village_id is not null
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByDefaultProvince($province_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name,  a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where e.province_id = $province_id
                group by a.id, a.name,a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

     public function getInputByDefaultProvince($province_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name,  a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where e.province_id = $province_id
                group by a.id, a.name,a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

     public function getInputByDefaultNational()
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name,  a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                where  b.village_id  is not null
                group by a.id, a.name,a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

     public function getInputByDefaultRegency($regency_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name,  a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                where d.regency_id = $regency_id
                group by a.id, a.name,a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

     public function getInputByDefaultDistrict($district_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name,  a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on b.village_id = c.id 
                where c.district_id = $district_id
                group by a.id, a.name,a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

     public function getInputByDefaultVillage($village_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name,  a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                where b.village_id = $village_id
                group by a.id, a.name,a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByMounthAdminRegency($mounth, $year, $regency_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and e.id = $regency_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }
    
    public function getInputByMounthAdminRegency($mounth, $year, $regency_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and e.id = $regency_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getInputByMounthAdminDistrict($mounth, $year, $district_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                join villages as c on b.village_id = c.id 
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and c.district_id = $district_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getInputByMounthAdminVillage($mounth, $year, $village_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.cby
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and b.village_id = $village_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByMounthAdminRegencyDefault($regency_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where e.id = $regency_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByMounthAdminDistrict($mounth, $year, $district_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and d.id = $district_id
                group by a.id, a.name, e.name, d.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByMounthAdminDistrictDefault($district_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where  d.id = $district_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByMounthAdminVillage($mounth, $year, $village_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where MONTH(b.created_at) = $mounth and YEAR(b.created_at) = $year and c.id = $village_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getReferealByMounthAdminVillageDefault($village_id)
    {
        $sql = "SELECT a.id as user_id, a.phone_number, a.whatsapp, a.name, a.photo, 
                COUNT(b.id) as total FROM users as a
                join users as b on a.id = b.user_id
                join villages as c on b.village_id = c.id 
                join districts as d on c.district_id = d.id 
                join regencies as e on d.regency_id = e.id
                where b.village_id = $village_id
                group by a.id, a.name, a.photo, a.phone_number, a.whatsapp 
                order by COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getPoint($start, $end)
    {
        $sql = "SELECT c.id as voucher_id, a.id, a.name, a.photo, d.number bank_number, d.owner , d.bank bank_name, COUNT(b.id) as total_referal from users as a
                join users as b on a.id = b.user_id
                left join voucher_history as c on a.id = c.user_id
                left join member_bank as d on a.id = d.user_id
                where b.village_id is not null
                GROUP BY c.id, a.id, a.name, a.photo, c.total_data, d.number, d.owner, d.bank order by  COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getPointByThisMonth($start, $end)
    {
        $sql = "SELECT a.id, a.name, a.photo, d.number bank_number, d.owner , d.bank bank_name, COUNT(b.id) as total_referal, c.total_data as referal_inpoint from users as a
                join users as b on a.id = b.user_id
                left join voucher_history as c on a.id = c.user_id
                left join member_bank as d on a.id = d.user_id
                where b.created_at BETWEEN  '$start' and '$end' and b.village_id is not null
                GROUP BY a.id, a.name, a.photo, c.total_data, d.number, d.owner, d.bank order by  COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getPointByThisMonthAdmin($start, $end)
    {
        $sql = "SELECT  c.id as voucher_id, a.id, a.name, a.photo, d.number bank_number, d.owner , d.bank bank_name, COUNT(b.id) as total_input from users as a
                join users as b on a.id = b.cby
                left join voucher_history as c on a.id = c.user_id
                left join member_bank as d on a.id = d.user_id
                where b.created_at BETWEEN  '$start' and '$end' and a.level != 0 and b.village_id is not null 
                GROUP BY c.id, a.id, a.name, a.photo, c.total_data, d.number, d.owner, d.bank order by  COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getPointMemberAdmin($start, $end)
    {
        $sql = "SELECT c.id as voucher_id, a.id, a.name, a.photo, d.number bank_number, d.owner , d.bank bank_name, COUNT(b.id) as total_input from users as a
                join users as b on a.id = b.cby
                left join voucher_history as c on a.id = c.user_id
                left join member_bank as d on a.id = d.user_id
                where b.village_id is not null and a.level != 0 and b.village_id is not null
                GROUP BY c.id, a.id, a.name, a.photo, c.total_data, d.number, d.owner, d.bank order by  COUNT(b.id) desc";
        return DB::select($sql);
    }

    public function getPointByMemberAccount($start, $end, $userId)
    {
        $sql = "SELECT COUNT(b.id) as total_input, c.total_data as input_inpoint from users as a
                join users as b on a.id = b.cby
                left join voucher_history_admin as c on a.id = c.user_id
                where b.created_at BETWEEN '$start' and '$end' and a.id = $userId and b.village_id  is not null
                GROUP BY c.total_data";
        $result =  collect(\DB::select($sql))->first();
        return $result;
    }

    public function getPointByMemberAccountReferal($start, $end, $userId)
    {
        $sql = "SELECT COUNT(b.id) as total_referal, c.total_data as referal_inpoint from users as a
                join users as b on a.id = b.user_id
                left join voucher_history as c on a.id = c.user_id
                where b.created_at BETWEEN '$start' and '$end' and a.id = $userId and b.village_id is not null
                GROUP BY c.total_data";
        $result =  collect(\DB::select($sql))->first();
        return $result;
    }

    public function getTotalVoucherReferal()
    {
        $sql = DB::table('users as a')
                ->select('b.id','a.photo','a.name', DB::raw('SUM(g.total_data) as total_data'), DB::raw('SUM(g.nominal) as total_nominal'),DB::raw('SUM(g.point) as total_point'))
                ->join('voucher_history as b','a.id','b.user_id')
                ->join('detail_voucher_history as g','b.id','g.voucher_history_id')
                ->where('g.type','Referal')
                ->groupBy('b.id','a.photo','a.name','c.name','d.name','e.name','f.name')
                ->get();
        return DB::select($sql);
    }

    public function getReferalDistrictCaleg($district_id, $userId)
    {
        $sql = "SELECT b.id, b.name , count(a.id) as total_data
                from users as a
                join users as b on a.user_id = b.id
                left join villages as c on a.village_id = c.id
                where c.district_id = $district_id and b.user_id = $userId
                group by b.name, b.id
                order by count(a.id) desc
                limit 5";
        return DB::select($sql);
    }

}
