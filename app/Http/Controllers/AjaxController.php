<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function takeChangeStatusAction(Request $request)
    {   
        $table_name = $request->table_name;
        $id = $request->id;
        $status = $request->status;
        if ($request->ajax() && !empty($table_name) && !empty($id) && isset($status)) {
            if ($status == 0) {
                $new_status = 1;
            }
            else {
                $new_status = 0;
            }
            DB::beginTransaction();
            $query = DB::table($table_name)->where('id', $id)->update(['status' => $new_status, 'updated_at'=>date('Y-m-d H:i:s')]);
            if ($query) {
                #commit transaction
                DB::commit();
                $data['code'] = 200;
                $data['result'] = 'success';
                $data['message'] = 'Action completed';
            }
            else
            {
                #rollback transaction
                DB::rollback();
                $data['code'] = 401;
                $data['result'] = 'failure';
                $data['message'] = 'Action can not be completed';
            }
        }
        else {
            $data['code'] = 401;
            $data['result'] = 'failure';
            $data['message'] = 'Unauthorized request';
        }
        return json_encode($data);
    }
    public function takeDeleteAction(Request $request)
    {   
        $table_name = $request->table_name;
        $id = $request->id;
        if ($request->ajax() && !empty($table_name) && !empty($id)) {
            DB::beginTransaction();
            $query = DB::table($table_name)->where('id', $id)->update(['status' => 9, 'deleted_at'=>date('Y-m-d H:i:s')]);
            if ($query) {
                #commit transaction
                DB::commit();
                $data['code'] = 200;
                $data['result'] = 'success';
                $data['message'] = 'Action completed';
            }
            else
            {
                #rollback transaction
                DB::rollback();
                $data['code'] = 401;
                $data['result'] = 'failure';
                $data['message'] = 'Action can not be completed';
            }
        }
        else {
            $data['code'] = 401;
            $data['result'] = 'failure';
            $data['message'] = 'Unauthorized request';
        }
        return json_encode($data);
    }
}
