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




    <table id="email-signature" style="width: 500px; font-size: 10pt; font-family:Verdana, sans-serif;" cellpadding="0" cellspacing="0" border="0">
        <tbody>
        <tr>
            <td width="200" align="center" style="font-size: 10pt; font-family: Verdana, sans-serif; width: 200px; text-align:center; vertical-align: top;" valign="top" rowspan="6">

                <p style="margin-top: 20px">
                </p>

                <a href="http://{{$company->getAttribute('website')}}" target="_blank" rel="noopener"><img alt="Badge" width="180" style="width:180px; height:auto; margin-top: 10px; border:0;" src="{{asset("assets/images/{$company->getAttribute('logo')}")}}"></a>


                <p style="margin-top:20px">
                    <a href="http://{{$company->getAttribute('website')}}" target="_blank" rel="noopener" style="text-decoration:none;"><strong style="font-size: 10pt; font-family: Verdana, sans-serif; color: #000000;">www.{{$company->getAttribute('website')}}</strong></a>

                </p>


                @foreach ($company->socials as $social)
                    <a href="{{$social->url}}" target="_blank" rel="noopener"><img width="25" src="https://my.mobilgroup.az/assets/images/signature/socials/{{$social->name}}.png" alt="facebook icon" style="border:0; height:25px; width:25px"></a>
                @endforeach
                <a rel="noopener"><img src="{{asset("assets/images/signature/socials/iso.png")}}" alt="facebook icon" style="border:0; height:100px; width:100px"></a>

            </td>

            <td width="20" style="width:20px">&nbsp;</td>

            <td>
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td style="font-size: 10pt; color:#0079ac; font-family: Verdana, sans-serif; width: 305px; padding-bottom: 15px; vertical-align: top; line-height:1.2;" valign="top">
                            <strong><span style="font-size: 14pt; font-family: Verdana, sans-serif; color:#000000;">{{$user->getAttribute('fullname')}}<br></span></strong>
                            <em style="font-family: Verdana, sans-serif; font-size:10pt; color:#000000;">@if($user->isDirector()) {{$user->getRelationValue('company')->getAttribute('name')}}
                                @else {{$user->getRelationValue('department')->getAttribute('name')}} @endif</em>
                            <span style="font-family: Verdana, sans-serif; font-size:9pt; color:#000000;" ><br>@if($user->isDirector()) {{$user->getRelationValue('role')->getAttribute('name')}}
                                @else {{$user->getRelationValue('position')->getAttribute('name')}} @endif</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #051542; font-size: 9pt; color:#444444; font-family: Verdana, sans-serif; padding-top: 15px; vertical-align: top; line-height:1.2" valign="top">
                            <span style="color: #051542; font-size: 10pt; margin-bottom: 30px">Mob: </span><span style="font-size: 10pt; font-family: Verdana, sans-serif; color:#000000; margin-bottom: 30px"> {{Str::replace('-', ' ', $user->getAttribute('phone_coop'))}}<br></span>
                            <span style="color: #051542; font-size: 10pt">Çağrı Mərkəzi: </span><span style="font-size: 10pt; font-family: Verdana, sans-serif; color:#000000;">{{$company->getAttribute("call_center")}}<br></span>
                            <span style="color: #051542; font-size: 10pt">Email: </span><span style="font-size: 10pt; font-family: Verdana, sans-serif; color:#000000;">{{$user->getAttribute("email_coop")}}</span>
                        </td>
                    </tr>
                    <tr >
                        <td style="font-size: 9pt; font-family: Verdana, sans-serif; padding-bottom: 5px; padding-top: 5px; vertical-align: top; color: #0079ac;" valign="top">
                            <span  style="font-size: 9pt; font-family: Verdana, sans-serif; color: #000000;">{{$company->getAttribute("address")}}<br /></span>

                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12pt; font-family: Verdana, sans-serif;  padding-left: 10px; vertical-align: top; color: #0079ac;" valign="top"></td>
                    </tr>

                    </td>
                    </tbody>
                </table>
        </tr>
        </tbody>
    </table>


















