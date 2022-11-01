<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Validation;

use App\Core\Biz\Container\Biz;
use App\Exception\InvalidArgumentException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

abstract class BaseValidation
{
    /**
     * @Inject
     */
    protected Biz $biz;

    protected array $rules = [];

    protected array $messages = [];

    protected array $scene = [];

    protected array $customAttributes = [];

    protected ValidatorFactoryInterface $validationFactory;

    public function __construct()
    {
        $this->customAttributes = array_merge($this->customAttributes, $this->extendCustomAttributes());
        $this->messages = array_merge($this->messages, $this->extendMessages());
        $this->rules = array_merge($this->rules, $this->extendRules());
        $this->validationFactory = make(ValidatorFactoryInterface::class);
    }

    public function validation(array $data, string $scene = ''): bool
    {
        $validator = $this->validationFactory->make($data, $this->getRules($scene), $this->messages, $this->customAttributes);
        if ($validator->fails()) {
            throw new InvalidArgumentException(500, $validator->errors()->first());
        }
        return true;
    }

    protected function extendRules(): array
    {
        return [];
    }

    protected function extendMessages(): array
    {
        return [];
    }

    protected function extendCustomAttributes(): array
    {
        return [];
    }

    private function getRules(string $scene = ''): array
    {
        if ($scene === '' || !isset($this->scene[$scene]) || empty($this->scene[$scene])) {
            return $this->rules;
        }
        $sceneRule = [];
        foreach ($this->scene[$scene] as $keyScene => $rowScene) {
            if (is_numeric($keyScene)) {
                // 键是数字;eg:'0'=>'field_name'，直接用rules中的规则
                if (!isset($this->rules[$rowScene])) {
                    throw new InvalidArgumentException(InvalidArgumentException::PARAMETER_LOSS, params: [$rowScene]);
                }
                $sceneRule[$rowScene] = $this->rules[$rowScene];
            }
            if (is_string($keyScene)) {
                // 键是字符串;eg:'field_name'=>'rule_value'，需要合并rules中的规则
                if (!isset($this->rules[$keyScene])) {
                    // 暂时给空操作，如无问题则移除以下注释代码
                    // throw new InvalidArgumentException(InvalidArgumentException::PARAMETER_LOSS, params: [$keyScene]);
                    $this->rules[$keyScene] = '';
                }
                if ($rowScene === $this->rules[$keyScene]) {
                    $sceneRule[$keyScene] = $rowScene;
                } else {
                    $sceneRule[$keyScene] = array_merge_recursive(is_array($rowScene) ? $rowScene : [$rowScene], is_array($this->rules[$keyScene]) ? $this->rules[$keyScene] : [$this->rules[$keyScene]]);
                }
            }
        }
        foreach ($sceneRule as $keyRule => $rowRule) {
            if (is_array($rowRule)) {
                $tempRule = [];
                foreach ($rowRule as $key2 => $row) {
                    if (is_string($row) && strpos($row, '|') !== false) {
                        $temp = explode('|', $row);
                        $tempRule = array_merge($tempRule, $temp);
                        unset($sceneRule[$keyRule][$key2]);
                    } else {
                        $tempRule = array_merge($tempRule, [$row]);
                    }
                }
                $sceneRule[$keyRule] = $tempRule;
            } else {
                $sceneRule[$keyRule] = $rowRule;
            }
        }
        return $sceneRule;
    }
}
