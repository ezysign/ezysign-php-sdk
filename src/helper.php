<?php
namespace EzySignSdk;

class Helper
{

    public function convert_jwt($jwt)
    {
        try {
            $jwt = str_replace('Bearer ', '', $jwt);

            $decoded = \Firebase\JWT\JWT::decode($jwt, 'ezysign', ['HS256']);
            $associated_array = (array) ($decoded);

            return $associated_array;
        } catch (Exception $e) {
            error_log($e->getMessage(), 0);
            return [];
        }
    }

    public function array_any(array $array, callable $fn)
    {
        foreach ($array as $value) {

            if ($fn($value)) {
                return true;
            }
        }
        return false;
    }

    public function get_two_week()
    {
        $startdate = strtotime("Today");
        $enddate = strtotime("+2 weeks", $startdate);
        $d = date("Y-m-d", $enddate);
        return $d;
    }

}
