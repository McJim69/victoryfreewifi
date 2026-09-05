<?php
	// Active sites
	$qryACT = $link->query("SELECT COUNT(*) AS total FROM sites WHERE status = 1");
	$totACT = ($qryACT && $row = $qryACT->fetch_assoc()) ? number_format($row['total']) : "0";
	$active = "<b class='active-count'>$totACT</b>";

	// Downed sites
	$qryDWN = $link->query("SELECT COUNT(*) AS total FROM sites WHERE status = 0");
	$totDWN = ($qryDWN && $row = $qryDWN->fetch_assoc()) ? number_format($row['total']) : "0";
	$downed = "<b class='downed-count'>$totDWN</b>";

	// Safe defaults for GET parameters
	$barangays  = $_GET["barangays"]   ?? "";
	$municipal  = $_GET["municipality"] ?? "";
	$places     = $_GET["places"]      ?? "";
?>
<style>
    #nav div {
        font-size:15px;
        cursor:pointer;
        background:#fff;
        padding:3px 5px;
        border-radius:5px;
        display:table-cell;
    }
    #nav div:hover {
        background:#ff0000;
        color:#fff;
    }
    #nav #opt {
        font-size:15px;
        padding:3px 5px;
        text-align:center;
        vertical-align:middle;
    }
</style>

<!-- ======= Header ======= -->
<header class="fixed-top header-inner-pages" style="margin-top:55px">
  <div class="container d-flex align-items-center justify-content-between">
    <h1 class="logo"><a href="sites_list.php" class="scrollto" style="color:#fff">SITES</a></h1>
    
    <span id="refresh">
      <a href="sites_status_mon.php">
        <button class="btn btn-light" style="padding:3px 5px;font-size:15px">
          Active: <?php echo $active;?> 
          <i class="fa fa-refresh fa-spin text-primary" aria-hidden="true"></i>
          Down: <?php echo $downed;?> 
        </button>
      </a>
    </span>

    <form method="post" enctype="multipart/form-data">
      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li>
            <input type="text" class="btn btn-light" size="25" placeholder="Type a keyword" 
                   name="t_search" id="t_search"
                   value="<?php echo $_POST["t_search"] ?? ""; ?>">
            <input type="submit" class="btn btn-light" name="b_search" value="Search">
            <input type="button" class="btn btn-light" value="<?php echo $view;?>" onclick="jump('<?php echo $goto;?>')">
            <?php 
              if(isset($_SESSION['user'])){ 
                echo "<input type='button' class='btn btn-light' onclick=\"jump('addRollout.php')\" value='+Add New'>";
              }
            ?>
            <!-- Municipality dropdown -->
            <select class="btn btn-light"
              onchange="if(this.value=='Municipality')jump('<?php echo $file;?>'); 
                        else jump('<?php echo $file;?>?municipality='+this.value+'&barangays=<?php echo $barangays; ?>')">
              <option>Municipality</option>
              <?php
                if ($barangays === "" || $barangays === "Barangays") {
                  $ex2 = $link->query("SELECT mcode FROM sites GROUP BY mcode ORDER BY mcode") or die(mysqli_error($link));
                } else {
                  $ex2 = $link->query("SELECT mcode FROM sites WHERE barangay='$barangays' GROUP BY mcode ORDER BY mcode") or die(mysqli_error($link));
                }
                while ($rs = mysqli_fetch_array($ex2)) {
                  $selected = ($municipal === $rs[0]) ? "selected" : "";
                  echo "<option $selected>$rs[0]</option>";
                }
              ?>
            </select>
            <!-- Barangays dropdown -->
            <select class="btn btn-light"
              onchange="jump('?municipality=<?php echo $municipal; ?>&barangays='+this.value)">
              <option>Barangays</option>
              <?php
                if ($municipal === "" || $municipal === "Municipality") {
                  $ex2 = $link->query("SELECT barangay FROM sites GROUP BY barangay ORDER BY barangay") or die(mysqli_error($link));
                } else {
                  $ex2 = $link->query("SELECT barangay FROM sites WHERE mcode='$municipal' GROUP BY barangay ORDER BY barangay") or die(mysqli_error($link));
                }
                while ($rs = mysqli_fetch_array($ex2)) {
                  $selected = ($barangays === $rs[0]) ? "selected" : "";
                  echo "<option $selected>$rs[0]</option>";
                }
              ?>
            </select>
          </li>
        </ul>
      </nav>
    </form>
  </div>    
</header>
