<?php
    namespace App\Application\Api\Services;
    use App\Application\Api\Models\Branch;
    use DB, Helper;

    class BranchService extends \App\Services\BaseService {

        public function create($name) {
            try {
                DB::connection()->beginTransaction();
                
                $branch                   = new Branch();
                $branch->xName            = $name;
                $branch->save();
                DB::connection()->commit();
                
                return $branch;
            } catch(\Exception $e) { 
                DB::connection()->rollback();
                throw $e;
            }
        }
    }