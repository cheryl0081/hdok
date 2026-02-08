<?php

// Cloudflare Worker URL
$worker_url = 'https://odfked5f2.fodskej021.workers.dev'; // Worker 地址，例如 /cloud1/example.jpg

// 从请求中获取路径信息
$resource = $_GET['resource']; // 假设请求像这样：your-site.com/resource?resource=cloud1/example.jpg

// 构建 Worker 的完整 URL
$worker_request_url = $worker_url . '/' . $resource;

// 使用 cURL 发起请求到 Worker
$ch = curl_init();

// 设置请求的 URL
curl_setopt($ch, CURLOPT_URL, $worker_request_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取响应而非直接输出

// 如果需要身份验证，添加请求头
$headers = [
    'Authorization: Basic ' . base64_encode('admin:123456') // 如果 Worker 使用 Basic Auth
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// 执行请求并获取响应
$response = curl_exec($ch);

// 检查是否有错误
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // 设置返回的文件类型，根据 Worker 返回的类型动态设置 Content-Type
    header('Content-Type: ' . curl_getinfo($ch, CURLINFO_CONTENT_TYPE));

    // 返回资源给客户端
    echo $response;
}

// 关闭 cURL 连接
curl_close($ch);
