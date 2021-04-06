<?php
    namespace App\Application\Api\Controllers;
    use App\Services\UserService;

    abstract class AuthController extends BaseController {
        protected function checkToken() {
            parent::checkToken();

            if ($this->userToken['user_id'] > 0) {
                $this->user         = UserService::instance()->getDataById($this->userToken['user_id']);
                unset($this->user['pwd']);
                $this->loginStatus  = 1;
            }

            if ($this->userToken['user_id'] <= 0 || empty($this->user)) {
                $this->error('USER DATA error');
            }

            if ($this->userToken['is_forever'] == 0) {
                if ($this->userToken['time'] + (86400 * 2) > UTC_CURRENT_TIME) {
                    $this->error('USER TOKEN Expired');
                }
            } 

            if ($this->userToken['time'] < $this->user['logout_time']) {
                $this->error('USER TOKEN error');
            }

            return true;
        }
    }
?>