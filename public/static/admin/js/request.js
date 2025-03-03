$.ajaxSetup({
    // 在发送请求前执行的函数
    beforeSend: function(xhr) {
        // 例如添加认证令牌
        //xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    },
    // 请求完成后执行(无论成功或失败)
    complete: function(xhr, status) {
        console.log('请求完成，状态：' + status);
    },
    // 请求成功时的默认处理
    success: function(response) {
        // 通用的响应处理
        if (response.code === 0) {
            // 处理成功响应
            console.log('成功响应：' + response.data);
        } else {
            // 处理业务逻辑错误
            console.log('业务错误：' + response.message);
        }
    },
    // 请求失败时的默认处理
    error: function(xhr, status, error) {
        // 通用的错误处理
        console.log('请求失败，状态：' + status + ', 错误信息：' + error);
    }
});
