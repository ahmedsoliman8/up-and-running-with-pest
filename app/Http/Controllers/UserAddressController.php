<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{


    public function update(User $user, Address $address, Request $request)
    {


        return tap($address, function ($address) use ($request,$user) {
            $address->fill($request->only($this->getAddressFields()));
            $this->associateCountry($address, $request);
            $address->load( 'user');
            $address->save();
        });
    }


    public function store(User $user, Request $request)
    {

        /*$country = Country::where('code', $request->country_code)->first();

        $address = new Address();
        $address->line_1 = $request->line_1;
        $address->country()->associate($country);
        $address->user()->associate($user);
        $address->save();
        return $address;*/


        return tap($this->newAddressRequest($request), function ($address) use ($user) {
            $this->associateUser($address, $user);
            $address->save();
        });
    }

    protected function newAddressRequest(Request $request)
    {
        $address = new Address($request->only($this->getAddressFields()));
        return tap($address, function ($address) use ($request) {
            $this->associateCountry($address, $request);
        });
    }

    protected function associateCountry(Address $address, Request $request)
    {
        $address->country()->associate($this->getCountryByCode($request->country_code));
    }


    protected function associateUser(Address $address, User $user)
    {
        $address->user()->associate($user);

    }

    protected function getCountryByCode($code)
    {
        return Country::where('code', $code)->first();
    }

    protected function getAddressFields()
    {
        return [
            'line_1'
        ];
    }


}
