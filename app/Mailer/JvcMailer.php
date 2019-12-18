<?php

namespace App\Mailer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JvcMailer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $blade_view, $tags;

    public function __construct($bladeView, $tags)
    {
        $this->blade_view = $bladeView;
        $this->tags = $tags;
    }

    public function build()
    {
        return $this
            ->from($this->tags['mail_from_address'], $this->tags['mail_from_name'])
            ->subject($this->tags['subject'])
            ->view($this->blade_view)
            ->with($this->tags);
    }
}
/* Example of use:

    $url = route('password.reset', ['token' => $this->token]);

    $config = \App\Config::fetch();

    $siteUrl = rtrim(env('SITE_URL'), '/');
    $s3 = env('S3_ACTIVE', false);
    $s3Url = env('S3_URL', false);
    $s3Public = env('S3_PUBLIC', false);
    $protocol = \Request::secure() ? 'https://' : 'http://';
    $publicUrl = ($s3 && $s3Public) ? $protocol . $s3Url . '/public' : $siteUrl;
    $imagesUrl = $publicUrl . '/frontend/img';

    $to = env('EMAIL_LOCAL', false) ? 'user@localhost.com' : $notifiable->email;

    $subject = 'Enlace para crear o cambiar contraseña.';

    $content = '<p>Hola {{ $notifiable->fullName }},</p>';
    $content .= '<p>Te hemos enviado este correo electrónico porque hemos recibido una solicitud de cambio de contraseña. Para completar esta acción, haz clic en el botón mostrado debajo:</p>';

    $content_2 = '<p>Si no fuiste tú quien hizo esta solicitud, no tienes que realizar ninguna acción.</p>';
    $content_2 .= '<p>Si tienes alguna dificultad dando clic en el botón, copia y pega este enlace en tu navegador: ' . $url . '</p>';

    $tags = [
        'subject'           => $subject,
        'mail_from_address' => $config['noReplyEmail'] ?? env('MAIL_FROM_ADDRESS'),
        'mail_from_name'    => $config['company'] ?? env('MAIL_FROM_NAME'),

        'config'            => $config,
        'siteUrl'           => $siteUrl,
        'publicUrl'         => $publicUrl,
        'imagesUrl'         => $imagesUrl,

        'content'           => $content,
        'aLink'             => $url,
        'aText'             => 'Crear / Cambiar Contraseña',
        'aStyle'            => 'text-decoration:none;display:inline-block;padding:12px 20px;background-color:#6c3ca8;color:#FFF;letter-spacing:1px;text-transform:uppercase;font-size:13px;margin-top:10px;margin-bottom:10px;',
        'content_2'         => $content_2,
        'signer'            => '<p style="margin-top:30px;">Equipo de Soporte Técnico.</p>',

    ];

    return \Mail::to($to)->send(new \App\Mailer\JvcMailer('emails.notification', $tags));

    // or

    return \Mail::to($to)->later($delay, new \App\Mailer\JvcMailer('emails.notification', $tags));
*/