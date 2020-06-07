<?php
include ("koneksi.php");
$db = new dbObj();
$connection=$db->getConnstring();
$requestnya=$_SERVER["REQUEST_METHOD"];
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);


switch($requestnya){
    case 'GET':
    if(!empty($uri_segments[3])){
        $nim= strval($uri_segments[3]);
        get_mahasiswa($nim);
        } else {
            get_mahasiswa();
        }
    break;
    case 'POST':
    insert_mahasiswa();
    break;
    case 'PUT':
        $nim=strval($uri_segments[3]);
        update_mahasiswa($nim);
    break;
    case 'DELETE':
        $nim=strval($uri_segments[3]);
        delete_mahasiswa($nim);
    break;
    default:
    header("HTTP/1.0 405 Method Tidak Terdaftar");
break;
    
}

function get_mahasiswa($nim=''){
    global $connection;
    $query="SELECT * FROM tb_mahasiswa";
    if($nim!=''){
    $query .=" WHERE nim='".$nim."'LIMIT 1"; 
}
    $response=array();
    $result=mysqli_query($connection, $query);
    $n=0;
    while($row=mysqli_fetch_array($result)){
        $response[$n]['nim']=$row['nim'];
        $response[$n]['nama']=$row['nama'];
        $response[$n]['angkatan']=$row['angkatan'];
        $response[$n]['semester'] = $row['semester'];
        $response[$n]['ipk']=$row['ipk'];
        $n++;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function insert_mahasiswa(){
    global $connection;
    $data=json_decode(file_get_contents('php://input'),true);

    $query = "insert into tb_mahasiswa set nim = '".$data['nim']."', nama='".$data['nama']."', angkatan='".$data['angkatan']."', semester='".$data['semester']."', ipk='".$data['ipk']."'";
    if(mysqli_query($connection, $query)){
        $response = array('kode'=>1, 'status'=>'Data Mahasiswa Berhasil Ditambah');
      } else {
        $response = array('kode'=>0, 'status'=>'Data Mahasiswa Gagal Ditambah');
      }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function update_mahasiswa($nim){
    global $connection;
    $data=json_decode(file_get_contents('php://input'),true);
    $nama=$data['nama'];
    $angkatan=$data['angkatan'];
    $semester = $data['semester'];
    $ipk=$data['ipk'];


    $query="UPDATE tb_mahasiswa SET  nama='".$nama."', angkatan='".$angkatan."', semester='".$semester."', ipk= '".$ipk."' WHERE nim='" .$nim."'";
    if(mysqli_query($connection, $query)){
        $response=array('kode'=>1, 'status'=>'Data Mahasiswa Berhasil Diupdate');
    }else{
        $response=array('kode'=>0, 'status'=> 'Data Mahasiswa Gagal Diupdate');
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    }

function delete_mahasiswa($nim){
    global $connection;
    $query="DELETE FROM tb_mahasiswa WHERE nim='" .$nim."'";
    if(mysqli_query($connection, $query)){
        $response=array('kode'=>1, 'status'=>'Data Mahasiswa Berhasil Dihapus');
    }else{
        $response=array('kode'=>0, 'status'=>'Data Mahasiswa Gagal Dihapus');
    }
    header('Content-Type: aplication/json');
    echo json_encode($response);
}

?>