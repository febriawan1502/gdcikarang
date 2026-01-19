<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $users = User::all();
        $companySettings = Setting::getByGroup('company');
        
        return view('settings.index', compact('users', 'companySettings'));
    }

    /**
     * Update company settings
     */
    public function updateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'up3_name' => 'required|string|max:255',
            'warehouse_location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Setting::set('company_name', $request->company_name);
            Setting::set('up3_name', $request->up3_name);
            Setting::set('warehouse_location', $request->warehouse_location);

            Setting::clearCache();

            return back()->with('success', 'Pengaturan perusahaan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return back()->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return back()->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting own account
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
            }

            $user->delete();

            return back()->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
