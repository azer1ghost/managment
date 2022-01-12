<div>
    <style>
        .icons {
            width: 28px;
        }
        #email-signature {
            font-family: Arial,sans-serif;
        }
    </style>
    <table id="email-signature" style="text-align: left">
        <thead>
        <tr><th style="width: 5.5cm" colspan="5">Hörmətlə,</th></tr>
        <tr><th colspan="5">{{$user->getAttribute('fullname')}}</th></tr>
        <tr><th colspan="5">@if($user->isDirector()) {{$user->getRelationValue('company')->getAttribute('name')}} @else {{$user->getRelationValue('department')->getAttribute('name')}} @endif
                 –
                @if($user->isDirector()) {{$user->getRelationValue('role')->getAttribute('name')}} @else @endif
                {{$user->getRelationValue('position')->getAttribute('name')}}</th></tr>
        <tr><th colspan="5">Mob: {{$company->getAttribute('mobile')}}</th></tr>
        <tr><th colspan="5">Email: {{$company->getAttribute('mail')}}</th></tr>
        <tr><th colspan="5"> <br> </th></tr>
        <tr><th colspan="5">Ünvan: {{$company->getAttribute('address')}}</th></tr>
        <tr><th colspan="5">Sayt: {{$company->getAttribute('website')}}</th></tr>
        <tr><th colspan="5">Çağrı Mərkəzi: {{$company->getAttribute("call_center")}}</th></tr>
        <tr>
            <th>
                <table style="margin-top: 10px">
                    <tbody>
                    <tr>
                        <th style="@if($company->getAttribute('id')==2) border-right: 1px solid #051542; @endif height: 60px">
                            <img style="width: 4cm; padding: 0 14px 0 0; margin: 0;"  src="https://mobilgroup.az/signature/{{ $company->getAttribute('logo') }}"/>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            @if($company->getAttribute('id')==2)
                <th>
                    <table style="padding-left: 5px">
                        <tbody>
                        <tr>
                            <th colspan="5">
                                <img style="width: 1cm"  src="https://mobilmanagement.com/assets/images/signature/socials/fiata.png"/>
                            </th>
                            <th colspan="5">
                                <img style="width: 1cm"  src="https://mobilmanagement.com/assets/images/signature/socials/iata.png"/>
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
                            <img class="icons" src="https://mobilmanagement.com/assets/images/signature/socials/{{$social->name}}.png" />
                        </a>
                    </th>
                @endforeach

            </tr>
            </tbody>
        </table>
        </thead>
    </table>
</div>

