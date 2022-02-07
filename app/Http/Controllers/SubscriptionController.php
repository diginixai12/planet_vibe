<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->current_menu='subscriptions';
        $this->table_name='subscriptions';
    }
    public function index(Request $request)
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $records = Subscription::join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('categories', 'categories.id', '=', 'subscriptions.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'subscriptions.sub_category_id')
            ->select('subscriptions.*', DB::raw("CONCAT_WS(' ', users.first_name, users.last_name) as full_name"), 'categories.category as category', 'sub_categories.sub_category as sub_category')
            ->where($table_name.'.status', '!=', 9)
            ->orderBy($table_name.'.id', 'DESC')
            ->get();
        if ($request->ajax()) {
            $datatables = Datatables::of($records)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-warning">Inactive</span>';
                    }
                })
                ->addColumn('actions', function($row) use ($current_menu, $table_name) {
                    if ($row->status == 1) {
                        $action1 = '<button type="button" rel="tooltip" title="Inactive User" class="btn btn-warning btn-link btn-sm" onclick="confirmChangeStatusAction(`'.$table_name.'`, '.$row->id.', '.$row->status.');"><i class="material-icons">rotate_left</i></button>';
                    } else {
                        $action1 = '<button type="button" rel="tooltip" title="Active User" class="btn btn-success btn-link btn-sm" onclick="confirmChangeStatusAction(`'.$table_name.'`, '.$row->id.', '.$row->status.');"><i class="material-icons">rotate_right</i></button>';
                    }
                    $encrypt_id = Crypt::encryptString($row->id);
                    $url = url($current_menu.'/'.$encrypt_id.'/edit');
                    $action2 = '<a href="'.$url.'" rel="tooltip" title="Edit User" class="btn btn-primary btn-link btn-sm"><i class="material-icons">edit</i></a>';
                    $action3 = '<button type="button" rel="tooltip" title="Remove User" class="btn btn-danger btn-link btn-sm" onclick="confirmDeleteAction(`'.$table_name.'`, '.$row->id.');"><i class="material-icons">close</i></button>';
                    $action = $action1.$action2.$action3;
                    return $action;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
            return $datatables;
        }
        return view($current_menu.'.index', [
            'current_menu'=>$current_menu,
            'table_name'=>$table_name,
            'records' => $records,
        ]);
    }
    public function get_providers()
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $providers = DB::table('users')
            ->where('users.user_type', '=', 'provider')
            ->where('users.status', '=', 1)
            ->pluck(DB::raw("CONCAT_WS(' ', users.first_name, users.last_name) as full_name"), 'users.id as id');
        return view($current_menu.'.get_providers', [
            'current_menu'=>$current_menu,
            'table_name'=>$table_name,
            'providers'=>$providers,
        ]);
    }
    public function get_categories(Request $request)
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $validate = $request->validate([
            'provider_id' => 'required',
        ]);
        $provider_id = $request->provider_id;
        $categories = Category::where('categories.status', '=', 1)
            ->orderBy('categories.id', 'DESC')
            ->get();
        return view($current_menu.'.get_categories', [
            'current_menu'=>$current_menu,
            'table_name'=>$table_name,
            'provider_id'=>$provider_id,
            'categories'=>$categories,
        ]);
    }
    public function get_sub_categories(Request $request, $id, $_id)
    {
      $current_menu = $this->current_menu;
      $table_name = $this->table_name;
      $provider_id = $id;
      $category_id = Crypt::decryptString($_id);
      $sub_categories = SubCategory::join('categories', 'categories.id', '=', 'sub_categories.category_id')
          ->select('sub_categories.*', 'categories.category as category')
          ->where('sub_categories.status', '=', 1)
          ->where('categories.id', '=', $category_id)
          ->orderBy('sub_categories.id', 'DESC')
          ->get();
      return view($current_menu.'.get_sub_categories', [
          'current_menu'=>$current_menu,
          'table_name'=>$table_name,
          'provider_id'=>$id,
          'encrypt_id'=>$_id,
          'sub_categories'=>$sub_categories,
      ]);
    }
    public function checkout(Request $request, $id, $_id)
    {
      $current_menu = $this->current_menu;
      $table_name = $this->table_name;
      $provider_id = $id;
      $decrypt_id = Crypt::decryptString($_id);
      $validate = $request->validate([
          'sub_category_id' => 'required',
      ]);
      $sub_category_id = $request->sub_category_id;
      $user = User::where('users.id', '=', $provider_id)
          ->select('users.*', DB::raw("CONCAT_WS(' ', users.first_name, users.last_name) as full_name"))
          ->first();
      $sub_category = SubCategory::join('categories', 'categories.id', '=', 'sub_categories.category_id')
          ->select('sub_categories.*', 'categories.category as category', 'categories.image as category_image')
          ->where('sub_categories.id', '=', $sub_category_id)
          ->first();
      $subscription = $request->subscription;
      $_days = $subscription . '_days';
      $days = $sub_category->$_days;
      $_price = $subscription . '_price';
      $price = $sub_category->$_price;
      $start_date = date('Y-m-d H:i:s');
      $strtotime = '+' . $days . ' days';
      $end_date = date('Y-m-d H:i:s', strtotime($strtotime));
      $subscription_summary = [
          'user_id' => $user->id,
          'user' => $user->full_name,
          'category_id' => $sub_category->category_id,
          'category' => $sub_category->category,
          'category_image' => $sub_category->category_image,
          'sub_category_id' => $sub_category->id,
          'sub_category' => $sub_category->sub_category,
          'sub_category_image' => $sub_category->image,
          'subscription' => $subscription,
          'days' => $days,
          'price' => $price,
          'start_date' => $start_date,
          'end_date' => $end_date,
      ];
      return view($current_menu.'.checkout', [
          'current_menu'=>$current_menu,
          'table_name'=>$table_name,
          'provider_id'=>$id,
          'encrypt_id'=>$_id,
          'user'=>$user,
          'sub_category'=>$sub_category,
          'subscription_summary'=>$subscription_summary,
      ]);
    }
    public function pay_securely(Request $request, $id, $_id)
    {
      $current_menu = $this->current_menu;
      $table_name = $this->table_name;
      $provider_id = $id;
      $decrypt_id = Crypt::decryptString($_id);
      $validate = $request->validate([
        'user_id' => 'required',
        'category_id' => 'required',
        'sub_category_id' => 'required',
        'subscription' => 'required',
        'validity' => 'required',
        'amount' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'name_on_card' => 'required',
        'card_number' => 'required',
        'expiry_date' => 'required',
        'csv' => 'required',
      ]);
      DB::beginTransaction();
      $temp_array = [
        'user_id' => !empty($request->user_id)?$request->user_id:0,
        'category_id' => !empty($request->category_id)?$request->category_id:0,
        'sub_category_id' => !empty($request->sub_category_id)?$request->sub_category_id:0,
        'subscription' => !empty($request->subscription)?$request->subscription:'N/A',
        'validity' => !empty($request->validity)?$request->validity:0,
        'amount' => !empty($request->amount)?$request->amount:0,
        'start_date' => !empty($request->start_date)?$request->start_date:date('Y-m-d H:i:s'),
        'end_date' => !empty($request->end_date)?$request->end_date:date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => Auth::user()->id,
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => Auth::user()->id,
        'status' => 1,
      ];
      $query = Subscription::create($temp_array);
      if ($query)
      {
          DB::commit();
          return view($current_menu.'.success', [
              'current_menu'=>$current_menu,
              'table_name'=>$table_name,
              'provider_id'=>$id,
              'encrypt_id'=>$_id,
          ]);
      } else {
          DB::rollback();
          return view($current_menu.'.error', [
              'current_menu'=>$current_menu,
              'table_name'=>$table_name,
              'provider_id'=>$id,
              'encrypt_id'=>$_id,
          ]);
      }

    }
}
