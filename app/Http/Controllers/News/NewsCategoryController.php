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
    public function index(Request $request) {
        $search = $request->get('search', '');
        $categories = NewsCategory::query()
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            });
        return Inertia::render('NewsCategory/Index', [
            'categories' => $categories,
            'search' => $search
        ]);
    }

    public function save(NewsCategoryRequest $request) {
        $id = $request->get('id');
        $name = $request->get('name');
        if(empty($id)) {
            NewsCategory::create([
                'name' => $name
            ]);
            return Redirect::route('news-category.index');
        } else {
            NewsCategory::where('id', $id)
                ->update(['name' => $name]);
            return Redirect::back();
        }
    }

    public function delete(NewsCategoryRequest $request) {
        $search = $request->get('search', '');
        $id = $request->get('id');
        NewsCategory::findOrFail($id)->delete();
        return Redirect::back();
    }
}
