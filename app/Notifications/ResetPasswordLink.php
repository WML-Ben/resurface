<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordLink extends ResetPassword
{
    public function toMail($notifiable)
    {
        $config = session()->get('config');

        $url = route('forgot_password_reset_link', ['token' => $this->token]);

        $data = [
            'config'    => $config,
            'siteUrl'   => asset('/'),
            'publicUrl' => asset('/'),
            'content'   => '<p>Hello,</p><p>We have sent this email to you because we have received a request to change your password.</p>',
            'aLink'     => $url,
            'aText'     => 'Change Password',
            'content_2' => '<p>If you have not done this request, you don\'t need to do any further action, except you might want to let us know about this situation.</p><p>If you have any problem clicking the button, copy and paste this url in your web browser: ' . $url . '</p>',
            'signer'    => '<p style="margin-top:30px;">Tech Support Team.</p>',
        ];

        return (new MailMessage)
            ->subject('Change Password Link')
            ->from($config['noReplyEmail'] ?? env('MAIL_FROM_ADDRESS'), $config['company'] ?? env('MAIL_FROM_NAME'))
            ->view('emails.notification', $data);
    }
}
