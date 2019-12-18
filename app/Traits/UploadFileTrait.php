<?php

trait UploadFileTrait
{
    use StringTrait;

    public function uploadFile($fileFieldName, $destinationPath, $params = [])
    {
        $oldFileName = (!empty($params['oldFileName'])) ? $params['oldFileName'] : null;

        if ( ! Request::hasFile($fileFieldName)) {
            return ($oldFileName) ? $oldFileName : null;
        }

        $file = Request::file($fileFieldName);

        if ( ! $file->isValid()) {
            return ($oldFileName) ? $oldFileName : null;
        }

        $fileName = $this->cleanFileName($file->getClientOriginalName());

        if (env('S3_ACTIVE', false)) {
            $file->move(storage_path() .'/tmp/', $fileName);
            Storage::disk('s3')->put('public'. $destinationPath . $fileName, file_get_contents(storage_path().'/tmp/'.$fileName));
            if (empty($params['keepLocalFileIfS3'])) {
                unlink(storage_path() .'/tmp/'.$fileName);
            }
        } else {
            $file->move(public_path() . $destinationPath, $fileName);
        }

        return $fileName;
    }

}