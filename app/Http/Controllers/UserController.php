<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;


class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'vardas' => 'required',
            'el_paštas' => 'required|email|unique:users,email',
            'slaptažodis' => 'required|same:patvirtinti_slaptažodį',
            'rolė' => 'required'
        ]);


        $input = $request->all();
        $input['slaptažodis'] = Hash::make($input['slaptažodis']);


        $user = User::create([
            'name' => $input['vardas'],
            'email' => $input['el_paštas'],
            'password' => $input['slaptažodis']
        ]);
        $user->assignRole($request->input('rolė'));


        return redirect()->route('users.index')
                        ->with('success','Naudotojas sukurtas sėkmingai');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        return view('users.edit',compact('user','roles','userRole'));
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
        $this->validate($request, [
            'vardas' => 'required',
            'el_paštas' => 'required|email|unique:users,email,'.$id,
            'rolė' => 'required'
        ]);


        $input = $request->all();
        $user = User::find($id);
        if(!empty($input['slaptažodis'])){
            $input['slaptažodis'] = Hash::make($input['slaptažodis']);
            $user->password = $input['slaptažodis'];
        }else{
            $input = array_except($input,array('slaptažodis'));
        }

        $user->name = $input['vardas'];
        $user->email = $input['el_paštas'];

        DB::table('model_has_roles')->where('model_id',$id)->delete();


        $user->assignRole($request->input('rolė'));
        $user->save();

        return redirect()->route('users.index')
                        ->with('success','Naudotojo informacija sėkmingai pakeista.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','Naudotojas sėkmingai pašalintas.');
    }
}
