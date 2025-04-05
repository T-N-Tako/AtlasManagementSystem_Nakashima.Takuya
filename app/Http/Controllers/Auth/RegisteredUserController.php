<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DB;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Users\Subjects;
use App\Models\Users\User;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        // 追記
        $birth_day = $request->validatedBirthDate();

        DB::beginTransaction();
        try {
            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $request->birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);

            if ($request->role == 4 && is_array($request->subject)) {
                $user_get->subjects()->attach($request->subject);
            }

            DB::commit();
            // 登録成功後のリダイレクト
            return redirect()->route('loginView')->with('success', '登録が完了しました。ログインしてください。');
        } catch (\Exception $e) {
            // ロールバック（エラー時）
            DB::rollback();
            // dd($e->getMessage());
            return redirect()->route('loginView');
        }
    }
}
