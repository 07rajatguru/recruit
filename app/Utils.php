<?php

namespace App;

class Utils
{
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function getUrlAttribute($file)
    {
        $s3 = \Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $bucket = \Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $file
        ]);

        $request = $client->createPresignedRequest($command, '+20 minutes');

        return (string) $request->getUri();
    }


    /**
     * method masks the username of an email address
     *
     * @param string $email the email address to mask
     * @param string $mask_char the character to use to mask with
     * @param int $percent the percent of the username to mask
     */
    function mask_email( $email, $mask_char, $percent=50 )
    {

        list( $user, $domain ) = preg_split("/@/", $email );

        $len = strlen( $user );

        $mask_count = floor( $len * $percent /100 );

        $offset = floor( ( $len - $mask_count ) / 2 );

        $masked = substr( $user, 0, $offset )
            .str_repeat( $mask_char, $mask_count )
            .substr( $user, $mask_count+$offset );


        return( $masked.'@'.$domain );
    }

    public static function IND_money_format($number)
    { 

        $decimal = (string)($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);
 
        for($i=0;$i<$length;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                $delimiter .=',';
            }
            $delimiter .=$money[$i];
        }
 
        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);
 
        if( $decimal != '0'){
            $result = $result.$decimal;
        }
 
        return $result;
    }
}