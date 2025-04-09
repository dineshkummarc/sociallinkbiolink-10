<?php

namespace Modules\Sociallink\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Helpers\PageHeader;
use App\Services\Toastr;
use App\Traits\Uploader;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProfessionController extends Controller
{
    use Uploader;

    public function __construct()
    {
        $this->middleware('permission:category');
    }

    public function index()
    {
        PageHeader::set()
            ->title(__("Professions"))
            ->buttons([
                [
                    'name' => '<i class="fa fa-plus"></i>&nbsp' . __('Add New'),
                    'url' => '#',
                    'target' => '#addNewCategoryDrawer',
                ]
            ])
            ->overviews([
                [
                    'title' => __('Total'),
                    'value' => Category::whereType('profession')->count(),
                    'icon' => 'bx:list-ul',
                    'style' => 'bg-primary-500/20 text-primary-600'
                ],
                [
                    'title' => __('Active'),
                    'value' => Category::whereType('profession')->where('status', 1)->count(),
                    'icon' => 'bx:check',
                    'style' => 'bg-primary-500/20 text-primary-600'
                ],
                [
                    'title' => __('Inactive'),
                    'value' => Category::whereType('profession')->where('status', 0)->count(),
                    'icon' => 'bx:x',
                    'style' => 'bg-primary-500/20 text-primary-600'
                ],
            ]);

        $categories = Category::whereType('profession')->latest()->paginate(10);
        $languages = get_option('languages', true);

        return Inertia::render('Admin/Profession/Index', [
            'categories' => $categories,
            'languages' => $languages,
        ]);
    }

    public function store(Request $request)
    {

        if (env('DEMO_MODE')) {
            return back()->with('danger', __('Permission disabled for demo mode..!'));
        }

        $request->validate([
            'title' => ['required', 'min:2', 'max:100'],
            'icon' => ['required', 'image', 'mimes:png,jpg,jpeg,gif,svg,webp'],
        ]);

        if ($request->hasFile('icon')) {
            $icon = $this->saveFile($request, 'icon');
        }

        Category::create([
            'title' => $request->title,
            'icon' => $icon ?? null,
            'status' => $request->status,
            'type' => 'profession',
            'slug' => Str::slug($request->title),
            'is_featured' => 0
        ]);

        Toastr::success('Created Successfully');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {

        if (env('DEMO_MODE')) {
            return back()->with('danger', __('Permission disabled for demo mode..!'));
        }

        $request->validate([
            'category.title' => ['required', 'min:2', 'max:100'],
        ]);

        if ($request->hasFile('category.icon')) {
            $request->validate([
                'category.icon' => ['required', 'image', 'mimes:png,jpg,jpeg,gif,svg,webp'],
            ]);
            $icon = $this->saveFile($request, 'category.icon');
        }

        $category = Category::findOrFail($id);

        $category->update([
            'title' =>  $request->category['title'],
            'status' => $request->category['status'],
            'icon' => $icon ?? $category->icon,
            'is_featured' => $request->category['is_featured'],
            'slug' => Str::slug($request->category['title']),
        ]);
        Toastr::success('Updated Successfully');

        return redirect()->back();
    }

    public function destroy($id)
    {

        if (env('DEMO_MODE')) {
            return back()->with('danger', __('Permission disabled for demo mode..!'));
        }

        $category = Category::findOrFail($id);
        $this->removeFile($category->preview);
        $category->delete();
        Toastr::danger('Deleted Successfully');
        return redirect()->back();
    }
}
