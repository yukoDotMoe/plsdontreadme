<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MasterSiteSetting;
use Illuminate\Support\Facades\View;
use File;
use Illuminate\Support\Str;


class SystemSettingController extends Controller
{
    // get site settings info
    public function index()
    {
        $settings = MasterSiteSetting::find(1);
        return view('admin.setting', compact('settings'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'logo_mini' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
//        ]);
//    }

    /**
     *  change logo function
     */
    public function ChangeLogoSystem(Request $request)
    {
        $settings = MasterSiteSetting::find(1);

        // change logo mini
        if ($request->hasFile('logo_mini')) {
            $logo_mini = $this->store($request, $settings, 'logo_mini');
        } else {
            $logo_mini = $settings->logo_mini;
        }

        // change logo
        if ($request->hasFile('text_logo')) {
            $text_logo = $this->store($request, $settings, 'text_logo');
        } else {
            $text_logo = $settings->text_logo;
        }

        // change favicon
        if ($request->hasFile('favicon')) {
            $favicon = $this->store($request, $settings, 'favicon');
        } else {
            $favicon = $settings->favicon;
        }

        // change verified_seller_logo
        if ($request->hasFile('verified_seller_logo')) {
            $verified_seller_logo = $this->store($request, $settings, 'verified_seller_logo');
        } else {
            $verified_seller_logo = $settings->verified_seller_logo;
        }
        $settings->logo_mini = $logo_mini;
        $settings->text_logo = $text_logo;
        $settings->favicon = $favicon;
        $settings->verified_seller_logo = $verified_seller_logo;
        $settings->about_us = $request->about_us;
        $settings->for_support = $request->for_support;
        $settings->verified_seller_url = $request->verified_seller_url;
        $settings->save();

        return redirect()->route('setting_system')->with(['msg' => 'Update site settings successful']);
    }

    /**
     *  store file
     */
    public function store($request, $settings, $file)
    {
        $oldFile = $settings[$file];
        File::Delete($oldFile);
        $file = $request[$file];
        $uploadFolder = '/images/logo/';
        $filename = Str::random() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path() . $uploadFolder, $filename);
        return $filename;
    }
}
