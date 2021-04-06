<?php
    namespace App\Application\Api\Services;
    use App\Application\Api\Models\User;
    use App\Application\Api\Models\LogUserCourse;
    use App\Application\Api\Models\Course;
    use DB, Helper;

    class UserService extends \App\Services\UserService {
        public function login($username, $pwd) {
            $user = $this->getDataByUsername($username);
            if (empty($user)) {
                $this->showError('User not found');
            }

            $this->checkPwd($pwd, $user['pwd']);

            if ($user['status'] != 1) {
                $this->showError('This user has been deactivated');
            }

            unset($user['pwd']);

            try {
                DB::connection()->beginTransaction();

                $user->login_time         = UTC_CURRENT_TIME;
                $user->login_count        = $user->login_count + 1;
                $user->save();

                DB::connection()->commit();
                return $user;
            } catch(\Exception $e) { 
                DB::connection()->rollback();
                throw $e;
            }
        }

        public function update($pwd, $level, $branch, $status, $user){
            try {
                DB::connection()->beginTransaction();

                $userData                     = $this->getDataById($user->id);
                if (isset($pwd) && !empty($pwd)) {
                    $userData->pwd            = $this->encryptPwd($pwd);
                }
                $userData->status             = $status;
                $userData->update_time        = UTC_CURRENT_TIME;
                $userData->save();

                DB::connection()->commit();
                return true;
            } catch(\Exception $e) { 
                DB::connection()->rollback();
                throw $e;
            }
        }

        public function logout($user){
            try {
                DB::connection()->beginTransaction();

                $userData                     = $this->getDataById($user->id);
                $userData->logout_time        = UTC_CURRENT_TIME;
                $userData->save();

                DB::connection()->commit();
                return true;
            } catch(\Exception $e) { 
                DB::connection()->rollback();
                throw $e;
            }
        }
    }
?>