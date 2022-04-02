<?php

header('Access_Control-Allow-Origin:*');
header('Content-type:application/json');
header('Access_Control-Allow-Method:POST');
header('Access_Control-Allow-Headers:Origin,Content-Type,Accept'); //handle pre-flight request

include_once('../../models/seller.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check the value
 if($seller->validate_params($_POST['name'])){
     $seller->name = $_POST['name'];
 }else{
     echo json_encode(array('success' => 0, 'message'=> 'Name is required!'));
     die();
 }

 if($seller->validate_params($_POST['email'])){
    $seller->email = $_POST['email'];
}else{
    echo json_encode(array('success' => 0, 'message'=> 'Email is required!'));
    die();
}
if($seller->validate_params($_POST['password'])){
    $seller->password = $_POST['password'];
}else{
    echo json_encode(array('success' => 0, 'message'=> 'Password is required!'));
    die();
}
 $seller_images_folder ='../../assets/seller_images/';
 if(!is_dir($seller_images_folder)){
     mkdir($seller_images_folder);
 }
 if(isset($_FILES['inage'])){
     $file_name = $_FILES['image']['name'];
     $file_tmp = $_FILES['image']['tmp_name'];
     $extension = end(explode('.',$file_name));

     $new_file_name = $seller->email . "_profile" . $extension;
//save 
     move_uploaded_file($file_tmp, $seller_images_folder ."/" . $new_file_name);

     $seller->image = 'seller_images/' . $new_file_name;
 }

if($seller->validate_params($_POST['address'])){
    $seller->address = $_POST['address'];
}else{
    echo json_encode(array('success' => 0, 'message'=> 'Address is required!'));
    die();
}

if($seller->validate_params($_POST['description'])){
    $seller->description = $_POST['description'];
}else{
    echo json_encode(array('success' => 0, 'message'=> 'Description is required!'));
    die();
}
//call the function to check if email exist
 if($seller->check_unique_email()){
    if($id = $seller->register_seller()){
        echo json_encode(array('success' => 1, 'message'=> 'Seller register'));
        
    } else{
        http_response_code(500);
        echo json_encode(array('success' => 0, 'message'=> 'Internal Server Error!'));
    }

 }else{
     http_response_code(401);
     echo json_encode(array('Failed' => 0, 'message'=> 'Email Already exist!'));
    }

}else{
    die(header('HTTP/1.1 405 Request Method Not Allowed'));
}