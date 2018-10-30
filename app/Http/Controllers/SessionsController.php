<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 获取登录表单
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * 提交登录
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $login_info = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($login_info, $request->has('remember'))) {
            //登录成功
            session()->flash('success', '欢迎回来！');
            return redirect()->intended(route('users.show', [Auth::user()]));
        } else {
            //登录失败
            session()->flash('danger', '抱歉，您的邮箱和密码不匹配！');
            return redirect()->back();
        }
        return;
    }

    /**
     * 退出登录
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
