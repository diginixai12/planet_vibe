<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->current_menu='sub_categories';
        $this->table_name='sub_categories';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $records = SubCategory::join('categories', 'categories.id', '=', 'sub_categories.category_id')
            ->select('sub_categories.*', 'categories.category as category')
            ->where($table_name.'.status', '!=', 9)
            ->orderBy($table_name.'.id', 'DESC')
            ->get();
        if ($request->ajax()) {
            $datatables = Datatables::of($records)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    $url= asset('images/sub_categories/'.$row->image);
                    return '<img src="'.$url.'" border="0" width="50" height="50" align="center" alt="image" />';
                })
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
                ->rawColumns(['image', 'status', 'actions'])
                ->make(true);
            return $datatables;
        }
        return view($current_menu.'.index', [
            'current_menu'=>$current_menu,
            'table_name'=>$table_name,
            'records' => $records,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $categories = DB::table('categories')
            ->where('categories.status', '=', 1)
            ->pluck('categories.category as category', 'categories.id as id');
        return view($current_menu.'.create', [
            'current_menu'=>$current_menu,
            'table_name'=>$table_name,
            'categories'=>$categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $validate = $request->validate([
            'category_id' => 'required',
            'sub_category' => 'required',
            'description' => 'required',
            'monthly_days' => 'required',
            'monthly_price' => 'required',
            'quarterly_days' => 'required',
            'quarterly_price' => 'required',
            'halfyearly_days' => 'required',
            'halfyearly_price' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
        ]);
        if($file=$request->file('image'))
        {
            $date = date('YmdHis');
            $no = str_shuffle('12345678902345678903456789045678905678906789078908909000987654321987654321876543217654321654321543214321321211');
            $random_no = substr($no, 0, 2);
            $final_image_name = $date.$random_no.'.'.$file->getClientOriginalExtension();
            $destination_path = public_path('/images/sub_categories/');
            $file->move($destination_path , $final_image_name);
        }
        DB::beginTransaction();
            $temp_array = [
                'category_id' => !empty($request->category_id)?$request->category_id:0,
                'sub_category' => !empty($request->sub_category)?$request->sub_category:'N/A',
                'description' => !empty($request->description)?$request->description:'N/A',
                'monthly_days' => !empty($request->monthly_days)?$request->monthly_days:0,
                'monthly_price' => !empty($request->monthly_price)?$request->monthly_price:0,
                'quarterly_days' => !empty($request->quarterly_days)?$request->quarterly_days:0,
                'quarterly_price' => !empty($request->quarterly_price)?$request->quarterly_price:0,
                'halfyearly_days' => !empty($request->halfyearly_days)?$request->halfyearly_days:0,
                'halfyearly_price' => !empty($request->halfyearly_price)?$request->halfyearly_price:0,
                'is_new' => !empty($request->is_new)?$request->is_new:'no',
                'is_popular' => !empty($request->is_popular)?$request->is_popular:'no',
                'image' => $final_image_name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ];
        $query = SubCategory::create($temp_array);
        if ($query) 
        {
            DB::commit();
            Session::flash('message', 'Category added successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            DB::rollback();
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->intended($current_menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $decrypt_id = Crypt::decryptString($id);
        $records = DB::table($table_name)
            ->where($table_name.'.id', $decrypt_id)
            ->first();
        $categories = DB::table('categories')
            ->where('categories.status', '=', 1)
            ->pluck('categories.category as category', 'categories.id as id');
        return view($current_menu.'.edit', [
            'current_menu'=>$current_menu,
            'table_name'=>$table_name,
            'encrypt_id'=>$id,
            'records' => $records,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $current_menu = $this->current_menu;
        $table_name = $this->table_name;
        $decrypt_id = Crypt::decryptString($id);
        $validate = $request->validate([
            'category_id' => 'required',
            'sub_category' => 'required',
            'description' => 'required',
            'quarterly_days' => 'required',
            'quarterly_price' => 'required',
            'monthly_days' => 'required',
            'monthly_price' => 'required',
            'halfyearly_days' => 'required',
            'halfyearly_price' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
        ]);
        if($file=$request->file('image'))
        {
            $date = date('YmdHis');
            $no = str_shuffle('12345678902345678903456789045678905678906789078908909000987654321987654321876543217654321654321543214321321211');
            $random_no = substr($no, 0, 2);
            $final_image_name = $date.$random_no.'.'.$file->getClientOriginalExtension();
            $destination_path = public_path('/images/sub_categories/');
            $file->move($destination_path , $final_image_name);
        }
        DB::beginTransaction();
            $temp_array = [
                'category_id' => !empty($request->category_id)?$request->category_id:0,
                'sub_category' => !empty($request->sub_category)?$request->sub_category:'N/A',
                'description' => !empty($request->description)?$request->description:'N/A',
                'monthly_days' => !empty($request->monthly_days)?$request->monthly_days:0,
                'monthly_price' => !empty($request->monthly_price)?$request->monthly_price:0,
                'quarterly_days' => !empty($request->quarterly_days)?$request->quarterly_days:0,
                'quarterly_price' => !empty($request->quarterly_price)?$request->quarterly_price:0,
                'halfyearly_days' => !empty($request->halfyearly_days)?$request->halfyearly_days:0,
                'halfyearly_price' => !empty($request->halfyearly_price)?$request->halfyearly_price:0,
                'is_new' => !empty($request->is_new)?$request->is_new:'no',
                'is_popular' => !empty($request->is_popular)?$request->is_popular:'no',
                'image' => $final_image_name,
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ];
        $query = SubCategory::where($table_name.'.id', $decrypt_id)->update($temp_array);
        if ($query) 
        {
            DB::commit();
            Session::flash('message', 'Sub Category updated successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            DB::rollback();
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->intended($current_menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
