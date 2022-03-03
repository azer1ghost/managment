<div id="signature">
    <style>
        .icons {
            width: 28px;
        }
        #email-signature {
            font-family: Arial,sans-serif;
        }
        #signature{
            background-color: white;
        }
    </style>
    <table id="email-signature" style="text-align: left">
        <thead>
        <tr><th style="width: 5.5cm" colspan="5">Hörmətlə,</th></tr>
        <tr><th style="height: 7px" colspan="5"></th></tr>
        <tr><th colspan="5">{{$user->getAttribute('fullname')}}</th></tr>
        <tr><th colspan="5">
                @if($user->isDirector()) {{$user->getRelationValue('company')->getAttribute('name')}}
                @else {{$user->getRelationValue('department')->getAttribute('name')}} @endif -
                @if($user->isDirector()) {{$user->getRelationValue('role')->getAttribute('name')}}
                @else {{$user->getRelationValue('position')->getAttribute('name')}} @endif
                </th></tr>
        <tr><th colspan="5">Mob: {{Str::replace('-', ' ', $user->getAttribute('phone_coop'))}}</th></tr>
        <tr><th colspan="5">Email: {{$user->getAttribute('email_coop')}}</th></tr>
        <tr><th style="height: 7px" colspan="5"></th></tr>
        <tr><th colspan="5">Ünvan: {{$company->getAttribute('address')}}</th></tr>
        <tr><th colspan="5">Sayt: {{$company->getAttribute('website')}}</th></tr>
        <tr><th colspan="5">Çağrı Mərkəzi: {{$company->getAttribute("call_center")}}</th></tr>
        <tr>
            <th>
                <table style="margin-top: 10px">
                    <tbody>
                    <tr>
                        <th style="@if($company->getAttribute('id')==2) border-right: 1px solid #051542; @endif height: 60px">
                            <img style="width: 4cm; padding: 0 14px 0 0; margin: 0;"  src="{{asset("assets/images/{$company->getAttribute('logo')}")}}"/>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            @if($company->getAttribute('id') == 2)
                <th>
                    <table style="padding-left: 5px">
                        <tbody>
                        <tr>
                            <th>
                                <img style="width: 1.3cm; margin: 5px"  src="https://my.mobilgroup.az/assets/images/signature/socials/fiata.png"/>
                            </th>
                            <th>
                                <img style="width: 1.3cm; margin: 5px"  src="https://my.mobilgroup.az/assets/images/signature/socials/iata.png"/>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </th>
            @endif
        </tr>
        <table style="width: 4cm">
            <tbody>
            <tr>
                @foreach ($company->socials as $social)
                    <th>
                        <a href="{{$social->url}}">
                            <img class="icons" src="https://my.mobilgroup.az/assets/images/signature/socials/{{$social->name}}.png" />
                        </a>
                    </th>
                @endforeach

            </tr>
            </tbody>
        </table>
        </thead>
    </table>
</div>

