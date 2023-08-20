<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // $thisはリクエストそのもの（LoginRequestオブジェクト）
        $this->ensureIsNotRateLimited();
        // Auth::attempt()は、ユーザーが認証された場合はtrueを返し、認証されなかった場合はfalseを返す。第一引数にリクエストから取得したemailとpasswordを渡す。第二引数には、ユーザーが「ログイン状態を保持する」のチェックボックスをチェックした場合はtrue、チェックしなかった場合はfalseを渡す。
        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // ログイン認証が失敗したら以下の処理
            // throttleKey()メソッドは、ユーザーのemailとIPアドレスを結合した文字列を返すことでユーザーを識別する。hit()メソッドは、第一引数に指定したキーの値をインクリメントする。第二引数には、リクエストが拒否された場合に何秒後に再試行できるかを指定する。デフォルト値が60秒になっている。
            RateLimiter::hit($this->throttleKey());

            // 
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    // ログイン試行回数が5回を超えた場合、ログインを拒否する関数
    public function ensureIsNotRateLimited(): void
    {
        // tooManyAttempts()メソッドは、第一引数に指定したキーの値が第二引数に指定した回数を超えている場合はtrue、そうでない場合はfalseを返す。
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        // 5回を超えた場合は以下の処理
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
