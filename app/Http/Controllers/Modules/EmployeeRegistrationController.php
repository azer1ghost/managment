<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Models\EmployeeRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $statusList = ['+' => 'Positive', '-' => 'Negative'];
        $dates = [];
        for ($i = 30; $i >= 0; $i--) {
            $dates[] = date('d', strtotime("-{$i} days"));
        }
        $registrations = EmployeeRegistration::get();
        $users = User::isActive()->get(['id', 'name']);

        return view('pages.employee-registrations.index')->with([
            'registrations' => $registrations,
            'users' => $users,
            'statusList' => $statusList,
            'dates' => $dates,
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->input('user_id');
        $date = $request->input('date');
        $value = $request->input('value');

        $registration = EmployeeRegistration::where('user_id', $userId)
            ->where('date', $date)
            ->first();

        if ($registration) {
            // Kayıt zaten varsa güncelle
            $registration->status = $value;
            $registration->save();
        } else {
            // Kayıt yoksa yeni kayıt oluştur
            $registration = new EmployeeRegistration();
            $registration->user_id = $userId;
            $registration->date = $date;
            $registration->status = $value;
            $registration->save();
        }



        return response()->json(['success' => true]);
    }

    public function getStatus(Request $request)
    {
        $userId = $request->input('user_id');
        $date = $request->input('date');

        // İlgili kullanıcının belirtilen tarihteki durumunu al
        $status = EmployeeRegistration::where('user_id', $userId)
            ->where('date', $date)
            ->value('status');

        return response()->json(['data' => [$userId => [$date => $status]]], 200);
    }

    public function destroy(EmployeeRegistration $employeeRegistration)
    {
        $employeeRegistration->delete();

        return response()->json(['success' => true]);
    }
}