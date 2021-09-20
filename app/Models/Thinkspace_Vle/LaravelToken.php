<?php

namespace App\Models\Thinkspace_Vle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LaravelToken extends Model {

    protected $table = 'laravel_token';

    /**
     * @param int $token
     * 
     * @return stdClass
     */
    public static function getLaravelTokenForUser($token) {
        
        return DB::connection('mysql_vle')
                        ->table('laravel_token')
                        ->where('token', '=', $token)->first();
    }

    /**
     * @param int $userId
     * 
     * @return stdClass 
     */
    public static function deleteLaravelTokenForUser($userId) {

        return DB::connection('mysql_vle')
                        ->table('laravel_token')
                        ->where('user_id', '=', $userId)->update(['token' => NULL]);
    }

    /**
     * 
     * @return stdClass 
     */
    public static function deleteLaravelToken() {

        $expiryDay = date('Y-m-d H:i:s', strtotime("-1 days"));

        return DB::connection('mysql_vle')
                        ->table('laravel_token')
                        ->where('date', '<', $expiryDay)->update(['token' => NULL]);
    }

}
