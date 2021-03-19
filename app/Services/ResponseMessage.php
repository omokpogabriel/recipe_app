<?php


namespace App\Services;


class ResponseMessage
{
    /**
     *  returns a failure message and optional array|string error
     *
     * @param $message
     * @param null $data
     * @return Array
     */
    public static function errorResponse($message, $data = null  ): Array{
        $response = ["status"=>"Failed"];
        $response["message"] = $message;
        if(isset($data)){
            $response["data"] =  is_array($data)?  json_decode ($data) : $data;
        }
        return $response;
    }

    /**
     * returns a success message and optional array|string error
     * @param $message
     * @param null $data
     * @return Array
     */
    public static function successResponse($message, $data = null  ): Array{
        $response = ["status"=>"success"];
        $response["message"] = $message;
        if(isset($data)){
            $response["data"] =  $data;
        }
        return $response;
    }
}
