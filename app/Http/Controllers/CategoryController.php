<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị các danh mục
    public function showCategory() {
        $cates = Category::orderBy('category_id', 'ASC')->paginate(5);
        return view('admin.category.index', compact('cates'));
    }

    // Hiển thị form thêm danh mục
    public function createCateForm() {
        $cates = Category::orderBy('category_id', 'DESC')->paginate(10);
        return view('admin.category.create', compact('cates'));
    }

    // Thêm danh mục
    public function store(Request $request) {
        $messages = [
            'category_name.required' => 'Tên danh mục không được để trống !!',
            'category_name.unique' => 'Tên danh mục đã tồn tại !!',
        ];
    
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ], $messages);

        Category::create($validatedData);

        return redirect()->route('admin.createcate')->with('success', 'Thêm danh mục thành công');
    }

    // Hiển thị form sửa danh mục
    public function editCateForm(Category $category) {
        return view('admin.category.edit', compact('category'));
    }

    // Sửa danh mục
    public function update(Request $request, Category $category) {
        $messages = [
            'category_name.required' => 'Tên danh mục không được để trống !!',
            'category_name.unique' => 'Tên danh mục đã tồn tại !!',
        ];
    
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ], $messages);

        $category->update($validatedData);

        // return redirect()->route('admin.category');
        return redirect()->route('admin.editcate', $category->category_id)->with('success', 'Sửa danh mục thành công');
    }

    // Xóa danh mục
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('admin.category');
    }
}
