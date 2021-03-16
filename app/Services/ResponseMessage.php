<?php


namespace App\Services;


class ResponseMessage
{
    public static function errorResponse( $message,$data = null  ): Array{
        $response = ["status"=>"Failed"];
        $response["message"] = $message;
        if(isset($data)){
            $response["data"] =  is_array($data)?  json_decode ($data) : $data;
        }
        return $response;
    }
    public static function successResponse( $message,  $data = null  ): Array{
        $response = ["status"=>"success"];
        $response["message"] = $message;
        if(isset($data)){
            $response["data"] =  $data;
        }

        return $response;
    }
}
