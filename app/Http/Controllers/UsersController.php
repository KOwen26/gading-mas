<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Users $users)
    {
        //
        return view('pages.user', ['users' => $users::orderBy('user_name', 'asc')->get()]);
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
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(Users $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Users $users)
    {
        //
        // dd($request->id);
        return view("components.users.user-modal", ['route' => "user.update", 'id' => $request->id, 'user' => $users::find($request->id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Users $users)
    {
        //
        $check = $users::where([['user_email', $request->user_email], ['user_password', $request->old_password]])->get();
        if ($request->old_password || $request->new_password || $request->new_password_repeat) {
            if ($check->count() == 1) {
                if ($request->new_password !== $request->new_password_repeat) {
                    return redirect('user')->with('error', 'Password yang diberikan tidak sama');
                } else {
                    $user = $users::find($request->id);
                    $user->user_name = $request->user_name;
                    $user->user_phone = $request->user_phone;
                    $user->user_email = $request->user_email;
                    $user->user_password = $request->new_password;
                    $user->save();
                    return redirect('user')->with('success', 'Data pengguna berhasil diperbarui');
                }
            } else {
                return redirect('user')->with('error', 'Akun pengguna tidak dapat ditemukan');
            }
        } else {
            $user = $users::find($request->id);
            $user->user_name = $request->user_name;
            $user->user_phone = $request->user_phone;
            $user->user_email = $request->user_email;
            $user->save();
            return redirect('user')->with('success', 'Data pengguna berhasil diperbarui');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(Users $users)
    {
        //
    }
}