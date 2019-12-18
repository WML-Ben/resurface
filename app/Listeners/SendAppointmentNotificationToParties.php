<?php

namespace App\Listeners;

use App\Events\NewAppointmentWasScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentNotificationToParties
{
    public function __construct()
    {
        //
    }

    public function handle(NewAppointmentWasScheduled $event)
    {
        $config = \App\Config::fetch();

        if (!empty($config['sendNotificationEmails'])) {
            $proposal = $event->proposal;
            $appointmentData = $event->appointmentData;
            $services = $event->services;

            $property = $proposal->property;
            $salesManager = $proposal->salesManager;
            $salesPerson = $proposal->salesPerson;
            $contactManager = $proposal->manager;

            $config = \App\Config::fetch();

            $siteUrl = rtrim(env('APP_URL'), '/');
            $s3 = env('S3_ACTIVE', false);
            $s3Url = env('S3_URL', false);
            $s3Public = env('S3_PUBLIC', false);
            $protocol = \Request::secure() ? 'https://' : 'http://';
            $publicUrl = ($s3 && $s3Public) ? $protocol . $s3Url . '/public' : $siteUrl;

            // Send notification to Sales Manager:

            $to = env('EMAIL_LOCAL', false) ? 'user@localhost.com' : (env('APP_ENV') == 'production' ? $salesManager->email : $config['adminEmail']);

            $subject = 'New appointment scheduled.';

            $content  = '<p>Dear '. $salesManager->fullName .',</p>';
            $content .= '<p>We have setup an appointment for you to discuss our services:</p>';
            $content .= '<p>'. $contactManager->fullName;
            $content .= '<br>'. $property->name;
            $content .= '<br>'. $property->location;
            $content .= '<br>'. $property->phone;
            $content .= '<br>'. $property->email;
            $content .= '</p>';
            $content .= '<p>The client has indicated that they are interested in:</p>';
            $content .= '<p>'. implode('<br>', $services) .'</p>';
            $content .= '<p>Please contact the client as soon as possible to confirm this appointment, time and place.</p>';

            $signer = '<p>'. $config['CEO'] .'<br>'. $config['phone'] .'</p>';

            $tags = [
                'mail_from_address' => $config['noReplyEmail'] ?? env('MAIL_FROM_ADDRESS'),
                'mail_from_name'    => $config['company'] ?? env('MAIL_FROM_NAME'),

                'config'    => $config,
                'siteUrl'   => $siteUrl,
                'publicUrl' => $publicUrl,

                'subject'   => $subject,
                'content'   => $content,

                'signer'    => $signer,
            ];
            $message = \Mail::to($to);
            if (env('APP_ENV') == 'production' && !empty($salesPerson->email)) {
                $message->cc($salesPerson->email);
            }
            $message->send(new \App\Mailer\JvcMailer('emails.notification', $tags));

            // send notification to Contact Manager:

            $to = env('EMAIL_LOCAL', false) ? 'user@localhost.com' : (env('APP_ENV') == 'production' ? $contactManager->email : $config['adminEmail']);

            $subject = 'New appointment scheduled.';

            $content  = '<p>Dear  '. $contactManager->fullName .',</p>';
            $content .= '<p>Thank you for contacting All Paving.</p>';
            $content .= '<p>We have setup an appointment for you to meet with our sales manager '. $salesManager->fullName .'.</p>';
            $content .= '<p>The appointment is set for '. $appointmentData['started_at']->format('l F jS \a\t g:i A') .'.</p>';
            $content .= '<p>Any problems, questions please don\'t hesitate to contact us.</p>';

            $signer  = '<p>'. $config['company'];
            $signer .= '<br>'. $salesManager->fullName;
            $signer .= '<br>'. $salesManager->email;
            $signer .= '<br>'. $config['phone'] .'</p>';

            $tags = [
                'mail_from_address' => $config['noReplyEmail'] ?? env('MAIL_FROM_ADDRESS'),
                'mail_from_name'    => $config['company'] ?? env('MAIL_FROM_NAME'),

                'config'    => $config,
                'siteUrl'   => $siteUrl,
                'publicUrl' => $publicUrl,

                'subject'   => $subject,
                'content'   => $content,

                'signer'    => $signer,
            ];
            \Mail::to($to)->send(new \App\Mailer\JvcMailer('emails.notification', $tags));
        }
    }
}
