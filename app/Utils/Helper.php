<?php
    namespace App\Utils;
    use Http, Config, Time;

    class Helper {
        public static function getSn() {
            list($usec, $sec) = explode(' ', microtime());
            $sec = sprintf('%06d', (int)($usec * 1000000));

            return Time::toDate(UTC_CURRENT_TIME, 'YmdHis');
        }

        public static function getClientIp($type = 0) {
            $type       = $type ? 1 : 0;
            static $ip  = NULL;
            if ($ip !== NULL) {
                return $ip[$type];
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip     = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
                $ip     = $_SERVER['HTTP_X_REAL_IP'];
            }  elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     = $_SERVER['REMOTE_ADDR'];
            }

            //IP地址合法验证
            $long       = sprintf('%u', ip2long($ip));
            $ip         = $long ? array($ip, $long) : array('0.0.0.0', 0);

            return $ip[$type];
        }

        public static function getClientRegionByIp($ip) {
            $location       = [
                'country'   => '',
                'province'  => '',
                'city'      => '',
            ];

            if ($ip == '127.0.0.1') {
                $url    = 'http://ip-api.com/json';
                $result = file_get_contents($url);
                if (!empty($result)) {
                    $result = json_decode($result, true);
                    if (!empty($result['query'])) {
                        $ip = $result['query'];
                    }
                }
            }

            $url    = 'http://ip-api.com/json/' . $ip . '?lang=zh-CN';
            $result = file_get_contents($url);
            if (!empty($result)) {
                $result                 = json_decode($result, true);
                $location['country']    = $result['country'];
                $location['province']   = null;
                $location['city']       = $result['city'];
            }

            return $location;
        }

        protected static function getEncrypter() {
            static $encrypter = null;

            if (!$encrypter) {
                $encrypter = new Encrypter(Config::get('app.encrypter.key'), Config::get('app.encrypter.iv'));
            }

            return $encrypter;
        }

        public static function encryptData($data) {
            if (empty($data)) {
                return null;
            }

            try {
                $encrypter = self::getEncrypter();
                return $encrypter->encrypt($data);
            } catch (\Exception $e) {
                return false;
            }
        }

        public static function decryptData($data) {
            if (empty($data)) {
                return null;
            }

            try {
                $encrypter = self::getEncrypter();
                return $encrypter->decrypt($data);
            } catch (\Exception $e) {
                return false;
            }
        }
    }
?>