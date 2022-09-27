<?php

    session_start();
    require('al-admin/core.php');

    if(empty($_SESSION['userId'])){
       header('Location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<?php include('al-includes/head.php');?>

<body>

<!--*** Preloader ***-->
<?php include('al-includes/preloader.php');?>

    <!--*** Main wrapper start ***-->
    <div id="main-wrapper">

        <?php include('al-includes/navbar.php');?>

        <!--*** Content body start ***-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <!-- Add Order -->
                <div class="modal fade" id="addOrderModalside">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Event</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Event Name</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Event Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Description</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="row">
                            <!--
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header border-0 pb-sm-0 pb-5">
                                        <h4 class="fs-20">Recent Event List</h4>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div data-toggle="dropdown">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 12.9999C12.5523 12.9999 13 12.5522 13 11.9999C13 11.4477 12.5523 10.9999 12 10.9999C11.4477 10.9999 11 11.4477 11 11.9999C11 12.5522 11.4477 12.9999 12 12.9999Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 5.99994C12.5523 5.99994 13 5.55222 13 4.99994C13 4.44765 12.5523 3.99994 12 3.99994C11.4477 3.99994 11 4.44765 11 4.99994C11 5.55222 11.4477 5.99994 12 5.99994Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 19.9999C12.5523 19.9999 13 19.5522 13 18.9999C13 18.4477 12.5523 17.9999 12 17.9999C11.4477 17.9999 11 18.4477 11 18.9999C11 19.5522 11.4477 19.9999 12 19.9999Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="javascript:void(0);">Details</a>
                                                <a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="event-bx owl-carousel">
                                            <div class="items">
                                                <div class="image-bx">
                                                    <img src="images/events/1.png" alt="">
                                                    <div class="info">
                                                        <p class="fs-18 font-w600"><a href="event-detail.html" class="text-black">International Live Choice Festivals (2020)</a></p>
                                                        <span class="fs-14 text-black d-block mb-3">Manchester, London</span>
                                                        <p class="fs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad mini</p>
                                                        <ul>
                                                            <li><i class="las la-dollar-sign"></i>$124,00</li>
                                                            <li><i class="las la-calendar"></i>June 20, 2020</li>
                                                            <li><i class="fa fa-ticket"></i>561 pcs</li>
                                                            <li><i class="las la-clock"></i>08:35 AM</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="items">
                                                <div class="image-bx">
                                                    <img src="images/events/3.png" alt="">
                                                    <div class="info">
                                                        <p class="fs-18 font-w600"><a href="event-detail.html" class="text-black">Envato Atuhor Community Fun Hiking at Sibayak Mt.</a></p>
                                                        <span class="fs-14 text-black d-block mb-3">London, United Kingdom</span>
                                                        <p class="fs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad mini</p>
                                                        <ul>
                                                            <li><i class="las la-dollar-sign"></i>$124,00</li>
                                                            <li><i class="las la-calendar"></i>June 20, 2020</li>
                                                            <li><i class="fa fa-ticket"></i>561 pcs</li>
                                                            <li><i class="las la-clock"></i>08:35 AM</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="items">
                                                <div class="image-bx">
                                                    <img src="images/events/1.png" alt="">
                                                    <div class="info">
                                                        <p class="fs-18 font-w600"><a href="event-detail.html" class="text-black">International Live Choice Festivals (2020)</a></p>
                                                        <span class="fs-14 text-black d-block mb-3">Manchester, London</span>
                                                        <p class="fs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad mini</p>
                                                        <ul>
                                                            <li><i class="las la-dollar-sign"></i>$124,00</li>
                                                            <li><i class="las la-calendar"></i>June 20, 2020</li>
                                                            <li><i class="fa fa-ticket"></i>561 pcs</li>
                                                            <li><i class="las la-clock"></i>08:35 AM</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="items">
                                                <div class="image-bx">
                                                    <img src="images/events/2.png" alt="">
                                                    <div class="info">
                                                        <p class="fs-18 font-w600"><a href="event-detail.html" class="text-black">Envato Indonesian Authors Meetup 2020</a></p>
                                                        <span class="fs-14 text-black d-block mb-3">Medan, Indonesia</span>
                                                        <p class="fs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad mini</p>
                                                        <ul>
                                                            <li><i class="las la-dollar-sign"></i>$124,00</li>
                                                            <li><i class="las la-calendar"></i>June 20, 2020</li>
                                                            <li><i class="fa fa-ticket"></i>561 pcs</li>
                                                            <li><i class="las la-clock"></i>08:35 AM</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="items">
                                                <div class="image-bx">
                                                    <img src="images/events/3.png" alt="">
                                                    <div class="info">
                                                        <p class="fs-18 font-w600"><a href="event-detail.html" class="text-black">Envato Atuhor Community Fun Hiking at Sibayak Mt.</a></p>
                                                        <span class="fs-14 text-black d-block mb-3">London, United Kingdom</span>
                                                        <p class="fs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad mini</p>
                                                        <ul>
                                                            <li><i class="las la-dollar-sign"></i>$124,00</li>
                                                            <li><i class="las la-calendar"></i>June 20, 2020</li>
                                                            <li><i class="fa fa-ticket"></i>561 pcs</li>
                                                            <li><i class="las la-clock"></i>08:35 AM</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--*** Content body end ***-->

    </div>
    <!--*** Main wrapper end ***-->

    <!--*** Scripts ***-->
<script src="./vendor/global/global.min.js"></script>
<script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="./vendor/chart.js/Chart.bundle.min.js"></script>
<script src="./js/custom.min.js"></script>
<script src="./js/deznav-init.js"></script>
<script src="./vendor/owl-carousel/owl.carousel.js"></script>

<!-- Chart piety plugin files -->
<script src="./vendor/peity/jquery.peity.min.js"></script>

<!-- Apex Chart -->
<script src="./vendor/apexchart/apexchart.js"></script>

<!-- Dashboard 1 -->
<script src="./js/dashboard/dashboard-1.js"></script>

    <script>
        function carouselReview() {
            jQuery('.event-bx').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                center: true,
                autoplaySpeed: 3000,
                navSpeed: 3000,
                paginationSpeed: 3000,
                slideSpeed: 3000,
                smartSpeed: 3000,
                autoplay: false,
                navText: ['<i class="fa fa-caret-left" aria-hidden="true"></i>', '<i class="fa fa-caret-right" aria-hidden="true"></i>'],
                dots: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    720: {
                        items: 2
                    },

                    1150: {
                        items: 3
                    },

                    1200: {
                        items: 2
                    },
                    1749: {
                        items: 3
                    }
                }
            })
        }

        jQuery(window).on('load', function () {
            setTimeout(function () {
                carouselReview();
            }, 1000);
        });
    </script>
</body>

</html>