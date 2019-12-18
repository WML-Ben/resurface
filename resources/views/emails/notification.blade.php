<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body style="padding:0; margin:0;font-family:Verdana,Arial;line-height:150%;">
<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="140" style="background-color:#5bba81;padding-top:5px;border-bottom:1px solid #f1f8f4;">
            <a href="{{ $siteUrl }}" style="text-decoration:none;">
                <img height="40" src="{{ $publicUrl }}/images/{{ $config['logo_transparent'] }}" alt="{{ $config['logo_transparent'] }}" border="0" style="display: block; padding: 10px 0 13px 20px;" />
            </a>
        </td>
        <td width="460" style="text-align:left;background-color:#5bba81;padding-top:5px;border-bottom:1px solid #f1f8f4;">
            <a href="{{ $siteUrl }}" style="text-decoration:none;">
                <span style="font-size:22px;color:#D6B161"></span>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="color: #181818; background: #f1f8f4; text-align: left; padding: 20px 40px 30px;">
            <div style="font-size:14px;">{!! $content !!}</div>
            @if (!empty($aLink))
                <div style="font-size:14px;"><a href="{{ $aLink }}" style="{!! $aStyle ?? 'font-size:16px;display:inline-block;margin-top:30px;margin-bottom:30px;padding:8px 24px;background-color:#119b49;color:#fff;text-decoration:none;' !!}">{{ (!empty($aText)) ? $aText : $aLink }}</a></div>
            @endif
            @if (!empty($content_2))
                <div style="font-size:14px;">{!! $content_2 !!}</div>
            @endif
            @if (!empty($signer))
                <div style="font-size:14px;padding-top: 10px;">{!! $signer !!}</div>
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2" style="background-color: #3ea568; padding: 4px 0; vertical-align: middle;border-top:1px solid #f1f8f4;">
            <p style="font-weight: bold; text-align: center; color:#ECECEC; font-size: 11px; padding: 0; margin: 0;">&copy; {{ date('Y') }} &bull; <a href="{{ $siteUrl }}" style="color: #ECECEC; text-decoration: none">{{ $config['company'] }}</a></p>
        </td>
    </tr>
</table>
</body>