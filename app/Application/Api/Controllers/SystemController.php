<?php
    namespace App\Application\Api\Controllers;
    use App\Application\Api\Services\UserService;
    use App\Application\Api\Services\SystemService;
    use Illuminate\Http\Request;

    class SystemController extends BaseController {
        
        public function reg(Request $request) {
            $this->validate($request, [
                'username'      => 'required|string|min:4',
                'pwd'           => 'required|string|min:6',
                'level'         => 'nullable',
                'branch'        => 'nullable',
            ], [
                'username.required' => 'Username cannot be empty',
                'username.min'      => 'Username must be at least 4 characters',
                'pwd.required'      => 'Password cannot be empty',
                'pwd.min'           => 'Password must be at least 6 characters'
            ]);

            $result = UserService::instance()->create(
                $request->get('username'),
                $request->get('pwd'),
                $request->get('level'),
                $request->get('branch'),
            );

            $this->success();
        }

        public function login(Request $request) {
            $this->validate($request, [
                'username'          => 'required|string|min:4',
                'pwd'               => 'required|string|min:6'
            ], [
                'username.required' => 'Username cannot be empty',
                'username.min'      => 'Username must be at least 4 characters',
                'pwd.required'      => 'Password cannot be empty',
                'pwd.min'           => 'Password must be at least 6 characters'
            ]);

            $result = UserService::instance()->login(
                $request->get('username'),
                $request->get('pwd')
            );
            $result = $this->generateUserToken($result, 1);

            $this->success($result);
        }
    }
?>