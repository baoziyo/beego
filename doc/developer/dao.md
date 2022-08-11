# Dao

## 判断是否有数据
1. `$dao = xxxDaoImpl::get()`
   1. `$dao->isEmpty()`
2. `$data = xxxService()->getByCache()`
   1. `$data === null`
3. `$data = xxxDaoImpl::value('xx')`
   1. `$data === null`
4. `$data = xxxDaoImpl::first()`
   1. `$data === null`
5.`$data = xxxDaoImpl::pluck()`
   2. `$data->isEmpty()`