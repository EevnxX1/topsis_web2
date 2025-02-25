<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <?php if ($_SESSION['status'] == 'admin'): ?>
                <ul id="sidebarnav">
                    <li><a class="waves-effect waves-dark" href="halutama.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                    <li><a class="waves-effect waves-dark" href="pemilik.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Pemilik UNIV</span></a></li>
                    <li><a class="waves-effect waves-dark" href="kriteria.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Bobot Kriteria</span></a></li>
                    <li><a class="waves-effect waves-dark" href="perhitungan.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Hasil Nilai Topsis</span></a></li>
                    <li><a class="waves-effect waves-dark" href="user.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">List Pemakai</span></a></li>
                    <li><a class="waves-effect waves-dark" href="logout.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Logout</span></a></li>
                </ul>
            <?php elseif ($_SESSION['status'] == 'pemilik'): ?>
                <ul id="sidebarnav">
                    <li><a class="waves-effect waves-dark" href="halutama.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                    <li><a class="waves-effect waves-dark" href="biopemilikkos.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Biodata</span></a></li>
                    <li><a class="waves-effect waves-dark" href="inputkamarpemilik.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Input Kamar</span></a></li>
                    <li><a class="waves-effect waves-dark" href="listkamarpemilik.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Daftar Kamar</span></a></li>
                    <li><a class="waves-effect waves-dark" href="logout.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Logout</span></a></li>
                </ul>
            <?php elseif ($_SESSION['status'] == 'user'): ?>
                <ul id="sidebarnav">
                    <li><a class="waves waves-dark" href="halutama.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                    <li><a class="waves waves-dark" href="rangking.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">RANGKING UNIV</span></a></li>
                    <li><a class="waves waves-dark" href="hitungtopsis.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Cari Kriteria</span></a></li>
                    <li><a class="waves waves-dark" href="logout.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Logout</span></a></li>
                </ul>
            <?php endif; ?>
        </nav>
    </div>
</aside>
