<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <?php
//   echo '<pre>', print_r($breadcrumb), '</pre>';
        for ($i = 0; $i < sizeof($breadcrumb); $i++) {
            $active = ($i == sizeof($breadcrumb) - 1) ? 'active' : '';

            $icon = empty($breadcrumb[$i]['icon']) ? '' : '<i class="' . $breadcrumb[$i]['icon'] . '"></i> ';
            $name = $icon . $breadcrumb[$i]['name'];

            if ($active) {
                echo "<li class='breadcrumb-item $active'>$name</li>";
            } else {
                $url = $breadcrumb[$i]['url'];
                echo "<li class='breadcrumb-item $active'><a href='$url'>$name</a></li>";
            }
        }
        ?>
    </ol>
</div>