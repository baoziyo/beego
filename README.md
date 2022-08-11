# beego

## 启动

* 安装`vendor`
    - dev `composer install --dev`
    - pro `composer install --no-dev`
* 普通 `php bin/hyperf.php start`
* 热更新 `php bin/hyperf.php server:watch`

## 命令

* 格式化代码 `conposer cs-fix`
* 一键中断某个端口下的所有进程`lsof -ntP -i:9501 | xargs kill -9`

## 单元测试
复制`.env.example`为`.env.testing`
### 命令
* 单元测试 `composer test`
* 生成单元测试覆盖率 `phpdbg -dmemory_limit=1024M -qrr ./vendor/bin/co-phpunit -c phpunit.xml --colors=always`

## 环境指南

| 环境 | 备注     |
| ---- | -------- |
| pro  | 生产环境 |
| uat  | 灰度环境 |
| test | 测试环境 |
| dev  | 开发环境 |
