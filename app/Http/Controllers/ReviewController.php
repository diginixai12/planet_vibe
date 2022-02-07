<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->current_menu='reviews';
        $this->table_name='reviews';
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
        $records = Review::join('users', 'users.id', '=', 'reviews.user_id')
            ->select('reviews.*', DB::raw("CONCAT_WS(' ', users.first_name, users.last_name) as full_name"))
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
