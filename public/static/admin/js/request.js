/**
 * 通用请求处理类
 */
const Request = {
    // 默认配置
    config: {
        showLoading: true,      // 是否显示加载层
        showError: true,        // 是否显示错误提示
        showSuccess: true,      // 是否显示成功提示
        successCode: 1,         // 成功状态码
        errorCallback: null,    // 错误回调
        successCallback: null,  // 成功回调
    },

    /**
     * 发送请求
     * @param {Object} options 请求配置项
     * @returns {Promise}
     */
    send(options) {
        // 合并配置
        const opts = Object.assign({}, this.config, options);
        let loadIndex;

        // 显示加载层
        if (opts.showLoading) {
            loadIndex = layer.load(1);
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                url: opts.url,
                type: opts.type || 'POST',
                data: opts.data || {},
                dataType: opts.dataType || 'json',
                success: (res) => {
                    // 关闭加载层
                    opts.showLoading && layer.close(loadIndex);

                    if (res.code === opts.successCode) {
                        // 成功提示
                        opts.showSuccess && layer.msg(res.msg || '操作成功', {icon: 1});
                        // 执行成功回调
                        opts.successCallback && opts.successCallback(res);
                        resolve(res);
                    } else {
                        // 错误提示
                        opts.showError && layer.msg(res.msg || '操作失败', {icon: 2});
                        // 执行错误回调
                        opts.errorCallback && opts.errorCallback(res);
                        reject(res);
                    }
                },
                error: (xhr, status, error) => {
                    // 关闭加载层
                    opts.showLoading && layer.close(loadIndex);
                    // 错误提示
                    opts.showError && layer.msg('网络错误', {icon: 2});
                    reject({code: -1, msg: error});
                }
            });
        });
    },

    /**
     * POST请求
     * @param {string} url 请求地址
     * @param {Object} data 请求数据
     * @param {Object} options 配置项
     * @returns {Promise}
     */
    post(url, data = {}, options = {}) {
        return this.send({
            url,
            data,
            type: 'POST',
            ...options
        });
    },

    /**
     * GET请求
     * @param {string} url 请求地址
     * @param {Object} data 请求数据
     * @param {Object} options 配置项
     * @returns {Promise}
     */
    get(url, data = {}, options = {}) {
        return this.send({
            url,
            data,
            type: 'GET',
            ...options
        });
    }
};