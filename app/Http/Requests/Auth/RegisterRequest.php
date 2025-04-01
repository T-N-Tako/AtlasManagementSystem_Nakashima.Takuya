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
            //
            // 追記
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],
            'over_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:30'],
            'under_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:30'],
            'mail_address' => ['required', 'email', 'max:100', 'unique:users,mail_address'],
            'sex' => ['required', 'in:1,2,3'],
            'old_year' => ['required', 'numeric', 'min:2000', 'max:' . now()->year],
            'old_month' => ['required', 'numeric', 'min:1', 'max:12'],
            'old_day' => ['required', 'numeric', 'min:1', 'max:31'],
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

            'old_year.required' => '年を選択してください。',
            'old_year.min' => '年は2000年以降である必要があります。',

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
        // 生年月日の正当性チェック
        // すべてintにキャストしてからcheckdateに渡す
        $year = (int) $this->old_year;
        $month = (int) $this->old_month;
        $day = (int) $this->old_day;

        if (!checkdate($month, $day, $year)) {
            $this->merge(['invalid_date' => true]);
        }
        // if (!checkdate($this->old_month, $this->old_day, $this->old_year)) {
        //     $this->merge(['invalid_date' => true]);
        // }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->invalid_date ?? false) {
                $validator->errors()->add('birth_day', '正しい日付を選択してください。');
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
