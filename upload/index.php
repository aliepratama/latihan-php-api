<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image to link converter</title>
</head>
<style>
    #uploadImage{
        display: flex;
        flex-direction: column;
    }
</style>
<body>
    <form action="" method="POST" id="uploadImage" enctype="multipart/form-data">
        <label for="uploader">Pilih file untuk upload Image</label>
        <input type="file" name="uploader" id="uploader" accept="image/*">
        <button type="submit">Submit</button>
    </form>
    <h3>Hasil request:</h3>
    <?php
        function saveRecordImage($image,$name = null){
            try{
                $env = parse_ini_file('../.env');
                $imgbb_key = $env["IMGBB_KEY"];
            }
            catch(Exception $e){
                $imgbb_key = $_ENV["IMGBB_KEY"];
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key='.$imgbb_key);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $extension = pathinfo($image['name'],PATHINFO_EXTENSION);
            $file_name = ($name)? $name.'.'.$extension : $image['name'] ;
            $data = array('image' => base64_encode(file_get_contents($image['tmp_name'])), 'name' => $file_name);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                return 'Error:' . curl_error($ch);
            }else{
                return json_decode($result, true);
            }
            curl_close($ch);
        }
        if (!empty($_FILES['uploader'])) {
            $return = saveRecordImage($_FILES['uploader']);
            echo $return['data']['url'];
        }
    ?>
</body>
</html>