<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use DB;

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
    public function store(Request $request)
    {
        // 追記
        // バリデーション
        $request->validate([
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
            'under_name_kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
            'mail_address' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'mail_address')
            ],
            'sex' => 'required|in:1,2,3',
            'old_year' => 'required|numeric|min:2000|max:' . now()->year,
            'old_month' => 'required|numeric|min:1|max:12',
            'old_day' => 'required|numeric|min:1|max:31',
            'role' => 'required|in:1,2,3,4',
            'subject' => 'nullable|array',
            'subject.*' => 'exists:subjects,id',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:/^[a-zA-Z0-9]+$/',
                'confirmed'
            ],
        ], [
            'over_name.required' => '姓は必須です。',
            'over_name.string' => '姓は文字列で入力してください。',
            'over_name.max' => '姓は10文字以内で入力してください。',

            'under_name.required' => '名は必須です。',
            'under_name.string' => '名は文字列で入力してください。',
            'under_name.max' => '名は10文字以内で入力してください。',

            'over_name_kana.required' => 'セイは必須です。',
            'over_name_kana.string' => 'セイは文字列で入力してください。',
            'over_name_kana.regex' => 'セイはカタカナで入力してください。',
            'over_name_kana.max' => 'セイは30文字以内で入力してください。',

            'under_name_kana.required' => 'メイは必須です。',
            'under_name_kana.string' => 'メイは文字列で入力してください。',
            'under_name_kana.regex' => 'メイはカタカナで入力してください。',
            'under_name_kana.max' => 'メイは30文字以内で入力してください。',

            'mail_address.required' => 'メールアドレスは必須です。',
            'mail_address.email' => '正しいメールアドレスの形式で入力してください。',
            'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
            'mail_address.unique' => 'このメールアドレスはすでに登録されています。',

            'sex.required' => '性別を選択してください。',
            'sex.in' => '性別の値が不正です。',

            'old_year.required' => '年を選択してください。',
            'old_year.min' => '年は2000年以降である必要があります。',
            'old_year.max' => '年が未来になっています。',
            'old_month.required' => '月を選択してください。',
            'old_day.required' => '日を選択してください。',

            'role.required' => '役職を選択してください。',
            'role.in' => '選択された役職が不正です。',

            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以内で入力してください。',
            'password.regex' => 'パスワードは英数字のみで入力してください。',
            'password.confirmed' => '確認用パスワードと一致していません。',
        ]);
        // 生年月日を結合
        // $data = $request->old_year . '-' . $request->old_month . '-' . $request->old_day;
        // $birth_day = date('Y-m-d', strtotime($data));
        // $subjects = $request->subject;

        // checkdate() を使って正しい日付か確認
        $year = (int) $request->old_year;
        $month = (int) $request->old_month;
        $day = (int) $request->old_day;

        if (!checkdate($month, $day, $year)) {
            return back()->withErrors(['birth_date' => '正しい日付を選択してください（例: 2月31日は存在しません）'])->withInput();
        }

        $birth_day = sprintf('%04d-%02d-%02d', $year, $month, $day); // 整形された日付
        $subjects = $request->subject;



        DB::beginTransaction();
        try {
            // $old_year = $request->old_year;
            // $old_month = $request->old_month;
            // $old_day = $request->old_day;
            // $data = $old_year . '-' . $old_month . '-' . $old_day;
            // $birth_day = date('Y-m-d', strtotime($data));
            // $subjects = $request->subject;

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);
            if ($request->role == 4) {
                $user = User::findOrFail($user_get->id);
                $user->subjects()->attach($subjects);
            }
            DB::commit();
            // 登録成功後のリダイレクト
            return redirect()->route('loginView')->with('success', '登録が完了しました。ログインしてください。');
        } catch (\Exception $e) {
            // ロールバック（エラー時）
            DB::rollback();
            return redirect()->route('loginView');
        }
    }
}
