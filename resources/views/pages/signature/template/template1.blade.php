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
    <TABLE style="WIDTH: 414px" cellSpacing="0" cellPadding="0" border="0" id="email-signature">
        <TBODY>
        <TR>
            <TD width="300" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial, sans-serif;line-height:14pt;" vAlign="bottom">
                <STRONG><SPAN style="FONT-SIZE: 18pt; FONT-FAMILY: Arial, sans-serif; COLOR: #051542">{{$user->getAttribute('fullname')}}</SPAN></STRONG><BR>
                <SPAN style="FONT-SIZE: 14pt; FONT-FAMILY: Arial, sans-serif; COLOR: #051542">
                    @if($user->isDirector()) {{$user->getRelationValue('company')->getAttribute('name')}}
                    @else {{$user->getRelationValue('department')->getAttribute('name')}} @endif -
                    @if($user->isDirector()) {{$user->getRelationValue('role')->getAttribute('name')}}
                    @else {{$user->getRelationValue('position')->getAttribute('name')}} @endif
                </SPAN>
            </TD>

            <TD vAlign="bottom" width="160">
                @foreach ($company->socials as $social)
                <a href="{{$social->url}}" target="_blank" rel="noopener"><img  width="19" height="19" src="https://my.mobilgroup.az/assets/images/signature/socials/{{$social->name}}.png" alt="" style="border:0; height:19px; width:19px"></a>
                @endforeach
            </TD>

        </TR>

        <TR>
            <TD style="FONT-SIZE: 9pt; FONT-FAMILY: Arial, sans-serif; " vAlign="top" colSpan="2">

                <table style="WIDTH: 414px">
                    <tr>
                        <td style="padding-right:30px" >
                            <a href="https://mobilgroup.az/" target="_blank" rel="noopener"><img src="{{asset("assets/images/{$company->getAttribute('logo')}")}}" alt="Logo" width="131" style="max-width:131px; height:auto; border:0;"></a>
                        </td>
                        <td style="FONT-SIZE: 9pt; FONT-FAMILY: Arial, sans-serif; line-height:11pt ">
                            <span style="color:#051542;"><strong style="color:#101010; display:table-cell; width:100px;">Mob:</strong><br></span>
                            <span style="color:#051542;"><strong style="color:#101010; display:table-cell; width:100px;">Çağrı Mərkəzi:</strong><br></span>
                            <span style="color:#051542;"><strong style="color:#101010; display:table-cell; width:100px;">E-mail:</strong></span>
                            <span style="color:#051542;"><br><strong  style="color:#101010; display:table-cell; width:100px;">Ünvan:</strong></span>
                            <span style="color:#051542;"><br><strong  style="color:#101010; display:table-cell; width:100px;">Web-Sayt:</strong></span>
                        </td>
                        <td style="FONT-SIZE: 9pt; FONT-FAMILY: Arial, sans-serif; line-height:11pt ">
                            <span style="color:#051542;">{{Str::replace('-', ' ', $user->getAttribute('phone_coop'))}}<br></span>
                            <span style="color:#051542;">{{$company->getAttribute("call_center")}}<br></span>
                            <span style="color:#051542;">{{$user->getAttribute('email_coop')}}</span>
                            <span style="color:#051542;"><br>{{$company->getAttribute('address')}}
					</span>
                            <br><a href="http://{website}" target="_blank" rel="noopener" style=" text-decoration:none;"><strong style="color:#037edd; font-family:Arial, sans-serif;">{{$company->getAttribute('website')}}</strong></a>
                        </td>
                    </tr>
                </table>

            </TD></TR>

        </TBODY></TABLE>

    {{--    <table id="email-signature" style="text-align: left">--}}
{{--        <thead>--}}
{{--        <tr><th style="width: 5.5cm" colspan="5">Hörmətlə,</th></tr>--}}
{{--        <tr><th style="height: 7px" colspan="5"></th></tr>--}}
{{--        <tr><th colspan="5">{{$user->getAttribute('fullname')}}</th></tr>--}}
{{--        <tr><th colspan="5">--}}
{{--                @if($user->isDirector()) {{$user->getRelationValue('company')->getAttribute('name')}}--}}
{{--                @else {{$user->getRelationValue('department')->getAttribute('name')}} @endif ---}}
{{--                @if($user->isDirector()) {{$user->getRelationValue('role')->getAttribute('name')}}--}}
{{--                @else {{$user->getRelationValue('position')->getAttribute('name')}} @endif--}}
{{--                </th></tr>--}}
{{--        <tr><th colspan="5">Mob: {{Str::replace('-', ' ', $user->getAttribute('phone_coop'))}}</th></tr>--}}
{{--        <tr><th colspan="5">Email: {{$user->getAttribute('email_coop')}}</th></tr>--}}
{{--        <tr><th style="height: 7px" colspan="5"></th></tr>--}}
{{--        <tr><th colspan="5">Ünvan: {{$company->getAttribute('address')}}</th></tr>--}}
{{--        <tr><th colspan="5">Sayt: {{$company->getAttribute('website')}}</th></tr>--}}
{{--        <tr><th colspan="5">Çağrı Mərkəzi: {{$company->getAttribute("call_center")}}</th></tr>--}}
{{--        <tr>--}}
{{--            <th>--}}
{{--                <table style="margin-top: 10px">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <th style="@if($company->getAttribute('id')==2) border-right: 1px solid #051542; @endif height: 60px">--}}
{{--                            <img style="width: 4cm; padding: 0 14px 0 0; margin: 0;"  src="{{asset("assets/images/{$company->getAttribute('logo')}")}}"/>--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </th>--}}
{{--            @if($company->getAttribute('id') == 2)--}}
{{--                <th>--}}
{{--                    <table style="padding-left: 5px">--}}
{{--                        <tbody>--}}
{{--                        <tr>--}}
{{--                            <th>--}}
{{--                                <img style="width: 1.3cm; margin: 5px"  src="https://my.mobilgroup.az/assets/images/signature/socials/fiata.png"/>--}}
{{--                            </th>--}}
{{--                            <th>--}}
{{--                                <img style="width: 1.3cm; margin: 5px"  src="https://my.mobilgroup.az/assets/images/signature/socials/iata.png"/>--}}
{{--                            </th>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </th>--}}
{{--            @endif--}}
{{--        </tr>--}}
{{--        <table style="width: 4cm">--}}
{{--            <tbody>--}}
{{--            <tr>--}}
{{--                @foreach ($company->socials as $social)--}}
{{--                    <th>--}}
{{--                        <a href="{{$social->url}}">--}}
{{--                            <img class="icons" src="https://my.mobilgroup.az/assets/images/signature/socials/{{$social->name}}.png" />--}}
{{--                        </a>--}}
{{--                    </th>--}}
{{--                @endforeach--}}

{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--        </thead>--}}
{{--    </table>--}}
</div>

