<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\Order;
use App\Models\Organization;
use App\Models\TransitCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransitController extends Controller
{

    public function index()
    {
        $transitId = transit_id();
        
        if (!$transitId) {
            // Create empty paginator
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
            $orders->withPath(request()->url());
        } elseif (auth('transit')->check()) {
            // New TransitCustomer
            $orders = Order::where('transit_customer_id', $transitId)->latest()->paginate(8);
        } else {
            // Legacy support for old transit users in users table
            $orders = Order::where('user_id', $transitId)
                ->whereHas('user', function($q) {
                    $q->where('role_id', 9);
                })
                ->latest()
                ->paginate(8);
        }
            
        return view('pages.transit.profile')->with([
            'orders' => $orders
        ]);
    }

    public function show()
    {

    }

    public function edit()
    {
        return view('pages.transit.edit');
    }

    public function update(Request $request, $id)
    {
        $customer = TransitCustomer::findOrFail($id);
        
        // Verify that the authenticated user is updating their own profile
        if (auth('transit')->id() != $id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:transit_customers,email,' . $id,
            'phone' => 'required|string|unique:transit_customers,phone,' . $id,
            'voen' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Handle password update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function service()
    {
        return view('pages.transit.index');
    }

    public function login()
    {
        return view('pages.transit.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->login,
            'password' => $request->password,
        ];

        if (Auth::guard('transit')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('service');
        }

        return back()->withErrors([
            'login' => 'Email və ya şifrə yanlışdır.',
        ])->onlyInput('login');
    }

    public function payment(Order $order)
    {
        return view('pages.transit.payment')->with(['order' => $order]);
    }

    public function profile()
    {
        return $this->index(); // Alias for index
    }

    /**
     * Generate 6-digit code for linking Telegram bot (transit customer only)
     */
    public function generateTelegramLinkCode(Request $request)
    {
        if (!auth('transit')->check()) {
            return redirect()->route('transit-login')->withErrors(['login' => 'Daxil olmalısınız.']);
        }

        /** @var \App\Models\TransitCustomer $customer */
        $customer = auth('transit')->user();
        $code = $customer->generateTelegramLinkCode();

        return redirect()->route('profile.index')->with('telegram_link_code', $code);
    }
}
