<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center">
            <table align="center" border="0" bgcolor="#f5f5f5" cellpadding="0" cellspacing="0" class="display-width"
                   width="640">
                <tr>
                    <td align="center" style="padding:15px;">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff"
                               class="display-width" width="600" style="padding:30px;">
                            <tr>
                                <td
                                        style="color:#25282A; font-family:Segoe UI, Arial, Helvetica, sans-serif; font-size:16px; font-weight:700;">
                                    Hello {{ $user->username }},
                                </td>
                            </tr>
                            <tr>
                                <td height="40"></td>
                            </tr>
                            <tr>
                                <td
                                        style="color:#25282A; font-family:Segoe UI, Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal;line-height: 1.5;">
                                    To reset the password click on the following link:</td>
                            </tr>
                            <tr>
                                <td height="30"></td>
                            </tr>
                            <tr>
                                <td
                                        style="text-align: center;  text-decoration: none; font-size: 16px; background: #000;
                                        color: #fff; padding: 10px; width: 200px; display: block; margin: auto; letter-spacing: 1px; text-transform: unset; border-radius: 4px;">
                                    <a style="text-decoration: none; color: #FFF; font-weight: bold; text-transform: uppercase; font-size: 70%;"
                                       href="{{ $reset_url }}reset-password?email={{$user->email}}&token={{ $token }}">
                                        Set New Password
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
