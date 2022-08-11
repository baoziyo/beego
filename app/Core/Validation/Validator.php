<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Validation;

use Hyperf\Contract\TranslatorInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\MessageBag;

class Validator extends \Hyperf\Validation\Validator
{
    public function __construct(TranslatorInterface $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->initialRules = $rules;
        $this->translator = $translator;
        $this->customMessages = $messages;
        $this->data = $this->parseData($data);
        $this->customAttributes = $customAttributes;
        $this->setRules($rules);
        $this->registerRules();
    }

    /**
     * Determine if the data passes the validation rules.
     */
    public function passes(): bool
    {
        $this->messages = new MessageBag();
        [$this->distinctValues, $this->failedRules] = [[], []];
        // We'll spin through each rule, validating the attributes attached to that
        // rule. Any error messages will be added to the containers with each of
        // the other error messages, returning true if we don't have messages.
        foreach ($this->rules as $attribute => $rules) {
            $attribute = str_replace('\\.', '->', $attribute);
            foreach ($rules as $rule) {
                if (empty($this->rules[$attribute])) {
                    continue;
                }
                $this->validateAttribute($attribute, $rule);
                if ($this->shouldStopValidating($attribute)) {
                    break;
                }
            }
        }
        // Here we will spin through all of the "after" hooks on this validator and
        // fire them off. This gives the callbacks a chance to perform all kinds
        // of other validation that needs to get wrapped up in this operation.
        foreach ($this->after as $after) {
            $after();
        }
        return $this->messages->isEmpty();
    }

    private function registerRules(): void
    {
        $this->addExtension('timestamp', function ($attribute, $value, $parameters, $validator) {
            return strtotime(date('Y-m-d H:i:s', (int) $value)) === (int) $value;
        });
        $this->addExtension('alpha_num_plus', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-z0-9]*$/', $value) !== 0;
        });
        $this->addExtension('minute_second', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\\d{2}:\\d{2}$/', (string) $value) !== 0;
        });
        $this->addExtension('phone_number_plus', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(13\\d|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[\\d]|19[0-35-9])\\d{8}$/', $value) !== 0;
        });
        $this->addExtension('exclude_rules_if', function ($attribute, $value, $parameters, $validator) {
            $this->requireParameterCount(2, $parameters, 'exclude_rules_if');
            if ($value === $parameters[0]) {
                $excludeFields = $parameters;
                unset($excludeFields[0]);
                $rules = $validator->getRules();
                foreach ($excludeFields as $excludeField) {
                    if (strpos($excludeField, '*') !== false) {
                        foreach ($rules as $key => $rule) {
                            if (preg_match('/^' . str_replace(['.', '*'], ['\\.', '[0-9]*'], str_replace([PHP_EOL, ' '], '', $excludeField)) . '$/', $key)) {
                                unset($rules[$key]);
                            }
                        }
                    } else {
                        unset($rules[str_replace([PHP_EOL, ' '], '', $excludeField)]);
                    }
                }
                $validator->setRules($rules);
            }
            return true;
        });
        $this->addExtension('eq', function ($attribute, $value, $parameters, $validator) {
            $this->requireParameterCount(1, $parameters, 'eq');
            if (Arr::has($validator->getData(), $parameters[0])) {
                return Arr::get($validator->getData(), $parameters[0]) === $value;
            }
            return false;
        });
        $this->addReplacer('eq', function ($message, $attribute, $rule, $parameters, $validator) {
            return str_replace(':value', $validator->customAttributes[$parameters[0]] ?? $parameters[0], $message);
        });
    }
}
