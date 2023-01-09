@extends('layouts.main')
@section('title', trans('translates.navbar.structure'))
<link href="{{asset('assets/css/structure.css')}}" rel="stylesheet" type="text/css"/>
@section('content')
<div id="orgChartContainer">
    <div id="orgChart" class="orgChart">
        <table cellpadding="0" cellspacing="0" border="0">
            <tbody>
            <tr>
                <td colspan="2">
                    <div style="background-color: #0A1549 !important;" class="node"><a href="{{route('getInstruction', 15)}}">Vüsal Xəlilov</a>
                    </div>
                </td>
            </tr>
            <tr class="lines">
                <td colspan="2">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                        <tr class="lines x">
                            <td class="line left half"></td>
                            <td class="line right half"></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="lines v">
                <td class="line left half"></td>
                <td class="line right half"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <div class="node"><a href="{{route('getInstruction', 94)}}">Fərhad İbrahimli</a>
                                </div>
                            </td>
                        </tr>
                        <tr class="lines">
                            <td colspan="2">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                    <tr class="lines x">
                                        <td class="line left half"></td>
                                        <td class="line right half"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr class="lines v">
                            <td class="line left half"></td>
                            <td class="line right half"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                    <tr>
                                        <td colspan="14">
                                            <div class="node"><a href="{{route('getInstruction', 123)}}">Zeynəb Mustafayeva</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="lines">
                                        <td colspan="14">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr class="lines x">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="lines v">
                                        <td class="line left"></td>
                                        <td class="line right top"></td>
                                        <td class="line left top"></td>
                                        <td class="line right top"></td>
                                        <td class="line left top"></td>
                                        <td class="line right top"></td>
                                        <td class="line left top"></td>
                                        <td class="line right top"></td>
                                        <td class="line left top"></td>
                                        <td class="line right top"></td>
                                        <td class="line left top"></td>
                                        <td class="line right top"></td>
                                        <td class="line left top"></td>
                                        <td class="line right"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="node"><a href="{{route('getInstruction',26)}}">Qafar Qafarzadə</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 78)}}">Əli Vəliyev</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="node"><a href="{{route('getInstruction', 12)}}">Elvin Hüseynov</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 111)}}">PR üzrə mütəxəssis</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines">
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr class="lines x">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines v">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 37)}}">Marketing üzrə mütəxəssis</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <div class="node">
                                                                                                <a href="{{route('getInstruction', 11)}}">Dizayner</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="node"><a href="{{route('getInstruction', 17)}}">Lamiyə Xəlilova</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 103)}}">Hüquqşunas</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines">
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr class="lines x">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines v">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 124)}}">İ.R üzrə kiçik mütəxəssis</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <div class="node">
                                                                                                <a href="{{route('getInstruction', 19)}}">Ümumi İşlər üzrə mütəxəssis</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="node" style="background-color: #0A1549 !important;"><a href="{{route('getInstruction', 129)}}">Alişan Cəlilov</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="10">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 122)}}">Yaqub Səfərov</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines">
                                                                <td colspan="10">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr class="lines x">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines v">
                                                                <td class="line left"></td>
                                                                <td class="line right top"></td>
                                                                <td class="line left top"></td>
                                                                <td class="line right top"></td>
                                                                <td class="line left top"></td>
                                                                <td class="line right top"></td>
                                                                <td class="line left top"></td>
                                                                <td class="line right top"></td>
                                                                <td class="line left top"></td>
                                                                <td class="line right"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 109)}}">Baş Ofis Üzrə Bəyənnaməçi</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 41)}}">Əhmədbəy İsmixanov</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="4">
                                                                                            <div class="node">
                                                                                                <a href="#">B.B rəis müavini</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines">
                                                                                        <td colspan="4">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr class="lines x">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines v">
                                                                                        <td class="line left"></td>
                                                                                        <td class="line right top"></td>
                                                                                        <td class="line left top"></td>
                                                                                        <td class="line right"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="{{route('getInstruction', 75)}}">Baş Bəyannaməçi</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="#">Böyük Bəyannaməçi-Vakant</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines">
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr class="lines x">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines v">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <div class="node">
                                                                                                                                    <a href="{{route('getInstruction', 76)}}">Bəyannaməçi-4 nəfər</a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines">
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr class="lines x">
                                                                                                                                        <td class="line left half"></td>
                                                                                                                                        <td class="line right half"></td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines v">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div class="node">
                                                                                                                                                <a href="#">Kiçik Bəyannaməçi</a>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="#">Baş Broker-vakant</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="{{route('getInstruction', 139)}}">Broker</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines">
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr class="lines x">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines v">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <div class="node">
                                                                                                                                    <a href="#">Kiçik Broker</a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines">
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr class="lines x">
                                                                                                                                        <td class="line left half"></td>
                                                                                                                                        <td class="line right half"></td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines v">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div class="node">
                                                                                                                                                <a href="{{route('getInstruction', 137)}}">Kassir</a>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node" ><a href="{{route('getInstruction', 86)}}">Bağır Əliyev</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="4">
                                                                                            <div class="node">
                                                                                                <a href="{{route('getInstruction', 110)}}">Namiq Novruzov</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines">
                                                                                        <td colspan="4">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr class="lines x">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines v">
                                                                                        <td class="line left"></td>
                                                                                        <td class="line right top"></td>
                                                                                        <td class="line left top"></td>
                                                                                        <td class="line right"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="#">Baş Bəyannaməçi-Vakant</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="{{route('getInstruction', 84)}}">Böyük Bəyannaməçi</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines">
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr class="lines x">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines v">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <div class="node">
                                                                                                                                    <a href="{{route('getInstruction', 126)}}">Bəyannaməçi-3 nəfər</a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines">
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr class="lines x">
                                                                                                                                        <td class="line left half"></td>
                                                                                                                                        <td class="line right half"></td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines v">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div class="node">
                                                                                                                                                <a href="#">Kiçik Bəyannaməçi-Vakant</a>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="{{route('getInstruction', 87)}}">Baş Broker</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="{{route('getInstruction', 80)}}">Broker</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines">
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr class="lines x">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines v">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <div class="node">
                                                                                                                                    <a href="#">Kiçik Broker-Vakant</a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines">
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr class="lines x">
                                                                                                                                        <td class="line left half"></td>
                                                                                                                                        <td class="line right half"></td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines v">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div class="node">
                                                                                                                                                <a href="#">Kassir</a>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node" ><a href="#">HNBGİ üzrə B.B xidmətinin rəisi</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="4">
                                                                                            <div class="node">
                                                                                                <a href="#">Rəis Müavini</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines">
                                                                                        <td colspan="4">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr class="lines x">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines v">
                                                                                        <td class="line left"></td>
                                                                                        <td class="line right top"></td>
                                                                                        <td class="line left top"></td>
                                                                                        <td class="line right"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="#">Baş Bəyannaməçi-Vakant</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="#">Böyük Bəyannaməçi</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines">
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr class="lines x">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines v">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <div class="node">
                                                                                                                                    <a href="{{route('getInstruction', 93)}}">Bəyannaməçi-2 nəfər</a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines">
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr class="lines x">
                                                                                                                                        <td class="line left half"></td>
                                                                                                                                        <td class="line right half"></td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines v">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div class="node">
                                                                                                                                                <a href="{{route('getInstruction', 113)}}">Kiçik Bəyannaməçi-Vakant</a>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="#">Baş Broker-Vakant</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="{{route('getInstruction', 59)}}">Broker</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines">
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr class="lines x">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr class="lines v">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <table cellpadding="0"
                                                                                                                           cellspacing="0"
                                                                                                                           border="0">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <div class="node">
                                                                                                                                    <a href="#">Kiçik Broker</a>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines">
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr class="lines x">
                                                                                                                                        <td class="line left half"></td>
                                                                                                                                        <td class="line right half"></td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr class="lines v">
                                                                                                                            <td class="line left half"></td>
                                                                                                                            <td class="line right half"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table cellpadding="0"
                                                                                                                                       cellspacing="0"
                                                                                                                                       border="0">
                                                                                                                                    <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div class="node">
                                                                                                                                                <a href="#">Kassir</a>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 18)}}">Müştərilərlə Əlaqə Şöbəsi</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="node"><a href="#">Maliyyə və Mühasibatlıq şöbəsinin rəisi</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 33)}}">Böyük Mühasib-Tülay Məmmədli</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines">
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr class="lines x">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines v">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 39)}}">Mühasib-3 nəfər</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <div class="node">
                                                                                                <a href="{{route('getInstruction', 118)}}">Kiçik Mühasib</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <div class="node">
                                                                                                <a href="{{route('getInstruction', 120)}}">Operator</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="node"><a href="#">Biznesin İnkişafı və Satış Şöbəsi-Vakant</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="4">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left"></td>
                                                    <td class="line right top"></td>
                                                    <td class="line left top"></td>
                                                    <td class="line right"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 20)}}">Uğur İsmayılov</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines">
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr class="lines x">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines v">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="{{route('getInstruction', 130)}}">Satış Mütəxəssisi-2 nəfər</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="{{route('getInstruction', 102)}}">Çağrı Mərkəzi operatoru</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="node"><a href="{{route('getInstruction', 101)}}">Təchizat Şöbəsinin Rəisi-Sabir Tahirov</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="lines">
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr class="lines x">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr class="lines v">
                                                    <td class="line left half"></td>
                                                    <td class="line right half"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="node">
                                                                        <a href="#">Sürücü</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines">
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr class="lines x">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="lines v">
                                                                <td class="line left half"></td>
                                                                <td class="line right half"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="node"><a href="#">Xadimə</a>

                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines">
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr class="lines x">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="lines v">
                                                                            <td class="line left half"></td>
                                                                            <td class="line right half"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <table cellpadding="0" cellspacing="0"
                                                                                       border="0">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <div class="node">
                                                                                                <a href="#">Xadimə</a>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines">
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr class="lines x">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="lines v">
                                                                                        <td class="line left half"></td>
                                                                                        <td class="line right half"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <table cellpadding="0"
                                                                                                   cellspacing="0"
                                                                                                   border="0">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <div class="node">
                                                                                                            <a href="#">Xadimə</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines">
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr class="lines x">
                                                                                                                <td class="line left half"></td>
                                                                                                                <td class="line right half"></td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr class="lines v">
                                                                                                    <td class="line left half"></td>
                                                                                                    <td class="line right half"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <table cellpadding="0"
                                                                                                               cellspacing="0"
                                                                                                               border="0">
                                                                                                            <tbody>
                                                                                                            <tr>
                                                                                                                <td colspan="2">
                                                                                                                    <div class="node">
                                                                                                                        <a href="#">Xadimə</a>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


@endsection

