<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ServerMaxFileSize implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->serverFileSizeLimit = $this->getServerUploadFileSizeLimit();
    }

    /**
     * Get server max upload file size
     *
     * @return int
     */
    protected function getServerUploadFileSizeLimit()
    {
        $uploadMax = $this->convertVariableValueToByte((ini_get('upload_max_filesize')));
        $postMax = $this->convertVariableValueToByte((ini_get('post_max_size')));
        $memoryLimit = $this->convertVariableValueToByte((ini_get('memory_limit')));
        return min($uploadMax, $postMax, $memoryLimit);
    }

    /**
     * Get byte value from variable in php.ini (100M => 100*1024*1024)
     * @param string $variable
     * @return int
     */
    protected function convertVariableValueToByte($variable)
    {
        $unitToBytePowValue = array('K'=>1, 'M'=>2, 'G'=>3);
        $unitValue = strtoupper(trim(substr($variable, -1)));

        if (!in_array($unitValue, array_keys($unitToBytePowValue))) {
            return 0;
        }
        $intValue = trim(substr($variable, 0, strlen($variable) - 1));
        if (!intval($intValue) == $intValue) {
            return 0;
        }
        return $intValue * pow(1024, $unitToBytePowValue[$unitValue]);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->getServerUploadFileSizeLimit() > $value->getSize();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.server_max_file_size_message') . ":{$this->serverFileSizeLimit} ";
    }
}
