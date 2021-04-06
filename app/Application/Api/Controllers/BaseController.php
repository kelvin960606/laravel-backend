<?php
    namespace App\Application\Api\Controllers;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Support\Facades\Request;
    use Helper, Time;

    class BaseController {
        use ValidatesRequests;

        protected $user           = null;
        protected $userToken      = null;
        protected $loginStatus    = 0;
        private $pathException    = [
            'App/token'
        ];

        public function __construct() {
            $clientIp = Request::header('CLIENT_IP');
            if (empty($clientIp)) {
                $clientIp = Helper::getClientIp();
            }
            define('CLIENT_IP', $clientIp);
            define('UTC_CURRENT_TIME', Time::getCurrentTime());

            if (!in_array(APP_CONTROLLER_NAME . '/' . APP_ACTION_NAME, $this->pathException)) {
                $this->checkToken();
            }
        }

        protected function checkToken() {
            $authorization = Request::header("Authorization");
            if (empty($authorization)) {
                $this->error('Authorization failed');
            }
            $authorization = substr($authorization, 7);

            try {
                $this->userToken = Helper::decryptData($authorization);
            } catch(\Exception $e) {
                $this->error('Authorization failed');
            }

            if (!isset($this->userToken['user_id'])) {
                $this->error('USER ID error');
            }

            // if (!isset($this->userToken['sid'])) {
            //     $this->error('SID error');
            // }

            if (!isset($this->userToken['client'])) {
                $this->error('CLIENT error');
            }

            if (!isset($this->userToken['ip'])) {
                $this->error('IP error');
            }

            return true;
        }

        protected function generateUserToken($user, $forever) {
            $this->user                    = $user;
            $this->userToken['user_id']    = $user['id'];
            $this->userToken['time']       = UTC_CURRENT_TIME;
            $this->userToken['is_forever'] = $forever;
            $this->loginStatus             = 1;

            $result = [
                'token' => Helper::encryptData($this->userToken)
            ];

            return $result;
        }

        protected function success($data = null, $msg = '') {
            $info = [
                'status' => true,
                'data'   => $data,
                'msg'    => $msg
            ];

            $this->output($info);
        }

        protected function error($msg = '') {
            $info = [
                'status' => false,
                'data'   => [],
                'msg'    => $msg
            ];

            $this->output($info);
        }

        private function output($info) {
            $info['loginStatus']    = $this->loginStatus;
            /*
            if ($this->userToken) {
                $info['token']      = $this->userToken;
            }
            */
            die(json_encode($info));
        }
    }
?>