<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'password' => 'required',
        // ]);
        $this->validate($request,[
            'name'  =>'required|string|max:191',
            'email' =>'required|unique:users',
            'password'=>'required|string|min:9'
        ]);
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'bio'        => $request->bio,
            // 'photo'      => $request->photo,
            'type'       => $request->type,
            'password'   => Hash::make($request->password),
        ]);
        return $user;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request,[
            'name'  =>'required|string|max:191',
            'email' =>'required|email|max:191|unique:users,email,'.$user->id,
            'password'=>'sometimes|min:9'
        ]);
        $user->update($request->all());

        return ['message'=> 'User updated in successfully  '];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }
}
