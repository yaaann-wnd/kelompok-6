<!--
=========================================================
* Soft UI Dashboard - v1.0.6
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php
include '../admin/config.php';
session_start();
$date = new DateTime('now');
$date7 = new DateTime('now');
// echo $date->format('Y-m-d');
$tgl=$date->format('Y-m-d');
$date7->modify('+7 day');
$tgl7=$date7->format('Y-m-d') . "\n";

// echo $tgl;
if (!isset($_SESSION['username']) && !isset($_SESSION['nip'])) {
    header('location:../index.php');
}
$kode = $_GET['id'];
$result = mysqli_query($db, "SELECT * FROM detail_peminjaman JOIN buku JOIN peminjaman join siswa join kelas ON detail_peminjaman.id_buku=buku.id_buku AND detail_peminjaman.id_peminjaman=peminjaman.id_peminjaman and peminjaman.id_siswa=siswa.nis AND siswa.id_kelas=kelas.id_kelas WHERE peminjaman.id_peminjaman='$kode';;");
while($data = mysqli_fetch_array($result)){
    $kodepinjam=$data['id_peminjaman'];
    $judul = $data['judul'];
    $cover = $data['cover'];
    $penulis = $data['penulis'];
    $namasiswa = $data['nama'];
    $tglpinjam = $data['tgl_pinjam'];
    $total = $data['kuantitas'];
    $kodebuku = $data['id_buku'];
    $namakelas = $data['nama_kelas'];
}
$result = mysqli_query($db, "SELECT * FROM siswa ");


