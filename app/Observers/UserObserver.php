<?php

use Illuminate\Support\Facades\Hash;

class UserObserver
{
    use ActionTrait;

    public function creating($model)
    {
        if (!empty($model->password)) {
            $model->password = Hash::make($model->password);
        }
    }

    public function saved($model)
    {
        if ( ! in_array($this->getActionFunction(), ['togglestatus', 'inlineupdate'])) {
            $oldAvatar = Request::get('old_avatar');
            if ( ! empty($model->avatar) && ($model->avatar != $oldAvatar)) {
                if (env('S3_ACTIVE', false)) {
                    \Storage::disk('s3')->put('media/avatars/'.$model->avatar, file_get_contents(\storage_path().'/tmp/'.$model->avatar));
                    unlink(\storage_path() .'/tmp/'.$model->avatar);
                    if ( ! empty($oldAvatar) && \Storage::disk('s3')->exists('media/avatars/'.$oldAvatar)) {
                        \Storage::disk('s3')->delete('media/avatars/'.$oldAvatar);
                    }
                } else {
                    rename(\storage_path().'/tmp/'.$model->avatar, public_path().'/media/avatars/'.$model->avatar);
                    if ( ! empty($oldAvatar) && file_exists(public_path() .'/media/avatars/'.$oldAvatar)) {
                        unlink(public_path() .'/media/avatars/'.$oldAvatar);
                    }
                }
            }

            $oldSignature = Request::get('old_signature');
            if ( ! empty($model->signature) && ($model->signature != $oldSignature)) {
                if (env('S3_ACTIVE', false)) {
                    \Storage::disk('s3')->put('media/signatures/'.$model->signature, file_get_contents(\storage_path().'/tmp/'.$model->signature));
                    unlink(\storage_path() .'/tmp/'.$model->signature);
                    if ( ! empty($oldSignature) && \Storage::disk('s3')->exists('media/signatures/'.$oldSignature)) {
                        \Storage::disk('s3')->delete('media/signatures/'.$oldSignature);
                    }
                } else {
                    rename(\storage_path().'/tmp/'.$model->signature, public_path().'/media/signatures/'.$model->signature);
                    if ( ! empty($oldSignature) && file_exists(public_path() .'/media/signatures/'.$oldSignature)) {
                        unlink(public_path() .'/media/signatures/'.$oldSignature);
                    }
                }
            }
        }
    }

    public function deleting($model)
    {
        if (env('S3_ACTIVE', false)) {
            if ( ! empty($model->avatar) && \Storage::disk('s3')->exists('media/avatars/'.$model->avatar)) {
                \Storage::disk('s3')->delete('media/avatars/'.$model->avatar);
            }
        } else {
            if ( ! empty($model->avatar) && file_exists(public_path() .'/media/avatars/'.$model->avatar)) {
                unlink(public_path() .'/media/avatars/'.$model->avatar);
            }
        }

        if (env('S3_ACTIVE', false)) {
            if ( ! empty($model->signature) && \Storage::disk('s3')->exists('media/signatures/'.$model->signature)) {
                \Storage::disk('s3')->delete('media/signatures/'.$model->signature);
            }
        } else {
            if ( ! empty($model->signature) && file_exists(public_path() .'/media/signatures/'.$model->signature)) {
                unlink(public_path() .'/media/signatures/'.$model->signature);
            }
        }
    }

}

