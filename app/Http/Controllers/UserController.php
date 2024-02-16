<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //php artisan serve
    //127.0.0.1:8000/api
    public function index(){
        $jsonString1 = file_get_contents(__DIR__.'\user.json');
        $jsonString2 = file_get_contents(__DIR__.'\dealer.json');
        $data1 = json_decode($jsonString1, true);
        $data2 = json_decode($jsonString2, true);

        $data3 = array();
        foreach($data1['data'] as $d1){
            $row = array();
            $row['name'] = $d1['name'];
            $row['email'] = $d1['email'];
            $row['booking_number'] = $d1['booking']['booking_number'];
            $row['book_date'] = $d1['booking']['book_date'];
            $row['ahass_code'] = $d1['booking']['workshop']['code'];
            $row['ahass_name'] = $d1['booking']['workshop']['name'];            
            $row['ahass_address'] = "";   
            $row['ahass_contact'] = "";
            $row['ahass_distance'] = 0;
            foreach($data2['data'] as $d2){                
                if($d1['booking']['workshop']['code'] ==  $d2['code']){       
                    $row['ahass_address'] = $d2['address'];   
                    $row['ahass_contact'] = $d2['phone_number'];
                    $row['ahass_distance'] = $d2['distance'];
                }
            }
            $row['motorcycle_ut_code'] = $d1['booking']['motorcycle']['ut_code'];
            $row['motorcycle'] = $d1['booking']['motorcycle']['name'];
            $data3[] = $row;
        }

        //sort data by ahass_distance https://stackoverflow.com/questions/62471856/laravel-collectarray-sortbyvalue-values-is-showing-on-screen
        $data3 = collect($data3)->sortBy('ahass_distance')->values();
        return response()->json([
            'status'=>1,
            'message'=> "Data Successfully Retrieved.",
            'data'=> $data3
        ]);
    }
}
