<?php

use SebastianBergmann\Environment\Console;
$controllermenu = $this->router->fetch_class();
$functionmenu = uri_string();
$functionmenu2 = $this->router->fetch_method();
$menuprivilegearray = $menuaccess;


//Main master files 
if ($functionmenu2 == 'Useraccount') {
    $addcheck = checkprivilege($menuprivilegearray, 1, 1);
    $editcheck = checkprivilege($menuprivilegearray, 1, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 1, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 1, 4);
} else if ($functionmenu2 == 'Usertype') {
    $addcheck = checkprivilege($menuprivilegearray, 2, 1);
    $editcheck = checkprivilege($menuprivilegearray, 2, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 2, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 2, 4);
} else if ($functionmenu2 == 'Userprivilege') {
    $addcheck = checkprivilege($menuprivilegearray, 3, 1);
    $editcheck = checkprivilege($menuprivilegearray, 3, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 3, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 3, 4);
} else if ($controllermenu == 'Suppliertype') {
    $addcheck = checkprivilege($menuprivilegearray, 4, 1);
    $editcheck = checkprivilege($menuprivilegearray, 4, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 4, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 4, 4);
} else if ($controllermenu == 'Supplier') {
    $addcheck = checkprivilege($menuprivilegearray, 5, 1);
    $editcheck = checkprivilege($menuprivilegearray, 5, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 5, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 5, 4);
} else if ($controllermenu == 'Customer') {
    $addcheck = checkprivilege($menuprivilegearray, 6, 1);
    $editcheck = checkprivilege($menuprivilegearray, 6, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 6, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 6, 4);
} else if ($controllermenu == 'Employee') {
    $addcheck = checkprivilege($menuprivilegearray, 7, 1);
    $editcheck = checkprivilege($menuprivilegearray, 7, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 7, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 7, 4);
} else if ($controllermenu == 'Location') {
    $addcheck = checkprivilege($menuprivilegearray, 8, 1);
    $editcheck = checkprivilege($menuprivilegearray, 8, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 8, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 8, 4);
} else if ($controllermenu == 'Measurements') {
    $addcheck = checkprivilege($menuprivilegearray, 9, 1);
    $editcheck = checkprivilege($menuprivilegearray, 9, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 9, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 9, 4);
} else if ($controllermenu == 'Materialmaincategory') {
    $addcheck = checkprivilege($menuprivilegearray, 10, 1);
    $editcheck = checkprivilege($menuprivilegearray, 10, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 10, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 10, 4);
} else if ($controllermenu == 'Rowmaterials') {
    $addcheck = checkprivilege($menuprivilegearray, 11, 1);
    $editcheck = checkprivilege($menuprivilegearray, 11, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 11, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 11, 4);
} else if ($controllermenu == 'Purchaseorder') {
    $addcheck = checkprivilege($menuprivilegearray, 12, 1);
    $editcheck = checkprivilege($menuprivilegearray, 12, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 12, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 12, 4);
} else if ($functionmenu == 'Purchaseorderstatus') {
    $addcheck = checkprivilege($menuprivilegearray, 13, 1);
    $editcheck = checkprivilege($menuprivilegearray, 13, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 13, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 13, 4);
} else if ($controllermenu == 'Goodreceive') {
    $addcheck = checkprivilege($menuprivilegearray, 14, 1);
    $editcheck = checkprivilege($menuprivilegearray, 14, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 14, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 14, 4);
} else if ($functionmenu == 'SortingGoods') {
    $addcheck = checkprivilege($menuprivilegearray, 15, 1);
    $editcheck = checkprivilege($menuprivilegearray, 15, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 15, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 15, 4);
} else if ($controllermenu == 'Stocktransfer') {
    $addcheck = checkprivilege($menuprivilegearray, 16, 1);
    $editcheck = checkprivilege($menuprivilegearray, 16, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 16, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 16, 4);
} else if ($controllermenu == 'Allstockview') {
    $addcheck = checkprivilege($menuprivilegearray, 17, 1);
    $editcheck = checkprivilege($menuprivilegearray, 17, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 17, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 17, 4);
} else if ($controllermenu == 'materialavailability') {
    $addcheck = checkprivilege($menuprivilegearray, 18, 1);
    $editcheck = checkprivilege($menuprivilegearray, 18, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 18, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 18, 4);
} else if ($controllermenu == 'StockReport') {
    $addcheck = checkprivilege($menuprivilegearray, 19, 1);
    $editcheck = checkprivilege($menuprivilegearray, 19, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 19, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 19, 4);
} else if ($controllermenu == 'Rack') {
    $addcheck = checkprivilege($menuprivilegearray, 20, 1);
    $editcheck = checkprivilege($menuprivilegearray, 20, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 20, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 20, 4);
} else if ($controllermenu == 'SortingAllocate') {
    $addcheck = checkprivilege($menuprivilegearray, 21, 1);
    $editcheck = checkprivilege($menuprivilegearray, 21, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 21, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 21, 4);
} else if ($controllermenu == 'shipmentplaning') {
    $addcheck = checkprivilege($menuprivilegearray, 22, 1);
    $editcheck = checkprivilege($menuprivilegearray, 22, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 22, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 22, 4);
}
else if ($controllermenu == 'Stockmovement') {
    $addcheck = checkprivilege($menuprivilegearray, 23, 1);
    $editcheck = checkprivilege($menuprivilegearray, 23, 2);
    $statuscheck = checkprivilege($menuprivilegearray, 23, 3);
    $deletecheck = checkprivilege($menuprivilegearray, 23, 4);
}

