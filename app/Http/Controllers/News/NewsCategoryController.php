<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewsCategoryRequest;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NewsCategoryController extends Controller
{
    public function index() {
        $categories = NewsCategory::orderByDesc('id')->paginate(10)->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });
        return Inertia::render('NewsCategory/Index', [
            'categories' => $categories,
        ]);
    }

    public function save(NewsCategoryRequest $request) {
        $name = $request->get('name');
        NewsCategory::create([
            'name' => $name
        ]);
        return Redirect::route('news-category.index');
    }
}
