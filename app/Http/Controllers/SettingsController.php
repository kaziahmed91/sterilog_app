<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as UserModel;
use App\Sterilizer as SterilizerModel;
use App\Cleaners as CleanersModel;
use App\SoftUser as SoftUserModel;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $company_id = \Auth::user()->company_id;
        $users = SoftUserModel::where('company_id', $company_id)->get();
        $equiptments = SterilizerModel::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray();
        $cleaners = CleanersModel::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray();
        return view('auth.settings', ['users' => $users, 'equiptments' => $equiptments, 'cleaners' => $cleaners]);
    }

    public function getCompanyView()
    {
        return view('auth.settings.company', ['route' => 'company']);
    }

    public function getCleanersView()
    {
        $company_id = \Auth::user()->company_id;
        $cleaners = CleanersModel::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray();
        return view('auth.settings.cleaners', ['route' => 'cleaners', 'cleaners' => $cleaners]);
    }

    public function getUsersView()
    {
        $company_id = \Auth::user()->company_id;
        $users = SoftUserModel::where('company_id', $company_id)->get();

        return view('auth.settings.user', ['users' => $users, 'route' => 'user']);

    }

    public function getUserView(Request $request, $id)
    {
        $company_id = \Auth::user()->company_id;
        $user = SoftUserModel::where('id', $id)->first();

        return view('auth.settings.user-edit', ['user' => $user, 'route' => 'user']);
    }


    public function getEquiptmentView()
    {
        $company_id = \Auth::user()->company_id;
        $equiptments = SterilizerModel::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray();
        return view('auth.settings.equiptment', ['route' => 'equiptment', 'equiptments' => $equiptments]);
    }

    public function addCleaner(Request $request)
    {
        $company_id = \Auth::user()->company_id;
        $data = $request->all();
        $validator = Validator::make($data, [
            'cleaner_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // dd($data);
        $cleaner = CleanersModel::create([
            'name' => $data['cleaner_name'],
            'company_id' => $company_id,
            'added_by' => \Auth::user()->id
        ]);

        if (!$cleaner) {
            Session::flash('error', 'There was a problem adding a cleaner.');
            throw new Exception("Cleaner Could not be added", 400);
        }
        return back();

    }

    public function removeCleaner(Request $request)
    {
        $data = $request->all();
        $cleanerId = $data['cleanerId'];
        $state = $data['newState'];

        $cleaner = CleanersModel::where('id', $cleanerId)->first();
        if ($state == '1') {
            $cleaner->date_deleted = Carbon::now();
        } else if ($state == '0') {
            $cleaner->date_deleted = null;
        }
        $cleaner->save();
        return response()->json(['response' => 'success'], 200);
    }

    public function editPrinter(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'printer' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!UserModel::where('id', \Auth::user()->id)->update(['current_printer' => $data['printer']])) {
            Session::flash('error', 'There was a problem updating the printer!');
            return redirect()->back();
        }

        Session::flash('success', 'Printer settings have been updated!');

        return back();

    }


    public function addSterilizer(Request $request)
    {
        $data = $request->all();
        $company_id = \Auth::user()->company_id;
        $data = $request->all();

        $validator = Validator::make($data, [
            'sterilizer_name' => 'required|string',
            // 'manufacturer'  => 'string',
        ]);

        $errors = $validator->errors()->toArray();

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sterilizer = SterilizerModel::create([
            'company_id' => $company_id,
            'sterilizer_name' => $data['sterilizer_name'],
            'added_by' => \Auth::user()->id,
            'created_at' => Carbon::now(),
            'cycle_number' => 0
        ]);

        if (!$sterilizer) {
            Session::flash('error', 'There was a problem adding a sterilizer.');
            throw new Exception("Equiptment Could not be added", 400);
        }

        Session::flash('success', 'New Sterilizer has been registered!');

        return back();
    }

    public function removeSterilizer(Request $request)
    {
        $data = $request->all();
        $sterilizerId = $data['sterilizerId'];
        $state = $data['newState'];

        $sterilizer = SterilizerModel::where('id', $sterilizerId)->first();
        if ($state == '1') {
            $sterilizer->date_deleted = Carbon::now();
        } else if ($state == '0') {
            $sterilizer->date_deleted = null;
        }
        $sterilizer->save();
        return response()->json(['response' => 'success'], 200);
    }

    public function addSoftUser(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name' => 'required',
            'user_name' => 'required',
            'password' => 'required|numeric|digits_between:4,6'
        ]);

        $errors = $validator->errors()->toArray();

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userExists = SoftUserModel::where('company_id', \Auth::user()->company_id)->where('user_name', $data['user_name'])->first();

        if ($userExists) {
            Session::flash('error', 'Username already exists!');
            return redirect()->back();
        }
        $data['company_id'] = \Auth::user()->company_id;

        if (!SoftUserModel::create($data)) {
            Session::flash('error', 'There was an error creating the User!  ');
            return redirect()->back();
        }
        Session::flash('success', 'New user has been created!');

        return back();
    }

    public function editSoftUser(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'user_name' => 'required',
        ]);

        $errors = $validator->errors()->toArray();

        $auth_confirmed = password_verify($data['system_password'], \Auth::user()->password);
        if (!$auth_confirmed) {
            Session::flash('error', 'Incorrect System Password!');
            return redirect()->back()->withErrors($validator)->withInput();;
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $userExists = SoftUserModel::where('company_id', \Auth::user()->company_id)->where('user_name', $data['user_name'])->first();
        if ($userExists && $id != $userExists->id) {
            Session::flash('error', 'Username already exists!');
            return redirect()->back()->withInput();
        }
        if (!SoftUserModel::where('id', $id)->update([
            'user_name' => $data['user_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ])) {
            Session::flash('error', 'There was an error updating the password!  ');
            return redirect()->back()->withInput();
        }
        Session::flash('success', 'Password as been updated!');
        return back();
    }

    public function changeUserPassword(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'new_password' => 'required'
        ]);
        $errors = $validator->errors()->toArray();

        $auth_confirmed = password_verify($data['system_password'], \Auth::user()->password);
        if (!$auth_confirmed) {
            Session::flash('error', 'Incorrect System Password!');
            return redirect()->back();
        }

        $password_confirmed = $data['confirm_password'] === $data['new_password'];
        if (!$password_confirmed) {
            Session::flash('error', 'New password does not match!');
            return redirect()->back();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }

    public function changeSoftUserPassword(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'new_password' => 'required|numeric|digits_between:4,6|unique:soft_user,password'
        ]);

        $errors = $validator->errors()->toArray();

        $auth_confirmed = password_verify($data['system_password'], \Auth::user()->password);
        if (!$auth_confirmed) {
            Session::flash('error', 'Incorrect System Password!');
            return redirect()->back();
        }

        $password_confirmed = $data['confirm_password'] === $data['new_password'];
        if (!$password_confirmed) {
            Session::flash('error', 'New password does not match!');
            return redirect()->back();
        }


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!SoftUserModel::where('id', $id)->update(['password' => $data['new_password']])) {
            Session::flash('error', 'There was an error updating the password!  ');
            return redirect()->back();
        }
        Session::flash('success', 'Password as been updated!');
        return back();

    }
}