function checkprivilege($arraymenu, $menuID, $type)
{
    foreach ($arraymenu as $array) {
        if ($array->menuid == $menuID) {
            if ($type == 1) {
                return $array->add;
            } else if ($type == 2) {
                return $array->edit;
            } else if ($type == 3) {
                return $array->statuschange;
            } else if ($type == 4) {
                return $array->remove;
            }
        }
    }
}
?>
<textarea class="d-none" id="actiontext"><?php if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
} ?></textarea>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url() . 'Welcome/Dashboard'; ?>">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboard
            </a>

            <?php if (menucheck($menuprivilegearray, 4) == 1 | menucheck($menuprivilegearray, 5) == 1 | menucheck($menuprivilegearray, 6) == 1 | menucheck($menuprivilegearray, 7) == 1 | menucheck($menuprivilegearray, 8) == 1 | menucheck($menuprivilegearray, 9) == 1 | menucheck($menuprivilegearray, 20) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#collapsemaster" aria-expanded="false" aria-controls="collapsemaster">
                    <div class="nav-link-icon"><i class="fa fa-shopping-bag"></i></div>
                    Master Files
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "Suppliertype" | $functionmenu == "Supplier" | $functionmenu == "Customer" | $functionmenu == "Employee" | $functionmenu == "Location" | $functionmenu == "Measurements" | $functionmenu == "Rack") {
                    echo 'show';
                } ?>" id="collapsemaster" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php
                        if (menucheck($menuprivilegearray, 4) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Suppliertype'; ?>">Supplier Category</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 5) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Supplier'; ?>">Suppliers</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 6) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Customer'; ?>">Customers</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 7) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Employee'; ?>">Employees</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 8) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url() . 'Location'; ?>">Stores
                                Location</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 9) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Measurements'; ?>">Measurments</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 9) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url() . 'Rack'; ?>">Zone</a>
                        <?php }
                        ?>
                    </nav>
                </div>
            <?php }
            if (menucheck($menuprivilegearray, 10) == 1 | menucheck($menuprivilegearray, 11) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#collapsematerial" aria-expanded="false" aria-controls="collapsematerial">
                    <div class="nav-link-icon"><i class="fa fa-cogs"></i></div>
                    Material Data
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "Materialmaincategory" | $functionmenu == "Rowmaterials") {
                    echo 'show';
                } ?>" id="collapsematerial" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php if (menucheck($menuprivilegearray, 10) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Materialmaincategory'; ?>">Material Main Category</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 11) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url() . 'Rowmaterials'; ?>">Raw
                                Materials</a>
                        <?php }
                        ?>
                    </nav>
                </div>
            <?php }
            if (menucheck($menuprivilegearray, 12) == 1 | menucheck($menuprivilegearray, 13) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#Purchaseordermenu" aria-expanded="false" aria-controls="Purchaseordermenu">
                    <div class="nav-link-icon"><i class="fa fa-cubes"></i></div>
                    Supplier Purchase Order
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "Purchaseorder" | $functionmenu == "Purchaseorderstatus") {
                    echo 'show';
                } ?>" id="Purchaseordermenu" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php if (menucheck($menuprivilegearray, 12) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Purchaseorder'; ?>">Purchase Order Create
                            </a>
                        <?php }
                        if (menucheck($menuprivilegearray, 13) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'Purchaseorderstatus'; ?>"> Purchase Order status

                            </a>
                        <?php }
                        ?>
                    </nav>
                </div>
            <?php }
            if (menucheck($menuprivilegearray, 14) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#goodReceiveSubmenu" aria-expanded="false" aria-controls="goodReceiveSubmenu">
                    <div class="nav-link-icon"><i class="fa fa-cubes"></i></div>
                    Goods Recieved
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "Goodreceive") {
                    echo 'show';
                } ?>" id="goodReceiveSubmenu" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php if (menucheck($menuprivilegearray, 14) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url() . 'Goodreceive'; ?>">Goods
                                Received Note</a>
                        <?php }
                        ?>
                    </nav>
                </div>
            <?php }
            if (menucheck($menuprivilegearray, 21) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url() . 'SortingAllocate'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-search-dollar"></i></div>
                    Allocation Material for Processing
                </a>
            <?php }
            if (menucheck($menuprivilegearray, 15) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url() . 'SortingGoods'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-search-dollar"></i></div>
                    Sorting Goods
                </a>
            <?php }

            if (menucheck($menuprivilegearray, 23) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url() . 'Stockmovement'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                    Stock movements
                </a>
            <?php }
            if (menucheck($menuprivilegearray, 22) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 text-dark" href="<?php echo base_url() . 'shipmentplaning'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                    Shipment Planning
                </a>
            <?php }

            if (menucheck($menuprivilegearray, 17) == 1 | menucheck($menuprivilegearray, 18) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#collapsematerialavailability" aria-expanded="false"
                    aria-controls="collapsematerialavailability">
                    <div class="nav-link-icon"><i class="fa fa-cubes"></i></div>
                    Stock Management
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "Allstockview" | $functionmenu == "materialavailability") {
                    echo 'show';
                } ?>" id="collapsematerialavailability" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php
                        if (menucheck($menuprivilegearray, 17) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark" href="<?php echo base_url() . 'Allstockview'; ?>">
                                All Material Stock </a>
                        <?php }

                        if (menucheck($menuprivilegearray, 18) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-2 text-dark"
                                href="<?php echo base_url() . 'materialavailability'; ?>">
                                Material Availability
                            </a>
                        <?php }
                        ?>
                    </nav>
                </div>
            <?php }
            if (menucheck($menuprivilegearray, 19) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                    <div class="nav-link-icon"><i class="fa fa-cubes"></i></div>
                    Reports
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "StockReport") {
                    echo 'show';
                } ?>" id="collapseReport" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php
                        if (menucheck($menuprivilegearray, 19) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'StockReport'; ?>">Stock</a>
                        <?php }
                        ?>
                    </nav>
                </div>
            <?php }
            if (menucheck($menuprivilegearray, 1) == 1 | menucheck($menuprivilegearray, 2) == 1 | menucheck($menuprivilegearray, 3) == 1) { ?>
                <a class="nav-link p-0 px-3 py-2 collapsed text-dark" href="javascript:void(0);" data-toggle="collapse"
                    data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                    <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                    User Account
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php if ($functionmenu == "Useraccount" | $functionmenu == "Usertype" | $functionmenu == "Userprivilege") {
                    echo 'show';
                } ?>" id="collapseUser" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <?php if (menucheck($menuprivilegearray, 1) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'User/Useraccount'; ?>">User
                                Account</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 2) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'User/Usertype'; ?>">Type</a>
                        <?php }
                        if (menucheck($menuprivilegearray, 3) == 1) { ?>
                            <a class="nav-link p-0 px-3 py-1 text-dark"
                                href="<?php echo base_url() . 'User/Userprivilege'; ?>">Privilege</a>
                        <?php } ?>
                    </nav>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>