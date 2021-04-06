<?php
    namespace App\Application\Api\Controllers;
    use App\Application\Api\Services\UserService;
    use Illuminate\Http\Request;

    class UserController extends AuthController {
        public function info(Request $request) {
            $this->success($this->user);
        }

        public function update(Request $request) {
            $this->validate($request, [
                'password'      => 'nullable|string|min:6',
                'level'         => 'nullable',
                'branch'        => 'nullable',
                'status'        => 'nullable'
            ]);

            $result = UserService::instance()->update(
                $request->post('password'),
                $request->post('level'),
                $request->post('branch'),
                $request->post('status'),
                $this->user
            );

            $this->success();
        }

        public function logout() {
            $result = UserService::instance()->logout(
                $this->user
            );
            $this->success();
        }
    }
?>