{{--    <TABLE style="WIDTH: 414px" cellSpacing="0" cellPadding="0" border="0" id="email-signature">--}}
{{--        <TBODY>--}}
{{--        <TR>--}}
{{--            <TD width="300" style="FONT-SIZE: 10pt; FONT-FAMILY: Arial, sans-serif;line-height:14pt;" vAlign="bottom">--}}
{{--                <STRONG><SPAN style="FONT-SIZE: 18pt; FONT-FAMILY: Arial, sans-serif; COLOR: #051542">{{$user->getAttribute('fullname')}}</SPAN></STRONG><BR>--}}
{{--                <SPAN style="FONT-SIZE: 14pt; FONT-FAMILY: Arial, sans-serif; COLOR: #051542">--}}
{{--                    @if($user->isDirector()) {{$user->getRelationValue('company')->getAttribute('name')}}--}}
{{--                    @else {{$user->getRelationValue('department')->getAttribute('name')}} @endif ---}}
{{--                    @if($user->isDirector()) {{$user->getRelationValue('role')->getAttribute('name')}}--}}
{{--                    @else {{$user->getRelationValue('position')->getAttribute('name')}} @endif--}}
{{--                </SPAN>--}}
{{--            </TD>--}}

{{--            <TD vAlign="bottom" width="160">--}}
{{--                @foreach ($company->socials as $social)--}}
{{--                <a href="{{$social->url}}" target="_blank" rel="noopener"><img  width="19" height="19" src="https://my.mobilgroup.az/assets/images/signature/socials/{{$social->name}}.png" alt="" style="border:0; height:19px; width:19px"></a>--}}
{{--                @endforeach--}}
{{--            </TD>--}}

{{--        </TR>--}}

{{--        <TR>--}}
{{--            <TD style="FONT-SIZE: 9pt; FONT-FAMILY: Arial, sans-serif; " vAlign="top" colSpan="2">--}}

{{--                <table style="WIDTH: 414px">--}}
{{--                    <tr>--}}
{{--                        <td style="padding-right:30px" >--}}
{{--                            <a href="https://mobilgroup.az/" target="_blank" rel="noopener"><img src="{{asset("assets/images/{$company->getAttribute('logo')}")}}" alt="Logo" width="131" style="max-width:131px; height:auto; border:0;"></a>--}}
{{--                        </td>--}}
{{--                        <td style="FONT-SIZE: 9pt; FONT-FAMILY: Arial, sans-serif; line-height:11pt ">--}}
{{--                            <span style="color:#051542;"><strong style="color:#101010; display:table-cell; width:100px;">Mob:</strong><br></span>--}}
{{--                            <span style="color:#051542;"><strong style="color:#101010; display:table-cell; width:100px;">Çağrı Mərkəzi:</strong><br></span>--}}
{{--                            <span style="color:#051542;"><strong style="color:#101010; display:table-cell; width:100px;">E-mail:</strong></span>--}}
{{--                            <span style="color:#051542;"><br><strong  style="color:#101010; display:table-cell; width:100px;">Ünvan:</strong></span>--}}
{{--                            <span style="color:#051542;"><br><strong  style="color:#101010; display:table-cell; width:100px;">Web-Sayt:</strong></span>--}}
{{--                        </td>--}}
{{--                        <td style="FONT-SIZE: 9pt; FONT-FAMILY: Arial, sans-serif; line-height:11pt ">--}}
{{--                            <span style="color:#051542;">{{Str::replace('-', ' ', $user->getAttribute('phone_coop'))}}<br></span>--}}
{{--                            <span style="color:#051542;">{{$company->getAttribute("call_center")}}<br></span>--}}
{{--                            <span style="color:#051542;">{{$user->getAttribute('email_coop')}}</span>--}}
{{--                            <span style="color:#051542;"><br>{{$company->getAttribute('address')}}--}}
{{--					</span>--}}
{{--                            <br><a href="http://{website}" target="_blank" rel="noopener" style=" text-decoration:none;"><strong style="color:#037edd; font-family:Arial, sans-serif;">{{$company->getAttribute('website')}}</strong></a>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                </table>--}}

{{--            </TD></TR>--}}

{{--        </TBODY></TABLE>--}}























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

