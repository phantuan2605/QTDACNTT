<?php
header('Content-type: text/html; charset=utf-8');


function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
}


$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

$orderInfo = "Thanh toán qua MoMo";
$amount = $_POST['total'];
$orderId = time() ."";
$redirectUrl = "http://localhost/Phan_Van_Tuan/bigshoes/trang-chinh/danh-sach-sp.php";
$ipnUrl = "http://localhost/Phan_Van_Tuan/bigshoes/trang-chinh/danh-sach-sp.php";
$extraData = "";


if (!empty($_POST)) {

    $requestId = time() . "";
    $requestType = "payWithATM";
    //$extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
    //before sign HMAC SHA256 signature
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    $signature = hash_hmac("sha256", $rawHash, $secretKey);
    $data = array('partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature);
    $result = execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);  // decode json


    //luu thong tin vao db


    // require_once ('./hoa-don.php');
    // // Tạo kết nối
    //      $conn = pdo_get_connection();
        
    //      // Tạo ngày mua
    //      $ngaymua = date("d-m-Y");
        
    //      // Câu SQL Insert
    //      $sql = "INSERT INTO hoa_don(ngay_mua,ghi_chu,ma_kh) 
    //              VALUES ('".$ngaymua."','".$ghi_chu."','".$ma_kh."')";
    
    //      // Thực hiện thêm record
    //      $conn->exec($sql);
        
    // //     // Lấy id hóa đơn vừa insert
    //      $ma_hd = $conn->lastInsertId();
        
    //     $items =  $_SESSION['cart'];
    //      foreach($items as $item){
    //          extract($item);
    //          $sql = "INSERT INTO hoa_don_chi_tiet(ma_hd,ma_hh,so_luong,don_gia) VALUES ('".$ma_hd."','".$ma_hh."','".$sl."','".$price."')";
    //          $conn->exec($sql);

            
    //      }

    header('Location: ' . $jsonResult['payUrl']);
        
}
?>