@extends('layouts.signature')

@section('content')
    <table class="mt-3"  style="position: absolute">
        <tbody>
        <tr>
            <td><x-logo class="animate__animated animate__flip" width="80px"></x-logo></td>
            <td class="text-center">
                <h1 style="font-family: Sequel;">
                    <span class="animate__animated animate__fadeIn" style="color: #2B2F47; font-size: 35px;">Mobil</span>
                    <span class="animate__animated animate__fadeInDown d-block" style="font-size: 20px; color: #98CF20;letter-spacing: 4px;margin-top: -10px;padding-left: 5px">GROUP</span>
                </h1>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="container animate__animated  animate__fadeIn" >
        <div class="col-md-12 text-center ">
            <h1>Employee Register Form</h1>
        </div>
        <div class="row d-flex align-items-center justify-content-center vh-100">
            <form class="col-md-12 form-row" action="">
                <x-input::text required="" name="name" width="4" class="pr-4" />
                <x-input::text required="" name="surname" width="4" class="pr-4" />
                <x-input::text required="" name="father" label="Father's name" width="4" class="pr-4" />


                <label style="position: absolute; padding-top: 80px; padding-right: 5px">Serial Number</label>
                <x-input::select :options="['AA','AZE']" name="serialPattern" label=" " width="1" />
                <x-input::text required="" name="serial" label=" " width="3" class="pr-4" />


                <x-input::text required="" name="fin" label="FIN" width="2" class="pr-4" />
                <x-input::date required="" name="birthday" width="2" class="pr-4" />
                <x-input::select :options="['male','female']" name="gender" width="2" class="pr-4" />
                <x-input::text required="" name="position" label="Position (documented)" width="6" class="pr-4" />
                <x-input::text required="" name="position_actual" label="Actual Position (currnetly)" width="6" class="pr-4" />
                <x-input::text required="" name="number_coop" label="Cooperative number" width="3" class="pr-4" />
                <x-input::text required="" name="number" label="Personal number" width="3" class="pr-4" />
                <x-input::email required="" name="email_coop" label="Cooperative Email" width="3" class="pr-4" />
                <x-input::email required="" name="email" label="Personal Email"  width="3" class="pr-4" />
                <x-input::select :options="['Azerbaijan','Turkey']" name="country" width="3" class="pr-4" />
                <x-input::select :options="['Baku','Sumgayit']" name="city" width="3" class="pr-4" />
                <x-input::text required="" name="address" width="6" class="pr-4" />
                <x-input::submit/>
            </form>
        </div>
    </div>
@endsection