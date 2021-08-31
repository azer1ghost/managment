
<table style="vertical-align: baseline;padding: 0; font-family: Arial,serif; width: 580px">
    <tbody>
    <!--  Columns width -->
    <tr>
        <td style="min-width:180px;max-width: 180px">
            <div><!-- Logo row --></div>
        </td>
        <td style="min-width:190px;max-width: 190px">
            <div><!-- Name row --></div>
        </td>
        <td style="min-width:5px;max-width: 5px">
            <div><!-- Padding row --></div>
        </td>
        <td style="min-width:190px;max-width: 190px">
            <div><!-- Detail row --></div>
        </td>
    </tr>
    <!--  Content  -->
    <tr>
        <!-- Logo -->
        <td style="vertical-align: middle;">
            <img alt="logo" width="200px" style="padding-right: 10px" src="https://mobilgroup.az/signature/{{ $company->getAttribute('logo') }}" />
        </td>
        <!-- Main content -->
        <td style="border-right: 1px solid ; border-color: #050E3A;">
            <table style="vertical-align: baseline;padding: 0; font-family: Arial,serif;">
                <tbody>
                <tr>
                    <td>
                        <h1 style="margin: 0; padding: 0; font-size: 16px;font-family: Arial,serif;color: rgb(80,80,80);">{{$user->getAttribute('fullname')}}</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin: 0; padding: 0; font-size: 13px;color: rgb(130,130,130);">{{$user->getAttribute('position')}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin: 0; padding: 0; font-size: 13px;color: rgb(130,130,130);">{{$user->getRelationValue('department')->getAttribute('name')}}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <!-- Padding column-->
        <td></td>
        <!-- Detail -->
        <td>
            <table style="vertical-align: baseline;padding: 0; font-family: Arial,serif;color: rgb(154,154,154);">
                <tbody>
                <tr>
                    <td style="width: 15px;">
                        <img width="16px" src="https://mobilgroup.az/signature/socials/blue/phone.png" />
                    </td>
                    <td>
                        <p style="margin: 0; padding: 3px; font-size: 13px">{{$user->getAttribute('phone')}}</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15px;">
                        <img width="16px" src="https://mobilgroup.az/signature/socials/blue/phone.png" />
                    </td>
                    <td>
                        <p style="margin: 0; padding: 3px; font-size: 13px">{{$user->getAttribute('phone_coop')}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img width="16px" src="https://mobilgroup.az/signature/socials/blue/envelope.png" />
                    </td>
                    <td>
                        <p style="margin: 0; padding: 3px; font-size: 13px">{{$user->getAttribute("email_coop")}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img width="16px" src="https://mobilgroup.az/signature/socials/blue/share.png" />
                    </td>
                    <td>
                        <p style="margin: 0; padding: 3px; font-size: 13px">{{$company->getAttribute("website")}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img width="16px" src="https://mobilgroup.az/signature/socials/blue/map.png" />
                    </td>
                    <td>
                        <p style="margin: 0; padding: 3px; font-size: 13px">{{$company->getAttribute("address")}}</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <!--  Padding  -->
    <tr style="padding: 0; margin: 0">
        <td style="height: 10px">
            <div></div>
        </td>
    </tr>
    <!--  Socials  -->
    <tr>
        <td colspan="4" style="background: #050E3A; height: 50px">
            <table style="vertical-align: baseline; padding: 0;">
                <tbody>
                <tr>
                    <td style="width: 100%">
                        <img style="padding-left: 5px" width="13px" src="https://mobilgroup.az/signature/socials/white/call.png" />
                        <b style="padding:0px;color: rgb(201,201,201)">{{$company->getAttribute("call_center")}}</b>
                    </td>
                    @foreach ($company->socials as $social)
                       <td style="padding: 5px; color: white;">
                            <a href="{{$social->url}}">
                                <img width="20px" src="https://mobilgroup.az/signature/socials/white/{{$social->name}}.png" />
                            </a>
                        </td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <!--  Sub text-->
    <tr style="margin: 0; padding: 0">
        <td style="margin: 0; padding: 0" colspan="4">
            <p style="padding: 0;margin: 5px;text-align: center;font-size: 12px; font-family: Arial,serif;color: rgb(154,154,154);">
                {{$company->getAttribute("about")}}
            </p>
        </td>
    </tr>
    <tr>
        <!--            <td>a</td>-->
        <!--            <td>a</td>-->
        <!--            <td>a</td>-->
    </tr>
    </tbody>
</table>