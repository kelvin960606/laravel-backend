<?php
    namespace App\Services;
    use App\Models\User;
    use DB;

    class UserService extends BaseService {
        public function create($name, $pwd, $level, $branch) {
            $isNameExist = $this->checkName(null, $name);
            if ($isNameExist) {
                $this->showError('Username already existed');
            }

            try {
                DB::connection()->beginTransaction();

                $user                  = new User();
                $user->username        = $name;
                $user->pwd             = $this->encryptPwd($pwd);
                $user->status          = 1;
                $user->level           = $level;
                $user->branch          = $branch;
                $user->login_time      = null;
                $user->login_ip        = null;
                $user->login_count     = 0;
                $user->create_time     = UTC_CURRENT_TIME;
                $user->update_time     = UTC_CURRENT_TIME;
                $user->save();

                DB::connection()->commit();
                return true;
            } catch(\Exception $e) {
                DB::connection()->rollback();
                throw $e;
            }
        }

        public function getDataById($id) {
            return User::where('id', $id)->get()->first();
        }

        public function getDataByUsername($username) {
            return User::where('username', $username)->get()->first();
        }

        protected function checkName($id = null, $name) {
            if ($id == null) {
                return User::where('username', $name)->get()->first();
            } else {
                return User::where('id', '!=', $id)->where('username', $name)->get()->first();
            }
        }
    }
?>