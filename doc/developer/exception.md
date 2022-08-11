# 错误处理

## 返回内容

1. 若`.env`内`APP_ENV`为`dev`则返回详情报错内容，若为其他则返回`json`

## 新建异常模块

1. 参考文件`app/Exception/InvalidArgumentException.php`
2. 命名文件内异常代码
3. 抛出异常`throw new InvalidArgumentException(500000001);`

## 代码解释

### 500000001

* 一到三位代表http状态码
* 四到六位代表模块
* 七到九位代表异常代码

### 已知代码

* `403001xxx` 权限异常
* `500000xxx` 系统通用异常
* `500001xxx` 验证码异常
* `500002xxx` 权限组异常
* `500003xxx` 微信异常
* `500004xxx` 用户异常
* `500005xxx` 队列异常
* `500006xxx` 文件异常
