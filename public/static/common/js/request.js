layui.define(['jquery', 'layer'], function(exports){
    var $ = layui.jquery;
    var layer = layui.layer;

    var Request = {
        // GET请求
        get(url, params = {}) {
            return this.request('GET', url, params);
        },

        // POST请求
        post(url, data = {}) {
            return this.request('POST', url, data);
        },

        // 统一请求处理
        request(method, url, data = {}) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        layer.load();
                    },
                    success: function(res) {
                        if (res.code === 0) {
                            resolve(res.data);
                        } else if (res.code === -1) {
                            // 未授权处理
                            layer.msg('登录已过期，请重新登录', {icon: 2});
                            // 判断是否在iframe中
                            if (window.self !== window.top) {
                                window.parent.location.href = '/admin/login';
                            } else {
                                window.location.href = '/admin/login';
                            }
                        } else {
                            layer.msg(res.msg || '操作失败', {icon: 2});
                            reject(res);
                        }
                    },
                    error: function(xhr, status, error) {
                        layer.msg('网络错误，请稍后重试', {icon: 2});
                        reject(error);
                    },
                    complete: function() {
                        layer.closeAll('loading');
                    }
                });
            });
        }
    };

    // 导出模块
    exports('request', Request);
});