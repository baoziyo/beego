# 注解过滤返回参数

## 说明
若配置了过滤器参数 `$fields = [];` 则只保留过滤器参数内的参数。
有两个拓展方法可以复写。

## 食用方法
`@ResponseFilterAnnotations(class=\App\Controller\Filter\IndexFilter::class, mode="simple", code=2010, message="获取成功。")`

1. `class` 为过滤器配置，包含了`simpleFields()` 和 `complexFields()`两个方法，如果有需求即可复写父类方法覆盖。
2. `mode` 为过滤模式 默认为 `simple`
3. `code` 为返回的`code` 默认为 `200`
4. `message` 为返回的`message` 默认为 `获取成功。`
