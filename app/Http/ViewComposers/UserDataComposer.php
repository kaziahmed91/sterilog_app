<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\SoftUser as SoftUserModel;

class UserDataComposer
{
    // public $softUsers = [];
    /**
     * Create a softUser composer.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $softUsers = '';
        if (\Auth::user()) {
            $softUsers = SoftUserModel::where('company_id', \Auth::user()->company_id)
                ->whereNull('deleted_at')->get();
        }
        $view->with('softUsers', $softUsers);
    }
}