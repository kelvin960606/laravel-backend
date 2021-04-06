<?php
    namespace App\Services;
    use Time, Helper, File, Config, Storage, Hash;

    class BaseService {
        protected static $instances = [];

        public static function instance() {
            $class = get_called_class();
            if (isset(self::$instances[$class])) {
                return self::$instances[$class];
            }
            
            return new static;
        }

        protected function showSuccess($data, $msg = '') {
            $info = [
                'status'    => true,
                'data'      => $data,
                'msg'       => $msg
            ];

            die(json_encode($info));
        }

        protected function showError($msg = '') {
            $info = [
                'status'    => false,
                'msg'       => $msg
            ];

            die(json_encode($info));
        }

        public function saveImg($img, $path) {
            $imgName = time().'.png';
            Storage::disk($path)->put($imgName, base64_decode(substr($img, strpos($img, ',') + 1)));

            return $imgName;
        }

        public function getImg($img, $path) {
            return Config::get('app.resource.url') . Config::get('app.resource.location') . $img;
        }

        protected function encryptPwd($pwd) {
            return Hash::make($pwd);
        }

        protected function checkPwd($pwd, $dbPwd) {
            if (!Hash::check($pwd, $dbPwd)) {
                $this->showError('Password incorrect');
            }
        }
    }
?>