<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{

    public function index(Request $request)
    {
        $data = Banner::paginate(PAGINATION_COUNT);
        return view('admin.banners.index', compact('data',));
    }


    public function create()
    {
        return view('admin.banners.create');
    }


    public function store(Request $request)
    {
        try {
           

            $banner = new Banner();
            
             
            if ($request->has('photo_for_desktop')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_for_desktop);
                $banner->photo_for_desktop = $the_file_path;
            }
            if ($request->has('photo_for_mobile')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_for_mobile);
                $banner->photo_for_mobile = $the_file_path;
            }
            
            if ($banner->save()) {
                return redirect()->route('banners.index')->with(['success' => 'Banner created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }



    public function edit($id)
    {
        if (auth()->user()->can('banner-edit')) {
            $data = Banner::findorFail($id);
            return view('admin.banners.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        try {
              if ($request->has('photo_for_desktop')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_for_desktop);
                $banner->photo_for_desktop = $the_file_path;
            }
            if ($request->has('photo_for_mobile')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_for_mobile);
                $banner->photo_for_mobile = $the_file_path;
            }
            // Save the updated banner
            if ($banner->save()) {
                return redirect()->route('banners.index')->with(['success' => 'Banner updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $banner = Banner::findOrFail($id);


            // Delete the banner
            if ($banner->delete()) {
                return redirect()->back()->with(['success' => 'Banner deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}
