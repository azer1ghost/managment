@extends('layouts.main')

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
            <form class="col-md-12 form-row" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-2">
                    <x-input::image name="image"/>
                </div>
                <div class="form-row col-md-10">
                    <!-- Main -->
                    <div class="col-md-12">
                        <p class="text-muted">PERSONAL</p>
                        <hr>
                    </div>
                    <x-input::text required="" name="name" :value="auth()->user()->name" width="4" class="pr-1" />
                    <x-input::text required="" name="surname" :value="auth()->user()->surname" width="4" class="pr-1" />
                    <x-input::text required="" name="father" :value="auth()->user()->father" label="Father's name" width="4" class="pr-1" />
                    <!-- Passport -->
                    <div class="col-md-12">
                        <br>
                        <p class="text-muted">PASSPORT</p>
                        <hr>
                    </div>
                    <x-input::select :options="['AA','AZE']" name="serialPattern" label="Serial" width="1" class="p-0" />
                    <x-input::text required="" name="serial" label=" " placeholder="Enter serial number" width="3" class="pr-1" />
                    <x-input::text required="" name="fin" label="FIN" width="2" class="pr-1" />
                    <x-input::select :options="['male','female']" name="gender" width="2" class="pr-1" />
                    <x-input::date required="" name="birthday" width="4" class="pr-1" />
                    <!-- Employer -->
                    <div class="col-md-12">
                        <br>
                        <p class="text-muted">EMPLOYMENT</p>
                        <hr>
                    </div>
                    <x-input::text required="" name="position" label="Position" width="6" class="pr-1" />
                    <x-input::select :options="['Marketing','Call center']" name="department" width="6" class="pr-1" />
                    <!-- Contact -->
                    <div class="col-md-12">
                        <br>
                        <p class="text-muted">CONTACT</p>
                        <hr>
                    </div>
                    <x-input::text required="" id="phone" name="phone_coop" label="Cooperative number" width="3" class="pr-1" />
                    <x-input::text required="" name="phone" label="Personal number" width="3" class="pr-1" />
                    <x-input::email required="" name="email_coop" label="Cooperative Email" width="3" class="pr-1" />
                    <x-input::email required="" name="email" label="Personal Email"  width="3" class="pr-1" />
                    <!-- Address -->
                    <div class="col-md-12">
                        <br>
                        <p class="text-muted">ADDRESS</p>
                        <hr>
                    </div>
                    <x-input::select :options="['Azerbaijan','Turkey']" name="country" width="3" class="pr-1" />
                    <x-input::select :options="['Baku','Sumgayit']" name="city" width="3" class="pr-1" />
                    <x-input::text required="" name="address" width="6" class="pr-1" />
                    <x-input::text required="" name="password" width="6" class="pr-1" />
                    <x-input::text required="" name="password_confirm" width="6" class="pr-1" />
                    <x-input::submit/>
                </div>


            </form>
        </div>
    </div>

@endsection