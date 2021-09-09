<?php

declare(strict_types=1);

namespace App\Domain\Validation;

class AbstractValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * {@inheritDoc}
     */
    public function validate(array $data): ValidationResult
    {
        $result = new ValidationResult();

        foreach ($this->fields as $field => $options) {
            if ($options['required'] == true && isset($data[$field]) == false) {
                $result->addMessage('Field is required: ' . $field);
                $result->setIsValid(false);
            } elseif (isset($data[$field]) == true && in_array(gettype($data[$field]), $options['types']) == false) {
                $result->addMessage(
                    'Field (' . $field . ') type is not valid. Allowed types: ' . implode(',', $options['types'])
                );
                $result->setIsValid(false);
            }
        }

        return $result;
    }
}