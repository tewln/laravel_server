<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAuthData;
use App\Models\UserContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Показать профиль пользователя
     */
    public function profile()
    {
        $user = User::with(['contacts', 'authData'])->find(Auth::id());
        $orders = DB::table('db_project.orders')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
        
        return view('users.profile', compact('user', 'orders'));
    }
    
    /**
     * Отобразить форму редактирования профиля
     */
    public function edit()
    {
        $user = Auth::user();
        $regions = DB::table('db_project.warehouses')
            ->select('region')
            ->distinct()
            ->pluck('region');
        
        return view('users.edit', compact('user', 'regions'));
    }
    
    /**
     * Обновить профиль пользователя
     */
    public function update(Request $request)
    {
        $user = User::with('authData')->find(Auth::id());
        $validated = $request->validate([
            'surname' => 'required|regex:/^[а-яА-ЯёЁa-zA-Z]+$/u',
            'firstname' => 'required|regex:/^[а-яА-ЯёЁa-zA-Z]+$/u',
            'lastname' => 'nullable|regex:/^[а-яА-ЯёЁa-zA-Z]+$/u',
            'region' => 'required|exists:warehouses,region',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('users.profile')
            ->with('success', 'Профиль успешно обновлен');
    }
    
    /**
     * Отобразить форму изменения пароля
     */
    public function changePasswordForm()
    {
        return view('users.change-password');
    }
    
    /**
     * Изменить пароль пользователя
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|regex:/^[0-9a-zA-Z!#$%&?]+$/|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ]);
        
        $user = Auth::user();
        $authData = $user->authData;
        
        // Проверка текущего пароля
        if (!Hash::check($validated['current_password'], $authData->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
        }
        
        $authData->password = Hash::make($validated['new_password']);
        $authData->save();
        
        return redirect()->route('users.profile')
            ->with('success', 'Пароль успешно изменен');
    }
    
    /**
     * Отобразить форму добавления номера телефона
     */
    public function addPhoneForm()
    {
        return view('users.add-phone');
    }
    
    /**
     * Добавить номер телефона
     */
    public function addPhone(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|regex:/^\+[0-9]+$/|unique:users_contacts,phone_number,NULL,id,user_id,' . Auth::id(),
        ]);
        
        $contact = new UserContact();
        $contact->user_id = Auth::id();
        $contact->phone_number = $validated['phone_number'];
        $contact->store();
        
        return redirect()->route('users.profile')
            ->with('success', 'Номер телефона добавлен');
    }
    
    /**
     * Удалить номер телефона
     */
    public function removePhone($phoneNumber)
    {
        $contact = UserContact::where('user_id', Auth::id())
            ->where('phone_number', $phoneNumber)
            ->firstOrFail();
            
        $contact->delete();
        
        return redirect()->route('users.profile')
            ->with('success', 'Номер телефона удален');
    }
}