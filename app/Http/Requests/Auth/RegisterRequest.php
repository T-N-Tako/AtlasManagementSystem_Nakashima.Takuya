<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }





    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 追記
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],
            'over_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:30'],
            'under_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:30'],
            'mail_address' => ['required', 'email', 'max:100', 'unique:users,mail_address'],
            'sex' => ['required', 'in:1,2,3'],
            'birth_day' => ['required', 'date', 'after_or_equal:2000-01-01', 'before_or_equal:' . now()->toDateString()],
            'role' => ['required', 'in:1,2,3,4'],
            'subject' => ['nullable', 'array'],
            'subject.*' => ['exists:subjects,id'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[a-zA-Z0-9]+$/', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
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

            'birth_day.required' => '生年月日は必須です。',
            'birth_day.date' => '正しい日付形式で入力してください（例: 2000-01-01）。',
            'birth_day.after_or_equal' => '生年月日は2000年1月1日以降の日付を入力してください。',
            'birth_day.before_or_equal' => '生年月日は本日以前の日付を入力してください。',

            'role.required' => '役職を選択してください。',
            'role.in' => '選択された役職が不正です。',

            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以内で入力してください。',
            'password.regex' => 'パスワードは英数字のみで入力してください。',
            'password.confirmed' => '確認用パスワードと一致していません。',
        ];
    }

    protected function prepareForValidation()
    {
        $year = $this->old_year;
        $month = $this->old_month;
        $day = $this->old_day;

        if ($year !== 'none' && $month !== 'none' && $day !== 'none') {
            $formatted = sprintf('%04d-%02d-%02d', $year, $month, $day);

            // checkdate で不正なら無効値を突っ込む
            if (checkdate((int) $month, (int) $day, (int) $year)) {
                $this->merge(['birth_day' => $formatted]);
            } else {
                $this->merge(['birth_day' => 'invalid-date']);
            }
        } else {
            $this->merge(['birth_day' => null]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->missing_birth_day ?? false) {
                $validator->errors()->add('birth_day', '生年月日は必須です。');
            } elseif ($this->invalid_birth_day ?? false) {
                $validator->errors()->add('birth_day', '正しい日付形式で入力してください（例: 2000-01-01）。');
            }
        });
    }

    /**
     * 生年月日を YYYY-MM-DD の形式で返す
     */
    public function validatedBirthDate()
    {
        return sprintf('%04d-%02d-%02d', $this->old_year, $this->old_month, $this->old_day);
    }
}
