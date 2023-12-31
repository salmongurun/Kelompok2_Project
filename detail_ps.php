<?php
session_start();
require 'koneksi.php';
$sql = "SELECT id_ps, tipe_ps, harga FROM ps";
$result = $koneksi->query($sql);

if (isset($_POST['submit'])) {
    $tipe_ps = $_POST['tipe_ps'];
    $harga = $_POST['harga'];
    $check_sql = "SELECT id_ps FROM ps WHERE id_ps = '$id_ps'";
    $check_result = $koneksi->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo '<script>alert("ID sudah ada. Silakan gunakan ID yang berbeda.")</script>';
    } else {
        $sql = "INSERT INTO ps (tipe_ps, harga) VALUES ('$tipe_ps', '$harga')";
        if ($koneksi->query($sql)) {
            header('Location: detail_ps.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail PS | RENTAL POWER GAMES</title>
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel="stylesheet" href="style.css">
</head>

</head>
<body>
    <nav class="sidebar">
        <header>
            <div class="image-text">
                <span class="image">
                    <i class='bx bxs-joystick icon'></i>
                </span>
                <div class="text logo-text">
                    <span class="name">Rental</span>
                    <span class="profession">Power Games</span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search...">
                </li>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="home.php" id="klikdisini" class="navbar-brand">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="detail_ps.php">
                            <i class='bx bx-joystick-alt icon'></i>
                            <span class="text nav-text">PlayStation</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="booking.php">
                            <i class='bx bxs-book-content icon'></i>
                            <span class="text nav-text">Booking</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="lap_keuangan.php">
                            <i class='bx bx-money-withdraw icon'></i>
                            <span class="text nav-text">Laporan Keuangan</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <i class='bx bx-user-circle icon'></i>
                    <span class="text logo-text profession"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Nama Profile'; ?></span>
                </li>
                <li class="nav-link">
                    <a href="register.php" id="klikdisini" class="navbar-brand">
                        <i class='bx bx-user-plus icon'></i>
                        <span class="text nav-text">Daftar Admin Baru</span>
                    </a>
                </li>
                <li class="" href="login.php" onclick="confirmLogout(event)">
                    <a href="#">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                        <script>
                        function confirmLogout(event) {
                            if (confirm('Yakin untuk logout?')) {
                                window.location.href = 'logout.php';
                            } else {
                                event.preventDefault();
                            }
                        }
                        </script>
                    </a>
                </li>
            </div>
        </div>
    </nav>
    <section class="home">
        <h3 class="text logo-text">Detail Playstation</h3>
        <div>
        <button type="submit" class="btn text-white" id="btn" onclick="openPopup()"><i class='bx bx-plus-circle icon'></i> Tambah Data</button>
        <div class="popup" id="popup">
            <br>
            <h3 id="text2">Formulir Penambahan Data PS</h3>
            <br>
                <form action="" method="POST">
                    <label for="tipe_ps" id="text2">Tipe PS : </label>
                    <select name="tipe_ps" class="form-input" required>
                        <option value="PS1">PS1</option>
                        <option value="PS2">PS2</option>
                        <option value="PS3">PS3</option>
                        <option value="PS4">PS4</option>
                        <option value="PS5">PS5</option>
                    </select><br>
                    <label for="harga" id="text2">Harga Per Jam : </label>
                    <input type="number" name="harga" class="form-input" placeholder="Isi tanpa Rp (diatas 1000)" required min="1000">
                    <br>
                    <br>
                    <button type="button" onclick="closePopup()">Kembali</button>
                    <button onclick="refreshPage()" type="submit" name="submit" id="popupbtn" class="btn text-white">Simpan Data</button>
                    <br>
                </form>
        </div>
    </div>
    <div class="tabular--wrapper">
        <h1 class="main--title">Tabel Playstation</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="text-white">ID PS</th>
                        <th class="text-white">Tipe PS</th>
                        <th class="text-white">Harga Per Jam</th>
                        <th class="text-white">Hapus</th>
                        <th class="text-white">Edit</th>
                    </tr>
                </thead>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<td>" . $row["id_ps"] . "</td>";
                            echo "<td>" . $row["tipe_ps"] . "</td>";
                            echo "<td>" . $row["harga"] . "</td>";
                            echo "<td><a href='#' class='btn-delete' onclick='konfirmasiHapus(" . $row['id_ps'] . ")'></a></td>";
                            echo "<td><a href='edit_ps.php?id=" . $row['id_ps'] . "' class='btn-edit'></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tidak ada data yang ditemukan</td></tr>";
                    }
                ?>
            </table>
        </div>
    </div>
    </section>

    <script>
            let popup = document.getElementById("popup");

            function openPopup(){
                popup.classList.add("open-popup");
                window.addEventListener("scroll", movePopup);
            }

            function closePopup(){
                popup.classList.remove("open-popup");
                window.removeEventListener("scroll", movePopup);
            }

            function refreshPage() {
                location.reload();
            }

            function movePopup() {
            popup.style.top = (window.scrollY + window.innerHeight / 2) + "px";
            popup.style.left = (window.scrollX + window.innerWidth / 2) + "px";
            }

            function konfirmasiHapus(id_ps) {
        if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
            fetch(`cek_status_pemesanan.php?id=${id_ps}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'gagal') {
                        alert("Data tidak dapat dihapus karena memiliki pemesanan yang sudah diproses.");
                    } else {
                        window.location.href = `hapus_ps.php?id=${id_ps}`;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
    </script>
    <script>
        const body = document.querySelector('body'),
        sidebar = body.querySelector('nav'),
        toggle = body.querySelector(".toggle"),
        searchBtn = body.querySelector(".search-box"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");
        if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark");
        modeText.innerText = "Light mode";
        }
        toggle.addEventListener("click" , () =>{
            sidebar.classList.toggle("close");
        })
        searchBtn.addEventListener("click" , () =>{
            sidebar.classList.remove("close");
        })
        modeSwitch.addEventListener("click" , () =>{
            body.classList.toggle("dark");
            if(body.classList.contains("dark")){
                modeText.innerText = "Light mode";
                localStorage.setItem("theme", "dark");
            }else{
                modeText.innerText = "Dark mode";
                localStorage.setItem("theme", "light");
            }
        });
    </script>
    <script>
        function searchTable() {
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    }
    </script>
</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>