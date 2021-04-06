<?php
    namespace App\Application\Api\Controllers;
    use Helper;
    use Illuminate\Http\Request;

    class AppController extends BaseController {
        private $client = ['pc', 'wap'];

        public function token(Request $request) {
            // $sid = $request->get('sid');
            // if (empty($sid) || strlen($sid) != 32) {
            //     $this->error('SID error');
            // }

            $client = $request->get('client');
            if (empty($client) || !in_array($client, $this->client)) {
                $this->error('CLIENT error');
            }
            
            $data           = [
                'user_id'   => 0,
                // 'sid'       => $sid,
                'client'    => $client,
                'ip'        => CLIENT_IP,
                'host'      => '',
                'source'    => '',
                'time'      => UTC_CURRENT_TIME,
                'is_forever'=> 0
            ];

            $result = [
                'token' => Helper::encryptData($data)
            ];

            $this->success($result);
        }
    }
?>