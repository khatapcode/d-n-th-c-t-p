<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;

class UserController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm() {
        return view('login');
    }

    // Xử lý đăng nhập
    public function checkLogin(Request $request) {
        // Các thông báo lỗi tùy chỉnh
        $messages = [
            'email.required' => 'Địa chỉ email không được để trống.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
        ];
    
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);

        // Kiểm tra xem email và mật khẩu có phải là thông tin quản trị viên không
        if ($validatedData['email'] === 'admin@gmail.com' && $validatedData['password'] === '123') {
            // Nếu đúng, chuyển hướng đến trang admin
            return redirect()->route('admin.index');
        }
    
        // Thử đăng nhập
        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            // Nếu đăng nhập thành công, chuyển hướng đến trang chủ
            return redirect()->route('home.index');
        }

        // Kiểm tra xem email có tồn tại không
        if (User::where('email', $request->email)->exists()) {
            // Nếu email tồn tại, thông báo mật khẩu sai
            return back()->withErrors([
                'password' => 'Mật khẩu không đúng.',
            ])->withInput($request->only('email'));
        } else {
            // Nếu email không tồn tại, thông báo email sai
            return back()->withErrors([
                'email' => 'Email không tồn tại trong hệ thống.',
            ])->withInput($request->only('email'));
        }
    }    

    // Hiển thị form đăng ký
    public function showSignUpForm() {
        return view('signup');
    }
    // Thực hiện đăng ký
    public function postSignUp(Request $req) {
        // Các thông báo lỗi tùy chỉnh
        $messages = [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Địa chỉ email không được để trống.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email đã được sử dụng.',
            'password.required' => 'Mật khẩu không được để trống.',
        ];
    
        // Xác thực dữ liệu đầu vào
        $validatedData = $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ], $messages);
    
        // Mã hóa mật khẩu
        $req->merge(['password' => Hash::make($req->password)]);
    
        try {
            // Tạo người dùng mới
            User::create($req->all());
        } catch (\Throwable $th) {
            // Bắt và hiển thị lỗi nếu có
            return back()->withErrors(['error' => 'Đã có lỗi xảy ra, vui lòng thử lại sau.'])->withInput();
        }
    
        // Chuyển hướng đến trang đăng nhập sau khi đăng ký thành công
        return redirect()->route('signup')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }    

    // Đăng xuất
    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

// ADMIN
    public function showUser() {
        $users = User::paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function editUserForm(User $user) {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Cập nhật thông tin danh mục
        $user->update($validatedData);

        // Chuyển hướng người dùng về trang danh sách danh mục
        return redirect()->route('admin.user');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.user');
    }
}
