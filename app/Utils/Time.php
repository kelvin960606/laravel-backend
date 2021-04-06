<?php
    namespace App\Utils;

    class Time {
        public static function getCurrentTime() {
            return time();
        }

        public static function toDate($time = NULL, $format = 'Y-m-d H:i:s'){
            if(empty($time)){
                return '';
            }
    
            if (!is_numeric($time)) {
                return $time;
            }
    
            $time += date('Z');
            $format = str_replace('#',':',$format);
            return date($format, $time);
        }

    }
?>