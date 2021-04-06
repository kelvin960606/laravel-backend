<?php
    namespace App\Application\Api\Controllers;
    use App\Application\Api\Services\BranchService;
    use Illuminate\Http\Request;

    class BranchController extends AuthController {

        public function Created(Request $request) {
           
            $result = BranchService::instance()->create(
                $request->post('name')
            );

            $this->success($result);
        }
    }