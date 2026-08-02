<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Banner;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppearanceController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.appearance.sections');
    }

    public function sections()
    {
        $sections = HomepageSection::orderBy('sort_order')->get();

        return view('admin.appearance.sections', compact('sections'));
    }

    public function createSection()
    {
        return view('admin.appearance.create-section');
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'type' => 'required|in:featured_products,best_sellers,new_arrivals,banners,hero,testimonials,categories,promo',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
        ]);

        $maxOrder = HomepageSection::max('sort_order') ?? 0;

        HomepageSection::create([
            'type' => $request->type,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'sort_order' => $request->sort_order ?? ($maxOrder + 1),
            'is_active' => $request->boolean('is_active', true),
            'settings' => $request->settings ?? [],
        ]);

        return redirect()->route('admin.appearance.sections')->with('success', 'Homepage section created successfully.');
    }

    public function editSection(HomepageSection $section)
    {
        return view('admin.appearance.edit-section', compact('section'));
    }

    public function updateSection(Request $request, HomepageSection $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
        ]);

        $section->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active'),
            'settings' => $request->settings ?? [],
        ]);

        return redirect()->route('admin.appearance.sections')->with('success', 'Homepage section updated successfully.');
    }

    public function deleteSection(HomepageSection $section)
    {
        $section->delete();

        return redirect()->route('admin.appearance.sections')->with('success', 'Homepage section deleted successfully.');
    }

    public function reorderSections(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:homepage_sections,id',
            'sections.*.sort_order' => 'required|integer',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            foreach ($request->sections as $item) {
                HomepageSection::where('id', $item['id'])->update([
                    'sort_order' => $item['sort_order'],
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function banners()
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('admin.appearance.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'image' => 'required|image|max:2048',
            'position' => 'required|in:hero,sidebar,footer,middle,top',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link,
            'link_text' => $request->link_text,
            'image' => $imagePath,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? (Banner::max('sort_order') ?? 0) + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.appearance.banners')->with('success', 'Banner created successfully.');
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'position' => 'required|in:hero,sidebar,footer,middle,top',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'subtitle', 'link', 'link_text', 'position', 'sort_order', 'is_active']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.appearance.banners')->with('success', 'Banner updated successfully.');
    }

    public function deleteBanner(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.appearance.banners')->with('success', 'Banner deleted successfully.');
    }

    public function testimonials()
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();

        return view('admin.appearance.testimonials', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'designation', 'company', 'content', 'rating', 'sort_order', 'is_active']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()->route('admin.appearance.testimonials')->with('success', 'Testimonial added successfully.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'designation', 'company', 'content', 'rating', 'sort_order', 'is_active']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.appearance.testimonials')->with('success', 'Testimonial updated successfully.');
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        return redirect()->route('admin.appearance.testimonials')->with('success', 'Testimonial deleted successfully.');
    }
}
