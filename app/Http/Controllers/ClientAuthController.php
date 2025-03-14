<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Work;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/clients/account';

    public function __construct()
    {
        $this->middleware('guest')->except(['logout']);
        app()->setLocale('az');
    }

    public function showLoginForm(Client $client)
    {
        return view('pages.clients.auth.login', ['guard' => 'clients']);
    }

    public function showRegisterForm(Client $client)
    {
        return view('pages.clients.auth.register', ['guard' => 'clients']);
    }

    protected function guard()
    {
        return Auth::guard('clients');
    }

    public function login(Request $request)
    {
        $client = Client::where('voen', $request->voen)->first();
//        dd($client);

        if (isset($client) && is_null($client->getAttribute('password'))) {
            $client->setAttribute('password', Hash::make($request->password));
            $client->save();
        }

        $credentials = $request->only('voen', 'password');

        if (auth('clients')->attempt($credentials)) {

            return redirect('/clients/account');
        }

        return back()->withErrors(['voen' => 'bu voenlə qeydiyyat yoxdur']);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'voen' => 'required|unique:clients',
            'password' => 'required|min:6',
        ]);

        $user = new Client;
        $user->fullname = $request->fullname;
        $user->voen = $request->voen;
        $user->password = Hash::make($request->password);
        $user->save();

        auth()->login($user);

        return redirect('/clients/account');
    }
    public function update(ClientRequest $request, Client $client)
    {
        $validated = $request->validated();
        $validated['send_sms'] = $request->has('send_sms');
//dd($validated);
        $client->update($validated);
        return back()->withNotify('info', $client->getAttribute('fullname'));
    }

    public function logout()
    {
        Auth::guard('clients')->logout();
        return redirect('/clients/login');
    }

    public function account()
    {
        return view('pages.clients.auth.account')->with([
            'client' => auth('clients',)->user(),
            'works' => Work::where('client_id', auth('clients')->id())->paginate(25)
        ]);
    }
}
