<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
//use App\Http\Requests;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    public function __construct()
    {
        //guest指定为登陆的用户的过滤    auth 指定已登陆的用户
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){
        $this -> validate($request,[
            'email' => 'required|email|min:3',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'),$request -> has('remember')) ){
            if(Auth::user()->activated) {
                session()->flash('success', '欢迎回来！');
                return redirect()->intended(route('users.show', [Auth::user()]));
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        }else{
            session() -> flash('danger', '很抱歉，您的用户名和密码不匹配');
            return redirect()-> back();
        }

    }


    public function destroy()
    {
        Auth::logout();
        session() -> flash('success', '您已成功退出！');
        return redirect('login');
    }

}
