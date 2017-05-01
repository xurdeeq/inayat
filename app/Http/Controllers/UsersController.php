<?php

namespace Inayat\Http\Controllers;

use Inayat\Account;
use Inayat\Kin;
use Inayat\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        $transactions = Account::where('user_id', '=', Auth::user()->getAuthIdentifier())->get();

        return view('users.index', compact('user', 'transactions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewProfile()
    {
        $user = Auth::user();

        return view('users.profile', compact('user'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editProfile(Request $request)
    {
        $currentUser = User::where('phone', '=', Auth::user()->phone);
        $user = User::find(Auth::user()->getAuthIdentifier());

        if (($currentUser)->exists()) {
            $user->surname = $request->input('surname');
            $user->firstName = $request->input('first-name');
            $user->middleName = $request->input('middle-name');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->permanentAddress = $request->input('permanentAddress');
            $user->occupation = $request->input('occupation');
            $user->save();

            return redirect('/edit-profile')->with('success', 'Profile Updated Successfully');
        }

        return redirect('/edit-profile')->with('danger', 'There was an Error. Update failed.');
    }

    public function changePassword()
    {

    }

    public function uploadImage()
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateKin(Request $request)
    {
        $nextKin = Kin::where('user_id', Auth::user()->getAuthIdentifier())->first();

        $nextKin->name = $request->input('name');
        $nextKin->relationship = $request->input('relationship');
        $nextKin->kin_phone = $request->input('kin-phone');
        $nextKin->kin_address = $request->input('kin-address');
        $nextKin->save();

        return redirect('/edit-profile')->with('success', 'Next of Kin Info Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTransaction()
    {
        return view('users.payment');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function makeTransaction(Request $request)
    {
        try {
            $pay = new Account();
            $pay->user_id = Auth::user()->getAuthIdentifier();
            $pay->amount = $request->input('amount');
            $pay->reference = $request->input('reference');
            $pay->transaction = $request->input('payment');
            $pay->status = Account::STATUS_PENDING;
            $pay->type = Account::TYPE_CREDIT;
            $pay->save();

            return redirect('/transaction')->with('success', 'Transaction Registered');
        } catch (\Exception $e) {
            return redirect('/transaction')->with('danger', 'Transaction Failed');
        }
    }
}
