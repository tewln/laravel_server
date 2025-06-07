<?php

namespace App\Http\Controllers;

use App\Models\UserContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserContactController extends Controller
{
    /**
     * Показать форму добавления номера телефона
     */
    public function create()
    {
        return view('users.add-phone');
    }

    /**
     * Сохранить номер телефона пользователя
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => [
                'required',
                'string',
                'regex:/^\+[0-9]+$/',
                'max:15',
                function ($attribute, $value, $fail) {
                    $exists = UserContact::where('user_id', Auth::id())
                                        ->where('phone_number', $value)
                                        ->exists();
                    if ($exists) {
                        $fail('Этот номер телефона уже добавлен к вашему аккаунту.');
                    }
                },
            ],
        ], [
            'phone_number.required' => 'Номер телефона обязателен для заполнения.',
            'phone_number.regex' => 'Номер телефона должен начинаться с + и содержать только цифры.',
            'phone_number.max' => 'Номер телефона не может быть длиннее 15 символов.',
        ]);

        try {
            DB::table('db_project.users_contacts')->insert([
                'user_id' => Auth::id(),
                'phone_number' => $request->phone_number,
            ]);

            return redirect()->route('users.profile')
                           ->with('success', 'Номер телефона успешно добавлен!');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'users_phone_is_valid')) {
                return back()->withErrors([
                    'phone_number' => 'Номер телефона не соответствует требованиям формата.'
                ])->withInput();
            }
            
            return back()->withErrors([
                'phone_number' => 'Произошла ошибка при добавлении номера телефона.'
            ])->withInput();
        }
    }

    /**
     * Удалить номер телефона пользователя
     */
    public function destroy($phoneNumber)
    {
        try {
            $deleted = DB::table('db_project.users_contacts')
                        ->where('user_id', Auth::id())
                        ->where('phone_number', $phoneNumber)
                        ->delete();

            return redirect()->route('users.profile')
                ->with($deleted ? 'success' : 'error', $deleted ? 'Номер телефона успешно удален!' : 'Номер телефона не найден.');
        } catch (\Exception $e) {
            return redirect()->route('users.profile')
                ->with('error', 'Произошла ошибка при удалении номера телефона.');
        }
    }
}