if (isset($_POST['submit'])) {
    // echo "<script>alert('asd')</script>";
    # code...
    // $nis = $_POST['nis'];
    // $total = $_POST['total'];
    // echo $_POST['nis'];
    
    if ($_POST['ada']==null || $_POST['hilang']==null|| $_POST['denda']==null ){
        echo "<script>alert('Tolong isi semua field')</script>";
    }else{
        $nis = $_POST['nis'];
        $total = $_POST['total'];
        $petugas = $_SESSION['username'];
        $ada = $_POST['ada'];
        $hilang = $_POST['hilang'];
        $denda = $_POST['denda'];
        $tanggal = $_POST['tanggal'];
        $kodepinjam2 = $_POST['kodepinjam2'];
        // $cover = $_POST['cover'];
        
        // tambahstock
        $getstock = mysqli_query($db, "SELECT stok FROM `buku` where id_buku = '$kodebuku'") ;
        $getvaluestock = mysqli_fetch_array($getstock);
        $valstock = $getvaluestock['stok']+$total;
        $miststock = mysqli_query($db, "UPDATE `buku` SET `stok` = '$valstock' WHERE `buku`.`id_buku` = '$kodebuku';");
        

        
        $sendd = mysqli_query($db, "INSERT INTO `pengembalian` (`id_pengembalian`, `id_peminjaman`, `tgl_pengembalian`, `denda`) VALUES (NULL, '$kodepinjam2', '$tanggal', '$denda');");
        $dataid = mysqli_query($db, "SELECT id_pengembalian FROM pengembalian ORDER BY id_pengembalian DESC limit 1");
        $id_pengembalian = '';
        if ($sendd) {
            # code...
            while($lastid = mysqli_fetch_array($dataid)){
                $id_pengembalian = $lastid['id_pengembalian'];
            }
            echo $id_pengembalian;
            if($sendd) {
                $sendd2 = mysqli_query($db, "INSERT INTO `detail_pengembalian` (`id_detail_kembali`, `id_pengembalian`, `ada`, `hilang`) VALUES ('', '$id_pengembalian', '$ada', '$hilang');");
                header('location:pengembalian.php');
            }
        }
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <title>Perpustakaan</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
    <!-- bootstrap -->
    <!-- <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"> -->

</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- include sidemenu -->
    <?php include '../sidemenu.php'; ?>
    <!-- end include sidemenu -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Pengembalian</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Pengembalian</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <!-- <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                <input type="text" class="form-control" placeholder="Type here..." /> -->
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="../logout.php" class="nav-link text-danger font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Logout</span>
                            </a>
                        </li>

                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->


        <!-- body content -->
        <div class="container-fluid py-4">
            <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Details</h6>
                        <img src="../bootstrap/img/<?= $cover ?>" class="rounded-4" width="75px" alt="">
                    </div>
                    <!-- s -->
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="container">
                        <form role="form" method="post">
                            <label>Kode Peminjaman</label>
                            <div class="mb-3">
                                <input readonly type="text" value="<?php echo $kodepinjam;?>" name="kodepinjam2" class="form-control" placeholder="NIP" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Judul</label>
                            <div class="mb-3">
                                <input readonly type="text" name="judul" value="<?php echo $judul." - ".$penulis;?>" class="form-control" placeholder="Judul" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>NIS</label>
                            <div class="mb-3">
                                <input readonly type="text" name="judul" value="<?php echo $namasiswa;?>" class="form-control" placeholder="Judul" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Kelas</label>
                            <div class="mb-3">
                                <input readonly type="text" name="kelas" value="<?php echo $namakelas;?>" class="form-control" placeholder="Judul" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Petugas</label>
                            <div class="mb-3">
                                <input readonly type="text" value="<?php echo $_SESSION['nip'];?>" name="username" class="form-control" placeholder="NIP" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Total</label>
                            <div class="mb-3">
                                <input readonly type="number" value="<?php echo $total;?>"  name="total" class="form-control" placeholder="Total" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Tanggal Pinjam</label>
                            <div class="mb-3">
                                <input readonly value="<?php echo $tglpinjam ?>" name="pinjam" class="form-control" placeholder="Tanggal" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Tanggal Pengembalian</label>
                            <div class="mb-3">
                                <input readonly value="<?php echo $tgl ?>" name="tanggal" class="form-control" placeholder="Tanggal" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <!-- <label>Ada</label>
                            <div class="mb-3">
                                <input name="ada" class="form-control" placeholder="Ada" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Hilang</label>
                            <div class="mb-3">
                                <input name="hilang" class="form-control" placeholder="Hilang" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Denda</label>
                            <div class="mb-3">
                                <input name="denda" class="form-control" placeholder="Denda" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            
                            <div class="text-center">
                            <button type="submit" name="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Submit</button>
                            </div> -->
                        </form>
                        </div>
                    </div>
                </div>
            </div>

            </div>
            <!-- <div class="posts-list">data</div> -->

            <!-- end body content -->
            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with <i class="fa fa-heart"></i> by
                                <a href="https://www.promaydo.net" class="font-weight-bold" target="_blank">Promaydo Technology</a>
                                for a better web.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="fa fa-cog py-2"> </i>
        </a>
        <div class="card shadow-lg">
            <div class="card-header pb-0 pt-3">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Soft UI Configurator</h5>
                    <p>See our dashboard options.</p>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="horizontal dark my-1" />
            <div class="card-body pt-sm-3 pt-0">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-0">Sidebar Colors</h6>
                </div>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                    </div>
                </a>
                <!-- Sidenav Type -->
                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>
                <div class="d-flex">
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
                <!-- Navbar Fixed -->
                <div class="mt-3">
                    <h6 class="mb-0">Navbar Fixed</h6>
                </div>
                <div class="form-check form-switch ps-0">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)" />
                </div>
                <hr class="horizontal dark my-sm-4" />
                <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/soft-ui-dashboard">Free Download</a>
                <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/license/soft-ui-dashboard">View documentation</a>
                <div class="w-100 text-center">
                    <a class="github-button" href="https://github.com/creativetimofficial/soft-ui-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/soft-ui-dashboard on GitHub">Star</a>
                    <h6 class="mt-3">Thank you for sharing!</h6>
                    <a href="https://twitter.com/intent/tweet?text=Check%20Soft%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                        <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/soft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                        <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->    
    <!-- end Modal -->
    <!--   Core JS Files   -->
    <script src="..assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>

    <script>
        var win = navigator.platform.indexOf("Win") > -1;
        if (win && document.querySelector("#sidenav-scrollbar")) {
            var options = {
                damping: "0.5",
            };
            Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.6"></script>
</body>

</html>

